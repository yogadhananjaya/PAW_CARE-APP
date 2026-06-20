<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/connect.php';

function login($username, $password) {
    global $pdo;
    
    // Kredensial khusus SuperAdmin sesuai permintaan spesifikasi
    if ($username === 'pawcare' && $password === 'kelompok5') {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'pawcare';
        $_SESSION['role'] = 'SuperAdmin';
        return true;
    }
    
    // Pengecekan ke database untuk role Pegawai dan User biasa
    try {
        $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        // Catatan: Di level produksi, disarankan menggunakan password_verify()
        if ($user && $password === $user['password']) {
            $_SESSION['user_id'] = $user['id_pengguna'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    } catch (Exception $e) {
        return false;
    }
    
    return false;
}

function logout() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // Hapus semua data sesi
    $_SESSION = array();
    session_destroy();
}

function check_access($allowed_roles = []) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Jika belum login sama sekali
    if (!isset($_SESSION['role'])) {
        return false;
    }
    
    // Jika rute bisa diakses siapa saja yang sudah login
    if (empty($allowed_roles)) {
        return true;
    }
    
    // Cek apakah role pengguna saat ini ada di dalam array role yang diizinkan
    return in_array($_SESSION['role'], $allowed_roles);
}
?>