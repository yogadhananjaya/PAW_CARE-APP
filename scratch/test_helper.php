<?php
require_once __DIR__ . '/../config/connect.php';

echo "UJI COBA FUNGSI KODE OTOMATIS DI DIREKTORI BARU:\n";
echo "1. Hewan: " . buat_kode_otomatis('hewan', 'kode_hewan', 'HW') . "\n";
echo "2. Pengguna: " . buat_kode_otomatis('pengguna', 'kode_pengguna', 'PG') . "\n";
echo "3. Kandang: " . buat_kode_otomatis('kandang', 'kode_kandang', 'KD') . "\n";
echo "4. Transaksi Adopsi: " . buat_kode_otomatis('transaksi_adopsi', 'kode_transaksi_adopsi', 'TA') . "\n";
?>
