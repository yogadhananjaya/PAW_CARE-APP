<?php
require_once __DIR__ . '/../config/connect.php';
$q = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='paw_care' AND TABLE_NAME='riwayat_kesehatan'");
$cols = $q->fetchAll();
foreach ($cols as $c) {
    echo $c['COLUMN_NAME'] . PHP_EOL;
}
