<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    .detail-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
        font-family: 'Plus Jakarta Sans', 'Outfit', 'Inter', sans-serif;
    }
    .detail-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
        display: grid;
        grid-template-columns: 1.2fr 1.8fr;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    @media (max-width: 768px) {
        .detail-card {
            grid-template-columns: 1fr;
        }
    }
    .detail-img-section {
        background: #f8fafc;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
        border-right: 1px solid #f1f5f9;
    }
    .detail-img-wrapper {
        width: 100%;
        height: 100%;
        min-height: 350px;
        max-height: 450px;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }
    .detail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .detail-img:hover {
        transform: scale(1.05);
    }
    .detail-info-section {
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .pet-badge-row {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }
    .pet-badge {
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-primary { background: #eeebff; color: #4f46e5; }
    .badge-secondary { background: #e0f2fe; color: #0369a1; }
    .badge-success { background: #dcfce7; color: #15803d; }
    
    .pet-title {
        font-size: 36px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 10px;
        font-family: 'Outfit', sans-serif;
    }
    .pet-subtitle {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 30px;
        font-weight: 500;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .info-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        transition: all 0.2s ease;
    }
    .info-item:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }
    .info-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #334155;
    }
    
    .text-block {
        margin-bottom: 25px;
        line-height: 1.6;
        font-size: 14px;
        color: #475569;
    }
    .text-block-title {
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .cta-row {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }
    .btn-action {
        flex: 1;
        padding: 16px 24px;
        border-radius: 14px;
        font-size: 15px;
        font-weight: 700;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-block;
    }
    .btn-action-primary {
        background: #4f46e5;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
    }
    .btn-action-primary:hover {
        background: #4338ca;
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
        transform: translateY(-2px);
    }
    .btn-action-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }
    .btn-action-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }
</style>

<div class="main-wrapper">
    <div class="detail-container">
        
        <div style="margin-bottom: 25px;">
            <a href="index.php?page=dashboard_user&tab=katalog" style="text-decoration:none; color:#64748b; font-weight:600; font-size:14px; display:inline-flex; align-items:center; gap:6px;">
                &larr; Kembali ke Katalog
            </a>
        </div>

        <div class="detail-card">
            
            <!-- Bagian Kiri: Gambar -->
            <div class="detail-img-section">
                <div class="detail-img-wrapper">
                    <?php 
                    $foto_path = 'assets/img/hewan/' . ($hewan['url_foto_hewan'] ?? '');
                    if (!empty($hewan['url_foto_hewan']) && file_exists(__DIR__ . '/../../' . $foto_path)): 
                    ?>
                        <img src="<?= htmlspecialchars($foto_path) ?>" class="detail-img" alt="Foto <?= htmlspecialchars($hewan['nama_hewan']) ?>">
                    <?php else: ?>
                        <div style="display:flex; width:100%; height:100%; min-height:350px; align-items:center; justify-content:center; color:#94a3b8; font-size:64px; background:#e2e8f0;">🐾</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bagian Kanan: Informasi Lengkap -->
            <div class="detail-info-section">
                <div>
                    <div class="pet-badge-row">
                        <span class="pet-badge badge-primary"><?= htmlspecialchars($hewan['nama_jenis']) ?></span>
                        <span class="pet-badge badge-secondary"><?= htmlspecialchars($hewan['nama_ras']) ?></span>
                        <?php if ($hewan['rekomendasi_adopsi'] == 1): ?>
                            <span class="pet-badge badge-success">✨ Direkomendasikan</span>
                        <?php endif; ?>
                    </div>

                    <h1 class="pet-title"><?= htmlspecialchars($hewan['nama_hewan']) ?></h1>
                    <p class="pet-subtitle">Status: <span style="font-weight:700; color:#10b981;"><?= htmlspecialchars($hewan['status_adopsi']) ?></span></p>

                    <!-- Info Grid -->
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Jenis Kelamin</span>
                            <span class="info-value"><?= htmlspecialchars($hewan['jenis_kelamin']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Estimasi Umur</span>
                            <span class="info-value"><?= htmlspecialchars($hewan['estimasi_umur']) ?> bulan</span>
                        </div>
                    </div>

                    <!-- Hobi -->
                    <div class="text-block">
                        <div class="text-block-title">⚽ Hobi Hewan</div>
                        <div class="info-value" style="font-weight: 500; font-size: 14.5px; color:#334155;">
                            <?= !empty($hewan['hobi']) ? htmlspecialchars($hewan['hobi']) : '—' ?>
                        </div>
                    </div>

                    <!-- Fun Fact -->
                    <div class="text-block">
                        <div class="text-block-title">💡 Fun Fact</div>
                        <div class="info-value" style="font-weight: 500; font-style: italic; font-size: 14.5px; color:#4f46e5;">
                            "<?= !empty($hewan['funfact']) ? htmlspecialchars($hewan['funfact']) : '—' ?>"
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="text-block">
                        <div class="text-block-title">📝 Deskripsi / Karakter</div>
                        <p style="margin: 0; color:#475569; font-size:14.5px;">
                            <?= !empty($hewan['deskripsi']) ? nl2br(htmlspecialchars($hewan['deskripsi'])) : 'Tidak ada deskripsi tambahan.' ?>
                        </p>
                    </div>
                </div>

                <!-- CTA Actions -->
                <div class="cta-row">
                    <?php if ($hewan['rekomendasi_adopsi'] == 1): ?>
                        <a href="index.php?page=proses_adopsi&id=<?= $hewan['id_hewan'] ?>" class="btn-action btn-action-primary">Mulai Adopsi Sekarang</a>
                    <?php else: ?>
                        <button disabled class="btn-action" style="background:#e2e8f0; color:#94a3b8; border:none; cursor:not-allowed; width: 100%;">Belum Siap Adopsi (Menunggu Vaksin / Rekomendasi)</button>
                    <?php endif; ?>
                </div>

            </div>

        </div>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
