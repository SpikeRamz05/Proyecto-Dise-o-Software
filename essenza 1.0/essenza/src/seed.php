<?php
function sembrarDatos(PDO $db): void
{
    $db->beginTransaction();
    try {
        $stmt = $db->prepare('INSERT INTO categorias (nombre) VALUES (?)');
        foreach (['Dama', 'Caballero', 'Unisex'] as $c) $stmt->execute([$c]);

        $perfumes = [
            ['Sauvage EDP',      'Dior',               'Fresco, amaderado',    'Bergamota, pimienta, ambroxan',  2],
            ['Bleu de Chanel',   'Chanel',             'Amaderado, aromático', 'Limón, menta, cedro',            2],
            ['La Vie Est Belle', 'Lancôme',            'Dulce, floral',        'Iris, praliné, vainilla',        1],
            ['Good Girl',        'Carolina Herrera',   'Oriental, dulce',      'Almendra, café, haba tonka',     1],
            ['Acqua di Gio',     'Giorgio Armani',     'Acuático',             'Limón, jazmín, musgo',           2],
            ['Black Opium',      'Yves Saint Laurent', 'Oriental',             'Café, vainilla, flor de azahar', 1],
        ];

        $stmtP    = $db->prepare('INSERT INTO perfumes (nombre, marca, descripcion, notas, categoria_id) VALUES (?, ?, ?, ?, ?)');
        $stmtPres = $db->prepare('INSERT INTO presentaciones (perfume_id, ml, precio, stock) VALUES (?, ?, ?, ?)');

        $precios = [3 => 80.0, 5 => 120.0, 10 => 200.0];

        foreach ($perfumes as $p) {
            $stmtP->execute($p);
            $id = (int) $db->lastInsertId();
            foreach ($precios as $ml => $precio) {
                $factor = 1 + ($id % 5) * 0.05;
                $stmtPres->execute([$id, $ml, round($precio * $factor, 2), random_int(5, 25)]);
            }
        }

        $db->commit();
    } catch (Throwable $e) {
        $db->rollBack();
        throw $e;
    }
}