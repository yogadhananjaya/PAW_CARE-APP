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
    $stmt_hewan = $pdo->query("SELECT h.*, j.nama_jenis, r.nama_ras FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status = 'Tersedia' ORDER BY h.id_hewan DESC");
    $katalog_hewan = $stmt_hewan->fetchAll();
}

// 3. Ambil data pengajuan adopsi milik user ini (untuk tab Pengajuan Saya)
$my_submissions = [];
if ($adopter) {
    $stmt_sub = $pdo->prepare("SELECT t.*, h.nama_hewan, h.foto, j.nama_jenis FROM transaksi_adopsi t JOIN hewan h ON t.id_hewan = h.id_hewan JOIN jenis_hewan j ON h.id_jenis = j.id_jenis WHERE t.id_pengadopsi = ? ORDER BY t.id_transaksi DESC");
    $stmt_sub->execute([$adopter['id_pengadopsi']]);
    $my_submissions = $stmt_sub->fetchAll();
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
                <a href="index.php?page=dashboard_user&tab=beranda" class="nav-item <?= $tab == 'beranda' ? 'active' : '' ?>">Beranda</a>
                <a href="index.php?page=dashboard_user&tab=katalog" class="nav-item <?= $tab == 'katalog' ? 'active' : '' ?>">Katalog</a>
                <a href="index.php?page=dashboard_user&tab=pengajuan" class="nav-item <?= $tab == 'pengajuan' ? 'active' : '' ?>">Pengajuan Saya</a>
                <a href="index.php?page=dashboard_user&tab=petunjuk" class="nav-item <?= $tab == 'petunjuk' ? 'active' : '' ?>">Petunjuk Adopsi</a>
            </div>
        </div>
        <div class="nav-right">
            <div class="user-profile">
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
                <p style="color:#95a5a6; font-size:14px; margin-bottom:25px;">Silakan isi data diri sesuai KTP untuk mengaktifkan seluruh fitur dashboard Anda.</p>
                <form action="index.php?page=process_verifikasi" method="POST">
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap Sesuai KTP" required>
                    <input type="text" name="nik" class="form-control" placeholder="Nomor Induk Kependudukan (NIK)" required>
                    <input type="text" name="no_hp" class="form-control" placeholder="Nomor WhatsApp" required>
                    <textarea name="alamat" class="form-control" placeholder="Alamat Lengkap Domisili" rows="3" required></textarea>
                    <button type="submit" class="btn-adopt" style="margin-top:10px;">Simpan & Verifikasi Otomatis</button>
                </form>
            </div>

        <?php else: ?>
            
            <?php if ($tab == 'beranda'): ?>
                <div class="welcome-card">
                    <h2>Selamat Datang Kembali, <?= htmlspecialchars($adopter['nama_lengkap']) ?>! ✨</h2>
                    <p>Akun Anda berstatus terverifikasi aktif. Gunakan menu navigasi di atas untuk menjelajahi katalog hewan atau melacak pengajuan Anda.</p>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="welcome-card" style="margin: 0;">
                        <h4 style="margin-bottom: 10px;">📋 Ringkasan Aktivitas</h4>
                        <p style="color:#555;">Anda telah mendaftarkan profil pengadopsi dengan NIK: <strong><?= htmlspecialchars($adopter['nik']) ?></strong>.</p>
                    </div>
                    <div class="welcome-card" style="margin: 0; border-left: 4px solid #e67e22;">
                        <h4 style="margin-bottom: 10px;">💡 Tips Hari Ini</h4>
                        <p style="color:#555;">Pastikan Anda telah membaca 'Petunjuk Adopsi' sebelum mengajukan kontrak agar proses serah terima berjalan lancar.</p>
                    </div>
                </div>

            <?php elseif ($tab == 'katalog'): ?>
                <h2 class="section-title">Katalog Hewan Tersedia</h2>
                <?php if (count($katalog_hewan) > 0): ?>
                    <div class="catalog-grid">
                        <?php foreach ($katalog_hewan as $hewan): ?>
                            <div class="pet-card">
                                <?php if (!empty($hewan['foto'])): ?>
                                    <img src="assets/img/hewan/<?= htmlspecialchars($hewan['foto']) ?>" class="pet-img" alt="Foto">
                                <?php else: ?>
                                    <div class="pet-img" style="display:flex; align-items:center; justify-content:center; color:#bdc3c7; font-size:13px;">No Visual</div>
                                <?php endif; ?>
                                <div class="pet-name"><?= htmlspecialchars($hewan['nama_hewan']) ?></div>
                                <div class="pet-breed"><?= htmlspecialchars($hewan['nama_jenis']) ?> - <?= htmlspecialchars($hewan['nama_ras']) ?></div>
                                <div class="pet-tags">
                                    <span class="tag"><?= htmlspecialchars($hewan['jenis_kelamin']) ?></span>
                                    <span class="tag"><?= htmlspecialchars($hewan['umur']) ?></span>
                                </div>
                                <a href="index.php?page=tanda_tangan&id=<?= $hewan['id_hewan'] ?>" class="btn-adopt">Ajukan Adopsi</a>
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
                                            <?php if (!empty($sub['e_contract'])): ?>
                                                <span style="color:#2ecc71; font-weight:600; font-size:13px;">✍️ Signed (Digital Canvas)</span>
                                            <?php else: ?>
                                                <span style="color:#7f8c8d; font-size:13px;">Belum Tanda Tangan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $pill_class = 'status-process';
                                            if ($sub['status_adopsi'] == 'Disetujui') $pill_class = 'status-approved';
                                            if ($sub['status_adopsi'] == 'Ditolak') $pill_class = 'status-rejected';
                                            ?>
                                            <span class="status-pill <?= $pill_class ?>"><?= htmlspecialchars($sub['status_adopsi']) ?></span>
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