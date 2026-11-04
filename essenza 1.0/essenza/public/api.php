<?php
require __DIR__ . '/../src/database.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $db     = getDB();
    $accion = $_GET['accion'] ?? 'catalogo';

    switch ($accion) {

        case 'catalogo':
            $perfumes = $db->query('
                SELECT p.id, p.nombre, p.marca, p.descripcion, p.notas,
                       p.imagen, c.nombre AS categoria
                FROM perfumes p
                LEFT JOIN categorias c ON c.id = p.categoria_id
                WHERE p.activo = 1
                ORDER BY p.marca, p.nombre
            ')->fetchAll();

            $porPerfume = [];
            foreach ($db->query('SELECT perfume_id, id, ml, precio, stock FROM presentaciones WHERE activo = 1 ORDER BY ml') as $r) {
                $porPerfume[$r['perfume_id']][] = [
                    'id'     => (int) $r['id'],
                    'ml'     => (int) $r['ml'],
                    'precio' => (float) $r['precio'],
                    'stock'  => (int) $r['stock'],
                ];
            }
            foreach ($perfumes as &$p) $p['presentaciones'] = $porPerfume[$p['id']] ?? [];
            echo json_encode(['ok' => true, 'data' => $perfumes]);
            break;

        case 'detalle':
            $id   = (int) ($_GET['id'] ?? 0);
            $stmt = $db->prepare('
                SELECT p.*, c.nombre AS categoria
                FROM perfumes p LEFT JOIN categorias c ON c.id = p.categoria_id
                WHERE p.id = ?
            ');
            $stmt->execute([$id]);
            $perfume = $stmt->fetch();
            if (!$perfume) {
                http_response_code(404);
                echo json_encode(['ok' => false, 'error' => 'Perfume no encontrado']);
                break;
            }
            $stmt = $db->prepare('SELECT id, ml, precio, stock FROM presentaciones WHERE perfume_id = ? AND activo = 1 ORDER BY ml');
            $stmt->execute([$id]);
            $perfume['presentaciones'] = $stmt->fetchAll();
            echo json_encode(['ok' => true, 'data' => $perfume]);
            break;

        case 'crear_pedido':
            $body = json_decode(file_get_contents('php://input'), true);
            if (!$body || empty($body['items'])) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
                break;
            }

            $db->beginTransaction();
            try {
                $stmt = $db->prepare('INSERT INTO clientes (nombre, email, telefono, direccion) VALUES (?, ?, ?, ?)');
                $stmt->execute([
                    $body['cliente']['nombre']    ?? 'Sin nombre',
                    $body['cliente']['email']     ?? null,
                    $body['cliente']['telefono']  ?? null,
                    $body['cliente']['direccion'] ?? null,
                ]);
                $clienteId = (int) $db->lastInsertId();

                $db->prepare('INSERT INTO pedidos (cliente_id, total) VALUES (?, 0)')
                   ->execute([$clienteId]);
                $pedidoId = (int) $db->lastInsertId();

                $stmtPres  = $db->prepare('SELECT precio, stock FROM presentaciones WHERE id = ? AND activo = 1');
                $stmtDet   = $db->prepare('INSERT INTO detalle_pedido (pedido_id, presentacion_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)');
                $stmtStock = $db->prepare('UPDATE presentaciones SET stock = stock - ? WHERE id = ?');

                $total = 0;
                foreach ($body['items'] as $it) {
                    $pid  = (int) $it['presentacion_id'];
                    $cant = (int) $it['cantidad'];
                    if ($cant <= 0) continue;

                    $stmtPres->execute([$pid]);
                    $pres = $stmtPres->fetch();
                    if (!$pres) throw new RuntimeException("Presentación $pid no existe");
                    if ($pres['stock'] < $cant) throw new RuntimeException("Stock insuficiente (presentación $pid)");

                    $subtotal = round($pres['precio'] * $cant, 2);
                    $total   += $subtotal;

                    $stmtDet->execute([$pedidoId, $pid, $cant, $pres['precio'], $subtotal]);
                    $stmtStock->execute([$cant, $pid]);
                }

                $db->prepare('UPDATE pedidos SET total = ? WHERE id = ?')->execute([$total, $pedidoId]);
                $db->commit();
                echo json_encode(['ok' => true, 'pedido_id' => $pedidoId, 'total' => $total]);
            } catch (Throwable $e) {
                $db->rollBack();
                http_response_code(409);
                echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
            }
            break;
                case 'registrar_usuario':
            $body = json_decode(file_get_contents('php://input'), true);
            
            if (empty($body['nombre']) || empty($body['contrasena'])) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Nombre y contraseña obligatorios']);
                break;
            }
            
            try {
                $stmt = $db->prepare('INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)');
                $stmt->execute([
                    $body['nombre'],
                    $body['correo'] ?? null,
                    $body['contrasena']
                ]);
                echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
            } catch (PDOException $e) {
                // Esto te mostrará el error exacto en la consola del navegador si algo falla
                http_response_code(500);
                echo json_encode(['ok' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
            }
            break;

        case 'login':
            $body = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare('SELECT * FROM usuarios WHERE nombre = ?');
            $stmt->execute([$body['nombre'] ?? '']);
            $usuario = $stmt->fetch();
            
            if ($usuario && $usuario['contrasena'] === ($body['contrasena'] ?? '')) {
                echo json_encode(['ok' => true, 'usuario' => $usuario]);
            } else {
                http_response_code(401);
                echo json_encode(['ok' => false, 'error' => 'Contraseña incorrecta']);
            }
            break;
        case 'listar_usuarios':
            $stmt = $db->query('SELECT id, nombre, correo FROM usuarios ORDER BY nombre');
            echo json_encode(['ok' => true, 'data' => $stmt->fetchAll()]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Acción desconocida']);
    }
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}