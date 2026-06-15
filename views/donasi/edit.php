<?php
// Hubungkan database dan template header
include "../../config/koneksi.php";
include "../layouts/header.php";

// Ambil ID donasi yang mau diedit dari URL
$id = $_GET['id'];

// Ambil data donasi yang lama dari database
$stmt = $pdo->prepare("SELECT * FROM donasi WHERE id_donasi = ?");
$stmt->execute([$id]);
$donasi = $stmt->fetch();

// Jika data donasi tidak ada di database
if ($donasi == false) {
    echo "<script>alert('Data donasi tidak ketemu!'); window.location='index.php';</script>";
    exit;
}

// Jika tombol simpan diklik
if (isset($_POST['simpan'])) {
    // Ambil data dari input form
    $nama_donatur = $_POST['nama_donatur'];
    $nominal = $_POST['nominal'];
    $kategori = $_POST['kategori'];
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $url_bukti = $_POST['url_bukti'];
    $status_konfirmasi = $_POST['status_konfirmasi'];

    // Validasi simpel: cek kalau ada yang kosong
    if ($nama_donatur == "" || $nominal == "" || $tanggal == "") {
        echo "<script>alert('Kolom Donatur, Nominal, dan Tanggal gak boleh kosong!'); window.history.back();</script>";
        exit;
    }

    // Update data ke database
    $stmtUpdate = $pdo->prepare("UPDATE donasi SET nama_donatur = ?, nominal = ?, kategori = ?, keterangan = ?, tanggal = ?, metode_pembayaran = ?, url_bukti = ?, status_konfirmasi = ? WHERE id_donasi = ?");
    $sukses = $stmtUpdate->execute([$nama_donatur, $nominal, $kategori, $keterangan, $tanggal, $metode_pembayaran, $url_bukti, $status_konfirmasi, $id]);

    if ($sukses) {
        echo "<script>alert('Data donasi berhasil diubah!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Aduh gagal mengubah data!'); window.history.back();</script>";
    }
    exit;
}
?>

<div class="flex-1 overflow-y-auto p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Edit Transaksi Donasi / Keuangan</h2>
            <p class="text-sm text-gray-500 mt-1">Ubah data transaksi keuangan yang tercatat</p>
        </div>
        <a href="index.php" class="text-paw-hitam bg-paw-krem-gelap px-5 py-2.5 rounded-xl text-sm font-bold border border-[#e0d6c8] hover:bg-gray-200 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Batal
        </a>
    </div>

    <div class="bg-paw-putih p-8 rounded-2xl shadow-sm border border-paw-krem-gelap max-w-3xl">
        <form action="" method="POST">
            
            <div class="grid grid-cols-2 gap-6 mb-5">
                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Kategori Transaksi</label>
                    <select name="kategori" required class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none cursor-pointer">
                        <option value="Pemasukan" <?php if ($donasi['kategori'] == 'Pemasukan') { echo 'selected'; } ?>>Pemasukan (Donasi Masuk)</option>
                        <option value="Pengeluaran" <?php if ($donasi['kategori'] == 'Pengeluaran') { echo 'selected'; } ?>>Pengeluaran (Operasional Shelter)</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" value="<?php echo $donasi['tanggal']; ?>" required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-5">
                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Donatur / Nama Penerima</label>
                    <input type="text" name="nama_donatur" value="<?php echo $donasi['nama_donatur']; ?>" required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Nominal (Rp)</label>
                    <input type="number" name="nominal" value="<?php echo $donasi['nominal']; ?>" min="1" required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-5">
                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none cursor-pointer">
                        <option value="Transfer Bank" <?php if ($donasi['metode_pembayaran'] == 'Transfer Bank') { echo 'selected'; } ?>>Transfer Bank</option>
                        <option value="Tunai" <?php if ($donasi['metode_pembayaran'] == 'Tunai') { echo 'selected'; } ?>>Tunai</option>
                        <option value="QRIS" <?php if ($donasi['metode_pembayaran'] == 'QRIS') { echo 'selected'; } ?>>QRIS / E-Wallet</option>
                        <option value="Lainnya" <?php if ($donasi['metode_pembayaran'] == 'Lainnya') { echo 'selected'; } ?>>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Status Konfirmasi</label>
                    <select name="status_konfirmasi" class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none cursor-pointer">
                        <option value="Menunggu" <?php if ($donasi['status_konfirmasi'] == 'Menunggu') { echo 'selected'; } ?>>Menunggu Konfirmasi</option>
                        <option value="Dikonfirmasi" <?php if ($donasi['status_konfirmasi'] == 'Dikonfirmasi') { echo 'selected'; } ?>>Dikonfirmasi (Lunas/Sah)</option>
                        <option value="Ditolak" <?php if ($donasi['status_konfirmasi'] == 'Ditolak') { echo 'selected'; } ?>>Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold text-paw-hitam">URL / Info Bukti Transfer (Opsional)</label>
                <input type="text" name="url_bukti" value="<?php echo $donasi['url_bukti']; ?>" placeholder="Contoh: https://drive.google.com/... atau No. Ref: 12345"
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
            </div>

            <div class="mb-8">
                <label class="block mb-2 text-sm font-bold text-paw-hitam">Keterangan / Rincian</label>
                <textarea name="keterangan" rows="3" placeholder="Rincian donasi atau peruntukan belanja pengeluaran..."
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none resize-none"><?php echo $donasi['keterangan']; ?></textarea>
            </div>

            <div class="flex justify-end border-t border-paw-krem-gelap pt-5">
                <button type="submit" name="simpan" class="bg-paw-hitam text-paw-putih px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?php
// Hubungkan template footer
include "../layouts/footer.php";
?>
