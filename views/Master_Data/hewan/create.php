<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Registrasi Hewan Masuk</h2>
        <a href="index.php?page=hewan" class="btn btn-secondary">&larr; Batal</a>
    </header>

    <div class="card" style="max-width: 800px;">
        <form action="index.php?page=hewan_create" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Hewan</label>
                <input type="text" name="nama_hewan" class="form-control" required placeholder="Contoh: Chiko...">
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Jenis Hewan</label>
                    <select name="id_jenis" id="id_jenis" class="form-control" required onchange="filterRas()">
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach($jenis_list as $j): ?>
                            <option value="<?= $j['id_jenis'] ?>"><?= $j['nama_jenis'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Ras Hewan</label>
                    <select name="id_ras" id="id_ras" class="form-control" required>
                        <option value="">-- Pilih Jenis Dahulu --</option>
                        <?php foreach($ras_list as $r): ?>
                            <option value="<?= $r['id_ras'] ?>" data-jenis="<?= $r['id_jenis'] ?>"><?= $r['nama_ras'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Perkiraan Umur</label>
                    <input type="text" name="umur" class="form-control" placeholder="Contoh: 5 Bulan" required>
                </div>
            </div>

            <div class="form-group">
                <label>Status Shelter</label>
                <select name="status" class="form-control" required>
                    <option value="Karantina">Karantina (Sakit / Adaptasi)</option>
                    <option value="Tersedia">Tersedia (Siap Diadopsi)</option>
                    <option value="Diadopsi">Diadopsi (Sudah Punya Majikan)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Upload Foto Visual Hewan</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label>Deskripsi (Karakter & Kondisi Medis)</label>
                <textarea name="deskripsi" class="form-control" rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:10px;">Simpan Record</button>
        </form>
    </div>
</div>

<script>
function filterRas() {
    var jenisSelect = document.getElementById('id_jenis');
    var rasSelect = document.getElementById('id_ras');
    var selectedJenis = jenisSelect.value;
    var options = rasSelect.options;

    rasSelect.value = "";
    for (var i = 0; i < options.length; i++) {
        var opt = options[i];
        if (opt.value === "") continue;
        if (opt.getAttribute('data-jenis') === selectedJenis) {
            opt.style.display = 'block';
        } else {
            opt.style.display = 'none';
        }
    }
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>