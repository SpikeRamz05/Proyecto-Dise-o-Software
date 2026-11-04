<?php
function getDB(): PDO
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $ruta    = __DIR__ . '/../db/essenza.db';
    $esNueva = !file_exists($ruta);

    $pdo = new PDO('sqlite:' . $ruta);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');

    if ($esNueva) {
        $pdo->exec(file_get_contents(__DIR__ . '/../db/schema.sql'));
        require __DIR__ . '/seed.php';
        sembrarDatos($pdo);
    }

    return $pdo;
}