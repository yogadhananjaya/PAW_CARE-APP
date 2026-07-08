<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php if (empty($data)): ?>
    <div class="main-wrapper" style="margin-left: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;">
        <div class="card">Pembayaran tidak ditemukan.</div>
    </div>
<?php else: ?>
<div class="main-wrapper" style="margin-left: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;">
    <div class="card" style="max-width:720px; width: 100%;">
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

        <?php if ($data['metode'] === 'QRIS' || !empty($meta['qris_svg'])): ?>
            <div style="margin-top:12px; text-align:center;">
                <?php 
                    $qr_payload = 'PawCare|' . $data['reference'] . '|' . $data['amount'];
                    $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . urlencode($qr_payload);
                ?>
                <img src="<?= $qr_url ?>" alt="QRIS" style="max-width:240px; border:1px solid #e2e8f0; padding:6px; background:#fff;" />
                <p style="color:#64748b; margin-top: 8px;">Scan QRIS dengan aplikasi pembayaran Anda.</p>
            </div>
        <?php endif; ?>

        <?php if ($data['status'] === 'Pending'): ?>
            <div style="margin-top: 20px;">
                <button onclick="simulasikanBayar()" class="btn" style="background-color: #2ecc71; color: white; width: 100%; font-weight: 700; border-radius: 30px; padding: 12px; border: none; cursor: pointer;">
                    💸 Simulasikan Pembayaran Sukses
                </button>
            </div>
            <script>
                function simulasikanBayar() {
                    if (confirm('Apakah Anda ingin mensimulasikan pembayaran ini sebagai SUKSES?')) {
                        fetch('index.php?page=pembayaran_callback', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                reference: '<?= htmlspecialchars($data['reference']) ?>',
                                status: 'Success'
                            })
                        })
                        .then(response => response.json())
                        .then(res => {
                            if (res.ok) {
                                alert('Simulasi sukses berhasil dikirim! Status telah diperbarui.');
                                window.location.reload();
                            } else {
                                alert('Gagal memperbarui status pembayaran.');
                            }
                        })
                        .catch(err => {
                            alert('Terjadi kesalahan saat memanggil callback.');
                            console.error(err);
                        });
                    }
                }
            </script>
        <?php endif; ?>

        <div style="margin-top: 24px; margin-bottom: 24px; display: flex; gap: 12px;">
            <?php if ($data['status'] !== 'Success'): ?>
                <button onclick="window.history.back()" class="btn btn-secondary">Kembali</button>
            <?php endif; ?>
            <a href="index.php?page=dashboard_user" class="btn btn-primary">Kembali ke Dashboard</a>
        </div>

        <p style="margin-top:18px; color:#94a3b8;">Ini adalah implementasi simulasi. Untuk integrasi nyata, sambungkan ke penyedia gateway (Midtrans, Xendit, dsb.) menggunakan API key dan webhook.</p>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
