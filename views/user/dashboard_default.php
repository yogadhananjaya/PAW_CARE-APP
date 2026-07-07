<?php
// Menarik data hewan dari database untuk ditampilkan di Homepage
require_once __DIR__ . '/../../config/connect.php';
$stmt_hewan = $pdo->query("SELECT h.*, j.nama_jenis, r.nama_ras FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status_adopsi = 'Tersedia' AND h.rekomendasi_adopsi = 1 ORDER BY h.id_hewan DESC LIMIT 6");
$katalog_hewan = $stmt_hewan->fetchAll();

// Menarik data donasi terkini untuk ditampilkan di Beranda
$stmt_donasi = $pdo->query("SELECT * FROM donasi WHERE kategori = 'Pemasukan' AND status_konfirmasi = 'Dikonfirmasi' ORDER BY id_donasi DESC LIMIT 5");
$donasi_list = $stmt_donasi->fetchAll();
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
            <span>🔍</span><input type="text" id="search-pet" placeholder="Cari hewan...">
        </div>
        <div class="nav-right">
            <?php if (isset($_SESSION['role'])): 
                // Tentukan halaman dashboard yang sesuai dengan role
                $target_dash = 'dashboard_user';
                if ($_SESSION['role'] === 'SuperAdmin') $target_dash = 'dashboard_superadmin';
                elseif ($_SESSION['role'] === 'Koordinator') $target_dash = 'dashboard_koordinator';
                elseif (in_array($_SESSION['role'], ['Perawat', 'Perawat Hewan'])) $target_dash = 'dashboard_staff';
            ?>
                <!-- Tombol Dashboard & Keluar untuk user yang sudah login (Tema Sesuai Screenshot & Palet Utama) -->
                <a href="index.php?page=<?= $target_dash ?>" class="nav-btn btn-fill">Dashboard</a>
                <a href="index.php?page=logout" class="nav-btn btn-outline" style="margin-left: 8px;">Keluar</a>
            <?php else: ?>
                <a href="index.php?page=login" class="nav-btn btn-outline hidden-mobile">Masuk</a>
                <a href="index.php?page=login" class="nav-btn btn-fill">Daftar / Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero" style="min-height: 85vh; padding-bottom: 80px;">
        <div class="hero-content">
            <div class="tagline" style="background: #ffffff; color: #bd4a0a; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 4px 10px rgba(0,0,0,0.02); font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <span style="font-size: 14px;">🐾</span> PawCare Shelter & Adopsi
            </div>
            <h1 style="font-size: 56px; font-weight: 800; line-height: 1.15; color: #0f172a; margin-bottom: 20px; font-family: 'Outfit', sans-serif;">Live Your<br><span style="color: #DE3B3B; background: none; -webkit-text-fill-color: initial; -webkit-background-clip: initial;">Pet Adoption</span><br>Adventure</h1>
            <p style="font-size: 15px; color: #64748b; line-height: 1.7; margin-bottom: 35px; max-width: 480px;">Temukan hewan peliharaan yang tepat, pelihara dengan cinta, dan jadikan momen adopsi Anda penuh arti bersama PawCare.</p>
            
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 40px;">
                <a href="#katalog" class="nav-btn" style="background: #bd4a0a; color: #ffffff; border: none; border-radius: 30px; font-weight: 700; box-shadow: 0 4px 15px rgba(189, 74, 10, 0.3); padding: 14px 28px; font-size: 14px; text-decoration: none;">Lihat Katalog</a>
            </div>
        </div>
        <div class="hero-image hidden-mobile" id="hero-img-container" style="position: relative; perspective: 1000px; display: flex; justify-content: flex-end; align-items: center; min-height: 400px; padding: 20px 0px 20px 20px; flex: 1;">
            <div style="background: #f0fdfa; padding: 12px; border-radius: 28px; box-shadow: 0 20px 45px rgba(15,23,42,0.08); border: 1px solid rgba(15,23,42,0.03); display: inline-block; position: relative;" id="hero-pet-image">
                <img src="assets/img/kucing_orange_white_hero.png" alt="Cat Adoption" style="width: 500px; height: 340px; object-fit: cover; border-radius: 20px; display: block;">
                <!-- Floating Info Badge -->
                <div style="position: absolute; bottom: -20px; right: -20px; background: #ffffff; border-radius: 18px; padding: 22px; width: 240px; box-shadow: 0 15px 35px rgba(15,23,42,0.08); border: 1px solid rgba(15,23,42,0.04); text-align: left; z-index: 10;">
                    <div style="font-size: 14px; font-weight: 800; color: #bd4a0a; font-family: 'Outfit', sans-serif; margin-bottom: 8px;">PawCare Shelter</div>
                    <div style="font-size: 11px; color: #64748b; line-height: 1.5; font-weight: 500;">Kami merawat setiap hewan dengan kasih sayang, kesehatan, dan keamanan sebelum mereka menemukan keluarga baru.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section (Fitur Utama Aplikasi) -->
    <section class="stats-section">
        <h2 style="font-size: 32px; font-family: 'Outfit', sans-serif; color: #0f172a;">Fitur Utama Aplikasi</h2>
        <p style="color: #94a3b8; margin-top: 10px;">Kemudahan manajemen adopsi dan perawatan hewan dalam satu platform pintar</p>
        <div class="stats-grid-landing">
            <div class="stat-card-landing" style="transition: transform 0.3s ease; cursor: default;">
                <div class="number" style="font-size: 40px; margin-bottom: 15px;">🔍</div>
                <h4 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 10px; font-family: 'Outfit', sans-serif;">Katalog Pintar & Real-Time</h4>
                <p style="color: #64748b; font-size: 14px; line-height: 1.6;">Cari hewan peliharaan impian secara cepat dan efisien dengan filter status ketersediaan ter-update.</p>
            </div>
            <div class="stat-card-landing" style="transition: transform 0.3s ease; cursor: default;">
                <div class="number" style="font-size: 40px; margin-bottom: 15px;">📅</div>
                <h4 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 10px; font-family: 'Outfit', sans-serif;">Manajemen Kunjungan</h4>
                <p style="color: #64748b; font-size: 14px; line-height: 1.6;">Jadwalkan janji temu tatap muka langsung dengan shelter secara mudah dan otomatis melalui sistem.</p>
            </div>
            <div class="stat-card-landing" style="transition: transform 0.3s ease; cursor: default;">
                <div class="number" style="font-size: 40px; margin-bottom: 15px;">✍️</div>
                <h4 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 10px; font-family: 'Outfit', sans-serif;">Kontrak Adopsi Digital</h4>
                <p style="color: #64748b; font-size: 14px; line-height: 1.6;">Proses administrasi cepat dengan tanda tangan digital resmi langsung di aplikasi tanpa kertas.</p>
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

    <!-- Donation Section -->
    <section class="donation-section" style="padding: 80px 8%; background: #ffffff; border-top: 1px solid rgba(15,23,42,0.05);">
        <div style="text-align:center;">
            <h2 class="interactive-title" style="font-size:36px; color:#0f172a; font-family:'Outfit', sans-serif; margin-bottom:10px;">Dukung Perawatan Hewan 🐾</h2>
            <p style="text-align:center; color:#94a3b8; margin-bottom:50px;">Bantu kami menyediakan pakan, tempat tinggal yang layak, dan perawatan medis untuk mereka</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; max-width: 1100px; margin: 0 auto;">
            <!-- Donation Form -->
            <div style="background: #ffffff; border: 1px solid rgba(15,23,42,0.08); border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(15,23,42,0.02);">
                <h3 style="font-size: 20px; font-family: 'Outfit', sans-serif; color: #0f172a; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">Formulir Donasi</h3>
                <form method="POST" action="index.php?page=donasi_proses" onsubmit="return validateDonationForm(this);">
                    <div style="margin-bottom: 15px;">
                        <label style="display:block; margin-bottom:6px; font-weight:600; font-size:13px; color:#0f172a;">Nama Donatur</label>
                        <input type="text" name="nama_donatur" required minlength="3" pattern="^[A-Za-z\s]+$" placeholder="Nama Lengkap Anda" style="width:100%; padding:12px 15px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; outline:none;">
                        <small style="color: #94a3b8; font-size: 11px; margin-top: 4px; display: block;">Hanya huruf dan spasi, minimal 3 karakter.</small>
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

            <!-- Recent Donors -->
            <div style="background: #ffffff; border: 1px solid rgba(15,23,42,0.08); border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(15,23,42,0.02);">
                <h3 style="font-size: 20px; font-family: 'Outfit', sans-serif; color: #0f172a; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">Donatur Terkini 💖</h3>
                <div style="display: flex; flex-direction: column; gap: 15px; max-height: 380px; overflow-y: auto; padding-right: 5px;">
                    <?php if (count($donasi_list) > 0): ?>
                        <?php foreach($donasi_list as $donasi): ?>
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

    <!-- Adoption Flow Section -->
    <section class="flow-section">
        <div style="text-align:center;"><h2 class="interactive-title" style="font-size:36px; color:#0f172a; font-family:'Outfit', sans-serif; margin-bottom:10px;">Langkah Mudah Adopsi</h2></div>
        <p style="text-align:center; color:#94a3b8; margin-top:10px;">Cara sederhana untuk membawa pulang sahabat peliharaan baru Anda</p>
        <div class="flow-grid">
            <div class="flow-card">
                <div class="flow-badge">1</div>
                <h4>Pilih Hewan</h4>
                <p>Cari dan pilih hewan peliharaan yang ingin Anda adopsi di katalog online kami.</p>
            </div>
            <div class="flow-card">
                <div class="flow-badge">2</div>
                <h4>Datang & Bertemu</h4>
                <p>Kunjungi tempat kami untuk berkenalan langsung dengan calon peliharaan baru Anda.</p>
            </div>
            <div class="flow-card">
                <div class="flow-badge">3</div>
                <h4>Lengkapi Data</h4>
                <p>Isi formulir adopsi sederhana secara online dengan cepat dan aman.</p>
            </div>
            <div class="flow-card">
                <div class="flow-badge">4</div>
                <h4>Bawa Pulang</h4>
                <p>Sambut hewan peliharaan baru Anda di rumah baru mereka yang penuh kasih sayang.</p>
            </div>
        </div>
    </section>

    <!-- Alur & Kebijakan Shelter Section -->
    <section class="faq-section" style="padding: 80px 8%; background: #f8fafc; border-top: 1px solid rgba(15,23,42,0.05); border-bottom: 1px solid rgba(15,23,42,0.05);">
        <div style="text-align:center;">
            <h2 class="interactive-title" style="font-size:36px; color:#0f172a; font-family:'Outfit', sans-serif; margin-bottom:10px;">Alur & Kebijakan Shelter</h2>
            <p style="text-align:center; color:#94a3b8; margin-bottom:40px;">Ketentuan dan syarat resmi untuk mengadopsi sahabat bulu di PawCare</p>
        </div>
        
        <div class="faq-accordion-wrapper" style="max-width: 800px; margin: 0 auto;">
            <div class="faq-item" style="background: #ffffff; border: 1px solid rgba(15,23,42,0.08); border-radius: 12px; margin-bottom: 15px; overflow: hidden; transition: all 0.3s ease;">
                <button class="faq-question" style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 20px; background: none; border: none; font-size: 16px; font-weight: 600; color: #0f172a; cursor: pointer; text-align: left; font-family: 'Outfit', sans-serif;">
                    <span>Apakah adopsi di PawCare dipungut biaya?</span>
                    <span class="faq-icon" style="font-size: 20px; color: #f97316;">+</span>
                </button>
                <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; padding: 0 20px;">
                    <p style="padding-bottom: 20px; color: #64748b; font-size: 14px; line-height: 1.6;">Adopsi hewan di PawCare sepenuhnya gratis. Komitmen kami adalah menyalurkan hewan peliharaan ke keluarga yang bertanggung jawab dan penuh kasih sayang.</p>
                </div>
            </div>
            <div class="faq-item" style="background: #ffffff; border: 1px solid rgba(15,23,42,0.08); border-radius: 12px; margin-bottom: 15px; overflow: hidden; transition: all 0.3s ease;">
                <button class="faq-question" style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 20px; background: none; border: none; font-size: 16px; font-weight: 600; color: #0f172a; cursor: pointer; text-align: left; font-family: 'Outfit', sans-serif;">
                    <span>Komitmen & Tanggung Jawab Pengadopsi</span>
                    <span class="faq-icon" style="font-size: 20px; color: #f97316;">+</span>
                </button>
                <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; padding: 0 20px;">
                    <p style="padding-bottom: 20px; color: #64748b; font-size: 14px; line-height: 1.6;">Calon pengadopsi wajib menjamin kebutuhan pangan yang layak, tempat tinggal yang aman dan bebas dari rantai/kandang sempit, serta bersedia memberikan perawatan medis rutin termasuk vaksinasi.</p>
                </div>
            </div>
            <div class="faq-item" style="background: #ffffff; border: 1px solid rgba(15,23,42,0.08); border-radius: 12px; margin-bottom: 15px; overflow: hidden; transition: all 0.3s ease;">
                <button class="faq-question" style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 20px; background: none; border: none; font-size: 16px; font-weight: 600; color: #0f172a; cursor: pointer; text-align: left; font-family: 'Outfit', sans-serif;">
                    <span>Verifikasi Identitas & Dokumen Pendukung</span>
                    <span class="faq-icon" style="font-size: 20px; color: #f97316;">+</span>
                </button>
                <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; padding: 0 20px;">
                    <p style="padding-bottom: 20px; color: #64748b; font-size: 14px; line-height: 1.6;">Setiap calon pengadopsi wajib mengunggah kartu identitas diri (KTP) yang sah saat pendaftaran serta menandatangani Kontrak Adopsi digital resmi sebelum serah terima dilakukan.</p>
                </div>
            </div>
            <div class="faq-item" style="background: #ffffff; border: 1px solid rgba(15,23,42,0.08); border-radius: 12px; margin-bottom: 15px; overflow: hidden; transition: all 0.3s ease;">
                <button class="faq-question" style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 20px; background: none; border: none; font-size: 16px; font-weight: 600; color: #0f172a; cursor: pointer; text-align: left; font-family: 'Outfit', sans-serif;">
                    <span>Pertemuan Tatap Muka di Shelter</span>
                    <span class="faq-icon" style="font-size: 20px; color: #f97316;">+</span>
                </button>
                <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; padding: 0 20px;">
                    <p style="padding-bottom: 20px; color: #64748b; font-size: 14px; line-height: 1.6;">Setelah proses verifikasi awal disetujui oleh Koordinator, Anda diharuskan melakukan kunjungan langsung ke shelter untuk membangun interaksi awal dengan hewan pilihan sebelum proses adopsi disahkan.</p>
                </div>
            </div>
        </div>
    </section>


    <footer class="paw-footer">
        <!-- Floating Paw Ornaments -->
        <div class="floating-paw paw-1">🐾</div>
        <div class="floating-paw paw-2">🐾</div>
        <div class="floating-paw paw-3">🐾</div>
        
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

        // Ponytail: hover 3d removed as requested, added gentle floating animation instead
        if (document.getElementById('hero-pet-image')) {
            gsap.to('#hero-pet-image', {
                y: -12,
                repeat: -1,
                yoyo: true,
                duration: 3,
                ease: "sine.inOut"
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



        // Ponytail: hover 3d removed on cards
        // 5. Kebijakan Accordion Interactivity
        document.querySelectorAll('.faq-question').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = btn.parentElement;
                const isActive = item.classList.contains('active');
                
                // Close all other items
                document.querySelectorAll('.faq-item').forEach(i => {
                    i.classList.remove('active');
                    i.querySelector('.faq-answer').style.maxHeight = null;
                    i.querySelector('.faq-icon').innerText = '+';
                });

                if (!isActive) {
                    item.classList.add('active');
                    const answer = item.querySelector('.faq-answer');
                    answer.style.maxHeight = answer.scrollHeight + "px";
                    item.querySelector('.faq-icon').innerText = '-';
                }
            });
        });

        // 6. Floating Paw GSAP Animations
        gsap.to('.floating-paw.paw-1', {
            y: -25,
            x: 10,
            rotation: 15,
            repeat: -1,
            yoyo: true,
            duration: 4,
            ease: "sine.inOut"
        });
        gsap.to('.floating-paw.paw-2', {
            y: 30,
            x: -15,
            rotation: -20,
            repeat: -1,
            yoyo: true,
            duration: 5,
            ease: "sine.inOut",
            delay: 0.5
        });
        gsap.to('.floating-paw.paw-3', {
            y: -20,
            x: -10,
            rotation: 10,
            repeat: -1,
            yoyo: true,
            duration: 4.5,
            ease: "sine.inOut",
            delay: 1
        });

        // 7. Real-Time Search Functionality
        const searchInput = document.getElementById('search-pet');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                const petCards = document.querySelectorAll('.card-pet');
                
                //  Auto Scroll ke Katalog saat mulai mengetik
                if (query.length > 0) {
                    const catalogSection = document.getElementById('katalog');
                    if (catalogSection) {
                        catalogSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
                
                petCards.forEach(card => {
                    const name = card.querySelector('h4').textContent.toLowerCase();
                    const details = card.querySelector('p').textContent.toLowerCase();
                    
                    if (name.includes(query) || details.includes(query)) {
                        card.style.display = 'block';
                        gsap.to(card, { opacity: 1, scale: 1, duration: 0.3 });
                    } else {
                        gsap.to(card, { 
                            opacity: 0, 
                            scale: 0.95, 
                            duration: 0.2, 
                            onComplete: () => {
                                card.style.display = 'none';
                            }
                        });
                    }
                });
            });
        }
    </script>
</body>
</html>