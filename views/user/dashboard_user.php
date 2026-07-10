<?php
require_once __DIR__ . '/../../config/connect.php';

if (!function_exists('getPetImage')) {
    function getPetImage($hewan) {
        $url = $hewan['url_foto_hewan'] ?? '';
        $jenis = strtolower($hewan['nama_jenis'] ?? '');
        $ras = strtolower($hewan['nama_ras'] ?? '');
        
        if (!empty($url)) {
            if (file_exists(__DIR__ . '/../../uploads/hewan/' . $url)) {
                return 'uploads/hewan/' . $url;
            }
            if (file_exists(__DIR__ . '/../../assets/img/hewan/' . $url)) {
                return 'assets/img/hewan/' . $url;
            }
        }
        
        if (strpos($jenis, 'kucing') !== false) {
            $kucing_images = [
                'image.png', 'image copy.png', 'image copy 2.png', 'image copy 3.png',
                'image copy 4.png', 'image copy 5.png', 'image copy 6.png', 'image copy 7.png',
                'image copy 8.png', 'image copy 9.png', 'image copy 10.png', 'image copy 11.png'
            ];
            $id = intval($hewan['id_hewan'] ?? 0);
            $idx = $id % count($kucing_images);
            return 'assets/img/hewan/kucing/' . $kucing_images[$idx];
        }
        
        if (strpos($jenis, 'anjing') !== false) {
            $dir_path = __DIR__ . '/../../assets/img/hewan/anjing/';
            if (is_dir($dir_path)) {
                $files = array_diff(scandir($dir_path), array('.', '..', '.gitkeep'));
                if (count($files) > 0) {
                    $id = intval($hewan['id_hewan'] ?? 0);
                    $files = array_values($files);
                    $idx = $id % count($files);
                    return 'assets/img/hewan/anjing/' . $files[$idx];
                }
            }
            
            if (strpos($ras, 'golden') !== false) {
                return 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&q=80&w=600';
            } elseif (strpos($ras, 'bulldog') !== false) {
                return 'https://images.unsplash.com/photo-1517849845537-4d257902454a?auto=format&fit=crop&q=80&w=600';
            } elseif (strpos($ras, 'pomeranian') !== false) {
                return 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?auto=format&fit=crop&q=80&w=600';
            } else {
                return 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&q=80&w=600';
            }
        }

        if (strpos($jenis, 'kelinci') !== false) {
            $dir_path = __DIR__ . '/../../assets/img/hewan/kelinci/';
            if (is_dir($dir_path)) {
                $files = array_diff(scandir($dir_path), array('.', '..', '.gitkeep'));
                if (count($files) > 0) {
                    $id = intval($hewan['id_hewan'] ?? 0);
                    $files = array_values($files);
                    $idx = $id % count($files);
                    return 'assets/img/hewan/kelinci/' . $files[$idx];
                }
            }
            return 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?auto=format&fit=crop&q=80&w=600';
        }
        
        return 'assets/img/logo.png';
    }
}

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Ambil data profil pengadopsi
$stmt = $pdo->prepare("SELECT * FROM pengadopsi WHERE nama_pengguna = ?");
$stmt->execute([$_SESSION['username'] ?? '']);
$adopter = $stmt->fetch();

// Menentukan tab aktif (default: beranda)
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'beranda';

// 2. Tentukan status verifikasi pengadopsi
$is_unverified = true;
$show_verification_form = true;
$status_txt = 'Belum Verifikasi';
$status_color = '#7f8c8d';
$status_bg = '#f1f5f9';

if ($adopter) {
    $is_unverified = in_array($adopter['status_verifikasi'], ['Belum', 'Menunggu', 'Ditolak']);
} else {
    $is_unverified = true;
}

if ($is_unverified && $tab !== 'beranda') {
    $tab = 'beranda';
}

if ($adopter) {
    if ($adopter['status_verifikasi'] === 'Terverifikasi') {
        $is_unverified = false;
        $show_verification_form = false;
        $status_txt = 'Terverifikasi';
        $status_color = '#27ae60';
        $status_bg = '#eafaf1';
    } elseif ($adopter['status_verifikasi'] === 'Menunggu') {
        $status_txt = 'Menunggu Verifikasi';
        $status_color = '#d35400';
        $status_bg = '#FFF4EC';
        $show_verification_form = false;
    } elseif ($adopter['status_verifikasi'] === 'Ditolak') {
        $status_txt = 'Ditolak';
        $status_color = '#c0392b';
        $status_bg = '#fdedec';
        $show_verification_form = true;
    } else {
        $status_txt = 'Belum Verifikasi';
        $status_color = '#7f8c8d';
        $status_bg = '#f1f5f9';
        $show_verification_form = true;
    }
}

// 3. Ambil data katalog hewan jika akun sudah terverifikasi
$katalog_hewan = [];
if ($adopter && !$is_unverified) {
    $stmt_hewan = $pdo->query("SELECT h.*, j.nama_jenis, r.nama_ras FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status_adopsi = 'Tersedia' ORDER BY h.id_hewan DESC");
    $katalog_hewan = $stmt_hewan->fetchAll();
}

// Ambil data gallery hewan yang tersedia untuk ditampilkan pada beranda
$gallery_pets = [];
$stmt_gallery = $pdo->query("SELECT h.*, j.nama_jenis, r.nama_ras FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status_adopsi = 'Tersedia' ORDER BY h.id_hewan DESC");
$gallery_pets = $stmt_gallery->fetchAll();

// 3. Ambil data pengajuan adopsi milik user ini (untuk tab Pengajuan Saya)
$my_submissions = [];
$my_visits = [];
$applied_animals = [];
if ($adopter) {
    $stmt_sub = $pdo->prepare("SELECT t.*, h.nama_hewan, h.url_foto_hewan, j.nama_jenis FROM transaksi_adopsi t JOIN hewan h ON t.id_hewan = h.id_hewan JOIN jenis_hewan j ON h.id_jenis = j.id_jenis WHERE t.id_pengadopsi = ? ORDER BY t.id_adopsi DESC");
    $stmt_sub->execute([$adopter['id_pengadopsi']]);
    $my_submissions = $stmt_sub->fetchAll();

    $stmt_vis = $pdo->prepare("SELECT j.*, h.nama_hewan, u.nama_lengkap as nama_petugas FROM jadwal_kunjungan j JOIN hewan h ON j.id_hewan = h.id_hewan LEFT JOIN pengguna u ON j.id_pengguna = u.id_pengguna WHERE j.id_pengadopsi = ? ORDER BY j.tanggal_jadwal DESC");
    $stmt_vis->execute([$adopter['id_pengadopsi']]);
    $my_visits = $stmt_vis->fetchAll();

    $stmt_app = $pdo->prepare("SELECT DISTINCT h.id_hewan, h.nama_hewan FROM transaksi_adopsi t JOIN hewan h ON t.id_hewan = h.id_hewan WHERE t.id_pengadopsi = ? AND t.status_kontrak != 'Batal'");
    $stmt_app->execute([$adopter['id_pengadopsi']]);
    $applied_animals = $stmt_app->fetchAll();
}

// Ambil data donasi terkini untuk ditampilkan di Beranda
$stmt_donasi = $pdo->query("SELECT * FROM donasi WHERE kategori = 'Pemasukan' AND status_konfirmasi = 'Dikonfirmasi' ORDER BY id_donasi DESC LIMIT 5");
$donasi_list = $stmt_donasi->fetchAll();

// Tentukan nama donatur otomatis dari akun login
$nama_donatur_val = '';
if ($adopter && !empty($adopter['nama_lengkap'])) {
    $nama_donatur_val = htmlspecialchars($adopter['nama_lengkap']);
} else {
    $nama_donatur_val = htmlspecialchars($_SESSION['username'] ?? '');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Adopter - PawCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        :root {

            --primary: #a33900;          /* Warm Red/Orange */
            --primary-hover: #cc4900;
            --primary-light: #ffdbce;
            --accent: #a33900;
            --accent-hover: #cc4900;
            --accent-light: #FFEDD5;     /* light orange pill background */
            --bg-page: #f7f9fb;
            --card-bg: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #67758c;
            --border-color: #E2E8F0;
            --shadow-sm: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            --shadow-md: 0 12px 30px -4px rgba(15, 23, 42, 0.12);
            --shadow-lg: 0 20px 40px -15px rgba(15, 23, 42, 0.15);
        }

        /* Tailwind micro-interaction helpers */
        .transition-all {
            transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .duration-300 {
            transition-duration: 300ms !important;
        }

        /* Shader canvas background */
        #shader-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            pointer-events: none;
            opacity: 0.04;
        }

        /* Entrance keyframes */
        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-hero-heading {
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        .animate-hero-text {
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards;
            opacity: 0;
        }
        .animate-hero-buttons {
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards;
            opacity: 0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Work Sans', 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-page); color: var(--text-main); min-height: 100vh; overflow-x: hidden; -webkit-font-smoothing: antialiased; display: flex; flex-direction: column; }
        .top-navbar {
            display: flex; justify-content: space-between; align-items: center;
            background: #FFFFFF;
            padding: 16px 5%; border-bottom: 1px solid var(--border-color);
            position: sticky; top: 0; z-index: 100; box-shadow: var(--shadow-sm);
        }
        
        .nav-left { display: flex; align-items: center; gap: 40px; }
        .navbar-brand { color: var(--accent); font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 24px; text-decoration: none; transition: transform 0.2s ease; }
        .navbar-brand:hover { transform: scale(1.03); }
        
        .nav-links { display: flex; gap: 6px; }
        .nav-item { 
            padding: 8px 18px; color: var(--text-muted); font-weight: 600; font-size: 14px;
            border-radius: 99px; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none; 
        }
        .nav-item:hover { color: var(--primary); }
        .nav-item.active { 
            color: var(--primary); 
            background: none; 
            border-radius: 0;
            position: relative;
        }
        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 18px;
            right: 18px;
            height: 2px;
            background-color: var(--primary);
        }

        .nav-right { display: flex; align-items: center; gap: 20px; }
        
        /* Profile Ringkas */
        .user-profile { 
            display: flex; align-items: center; gap: 10px; background: #FFFFFF; 
            padding: 5px 14px 5px 5px; border-radius: 99px; border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }
        .avatar { 
            width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%); 
            color: #FFF; display: flex; align-items: center; justify-content: center; 
            font-weight: 700; font-size: 14px; text-transform: uppercase;
        }
        .user-greeting { font-weight: 600; font-size: 13px; color: var(--text-main); }

        .logout-btn { color: #EF4444; font-weight: 600; font-size: 14px; text-decoration: none; transition: 0.2s; padding: 6px 12px; border-radius: 99px; }
        .logout-btn:hover { background: #FEF2F2; }

        /* Main Content Grid with Tab Entry Animation */
        .main-content { 
            width: 100%; margin: 0; padding: 30px 4%; 
            animation: tabFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            flex: 1;
        }

        @keyframes tabFadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Hero Section */
        .hero-section { 
            display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 40px; align-items: center; 
            min-height: calc(85vh - 110px); 
            background: radial-gradient(circle at 10% 20%, rgba(204, 251, 241, 0.4) 0%, rgba(255, 247, 237, 0.4) 100%), #FFFFFF;
            color: var(--text-main); padding: 50px 6%; border-radius: 24px; margin-bottom: 40px; 
            border: 1px solid rgba(13, 148, 136, 0.08);
            box-shadow: var(--shadow-md); overflow: hidden; position: relative; 
        }
        .hero-content { position: relative; z-index: 1; }
        .hero-badge { 
            display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.9); 
            padding: 8px 16px; border-radius: 999px; font-size: 13px; font-weight: 600; margin-bottom: 20px; 
            color: var(--primary-hover); border: 1px solid rgba(13, 148, 136, 0.15); box-shadow: var(--shadow-sm); 
        }
        .hero-badge strong { color: var(--accent); }
        .hero-title { font-size: clamp(2.5rem, 4.5vw, 4.5rem); line-height: 1.05; font-weight: 900; margin-bottom: 20px; color: var(--text-main); letter-spacing: -1.5px; }
        .hero-title span { color: var(--primary); }
        .hero-subtitle { max-width: 600px; color: var(--text-muted); font-size: 1.1rem; margin-bottom: 32px; line-height: 1.65; }
        .hero-buttons { display: flex; flex-wrap: wrap; gap: 16px; }
        .hero-buttons a { padding: 14px 30px; border-radius: 99px; font-weight: 700; font-size: 0.95rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .hero-buttons a.btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); color: #ffffff; box-shadow: 0 10px 20px rgba(13, 148, 136, 0.2); }
        .hero-buttons a.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(13, 148, 136, 0.3); }
        .hero-buttons a.btn-secondary { background: #FFFFFF; color: var(--text-main); border: 1px solid var(--border-color); }
        .hero-buttons a.btn-secondary:hover { transform: translateY(-2px); background: var(--bg-page); box-shadow: var(--shadow-sm); }
        
        .hero-image { position: relative; display: flex; justify-content: center; align-items: center; }
        .hero-image .hero-photo { width: 100%; max-width: 480px; border-radius: 24px; box-shadow: var(--shadow-lg); border: 6px solid #FFFFFF; background: #fff; transition: transform 0.5s ease; animation: floatImage 6s ease-in-out infinite; }
        .hero-image:hover .hero-photo { transform: translateY(-6px) scale(1.02); }
        @keyframes floatImage { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        
        .hero-image .hero-card { position: absolute; right: -10px; bottom: 20px; width: 220px; background: rgba(255,255,255,0.95); border-radius: 20px; padding: 16px; color: var(--text-main); box-shadow: var(--shadow-lg); border: 1px solid rgba(13, 148, 136, 0.1); }
        .hero-image .hero-card h4 { margin-bottom: 6px; font-size: 0.95rem; font-weight: 800; color: var(--primary); }
        .hero-image .hero-card p { margin: 0; color: var(--text-muted); font-size: 0.85rem; line-height: 1.5; }

        .hero-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-top: 32px; }
        .hero-stat { background: #FFFFFF; border: 1px solid rgba(13, 148, 136, 0.1); border-radius: 16px; padding: 14px 16px; box-shadow: var(--shadow-sm); }
        .hero-stat strong { display: block; font-size: 1.5rem; font-weight: 800; color: var(--accent); margin-bottom: 4px; }
        .hero-stat span { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; }

        .scroll-indicator { display: flex; flex-direction: column; align-items: center; gap: 8px; color: var(--primary); margin: 0 auto 40px; text-align: center; }
        .scroll-indicator span { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; }
        .scroll-indicator .arrow { width: 10px; height: 10px; border-left: 2px solid currentColor; border-bottom: 2px solid currentColor; transform: rotate(-45deg); animation: scrollBounce 1.5s infinite; }
        @keyframes scrollBounce { 0%, 20% { transform: translateY(0) rotate(-45deg); opacity: 0.7; } 50% { transform: translateY(6px) rotate(-45deg); opacity: 1; } 100% { transform: translateY(0) rotate(-45deg); opacity: 0.7; } }

        .section-title { font-size: 24px; font-weight: 800; color: var(--text-main); margin-bottom: 25px; letter-spacing: -0.5px; }

        /* Form Pengisian Data Diri */
        .verify-card { 
            background: #FFFFFF; padding: 40px; border-radius: 20px; 
            border: 1px solid var(--border-color); max-width: 600px; margin: 40px auto; 
            box-shadow: var(--shadow-md);
        }
        .verify-card h2 { margin-bottom: 8px; font-weight: 800; color: var(--text-main); }
        .form-control { 
            width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); 
            border-radius: 10px; margin-bottom: 16px; outline: none; font-size: 14px;
            transition: all 0.2s; background: var(--bg-page);
        }
        .form-control:focus { border-color: var(--primary); background: #FFFFFF; box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15); }

        /* Pet Card Grid (Katalog) */
        .catalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; }
        .pet-card { 
            background: #FFFFFF; border-radius: 20px; padding: 16px; 
            border: 1px solid var(--border-color); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; 
            box-shadow: var(--shadow-sm);
        }
        .pet-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-md); border-color: rgba(13, 148, 136, 0.2); }
        .pet-img { width: 100%; height: 200px; border-radius: 14px; object-fit: cover; margin-bottom: 16px; background: var(--bg-page); }
        
        .pet-name { font-size: 20px; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
        .pet-breed { font-size: 13.5px; color: var(--text-muted); font-weight: 500; margin-bottom: 16px; }
        
        .pet-tags { display: flex; gap: 6px; margin-bottom: 20px; }
        .tag { background: var(--primary-light); color: var(--primary); font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 8px; }
        
        .btn-adopt { 
            margin-top: auto; width: 100%; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); color: #FFF; 
            border: none; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 14px;
            cursor: pointer; transition: all 0.2s; text-align: center; text-decoration: none; display: block;
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.15);
        }
        .btn-adopt:hover { transform: translateY(-1px); box-shadow: 0 6px 15px rgba(13, 148, 136, 0.25); }

        /* Tabel Pengajuan Saya */
        .table-wrapper { background: #FFFFFF; border-radius: 20px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-sm); }
        .submission-table { width: 100%; border-collapse: collapse; text-align: left; }
        .submission-table th { background: var(--bg-page); padding: 18px 24px; font-size: 13px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border-color); }
        .submission-table td { padding: 18px 24px; border-top: 1px solid var(--border-color); font-size: 14.5px; color: var(--text-main); vertical-align: middle; }
        .submission-table tr:hover { background-color: rgba(13, 148, 136, 0.01); }
        
        /* Premium Badges */
        .status-pill { 
            padding: 6px 14px; border-radius: 99px; font-size: 12.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; 
        }
        .status-process { background: #FEF3C7; color: #D97706; border: 1px solid #FDE68A; }
        .status-approved { background: #D1FAE5; color: #059669; border: 1px solid #A7F3D0; }
        .status-rejected { background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; }

        /* Petunjuk Adopsi Step Cards */
        .guide-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; }
        .guide-card { background: #FFFFFF; padding: 30px 24px; border-radius: 20px; border: 1px solid var(--border-color); text-align: center; transition: all 0.3s; box-shadow: var(--shadow-sm); }
        .guide-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: rgba(13, 148, 136, 0.15); }
        .guide-number { width: 44px; height: 44px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%); color: #FFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px; margin: 0 auto 20px; box-shadow: 0 4px 10px rgba(249, 115, 22, 0.2); }

        .gallery-section { margin-top: 50px; }
        .gallery-section h2 { color: var(--text-main); margin-bottom: 20px; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; }
        .gallery-link { display: block; text-decoration: none; border-radius: 24px; overflow: hidden; }
        .gallery-card { position: relative; border-radius: 24px; overflow: hidden; min-height: 280px; box-shadow: var(--shadow-sm); border: 4px solid #FFFFFF; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .gallery-card img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease; }
        .gallery-card:hover { transform: scale(1.02); box-shadow: var(--shadow-lg); }
        .gallery-card:hover img { transform: scale(1.05); }
        .gallery-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, transparent 40%, rgba(15, 23, 42, 0.8) 100%); opacity: 0.9; transition: opacity .3s ease; }
        .gallery-label { position: absolute; left: 24px; bottom: 24px; color: #fff; z-index: 2; }
        .gallery-label h4 { margin: 0 0 4px; font-size: 20px; font-weight: 800; line-height: 1.2; }
        .gallery-label span { font-size: 13px; color: rgba(255,255,255,0.8); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        @media (max-width: 1024px) {
            .hero-section { grid-template-columns: 1fr; padding: 40px 6%; min-height: auto; gap: 30px; }
            .hero-image { margin-top: 10px; }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .main-content { padding: 20px 4%; }
            .hero-title { font-size: 2.5rem; }
            .hero-section { padding: 30px 4%; border-radius: 20px; }
            .hero-buttons { flex-direction: column; width: 100%; }
            .hero-buttons a { width: 100%; }
        }

        /* New Gallery Card Styling (Match Screenshot) */
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; }
        .gallery-link { display: block; text-decoration: none; color: inherit; }
        .gallery-card { 
            background: #FFFFFF; border-radius: 20px; overflow: hidden; 
            border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column;
        }
        .gallery-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-md); border-color: rgba(163, 57, 0, 0.2); }
        .gallery-img-container { position: relative; width: 100%; height: 200px; overflow: hidden; }
        .gallery-img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .gallery-card:hover .gallery-img-container img { transform: scale(1.05); }
        .gallery-status-badge { 
            position: absolute; top: 12px; left: 12px; 
            background: #10B981; color: #FFFFFF; font-size: 11px; font-weight: 700; 
            padding: 4px 10px; border-radius: 8px; z-index: 2; 
        }
        .gallery-info { padding: 18px; display: flex; flex-direction: column; gap: 8px; position: relative; }
        .gallery-info-header { display: flex; justify-content: space-between; align-items: flex-start; }
        .gallery-pet-name { font-size: 20px; font-weight: 800; color: var(--text-main); }
        .gallery-pet-meta { font-size: 13.5px; color: var(--text-muted); font-weight: 500; }
        .gallery-heart-icon { color: #94a3b8; cursor: pointer; transition: color 0.2s; font-size: 20px; }
        .gallery-heart-icon:hover { color: #EF4444; }
        .gallery-pet-tags { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 4px; }
        .gallery-pet-tag { 
            background: var(--accent-light); color: var(--primary); 
            font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 8px; 
        }
    </style>
</head>
<body>
    <canvas id="shader-canvas"></canvas>

    <nav class="top-navbar">
        <div class="nav-left">
            <a href="index.php?page=dashboard_user" class="navbar-brand transition-all duration-300">PawCare</a>
            <div class="nav-links">
                <a href="index.php?page=dashboard_user&tab=beranda" class="nav-item <?= $tab == 'beranda' ? 'active' : '' ?> transition-all duration-300">Beranda</a>
                
                <?php if ($is_unverified): ?>
                    <a href="#" class="nav-item transition-all duration-300" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;">Katalog</a>
                    <a href="#" class="nav-item transition-all duration-300" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;">Pengajuan Saya</a>
                    <a href="#" class="nav-item transition-all duration-300" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;">Jadwal Kunjungan</a>
                    <a href="#" class="nav-item transition-all duration-300" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;">Petunjuk Adopsi</a>
                <?php else: ?>
                    <a href="index.php?page=dashboard_user&tab=katalog" class="nav-item <?= $tab == 'katalog' ? 'active' : '' ?> transition-all duration-300">Katalog</a>
                    <a href="index.php?page=dashboard_user&tab=pengajuan" class="nav-item <?= $tab == 'pengajuan' ? 'active' : '' ?> transition-all duration-300">Pengajuan Saya</a>
                    <a href="index.php?page=dashboard_user&tab=kunjungan" class="nav-item <?= $tab == 'kunjungan' ? 'active' : '' ?> transition-all duration-300">Jadwal Kunjungan</a>
                    <a href="index.php?page=dashboard_user&tab=petunjuk" class="nav-item <?= $tab == 'petunjuk' ? 'active' : '' ?> transition-all duration-300">Petunjuk Adopsi</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="nav-right">
            <a href="index.php?page=landing" style="color: #e67e22; font-weight: 600; font-size: 14px; text-decoration: none; margin-right: 10px;">🏠 Halaman Utama</a>
            <div class="user-profile" style="position: relative;">
                <?php
                $status_txt = 'Belum Verifikasi';
                $status_color = '#7f8c8d'; // Abu-abu
                $status_bg = '#f1f5f9';

                if ($adopter) {
                    if ($adopter['status_verifikasi'] === 'Menunggu') {
                        $status_txt = 'Menunggu Verifikasi';
                        $status_color = '#d35400'; // Oranye
                        $status_bg = '#FFF4EC';
                    } elseif ($adopter['status_verifikasi'] === 'Terverifikasi') {
                        $status_txt = 'Terverifikasi';
                        $status_color = '#27ae60'; // Hijau
                        $status_bg = '#eafaf1';
                    } elseif ($adopter['status_verifikasi'] === 'Ditolak') {
                        $status_txt = 'Ditolak';
                        $status_color = '#c0392b'; // Merah
                        $status_bg = '#fdedec';
                    }
                }
                ?>
                <span style="font-size: 11px; font-weight: 700; color: <?= $status_color ?>; background: <?= $status_bg ?>; padding: 4px 10px; border-radius: 20px; border: 1px solid <?= $status_color ?>; margin-right: 8px;">
                    <?= $status_txt ?>
                </span>
                <div class="avatar"><?= substr($_SESSION['username'], 0, 1) ?></div>
                <div class="user-greeting">Halo, <?= htmlspecialchars($_SESSION['username']) ?> 👋</div>
            </div>
            <a href="index.php?page=logout" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="main-content">
        
        <?php if ($show_verification_form): ?>
            <div class="verify-card">
                <h2>Lengkapi Data Diri</h2>
                <p style="color:#95a5a6; font-size:14px; margin-bottom:25px;">Silakan isi data diri sesuai KTP dan unggah foto KTP Anda untuk mengaktifkan seluruh fitur dashboard.</p>
                <form action="index.php?page=process_verifikasi" method="POST" enctype="multipart/form-data">
                    <div style="margin-bottom: 15px; text-align: left;">
                        <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Nama Lengkap (Sesuai KTP) *</label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div style="margin-bottom: 15px; text-align: left;">
                        <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">NIK *</label>
                        <input type="text" name="nik" class="form-control" placeholder="Nomor Induk Kependudukan (16 digit)" required pattern="[0-9]{16}" title="NIK harus berupa 16 digit angka">
                    </div>
                    <div style="margin-bottom: 15px; text-align: left;">
                        <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Nomor WhatsApp *</label>
                        <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" required>
                    </div>
                    <div style="margin-bottom: 15px; text-align: left;">
                        <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="Contoh: nama@email.com" required>
                    </div>
                    <div style="margin-bottom: 15px; text-align: left;">
                        <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Alamat Domisili Lengkap *</label>
                        <textarea name="alamat" class="form-control" placeholder="Alamat Lengkap" rows="3" required></textarea>
                    </div>
                    <div style="margin-bottom: 15px; text-align: left;">
                        <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Foto KTP *</label>
                        <input type="file" name="foto_ktp" class="form-control" accept="image/*" required style="padding: 8px 12px;">
                        <small style="color: #7f8c8d; font-size: 11px; display: block; margin-top: 2px;">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                    </div>
                    <button type="submit" class="btn-adopt" style="margin-top:15px; width: 100%;">Simpan & Verifikasi Otomatis</button>
                </form>
            </div>

        <?php else: ?>
                 <?php if ($tab == 'beranda'): ?>
                <section class="hero-section">
                    <div class="hero-content">
                        <div class="hero-badge">🐾 PawCare Shelter & Adopsi</div>
                        <h1 class="hero-title animate-hero-heading">Live Your<br><span>Pet Adoption Adventure</span></h1>
                        <p class="hero-subtitle animate-hero-text">Temukan hewan peliharaan yang tepat, pelihara dengan cinta, dan jadikan momen adopsi Anda penuh arti bersama PawCare.</p>
                        <div class="hero-buttons animate-hero-buttons">
                            <a href="index.php?page=dashboard_user&tab=katalog" class="btn-primary transition-all duration-300">Lihat Katalog</a>
                            <a href="index.php?page=dashboard_user&tab=pengajuan" class="btn-secondary transition-all duration-300">Riwayat Pengajuan</a>
                        </div>
                        <div class="hero-stats">
                            <div class="hero-stat"><strong>1.200+</strong><span>HEWAN DISELAMATKAN</span></div>
                            <div class="hero-stat"><strong>840+</strong><span>ADOPSI SUKSES</span></div>
                        </div>
                    </div>
                    <div class="hero-image">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCKEQJq6ZAfQEGqHVM09jjbKo0P-kVQPNqJu7tGpMp5MDlT0G4oPkp2CZaw4AzrdcadQqDt14bTOTtUhvaBd1yQmxgCb5Fsqb5Zm_svupsoOpaldpt8cuVVbN6dmx9rGTTRYkSuLJRNhqyrWt5aVTVFHA1qNzbafQUJqsqA2By9vDueZfF1ymsdCf-l-hEt96KAj6Q2PeUOD5nFKr4sZycxnzSwZRK81YzaY0dugOFNQlag0_xqpOHhFU_XsTTlZ1aCzk4eTxkwZyI" alt="Hewan Peliharaan" class="hero-photo">
                        <div class="hero-card">
                            <h4>PawCare Shelter</h4>
                            <p>Kami merawat setiap hewan dengan kasih sayang, kesehatan, dan keamanan sebelum mereka menemukan keluarga baru.</p>
                        </div>
                    </div>
                </section>

                <div class="scroll-indicator">
                    <span>Scroll untuk melihat hewan</span>
                    <div class="arrow"></div>
                </div>

                <section class="gallery-section">
                    <div class="section-heading" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
                        <div>
                            <h2 style="font-size: 28px; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">Teman Baru Menunggumu</h2>
                            <p style="color:#64748b; font-size:14px; max-width: 720px;">Lihat daftar hewan terbaru yang siap untuk diadopsi hari ini.</p>
                        </div>
                        <a href="index.php?page=dashboard_user&tab=katalog" style="color: var(--primary); font-weight: 700; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">Semua Katalog <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span></a>
                    </div>
                    <div class="gallery-grid">
                        <?php 
                        $counter_gallery = 0;
                        foreach ($gallery_pets as $pet): 
                            $counter_gallery++;
                            $isHiddenGallery = $counter_gallery > 10 ? 'style="display: none;" class="gallery-link extra-gallery-pet"' : 'class="gallery-link"';
                            $imagePath = getPetImage($pet);
                            $petName = htmlspecialchars($pet['nama_hewan']);
                            $petType = htmlspecialchars($pet['nama_jenis']);
                            $petRas = htmlspecialchars($pet['nama_ras']);
                            $umur = intval($pet['estimasi_umur']);
                            $umur_text = ($umur >= 12) ? (round($umur / 12) . ' Tahun') : ($umur . ' Bulan');
                            
                            // Generate tags
                            $hobi = $pet['hobi'] ?? '';
                            $tags = [];
                            if (!empty($hobi)) {
                                $tags = array_filter(array_map('trim', explode(',', $hobi)));
                            }
                            if (empty($tags)) {
                                $tags = [$pet['jenis_kelamin'] === 'Jantan' ? 'Sangat Ceria' : 'Manja', 'Ramah'];
                            }
                        ?>
                            <a href="index.php?page=hewan_detail&id=<?= $pet['id_hewan'] ?>" <?= $isHiddenGallery ?>>
                                <article class="gallery-card">
                                    <div class="gallery-img-container">
                                        <span class="gallery-status-badge">Tersedia</span>
                                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="Foto <?= $petName ?>">
                                    </div>
                                    <div class="gallery-info">
                                        <div class="gallery-info-header">
                                            <h4 class="gallery-pet-name"><?= $petName ?></h4>
                                            <?php if (!$adopter || $adopter['status_verifikasi'] !== 'Menunggu'): ?>
                                                <span class="material-symbols-outlined gallery-heart-icon">favorite</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="gallery-pet-meta"><?= $petType ?> - <?= $petRas ?> &bull; <?= $umur_text ?></div>
                                        <div class="gallery-pet-tags">
                                            <?php foreach (array_slice($tags, 0, 2) as $tag): ?>
                                                <span class="gallery-pet-tag"><?= htmlspecialchars($tag) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </article>
                            </a>
                        <?php endforeach; ?>

                        <?php if (count($gallery_pets) > 10): ?>
                            <div id="btn-load-more-gallery-container" style="grid-column: 1 / -1; text-align: center; margin-top: 30px;">
                                <button onclick="showAllGalleryPets()" class="btn-adopt" style="width: auto; padding: 12px 35px; border-radius: 30px; font-weight: 700; cursor: pointer; display: inline-block;">Semua Katalog</button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <script>
                    function showAllGalleryPets() {
                        var extraGalleryPets = document.querySelectorAll('.extra-gallery-pet');
                        extraGalleryPets.forEach(function(pet) {
                            pet.style.display = 'block';
                            if (window.gsap) {
                                gsap.fromTo(pet, {opacity: 0, scale: 0.95}, {opacity: 1, scale: 1, duration: 0.3});
                            }
                        });
                        var btnContainerGallery = document.getElementById('btn-load-more-gallery-container');
                        if (btnContainerGallery) {
                            btnContainerGallery.style.display = 'none';
                        }
                    }
                    </script>
                </section>

                <section class="steps-section" style="margin-top: 60px; margin-bottom: 40px; text-align: center;">
                    <h2 style="font-size: 28px; font-weight: 800; color: var(--text-main); margin-bottom: 40px;">Langkah Mudah Adopsi</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; max-width: 1000px; margin: 0 auto;">
                        <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                            <div style="width: 56px; height: 56px; background-color: var(--accent-light); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <span class="material-symbols-outlined" style="font-size: 24px; color: var(--primary); font-weight: bold;">search</span>
                            </div>
                            <h4 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Cari Temanmu</h4>
                            <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.6; max-width: 240px;">Jelajahi ratusan hewan lucu di katalog kami yang butuh rumah baru.</p>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                            <div style="width: 56px; height: 56px; background-color: var(--accent-light); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <span class="material-symbols-outlined" style="font-size: 24px; color: var(--primary); font-weight: bold;">assignment</span>
                            </div>
                            <h4 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Ajukan Profil</h4>
                            <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.6; max-width: 240px;">Isi formulir singkat tentang lingkungan tempat tinggal dan pengalamanmu.</p>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                            <div style="width: 56px; height: 56px; background-color: var(--accent-light); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <span class="material-symbols-outlined" style="font-size: 24px; color: var(--primary); font-weight: bold;">home</span>
                            </div>
                            <h4 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Bawa Pulang</h4>
                            <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.6; max-width: 240px;">Setelah verifikasi dan kunjungan, sambut anggota keluarga barumu.</p>
                        </div>
                    </div>
                </section>

                <!-- Donation Section -->
                <section class="donation-section" style="padding: 80px 8%; background: #ffffff; border-top: 1px solid rgba(15,23,42,0.05); margin-top: 60px; border-radius: 24px;">
                    <div style="text-align:center;">
                        <h2 class="interactive-title" style="font-size:36px; color:#0f172a; font-family:'Outfit', sans-serif; margin-bottom:10px;">Dukung Perawatan Hewan 🐾</h2>
                        <p style="text-align:center; color:#94a3b8; margin-bottom:50px;">Bantu kami menyediakan pakan, tempat tinggal yang layak, dan perawatan medis untuk mereka</p>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; max-width: 1100px; margin: 0 auto; text-align: left;">
                        <!-- Donation Form -->
                        <div style="background: #ffffff; border: 1px solid rgba(15,23,42,0.08); border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(15,23,42,0.02);">
                            <h3 style="font-size: 20px; font-family: 'Outfit', sans-serif; color: #0f172a; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">Formulir Donasi</h3>
                            <form method="POST" action="index.php?page=donasi_proses" onsubmit="return validateDonationForm(this);">
                                <div style="margin-bottom: 15px;">
                                    <label style="display:block; margin-bottom:6px; font-weight:600; font-size:13px; color:#0f172a;">Nama Donatur</label>
                                    <input type="text" name="nama_donatur" value="<?= $nama_donatur_val ?>" readonly required minlength="3" pattern="^[A-Za-z\s]+$" style="width:100%; padding:12px 15px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; outline:none; background-color: #f1f5f9; cursor: not-allowed;">
                                    <small style="color: #94a3b8; font-size: 11px; margin-top: 4px; display: block;">Diisi otomatis dari akun Anda.</small>
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <label style="display:block; margin-bottom:6px; font-weight:600; font-size:13px; color:#0f172a;">Nominal Donasi (IDR)</label>
                                    <input type="number" name="nominal" min="10000" step="1000" value="50000" required style="width:100%; padding:12px 15px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; outline:none;">
                                    <small style="color: #94a3b8; font-size: 11px; margin-top: 4px; display: block;">Minimal donasi Rp 10.000.</small>
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <label style="display:block; margin-bottom:6px; font-weight:600; font-size:13px; color:#0f172a;">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" style="width:100%; padding:12px 15px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; outline:none; background:#fff;">
                                        <option value="Transfer Bank">Transfer Bank</option>
                                        <option value="E-Wallet">E-Wallet / QRIS</option>
                                    </select>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <label style="display:block; margin-bottom:6px; font-weight:600; font-size:13px; color:#0f172a;">Catatan / Pesan</label>
                                    <textarea name="keterangan" placeholder="Tulis doa atau dukungan Anda..." rows="3" style="width:100%; padding:12px 15px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; outline:none; resize:none;"></textarea>
                                </div>
                                <button type="submit" style="width:100%; background:#bd4a0a; color:#fff; border:none; padding:14px; border-radius:30px; font-weight:700; font-size:14px; cursor:pointer; box-shadow: 0 4px 12px rgba(189,74,10,0.2); transition:all 0.3s ease;">Donasi Sekarang</button>
                            </form>
                        </div>

                        <!-- Recent Donors -->
                        <div style="background: #ffffff; border: 1px solid rgba(15,23,42,0.08); border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(15,23,42,0.02);">
                            <h3 style="font-size: 20px; font-family: 'Outfit', sans-serif; color: #0f172a; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">Donatur Terkini 💖</h3>
                            <div style="display: flex; flex-direction: column; gap: 15px; max-height: 380px; overflow-y: auto; padding-right: 5px;">
                                <?php if (count($donasi_list) > 0): ?>
                                    <?php foreach ($donasi_list as $donasi): ?>
                                        <div style="background: #f8fafc; border-radius: 12px; padding: 15px; border-left: 4px solid #bd4a0a; display: flex; flex-direction: column; gap: 4px;">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <span style="font-weight: 700; color: #0f172a; font-size: 14px;"><?= htmlspecialchars($donasi['nama_donatur']) ?></span>
                                                <span style="font-size: 12px; color: #64748b; font-weight: 500;"><?= date('d M Y', strtotime($donasi['tanggal'])) ?></span>
                                            </div>
                                            <div style="font-weight: 800; color: #DE3B3B; font-size: 15px; font-family: 'Outfit', sans-serif;">
                                                Rp <?= number_format($donasi['nominal'], 0, ',', '.') ?>
                                            </div>
                                            <?php if (!empty($donasi['keterangan'])): ?>
                                                <div style="font-size: 13px; color: #64748b; font-style: italic; margin-top: 4px;">
                                                    "<?= htmlspecialchars($donasi['keterangan']) ?>"
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div style="text-align: center; color: #94a3b8; padding: 40px 0; font-size: 14px;">
                                        🐾 Belum ada donatur terbaru. Jadilah donatur pertama kami!
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>

                <script>
                    function validateDonationForm(form) {
                        var nama = form.nama_donatur.value.trim();
                        var nominal = parseInt(form.nominal.value, 10);

                        if (nama.length < 3) {
                            alert("Nama donatur minimal harus terdiri dari 3 karakter.");
                            form.nama_donatur.focus();
                            return false;
                        }

                        var nameRegex = /^[A-Za-z\s]+$/;
                        if (!nameRegex.test(nama)) {
                            alert("Nama donatur hanya boleh berisi huruf dan spasi.");
                            form.nama_donatur.focus();
                            return false;
                        }

                        if (isNaN(nominal) || nominal < 10000) {
                            alert("Nominal donasi minimal adalah Rp 10.000.");
                            form.nominal.focus();
                            return false;
                        }
                        return true;
                    }
                </script>
            <?php elseif ($tab == 'katalog'): ?>
                <h2 class="section-title">Katalog Hewan Tersedia</h2>
                <?php if (count($katalog_hewan) > 0): ?>
                    <div class="catalog-grid">
                        <?php foreach ($katalog_hewan as $hewan): ?>
                            <div class="pet-card">
                                <img src="<?= getPetImage($hewan) ?>" class="pet-img" alt="Foto" onerror="this.onerror=null; this.src='assets/img/logo.png';">
                                <div class="pet-name"><?= htmlspecialchars($hewan['nama_hewan']) ?></div>
                                <div class="pet-breed"><?= htmlspecialchars($hewan['nama_jenis']) ?> - <?= htmlspecialchars($hewan['nama_ras']) ?></div>
                                <div class="pet-tags">
                                    <span class="tag"><?= htmlspecialchars($hewan['jenis_kelamin']) ?></span>
                                    <span class="tag"><?= htmlspecialchars($hewan['estimasi_umur']) ?> bln</span>
                                </div>
                                <a href="index.php?page=hewan_detail&id=<?= $hewan['id_hewan'] ?>" class="btn-adopt">Detail Hewan</a>
                            </div>
                        <?php endforeach; ?>

                    </div>
                <?php endif; ?>
                </script>

            <?php elseif ($tab == 'pengajuan'): ?>
                <style>
                    /* Submission Grid & Card Styles */
                    .submission-grid {
                        display: flex;
                        flex-direction: column;
                        gap: 24px;
                        margin-top: 10px;
                    }
                    .submission-card {
                        background: #FFFFFF;
                        border-radius: 20px;
                        border: 1px solid var(--border-color);
                        box-shadow: var(--shadow-sm);
                        padding: 24px;
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                        display: grid;
                        grid-template-columns: auto 1fr;
                        gap: 24px;
                        position: relative;
                        overflow: hidden;
                    }
                    .submission-card:hover {
                        transform: translateY(-4px);
                        box-shadow: var(--shadow-md);
                        border-color: rgba(163, 57, 0, 0.2);
                    }
                    .submission-left {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        gap: 12px;
                    }
                    .submission-avatar-container {
                        width: 120px;
                        height: 120px;
                        border-radius: 16px;
                        overflow: hidden;
                        border: 3px solid #FFFFFF;
                        box-shadow: var(--shadow-sm);
                        background: var(--bg-page);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .submission-avatar-container img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }
                    .submission-right {
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        gap: 16px;
                    }
                    .submission-info-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        flex-wrap: wrap;
                        gap: 12px;
                    }
                    .submission-title-group h3 {
                        font-size: 20px;
                        font-weight: 800;
                        color: var(--text-main);
                        margin-bottom: 4px;
                    }
                    .submission-subtitle {
                        font-size: 13.5px;
                        color: var(--text-muted);
                        font-weight: 500;
                    }
                    .submission-actions {
                        display: flex;
                        gap: 10px;
                        flex-wrap: wrap;
                    }
                    .btn-sub-action {
                        padding: 8px 16px;
                        border-radius: 10px;
                        font-size: 13px;
                        font-weight: 700;
                        text-decoration: none;
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        cursor: pointer;
                        border: none;
                        transition: all 0.2s;
                    }
                    .btn-sub-primary {
                        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
                        color: #FFFFFF;
                        box-shadow: 0 4px 10px rgba(163, 57, 0, 0.15);
                    }
                    .btn-sub-primary:hover {
                        transform: translateY(-1px);
                        box-shadow: 0 6px 15px rgba(163, 57, 0, 0.25);
                    }
                    .btn-sub-secondary {
                        background: var(--bg-page);
                        color: var(--text-main);
                        border: 1px solid var(--border-color);
                    }
                    .btn-sub-secondary:hover {
                        background: #FFFFFF;
                        border-color: var(--primary);
                        color: var(--primary);
                    }
                    .btn-sub-danger {
                        background: #FEE2E2;
                        color: #DC2626;
                        border: 1px solid #FCA5A5;
                    }
                    .btn-sub-danger:hover {
                        background: #DC2626;
                        color: #FFFFFF;
                        border-color: #DC2626;
                    }

                    /* Dynamic Timeline Styles */
                    .timeline-container {
                        background: var(--bg-page);
                        padding: 16px 20px;
                        border-radius: 14px;
                        border: 1px solid var(--border-color);
                        margin-top: 8px;
                    }
                    .timeline-steps {
                        display: flex;
                        justify-content: space-between;
                        position: relative;
                        align-items: center;
                        margin-top: 10px;
                    }
                    .timeline-line-bg {
                        position: absolute;
                        left: 4%;
                        right: 4%;
                        top: 15px;
                        height: 4px;
                        background: #E2E8F0;
                        z-index: 1;
                        border-radius: 2px;
                    }
                    .timeline-line-active {
                        position: absolute;
                        left: 4%;
                        top: 15px;
                        height: 4px;
                        background: #10B981;
                        z-index: 2;
                        border-radius: 2px;
                        transition: width 0.5s ease;
                    }
                    .timeline-step {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        position: relative;
                        z-index: 3;
                        width: 18%;
                        text-align: center;
                    }
                    .timeline-node {
                        width: 32px;
                        height: 32px;
                        border-radius: 50%;
                        background: #FFFFFF;
                        border: 3px solid #CBD5E1;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 700;
                        font-size: 13px;
                        color: #64748B;
                        transition: all 0.3s ease;
                        box-shadow: var(--shadow-sm);
                    }
                    .timeline-step.active .timeline-node {
                        border-color: #10B981;
                        background: #10B981;
                        color: #FFFFFF;
                        box-shadow: 0 0 10px rgba(16, 185, 129, 0.4);
                    }
                    .timeline-step.current .timeline-node {
                        border-color: #3B82F6;
                        background: #FFFFFF;
                        color: #3B82F6;
                        box-shadow: 0 0 10px rgba(59, 130, 246, 0.4);
                        animation: pulseCurrent 2s infinite;
                    }
                    .timeline-step.cancelled .timeline-node {
                        border-color: #EF4444;
                        background: #EF4444;
                        color: #FFFFFF;
                    }
                    .timeline-label {
                        margin-top: 8px;
                        font-size: 11px;
                        font-weight: 700;
                        color: #64748B;
                        transition: color 0.3s;
                    }
                    .timeline-step.active .timeline-label {
                        color: #059669;
                    }
                    .timeline-step.current .timeline-label {
                        color: #2563EB;
                    }
                    .timeline-step.cancelled .timeline-label {
                        color: #DC2626;
                    }

                    @keyframes pulseCurrent {
                        0% { transform: scale(1); }
                        50% { transform: scale(1.08); }
                        100% { transform: scale(1); }
                    }

                    /* Modal Popup Styles */
                    .custom-modal {
                        position: fixed;
                        top: 0; left: 0; width: 100%; height: 100%;
                        background: rgba(15, 23, 42, 0.6);
                        backdrop-filter: blur(4px);
                        z-index: 1000;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        opacity: 0;
                        pointer-events: none;
                        transition: all 0.3s ease;
                    }
                    .custom-modal.open {
                        opacity: 1;
                        pointer-events: auto;
                    }
                    .modal-dialog {
                        background: #FFFFFF;
                        border-radius: 24px;
                        width: 90%;
                        max-width: 560px;
                        box-shadow: var(--shadow-lg);
                        border: 1px solid var(--border-color);
                        overflow: hidden;
                        transform: scale(0.9);
                        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }
                    .custom-modal.open .modal-dialog {
                        transform: scale(1);
                    }
                    .modal-header {
                        padding: 20px 24px;
                        border-bottom: 1px solid var(--border-color);
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        background: var(--bg-page);
                    }
                    .modal-header h3 {
                        font-size: 18px;
                        font-weight: 800;
                        color: var(--text-main);
                        margin: 0;
                    }
                    .modal-close {
                        background: none;
                        border: none;
                        font-size: 24px;
                        color: var(--text-muted);
                        cursor: pointer;
                        transition: color 0.2s;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .modal-close:hover {
                        color: #EF4444;
                    }
                    .modal-body {
                        padding: 24px;
                        max-height: 70vh;
                        overflow-y: auto;
                    }
                    .detail-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 16px;
                        margin-bottom: 20px;
                    }
                    .detail-item {
                        display: flex;
                        flex-direction: column;
                        gap: 4px;
                    }
                    .detail-label {
                        font-size: 11px;
                        font-weight: 700;
                        color: var(--text-muted);
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }
                    .detail-value {
                        font-size: 14px;
                        font-weight: 600;
                        color: var(--text-main);
                    }
                    
                    @media(max-width: 768px) {
                        .submission-card {
                            grid-template-columns: 1fr;
                            gap: 16px;
                        }
                        .submission-avatar-container {
                            width: 100px;
                            height: 100px;
                        }
                        .timeline-steps {
                            overflow-x: auto;
                            padding-bottom: 10px;
                        }
                        .timeline-step {
                            min-width: 100px;
                        }
                    }
                </style>

                <h2 class="section-title">Riwayat Pengajuan Adopsi Anda</h2>
                <?php if (count($my_submissions) > 0): ?>
                    <div class="submission-grid">
                        <?php foreach ($my_submissions as $sub): ?>
                            <?php
                            // Ambil data kunjungan yang berkaitan dengan hewan ini
                            $matched_visit = null;
                            foreach ($my_visits as $visit) {
                                if ($visit['id_hewan'] == $sub['id_hewan']) {
                                    $matched_visit = $visit;
                                    break;
                                }
                            }

                            // Tentukan posisi langkah timeline secara dinamis
                            // Alur: 1. Draft / Ttd Kontrak -> 2. Verifikasi Koordinator -> 3. Jadwal Kunjungan -> 4. ttd Kontrak Koordinator -> 5. Adopsi Selesai
                            $step = 1;
                            $is_cancelled = ($sub['status_kontrak'] == 'Batal');

                            if (!$is_cancelled) {
                                // Jika adopter sudah tanda tangan, minimal masuk ke langkah 2
                                if (!empty($sub['ttd_adopter'])) {
                                    $step = 2; // Verifikasi Koordinator
                                }
                                // Jika jadwal kunjungan sudah dibuat/dikonfirmasi, masuk ke langkah 3
                                if ($matched_visit) {
                                    $step = 3; // Jadwal Kunjungan
                                }
                                // Jika jadwal kunjungan selesai, masuk ke langkah 4
                                if ($matched_visit && $matched_visit['status_jadwal'] == 'Selesai') {
                                    $step = 4; // ttd Kontrak Koordinator
                                }
                                // Jika admin/koordinator sudah tanda tangan & kontrak aktif, masuk langkah 5
                                if (!empty($sub['ttd_admin']) && $sub['status_kontrak'] == 'Aktif') {
                                    $step = 5; // Adopsi Selesai
                                }
                            }

                            // Hitung persentase lebar timeline-line-active
                            $progress_percent = 0;
                            if (!$is_cancelled && $step > 1) {
                                $progress_percent = (($step - 1) / 4) * 100;
                            }
                            ?>
                            <div class="submission-card">
                                <div class="submission-left">
                                    <div class="submission-avatar-container">
                                        <?php 
                                        $foto_hewan = 'uploads/hewan/' . ($sub['url_foto_hewan'] ?? '');
                                        if (!empty($sub['url_foto_hewan']) && file_exists(__DIR__ . '/../../' . $foto_hewan)): 
                                        ?>
                                            <img src="<?= htmlspecialchars($foto_hewan) ?>" alt="Foto <?= htmlspecialchars($sub['nama_hewan']) ?>">
                                        <?php else: ?>
                                            <span style="font-size: 36px;">🐾</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php 
                                    $pill_class = 'status-process';
                                    $pill_label = $sub['status_kontrak'];
                                    if ($sub['status_kontrak'] == 'Draft') {
                                        $pill_class = 'status-process';
                                        $pill_label = 'Menunggu Tanda Tangan';
                                    }
                                    if ($sub['status_kontrak'] == 'Aktif') $pill_class = 'status-approved';
                                    if ($sub['status_kontrak'] == 'Batal') $pill_class = 'status-rejected';
                                    ?>
                                    <span class="status-pill <?= $pill_class ?>" style="font-size: 11px; padding: 4px 10px;"><?= htmlspecialchars($pill_label) ?></span>
                                </div>

                                <div class="submission-right">
                                    <div class="submission-info-header">
                                        <div class="submission-title-group">
                                            <h3><?= htmlspecialchars($sub['nama_hewan']) ?></h3>
                                            <div class="submission-subtitle"><?= htmlspecialchars($sub['nama_jenis']) ?> &bull; Diajukan pada <?= date('d M Y', strtotime($sub['tanggal_adopsi'])) ?></div>
                                        </div>
                                        <div class="submission-actions">
                                            <button class="btn-sub-action btn-sub-secondary" onclick="openDetailModal(<?= htmlspecialchars(json_encode([
                                                'nama_hewan' => $sub['nama_hewan'],
                                                'kategori' => $sub['nama_jenis'],
                                                'tanggal' => date('d F Y', strtotime($sub['tanggal_adopsi'])),
                                                'status' => $sub['status_kontrak'],
                                                'nik' => $adopter['nik'] ?? '-',
                                                'alamat' => $adopter['alamat_lengkap'] ?? '-',
                                                'visit_kode' => $matched_visit['kode_jadwal_kunjungan'] ?? 'Belum ada jadwal',
                                                'visit_tgl' => $matched_visit ? date('d M Y, H:i', strtotime($matched_visit['tanggal_jadwal'])) : '-',
                                                'visit_status' => $matched_visit['status_jadwal'] ?? '-',
                                            ])) ?>)">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">info</span> Detail
                                            </button>

                                            <?php if (empty($sub['ttd_adopter']) && $sub['status_kontrak'] == 'Draft'): ?>
                                                <a href="index.php?page=kontrak_adopsi&id=<?= $sub['id_adopsi'] ?>" class="btn-sub-action btn-sub-primary">
                                                    <span class="material-symbols-outlined" style="font-size: 16px;">edit_document</span> Tanda Tangani Kontrak
                                                </a>
                                            <?php else: ?>
                                                <a href="index.php?page=kontrak_adopsi&id=<?= $sub['id_adopsi'] ?>" class="btn-sub-action btn-sub-secondary">
                                                    <span class="material-symbols-outlined" style="font-size: 16px;">description</span> Lihat Kontrak
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($sub['status_kontrak'] == 'Draft'): ?>
                                                <a href="index.php?page=proses_adopsi_batal&id=<?= $sub['id_adopsi'] ?>" 
                                                   class="btn-sub-action btn-sub-danger" 
                                                   onclick="return confirm('Apakah Anda yakin ingin membatalkan pengajuan adopsi ini?')">
                                                   <span class="material-symbols-outlined" style="font-size: 16px;">cancel</span> Batalkan
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Timeline Progress -->
                                    <div class="timeline-container">
                                        <div style="font-size: 12px; font-weight: 700; color: var(--text-main);">Progres Pengajuan Adopsi</div>
                                        <div class="timeline-steps">
                                            <div class="timeline-line-bg"></div>
                                            <?php if (!$is_cancelled): ?>
                                                <div class="timeline-line-active" style="width: <?= $progress_percent ?>%;"></div>
                                            <?php endif; ?>

                                            <!-- Step 1: Draft / Ttd Kontrak -->
                                            <div class="timeline-step <?= $is_cancelled ? 'cancelled' : (($step >= 1) ? (($step == 1) ? 'current' : 'active') : '') ?>">
                                                <div class="timeline-node">
                                                    <?php if ($is_cancelled): ?>✕<?php elseif ($step > 1): ?>✓<?php else: ?>1<?php endif; ?>
                                                </div>
                                                <div class="timeline-label">Draft / Ttd Kontrak</div>
                                            </div>

                                            <!-- Step 2: Verifikasi Koordinator -->
                                            <div class="timeline-step <?= $is_cancelled ? '' : (($step >= 2) ? (($step == 2) ? 'current' : 'active') : '') ?>">
                                                <div class="timeline-node">
                                                    <?php if (!$is_cancelled && $step > 2): ?>✓<?php else: ?>2<?php endif; ?>
                                                </div>
                                                <div class="timeline-label">Verifikasi Koordinator</div>
                                            </div>

                                            <!-- Step 3: Jadwal Kunjungan -->
                                            <div class="timeline-step <?= $is_cancelled ? '' : (($step >= 3) ? (($step == 3) ? 'current' : 'active') : '') ?>">
                                                <div class="timeline-node">
                                                    <?php if (!$is_cancelled && $step > 3): ?>✓<?php else: ?>3<?php endif; ?>
                                                </div>
                                                <div class="timeline-label">Jadwal Kunjungan</div>
                                            </div>

                                            <!-- Step 4: ttd Kontrak Koordinator -->
                                            <div class="timeline-step <?= $is_cancelled ? '' : (($step >= 4) ? (($step == 4) ? 'current' : 'active') : '') ?>">
                                                <div class="timeline-node">
                                                    <?php if (!$is_cancelled && $step > 4): ?>✓<?php else: ?>4<?php endif; ?>
                                                </div>
                                                <div class="timeline-label">ttd Kontrak Koordinator</div>
                                            </div>

                                            <!-- Step 5: Adopsi Selesai -->
                                            <div class="timeline-step <?= $is_cancelled ? '' : (($step >= 5) ? 'active' : '') ?>">
                                                <div class="timeline-node">
                                                    <?php if (!$is_cancelled && $step >= 5): ?>✓<?php else: ?>5<?php endif; ?>
                                                </div>
                                                <div class="timeline-label">Adopsi Selesai</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align:center; padding:50px; background:#FFF; border-radius:16px; border:1px solid #EAEAEA;">
                        <p style="color:#95a5a6;">Anda belum pernah mengajukan adopsi peliharaan.</p>
                    </div>
                <?php endif; ?>

                <!-- Modal Detail Pengajuan -->
                <div class="custom-modal" id="detailModal" onclick="closeDetailModal(event)">
                    <div class="modal-dialog" onclick="event.stopPropagation()">
                        <div class="modal-header">
                            <h3>Detail Pengajuan Adopsi</h3>
                            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <h4 style="font-size:15px; font-weight:700; color:var(--text-main); margin-bottom:12px; border-bottom:2px solid var(--border-color); padding-bottom:6px;">Informasi Peliharaan</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Nama Hewan</span>
                                    <span class="detail-value" id="det-hewan">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Kategori</span>
                                    <span class="detail-value" id="det-kategori">-</span>
                                </div>
                            </div>

                            <h4 style="font-size:15px; font-weight:700; color:var(--text-main); margin-bottom:12px; border-bottom:2px solid var(--border-color); padding-bottom:6px; margin-top:20px;">Informasi Pengadopsi</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="detail-label">NIK KTP</span>
                                    <span class="detail-value" id="det-nik">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Alamat Domisili</span>
                                    <span class="detail-value" id="det-alamat">-</span>
                                </div>
                            </div>

                            <h4 style="font-size:15px; font-weight:700; color:var(--text-main); margin-bottom:12px; border-bottom:2px solid var(--border-color); padding-bottom:6px; margin-top:20px;">Detail Transaksi & Kunjungan</h4>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Tanggal Pengajuan</span>
                                    <span class="detail-value" id="det-tanggal">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Status Kontrak</span>
                                    <span class="detail-value" id="det-status">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Jadwal Kunjungan</span>
                                    <span class="detail-value" id="det-visit-tgl">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Status Jadwal</span>
                                    <span class="detail-value" id="det-visit-status">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function openDetailModal(data) {
                        document.getElementById('det-hewan').innerText = data.nama_hewan;
                        document.getElementById('det-kategori').innerText = data.kategori;
                        document.getElementById('det-nik').innerText = data.nik;
                        document.getElementById('det-alamat').innerText = data.alamat;
                        document.getElementById('det-tanggal').innerText = data.tanggal;
                        document.getElementById('det-status').innerText = data.status;
                        document.getElementById('det-visit-tgl').innerText = data.visit_tgl !== '-' ? data.visit_kode + ' (' + data.visit_tgl + ')' : 'Belum Dijadwalkan';
                        document.getElementById('det-visit-status').innerText = data.visit_status !== '-' ? data.visit_status : '-';
                        
                        document.getElementById('detailModal').classList.add('open');
                    }

                    function closeDetailModal(e) {
                        document.getElementById('detailModal').classList.remove('open');
                    }
                </script>

            <?php elseif ($tab == 'kunjungan'): ?>
                <h2 class="section-title">Jadwal Kunjungan & Penjemputan</h2>
                <p style="color:#64748b; font-size:13px; margin-bottom:20px;">Jadwal kunjungan dibuat saat pengajuan adopsi melalui wizard. Tabel berikut adalah riwayat jadwal Anda.</p>

                <?php if (count($my_visits) > 0): ?>
                    <div class="table-wrapper">
                        <table class="submission-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Hewan</th>
                                    <th>Metode</th>
                                    <th>Waktu Rencana</th>
                                    <th>Petugas Shelter</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($my_visits as $v): ?>
                                    <tr>
                                        <td><span style="font-family: monospace; font-weight: 600;"><?= htmlspecialchars($v['kode_jadwal_kunjungan'] ?? '-') ?></span></td>
                                        <td><strong><?= htmlspecialchars($v['nama_hewan']) ?></strong></td>
                                        <td><?= htmlspecialchars($v['metode']) ?></td>
                                        <td><?= date('d M Y, H:i', strtotime($v['tanggal_jadwal'])) ?></td>
                                        <td><span style="color: #4b5563;"><?= htmlspecialchars($v['nama_petugas'] ?? 'Belum Ditentukan') ?></span></td>
                                        <td>
                                            <?php 
                                            $v_pill = 'status-process';
                                            if ($v['status_jadwal'] == 'Dikonfirmasi') $v_pill = 'status-approved';
                                            if ($v['status_jadwal'] == 'Selesai') $v_pill = 'status-approved';
                                            if ($v['status_jadwal'] == 'Batal') $v_pill = 'status-rejected';
                                            ?>
                                            <span class="status-pill <?= $v_pill ?>"><?= htmlspecialchars($v['status_jadwal']) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align:center; padding:40px; background:#FFF; border-radius:16px; border:1px solid #EAEAEA;">
                        <p style="color:#95a5a6; font-size:13px;">Belum ada pengajuan jadwal kunjungan.</p>
                    </div>
                <?php endif; ?>

            <?php elseif ($tab == 'petunjuk'): ?>
                <h2 class="section-title">Alur Proses Kemitraan Adopsi</h2>
                <div class="guide-grid">
                    <div class="guide-card">
                        <div class="guide-number">1</div>
                        <h4 style="margin-bottom:8px;">Verifikasi Data</h4>
                        <p style="font-size:13px; color:#7f8c8d;">Mengisi profil lengkap domisili dan NIK KTP pada sistem.</p>
                    </div>
                    <div class="guide-card">
                        <div class="guide-number">2</div>
                        <h4 style="margin-bottom:8px;">Pilih Peliharaan</h4>
                        <p style="font-size:13px; color:#7f8c8d;">Buka menu katalog untuk melihat daftar hewan peliharaan shelter yang siap diadopsi.</p>
                    </div>
                    <div class="guide-card">
                        <div class="guide-number">3</div>
                        <h4 style="margin-bottom:8px;">E-Contract TTD</h4>
                        <p style="font-size:13px; color:#7f8c8d;">Mengisi tanda tangan digital di canvas sebagai lembar komitmen hukum aman.</p>
                    </div>
                    <div class="guide-card">
                        <div class="guide-number">4</div>
                        <h4 style="margin-bottom:8px;">Penjemputan</h4>
                        <p style="font-size:13px; color:#7f8c8d;">Datang ke shelter untuk serah terima fisik setelah disetujui admin.</p>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <!-- Footer Section -->
    <footer style="background-color: #0F172A; color: #FFFFFF; padding: 60px 5% 30px; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1);">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; max-width: 1200px; margin: 0 auto; margin-bottom: 40px;">
            <div style="grid-column: span 2; min-width: 280px;">
                <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; margin-bottom: 20px; color: #FFFFFF;">PawCare</h3>
                <p style="color: #94A3B8; font-size: 14px; line-height: 1.6; max-width: 320px;">Misi kami adalah memastikan setiap hewan peliharaan mendapatkan cinta yang mereka butuhkan dan setiap pemilik mendapatkan teman seumur hidup.</p>
            </div>
            <div>
                <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 20px; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.5px;">Perusahaan</h4>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 12px;">
                    <li><a href="#" style="color: #94A3B8; text-decoration: none; font-size: 14px; transition: color 0.2s;">Tentang Kami</a></li>
                    <li><a href="#" style="color: #94A3B8; text-decoration: none; font-size: 14px; transition: color 0.2s;">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 20px; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.5px;">Dukungan</h4>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 12px;">
                    <li><a href="#" style="color: #94A3B8; text-decoration: none; font-size: 14px; transition: color 0.2s;">Kebijakan Privasi</a></li>
                    <li><a href="#" style="color: #94A3B8; text-decoration: none; font-size: 14px; transition: color 0.2s;">Syarat & Ketentuan</a></li>
                </ul>
            </div>
            <div>
                <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 20px; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.5px;">Lainnya</h4>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 12px;">
                    <li><a href="#" style="color: #94A3B8; text-decoration: none; font-size: 14px; transition: color 0.2s;">Donasi</a></li>
                </ul>
            </div>
        </div>
        <div style="max-width: 1200px; margin: 0 auto; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <p style="color: #94A3B8; font-size: 13px;">&copy; 2024 PawCare. Menghubungkan Hati, Menemukan Rumah.</p>
            <div style="display: flex; gap: 16px;">
                <a href="#" style="color: #94A3B8; transition: color 0.2s;"><span class="material-symbols-outlined" style="font-size: 20px;">share</span></a>
                <a href="#" style="color: #94A3B8; transition: color 0.2s;"><span class="material-symbols-outlined" style="font-size: 20px;">mail</span></a>
            </div>
        </div>
    </footer>

    <?php include __DIR__ . '/chatbot_widget.php'; ?>
    <script>
        // Efek latar belakang shader dinamis dengan canvas
        const canvas = document.getElementById('shader-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let w = canvas.width = window.innerWidth;
            let h = canvas.height = window.innerHeight;
            
            window.addEventListener('resize', () => {
                w = canvas.width = window.innerWidth;
                h = canvas.height = window.innerHeight;
            });
            
            let time = 0;
            function drawShader() {
                time += 0.003;
                ctx.clearRect(0, 0, w, h);
                
                // Draw dynamic subtle gradient wave
                const cx = w / 2 + Math.sin(time) * (w * 0.15);
                const cy = h / 2 + Math.cos(time * 0.8) * (h * 0.15);
                const radius = Math.max(w, h) * 0.8;
                
                const grad = ctx.createRadialGradient(cx, cy, 20, w / 2, h / 2, radius);
                grad.addColorStop(0, '#ffdbce');
                grad.addColorStop(0.5, '#fef2ec');
                grad.addColorStop(1, '#f7f9fb');
                
                ctx.fillStyle = grad;
                ctx.fillRect(0, 0, w, h);
                
                requestAnimationFrame(drawShader);
            }
            drawShader();
        }
    </script>
</body>
</html>