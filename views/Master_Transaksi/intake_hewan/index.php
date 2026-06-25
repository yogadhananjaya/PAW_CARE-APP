<?php
// =========================================================================
// HALAMAN INTAKE HEWAN BARU
// Formulir penerimaan hewan masuk + daftar inventaris terbaru
// =========================================================================

// Ambil koneksi database global
global $pdo;

// Ambil info user dari session
$nama_lengkap_session = "Koordinator";
if (isset($_SESSION['nama_lengkap'])) {
    $nama_lengkap_session = $_SESSION['nama_lengkap'];
}

// Ambil pesan sukses jika ada
$show_success = false;
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $show_success = true;
}

// Hitung jumlah total inventaris
$total_inventaris = 0;
if (isset($recentHewan) && is_array($recentHewan)) {
    $total_inventaris = count($recentHewan);
}
?>
<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_koordinator.php'; ?>

<style>
    /* Style khusus untuk halaman intake */
    .intake-page-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--hitam);
        margin: 0 0 4px 0;
    }
    .intake-page-subtitle {
        font-size: 14px;
        color: var(--text-muted);
        margin: 0 0 20px 0;
    }
    .intake-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 25px;
        align-items: start;
    }
    .intake-form-card {
        background: var(--putih);
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        padding: 25px;
    }
    .intake-form-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        font-weight: 700;
        color: #4f46e5;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    .intake-label {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        display: block;
    }
    .intake-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        outline: none;
        transition: 0.2s;
    }
    .intake-input:focus {
        border-color: var(--hitam);
        box-shadow: 0 0 0 2px rgba(17,17,17,0.05);
    }
    textarea.intake-input {
        resize: vertical;
    }
    .intake-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    .intake-field {
        margin-bottom: 15px;
    }
    .intake-submit {
        width: 100%;
        background: #4f46e5;
        color: #ffffff;
        font-weight: 700;
        padding: 14px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        margin-top: 10px;
        transition: background 0.2s;
    }
    .intake-submit:hover {
        background: #4338ca;
    }
    .intake-list-card {
        background: var(--putih);
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        padding: 25px;
    }
    .intake-list-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 15px;
    }
    .intake-list-table {
        width: 100%;
        border-collapse: collapse;
    }
    .intake-list-table th {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 8px;
        text-align: left;
        background: #f8fafc;
    }
    .intake-list-table td {
        padding: 16px 8px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    .intake-animal-name {
        font-weight: 700;
        color: #0f172a;
    }
    .intake-animal-sub {
        font-size: 11px;
        color: #94a3b8;
    }
    .intake-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
    }
    .badge-breeding { background: #dbeafe; color: #1d4ed8; }
    .badge-donasi { background: #fef3c7; color: #b45309; }
    .badge-legacy { background: #ede9fe; color: #6d28d9; }
    .badge-tersedia { background: #dcfce7; color: #15803d; }
    .badge-karantina { background: #fee2e2; color: #b91c1c; }
    .badge-proses { background: #fed7aa; color: #c2410c; }
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
<!-- KONTEN UTAMA: INTAKE HEWAN BARU                                        -->
<!-- ===================================================================== -->
<div class="main-wrapper">

    <!-- Judul Halaman -->
    <h1 class="intake-page-title">Penerimaan Hewan Baru (Intake)</h1>
    <p class="intake-page-subtitle">Catat hewan baru yang masuk ke shelter baik dari hasil pembiakan, penyerahan donasi, atau legacy.</p>

    <!-- Pesan sukses -->
    <?php if ($show_success): ?>
        <div class="alert-sukses">✅ Hewan baru berhasil dicatat ke inventaris shelter.</div>
    <?php endif; ?>

    <!-- Grid 2 Kolom: Form di kiri, Daftar di kanan -->
    <div class="intake-grid">

        <!-- ============================================== -->
        <!-- KOLOM KIRI: FORMULIR PENERIMAAN                 -->
        <!-- ============================================== -->
        <div class="intake-form-card">
            <div class="intake-form-header">
                <span>➕</span> Formulir Penerimaan
            </div>

            <form action="index.php?page=intake_hewan" method="POST" enctype="multipart/form-data">

                <!-- Nama Hewan -->
                <div class="intake-field">
                    <label class="intake-label">Nama Hewan *</label>
                    <input type="text" name="nama_hewan" class="intake-input" required placeholder="Contoh: Buddy, Miko">
                </div>

                <!-- Baris 2 Kolom: Jenis & Ras -->
                <div class="intake-row-2">
                    <div>
                        <label class="intake-label">Jenis Hewan *</label>
                        <select name="id_jenis" id="id_jenis" class="intake-input" required onchange="filterRas()">
                            <option value="">-- Pilih Jenis --</option>
                            <?php foreach ($jenis_list as $j): ?>
                                <option value="<?php echo $j['id_jenis']; ?>"><?php echo htmlspecialchars($j['nama_jenis']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="intake-label">Ras Hewan *</label>
                        <select name="id_ras" id="id_ras" class="intake-input" required>
                            <option value="">-- Pilih Ras --</option>
                            <?php foreach ($ras_list as $r): ?>
                                <option value="<?php echo $r['id_ras']; ?>" data-jenis="<?php echo $r['id_jenis']; ?>"><?php echo htmlspecialchars($r['nama_ras']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Baris 2 Kolom: Estimasi Umur & Jenis Kelamin -->
                <div class="intake-row-2">
                    <div>
                        <label class="intake-label">Estimasi Umur (Bulan) *</label>
                        <input type="number" name="estimasi_umur" class="intake-input" required min="0" placeholder="12">
                    </div>
                    <div>
                        <label class="intake-label">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="intake-input" required>
                            <option value="">-- Pilih --</option>
                            <option value="Jantan">Jantan</option>
                            <option value="Betina">Betina</option>
                        </select>
                    </div>
                </div>

                <!-- Asal / Sumber Penerimaan -->
                <div class="intake-field">
                    <label class="intake-label">Asal / Sumber Penerimaan *</label>
                    <select name="sumber_intake" id="sumber_intake" class="intake-input" required onchange="toggleDonatur()">
                        <option value="Breeding">Breeding (Pembiakan internal)</option>
                        <option value="Donasi">Donasi (Diterima dari donatur)</option>
                        <option value="Legacy">Legacy (Titipan / Warisan)</option>
                    </select>
                </div>

                <!-- Data Donatur (muncul jika Donasi) -->
                <div id="donatur_section" style="display: none; margin-bottom: 15px;">
                    <div class="intake-row-2">
                        <div>
                            <label class="intake-label">Nama Donatur</label>
                            <input type="text" name="nama_donatur" class="intake-input" placeholder="Nama lengkap donatur">
                        </div>
                        <div>
                            <label class="intake-label">Kontak Donatur</label>
                            <input type="text" name="kontak_donatur" class="intake-input" placeholder="Nomor HP donatur">
                        </div>
                    </div>
                </div>

                <!-- Tanggal Masuk Shelter -->
                <div class="intake-field">
                    <label class="intake-label">Tanggal Masuk Shelter *</label>
                    <input type="date" name="tanggal_intake" class="intake-input" required value="<?php echo date('Y-m-d'); ?>">
                </div>

                <!-- Catatan Asal Usul -->
                <div class="intake-field">
                    <label class="intake-label">Catatan Asal Usul / Keterangan Masuk</label>
                    <textarea name="keterangan_intake" class="intake-input" rows="3" placeholder="Ditemukan terlantar di pasar pagi / anak dari indukan Kitty..."></textarea>
                </div>

                <!-- Foto Hewan -->
                <div class="intake-field">
                    <label class="intake-label">Foto Hewan (.jpg / .png, maks 2MB)</label>
                    <input type="file" name="url_foto_hewan" class="intake-input" accept="image/*">
                </div>

                <!-- Deskripsi Karakter -->
                <div class="intake-field">
                    <label class="intake-label">Deskripsi Karakter / Kepribadian</label>
                    <textarea name="deskripsi" class="intake-input" rows="3" placeholder="Buddy sangat lincah, takut petir, suka bermain air..."></textarea>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="intake-submit">Terima Hewan</button>
            </form>
        </div>

        <!-- ============================================== -->
        <!-- KOLOM KANAN: DAFTAR INVENTARIS HEWAN           -->
        <!-- ============================================== -->
        <div class="intake-list-card">
            <div class="intake-list-header">
                <span>📋</span> Daftar Inventaris Hewan
            </div>

            <table class="intake-list-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jenis / Ras</th>
                        <th>Umur (Estimasi)</th>
                        <th>Sumber</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil 10 hewan terbaru untuk ditampilkan
                    if (isset($recentHewan) && count($recentHewan) > 0) {
                        $hewan_ditampilkan = array_slice($recentHewan, 0, 10);

                        foreach ($hewan_ditampilkan as $hw) {
                            // Siapkan data dengan nilai default jika kosong
                            $nama_hewan_tabel  = htmlspecialchars($hw['nama_hewan']);
                            $jk                = htmlspecialchars($hw['jenis_kelamin']);
                            $nama_jenis_tabel  = htmlspecialchars($hw['nama_jenis']);
                            $nama_ras_tabel    = htmlspecialchars($hw['nama_ras']);
                            $estimasi_umur     = $hw['estimasi_umur'] . ' Bulan';
                            $sumber_intake     = htmlspecialchars($hw['sumber_intake']);
                            $nama_donatur      = '';
                            if (!empty($hw['nama_donatur'])) {
                                $nama_donatur = '<div style="font-size:11px; color:#94a3b8; margin-top:2px;">Oleh: ' . htmlspecialchars($hw['nama_donatur']) . '</div>';
                            }
                            $status_adopsi = htmlspecialchars($hw['status_adopsi']);

                            // Tentukan kelas badge sumber
                            $badge_sumber_class = 'badge-breeding';
                            if ($sumber_intake == 'Donasi') {
                                $badge_sumber_class = 'badge-donasi';
                            } elseif ($sumber_intake == 'Legacy') {
                                $badge_sumber_class = 'badge-legacy';
                            }

                            // Tentukan kelas badge status
                            $badge_status_class = 'badge-tersedia';
                            $status_label = $status_adopsi;
                            if ($status_adopsi == 'Karantina') {
                                $badge_status_class = 'badge-karantina';
                            } elseif ($status_adopsi == 'Tersedia') {
                                $badge_status_class = 'badge-tersedia';
                            } elseif ($status_adopsi == 'Dalam Proses') {
                                $badge_status_class = 'badge-proses';
                                $status_label = 'Proses Adopsi';
                            }

                            // Tampilkan baris tabel
                            echo '<tr>';
                            echo '<td>';
                            echo '<div class="intake-animal-name">' . $nama_hewan_tabel . '</div>';
                            echo '<div class="intake-animal-sub">' . $jk . '</div>';
                            echo '</td>';
                            echo '<td>';
                            echo '<div>' . $nama_jenis_tabel . '</div>';
                            echo '<div style="font-size:11px; color:#94a3b8;">' . $nama_ras_tabel . '</div>';
                            echo '</td>';
                            echo '<td>' . $estimasi_umur . '</td>';
                            echo '<td>';
                            echo '<span class="intake-badge ' . $badge_sumber_class . '">' . strtoupper($sumber_intake) . '</span>';
                            echo $nama_donatur;
                            echo '</td>';
                            echo '<td>';
                            echo '<span class="intake-badge ' . $badge_status_class . '">' . strtoupper($status_label) . '</span>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr>';
                        echo '<td colspan="5" style="padding: 30px; text-align: center; color: #94a3b8;">Belum ada data hewan di inventaris.</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
// Filter dropdown ras berdasarkan jenis yang dipilih
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

// Tampilkan input donatur jika sumber adalah Donasi
function toggleDonatur() {
    var sumber = document.getElementById('sumber_intake').value;
    var donaturSection = document.getElementById('donatur_section');
    if (sumber === 'Donasi') {
        donaturSection.style.display = 'block';
    } else {
        donaturSection.style.display = 'none';
    }
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
