<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>
<?php $lanjut = isset($_GET['lanjut']) && $_GET['lanjut'] == '1'; $hewan_terpilih = $_GET['hewan'] ?? ($_POST['id_hewan'] ?? ''); ?>
<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Rekam Medis Hewan</h2>
        <a href="index.php?page=riwayat_kesehatan" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 650px;">
        <?php if (!empty($error_duplikat)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=riwayat_kesehatan_create" method="POST">
            <input type="hidden" name="dari_sebelumnya" value="<?= htmlspecialchars($_GET['dari_sebelumnya'] ?? '') ?>">
            <?php if ($lanjut): ?><input type="hidden" name="id_hewan" value="<?= $hewan_terpilih ?>"><?php endif; ?>
            <!-- Pilih Hewan -->
            <div class="form-group">
                <label>Pilih Hewan</label>
                <select id="hewan_select" class="form-control" required onchange="filterVaksin()" <?= $lanjut ? 'disabled style="background:#f1f5f9;cursor:not-allowed;"' : 'name="id_hewan"' ?>>
                    <option value="">-- Pilih Hewan --</option>
                    <?php foreach($h as $hw): ?><option value="<?= $hw['id_hewan'] ?>" data-jenis="<?= $hw['id_jenis'] ?>" <?= ($hewan_terpilih == $hw['id_hewan']) ? 'selected' : '' ?>><?= $hw['nama_hewan'] ?></option><?php endforeach; ?>
                </select>
            </div>

            <?php if ($lanjut): ?><input type="hidden" name="tipe" value="<?= ($_GET['lanjut'] == '2') ? 'Karantina Selesai' : 'Vaksinasi' ?>"><?php endif; ?>
            <!-- Tipe Rekam Medis -->
            <div class="form-group">
                <label>Tipe Rekam Medis</label>
                <select id="tipe_select" class="form-control" required onchange="toggleVaksin()" <?= $lanjut ? 'disabled style="background:#f1f5f9;cursor:not-allowed;"' : 'name="tipe"' ?>>
                    <option value="Perawatan" <?= (!$lanjut) ? '' : '' ?>>Perawatan (Pemeriksaan Umum)</option>
                    <option value="Vaksinasi" <?= ($lanjut && $_GET['lanjut'] == '1') ? 'selected' : '' ?>>Vaksinasi</option>
                    <option value="Karantina Selesai" <?= ($lanjut && $_GET['lanjut'] == '2') ? 'selected' : '' ?>>Karantina Selesai</option>
                </select>
            </div>

            <!-- Vaksin (muncul jika tipe = Vaksinasi) -->
            <div class="form-group" id="vaksin_section" style="display:none;">
                <label>Pilih Vaksin</label>
                <select name="id_vaksin" id="vaksin_select" class="form-control">
                    <option value="">-- Pilih Vaksin --</option>
                    <?php foreach($v as $vk): ?>
                        <?php $v_disabled = ($vk['status'] !== 'Tersedia' || ($vk['stok'] ?? 0) == 0); ?>
                        <option value="<?= $vk['id_vaksin'] ?>" data-jenis-list="<?= $vk['id_jenis_list'] ?? '' ?>" <?= $v_disabled ? 'disabled style="color:#94a3b8;"' : '' ?>>
                            <?= htmlspecialchars($vk['nama_vaksin']) ?>
                            <?php if ($vk['status'] !== 'Tersedia'): ?> (<?= $vk['status'] ?>)
                            <?php elseif (($vk['stok'] ?? 0) == 0): ?> (Habis - Stok 0)
                            <?php elseif (($vk['stok'] ?? 0) < 5): ?> ⚠️ (Segera Restock)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Perawat Penanggung Jawab -->
            <div class="form-group">
                <label>Perawat Penanggung Jawab</label>
                <select name="id_pengguna" class="form-control" required>
                    <option value="">-- Pilih Perawat --</option>
                    <?php foreach($p as $pw): ?><option value="<?= $pw['id_pengguna'] ?>"><?= $pw['nama_pengguna'] ?></option><?php endforeach; ?>
                </select>
            </div>

            <!-- Tanggal -->
            <div class="form-group">
                <label>Tanggal Pemeriksaan</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label>Deskripsi / Catatan Medis</label>
                <textarea name="deskripsi" class="form-control" rows="4" required placeholder="Contoh: Hewan dalam kondisi sehat, diberikan vaksin rabies..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<script>
function toggleVaksin() {
    var tipe = document.getElementById('tipe_select').value;
    document.getElementById('vaksin_section').style.display = (tipe === 'Vaksinasi') ? 'block' : 'none';
}
function filterVaksin() {
    var hewan = document.getElementById('hewan_select');
    var jenis = hewan.options[hewan.selectedIndex].getAttribute('data-jenis');
    var opts = document.getElementById('vaksin_select').options;
    for (var i = 1; i < opts.length; i++) {
        var vList = opts[i].getAttribute('data-jenis-list');
        if (!vList) { opts[i].style.display = ''; continue; }
        var arr = vList.split(',');
        opts[i].style.display = arr.includes(jenis) ? '' : 'none';
    }
    opts[0].selected = true;
}
<?php if ($lanjut): ?>
document.addEventListener('DOMContentLoaded', function() {
    toggleVaksin();
    filterVaksin();
});
<?php endif; ?>
// Karantina Selesai: sembunyikan vaksin section
document.getElementById('tipe_select').addEventListener('change', function() {
    if (this.value === 'Karantina Selesai') document.getElementById('vaksin_section').style.display = 'none';
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>