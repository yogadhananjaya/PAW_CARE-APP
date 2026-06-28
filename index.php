<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/config/connect.php';
require_once __DIR__ . '/config/auth.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    // ==================== HALAMAN UTAMA & AUTENTIKASI ====================
    case 'home':
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'SuperAdmin') header('Location: index.php?page=dashboard_superadmin');
            elseif ($_SESSION['role'] == 'Koordinator') header('Location: index.php?page=dashboard_koordinator');
            elseif (in_array($_SESSION['role'], ['Perawat', 'Perawat Hewan'])) header('Location: index.php?page=dashboard_staff');
            else header('Location: index.php?page=dashboard_user');
            exit;
        }
        include __DIR__ . '/views/user/dashboard_default.php';
        break;

    case 'landing':
        // Rute untuk kembali ke Landing Page (Halaman Utama) secara langsung
        include __DIR__ . '/views/user/dashboard_default.php';
        break;
        
    case 'login':
        if (isset($_SESSION['role'])) { header('Location: index.php?page=home'); exit; }
        include __DIR__ . '/views/login.php';
        break;
        
    case 'process_login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && login($_POST['username'], $_POST['password'])) {
            header('Location: index.php?page=home');
        } else {
            header('Location: index.php?page=login&error=1');
        }
        break;

    case 'process_register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/app/models/PenggunaModel.php';
            $userModel = new PenggunaModel();
            
            $username = trim($_POST['username']);
            // ponytail: Validasi format username (hanya huruf, angka, underscore, 4-20 karakter)
            if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
                echo "<script>alert('Gagal: Username hanya boleh terdiri dari huruf, angka, underscore, dan panjang antara 4 sampai 20 karakter.'); window.history.back();</script>";
                exit;
            }

            // ponytail: Cek apakah username sudah digunakan di tabel pengguna atau pengadopsi
            if ($userModel->isDuplicate($username)) {
                echo "<script>alert('Gagal: Username \"" . htmlspecialchars($username) . "\" sudah terdaftar! Silakan gunakan username lain.'); window.history.back();</script>";
                exit;
            }

            // Simpan akun baru dengan field yang sesuai untuk model Pengguna
            $userModel->insert([
                'nama_lengkap' => $username,
                'jabatan' => 'User',
                'kontak' => '',
                'nama_pengguna' => $username,
                'kata_sandi' => $_POST['password'],
                'role' => 'User',
                'status' => 'Aktif'
            ]);
            
            // Langsung otomatiskan login setelah berhasil daftar
            login(trim($_POST['username']), $_POST['password']);
            header('Location: index.php?page=home');
            exit;
        }
        break;
    
    case 'process_verifikasi':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            
            // Ambil username dari tabel pengguna
            $stmt_user = $pdo->prepare("SELECT nama_pengguna FROM pengguna WHERE id_pengguna = ?");
            $stmt_user->execute([$user_id]);
            $user_info = $stmt_user->fetch();
            $username_aktif = $user_info ? $user_info['nama_pengguna'] : '';
            
            // Buat email default & kode pengadopsi otomatis
            $email_default = $username_aktif . '@pawcare.com';
            $kode_adopter = buat_kode_otomatis('pengadopsi', 'kode_pengadopsi', 'AD');

            // Simpan data diri lengkap pengadopsi
            $stmt = $pdo->prepare("INSERT INTO pengadopsi (id_pengguna, kode_pengadopsi, nama_lengkap, nama_pengguna, email, nik, alamat, no_hp, status_verifikasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Terverifikasi')");
            $stmt->execute([
                $user_id, 
                $kode_adopter,
                trim($_POST['nama_lengkap']), 
                $username_aktif,
                $email_default,
                trim($_POST['nik']), 
                trim($_POST['alamat']), 
                trim($_POST['no_hp'])
            ]);
            
            // Langsung kembalikan ke dashboard user agar melihat status sukses
            header('Location: index.php?page=dashboard_user');
            exit;
        }
        break;
        
    case 'logout':
        logout();
        header('Location: index.php');
        break;

    // ==================== DASHBOARD BERDASARKAN ROLE ====================
    case 'dashboard_superadmin':
        if (!check_access(['SuperAdmin'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/dashboard_superadmin.php';
        break;

    case 'dashboard_staff':
        if (!check_access(['Koordinator', 'Perawat', 'SuperAdmin'])) { header('Location: index.php?page=login'); exit; }
        if ($_SESSION['role'] == 'Koordinator') { header('Location: index.php?page=dashboard_koordinator'); exit; }
        include __DIR__ . '/views/user/dashboard_staff.php';
        break;

    case 'dashboard_koordinator':
        if (!check_access(['Koordinator', 'SuperAdmin'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/dashboard_koordinator.php';
        break;

    case 'dashboard_user':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/dashboard_user.php';
        break;

    // --- WIZARD ADOPSI BARU (Langkah 1-4) ---
    case 'proses_adopsi':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/proses_adopsi.php';
        break;

    case 'proses_adopsi_submit':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?page=dashboard_user'); exit; }
        if (!isset($_SESSION['user_id'])) { header('Location: index.php?page=login'); exit; }

        // Ambil id_pengadopsi dari user yang login
        $stmt_adopter = $pdo->prepare("SELECT id_pengadopsi FROM pengadopsi WHERE id_pengguna = ?");
        $stmt_adopter->execute([$_SESSION['user_id']]);
        $adopter_data = $stmt_adopter->fetch();

        if (!$adopter_data) {
            echo "<script>alert('Profil pengadopsi belum lengkap. Harap isi data diri terlebih dahulu.'); window.location.href='index.php?page=dashboard_user';</script>";
            exit;
        }

        $id_hewan       = intval($_POST['id_hewan'] ?? 0);
        $ttd_base64     = $_POST['tanda_tangan_png'] ?? '';
        $metode_bayar   = htmlspecialchars($_POST['metode_pembayaran'] ?? 'Transfer Bank');
        $id_pengadopsi  = $adopter_data['id_pengadopsi'];

        // Pastikan hewan masih tersedia sebelum disimpan (cegah double booking)
        $stmt_cek = $pdo->prepare("SELECT id_hewan FROM hewan WHERE id_hewan = ? AND status_adopsi = 'Tersedia'");
        $stmt_cek->execute([$id_hewan]);
        if (!$stmt_cek->fetch()) {
            echo "<script>alert('Maaf, hewan ini sudah tidak tersedia.'); window.location.href='index.php?page=dashboard_user&tab=katalog';</script>";
            exit;
        }

        // Buat kode transaksi otomatis
        $kode_transaksi = buat_kode_otomatis('transaksi_adopsi', 'kode_transaksi_adopsi', 'TA');

        // Simpan transaksi adopsi baru
        $stmt_insert = $pdo->prepare("INSERT INTO transaksi_adopsi (kode_transaksi_adopsi, id_hewan, id_pengadopsi, tanggal_adopsi, status_kontrak, ttd_adopter) VALUES (?, ?, ?, CURDATE(), 'Ditandatangani', ?)");
        $stmt_insert->execute([$kode_transaksi, $id_hewan, $id_pengadopsi, $ttd_base64]);

        // Ubah status hewan menjadi 'Dalam Proses'
        $pdo->prepare("UPDATE hewan SET status_adopsi = 'Dalam Proses' WHERE id_hewan = ?")->execute([$id_hewan]);

        echo "<script>alert('✅ Pengajuan adopsi berhasil! Silakan tunggu konfirmasi dari tim PawCare.'); window.location.href='index.php?page=dashboard_user&tab=pengajuan';</script>";
        exit;

    // --- FITUR TANDA TANGAN & SIMPAN (lama, dipertahankan) ---
    case 'tanda_tangan':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/tanda_tangan.php';
        break;

    case 'simpan_ttd':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) { header('Location: index.php?page=login'); exit; }
            
            // Ambil id_pengadopsi dari tabel pengadopsi berdasarkan user yang sedang login
            $stmt_adopter = $pdo->prepare("SELECT id_pengadopsi FROM pengadopsi WHERE id_pengguna = ?");
            $stmt_adopter->execute([$_SESSION['user_id']]);
            $adopter = $stmt_adopter->fetch();
            
            if ($adopter) {
                $id_hewan = $_POST['id_transaksi']; // yang dikirim dari tanda_tangan.php adalah id_hewan
                $ttd_base64 = $_POST['ttd_base64'];
                
                // Simpan data ke dalam transaksi_adopsi dengan status 'Ditandatangani'
                $stmt = $pdo->prepare("INSERT INTO transaksi_adopsi (id_hewan, id_pengadopsi, tanggal_adopsi, status_kontrak, ttd_adopter) VALUES (?, ?, CURDATE(), 'Ditandatangani', ?)");
                $stmt->execute([$id_hewan, $adopter['id_pengadopsi'], $ttd_base64]);
                
                echo "<script>alert('Tanda tangan berhasil disimpan. Adopsi Sah!'); window.location.href='index.php?page=dashboard_user&tab=pengajuan';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan: Profil pengadopsi belum lengkap.'); window.location.href='index.php?page=dashboard_user';</script>";
            }
            exit;
        }
        break;

    // --- INTEGRASI MIDTRANS PAYMENT GATEWAY SNAP ---
    case 'bayar_donasi':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama_donatur'];
            $jumlah = $_POST['jumlah'];
            $order_id = 'DONASI-' . time(); // Order ID unik

            // 1. Simpan ke database dengan status Pending
            $stmt = $pdo->prepare("INSERT INTO donasi (nama_donatur, jumlah, tanggal, status) VALUES (?, ?, CURDATE(), 'Pending')");
            $stmt->execute([$nama, $jumlah]);

            // 2. Request Token API Midtrans
            // Ganti SERVER_KEY_ANDA dengan kunci server dari dashboard sandbox Midtrans Anda
            $server_key = "SERVER_KEY_ANDA"; 
            
            $transaction = array(
                'transaction_details' => array( 'order_id' => $order_id, 'gross_amount' => $jumlah ),
                'customer_details' => array( 'first_name' => $nama )
            );

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://app.sandbox.midtrans.com/snap/v1/transactions",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($transaction),
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Authorization: Basic " . base64_encode($server_key . ":")
                ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response);

            // 3. Redirect user ke halaman pembayaran Midtrans
            if(isset($result->redirect_url)) {
                header("Location: " . $result->redirect_url);
            } else {
                echo "Gagal memproses Payment Gateway.";
            }
            exit;
        }
        break;
        
    // --- MIDTRANS WEBHOOK (Notifikasi Otomatis) ---
    case 'midtrans_webhook':
        // Ini akan ditembak oleh server Midtrans ketika user selesai bayar
        $json_result = file_get_contents('php://input');
        $notif = json_decode($json_result);

        if($notif && $notif->transaction_status == 'settlement') {
            // Ubah status otomatis dari Pending menjadi Dikonfirmasi tanpa campur tangan Admin
            $stmt = $pdo->prepare("UPDATE donasi SET status = 'Dikonfirmasi' WHERE status = 'Pending' AND id_donasi = (SELECT MAX(id_donasi) FROM donasi)");
            $stmt->execute();
        }
        http_response_code(200);
        exit;
        break;
        
    // ==================== ROUTING DINAMIS MVC (MASTER & TRANSAKSI) ====================
    default:
        // Cek apakah $page memiliki format "nama_entitas" atau "nama_entitas_action"
        $parts = explode('_', $page);
        $action = 'index';
        $entity = $page;

        // Mendeteksi _create, _edit, _delete, _confirm, _reject, _activate, _intake, _koordinator, _recommend, _release, _complete, _sign dari string parameter page
        if (in_array(end($parts), ['create', 'edit', 'delete', 'confirm', 'reject', 'activate', 'intake', 'koordinator', 'recommend', 'release', 'complete', 'sign'])) {
            $action = array_pop($parts);
            $entity = implode('_', $parts);
        }

        // Daftar entitas yang valid di sistem
        $valid_entities = [
            'hewan', 'jenis', 'ras', 'kandang', 'vaksin', 'pengguna', 'pengadopsi', 'donasi',
            'riwayat_kesehatan', 'penempatan_kandang', 'jadwal_kunjungan', 'transaksi_adopsi',
            'intake_hewan'
        ];

        // Entitas Master (hanya SuperAdmin)
        $master_entities = ['hewan', 'jenis', 'ras', 'kandang', 'vaksin', 'pengguna', 'pengadopsi', 'donasi'];

        if (in_array($entity, $valid_entities)) {
            // ponytail: validasi rbac ketat sesuai matriks hak akses
            if (!check_rbac($entity, $action)) {
                $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
                if ($user_role == 'SuperAdmin') {
                    header('Location: index.php?page=dashboard_superadmin');
                } elseif ($user_role == 'Koordinator') {
                    header('Location: index.php?page=dashboard_koordinator');
                } elseif ($user_role == 'Perawat' || $user_role == 'Perawat Hewan') {
                    header('Location: index.php?page=dashboard_staff');
                } else {
                    header('Location: index.php?page=login');
                }
                exit;
            }
            
            // Route khusus: intake_hewan pakai HewanController
            if ($entity == 'intake_hewan') {
                require_once __DIR__ . '/app/controllers/HewanController.php';
                $hewanCtrl = new HewanController();
                $hewanCtrl->intake();
                break;
            }
            
            // Route khusus: penempatan_kandang dengan action koordinator
            if ($entity == 'penempatan_kandang' && $action == 'koordinator') {
                require_once __DIR__ . '/app/controllers/PenempatanKandangController.php';
                $pkCtrl = new PenempatanKandangController();
                $pkCtrl->koordinator();
                break;
            }
            
            // Mengubah format snake_case (riwayat_kesehatan) menjadi CamelCase (RiwayatKesehatanController)
            $ctrlName = str_replace(' ', '', ucwords(str_replace('_', ' ', $entity))) . 'Controller';
            $ctrlFile = __DIR__ . '/app/controllers/' . $ctrlName . '.php';
            
            if (file_exists($ctrlFile)) {
                require_once $ctrlFile;
                $controller = new $ctrlName();
                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                
                if ($action === 'index') $controller->index();
                elseif ($action === 'create') $controller->create();
                elseif ($action === 'edit') $controller->edit($id);
                elseif ($action === 'delete') $controller->delete($id);
                elseif ($action === 'confirm') $controller->confirm($id);
                elseif ($action === 'reject') $controller->reject($id);
                elseif ($action === 'activate') $controller->activate($id);
                elseif ($action === 'intake') $controller->intake();
                elseif ($action === 'recommend') $controller->recommend($id);
                elseif ($action === 'release') $controller->release($id);
                elseif ($action === 'complete') $controller->complete($id);
                elseif ($action === 'sign') $controller->sign($id);
                break;
            }
        }
        
        // Fitur Laporan PDF Donasi
        if ($page == 'report_donasi' && check_access(['SuperAdmin'])) {
            require_once __DIR__ . '/app/controllers/ReportController.php';
            $ctrl = new ReportController();
            $ctrl->laporanDonasi();
            break;
        }

        // Jika tidak masuk rute manapun
        echo "<h1 style='text-align:center; margin-top:50px;'>404 Not Found</h1><p style='text-align:center;'>Halaman tidak tersedia.</p>";
        break;
}
?>