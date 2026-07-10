<?php 
include __DIR__ . '/../layouts/header.php'; 

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
?>

<style>
    html, body {
        background-color: #F8FAFC;
        overflow: hidden;
        height: 100%;
    }
    .detail-container {
        max-width: 1100px;
        margin: 10px auto;
        padding: 0 24px;
        font-family: 'Inter', sans-serif;
    }
    
    /* Layout grid utama atas */
    .detail-grid-top {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 40px;
        margin-bottom: 40px;
    }
    @media (max-width: 900px) {
        .detail-grid-top {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    }

    /* Bagian Gambar Kiri */
    .image-card {
        position: relative;
        border-radius: 28px;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.06);
        background: #ffffff;
    }
    .image-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .image-card img:hover {
        transform: scale(1.03);
    }
    .status-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: #0d9488;
        color: #ffffff;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
    }
    .status-badge::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #ffffff;
        border-radius: 50%;
    }

    /* Bagian Info Kanan */
    .info-panel {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .pet-category-row {
        font-size: 14px;
        color: #0d9488;
        font-weight: 700;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pet-category-row span.status-text {
        color: #64748b;
        font-weight: 500;
    }
    .pet-name {
        font-family: 'Outfit', sans-serif;
        font-size: 42px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 12px;
        line-height: 1.1;
    }
    .pet-desc-short {
        font-size: 15px;
        color: #475569;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    /* Grid Atribut (Gender, Umur, Ukuran) */
    .attr-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }
    .attr-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 16px 10px;
        text-align: center;
        transition: all 0.2s ease;
    }
    .attr-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.03);
        border-color: #cbd5e1;
    }
    .attr-icon {
        font-size: 20px;
        margin-bottom: 8px;
        display: block;
    }
    .attr-label {
        font-size: 10px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }
    .attr-value {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }

    /* Panel Biaya & CTA */
    .cta-box {
        background: #eff6ff;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid #dbeafe;
    }
    .price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .price-label {
        font-size: 14px;
        color: #475569;
        font-weight: 500;
    }
    .price-value-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .price-value {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
    }
    .toggle-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #cbd5e1;
        box-shadow: inset 0 0 0 2px #fff;
    }
    .btn-primary-adopt {
        background: #ea580c;
        color: #ffffff;
        font-weight: 700;
        font-size: 16px;
        padding: 16px;
        border-radius: 30px;
        border: none;
        cursor: pointer;
        width: 100%;
        box-shadow: 0 4px 15px rgba(234, 88, 12, 0.25);
        transition: all 0.2s ease;
        text-decoration: none;
        display: block;
        text-align: center;
    }
    .btn-primary-adopt:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(234, 88, 12, 0.35);
    }
    .btn-disabled-adopt {
        background: #e2e8f0;
        color: #94a3b8;
        font-weight: 700;
        font-size: 16px;
        padding: 16px;
        border-radius: 30px;
        border: none;
        width: 100%;
        cursor: not-allowed;
        display: block;
        text-align: center;
    }
    .cta-subtext {
        font-size: 12px;
        color: #64748b;
        text-align: center;
        margin-top: 12px;
        margin-bottom: 0;
        line-height: 1.4;
    }

    /* Grid 3 Kartu Tengah (Hobi, Fun Fact, Deskripsi) */
    .details-triple-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        margin-bottom: 60px;
    }
    @media (max-width: 850px) {
        .details-triple-grid {
            grid-template-columns: 1fr;
        }
    }
    .info-card-block {
        border-radius: 24px;
        padding: 28px;
        transition: all 0.3s ease;
    }
    .info-card-block:hover {
        transform: translateY(-4px);
    }
    .bg-green-card {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
    }
    .bg-blue-card {
        background: #eff6ff;
        border: 1px solid #dbeafe;
    }
    .bg-white-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.02);
    }
    .block-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 15px;
    }
    .block-content {
        font-size: 14px;
        line-height: 1.6;
        color: #475569;
        margin-bottom: 20px;
    }
    .pills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .pill-item {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
    }
    .badge-subinfo {
        font-size: 13px;
        font-weight: 700;
        color: #ea580c;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Bagian Bawah: Langkah Membawa Pulang */
    .timeline-section {
        text-align: center;
        border-top: 1px solid #e2e8f0;
        padding-top: 50px;
    }
    .timeline-title {
        font-size: 16px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 40px;
    }
    .timeline-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        max-width: 900px;
        margin: 0 auto;
    }
    @media (max-width: 600px) {
        .timeline-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    }
    .timeline-step {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #eff6ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b82f6;
        font-size: 20px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .timeline-step:hover .step-circle {
        background: #3b82f6;
        color: #ffffff;
        transform: scale(1.1);
    }
    .step-name {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .step-desc {
        font-size: 12px;
        color: #64748b;
        line-height: 1.4;
        max-width: 180px;
    }
</style>

<div class="main-wrapper" style="padding-top: 0;">
    <div class="detail-container">
        
        <!-- Tombol Kembali -->
        <div style="margin-bottom: 10px;">
            <a href="index.php?page=dashboard_user&tab=katalog" style="text-decoration:none; color:#64748b; font-weight:600; font-size:14px; display:inline-flex; align-items:center; gap:8px; transition: color 0.2s;">
                &larr; Kembali ke Katalog
            </a>
        </div>

        <!-- Bagian Atas: Grid Kiri Kanan -->
        <div class="detail-grid-top">
            
            <!-- Gambar Kiri -->
            <div class="image-card">
                <img src="<?= getPetImage($hewan) ?>" alt="Foto <?= htmlspecialchars($hewan['nama_hewan']) ?>" onerror="this.onerror=null; this.src='assets/img/logo.png';">
                
                <?php if ($hewan['status_adopsi'] === 'Tersedia'): ?>
                    <span class="status-badge">Siap Diadopsi</span>
                <?php endif; ?>
            </div>

            <!-- Panel Detail Kanan -->
            <div class="info-panel">
                <div>
                    <div class="pet-category-row">
                        <?= htmlspecialchars($hewan['nama_jenis']) ?> <?= htmlspecialchars($hewan['nama_ras']) ?>
                        <span style="color: #cbd5e1;">&bull;</span>
                        <span class="status-text"><?= htmlspecialchars($hewan['status_adopsi']) ?></span>
                    </div>
                    
                    <h1 class="pet-name"><?= htmlspecialchars($hewan['nama_hewan']) ?></h1>
                    
                    <p class="pet-desc-short">
                        <?= htmlspecialchars($hewan['nama_hewan']) ?> adalah <?= htmlspecialchars($hewan['nama_jenis']) ?> <?= htmlspecialchars($hewan['nama_ras']) ?> yang sangat manis, sehat, lincah, dan siap membawa kehangatan baru ke dalam rumah Anda.
                    </p>

                    <!-- Atribut Grid -->
                    <div class="attr-grid">
                        <div class="attr-card">
                            <span class="attr-icon">
                                <?= $hewan['jenis_kelamin'] === 'Betina' ? '♀' : '♂' ?>
                            </span>
                            <span class="attr-label">Jenis Kelamin</span>
                            <span class="attr-value"><?= htmlspecialchars($hewan['jenis_kelamin']) ?></span>
                        </div>
                        <div class="attr-card">
                            <span class="attr-icon">📅</span>
                            <span class="attr-label">Umur</span>
                            <span class="attr-value"><?= htmlspecialchars($hewan['estimasi_umur']) ?> Bulan</span>
                        </div>
                        <div class="attr-card">
                            <span class="attr-icon">📐</span>
                            <span class="attr-label">Ukuran</span>
                            <span class="attr-value">
                                <?php 
                                $umur = (int)$hewan['estimasi_umur'];
                                if ($umur < 6) echo 'Kecil';
                                elseif ($umur < 12) echo 'Sedang';
                                else echo 'Besar';
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Alert & CTA Button -->
                <div class="cta-box">
                    <?php 
                    // Ambil status verifikasi pengadopsi saat ini dari session
                    $adopter_status = 'Belum';
                    if (isset($_SESSION['username'])) {
                        $stmt_status = $pdo->prepare("SELECT status_verifikasi FROM pengadopsi WHERE nama_pengguna = ?");
                        $stmt_status->execute([$_SESSION['username']]);
                        $adopter_status = $stmt_status->fetchColumn() ?: 'Belum';
                    }
                    ?>

                    <?php if ($hewan['status_adopsi'] === 'Tersedia'): ?>
                        <?php if ($adopter_status === 'Menunggu'): ?>
                            <button disabled class="btn-disabled-adopt" style="background: #e2e8f0; color: #94a3b8;">Menunggu Verifikasi Akun...</button>
                        <?php else: ?>
                            <a href="index.php?page=proses_adopsi&id=<?= $hewan['id_hewan'] ?>" class="btn-primary-adopt">Mulai Adopsi Sekarang</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button disabled class="btn-disabled-adopt">Tidak Tersedia untuk Adopsi</button>
                    <?php endif; ?>
                    
                    <p class="cta-subtext">Pengiriman gratis ke area tertentu dalam jaringan perlindungan kami.</p>
                </div>
            </div>

        </div>

        <!-- Bagian Tengah: Tiga Kartu Detail -->
        <div class="details-triple-grid">
            
            <!-- Kartu Hobi -->
            <div class="info-card-block bg-green-card">
                <div class="block-header">
                    <span>☀️</span> Hobi Hewan
                </div>
                <div class="block-content">
                    <?= !empty($hewan['hobi']) ? htmlspecialchars($hewan['hobi']) : htmlspecialchars($hewan['nama_hewan']) . ' sangat suka bermain aktif, menjelajahi sudut ruangan, dan beristirahat santai setelah lelah beraktivitas.' ?>
                </div>
                <div class="pills-container">
                    <span class="pill-item">Berjemur</span>
                    <span class="pill-item">Eksplorasi</span>
                    <span class="pill-item">Bersantai</span>
                </div>
            </div>

            <!-- Kartu Fun Fact -->
            <div class="info-card-block bg-blue-card">
                <div class="block-header">
                    <span>💡</span> Fun Fact
                </div>
                <div class="block-content">
                    "<?= !empty($hewan['funfact']) ? htmlspecialchars($hewan['funfact']) : htmlspecialchars($hewan['nama_hewan']) . ' sangat manja dan akan bersuara lembut setiap kali kepalanya diusap lembut oleh pengasuhnya.' ?>"
                </div>
                <div class="badge-subinfo">
                    ✨ Kepribadian Lembut
                </div>
            </div>

            <!-- Kartu Deskripsi -->
            <div class="info-card-block bg-white-card">
                <div class="block-header">
                    <span>📄</span> Deskripsi
                </div>
                <div class="block-content" style="margin-bottom: 0;">
                    <?= !empty($hewan['deskripsi']) ? nl2br(htmlspecialchars($hewan['deskripsi'])) : 'Memiliki fisik yang sehat dan nafsu makan yang sangat baik. Sangat cocok dipelihara dalam lingkungan keluarga yang ramah anak.' ?>
                </div>
            </div>

        </div>

        <!-- Bagian Bawah: Langkah Membawa Pulang -->
        <div class="timeline-section">
            <div class="timeline-title">Cara membawa pulang <?= htmlspecialchars($hewan['nama_hewan']) ?></div>
            
            <div class="timeline-grid">
                <div class="timeline-step">
                    <div class="step-circle">📋</div>
                    <div class="step-name">Daftar</div>
                    <div class="step-desc">Isi formulir adopsi singkat.</div>
                </div>
                <div class="timeline-step">
                    <div class="step-circle">💬</div>
                    <div class="step-name">Wawancara</div>
                    <div class="step-desc">Panggilan singkat untuk bertemu tim kami.</div>
                </div>
                <div class="timeline-step">
                    <div class="step-circle">🏠</div>
                    <div class="step-name">Kunjungi</div>
                    <div class="step-desc">Temui <?= htmlspecialchars($hewan['nama_hewan']) ?> di tempat tinggalnya sekarang.</div>
                </div>
                <div class="timeline-step">
                    <div class="step-circle">🎉</div>
                    <div class="step-name">Adopsi</div>
                    <div class="step-desc">Sambut <?= htmlspecialchars($hewan['nama_hewan']) ?> di keluarga Anda!</div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
