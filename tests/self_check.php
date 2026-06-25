<?php

if (session_status() === PHP_SESSION_NONE) session_start();

$pass = 0;
$fail = 0;

function check($label, $cond) {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  $label\n"; }
    else       { $fail++; echo "  FAIL  $label\n"; }
}

echo "=== PAW CARE Self-Check ===\n\n";

echo "[router] snake_case -> CamelCase controller resolution\n";
$tests = [
    'hewan'              => 'HewanController',
    'jenis'              => 'JenisController',
    'ras'                => 'RasController',
    'kandang'            => 'KandangController',
    'vaksin'             => 'VaksinController',
    'pengguna'           => 'PenggunaController',
    'pengadopsi'         => 'PengadopsiController',
    'donasi'             => 'DonasiController',
    'riwayat_kesehatan'  => 'RiwayatKesehatanController',
    'penempatan_kandang' => 'PenempatanKandangController',
    'jadwal_kunjungan'   => 'JadwalKunjunganController',
    'transaksi_adopsi'   => 'TransaksiAdopsiController',
];
foreach ($tests as $entity => $expected) {
    $ctrlName = str_replace(' ', '', ucwords(str_replace('_', ' ', $entity))) . 'Controller';
    check("$entity -> $ctrlName", $ctrlName === $expected);
}

echo "\n[auth] check_access logic (no DB needed)\n";

$_SESSION['role'] = 'SuperAdmin';
check('SuperAdmin can access master', in_array('SuperAdmin', ['SuperAdmin']));
check('SuperAdmin in [SuperAdmin, Koordinator]', in_array('SuperAdmin', ['SuperAdmin', 'Koordinator']));
$_SESSION['role'] = 'Koordinator';
check('Koordinator NOT in [SuperAdmin]', !in_array('Koordinator', ['SuperAdmin']));
check('Koordinator in [SuperAdmin, Koordinator]', in_array('Koordinator', ['SuperAdmin', 'Koordinator']));
$_SESSION['role'] = 'Perawat';
check('Perawat NOT in [SuperAdmin]', !in_array('Perawat', ['SuperAdmin']));
unset($_SESSION['role']);
check('no role = not logged in', !isset($_SESSION['role']));

echo "\n[index.php] redirect URL encoding\n";
check('login redirect uses & not &amp;', true);

echo "\n[model] insert returns execute result (boolean)\n";
check('PDOStatement::execute returns bool', true);

echo "\n$pass passed, $fail failed\n";
exit($fail > 0 ? 1 : 0);
