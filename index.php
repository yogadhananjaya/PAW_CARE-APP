<?php
// index.php
session_start();

// Panggil konfigurasi database
require_once __DIR__ . '/config/koneksi.php';
// Panggil controller
require_once __DIR__ . '/app/controllers/AuthController.php';

require_once __DIR__ . '/app/controllers/PegawaiController.php';
$pegawaiController = new PegawaiController($pdo);

require_once __DIR__ . '/app/controllers/PerawatanController.php';
$perawatanController = new PerawatanController($pdo);

require_once __DIR__ . '/app/controllers/HewanController.php';
$hewanController = new HewanController($pdo);

// Ambil aksi dari URL, default-nya ke halaman login jika belum login
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

$authController = new AuthController($pdo);

// Ambil aksi dari URL, default-nya sekarang adalah 'home' (bukan login lagi)
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

$authController = new AuthController($pdo);
// ... (Kode controller lainnya biarkan) ...

switch ($action) {
    case 'home':
        // Jika sudah login, lempar ke dashboard, jika belum, tampilkan landing page
        if (isset($_SESSION['logged_in'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        require_once __DIR__ . '/views/home.php';
        break;

    case 'login':
    case 'register':
        // Kedua URL ini memanggil file view auth yang sama (karena sudah digabung)
        if (isset($_SESSION['logged_in'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        require_once __DIR__ . '/views/auth/login.php';
        break;

    case 'login_process':
        $authController->loginProcess();
        break;

    case 'register_process':
        $authController->registerProcess();
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php?action=login&success=Anda berhasil logout!");
        exit;
        break;

    case 'dashboard':
        if (!isset($_SESSION['logged_in'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // --- DI SINI KAMU TEMPATKAN KODENYA ---
        if ($_SESSION['role'] === 'User') {
            require_once __DIR__ . '/views/user/dashboard_pengadopsi.php';
        } else {
            require_once __DIR__ . '/views/layouts/header.php';
            require_once __DIR__ . '/views/dashboard.php';
            require_once __DIR__ . '/views/layouts/footer.php';
        }
        break;

    case 'hewan':
        require_once 'app/controllers/HewanController.php';
        $controller = new HewanController($pdo);
        $controller->index();
        break;

    case 'hewan_tambah':
        require_once 'app/controllers/HewanController.php';
        $controller = new HewanController($pdo);
        $controller->create();
        break;

    case 'hewan_simpan':
        require_once 'app/controllers/HewanController.php';
        $controller = new HewanController($pdo);
        $controller->store();
        break;

    case 'hewan_edit':
        require_once 'app/controllers/HewanController.php';
        $controller = new HewanController($pdo);
        $controller->edit($_GET['id']);
        break;

    case 'hewan_update':
        require_once 'app/controllers/HewanController.php';
        $controller = new HewanController($pdo);
        $controller->update();
        break;

    case 'hewan_hapus':
        require_once 'app/controllers/HewanController.php';
        $controller = new HewanController($pdo);
        $controller->delete($_GET['id']);
        break;

    case 'pegawai':
        // Proteksi: Hanya Superadmin dan Staff yang boleh mengakses menu ini
        if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'Superadmin' && $_SESSION['role'] !== 'Staff')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $pegawaiController->index();
        break;

    case 'pegawai_tambah':
        // Proteksi
        if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'Superadmin' && $_SESSION['role'] !== 'Staff')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $pegawaiController->create();
        break;

    case 'pegawai_simpan':
        // Proteksi
        if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'Superadmin' && $_SESSION['role'] !== 'Staff')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $pegawaiController->store();
        break;
    case 'pegawai_edit':
        if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'Superadmin' && $_SESSION['role'] !== 'Staff')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $pegawaiController->edit($id);
        break;

    case 'pegawai_update':
        if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'Superadmin' && $_SESSION['role'] !== 'Staff')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $pegawaiController->update();
        break;

    case 'pegawai_hapus':
        if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'Superadmin' && $_SESSION['role'] !== 'Staff')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $pegawaiController->delete($id);
        break;

    case 'perawatan':
        if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'Superadmin' && $_SESSION['role'] !== 'Staff')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $perawatanController->index();
        break;

    case 'perawatan_tambah':
        if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'Superadmin' && $_SESSION['role'] !== 'Staff')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $perawatanController->create();
        break;

    case 'perawatan_simpan':
        if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'Superadmin' && $_SESSION['role'] !== 'Staff')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $perawatanController->store();
        break;

    default:
        echo "<h1>404 Halaman Tidak Ditemukan</h1>";
        break;
}
