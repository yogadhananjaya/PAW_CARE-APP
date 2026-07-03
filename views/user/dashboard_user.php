<?php
require_once __DIR__ . '/../../config/connect.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Ambil data profil pengadopsi
$stmt = $pdo->prepare("SELECT * FROM pengadopsi WHERE id_pengguna = ? OR id_pengadopsi = ?");
$stmt->execute([$user_id, $user_id]);
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
    $stmt_hewan = $pdo->query("SELECT h.*, j.nama_jenis, r.nama_ras FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status_adopsi = 'Tersedia' AND h.rekomendasi_adopsi = 1 ORDER BY h.id_hewan DESC");
    $katalog_hewan = $stmt_hewan->fetchAll();
}

// Ambil data gallery hewan yang tersedia untuk ditampilkan pada beranda
$gallery_pets = [];
$stmt_gallery = $pdo->query("SELECT h.*, j.nama_jenis, r.nama_ras FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status_adopsi = 'Tersedia' ORDER BY h.id_hewan DESC LIMIT 6");
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Adopter - PawCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <style>
        /* CSS Dashboard Standar Sesuai Gambar Referensi */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #fff; color: #2c3e50; min-height: 100vh; overflow-x: hidden; }

        /* Top Navbar Styling */
        .top-navbar {
            display: flex; justify-content: space-between; align-items: center;
            background: #FFFFFF; padding: 20px 5%; border-bottom: 1px solid rgba(255,156,55,0.16);
            position: sticky; top: 0; z-index: 100; box-shadow: 0 18px 60px rgba(255,156,55,0.12);
        }
        
        .nav-left { display: flex; align-items: center; gap: 40px; }
        .navbar-brand { color: #e67e22; font-weight: 800; font-size: 22px; text-decoration: none; }
        
        /* 4 Menu Utama Sesuai Permintaan Gambar */
        .nav-links { display: flex; gap: 8px; }
        .nav-item { 
            padding: 10px 20px; color: #95a5a6; font-weight: 600; font-size: 14px;
            border-radius: 25px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; 
        }
        .nav-item:hover { color: #e67e22; background: #FFF4EC; }
        .nav-item.active { background: #FFF4EC; color: #e67e22; }

        .nav-right { display: flex; align-items: center; gap: 20px; }
        
        /* Profile Ringkas */
        .user-profile { 
            display: flex; align-items: center; gap: 10px; background: #FFFFFF; 
            padding: 6px 14px 6px 6px; border-radius: 30px; border: 1px solid #EAEAEA; 
        }
        .avatar { 
            width: 32px; height: 32px; border-radius: 50%; background: #e67e22; 
            color: #FFF; display: flex; align-items: center; justify-content: center; 
            font-weight: 700; font-size: 14px; text-transform: uppercase;
        }
        .user-greeting { font-weight: 600; font-size: 13px; color: #2c3e50; }

        .logout-btn { color: #e74c3c; font-weight: 600; font-size: 14px; text-decoration: none; transition: 0.3s; }
        .logout-btn:hover { color: #c0392b; }

        /* Main Content Grid */
        .main-content { width: 100%; margin: 0; padding: 30px 4%; }

        /* Hero Section */
        .hero-section { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 28px; align-items: center; min-height: calc(100vh - 110px); background: linear-gradient(135deg, #fff9ec 0%, #ffe8bd 38%, #ffd186 70%, #fff9f0 100%); color: #2b2b2b; padding: 50px 4%; border-radius: 0 0 40px 40px; margin-bottom: 30px; overflow: hidden; position: relative; }
        .hero-section::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at top right, rgba(255,255,255,0.85), transparent 22%); pointer-events: none; }
        .hero-content { position: relative; z-index: 1; }
        .hero-badge { display: inline-flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.9); padding: 12px 18px; border-radius: 999px; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 20px; color: #c16104; box-shadow: 0 18px 60px rgba(255,184,79,0.12); }
        .hero-badge strong { color: #d97706; }
        .hero-title { font-size: clamp(3rem, 5vw, 5rem); line-height: 0.98; font-weight: 900; margin-bottom: 20px; max-width: 580px; color: #1d1d1d; }
        .hero-title span { color: #f97316; }
        .hero-subtitle { max-width: 640px; color: #5b4b33; font-size: 1.05rem; margin-bottom: 28px; line-height: 1.75; }
        .hero-buttons { display: flex; flex-wrap: wrap; gap: 16px; }
        .hero-buttons a { padding: 16px 34px; border-radius: 999px; font-weight: 700; font-size: 0.95rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .hero-buttons a.btn-primary { background: linear-gradient(135deg, #ffb703 0%, #fb8500 100%); color: #1c1c1c; box-shadow: 0 24px 50px rgba(251,133,0,0.25); }
        .hero-buttons a.btn-secondary { background: rgba(255,255,255,0.96); color: #1d1d1d; }
        .hero-buttons a:hover { transform: translateY(-2px); box-shadow: 0 22px 48px rgba(0,0,0,0.12); }
        .hero-image { position: relative; display: flex; justify-content: center; align-items: center; transform-style: preserve-3d; }
        .hero-image .hero-photo { width: 100%; max-width: 540px; border-radius: 36px; box-shadow: 0 45px 100px rgba(255,156,55,0.2); border: 8px solid rgba(255,255,255,0.85); background: #fff; transition: transform 0.6s ease; animation: floatImage 8s ease-in-out infinite; }
        .hero-image:hover .hero-photo { transform: translateY(-10px) scale(1.03); }
        .hero-image::after { content: ''; position: absolute; width: 160px; height: 160px; border-radius: 50%; background: rgba(255,183,62,0.16); filter: blur(20px); top: -30px; right: -50px; pointer-events: none; }
        .scroll-indicator { display: flex; flex-direction: column; align-items: center; gap: 8px; color: #d97706; margin: 0 auto 30px; text-align: center; }
        .scroll-indicator span { font-size: 0.78rem; letter-spacing: 0.16em; text-transform: uppercase; opacity: 0.9; }
        .scroll-indicator .arrow { width: 18px; height: 18px; border-left: 2px solid currentColor; border-bottom: 2px solid currentColor; transform: rotate(-45deg); animation: scrollBounce 1.6s infinite; }
        @keyframes floatImage { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        @keyframes scrollBounce { 0%, 20% { transform: translate(-50%, 0) rotate(-45deg); opacity: 0.8; } 50% { transform: translate(-50%, 6px) rotate(-45deg); opacity: 1; } 100% { transform: translate(-50%, 0) rotate(-45deg); opacity: 0.8; } }
        .hero-image .hero-card { position: absolute; right: 18px; bottom: 18px; width: 240px; background: rgba(255,255,255,0.96); border-radius: 28px; padding: 20px 22px; color: #1f2937; box-shadow: 0 22px 45px rgba(15,23,42,0.14); border: 1px solid rgba(255,156,55,0.16); }
        .hero-image .hero-card h4 { margin-bottom: 8px; font-size: 1rem; font-weight: 800; }
        .hero-image .hero-card p { margin: 0; color: #475569; font-size: 0.95rem; line-height: 1.65; }

        .hero-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; margin-top: 28px; }
        .hero-stat { background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.72)); border: 1px solid rgba(255,255,255,0.55); border-radius: 20px; padding: 18px 20px; }
        .hero-stat strong { display: block; font-size: 1.55rem; font-weight: 800; color: #d97706; margin-bottom: 8px; }
        .hero-stat span { font-size: 0.85rem; color: #7a5a25; }

        .section-title { font-size: 22px; font-weight: 700; color: #1A1A1A; margin-bottom: 25px; }

        /* Welcome Card */
        .welcome-card { background: #FFF; padding: 30px; border-radius: 20px; border: 1px solid #EAEAEA; margin-bottom: 30px; }
        .welcome-card h2 { font-size: 24px; font-weight: 700; color: #1A1A1A; margin-bottom: 5px; }
        .welcome-card p { color: #95a5a6; font-size: 14px; }

        /* Form Pengisian Data Diri (Jika Belum Verifikasi) */
        .verify-card { 
            background: #FFF; padding: 40px; border-radius: 20px; 
            border: 1px solid #EAEAEA; max-width: 600px; margin: 0 auto; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .verify-card h2 { margin-bottom: 8px; font-weight: 700; color: #1A1A1A; }
        .form-control { 
            width: 100%; padding: 12px 15px; border: 1px solid #ddd; 
            border-radius: 8px; margin-bottom: 15px; outline: none; font-family: 'Poppins', sans-serif;
            transition: 0.3s; font-size: 14px;
        }
        .form-control:focus { border-color: #e67e22; }

        /* Pet Card Grid (Katalog) */
        .section-title { font-size: 22px; font-weight: 700; color: #1A1A1A; margin-bottom: 25px; }
        .catalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px; }

        .pet-card { 
            background: #FFFFFF; border-radius: 16px; padding: 15px; 
            border: 1px solid #EAEAEA; transition: 0.3s; display: flex; flex-direction: column; 
        }
        .pet-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-color: #e67e22; }
        .pet-img { width: 100%; height: 190px; border-radius: 12px; object-fit: cover; margin-bottom: 15px; background: #F4F4F4; }
        
        .pet-name { font-size: 18px; font-weight: 700; color: #1A1A1A; margin-bottom: 2px; }
        .pet-breed { font-size: 13px; color: #95a5a6; font-weight: 500; margin-bottom: 15px; }
        
        .pet-tags { display: flex; gap: 6px; margin-bottom: 20px; }
        .tag { background: #FFF4EC; color: #e67e22; font-size: 11px; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
        
        .btn-adopt { 
            margin-top: auto; width: 100%; background: #e67e22; color: #FFF; 
            border: none; padding: 12px; border-radius: 10px; font-weight: 600; 
            cursor: pointer; transition: 0.3s; text-align: center; text-decoration: none; display: block;
        }
        .btn-adopt:hover { background: #d35400; }

        /* Tabel Pengajuan Saya */
        .table-wrapper { background: #FFF; border-radius: 16px; border: 1px solid #EAEAEA; overflow: hidden; }
        .submission-table { width: 100%; border-collapse: collapse; text-align: left; }
        .submission-table th { background: #FAF9F6; padding: 15px 20px; font-size: 13px; font-weight: 700; text-transform: uppercase; color: #7f8c8d; }
        .submission-table td { padding: 15px 20px; border-top: 1px solid #EAEAEA; font-size: 14px; }
        
        .status-pill { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block; }
        .status-process { background: #E1F5FE; color: #0288D1; }
        .status-approved { background: #E2FBE8; color: #2ECC71; }
        .status-rejected { background: #FCE4E4; color: #E74C3C; }

        /* Petunjuk Adopsi Step Cards */
        .guide-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .guide-card { background: #FFF; padding: 25px; border-radius: 16px; border: 1px solid #EAEAEA; text-align: center; }
        .guide-number { width: 40px; height: 40px; background: #e67e22; color: #FFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; margin: 0 auto 15px; }

        .gallery-section { margin-top: 30px; }
        .gallery-section h2 { color: #1f2937; margin-bottom: 18px; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .gallery-card { position: relative; border-radius: 26px; overflow: hidden; min-height: 260px; box-shadow: 0 25px 70px rgba(0,0,0,0.08); }
        .gallery-card img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .gallery-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, transparent 35%, rgba(20,20,20,0.65) 100%); opacity: 0; transition: opacity .3s ease; }
        .gallery-card:hover .gallery-overlay { opacity: 1; }
        .gallery-label { position: absolute; left: 20px; bottom: 20px; color: #fff; z-index: 2; }
        .gallery-label h4 { margin: 0 0 6px; font-size: 18px; line-height: 1.2; }
        .gallery-label span { font-size: 13px; color: #f9e6c2; }

        @media (max-width: 1024px) {
            .hero-section { grid-template-columns: 1fr; padding: 36px 5%; min-height: auto; }
            .hero-image { margin-top: 20px; }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .main-content { padding: 20px 3%; }
            .hero-title { font-size: 2.8rem; }
            .hero-section { padding: 30px 3%; border-radius: 20px; }
            .hero-buttons { flex-direction: column; width: 100%; }
            .hero-buttons a { width: 100%; }
        }
    </style>
</head>
<body>

    <nav class="top-navbar">
        <div class="nav-left">
            <a href="index.php?page=dashboard_user" class="navbar-brand">🐾 PawCare</a>
            <div class="nav-links">
                <a href="index.php?page=dashboard_user&tab=beranda" class="nav-item <?= $tab == 'beranda' ? 'active' : '' ?>">Beranda</a>
                
                <?php if ($is_unverified): ?>
                    <a href="#" class="nav-item" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;">Katalog</a>
                    <a href="#" class="nav-item" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;">Pengajuan Saya</a>
                    <a href="#" class="nav-item" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;">Jadwal Kunjungan</a>
                    <a href="#" class="nav-item" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;">Petunjuk Adopsi</a>
                <?php else: ?>
                    <a href="index.php?page=dashboard_user&tab=katalog" class="nav-item <?= $tab == 'katalog' ? 'active' : '' ?>">Katalog</a>
                    <a href="index.php?page=dashboard_user&tab=pengajuan" class="nav-item <?= $tab == 'pengajuan' ? 'active' : '' ?>">Pengajuan Saya</a>
                    <a href="index.php?page=dashboard_user&tab=kunjungan" class="nav-item <?= $tab == 'kunjungan' ? 'active' : '' ?>">Jadwal Kunjungan</a>
                    <a href="index.php?page=dashboard_user&tab=petunjuk" class="nav-item <?= $tab == 'petunjuk' ? 'active' : '' ?>">Petunjuk Adopsi</a>
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
                        <div class="hero-badge"><strong>PawCare</strong> Shelter & Adopsi</div>
                        <h1 class="hero-title">Live Your<br><span>Pet Adoption Adventure</span></h1>
                        <p class="hero-subtitle">Temukan hewan peliharaan yang tepat, pelihara dengan cinta, dan jadikan momen adopsi Anda penuh arti bersama PawCare.</p>
                        <div class="hero-buttons">
                            <a href="index.php?page=dashboard_user&tab=katalog" class="btn-primary">Lihat Katalog</a>
                            <a href="index.php?page=dashboard_user&tab=pengajuan" class="btn-secondary">Riwayat Pengajuan</a>
                        </div>
                        <div class="hero-stats">
                            <div class="hero-stat"><strong>1.200+</strong><span>Hewan Diselamatkan</span></div>
                            <div class="hero-stat"><strong>840+</strong><span>Adopsi Sukses</span></div>
                            <div class="hero-stat"><strong>4.9/5</strong><span>Rating Kepuasan</span></div>
                        </div>
                    </div>
                    <div class="hero-image">
                        <img src="https://images.unsplash.com/photo-1518020382113-a7e8fc38eac9?auto=format&fit=crop&w=900&q=80" alt="Hewan Peliharaan" class="hero-photo">
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
                    <div class="section-heading" style="margin-bottom: 18px;">
                        <h2>Galeri Pilihan</h2>
                        <p style="color:#64748b; font-size:14px; max-width: 720px;">Lihat beberapa hewan yang siap adopsi dan temukan sahabat baru dengan mudah.</p>
                    </div>
                    <div class="gallery-grid">
                        <?php foreach ($gallery_pets as $pet): ?>
                            <?php
                                $imagePath = !empty($pet['url_foto_hewan']) ? "uploads/hewan/{$pet['url_foto_hewan']}" : "assets/img/logo.png";
                                $petName = htmlspecialchars($pet['nama_hewan']);
                                $petType = htmlspecialchars($pet['nama_jenis']);
                            ?>
                            <article class="gallery-card">
                                <img src="<?= htmlspecialchars($imagePath) ?>" alt="Foto <?= $petName ?>">
                                <div class="gallery-overlay"></div>
                                <div class="gallery-label">
                                    <h4><?= $petName ?></h4>
                                    <span><?= $petType ?></span>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php elseif ($tab == 'katalog'): ?>
                <h2 class="section-title">Katalog Hewan Tersedia</h2>
                <?php if (count($katalog_hewan) > 0): ?>
                    <div class="catalog-grid">
                        <?php foreach ($katalog_hewan as $hewan): ?>
                            <div class="pet-card">
                                <?php 
                                $foto_path = 'assets/img/hewan/' . ($hewan['url_foto_hewan'] ?? '');
                                if (!empty($hewan['url_foto_hewan']) && file_exists(__DIR__ . '/../../' . $foto_path)): 
                                ?>
                                    <img src="<?= htmlspecialchars($foto_path) ?>" class="pet-img" alt="Foto">
                                <?php else: ?>
                                    <div class="pet-img" style="display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:32px; background:#f1f5f9;">🐾</div>
                                <?php endif; ?>
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
                <?php else: ?>
                    <p style="color:#95a5a6; text-align:center; padding:4px 0;">Tidak ada hewan yang tersedia saat ini.</p>
                <?php endif; ?>

            <?php elseif ($tab == 'pengajuan'): ?>
                <h2 class="section-title">Riwayat Pengajuan Adopsi Anda</h2>
                <?php if (count($my_submissions) > 0): ?>
                    <div class="table-wrapper">
                        <table class="submission-table">
                            <thead>
                                <tr>
                                    <th>Nama Hewan</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>E-Contract</th>
                                    <th>Status Persetujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($my_submissions as $sub): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($sub['nama_hewan']) ?></strong> (<?= htmlspecialchars($sub['nama_jenis']) ?>)</td>
                                        <td><?= htmlspecialchars($sub['tanggal_adopsi']) ?></td>
                                        <td>
                                            <?php if (!empty($sub['ttd_adopter'])): ?>
                                                <span style="color:#2ecc71; font-weight:600; font-size:13px;">✍️ Signed (Digital Canvas)</span>
                                            <?php elseif ($sub['status_kontrak'] == 'Draft'): ?>
                                                <a href="index.php?page=kontrak_adopsi&id=<?= $sub['id_adopsi'] ?>" style="display:inline-block;background:#4f46e5;color:#fff;text-decoration:none;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;">📄 Lihat & Tanda Tangani</a>
                                            <?php else: ?>
                                                <span style="color:#7f8c8d; font-size:13px;">Belum Tanda Tangan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
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
                                            <span class="status-pill <?= $pill_class ?>"><?= htmlspecialchars($pill_label) ?></span>

                                            <?php if ($sub['status_kontrak'] == 'Draft'): ?>
                                                <a href="index.php?page=proses_adopsi_batal&id=<?= $sub['id_adopsi'] ?>" 
                                                   class="status-pill status-rejected" 
                                                   style="text-decoration: none; margin-left: 8px; font-weight: bold;" 
                                                   onclick="return confirm('Apakah Anda yakin ingin membatalkan pengajuan adopsi ini?')">
                                                   ❌ Batalkan
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align:center; padding:50px; background:#FFF; border-radius:16px; border:1px solid #EAEAEA;">
                        <p style="color:#95a5a6;">Anda belum pernah mengajukan adopsi peliharaan.</p>
                    </div>
                <?php endif; ?>

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
    <?php include __DIR__ . '/chatbot_widget.php'; ?>
</body>
</html>