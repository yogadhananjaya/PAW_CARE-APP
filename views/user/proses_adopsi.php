<?php
global $pdo;
if (empty($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

// Ambil id_pengadopsi menggunakan model terpusat dan cek cooldown 1 bulan
require_once __DIR__ . '/../../app/models/PengadopsiModel.php';
$pm = new PengadopsiModel();
$stmt_adopter = $pdo->prepare("SELECT id_pengadopsi FROM pengadopsi WHERE id_pengguna = ?");
$stmt_adopter->execute([$_SESSION['user_id']]);
$adopter = $stmt_adopter->fetch();
if ($adopter) {
    // Gunakan helper untuk memeriksa apakah boleh mengajukan adopsi lagi
    if (!$pm->canAdoptAgain($adopter['id_pengadopsi'])) {
        // Tampilkan pesan informatif dan hentikan alur
        $stmt_last_adopsi = $pdo->prepare("SELECT tanggal_adopsi FROM transaksi_adopsi WHERE id_pengadopsi = ? ORDER BY tanggal_adopsi DESC LIMIT 1");
        $stmt_last_adopsi->execute([$adopter['id_pengadopsi']]);
        $last = $stmt_last_adopsi->fetch();
        $tgl_bisa_adopsi = $last ? date('d F Y', strtotime($last['tanggal_adopsi'] . ' + 1 month')) : '';
        include __DIR__ . '/../layouts/header.php';
                echo <<<HTML
<div class='main-wrapper' style='max-width: 800px; margin: 40px auto; padding: 20px;'>
    <div style='background:#fee2e2; border:1px solid #fecaca; color:#b91c1c; padding: 25px; border-radius:12px; font-weight:600; line-height:1.6; font-size:15px; text-align:center;'>
        ⚠️ Maaf, Anda baru saja melakukan adopsi. Anda harus menunggu 1 bulan sejak adopsi terakhir sebelum mengajukan adopsi baru.<br><br>
        Anda dapat mengajukan kembali setelah: <strong style="font-size:18px; text-decoration:underline;">{$tgl_bisa_adopsi}</strong>.
    </div>
    <div style="text-align:center; margin-top:20px;">
        <a href="index.php?page=dashboard_user&tab=katalog" class="btn btn-secondary" style="background:#cbd5e1; color:#334155; text-decoration:none; padding:10px 20px; border-radius:8px; font-weight:700;">Kembali ke Katalog</a>
    </div>
</div>
HTML;
        include __DIR__ . '/../layouts/footer.php';
        exit;
    }
}

$id_hewan = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT h.*, r.nama_ras, j.nama_jenis FROM hewan h JOIN ras r ON h.id_ras = r.id_ras JOIN jenis_hewan j ON h.id_jenis = j.id_jenis WHERE h.id_hewan = ? AND h.status_adopsi = 'Tersedia' AND h.rekomendasi_adopsi = 1");
$stmt->execute([$id_hewan]);
$hewan = $stmt->fetch();

if (!$hewan) {
    echo "<div class='main-wrapper'><div class='alert-sukses' style='background:#fee2e2; color:#b91c1c;'>⚠️ Hewan tidak ditemukan atau sudah tidak tersedia untuk diadopsi.</div></div>";
    exit;
}

$nama_adopter = $_SESSION['nama_lengkap'] ?? $_SESSION['username'];
?>

<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    .wizard-container {
        max-width: 800px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        padding: 30px;
    }
    .wizard-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }
    .wizard-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 4px;
        background: #e2e8f0;
        z-index: 1;
    }
    .step-indicator {
        position: relative;
        z-index: 2;
        background: #ffffff;
        padding: 0 6px;
        text-align: center;
        flex: 1;
    }
    .step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        border: 4px solid #ffffff;
        transition: 0.3s;
        font-size: 13px;
    }
    .step-indicator.active .step-circle {
        background: #4f46e5;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
    }
    .step-indicator.completed .step-circle {
        background: #10b981;
        color: #ffffff;
    }
    .step-title {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
    }
    .step-indicator.active .step-title { color: #4f46e5; }
    .wizard-panel { display: none; }
    .wizard-panel.active { display: block; }
    .btn-wizard {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: 0.2s;
    }
    .btn-next { background: #4f46e5; color: #ffffff; }
    .btn-next:hover { background: #4338ca; }
    .btn-prev { background: #f1f5f9; color: #475569; }
    .btn-prev:hover { background: #e2e8f0; }
    .btn-wizard:disabled { background: #cbd5e1; color: #94a3b8; cursor: not-allowed; }
    .kuis-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }
    .kuis-options { display: flex; gap: 15px; margin-top: 10px; }
    .kuis-option {
        flex: 1;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
    }
    .kuis-option.selected { border-color: #4f46e5; background: #eeebff; color: #4f46e5; }
    .surat-preview {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        padding: 40px;
        border-radius: 12px;
        max-height: 400px;
        overflow-y: scroll;
        margin-bottom: 20px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 13px;
        line-height: 1.6;
    }
    .sig-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        position: relative;
        margin-bottom: 20px;
        width: 100%;
        height: 200px;
    }
    #signature-canvas {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        cursor: crosshair;
    }
    .materai-badge {
        width: 100px; height: 130px;
        border: 2px dashed #92400e;
        background: #fef3c7;
        color: #92400e;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-weight: bold;
        font-size: 11px;
        text-align: center;
        padding: 5px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .payment-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        background: #f8fafc;
    }
    .visit-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        background: #f8fafc;
    }
</style>

<div class="main-wrapper">
    <div class="wizard-container">
        <h2 style="text-align: center; margin-bottom: 10px;">🐾 Formulir Komitmen & Adopsi Hewan</h2>
        <p style="text-align: center; color: #64748b; font-size: 14px; margin-bottom: 30px;">
            Anda mengajukan adopsi untuk: <strong><?= htmlspecialchars($hewan['nama_hewan']) ?></strong> (<?= htmlspecialchars($hewan['nama_jenis']) ?> - <?= htmlspecialchars($hewan['nama_ras']) ?>)
        </p>

        <div class="wizard-steps">
            <div class="step-indicator active" id="indicator-1">
                <div class="step-circle">1</div>
                <div class="step-title">Komitmen</div>
            </div>
            <div class="step-indicator" id="indicator-2">
                <div class="step-circle">2</div>
                <div class="step-title">Perjanjian</div>
            </div>
            <div class="step-indicator" id="indicator-3">
                <div class="step-circle">3</div>
                <div class="step-title">Tanda Tangan</div>
            </div>
            <div class="step-indicator" id="indicator-4">
                <div class="step-circle">4</div>
                <div class="step-title">Jadwal</div>
            </div>
            <div class="step-indicator" id="indicator-5">
                <div class="step-circle">5</div>
                <div class="step-title">Pembayaran</div>
            </div>
        </div>

        <form action="index.php?page=proses_adopsi_submit" method="POST" id="adoption-form">
            <input type="hidden" name="id_hewan" value="<?= $id_hewan ?>">
            <input type="hidden" name="tanda_tangan_png" id="tanda_tangan_png">

            <!-- LANGKAH 1: KUIS KOMITMEN -->
            <div class="wizard-panel active" id="panel-1">
                <h3 style="margin-bottom: 20px; color: #1e293b;">1. Kuesioner Komitmen Pengadopsi</h3>
                
                <div class="pk-field" style="margin-bottom: 20px;">
                    <label class="pk-label" style="font-weight: 700;">Nama Lengkap Adopter</label>
                    <input type="text" class="pk-input" value="<?= htmlspecialchars($nama_adopter) ?>" readonly style="background: #f1f5f9; cursor: not-allowed; width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                </div>

                <div class="kuis-box">
                    <p style="font-weight: 600; font-size: 14px;">Pertanyaan 1: Apakah Anda berkomitmen memberikan makanan bernutrisi, tempat tinggal yang aman, dan kasih sayang seutuhnya?</p>
                    <div class="kuis-options">
                        <div class="kuis-option" onclick="selectOption(1, 'ya', this)">YA</div>
                        <div class="kuis-option" onclick="selectOption(1, 'tidak', this)">TIDAK</div>
                    </div>
                </div>

                <div class="kuis-box">
                    <p style="font-weight: 600; font-size: 14px;">Pertanyaan 2: Apakah Anda berjanji tidak akan merantai secara kejam, menelantarkan, atau membuang hewan ini jika sakit?</p>
                    <div class="kuis-options">
                        <div class="kuis-option" onclick="selectOption(2, 'ya', this)">YA</div>
                        <div class="kuis-option" onclick="selectOption(2, 'tidak', this)">TIDAK</div>
                    </div>
                </div>

                <div class="kuis-box">
                    <p style="font-weight: 600; font-size: 14px;">Pertanyaan 3: Jika di masa mendatang Anda terpaksa tidak bisa merawatnya lagi, apakah Anda berjanji mengembalikannya ke shelter PawCare (tidak dilepas liar)?</p>
                    <div class="kuis-options">
                        <div class="kuis-option" onclick="selectOption(3, 'ya', this)">YA</div>
                        <div class="kuis-option" onclick="selectOption(3, 'tidak', this)">TIDAK</div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 30px;">
                    <button type="button" class="btn-wizard btn-next" id="btn-next-1" disabled onclick="nextStep(2)">Lanjutkan ke Perjanjian</button>
                </div>
            </div>

            <!-- LANGKAH 2: PREVIEW SURAT PERJANJIAN -->
            <div class="wizard-panel" id="panel-2">
                <h3 style="margin-bottom: 20px; color: #1e293b;">2. Surat Pernyataan Tanggung Jawab Mutlak</h3>
                
                <div class="surat-preview">
                    <h4 style="text-align: center; margin-bottom: 20px; text-decoration: underline;">SURAT PERNYATAAN ADOPSI HEWAN</h4>
                    <p>Saya yang bertanda tangan di bawah ini:</p>
                    <table style="width: 100%; margin: 15px 0;">
                        <tr><td style="width: 30%;">Nama Lengkap</td><td>: <?= htmlspecialchars($nama_adopter) ?></td></tr>
                        <tr><td>Status Akun</td><td>: Pengadopsi Terverifikasi</td></tr>
                    </table>
                    <p>Menyatakan dengan sesungguhnya bahwa saya mengadopsi hewan peliharaan dari shelter PawCare dengan rincian:</p>
                    <table style="width: 100%; margin: 15px 0;">
                        <tr><td style="width: 30%;">Nama Hewan</td><td>: <?= htmlspecialchars($hewan['nama_hewan']) ?></td></tr>
                        <tr><td>Kode Hewan</td><td>: <?= htmlspecialchars($hewan['kode_hewan']) ?></td></tr>
                        <tr><td>Jenis / Ras</td><td>: <?= htmlspecialchars($hewan['nama_jenis']) ?> / <?= htmlspecialchars($hewan['nama_ras']) ?></td></tr>
                    </table>
                    
                    <p style="margin-top: 20px; font-weight: bold;">Dengan ini berjanji dan menjamin sepenuhnya untuk:</p>
                    <ol style="margin-left: 20px; padding-left: 0;">
                        <li>Merawat hewan tersebut dengan penuh kasih sayang dan memperlakukan secara layak.</li>
                        <li>Memenuhi kebutuhan pakan bernutrisi, tempat tinggal yang terlindung dari cuaca, serta perawatan medis (vaksinasi/pengobatan).</li>
                        <li>Tidak melakukan kekerasan fisik, tidak merantai terus-menerus, dan tidak menelantarkan hewan tersebut.</li>
                        <li>Mengembalikan hewan ini secara resmi ke shelter PawCare apabila terjadi keadaan darurat yang membuat saya tidak mampu merawatnya kembali.</li>
                    </ol>
                    <p style="margin-top: 20px;">Demikian surat pernyataan komitmen ini dibuat tanpa ada paksaan dari pihak manapun demi kesejahteraan hewan.</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" id="check-perjanjian" onchange="toggleNext2(this)">
                        Saya menyatakan telah membaca, memahami, dan menyetujui seluruh isi surat perjanjian di atas.
                    </label>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                    <button type="button" class="btn-wizard btn-prev" onclick="prevStep(1)">Kembali</button>
                    <button type="button" class="btn-wizard btn-next" id="btn-next-2" disabled onclick="nextStep(3)">Lanjutkan ke Tanda Tangan</button>
                </div>
            </div>

            <!-- LANGKAH 3: TEMPEL MATERAI & TANDA TANGAN -->
            <div class="wizard-panel" id="panel-3">
                <h3 style="margin-bottom: 10px; color: #1e293b;">3. Bubuhkan Tanda Tangan Digital</h3>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 25px;">Silakan buat tanda tangan Anda langsung di dalam kotak kanvas di bawah. Tanda tangan akan diposisikan secara otomatis bersama materai simulasi.</p>

                <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px;">
                    <div class="materai-badge">
                        <span style="font-size: 9px; opacity: 0.8;">SIMULASI MATERAI</span>
                        <span style="font-size: 12px; margin: 4px 0;">TEMPEL</span>
                        <span style="font-size: 10px; font-weight: 800; border-top: 1px solid #92400e; padding-top: 2px;">Rp 10.000</span>
                    </div>
                    <div style="flex: 1;">
                        <div class="sig-area">
                            <canvas id="signature-canvas"></canvas>
                        </div>
                        <div style="text-align: right; margin-top: 10px;">
                            <button type="button" class="btn-wizard btn-prev" style="padding: 6px 12px; font-size: 12px;" onclick="clearSignature()">Bersihkan Coretan</button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                    <button type="button" class="btn-wizard btn-prev" onclick="prevStep(2)">Kembali</button>
                    <button type="button" class="btn-wizard btn-next" id="btn-next-3" onclick="saveSignatureAndNext()">Lanjutkan ke Jadwal</button>
                </div>
            </div>

            <!-- LANGKAH 4: JADWAL KUNJUNGAN -->
            <div class="wizard-panel" id="panel-4">
                <h3 style="margin-bottom: 10px; color: #1e293b;">4. Tentukan Jadwal Kunjungan ke Shelter</h3>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 25px;">Tentukan waktu kedatangan Anda ke shelter untuk bertemu langsung dengan calon peliharaan baru Anda.</p>

                <div class="visit-card">
                    <input type="hidden" name="metode" id="metode_kunjungan" value="Kunjungan ke Shelter">
                    <input type="hidden" name="alamat_tujuan" value="">

                    <label style="font-size: 13px; font-weight: 600; color: #4a5568; margin-bottom: 5px; display: block;">Tanggal & Waktu Kunjungan</label>
                    <input type="datetime-local" name="tanggal_jadwal" class="pk-input" required min="<?= date('Y-m-d\TH:i') ?>" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; margin-bottom: 15px;">
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                    <button type="button" class="btn-wizard btn-prev" onclick="prevStep(3)">Kembali</button>
                    <button type="button" class="btn-wizard btn-next" id="btn-next-4" onclick="validateAndNext4()">Lanjutkan ke Pembayaran</button>
                </div>
            </div>

            <!-- LANGKAH 5: GATEWAY PEMBAYARAN -->
            <div class="wizard-panel" id="panel-5">
                <h3 style="margin-bottom: 20px; color: #1e293b;">5. Konfirmasi & Pembayaran Administrasi</h3>

                <div class="payment-card">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; color: #334155;">Ringkasan Invoice Adopsi</h4>
                    <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                        <tr style="height: 35px;">
                            <td>Adopsi Hewan: <strong><?= htmlspecialchars($hewan['nama_hewan']) ?></strong></td>
                            <td style="text-align: right; font-weight: 600;">Rp 150.000</td>
                        </tr>
                        <tr style="height: 35px;">
                            <td>Simulasi Materai Perjanjian</td>
                            <td style="text-align: right; font-weight: 600;">Rp 10.000</td>
                        </tr>
                        <tr style="height: 35px; border-top: 2px solid #e2e8f0; font-weight: bold; font-size: 16px; color: #4f46e5;">
                            <td style="padding-top: 10px;">Total Pembayaran</td>
                            <td style="padding-top: 10px; text-align: right;">Rp 160.000</td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="pk-label" style="font-weight: 700; margin-bottom: 10px;">Pilih Metode Pembayaran Simulasi</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px;">
                        <label style="border: 2px solid #4f46e5; background: #eeebff; padding: 15px; border-radius: 10px; text-align: center; cursor: pointer; display: block; font-weight: 700; color: #4f46e5;" onclick="selectPayment(this)">
                            <input type="radio" name="metode_pembayaran" value="Transfer Bank" checked style="display: none;">
                            🏦 Bank Transfer
                        </label>
                        <label style="border: 2px solid #cbd5e1; padding: 15px; border-radius: 10px; text-align: center; cursor: pointer; display: block; font-weight: 600;" onclick="selectPayment(this)">
                            <input type="radio" name="metode_pembayaran" value="QRIS" style="display: none;">
                            📱 QRIS / E-Wallet
                        </label>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                    <button type="button" class="btn-wizard btn-prev" onclick="prevStep(4)">Kembali</button>
                    <div>
                        <button type="button" class="btn-wizard" style="background:#4f46e5; color:#fff; margin-right:10px;" onclick="goToPayment(160000, 'VA')">Bayar via Bank (VA)</button>
                        <button type="button" class="btn-wizard" style="background:#10b981; color:#fff;" onclick="goToPayment(160000, 'QRIS')">Bayar via QRIS</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    let kuisAnswers = { 1: null, 2: null, 3: null };

    function selectOption(qNum, value, el) {
        const options = el.parentNode.querySelectorAll('.kuis-option');
        options.forEach(opt => opt.classList.remove('selected'));
        el.classList.add('selected');
        kuisAnswers[qNum] = value;
        if (kuisAnswers[1] === 'ya' && kuisAnswers[2] === 'ya' && kuisAnswers[3] === 'ya') {
            document.getElementById('btn-next-1').disabled = false;
        } else {
            document.getElementById('btn-next-1').disabled = true;
        }
    }

    function toggleNext2(chk) {
        document.getElementById('btn-next-2').disabled = !chk.checked;
    }

    function validateAndNext4() {
        var tanggal = document.querySelector('input[name="tanggal_jadwal"]').value;
        if (!tanggal) { alert('Silakan pilih tanggal dan waktu kunjungan.'); return; }
        nextStep(5);
    }

    function selectPayment(el) {
        const labels = el.parentNode.querySelectorAll('label');
        labels.forEach(l => {
            l.style.borderColor = '#cbd5e1';
            l.style.background = '#ffffff';
            l.style.color = '#000000';
            l.style.fontWeight = '600';
        });
        el.style.borderColor = '#4f46e5';
        el.style.background = '#eeebff';
        el.style.color = '#4f46e5';
        el.style.fontWeight = '700';
        el.querySelector('input').checked = true;
    }

    function nextStep(step) {
        document.querySelectorAll('.wizard-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.step-indicator').forEach(i => i.classList.remove('active'));
        document.getElementById('panel-' + step).classList.add('active');
        document.getElementById('indicator-' + step).classList.add('active');
        for (let i = 1; i < step; i++) {
            document.getElementById('indicator-' + i).classList.add('completed');
        }
        if (step === 3) { setTimeout(initCanvas, 100); }
    }

    function prevStep(step) {
        document.querySelectorAll('.wizard-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.step-indicator').forEach(i => { i.classList.remove('active'); i.classList.remove('completed'); });
        document.getElementById('panel-' + step).classList.add('active');
        document.getElementById('indicator-' + step).classList.add('active');
        for (let i = 1; i < step; i++) {
            document.getElementById('indicator-' + i).classList.add('completed');
        }
    }

    let canvas, ctx, drawing = false;
    function initCanvas() {
        canvas = document.getElementById('signature-canvas');
        if (!canvas) return;
        ctx = canvas.getContext('2d');
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
        ctx.strokeStyle = '#0f172a';
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDraw);
        canvas.addEventListener('mouseleave', stopDraw);
        canvas.addEventListener('touchstart', startDrawTouch);
        canvas.addEventListener('touchmove', drawTouch);
        canvas.addEventListener('touchend', stopDraw);
    }
    function startDraw(e) { drawing = true; ctx.beginPath(); ctx.moveTo(e.offsetX, e.offsetY); }
    function draw(e) { if (!drawing) return; ctx.lineTo(e.offsetX, e.offsetY); ctx.stroke(); }
    function startDrawTouch(e) {
        drawing = true;
        const rect = canvas.getBoundingClientRect();
        ctx.beginPath();
        ctx.moveTo(e.touches[0].clientX - rect.left, e.touches[0].clientY - rect.top);
        e.preventDefault();
    }
    function drawTouch(e) {
        if (!drawing) return;
        const rect = canvas.getBoundingClientRect();
        ctx.lineTo(e.touches[0].clientX - rect.left, e.touches[0].clientY - rect.top);
        ctx.stroke();
        e.preventDefault();
    }
    function stopDraw() { drawing = false; }
    function clearSignature() { if (ctx && canvas) { ctx.clearRect(0, 0, canvas.width, canvas.height); } }
    function saveSignatureAndNext() {
        const dataURL = canvas.toDataURL('image/png');
        document.getElementById('tanda_tangan_png').value = dataURL;
        nextStep(4);
    }
    function goToPayment(amount, metode) {
        // Redirect ke pembayaran demo dengan parameter amount & metode
        const url = 'index.php?page=pembayaran_create&amount=' + encodeURIComponent(amount) + '&metode=' + encodeURIComponent(metode);
        window.location.href = url;
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
