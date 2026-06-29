<?php
require_once __DIR__ . '/../config/connect.php';
require_once __DIR__ . '/../app/models/RiwayatKesehatanModel.php';
$m = new RiwayatKesehatanModel();
try {
    $rows = $m->getAll();
    echo "getAll() returned " . count($rows) . " rows\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
