<?php
$host = 'localhost';
$db   = 'paw_care';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    // 1. Buat koneksi ke database MySQL
    $pdo = new PDO($dsn, $user, $pass);
    
    // 2. Setting agar kalau ada error di database, PHP langsung kasih tahu kita (error dimunculkan)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 3. Setting agar hasil ambil data dari database otomatis jadi bentuk array yang gampang dibaca
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // 4. Setting keamanan agar database tidak mudah diretas/dihack orang jahat
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch (\PDOException $e) {
    // Kalau koneksi gagal, hentikan program dan kasih tahu error-nya apa
    die("Koneksi database gagal: " . $e->getMessage());
}

// Fungsi Helper untuk membuat Kode Alfanumerik Otomatis (Gaya Pemula & Universal)
function buat_kode_otomatis($nama_tabel, $nama_kolom, $prefix) {
    global $pdo;
    
    // Ambil kode terakhir dari tabel tersebut
    $query = "SELECT $nama_kolom FROM $nama_tabel ORDER BY $nama_kolom DESC LIMIT 1";
    $stmt = $pdo->query($query);
    $data = $stmt->fetch();
    
    // Jika belum ada data sama sekali, mulai dari 0001
    if (!$data || empty($data[$nama_kolom])) {
        return $prefix . "0001";
    }
    
    // Ambil string kode terakhir, contoh: "HW0001"
    $kode_terakhir = $data[$nama_kolom];
    
    // Potong prefix (misal: "HW") untuk mengambil angka di belakangnya
    $panjang_prefix = strlen($prefix);
    $angka_saja = substr($kode_terakhir, $panjang_prefix); // Hasil: "0001"
    
    // Tambahkan 1 ke angka tersebut
    $angka_baru = (int)$angka_saja + 1; // Hasil: 2
    
    // Gabungkan kembali prefix dengan angka baru yang diformat 4 digit (misal: "HW0002")
    $kode_baru = $prefix . str_pad($angka_baru, 4, "0", STR_PAD_LEFT);
    
    return $kode_baru;
}

if (!function_exists('get_env_var')) {
    function get_env_var($key, $default = '') {
        // Prioritas gunakan environment variable sistem bila tersedia
        $value = getenv($key);
        if ($value !== false && $value !== '') {
            return $value;
        }

        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }

        $env_path = __DIR__ . '/../.env';
        if (file_exists($env_path)) {
            $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || strpos($line, '#') === 0) {
                    continue;
                }

                $parts = explode('=', $line, 2);
                if (count($parts) === 2) {
                    $name = trim($parts[0]);
                    $value = trim($parts[1]);

                    if ($name === $key) {
                        if (strlen($value) >= 2 && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
                            $value = substr($value, 1, -1);
                        }
                        return $value;
                    }
                }
            }
        }

        return $default;
    }
}
?>