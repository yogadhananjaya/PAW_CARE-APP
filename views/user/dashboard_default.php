<?php
// Menarik data hewan dari database untuk ditampilkan di Homepage
require_once __DIR__ . '/../../config/connect.php';
$stmt_hewan = $pdo->query("SELECT h.*, j.nama_jenis, r.nama_ras FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status = 'Tersedia' ORDER BY h.id_hewan DESC LIMIT 6");
$katalog_hewan = $stmt_hewan->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>PawCare - Adopt Your Pet Friend</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="nav-brand">🐾 PawCare</a>
        <div class="nav-center hidden-mobile">
            <span>🔍</span><input type="text" placeholder="Cari hewan..."><span>|</span>
            <select><option>Semua Lokasi</option><option>Jakarta</option></select>
        </div>
        <div class="nav-right">
            <a href="index.php?page=login" class="nav-btn btn-outline hidden-mobile">Masuk</a>
            <a href="index.php?page=login" class="nav-btn btn-fill">Daftar / Login</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="tagline">🐾 Lebih dari sekadar Shelter</div>
            <h1>Adopt your<br><span>pet friend</span></h1>
            <p>Berikan rumah permanen yang penuh kasih. Temukan sahabat bulu Anda hari ini dan selamatkan nyawa mereka.</p>
            <a href="#katalog" class="nav-btn btn-fill" style="padding: 15px 40px;">Lihat Katalog</a>
        </div>
        <div class="hero-image hidden-mobile">
            <div class="bg-circle"></div>
            <<img src="https://cdn-icons-png.flaticon.com/512/194/194279.png" alt="Happy Pet" class="pet-img">
        </div>
    </section>

    <section id="katalog" class="catalog-section">
        <h2 style="text-align:center; font-size:32px; color:var(--hitam); margin-bottom:40px;">Menunggu Diadopsi</h2>
        <div class="catalog-grid">
            <?php foreach($katalog_hewan as $hewan): ?>
                <div class="card-pet">
                    <div class="pet-img-wrapper" style="height: 200px; background: #eee; border-radius: 10px; overflow: hidden; margin-bottom:15px;">
                        <?php if(!empty($hewan['foto'])): ?>
                            <img src="assets/img/hewan/<?= htmlspecialchars($hewan['foto']) ?>" style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <div style="display:flex; height:100%; align-items:center; justify-content:center; color:#999;">Tanpa Foto</div>
                        <?php endif; ?>
                    </div>
                    <h4 style="font-size:18px; font-weight:700;"><?= htmlspecialchars($hewan['nama_hewan']) ?></h4>
                    <p style="color:var(--text-muted); font-size:13px; margin-bottom:10px;"><?= htmlspecialchars($hewan['nama_jenis']) ?> - <?= htmlspecialchars($hewan['nama_ras']) ?></p>
                    <a href="index.php?page=login" class="btn btn-outline" style="width:100%; display:block; text-align:center;">Login untuk Adopsi</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="video-section">
        <h2 style="text-align:center; font-size:32px; color:var(--hitam); margin-bottom:20px;">Mengenal Shelter Kami</h2>
        <p style="text-align:center; color:var(--text-muted); margin-bottom:40px;">Lihat bagaimana kami merawat mereka sebelum menemukan keluarga barunya.</p>
        <div style="max-width:800px; margin:0 auto; border-radius:20px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <video width="100%" controls poster="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=800&q=80">
                <source src="assets/video/profil_shelter.mp4" type="video/mp4">
                Maaf, browser Anda tidak mendukung pemutar video.
            </video>
        </div>
    </section>

    <footer class="paw-footer">
        <div class="footer-grid">
            <div>
                <h3 style="color:var(--merah); font-size:24px; margin-bottom:15px;">🐾 PawCare</h3>
                <p style="color:#ccc; font-size:14px; line-height:1.6;">Kami berdedikasi untuk menyelamatkan, merawat, dan mencarikan rumah baru bagi hewan terlantar.</p>
            </div>
            <div>
                <h4 style="color:#fff; margin-bottom:15px;">Hubungi Kami</h4>
                <ul style="list-style:none; padding:0; color:#ccc; font-size:14px; line-height:2;">
                    <li>📞 +62 812-3456-7890</li>
                    <li>📧 care@pawcare.id</li>
                    <li>📍 Jl. Margonda Raya No. 100, Depok</li>
                </ul>
            </div>
            <div>
                <h4 style="color:#fff; margin-bottom:15px;">Sosial Media</h4>
                <div class="social-links">
                    <a href="#">📷 Instagram</a>
                    <a href="#">💬 WhatsApp</a>
                    <a href="#">𝕏 Twitter (X)</a>
                </div>
            </div>
        </div>
        <div style="text-align:center; color:#888; font-size:13px; margin-top:40px; padding-top:20px; border-top:1px solid #333;">
            &copy; 2026 PawCare Foundation. All rights reserved.
        </div>
    </footer>
</body>
</html>