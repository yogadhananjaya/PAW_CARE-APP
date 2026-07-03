<?php
$total_pemasukan = 0;
$total_pengeluaran = 0;
$jumlah_transaksi = count($data);

foreach ($data as $d) {
    if ($d['status_konfirmasi'] === 'Dikonfirmasi') {
        if ($d['kategori'] === 'Pemasukan') {
            $total_pemasukan += $d['nominal'];
        } else {
            $total_pengeluaran += $d['nominal'];
        }
    }
}
$saldo_bersih = $total_pemasukan - $total_pengeluaran;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan & Donasi PawCare</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #334155;
            margin: 20px;
            font-size: 13px;
            line-height: 1.5;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
        }
        .header-title {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            margin: 0;
        }
        .header-subtitle {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .header-meta {
            text-align: right;
            font-size: 11px;
            color: #64748b;
        }
        .summary-box-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .summary-label {
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .report-table th, .report-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .report-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        .report-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-dikonfirmasi { background-color: #dcfce7; color: #15803d; }
        .badge-menunggu { background-color: #fef3c7; color: #d97706; }
        .badge-ditolak { background-color: #fee2e2; color: #b91c1c; }
        
        .badge-pemasukan { background-color: #e0f2fe; color: #0369a1; }
        .badge-pengeluaran { background-color: #ffedd5; color: #c2410c; }
    </style>
</head>
<body>

    <!-- Header Instansi -->
    <table class="header-table">
        <tr>
            <td>
                <div class="header-title">🐾 Shelter PawCare</div>
                <div class="header-subtitle">Laporan Arus Keuangan & Donasi Donatur Resmi</div>
            </td>
            <td class="header-meta">
                <strong>Tanggal Cetak:</strong> <?= date('d M Y') ?><br>
                <strong>Dicetak Oleh:</strong> SuperAdmin System
            </td>
        </tr>
    </table>

    <!-- Ringkasan Keuangan -->
    <table class="summary-box-table">
        <tr>
            <td style="width: 33%; padding-right: 10px;">
                <div class="summary-card">
                    <div class="summary-label">Total Pemasukan</div>
                    <div class="summary-value" style="color: #16a34a;">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></div>
                </div>
            </td>
            <td style="width: 33%; padding: 0 5px;">
                <div class="summary-card">
                    <div class="summary-label">Total Pengeluaran</div>
                    <div class="summary-value" style="color: #dc2626;">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></div>
                </div>
            </td>
            <td style="width: 33%; padding-left: 10px;">
                <div class="summary-card">
                    <div class="summary-label">Saldo Bersih</div>
                    <div class="summary-value" style="color: #4f46e5;">Rp <?= number_format($saldo_bersih, 0, ',', '.') ?></div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Transaksi -->
    <h3 style="margin-bottom: 10px; color: #1e293b;">Daftar Transaksi Keuangan</h3>
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 12%;">Kode</th>
                <th style="width: 25%;">Nama Donatur</th>
                <th style="width: 18%;">Kategori</th>
                <th style="width: 15%;">Nominal</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8;">Belum ada data transaksi donasi.</td>
                </tr>
            <?php else: ?>
                <?php foreach($data as $d): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($d['kode_donasi'] ?? '—') ?></strong></td>
                    <td><?= htmlspecialchars($d['nama_donatur']) ?></td>
                    <td>
                        <span class="badge badge-<?= strtolower($d['kategori']) ?>">
                            <?= htmlspecialchars($d['kategori']) ?>
                        </span>
                    </td>
                    <td><strong>Rp <?= number_format($d['nominal'], 0, ',', '.') ?></strong></td>
                    <td><?= date('d-m-Y', strtotime($d['tanggal'])) ?></td>
                    <td>
                        <span class="badge badge-<?= strtolower($d['status_konfirmasi']) ?>">
                            <?= htmlspecialchars($d['status_konfirmasi']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>