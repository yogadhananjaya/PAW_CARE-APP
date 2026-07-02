<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Registrasi Hewan Masuk</h2>
        <a href="index.php?page=hewan" class="btn btn-secondary">&larr; Batal</a>
    </header>

    <div class="card" style="max-width: 850px;">
        <?php if (!empty($error_duplikat)): ?>
            <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                ⚠️ <?= htmlspecialchars($error_duplikat) ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=hewan_create" method="POST" enctype="multipart/form-data">
            <!-- Nama Hewan -->
            <div class="form-group">
                <label>Nama Hewan</label>
                <input type="text" name="nama_hewan" class="form-control" required placeholder="Contoh: Chiko...">
            </div>

            <div style="display: flex; gap: 20px;">
                <!-- Jenis Hewan -->
                <div class="form-group" style="flex: 1;">
                    <label>Jenis Hewan</label>
                    <select name="id_jenis" id="id_jenis" class="form-control" required onchange="filterRas()">
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach($jenis_list as $j): ?>
                            <option value="<?= $j['id_jenis'] ?>"><?= $j['nama_jenis'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Ras Hewan -->
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
                <!-- Jenis Kelamin -->
                <div class="form-group" style="flex: 1;">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
                <!-- Tanggal Lahir -->
                <div class="form-group" style="flex: 1;">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control">
                </div>
                <!-- Estimasi Umur -->
                <div class="form-group" style="flex: 1;">
                    <label>Estimasi Umur (bulan)</label>
                    <input type="number" name="estimasi_umur" id="estimasi_umur" class="form-control" required min="0" placeholder="Contoh: 3">
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <!-- Status Adopsi -->
                <div class="form-group" style="flex: 1;">
                    <label>Status Adopsi</label>
                    <input type="text" value="Karantina" class="form-control" readonly style="background: #f1f5f9; cursor: not-allowed;">
                    <input type="hidden" name="status_adopsi" value="Karantina">
                </div>
                <!-- Sumber Intake -->
                <div class="form-group" style="flex: 1;">
                    <label>Sumber Hewan Masuk</label>
                    <select name="sumber_intake" class="form-control" required id="sumber_intake" onchange="toggleDonatur()">
                        <option value="Breeding">Breeding (Pembiakan Internal)</option>
                        <option value="Donasi">Donasi (Diterima dari Donatur)</option>
                        <option value="Legacy">Legacy (Titipan / Warisan)</option>
                    </select>
                </div>
            </div>

            <!-- Data Donatur (muncul jika Donasi) -->
            <div id="donatur_section" style="display:none;">
                <div style="display:flex; gap:20px;">
                    <div class="form-group" style="flex:1;">
                        <label>Nama Donatur</label>
                        <input type="text" name="nama_donatur" class="form-control" placeholder="Nama lengkap donatur">
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label>Kontak Donatur</label>
                        <input type="text" name="kontak_donatur" class="form-control" placeholder="Nomor HP donatur">
                    </div>
                </div>
            </div>

            <div style="display:flex; gap:20px;">
                <!-- Tanggal Intake -->
                <div class="form-group" style="flex:1;">
                    <label>Tanggal Masuk Shelter</label>
                    <input type="date" name="tanggal_intake" class="form-control" required>
                </div>
            </div>

            <!-- Keterangan Intake -->
            <div class="form-group">
                <label>Keterangan Kondisi Saat Masuk <small style="color:#999;">(opsional)</small></label>
                <textarea name="keterangan_intake" class="form-control" rows="2" placeholder="Contoh: Datang dalam kondisi kurus, sudah dibersihkan..."></textarea>
            </div>

            <!-- Upload Foto -->
            <div class="form-group">
                <label>Upload Foto Hewan</label>
                <input type="file" name="url_foto_hewan" class="form-control" accept="image/*">
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label>Deskripsi (Karakter & Kondisi)</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsikan karakter dan kondisi medis hewan..."></textarea>
            </div>

            <!-- Hobi -->
            <div class="form-group">
                <label>Hobi Hewan</label>
                <input type="text" name="hobi" class="form-control" placeholder="Contoh: Mengejar bola kecil, tidur siang...">
            </div>

            <!-- Fun Fact -->
            <div class="form-group">
                <label>Fun Fact Hewan</label>
                <input type="text" name="funfact" class="form-control" placeholder="Contoh: Hanya makan jika ditemani, takut dengan suara air...">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:10px;">Simpan Record</button>
        </form>
    </div>
</div>

<script>
// Filter ras berdasarkan jenis yang dipilih
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

// Tampilkan/sembunyikan input donatur berdasarkan sumber intake
function toggleDonatur() {
    var sumber = document.getElementById('sumber_intake').value;
    var donaturSection = document.getElementById('donatur_section');
    donaturSection.style.display = (sumber === 'Donasi') ? 'block' : 'none';
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