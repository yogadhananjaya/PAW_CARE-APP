<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<style>
    body {
        overflow: hidden !important;
    }
    .contract-wrapper {
        max-width: 900px;
        margin: 0 auto;
        max-height: calc(100vh - 80px);
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 10px;
    }
    .contract-wrapper::-webkit-scrollbar {
        width: 4px;
    }
    .contract-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }
    .contract-wrapper::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .contract-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .contract-header h2 {
        font-size: 22px;
        font-weight: 800;
        color: var(--hitam);
        margin: 0;
    }
    .contract-actions {
        display: flex;
        gap: 10px;
    }
    .contract-card {
        background: var(--putih);
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        padding: 40px;
        margin-bottom: 25px;
    }
    .contract-title {
        text-align: center;
        margin-bottom: 8px;
    }
    .contract-title h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--hitam);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .contract-doc-number {
        text-align: center;
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 20px;
    }
    .contract-divider {
        border: none;
        border-top: 2px solid var(--hitam);
        margin: 0 0 25px 0;
    }
    .contract-preamble {
        font-size: 14px;
        color: #333;
        line-height: 1.7;
        margin-bottom: 20px;
    }
    .contract-party {
        margin-bottom: 18px;
    }
    .contract-party-title {
        font-weight: 700;
        font-size: 14px;
        color: var(--hitam);
        margin-bottom: 6px;
    }
    .contract-party-detail {
        font-size: 13px;
        color: #444;
        line-height: 1.8;
        padding-left: 15px;
    }
    .contract-party-detail span {
        font-weight: 600;
        color: var(--hitam);
    }
    .contract-animal-info {
        margin: 20px 0;
        padding-left: 15px;
    }
    .contract-animal-info li {
        font-size: 13px;
        color: #444;
        line-height: 1.8;
        margin-bottom: 4px;
    }
    .contract-animal-info li span {
        font-weight: 600;
        color: var(--hitam);
    }
    .contract-pasal {
        margin-top: 25px;
    }
    .contract-pasal-title {
        font-weight: 700;
        font-size: 14px;
        color: var(--hitam);
        margin-bottom: 8px;
        text-transform: uppercase;
    }
    .contract-pasal-text {
        font-size: 13px;
        color: #444;
        line-height: 1.7;
    }
    .contract-signatures {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-top: 40px;
        padding-top: 25px;
        border-top: 1px dashed #ccc;
    }
    .signature-block {
        text-align: center;
    }
    .signature-title {
        font-weight: 700;
        font-size: 13px;
        color: var(--hitam);
        margin-bottom: 50px;
    }
    .signature-status {
        font-size: 12px;
        color: var(--text-muted);
        font-style: italic;
        margin-bottom: 50px;
    }
    .signature-line {
        border-top: 1px solid var(--hitam);
        margin: 0 30px;
        padding-top: 10px;
    }
    .signature-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--hitam);
    }
    .signature-role {
        font-size: 12px;
        color: var(--text-muted);
    }
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }
    .status-draft { background: #fff4e6; color: #d97706; }
    .status-signed { background: #e0f2fe; color: #0369a1; }
    .status-active { background: #dcfce7; color: #15803d; }
    .btn-contract {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
    }
    .btn-back {
        background: var(--krem-gelap);
        color: var(--hitam);
        border: 1px solid rgba(0,0,0,0.08);
    }
    .btn-back:hover { background: #ebe6da; }
    .btn-print {
        background: var(--hitam);
        color: var(--putih);
    }
    .btn-print:hover { background: #2a2a2a; }
    .btn-activate {
        background: #15803d;
        color: var(--putih);
    }
    .btn-activate:hover { background: #166534; }

    @media print {
        @page {
            size: A4;
            margin: 10mm;
        }
        html, body {
            overflow: visible !important;
            height: auto !important;
        }
        .sidebar, .contract-actions, .admin-header, .sidebar-user { display: none !important; }
        .main-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            min-height: auto !important;
        }
        .contract-wrapper {
            max-height: none !important;
            overflow: visible !important;
            padding: 0 !important;
            transform: scale(0.92);
            transform-origin: top center;
        }
        .contract-card {
            box-shadow: none !important;
            border: none !important;
            padding: 15px 20px !important;
            border-radius: 0 !important;
            page-break-inside: avoid;
        }
        .contract-header { display: none !important; }
        .contract-title h3 { font-size: 16px !important; }
        .contract-preamble, .contract-pasal-text { font-size: 11px !important; line-height: 1.5 !important; }
        .contract-party-detail { font-size: 11px !important; line-height: 1.6 !important; }
        .contract-animal-info li { font-size: 11px !important; line-height: 1.6 !important; }
        .contract-pasal-title { font-size: 12px !important; margin-bottom: 4px !important; }
        .contract-pasal { margin-top: 12px !important; }
        .contract-party { margin-bottom: 10px !important; }
        .contract-signatures { margin-top: 20px !important; gap: 30px !important; }
        .signature-title { margin-bottom: 35px !important; font-size: 11px !important; }
        .signature-status { margin-bottom: 35px !important; }
        .contract-divider { margin-bottom: 15px !important; }
        .contract-doc-number { margin-bottom: 12px !important; }
    }
</style>

<div class="main-wrapper">
    <div class="contract-wrapper">
        <!-- Header -->
        <div class="contract-header">
            <h2>📄 E-Contract Adopsi: <?= htmlspecialchars($data['nama_hewan'] ?? 'N/A') ?></h2>
            <div class="contract-actions">
                <?php
                $statusClass = 'status-draft';
                $statusText = 'MENUNGGU ADOPTER';
                if ($data['status_kontrak'] == 'Ditandatangani') {
                    $statusClass = 'status-signed';
                    $statusText = 'DITANDATANGANI';
                } elseif ($data['status_kontrak'] == 'Aktif') {
                    $statusClass = 'status-active';
                    $statusText = 'AKTIF';
                }
                ?>
                <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                <a href="index.php?page=transaksi_adopsi" class="btn-contract btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    Kembali
                </a>
                <button onclick="window.print()" class="btn-contract btn-print">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak
                </button>
                <?php if ($data['status_kontrak'] == 'Ditandatangani'): ?>
                <a href="index.php?page=transaksi_adopsi_activate&id=<?= $data['id_adopsi'] ?>" class="btn-contract btn-activate" onclick="return confirm('Aktifkan kontrak adopsi ini?')">
                    ✓ Aktifkan Kontrak
                </a>
                <?php endif; ?>
                <?php if (empty($data['ttd_admin'])): ?>
                <button onclick="openSignatureModal()" class="btn-contract btn-activate" style="background:#4f46e5; border:none; cursor:pointer;">
                    ✍ Tanda Tangani
                </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_GET['signed_success'])): ?>
            <div style="background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; border-radius:10px; padding:12px 18px; margin-bottom:20px; font-weight:600; font-size:14px;">
                ✓ Tanda tangan Koordinator berhasil disimpan ke dalam kontrak adopsi.
            </div>
        <?php endif; ?>

        <!-- Contract Document -->
        <div class="contract-card">
            <div class="contract-title">
                <h3>Surat Perjanjian Adopsi Hewan</h3>
            </div>
            <div class="contract-doc-number">
                Nomor Dokumen: SPA/<?= $data['id_adopsi'] ?>/<?= date('Y') ?>
            </div>
            <hr class="contract-divider">

            <p class="contract-preamble">
                Pada hari ini, tanggal <strong><?= date('d M Y', strtotime($data['tanggal_adopsi'] ?? 'now')) ?></strong>, kami yang bertandatangan di bawah ini menerangkan sepakat mengadakan perjanjian adopsi hewan peliharaan:
            </p>

            <!-- PIHAK PERTAMA -->
            <div class="contract-party">
                <div class="contract-party-title">PIHAK PERTAMA (Shelter):</div>
                <div class="contract-party-detail">
                    Nama Instansi: <span>Shelter Perlindungan & Adopsi Hewan</span><br>
                    Alamat: <span>Kompleks Perlindungan Hewan Utama, Blok A-B</span>
                </div>
            </div>

            <!-- PIHAK KEDUA -->
            <div class="contract-party">
                <div class="contract-party-title">PIHAK KEDUA (Adopter):</div>
                <div class="contract-party-detail">
                    Nama Lengkap: <span><?= htmlspecialchars($data['nama_pengadopsi'] ?? '-') ?></span><br>
                    Nomor HP: <span><?= htmlspecialchars($data['hp_adopter'] ?? '-') ?></span><br>
                    Alamat Rumah: <span><?= htmlspecialchars($data['alamat_adopter'] ?? '-') ?></span>
                </div>
            </div>

            <p class="contract-preamble" style="margin-top: 20px;">
                Kedua belah pihak bersepakat untuk melakukan adopsi atas hewan peliharaan dengan rincian sebagai berikut:
            </p>

            <!-- Detail Hewan -->
            <ul class="contract-animal-info">
                <li>Nama Hewan: <span><?= htmlspecialchars($data['nama_hewan'] ?? '-') ?></span></li>
                <li>Kategori Hewan: <span><?= htmlspecialchars($data['kategori_hewan'] ?? '-') ?> (<?= htmlspecialchars($data['nama_ras'] ?? '-') ?>)</span></li>
                <li>Estimasi Umur: <span><?= htmlspecialchars($data['estimasi_umur'] ?? '-') ?> Bulan</span></li>
                <li>Jenis Kelamin: <span><?= htmlspecialchars($data['jenis_kelamin'] ?? '-') ?></span></li>
            </ul>

            <!-- PASAL 1 -->
            <div class="contract-pasal">
                <div class="contract-pasal-title">Pasal 1: Ketentuan Perawatan</div>
                <p class="contract-pasal-text">
                    PIHAK KEDUA berkomitmen untuk memberikan perawatan yang layak kepada hewan tersebut, mencakup penyediaan makanan bernutrisi, tempat tinggal yang aman, perhatian kasih sayang, serta memberikan perawatan medis/vaksinasi apabila diperlukan.
                </p>
            </div>

            <!-- PASAL 2 -->
            <div class="contract-pasal">
                <div class="contract-pasal-title">Pasal 2: Larangan dan Pengalihan</div>
                <p class="contract-pasal-text">
                    PIHAK KEDUA dilarang keras menelantarkan, menyiksa, memperjualbelikan, atau menyerahkan hewan tersebut kepada pihak lain tanpa persetujuan tertulis dari PIHAK PERTAMA. Apabila PIHAK KEDUA sudah tidak mampu merawat, hewan wajib dikembalikan ke shelter PIHAK PERTAMA.
                </p>
            </div>

            <!-- Tanda Tangan -->
            <div class="contract-signatures">
                <div class="signature-block">
                    <div class="signature-title">PIHAK PERTAMA (Koordinator Shelter)</div>
                    <?php if (!empty($data['ttd_admin'])): ?>
                        <img src="<?= $data['ttd_admin'] ?>" alt="TTD Koordinator" style="max-height: 60px; margin-bottom: 10px;">
                    <?php else: ?>
                        <div class="signature-status">(Menunggu TTD Koordinator)</div>
                    <?php endif; ?>
                    <div class="signature-line">
                        <div class="signature-name"><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Koordinator Shelter') ?></div>
                        <div class="signature-role">Koordinator</div>
                    </div>
                </div>
                <div class="signature-block">
                    <div class="signature-title">PIHAK KEDUA (Adopter)</div>
                    <?php if (!empty($data['ttd_adopter'])): ?>
                        <img src="<?= $data['ttd_adopter'] ?>" alt="TTD Adopter" style="max-height: 60px; margin-bottom: 10px;">
                    <?php else: ?>
                        <div class="signature-status">(Belum Ditandatangani)</div>
                    <?php endif; ?>
                    <div class="signature-line">
                        <div class="signature-name"><?= htmlspecialchars($data['nama_pengadopsi'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tanda Tangan Admin/Koordinator -->
<div id="sigModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:25px; border-radius:16px; width:450px; text-align:center; box-shadow:0 10px 25px rgba(0,0,0,0.15); border:1px solid #e2e8f0; font-family:'Inter', sans-serif;">
        <h3 style="margin-bottom:10px; font-weight:700; color:#1e293b; font-size:18px;">Tanda Tangan Koordinator</h3>
        <p style="font-size:13px; color:#64748b; margin-bottom:20px;">Silakan bubuhkan tanda tangan Anda di bawah ini menggunakan mouse atau touch screen.</p>
        
        <canvas id="sigCanvas" width="400" height="200" style="border:2px dashed #cbd5e1; border-radius:8px; background:#f8fafc; cursor:crosshair; touch-action:none;"></canvas>
        
        <div style="margin-top:20px; display:flex; gap:10px; justify-content:center;">
            <button class="btn" style="border:1px solid #cbd5e1; padding:8px 16px; border-radius:8px; cursor:pointer; background:#fff; font-weight:600; color:#475569;" onclick="clearCanvas()">Hapus</button>
            <button class="btn" style="border:1px solid #cbd5e1; padding:8px 16px; border-radius:8px; cursor:pointer; background:#64748b; color:#fff; font-weight:600;" onclick="closeSignatureModal()">Batal</button>
            <form action="index.php?page=transaksi_adopsi_sign&id=<?= $data['id_adopsi'] ?>" method="POST" id="ttdForm">
                <input type="hidden" name="ttd_admin" id="ttd_base64">
                <button type="button" class="btn" style="background:#4f46e5; color:#fff; padding:8px 20px; border-radius:8px; border:none; cursor:pointer; font-weight:600;" onclick="saveSignature()">Simpan TTD</button>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById("sigModal");
    const canvas = document.getElementById("sigCanvas");
    const ctx = canvas.getContext("2d");
    let isDrawing = false;

    // Atur gaya pena
    ctx.strokeStyle = "#1e293b"; 
    ctx.lineWidth = 3.5; 
    ctx.lineJoin = "round"; 
    ctx.lineCap = "round";

    function openSignatureModal() {
        modal.style.display = "flex";
        clearCanvas();
    }

    function closeSignatureModal() {
        modal.style.display = "none";
    }

    function getCoordinates(event) {
        const rect = canvas.getBoundingClientRect();
        if (event.touches && event.touches.length > 0) {
            return { x: event.touches[0].clientX - rect.left, y: event.touches[0].clientY - rect.top };
        }
        return { x: event.clientX - rect.left, y: event.clientY - rect.top };
    }

    function startDrawing(e) {
        isDrawing = true;
        const pos = getCoordinates(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
        e.preventDefault();
    }

    function draw(e) {
        if (!isDrawing) return;
        const pos = getCoordinates(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        e.preventDefault();
    }

    function stopDrawing() {
        isDrawing = false;
        ctx.closePath();
    }

    // Mouse events
    canvas.addEventListener("mousedown", startDrawing);
    canvas.addEventListener("mousemove", draw);
    canvas.addEventListener("mouseup", stopDrawing);
    canvas.addEventListener("mouseout", stopDrawing);

    // Touch events
    canvas.addEventListener("touchstart", startDrawing, {passive: false});
    canvas.addEventListener("touchmove", draw, {passive: false});
    canvas.addEventListener("touchend", stopDrawing);

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function saveSignature() {
        const dataUrl = canvas.toDataURL("image/png");
        
        // Cek apakah canvas kosong
        const blank = document.createElement('canvas');
        blank.width = canvas.width;
        blank.height = canvas.height;
        if (canvas.toDataURL() === blank.toDataURL()) {
            alert("Silakan bubuhkan tanda tangan terlebih dahulu.");
            return;
        }
        
        document.getElementById("ttd_base64").value = dataUrl;
        document.getElementById("ttdForm").submit();
    }
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
