<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Edit Data Hewan</h2>
        <a href="index.php?page=hewan" class="btn btn-secondary">&larr; Batal</a>
    </header>

    <div class="card" style="max-width: 800px;">
        <form action="index.php?page=hewan_edit&id=<?= $hewan['id_hewan'] ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Hewan</label>
                <input type="text" name="nama_hewan" class="form-control" value="<?= htmlspecialchars($hewan['nama_hewan']) ?>" required>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Jenis Hewan</label>
                    <select name="id_jenis" id="id_jenis" class="form-control" required onchange="filterRas()">
                        <?php foreach($jenis_list as $j): ?>
                            <option value="<?= $j['id_jenis'] ?>" <?= $j['id_jenis'] == $hewan['id_jenis'] ? 'selected' : '' ?>><?= $j['nama_jenis'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Ras Hewan</label>
                    <select name="id_ras" id="id_ras" class="form-control" required>
                        <?php foreach($ras_list as $r): ?>
                            <option value="<?= $r['id_ras'] ?>" data-jenis="<?= $r['id_jenis'] ?>" <?= $r['id_ras'] == $hewan['id_ras'] ? 'selected' : '' ?> style="<?= $r['id_jenis'] != $hewan['id_jenis'] ? 'display:none;' : '' ?>">
                                <?= $r['nama_ras'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="Jantan" <?= $hewan['jenis_kelamin'] == 'Jantan' ? 'selected' : '' ?>>Jantan</option>
                        <option value="Betina" <?= $hewan['jenis_kelamin'] == 'Betina' ? 'selected' : '' ?>>Betina</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Perkiraan Umur</label>
                    <input type="text" name="umur" class="form-control" value="<?= htmlspecialchars($hewan['umur']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Status Shelter</label>
                <select name="status" class="form-control" required>
                    <option value="Karantina" <?= $hewan['status'] == 'Karantina' ? 'selected' : '' ?>>Karantina</option>
                    <option value="Tersedia" <?= $hewan['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia (Siap Diadopsi)</option>
                    <option value="Diadopsi" <?= $hewan['status'] == 'Diadopsi' ? 'selected' : '' ?>>Diadopsi</option>
                </select>
            </div>

            <div class="form-group">
                <label>Ganti Foto (Kosongkan jika tidak ingin mengubah foto)</label>
                <?php if(!empty($hewan['foto'])): ?>
                    <div style="margin-bottom:10px;">
                        <img src="assets/img/hewan/<?= htmlspecialchars($hewan['foto']) ?>" style="height:60px; border-radius:5px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($hewan['deskripsi']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-warning" style="margin-top:10px;">Update Record</button>
        </form>
    </div>
</div>

<script>
function filterRas() {
    var jenisSelect = document.getElementById('id_jenis');
    var rasSelect = document.getElementById('id_ras');
    var selectedJenis = jenisSelect.value;
    var options = rasSelect.options;

    // Reset pilihan ras jika jenis diubah
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