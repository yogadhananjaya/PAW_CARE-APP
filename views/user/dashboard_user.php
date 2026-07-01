<?php
require_once __DIR__ . '/../../config/connect.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Ambil data profil pengadopsi
$stmt = $pdo->prepare("SELECT * FROM pengadopsi WHERE id_pengguna = ?");
$stmt->execute([$user_id]);
$adopter = $stmt->fetch();

// Menentukan tab aktif (default: beranda)
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'beranda';

// 2. Ambil data katalog hewan jika akun terverifikasi
$katalog_hewan = [];
if ($adopter) {
    $stmt_hewan = $pdo->query("SELECT h.*, j.nama_jenis, r.nama_ras FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status_adopsi = 'Tersedia' AND h.rekomendasi_adopsi = 1 ORDER BY h.id_hewan DESC");
    $katalog_hewan = $stmt_hewan->fetchAll();
}

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
    <style>
        /* CSS Dashboard Standar Sesuai Gambar Referensi */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #F8F9FA; color: #2c3e50; }

        /* Top Navbar Styling */
        .top-navbar {
            display: flex; justify-content: space-between; align-items: center;
            background: #FFFFFF; padding: 15px 5%; border-bottom: 1px solid #EAEAEA;
            position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
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
        .main-content { max-width: 1200px; margin: 0 auto; padding: 40px 5%; }

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

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .main-content { padding: 20px; }
        }
    </style>
</head>
<body>

    <nav class="top-navbar">
        <div class="nav-left">
            <a href="index.php?page=dashboard_user" class="navbar-brand">🐾 PawCare</a>
            <div class="nav-links">
                <?php 
                $is_unverified = (!$adopter || in_array($adopter['status_verifikasi'], ['Belum', 'Menunggu', 'Ditolak']));
                ?>
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
        
        <?php if (!$adopter): ?>
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
                <?php if ($adopter['status_verifikasi'] === 'Belum'): ?>
                    <div class="verify-card" style="margin: 0 0 20px 0;">
                        <h2>Lengkapi Data Diri</h2>
                        <p style="color:#95a5a6; font-size:14px; margin-bottom:25px;">Silakan isi data diri sesuai KTP dan unggah foto KTP Anda untuk mengajukan adopsi.</p>
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
                            <button type="submit" class="btn-adopt" style="margin-top:15px; width: 100%;">Simpan & Ajukan Verifikasi</button>
                        </form>
                    </div>
                <?php elseif ($adopter['status_verifikasi'] === 'Menunggu'): ?>
                    <div class="welcome-card" style="border-left: 4px solid #d35400;">
                        <h2>Dokumen Anda Sedang Ditinjau ⏳</h2>
                        <p>Terima kasih telah melengkapi data diri. Saat ini berkas pendaftaran dan foto KTP Anda sedang dalam proses peninjauan oleh tim admin kami.</p>
                        <p style="color:#7f8c8d; font-size:13px; margin-top:10px;">Fitur adopsi dan jadwal kunjungan akan aktif setelah akun Anda disetujui.</p>
                    </div>
                <?php elseif ($adopter['status_verifikasi'] === 'Ditolak'): ?>
                    <div class="welcome-card" style="border-left: 4px solid #c0392b; background-color: #fdedec;">
                        <h2 style="color:#c0392b;">Verifikasi Akun Ditolak ❌</h2>
                        <p>Mohon maaf, pengajuan verifikasi akun Anda ditolak oleh admin.</p>
                        <?php if (!empty($adopter['catatan_verifikasi'])): ?>
                            <div style="background:#FFF; padding:15px; border-radius:8px; border: 1px solid #c0392b; margin-top:15px;">
                                <strong style="color:#c0392b;">Catatan Penolakan:</strong>
                                <p style="margin:5px 0 0; color:#555;"><?= htmlspecialchars($adopter['catatan_verifikasi']) ?></p>
                            </div>
                        <?php endif; ?>
                        <p style="margin-top:20px; font-weight:600; color:#2c3e50;">Silakan perbaiki data diri Anda dan kirim ulang:</p>
                        <form action="index.php?page=process_verifikasi" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
                            <div style="margin-bottom: 15px; text-align: left;">
                                <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Nama Lengkap (Sesuai KTP) *</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($adopter['nama_lengkap']) ?>" required>
                            </div>
                            <div style="margin-bottom: 15px; text-align: left;">
                                <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">NIK *</label>
                                <input type="text" name="nik" class="form-control" value="<?= htmlspecialchars($adopter['nik']) ?>" required pattern="[0-9]{16}" title="NIK harus berupa 16 digit angka">
                            </div>
                            <div style="margin-bottom: 15px; text-align: left;">
                                <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Nomor WhatsApp *</label>
                                <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($adopter['no_hp']) ?>" required>
                            </div>
                            <div style="margin-bottom: 15px; text-align: left;">
                                <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Email *</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($adopter['email']) ?>" placeholder="Contoh: nama@email.com" required>
                            </div>
                            <div style="margin-bottom: 15px; text-align: left;">
                                <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Alamat Domisili Lengkap *</label>
                                <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($adopter['alamat']) ?></textarea>
                            </div>
                            <div style="margin-bottom: 15px; text-align: left;">
                                <label style="font-size: 13px; font-weight: 600; color: #2c3e50; display: block; margin-bottom: 5px;">Foto KTP Baru *</label>
                                <input type="file" name="foto_ktp" class="form-control" accept="image/*" required style="padding: 8px 12px;">
                                <small style="color: #7f8c8d; font-size: 11px; display: block; margin-top: 2px;">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                            </div>
                            <button type="submit" class="btn-adopt" style="margin-top:15px; width: 100%;">Kirim Ulang Data & KTP</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="welcome-card">
                        <h2>Selamat Datang Kembali, <?= htmlspecialchars($adopter['nama_lengkap']) ?>! ✨</h2>
                        <p>Akun Anda berstatus terverifikasi aktif. Gunakan menu navigasi di atas untuk menjelajahi katalog hewan atau melacak pengajuan Anda.</p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="welcome-card" style="margin: 0;">
                            <h4 style="margin-bottom: 10px;">📋 Ringkasan Aktivitas</h4>
                            <p style="color:#555;">Anda telah mendaftarkan profil pengadopsi dengan NIK: <strong><?= htmlspecialchars($adopter['nik'] ?? '') ?></strong>.</p>
                        </div>
                        <div class="welcome-card" style="margin: 0; border-left: 4px solid #e67e22;">
                            <h4 style="margin-bottom: 10px;">💡 Tips Hari Ini</h4>
                            <p style="color:#555;">Pastikan Anda telah membaca 'Petunjuk Adopsi' sebelum mengajukan kontrak agar proses serah terima berjalan lancar.</p>
                        </div>
                    </div>
                <?php endif; ?>
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
                                <a href="index.php?page=proses_adopsi&id=<?= $hewan['id_hewan'] ?>" class="btn-adopt">Adopsi Sekarang</a>
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
</body>
</html>