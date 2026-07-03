<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar_admin.php'; ?>

<?php if (empty($data)): ?>
    <div class="main-wrapper"><div class="card">Pembayaran tidak ditemukan.</div></div>
<?php else: ?>
<div class="main-wrapper">
    <div class="card" style="max-width:720px;">
        <h3>Rincian Pembayaran</h3>
        <p><strong>Kode:</strong> <?= htmlspecialchars($data['kode_pembayaran']) ?></p>
        <p><strong>Reference:</strong> <?= htmlspecialchars($data['reference']) ?></p>
        <p><strong>Jumlah:</strong> Rp <?= number_format($data['amount'],0,',','.') ?></p>
        <p><strong>Metode:</strong> <?= htmlspecialchars($data['metode']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($data['status']) ?></p>

        <?php $meta = json_decode($data['metadata'] ?? 'null', true) ?: []; ?>

        <?php if (!empty($meta['va_number'])): ?>
            <div style="padding:12px; border:1px dashed #e2e8f0; border-radius:8px; margin-top:10px;">
                <strong>Virtual Account:</strong>
                <div style="margin-top:8px; font-size:18px;"><?= htmlspecialchars($meta['va_number']) ?></div>
                <p style="color:#64748b;">Gunakan nomor VA di aplikasi bank Anda untuk melakukan transfer.</p>
            </div>
        <?php endif; ?>

        <?php if (!empty($meta['deeplink'])): ?>
            <div style="margin-top:12px;">
                <a href="<?= htmlspecialchars($meta['deeplink']) ?>" class="btn btn-primary">Buka App Pembayaran</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($meta['qris_svg'])): ?>
            <div style="margin-top:12px; text-align:center;">
                <img src="<?= $meta['qris_svg'] ?>" alt="QRIS" style="max-width:240px; border:1px solid #e2e8f0; padding:6px; background:#fff;" />
                <p style="color:#64748b;">Scan QRIS dengan aplikasi pembayaran Anda.</p>
            </div>
        <?php endif; ?>

        <p style="margin-top:18px; color:#94a3b8;">Ini adalah implementasi simulasi. Untuk integrasi nyata, sambungkan ke penyedia gateway (Midtrans, Xendit, dsb.) menggunakan API key dan webhook.</p>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
