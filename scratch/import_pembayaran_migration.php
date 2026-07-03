<?php
require 'config/connect.php';
$sql = file_get_contents('database/migrasi_pembayaran.sql');
$lines = preg_split('/;\s*\n/', $sql);
foreach ($lines as $stmt) {
    $stmt = trim($stmt);
    if ($stmt === '') continue;
    if (stripos($stmt, 'use ') === 0) continue;
    $pdo->exec($stmt);
}
echo 'migration_ok';
