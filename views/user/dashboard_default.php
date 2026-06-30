<?php
// Menarik data hewan dari database untuk ditampilkan di Homepage
require_once __DIR__ . '/../../config/connect.php';
$stmt_hewan = $pdo->query("SELECT h.*, j.nama_jenis, r.nama_ras FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status_adopsi = 'Tersedia' AND h.rekomendasi_adopsi = 1 ORDER BY h.id_hewan DESC LIMIT 6");
$katalog_hewan = $stmt_hewan->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>PawCare - Adopt Your Pet Friend</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
</head>
<body class="landing-page-body">
    <nav class="navbar">
        <a href="index.php" class="nav-brand">🐾 PawCare</a>
        <div class="nav-center hidden-mobile">
            <span>🔍</span><input type="text" placeholder="Cari hewan..."><span>|</span>
            <select><option>Semua Lokasi</option><option>Jakarta</option><option>Depok</option></select>
        </div>
        <div class="nav-right">
            <?php if (isset($_SESSION['role'])): 
                // Tentukan halaman dashboard yang sesuai dengan role
                $target_dash = 'dashboard_user';
                if ($_SESSION['role'] === 'SuperAdmin') $target_dash = 'dashboard_superadmin';
                elseif ($_SESSION['role'] === 'Koordinator') $target_dash = 'dashboard_koordinator';
                elseif (in_array($_SESSION['role'], ['Perawat', 'Perawat Hewan'])) $target_dash = 'dashboard_staff';
            ?>
                <!-- ponytail: Tombol Dashboard & Keluar untuk user yang sudah login (Tema Sesuai Screenshot & Palet Utama) -->
                <a href="index.php?page=<?= $target_dash ?>" class="nav-btn btn-fill">Dashboard</a>
                <a href="index.php?page=logout" class="nav-btn btn-outline" style="margin-left: 8px;">Keluar</a>
            <?php else: ?>
                <a href="index.php?page=login" class="nav-btn btn-outline hidden-mobile">Masuk</a>
                <a href="index.php?page=login" class="nav-btn btn-fill">Daftar / Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="tagline">🐾 Lebih dari sekadar Shelter</div>
            <h1>Adopt your<br><span>pet friend</span></h1>
            <p>Berikan rumah permanen yang penuh kasih. Temukan sahabat bulu Anda hari ini dan selamatkan nyawa mereka.</p>
            <a href="#katalog" class="nav-btn btn-fill" style="padding: 15px 40px; font-size: 16px;">Lihat Katalog</a>
        </div>
        <div class="hero-image hidden-mobile" id="hero-img-container">
            <div class="bg-circle"></div>
            <img src="assets/img/logo.png" alt="PawCare" class="pet-img" id="hero-pet-image" style="max-width: 380px; filter: drop-shadow(0 10px 25px rgba(0,0,0,0.05));">
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <h2 style="font-size: 32px; font-family: 'Outfit', sans-serif; color: #0f172a;">Dampak Nyata PawCare</h2>
        <p style="color: #94a3b8; margin-top: 10px;">Komitmen kami dalam menyelamatkan dan menyalurkan kasih sayang</p>
        <div class="stats-grid-landing">
            <div class="stat-card-landing">
                <div class="number"><span class="count-num" data-val="1240">0</span>+</div>
                <div class="label">Hewan Diselamatkan</div>
            </div>
            <div class="stat-card-landing">
                <div class="number"><span class="count-num" data-val="980">0</span>+</div>
                <div class="label">Adopsi Sukses</div>
            </div>
            <div class="stat-card-landing">
                <div class="number"><span class="count-num" data-val="150">0</span>M+</div>
                <div class="label">Donasi Tersalurkan</div>
            </div>
        </div>
    </section>

    <section id="katalog" class="catalog-section">
        <div style="text-align:center;"><h2 class="interactive-title" style="font-size:36px; color:#0f172a; margin-bottom:10px; font-family:'Outfit', sans-serif;">Menunggu Diadopsi</h2></div>
        <p style="text-align:center; color:#94a3b8; margin-bottom:50px;">Temukan teman bermain baru yang siap melengkapi keceriaan di rumah Anda</p>
        <div class="catalog-grid">
            <?php if (count($katalog_hewan) > 0): ?>
                <?php foreach($katalog_hewan as $hewan): ?>
                    <div class="card-pet">
                        <div class="pet-img-wrapper" style="height: 200px; background: rgba(255,255,255,0.02); border-radius: 16px; overflow: hidden; margin-bottom:15px; border: 1px solid rgba(255,255,255,0.05);">
                            <?php if(!empty($hewan['url_foto_hewan'])): ?>
                                <img src="assets/img/hewan/<?= htmlspecialchars($hewan['url_foto_hewan']) ?>" 
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=250&q=80';"
                                     style="width:100%; height:100%; object-fit:cover; transition: transform 0.5s ease;">
                            <?php else: ?>
                                <div style="display:flex; height:100%; align-items:center; justify-content:center; color:#64748b; font-size:14px; background: rgba(255,255,255,0.02);">🐾 Tanpa Foto</div>
                            <?php endif; ?>
                        </div>
                        <h4 style="font-size:18px; font-weight:700; font-family:'Outfit', sans-serif;"><?= htmlspecialchars($hewan['nama_hewan']) ?></h4>
                        <p style="color:#64748b; font-size:13px; margin-bottom:10px;"><?= htmlspecialchars($hewan['nama_jenis']) ?> - <?= htmlspecialchars($hewan['nama_ras']) ?></p>
                        <a href="index.php?page=login" class="nav-btn btn-outline" style="width:100%; display:block; text-align:center; padding: 10px 0; border-radius: 12px; font-size:14px;">Login untuk Adopsi</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; backdrop-filter: blur(10px);">
                    <p style="color: #94a3b8; font-size: 16px;">🐾 Saat ini belum ada hewan yang tersedia untuk diadopsi. Silakan periksa kembali nanti!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Adoption Flow Section -->
    <section class="flow-section">
        <div style="text-align:center;"><h2 class="interactive-title" style="font-size:36px; color:#0f172a; font-family:'Outfit', sans-serif; margin-bottom:10px;">Alur Adopsi Mudah</h2></div>
        <p style="text-align:center; color:#94a3b8; margin-top:10px;">Langkah sederhana untuk membawa sahabat baru Anda pulang</p>
        <div class="flow-grid">
            <div class="flow-card">
                <div class="flow-badge">1</div>
                <h4>Cari Sahabat</h4>
                <p>Telusuri katalog hewan online kami dan temukan yang cocok dengan kepribadian Anda.</p>
            </div>
            <div class="flow-card">
                <div class="flow-badge">2</div>
                <h4>Kunjungi Shelter</h4>
                <p>Temui langsung calon sahabat bulu Anda di shelter kami untuk interaksi tatap muka.</p>
            </div>
            <div class="flow-card">
                <div class="flow-badge">3</div>
                <h4>Formulir & Kontrak</h4>
                <p>Lengkapi data diri dan tandatangani kontrak adopsi secara digital yang aman.</p>
            </div>
            <div class="flow-card">
                <div class="flow-badge">4</div>
                <h4>Bawa Pulang</h4>
                <p>Sambut anggota keluarga baru Anda di rumah dengan penuh kebahagiaan.</p>
            </div>
        </div>
    </section>

    <section class="video-section">
        <div style="text-align:center;"><h2 class="interactive-title" style="font-size:36px; color:#0f172a; margin-bottom:20px; font-family:'Outfit', sans-serif;">Mengenal Shelter Kami</h2></div>
        <p style="text-align:center; color:#94a3b8; margin-bottom:40px;">Lihat bagaimana kami merawat mereka sebelum menemukan keluarga barunya.</p>
        <div style="max-width:800px; margin:0 auto; border-radius:20px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <video width="100%" controls poster="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=800&q=80">
                <source src="assets/video/profil_shelter.mp4" type="video/mp4">
                Maaf, browser Anda tidak mendukung pemutar video.
            </video>
        </div>
       <footer class="paw-footer">
        <div class="footer-grid">
            <div>
                <h3 style="color:#f97316; font-size:24px; margin-bottom:15px; font-family:'Outfit', sans-serif;">🐾 PawCare</h3>
                <p style="color:#94a3b8; font-size:14px; line-height:1.6;">Kami berdedikasi untuk menyelamatkan, merawat, dan mencarikan rumah baru bagi hewan terlantar.</p>
            </div>
            <div>
                <h4 style="color:#fff; margin-bottom:15px; font-family:'Outfit', sans-serif;">Hubungi Kami</h4>
                <ul style="list-style:none; padding:0; color:#94a3b8; font-size:14px; line-height:2;">
                    <li>📞 +62 812-3456-7890</li>
                    <li>📧 care@pawcare.id</li>
                    <li>📍 Jl. Margonda Raya No. 100, Depok</li>
                </ul>
            </div>
            <div>
                <h4 style="color:#fff; margin-bottom:15px; font-family:'Outfit', sans-serif;">Sosial Media</h4>
                <div class="social-links">
                    <a href="#">📷 Instagram</a>
                    <a href="#">💬 WhatsApp</a>
                    <a href="#">𝕏 Twitter (X)</a>
                </div>
            </div>
        </div>
        <div style="text-align:center; color:#475569; font-size:13px; margin-top:40px; padding-top:20px; border-top:1px solid rgba(255,255,255,0.05);">
            &copy; 2026 PawCare Foundation. All rights reserved.
        </div>
    </footer>

    <!-- GSAP & ScrollTrigger CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script>
        // Register ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        // Navbar Scroll Class
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // 1. Antigravity Hero Mouse Parallax
        const heroImgContainer = document.getElementById('hero-img-container');
        const heroPetImage = document.getElementById('hero-pet-image');
        
        if (heroImgContainer && heroPetImage) {
            heroImgContainer.addEventListener('mousemove', (e) => {
                const rect = heroImgContainer.getBoundingClientRect();
                const x = e.clientX - rect.left - (rect.width / 2);
                const y = e.clientY - rect.top - (rect.height / 2);
                
                // Tilt image slightly based on mouse position
                gsap.to(heroPetImage, {
                    x: x * 0.08,
                    y: y * 0.08,
                    rotationY: x * 0.04,
                    rotationX: -y * 0.04,
                    ease: "power2.out",
                    duration: 0.5
                });
            });
            
            heroImgContainer.addEventListener('mouseleave', () => {
                gsap.to(heroPetImage, {
                    x: 0,
                    y: 0,
                    rotationY: 0,
                    rotationX: 0,
                    ease: "power2.out",
                    duration: 0.8
                });
            });
        }

        // 2. Initial Animation for Hero Content
        gsap.from('.hero-content > *', {
            y: 40,
            opacity: 0,
            duration: 1,
            stagger: 0.2,
            ease: 'power3.out'
        });

        gsap.from('.hero-image', {
            scale: 0.8,
            opacity: 0,
            duration: 1.2,
            ease: 'power3.out',
            delay: 0.4
        });

        // 3. Stats Counter Animation
        const countNums = document.querySelectorAll('.count-num');
        countNums.forEach(num => {
            const targetVal = parseInt(num.getAttribute('data-val'));
            gsap.fromTo(num, 
                { textContent: 0 }, 
                {
                    textContent: targetVal,
                    duration: 2.5,
                    ease: 'power2.out',
                    snap: { textContent: 1 },
                    scrollTrigger: {
                        trigger: '.stats-section',
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                }
            );
        });

        // 4. Spatial 3D Tilt Effect on Cards Hover
        const cards = document.querySelectorAll('.card-pet');
        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left - (rect.width / 2);
                const y = e.clientY - rect.top - (rect.height / 2);
                
                gsap.to(card, {
                    rotationY: x * 0.08,
                    rotationX: -y * 0.08,
                    z: 15,
                    transformPerspective: 600,
                    duration: 0.4,
                    ease: 'power2.out'
                });
            });
            
            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    rotationY: 0,
                    rotationX: 0,
                    z: 0,
                    duration: 0.6,
                    ease: 'power2.out'
                });
            });
        });
    </script>
</body>
</html>