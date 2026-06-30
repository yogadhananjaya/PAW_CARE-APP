<?php
require_once __DIR__ . '/../config/connect.php';

echo "=== MEMULAI MIGRASI DATABASE PAW CARE (CREATE OR ALTER MODE) ===\n";

// 1. Eksekusi File SQL Utama (schema.sql, schema_master_sisa.sql, schema_transaksi.sql)
// Untuk membuat tabel jika belum ada (CREATE TABLE IF NOT EXISTS)
$sql_files = [
    __DIR__ . '/schema.sql',
    __DIR__ . '/schema_master_sisa.sql',
    __DIR__ . '/schema_transaksi.sql'
];

foreach ($sql_files as $file) {
    if (file_exists($file)) {
        try {
            $sql = file_get_contents($file);

            // Hapus delimiter trigger karena PDO tidak mendukung delimiter multi-statement secara default
            // Kita akan membagi SQL berdasarkan titik koma (;) yang aman
            $queries = explode(';', $sql);
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query) && strpos($query, 'DELIMITER') === false) {
                    $pdo->exec($query);
                }
            }
            echo "Sukses memproses file: " . basename($file) . "\n";
        } catch (Exception $e) {
            echo "Peringatan saat memproses " . basename($file) . ": " . $e->getMessage() . "\n";
        }
    }
}


echo "\n=== MIGRASI DATABASE SELESAI DENGAN SUKSES ===\n";
?>