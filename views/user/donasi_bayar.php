<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Donasi - PawCare</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #FAF8F5;
            font-family: 'Inter', sans-serif;
            color: #111;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .payment-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 35px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
            border: 1px solid rgba(15, 23, 42, 0.04);
            text-align: center;
        }
        .payment-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #bd4a0a;
            margin-bottom: 5px;
        }
        .payment-header p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .detail-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            text-align: left;
            margin-bottom: 25px;
            border: 1px solid rgba(0, 0, 0, 0.02);
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            color: #64748b;
            font-weight: 500;
        }
        .detail-value {
            color: #0f172a;
            font-weight: 700;
        }
        .amount-large {
            font-size: 26px;
            font-weight: 800;
            color: #DE3B3B;
            font-family: 'Outfit', sans-serif;
        }
        .simulator-area {
            background: #fffbeb;
            border: 1px dashed #f59e0b;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            font-size: 13px;
            color: #b45309;
            line-height: 1.6;
        }
        .action-button {
            background: #bd4a0a;
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 28px;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            width: 100%;
            box-shadow: 0 4px 15px rgba(189, 74, 10, 0.3);
            transition: all 0.2s ease;
            text-decoration: none;
            display: block;
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(189, 74, 10, 0.4);
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
        }
        .back-link:hover {
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="payment-card">
        <div class="payment-header">
            <h2>🐾 Pembayaran Donasi</h2>
            <p>Selesaikan donasi Anda dengan aman & mudah</p>
        </div>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Kode Transaksi:</span>
                <span class="detail-value"><?= htmlspecialchars($donasi['kode_donasi']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Nama Donatur:</span>
                <span class="detail-value"><?= htmlspecialchars($donasi['nama_donatur']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Metode Pembayaran:</span>
                <span class="detail-value"><?= htmlspecialchars($donasi['metode_pembayaran']) ?></span>
            </div>
            <?php if(!empty($donasi['keterangan'])): ?>
            <div class="detail-row">
                <span class="detail-label">Catatan:</span>
                <span class="detail-value"><?= htmlspecialchars($donasi['keterangan']) ?></span>
            </div>
            <?php endif; ?>
            <div style="border-top: 1px solid #e2e8f0; margin: 15px 0; padding-top: 15px;" class="detail-row">
                <span class="detail-label">Total Donasi:</span>
                <span class="amount-large">Rp <?= number_format($donasi['nominal'], 0, ',', '.') ?></span>
            </div>
        </div>

        <?php if ($donasi['metode_pembayaran'] === 'Transfer Bank'): ?>
            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 20px; margin-bottom: 25px; text-align: left;">
                <div style="font-size: 13px; color: #64748b; font-weight: 500;">Nomor Virtual Account:</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 5px; font-family: monospace; letter-spacing: 1px;">
                    88012<?= str_pad($donasi['id_donasi'], 8, '0', STR_PAD_LEFT) ?>
                </div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 5px;">Transfer nominal yang tepat ke nomor VA di atas.</div>
            </div>
        <?php else: ?>
            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 20px; margin-bottom: 25px; text-align: center;">
                <div style="font-size: 13px; color: #64748b; font-weight: 500; text-align: left; margin-bottom: 10px;">Scan QRIS:</div>
                <?php
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 200 200"><rect width="200" height="200" fill="#fff"/><rect x="20" y="20" width="40" height="40" fill="#111"/><rect x="140" y="20" width="40" height="40" fill="#111"/><rect x="20" y="140" width="40" height="40" fill="#111"/><text x="15" y="110" font-size="9" fill="#555" font-family="monospace">'.htmlspecialchars($donasi['kode_donasi']).'</text></svg>';
                ?>
                <div style="display: inline-block; background: #fff; padding: 10px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <?= $svg ?>
                </div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 8px;">Pindai kode QR di atas dengan aplikasi E-Wallet Anda.</div>
            </div>
        <?php endif; ?>

        <a href="index.php?page=donasi_sukses&id=<?= $donasi['id_donasi'] ?>" class="action-button">Selesaikan Pembayaran</a>
        <a href="index.php?page=home" class="back-link">&larr; Batal & Kembali ke Beranda</a>
    </div>
</body>
</html>
