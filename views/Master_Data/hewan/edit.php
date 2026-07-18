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
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($hewan['tanggal_lahir'] ?? '') ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Estimasi Umur (bulan)</label>
                    <input type="number" name="estimasi_umur" id="estimasi_umur" class="form-control" value="<?= htmlspecialchars($hewan['estimasi_umur']) ?>" required <?= !empty($hewan['tanggal_lahir']) ? 'readonly' : '' ?>>
                </div>
            </div>

            <div class="form-group">
                <label>Status Shelter</label>
                <input type="text" value="<?= htmlspecialchars($hewan['status_adopsi']) ?>" class="form-control" readonly style="background: #f1f5f9; cursor: not-allowed;">
                <input type="hidden" name="status_adopsi" value="<?= htmlspecialchars($hewan['status_adopsi']) ?>">
            </div>

            <div class="form-group">
                <label>Ganti Foto (Kosongkan jika tidak ingin mengubah foto)</label>
                <?php
                $photo_path = '';
                $url = $hewan['url_foto_hewan'] ?? '';
                $jenis = strtolower($hewan['nama_jenis'] ?? '');
                $ras = strtolower($hewan['nama_ras'] ?? '');
                
                if (!empty($url)) {
                    if (file_exists(__DIR__ . '/../../../uploads/hewan/' . $url)) {
                        $photo_path = 'uploads/hewan/' . $url;
                    } elseif (file_exists(__DIR__ . '/../../../assets/img/hewan/' . $url)) {
                        $photo_path = 'assets/img/hewan/' . $url;
                    }
                }
                
                if (empty($photo_path)) {
                    if (strpos($jenis, 'kucing') !== false) {
                        $kucing_images = [
                            'image.png', 'image copy.png', 'image copy 2.png', 'image copy 3.png',
                            'image copy 4.png', 'image copy 5.png', 'image copy 6.png', 'image copy 7.png',
                            'image copy 8.png', 'image copy 9.png', 'image copy 10.png', 'image copy 11.png'
                        ];
                        $id = intval($hewan['id_hewan'] ?? 0);
                        $idx = $id % count($kucing_images);
                        $photo_path = 'assets/img/hewan/kucing/' . $kucing_images[$idx];
                    } elseif (strpos($jenis, 'anjing') !== false) {
                        $dir_path = __DIR__ . '/../../../assets/img/hewan/anjing/';
                        if (is_dir($dir_path)) {
                            $files = array_diff(scandir($dir_path), array('.', '..', '.gitkeep'));
                            if (count($files) > 0) {
                                $id = intval($hewan['id_hewan'] ?? 0);
                                $files = array_values($files);
                                $idx = $id % count($files);
                                $photo_path = 'assets/img/hewan/anjing/' . $files[$idx];
                            }
                        }
                        if (empty($photo_path)) {
                            $photo_path = 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&q=80&w=600';
                        }
                    } elseif (strpos($jenis, 'kelinci') !== false) {
                        $dir_path = __DIR__ . '/../../../assets/img/hewan/kelinci/';
                        if (is_dir($dir_path)) {
                            $files = array_diff(scandir($dir_path), array('.', '..', '.gitkeep'));
                            if (count($files) > 0) {
                                $id = intval($hewan['id_hewan'] ?? 0);
                                $files = array_values($files);
                                $idx = $id % count($files);
                                $photo_path = 'assets/img/hewan/kelinci/' . $files[$idx];
                            }
                        }
                        if (empty($photo_path)) {
                            $photo_path = 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?auto=format&fit=crop&q=80&w=600';
                        }
                    } else {
                        $photo_path = 'assets/img/logo.png';
                    }
                }
                ?>
                <div style="margin-bottom:10px;">
                    <img src="<?= htmlspecialchars($photo_path) ?>" style="height:100px; border-radius:8px;" onerror="this.onerror=null; this.src='assets/img/logo.png';">
                </div>
                <input type="file" name="url_foto_hewan" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($hewan['deskripsi']) ?></textarea>
            </div>

            <!-- Hobi -->
            <div class="form-group">
                <label>Hobi Hewan</label>
                <input type="text" name="hobi" class="form-control" placeholder="Contoh: Mengejar bola kecil, tidur siang..." value="<?= htmlspecialchars($hewan['hobi'] ?? '') ?>" required>
            </div>

            <!-- Fun Fact -->
            <div class="form-group">
                <label>Fun Fact Hewan</label>
                <input type="text" name="funfact" class="form-control" placeholder="Contoh: Hanya makan jika ditemani, takut dengan suara air..." value="<?= htmlspecialchars($hewan['funfact'] ?? '') ?>" required>
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

// Hitung estimasi umur otomatis ketika tanggal lahir diubah
document.getElementById('tanggal_lahir').addEventListener('change', function() {
    var birthDateVal = this.value;
    var ageInput = document.getElementById('estimasi_umur');
    if (birthDateVal) {
        var birthDate = new Date(birthDateVal);
        var today = new Date();
        var months = (today.getFullYear() - birthDate.getFullYear()) * 12 + (today.getMonth() - birthDate.getMonth());
        if (today.getDate() < birthDate.getDate()) {
            months--;
        }
        if (months < 0) months = 0;
        ageInput.value = months;
        ageInput.readOnly = true;
    } else {
        ageInput.value = '';
        ageInput.readOnly = false;
    }
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>