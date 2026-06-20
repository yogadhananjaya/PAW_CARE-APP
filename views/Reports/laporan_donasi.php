<style>
    body { font-family: sans-serif; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    th { background: #f2f2f2; }
</style>
<h2>Laporan Donasi Shelter</h2>
<table>
    <thead>
        <tr><th>Nama Donatur</th><th>Jumlah</th><th>Tanggal</th><th>Status</th></tr>
    </thead>
    <tbody>
        <?php foreach($data as $d): ?>
        <tr>
            <td><?= $d['nama_donatur'] ?></td>
            <td>Rp <?= number_format($d['jumlah']) ?></td>
            <td><?= $d['tanggal'] ?></td>
            <td><?= $d['status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>