<?php
session_start();
require_once __DIR__ . '/config/connect.php';
require_once __DIR__ . '/config/auth.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    // ==================== HALAMAN UTAMA & AUTENTIKASI ====================
    case 'home':
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'SuperAdmin') header('Location: index.php?page=dashboard_superadmin');
            elseif ($_SESSION['role'] == 'Pegawai') header('Location: index.php?page=dashboard_staff');
            else header('Location: index.php?page=dashboard_user');
            exit;
        }
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
            
            // Simpan akun baru
            $userModel->insert([
                'username' => trim($_POST['username']),
                'password' => $_POST['password'], // Catatan: Sebaiknya di-hash di production
                'role' => 'User'
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
            // PERUBAHAN: Status default diubah dari 'Belum' menjadi 'Terverifikasi'
            $stmt = $pdo->prepare("INSERT INTO pengadopsi (id_pengguna, nama_lengkap, nik, alamat, no_hp, status_verifikasi) VALUES (?, ?, ?, ?, ?, 'Terverifikasi')");
            $stmt->execute([
                $_SESSION['user_id'], 
                trim($_POST['nama_lengkap']), 
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
        if (!check_access(['Pegawai', 'SuperAdmin'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/dashboard_staff.php';
        break;

    case 'dashboard_user':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/dashboard_user.php';
        break;

    // --- FITUR SIMPAN TANDA TANGAN ---
    case 'simpan_ttd':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_transaksi = $_POST['id_transaksi'];
            $ttd_base64 = $_POST['ttd_base64'];
            
            // Simpan data Base64 ke dalam kolom e_contract di database
            $stmt = $pdo->prepare("UPDATE transaksi_adopsi SET e_contract = ?, status_adopsi = 'Disetujui' WHERE id_transaksi = ?");
            $stmt->execute([$ttd_base64, $id_transaksi]);
            
            echo "<script>alert('Tanda tangan berhasil disimpan. Adopsi Sah!'); window.location.href='index.php?page=dashboard_user';</script>";
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

        // Mendeteksi _create, _edit, _delete dari string parameter page
        if (in_array(end($parts), ['create', 'edit', 'delete'])) {
            $action = array_pop($parts);
            $entity = implode('_', $parts);
        }

        // Daftar entitas yang valid di sistem
        $valid_entities = [
            'hewan', 'jenis', 'ras', 'kandang', 'vaksin', 'pengguna', 'pengadopsi', 'donasi',
            'riwayat_kesehatan', 'penempatan_kandang', 'jadwal_kunjungan', 'transaksi_adopsi'
        ];

        if (in_array($entity, $valid_entities)) {
            // Hanya SuperAdmin dan Pegawai yang bisa mengakses modul CRUD
            if (!check_access(['SuperAdmin', 'Pegawai'])) { header('Location: index.php?page=login'); exit; }
            
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