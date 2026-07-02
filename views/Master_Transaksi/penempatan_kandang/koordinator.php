<?php
// =========================================================================
// HALAMAN PENEMPATAN KANDANG - TAMPILAN KHUSUS KOORDINATOR
// Formulir penempatan + daftar lokasi + okupansi kapasitas
// =========================================================================

// Ambil info user dari session
$nama_lengkap_session = "Koordinator";
if (isset($_SESSION['nama_lengkap'])) {
    $nama_lengkap_session = $_SESSION['nama_lengkap'];
}
$current_role = $_SESSION['role'] ?? '';

// Ambil pesan sukses jika ada
$show_success = false;
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $show_success = true;
}
?>
<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<style>
    /* Style khusus halaman penempatan */
    .pk-grid-top {
        display: grid;
        grid-template-columns: <?= in_array($current_role, ['Perawat', 'Perawat Hewan']) ? '1fr' : '1fr 1.3fr' ?>;
        gap: 25px;
        align-items: start;
        margin-bottom: 25px;
    }
    .pk-card {
        background: var(--putih);
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        padding: 25px;
    }
    .pk-card-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    .pk-form-header {
        color: #4f46e5;
    }
    .pk-label {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        display: block;
    }
    .pk-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        outline: none;
        transition: 0.2s;
    }
    .pk-input:focus {
        border-color: var(--hitam);
        box-shadow: 0 0 0 2px rgba(17,17,17,0.05);
    }
    .pk-field {
        margin-bottom: 15px;
    }
    .pk-checkbox-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 10px 0 20px 0;
        font-size: 13px;
        color: #334155;
    }
    .pk-checkbox-row input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #4f46e5;
    }
    .pk-submit {
        width: 100%;
        background: #4f46e5;
        color: #ffffff;
        font-weight: 700;
        padding: 14px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .pk-submit:hover {
        background: #4338ca;
    }
    .pk-table {
        width: 100%;
        border-collapse: collapse;
    }
    .pk-table th {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 8px;
        text-align: left;
        background: #f8fafc;
    }
    .pk-table td {
        padding: 16px 8px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    .pk-table tr:last-child td {
        border-bottom: none;
    }
    .pk-animal-name {
        font-weight: 700;
        color: #0f172a;
    }
    .pk-badge-kandang {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        background: #ede9fe;
        color: #6d28d9;
    }
    .pk-badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
    }
    .pk-badge-tersedia { background: #dcfce7; color: #15803d; }
    .pk-badge-karantina { background: #fee2e2; color: #b91c1c; }
    .pk-badge-proses { background: #fed7aa; color: #c2410c; }
    /* Progress bar okupansi */
    .pk-progress-bar {
        position: relative;
        width: 150px;
        height: 10px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
        margin-left: 8px;
    }
    .pk-progress-fill {
        display: block;
        height: 100%;
        border-radius: 10px;
        background: #3182CE;
        transition: width 0.3s;
        min-width: 2px;
    }
    .pk-progress-fill.warning {
        background: #f59e0b;
    }
    .pk-progress-fill.danger {
        background: #ef4444;
    }
    .pk-progress-text {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        display: inline-block;
        vertical-align: middle;
        min-width: 50px;
    }
    .pk-okupansi-lokasi {
        font-size: 12px;
        color: #64748b;
    }
    .alert-sukses {
        background: #dcfce7;
        color: #15803d;
        padding: 12px 18px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
        border: 1px solid #bbf7d0;
    }
</style>

<!-- ===================================================================== -->
<!-- KONTEN UTAMA: PENEMPATAN KANDANG                                       -->
<!-- ===================================================================== -->
<div class="main-wrapper">

    <!-- Pesan notifikasi -->
    <?php if ($show_success): ?>
        <div class="alert-sukses">✅ Hewan berhasil ditempatkan ke kandang.</div>
    <?php endif; ?>
    <?php if (isset($_GET['success_release']) && $_GET['success_release'] == '1'): ?>
        <div class="alert-sukses">✅ Hewan berhasil dikeluarkan dari kandang.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate'): ?>
        <div class="alert-sukses" style="background:#fee2e2; color:#b91c1c; border-color:#fecaca;">⚠️ Hewan sudah aktif berada di kandang pilihan tersebut!</div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'jenis_tidak_cocok'): ?>
        <div class="alert-sukses" style="background:#fee2e2; color:#b91c1c; border-color:#fecaca;">⚠️ Jenis hewan tidak cocok dengan jenis kandang!</div>
    <?php endif; ?>

    <!-- Baris 1: Form di kiri (disembunyikan untuk Perawat), Daftar Lokasi di kanan -->
    <div class="pk-grid-top">

        <!-- ============================== -->
        <!-- KOLOM KIRI: FORM TEMPATKAN     -->
        <!-- ============================== -->
        <?php if (!in_array($current_role, ['Perawat', 'Perawat Hewan'])): ?>
        <div class="pk-card">
            <div class="pk-card-header pk-form-header">
                <span>🏠</span> Tempatkan / Pindahkan Hewan
            </div>

            <form action="index.php?page=penempatan_kandang_koordinator" method="POST">

                <!-- Pilih Hewan -->
                <div class="pk-field">
                    <label class="pk-label">Pilih Hewan</label>
                    <select name="id_hewan" id="select-hewan" class="pk-input" required>
                        <option value="">-- Pilih Hewan --</option>
                        <?php foreach ($h as $hw): ?>
                            <option value="<?php echo $hw['id_hewan']; ?>" data-jenis="<?php echo $hw['id_jenis']; ?>">
                                <?php echo htmlspecialchars($hw['nama_hewan']); ?> (Status: <?php echo htmlspecialchars($hw['status_adopsi']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Kandang Tujuan -->
                <div class="pk-field">
                    <label class="pk-label">Kandang Tujuan</label>
                    <select name="id_kandang" id="select-kandang" class="pk-input" required>
                        <option value="">-- Pilih Kandang --</option>
                        <?php foreach ($k as $kd): 
                            $kapasitas_kd = intval($kd['kapasitas']);
                            $terisi_kd = intval($kd['terisi']);
                            $sisa_slot = $kapasitas_kd - $terisi_kd;
                        ?>
                            <option value="<?php echo $kd['id_kandang']; ?>" data-jenis="<?php echo $kd['id_jenis']; ?>" data-full="<?php echo ($sisa_slot <= 0) ? '1' : '0'; ?>" <?php if ($sisa_slot <= 0) echo 'disabled'; ?>>
                                <?php echo htmlspecialchars($kd['kode_kandang'] . ' - ' . $kd['nama_kandang']); ?> (Sisa Slot: <?php echo $sisa_slot; ?>/<?php echo $kapasitas_kd; ?>)<?php if ($sisa_slot <= 0) echo ' - PENUH'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tanggal Masuk -->
                <div class="pk-field">
                    <label class="pk-label">Tanggal Masuk Kandang</label>
                    <input type="date" name="tanggal_masuk" class="pk-input" required value="<?php echo date('Y-m-d'); ?>">
                </div>

                <!-- Checkbox Jadikan Tersedia (default tercentang) -->
                <div class="pk-checkbox-row">
                    <input type="checkbox" name="jadikan_tersedia" id="jadikan_tersedia" value="1" checked>
                    <label for="jadikan_tersedia" style="margin: 0; font-weight: 500; cursor: pointer;">Jadikan hewan 'Tersedia' untuk adopsi (jika sebelumnya karantina)</label>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="pk-submit">Tempatkan Hewan</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- ============================== -->
        <!-- KOLOM KANAN: DAFTAR LOKASI     -->
        <!-- ============================== -->
        <div class="pk-card">
            <div class="pk-card-header">
                <span>📋</span> Lokasi Penempatan Hewan Saat Ini
            </div>

            <table class="pk-table">
                <thead>
                    <tr>
                        <th style="padding-left: 20px;">Nama Hewan</th>
                        <th>Jenis Hewan</th>
                        <th>Kode Kandang</th>
                        <th>Tanggal Masuk</th>
                        <th>Status</th>
                        <?php if (!in_array($current_role, ['Perawat', 'Perawat Hewan'])): ?>
                        <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Kelompokkan data penempatan yang berstatus 'Aktif' berdasarkan Kandang
                    $grouped = [];
                    if (isset($data) && is_array($data)) {
                        foreach ($data as $pk) {
                            if (($pk['status'] ?? '') === 'Aktif') {
                                $kandang_key = $pk['kode_kandang'] . ' - ' . $pk['nama_kandang'];
                                $grouped[$kandang_key][] = $pk;
                            }
                        }
                    }

                    if (count($grouped) > 0) {
                        foreach ($grouped as $kandang_name => $items) {
                            // Render baris sub-header kategori kandang
                            echo '<tr style="background: #f1f5f9; font-weight: bold;">';
                            echo '<td colspan="6" style="padding: 10px 15px; color: #4f46e5; border-bottom: 2px solid #cbd5e1;">🏢 ' . htmlspecialchars($kandang_name) . '</td>';
                            echo '</tr>';
                            
                            foreach ($items as $pk) {
                                $nama_hewan_pk = htmlspecialchars($pk['nama_hewan']);
                                $nama_jenis_pk = htmlspecialchars($pk['nama_jenis'] ?? '—');
                                $tanggal_masuk_pk = date('d M Y', strtotime($pk['tanggal_masuk']));
                                
                                echo '<tr>';
                                echo '<td style="padding-left: 30px;"><span class="pk-animal-name">🐕 ' . $nama_hewan_pk . '</span></td>';
                                echo '<td>' . $nama_jenis_pk . '</td>';
                                echo '<td><span class="pk-badge-kandang">' . htmlspecialchars($pk['kode_kandang']) . '</span></td>';
                                echo '<td>' . $tanggal_masuk_pk . '</td>';
                                echo '<td><span class="pk-badge-status pk-badge-tersedia">AKTIF</span></td>';
                                if (!in_array($current_role, ['Perawat', 'Perawat Hewan'])) {
                                    echo '<td>';
                                    echo '<a href="index.php?page=penempatan_kandang_release&id=' . $pk['id_penempatan'] . '" class="btn" style="border: 1px solid #fecaca; background: #fff5f5; color: var(--merah); padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center;" onclick="return confirm(\'Keluarkan hewan ' . addslashes($nama_hewan_pk) . ' dari kandang ini?\')">Keluarkan</a>';
                                    echo '</td>';
                                }
                                echo '</tr>';
                            }
                        }
                    } else {
                        $colspan = in_array($current_role, ['Perawat', 'Perawat Hewan']) ? 5 : 6;
                        echo '<tr>';
                        echo '<td colspan="' . $colspan . '" style="padding: 30px; text-align: center; color: #94a3b8;">Belum ada hewan di dalam kandang saat ini.</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- ============================== -->
    <!-- BARIS 2: OKUPANSI KAPASITAS    -->
    <!-- ============================== -->
    <div class="pk-card">
        <div class="pk-card-header">
            <span>📊</span> Okupansi Kapasitas Kandang
        </div>

        <table class="pk-table">
            <thead>
                <tr>
                    <th>Kandang</th>
                    <th>Lokasi</th>
                    <th>Okupansi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($okupansi) && count($okupansi) > 0) {
                    foreach ($okupansi as $ok) {
                        $nama_kandang_ok = htmlspecialchars($ok['nama_kandang']);
                        $kode_kandang_ok = htmlspecialchars($ok['kode_kandang']);
                        $kapasitas = intval($ok['kapasitas']);
                        $terisi = intval($ok['terisi']);

                        // Hitung persentase
                        $persen = 0;
                        if ($kapasitas > 0) {
                            $persen = ($terisi / $kapasitas) * 100;
                        }

                        // Tentukan warna progress bar
                        $warna_class = '';
                        if ($persen >= 90) {
                            $warna_class = 'danger';
                        } elseif ($persen >= 70) {
                            $warna_class = 'warning';
                        }

                        // Lokasi/blok (saya pakai fallback kalau tidak ada kolom blok)
                        $lokasi_text = 'Blok ' . substr($kode_kandang_ok, 0, 1);

                        echo '<tr>';
                        echo '<td><span class="pk-animal-name">' . $nama_kandang_ok . '</span></td>';
                        echo '<td><span class="pk-okupansi-lokasi">' . $lokasi_text . '</span></td>';
                        echo '<td>';
                        echo '<span class="pk-progress-text">' . $terisi . ' / ' . $kapasitas . ' Ekor</span>';
                        echo '<span class="pk-progress-bar">';
                        echo '<span class="pk-progress-fill ' . $warna_class . '" style="width: ' . $persen . '%;"></span>';
                        echo '</span>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo '<td colspan="3" style="padding: 30px; text-align: center; color: #94a3b8;">Belum ada data kandang.</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    
    <!-- BARIS 3: RIWAYAT PENEMPATAN    -->
    
    <?php
    $riwayat_list = [];
    if (isset($data) && is_array($data)) {
        foreach ($data as $pk) {
            if (($pk['status'] ?? '') === 'Riwayat') {
                $riwayat_list[] = $pk;
            }
        }
    }
    ?>
    <div class="pk-card" style="margin-top: 25px;">
        <div class="pk-card-header">
            <span>📜</span> Riwayat Penempatan Kandang (Log Masuk-Keluar)
        </div>

        <table class="pk-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Kode</th>
                    <th>Nama Hewan</th>
                    <th>Jenis Hewan</th>
                    <th>Kandang</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($riwayat_list) > 0) {
                    $no = 1;
                    foreach ($riwayat_list as $r) {
                        $nama_hewan_r = htmlspecialchars($r['nama_hewan']);
                        $nama_jenis_r = htmlspecialchars($r['nama_jenis'] ?? '—');
                        $kode_kandang_r = htmlspecialchars($r['kode_kandang']);
                        $nama_kandang_r = htmlspecialchars($r['nama_kandang']);
                        $tgl_masuk = date('d M Y', strtotime($r['tanggal_masuk']));
                        $tgl_keluar = !empty($r['tanggal_keluar']) ? date('d M Y', strtotime($r['tanggal_keluar'])) : '—';
                        
                        echo '<tr>';
                        echo '<td>' . $no++ . '</td>';
                        echo '<td>' . htmlspecialchars($r['kode_penempatan_kandang'] ?? '') . '</td>';
                        echo '<td><span class="pk-animal-name">🐕 ' . $nama_hewan_r . '</span></td>';
                        echo '<td>' . $nama_jenis_r . '</td>';
                        echo '<td><span class="pk-badge-kandang">' . $kode_kandang_r . ' - ' . $nama_kandang_r . '</span></td>';
                        echo '<td>' . $tgl_masuk . '</td>';
                        echo '<td>' . $tgl_keluar . '</td>';
                        echo '<td><span class="pk-badge-status" style="background:#f1f5f9; color:#64748b; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:700;">RIWAYAT</span></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo '<td colspan="8" style="padding: 30px; text-align: center; color: #94a3b8;">Belum ada riwayat penempatan kandang.</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.getElementById('select-hewan').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    var idJenisHewan = selectedOption.getAttribute('data-jenis');
    
    var selectKandang = document.getElementById('select-kandang');
    var optionsKandang = selectKandang.options;
    
    // Reset selection
    selectKandang.value = "";
    
    for (var i = 0; i < optionsKandang.length; i++) {
        var opt = optionsKandang[i];
        if (opt.value === "") {
            opt.style.display = "block";
            continue;
        }
        
        var idJenisKandang = opt.getAttribute('data-jenis');
        if (!idJenisHewan || idJenisHewan === idJenisKandang) {
            opt.style.display = "block";
            if (opt.getAttribute('data-full') === '1') {
                opt.disabled = true;
            } else {
                opt.disabled = false;
            }
        } else {
            opt.style.display = "none";
            opt.disabled = true;
        }
    }
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
