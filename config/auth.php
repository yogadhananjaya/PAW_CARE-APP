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
    
    try {
        // 1. Cek apakah ini akun Pengadopsi langsung di tabel pengadopsi
        $stmt_adopter = $pdo->prepare("SELECT * FROM pengadopsi WHERE nama_pengguna = ?");
        $stmt_adopter->execute([$username]);
        $adopter = $stmt_adopter->fetch();
        
        if ($adopter && password_verify($password, $adopter['kata_sandi'])) {
            if ($adopter['status_verifikasi'] === 'Terverifikasi') {
                // Hanya buat akun pengguna resmi setelah disetujui oleh admin
                $stmt_check_user = $pdo->prepare("SELECT id_pengguna FROM pengguna WHERE nama_pengguna = ?");
                $stmt_check_user->execute([$adopter['nama_pengguna']]);
                $user_row = $stmt_check_user->fetch();
                if (!$user_row) {
                    $kode_pg = buat_kode_otomatis('pengguna', 'kode_pengguna', 'PG');
                    $stmt_insert = $pdo->prepare("INSERT INTO pengguna (kode_pengguna, nama_lengkap, jabatan, kontak, nama_pengguna, kata_sandi, role, status) VALUES (?, ?, 'Pengadopsi', ?, ?, ?, 'User', 'Aktif')");
                    $stmt_insert->execute([
                        $kode_pg,
                        $adopter['nama_lengkap'],
                        $adopter['no_hp'],
                        $adopter['nama_pengguna'],
                        $adopter['kata_sandi']
                    ]);
                    $new_id_pengguna = $pdo->lastInsertId();
                } else {
                    $new_id_pengguna = $user_row['id_pengguna'];
                }
                $_SESSION['user_id'] = $new_id_pengguna;
                unset($_SESSION['pending_adopter_id']);
            } else {
                // Simpan session untuk calon adopter yang masih menunggu verifikasi
                $_SESSION['user_id'] = $adopter['id_pengadopsi'];
                $_SESSION['pending_adopter_id'] = $adopter['id_pengadopsi'];
            }
            
            $_SESSION['username'] = $adopter['nama_pengguna'];
            $_SESSION['nama_lengkap'] = $adopter['nama_lengkap'];
            $_SESSION['role'] = 'User';
            return true;
        }
        
        // 2. Jika tidak ditemukan di pengadopsi, cek di tabel pengguna (untuk Admin/Staf)
        $stmt_user = $pdo->prepare("SELECT * FROM pengguna WHERE nama_pengguna = ?");
        $stmt_user->execute([$username]);
        $user = $stmt_user->fetch();
        
        if ($user && password_verify($password, $user['kata_sandi'])) {
            $_SESSION['user_id'] = $user['id_pengguna'];
            $_SESSION['username'] = $user['nama_pengguna'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['jabatan'];
            $_SESSION['jabatan'] = $user['jabatan'];
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

function get_current_adopter_id() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    global $pdo;
    if (isset($_SESSION['pending_adopter_id']) && $_SESSION['pending_adopter_id']) {
        return $_SESSION['pending_adopter_id'];
    }

    if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
        $stmt = $pdo->prepare("SELECT id_pengadopsi FROM pengadopsi WHERE nama_pengguna = ?");
        $stmt->execute([$_SESSION['username'] ?? '']);
        $row = $stmt->fetch();
        return $row['id_pengadopsi'] ?? null;
    }

    return null;
}

function sync_pengadopsi_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    global $pdo;
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'User' || empty($_SESSION['username'])) {
        return;
    }

    $current_username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT id_pengadopsi, nama_pengguna, nama_lengkap FROM pengadopsi WHERE nama_pengguna = ?");
    $stmt->execute([$current_username]);
    $adopter = $stmt->fetch();

    if (!$adopter) {
        return;
    }

    $_SESSION['username'] = $adopter['nama_pengguna'];
    $_SESSION['nama_lengkap'] = $adopter['nama_lengkap'];
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
    
    $role = $_SESSION['role'];
    if ($role == 'Perawat Hewan') {
        $role = 'Perawat';
    }
    
    // Cek apakah role pengguna saat ini ada di dalam array role yang diizinkan
    return in_array($role, $allowed_roles);
}

//  Fungsi check_rbac super sederhana untuk memeriksa hak akses berdasarkan Role
function check_rbac($entity, $action) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // 1. Ambil role user dari session
    $role = '';
    if (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
    }
    
    // Jika belum login, tolak semua
    if ($role == '') {
        return false;
    }
    
    // Normalisasi role Perawat Hewan dan Perawat agar sama
    if ($role == 'Perawat Hewan') {
        $role = 'Perawat';
    }
    
    // ============================================
    // 2. ATURAN HAK AKSES UNTUK SUPERADMIN
    // ============================================
    if ($role == 'SuperAdmin') {
        // Kelola Akun Internal (pengguna): CRUD
        if ($entity == 'pengguna') {
            return true;
        }
        // Kelola Master Data (hewan, jenis, ras): CRUD
        if ($entity == 'hewan' || $entity == 'jenis' || $entity == 'ras') {
            // Khusus action recommend (Rekomendasi Layak Adopsi) bernilai R (read-only/tidak boleh action recommend)
            if ($action == 'recommend') {
                return false;
            }
            return true;
        }
        // Kelola Kandang & Vaksin: CRUD
        if ($entity == 'kandang' || $entity == 'vaksin') {
            return true;
        }
        // Penerimaan Hewan Baru (intake_hewan): R (Hanya Read)
        if ($entity == 'intake_hewan') {
            if ($action == 'index') {
                return true;
            }
            return false;
        }
        // Kelola Rekam Medis: CRUD (edit/delete divalidasi lebih lanjut oleh canModify() di controller)
        if ($entity == 'riwayat_kesehatan') {
            return true;
        }
        // Penempatan & Log Kandang: CRUD penuh (Sama seperti Koordinator)
        if ($entity == 'penempatan_kandang') {
            return true;
        }
        // Kelola Jadwal Kunjungan Adopter: CRUD penuh (Sama seperti Koordinator)
        if ($entity == 'jadwal_kunjungan') {
            return true;
        }
        // Kelola Kontrak Adopsi (transaksi_adopsi): CRUD
        if ($entity == 'transaksi_adopsi') {
            return true;
        }
        // Kelola & Verifikasi Calon Adopter (pengadopsi): CRUD
        if ($entity == 'pengadopsi') {
            return true;
        }
        // Kelola Keuangan & Donasi Manual: CRUD
        if ($entity == 'donasi') {
            return true;
        }
        
        return true;
    }
    
    // ============================================
    // 3. ATURAN HAK AKSES UNTUK KOORDINATOR
    // ============================================
    if ($role == 'Koordinator') {
        // Kelola Akun Internal: Ditolak (❌)
        if ($entity == 'pengguna') {
            return false;
        }
        // Kelola Master Data (hewan, jenis, ras): CRUD
        if ($entity == 'hewan' || $entity == 'jenis' || $entity == 'ras') {
            // Dilarang recommend
            if ($action == 'recommend') {
                return false;
            }
            return true;
        }
        // Kelola Kandang & Vaksin: R (Hanya Read)
        if ($entity == 'kandang' || $entity == 'vaksin') {
            if ($action == 'index') {
                return true;
            }
            return false;
        }
        // Penerimaan Hewan Baru (intake_hewan): CRUD
        if ($entity == 'intake_hewan') {
            return true;
        }
        // Kelola Rekam Medis: R (Hanya Read untuk Koordinator)
        if ($entity == 'riwayat_kesehatan') {
            if ($action == 'index') {
                return true;
            }
            return false;
        }
        // Penempatan & Log Kandang: CRUD
        if ($entity == 'penempatan_kandang') {
            return true;
        }
        // Kelola Jadwal Kunjungan Adopter: R (Hanya Read)
        if ($entity == 'jadwal_kunjungan') {
            if ($action == 'index') {
                return true;
            }
            return false;
        }
        // Kelola Kontrak Adopsi: Sign Admin, Aktivasi & Inisiasi (Tidak boleh menghapus/delete)
        if ($entity == 'transaksi_adopsi') {
            if (in_array($action, ['index', 'create', 'edit', 'activate', 'sign', 'reject'])) {
                return true;
            }
            return false;
        }
        // Kelola & Verifikasi Calon Adopter (pengadopsi): R (Hanya Read)
        if ($entity == 'pengadopsi') {
            if ($action == 'index') {
                return true;
            }
            return false;
        }
        // Kelola Keuangan & Donasi Manual: Ditolak (❌)
        if ($entity == 'donasi') {
            return false;
        }
        
        return false;
    }
    
    // ============================================
    // 4. ATURAN HAK AKSES UNTUK PERAWAT
    // ============================================
    if ($role == 'Perawat') {
        // Kelola Akun Internal: Ditolak (❌)
        if ($entity == 'pengguna') {
            return false;
        }
        // Kelola Master Data (hewan, jenis, ras): R (Hanya Read, kecuali recommend)
        if ($entity == 'hewan' || $entity == 'jenis' || $entity == 'ras') {
            if ($entity == 'hewan' && $action == 'recommend') {
                return true; // Rekomendasi Layak Adopsi (CRUD)
            }
            if ($action == 'index') {
                return true;
            }
            return false;
        }
        // Kelola Kandang & Vaksin: R (Hanya Read)
        if ($entity == 'kandang' || $entity == 'vaksin') {
            if ($action == 'index') {
                return true;
            }
            return false;
        }
        // Penerimaan Hewan Baru: Ditolak (❌)
        if ($entity == 'intake_hewan') {
            return false;
        }
        // Kelola Rekam Medis (riwayat_kesehatan): CRUD
        if ($entity == 'riwayat_kesehatan') {
            return true;
        }
        // Penempatan & Log Kandang: R (Hanya Read)
        if ($entity == 'penempatan_kandang') {
            if (in_array($action, ['index', 'koordinator'])) {
                return true;
            }
            return false;
        }
        // Kelola Jadwal Kunjungan Adopter: Ditolak (❌)
        if ($entity == 'jadwal_kunjungan') {
            return false;
        }
        // Kelola Kontrak Adopsi: Ditolak (❌)
        if ($entity == 'transaksi_adopsi') {
            return false;
        }
        // Kelola & Verifikasi Calon Adopter: Ditolak (❌)
        if ($entity == 'pengadopsi') {
            return false;
        }
        // Kelola Keuangan & Donasi Manual: Ditolak (❌)
        if ($entity == 'donasi') {
            return false;
        }
        
        return false;
    }
    
    // Peran lainnya (seperti Pengadopsi/User umum) ditolak dari modul backend ini
    return false;
}
?>