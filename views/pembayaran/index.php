<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Daftar Pembayaran</h2>
    </header>
    <div class="card">
        <table class="crud-table">
            <thead><tr><th>No</th><th>Kode</th><th>Reference</th><th>Jumlah</th><th>Metode</th><th>Status</th><th>Tgl</th></tr></thead>
            <tbody>
                <?php $no=1; foreach($data as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['kode_pembayaran']) ?></td>
                    <td><?= htmlspecialchars($row['reference']) ?></td>
                    <td>Rp <?= number_format($row['amount'],0,',','.') ?></td>
                    <td><?= htmlspecialchars($row['metode']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
