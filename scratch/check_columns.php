<?php
require_once __DIR__ . '/../config/connect.php';
$q = $pdo->query("SHOW COLUMNS FROM pengadopsi");
$cols = $q->fetchAll();
foreach ($cols as $c) {
    echo $c['Field'] . PHP_EOL;
}
