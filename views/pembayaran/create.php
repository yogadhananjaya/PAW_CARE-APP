<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Pembayaran Tiket / Donasi</h2>
        <a href="index.php?page=home" class="btn btn-secondary">&larr; Kembali</a>
    </header>

    <div class="card" style="max-width:720px;">
        <form method="POST" action="index.php?page=pembayaran_create">
            <div class="form-group">
                <label>Jumlah (IDR)</label>
                <input type="number" name="amount" class="form-control" required min="1000" step="1000" value="50000">
            </div>
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode" class="form-control">
                    <option value="CreditCard">Credit Card</option>
                    <option value="VA">Virtual Account (Bank)</option>
                    <option value="GOPAY">Gopay</option>
                    <option value="OVO">OVO</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Bayar</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
