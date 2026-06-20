<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan E-Contract - PawCare</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: var(--krem-utama); display: flex; justify-content: center; align-items: center; height: 100vh; flex-direction: column;}
        .canvas-container { background: var(--putih); padding: 20px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 2px solid var(--krem-gelap); text-align: center; }
        canvas { border: 2px dashed #ccc; border-radius: 8px; cursor: crosshair; background: #fafafa; touch-action: none; }
    </style>
</head>
<body>
    <div class="canvas-container">
        <h2 style="color:var(--hitam); margin-bottom:5px;">Tanda Tangan E-Contract</h2>
        <p style="color:var(--text-muted); font-size:13px; margin-bottom:20px;">Silakan tanda tangan di dalam kotak menggunakan mouse atau jari Anda.</p>
        
        <canvas id="sigCanvas" width="400" height="200"></canvas>
        
        <div style="margin-top: 20px; display: flex; gap:10px; justify-content:center;">
            <button class="btn btn-secondary" onclick="clearCanvas()">Hapus / Ulangi</button>
            <form action="index.php?page=simpan_ttd" method="POST" id="ttdForm">
                <input type="hidden" name="ttd_base64" id="ttd_base64">
                <input type="hidden" name="id_transaksi" value="<?= isset($_GET['id']) ? $_GET['id'] : 0 ?>">
                <button type="button" class="btn btn-primary" onclick="saveSignature()">Simpan & Setujui Adopsi</button>
            </form>
        </div>
    </div>

    <script>
        const canvas = document.getElementById("sigCanvas");
        const ctx = canvas.getContext("2d");
        let isDrawing = false;

        // Atur gaya pena
        ctx.strokeStyle = "#1A1A1A"; ctx.lineWidth = 3; ctx.lineJoin = "round"; ctx.lineCap = "round";

        function getCoordinates(event) {
            const rect = canvas.getBoundingClientRect();
            if(event.touches) { // Untuk HP / Touch
                return { x: event.touches[0].clientX - rect.left, y: event.touches[0].clientY - rect.top };
            }
            return { x: event.clientX - rect.left, y: event.clientY - rect.top }; // Untuk Mouse
        }

        function startDrawing(e) { isDrawing = true; const pos = getCoordinates(e); ctx.beginPath(); ctx.moveTo(pos.x, pos.y); e.preventDefault(); }
        function draw(e) { if (!isDrawing) return; const pos = getCoordinates(e); ctx.lineTo(pos.x, pos.y); ctx.stroke(); e.preventDefault(); }
        function stopDrawing() { isDrawing = false; ctx.closePath(); }

        // Event Listeners Mouse
        canvas.addEventListener("mousedown", startDrawing); canvas.addEventListener("mousemove", draw);
        canvas.addEventListener("mouseup", stopDrawing); canvas.addEventListener("mouseout", stopDrawing);
        // Event Listeners Touch (Mobile)
        canvas.addEventListener("touchstart", startDrawing); canvas.addEventListener("touchmove", draw);
        canvas.addEventListener("touchend", stopDrawing);

        function clearCanvas() { ctx.clearRect(0, 0, canvas.width, canvas.height); }
        function saveSignature() {
            // Mengubah gambar canvas menjadi string teks (Base64) untuk disimpan di DB
            const dataUrl = canvas.toDataURL("image/png");
            document.getElementById("ttd_base64").value = dataUrl;
            document.getElementById("ttdForm").submit();
        }
    </script>
</body>
</html>