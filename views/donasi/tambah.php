    <?php
// Hubungkan database dan template header
include "../../config/koneksi.php";
include "../layouts/header.php";

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

    if ($nama_donatur == "" || $nominal == "" || $tanggal == "") {
        echo "<script>alert('Kolom Donatur, Nominal, dan Tanggal gak boleh kosong!'); window.history.back();</script>";
        exit;
    }

    // Query untuk simpan data ke database
    $stmt = $pdo->prepare("INSERT INTO donasi (nama_donatur, nominal, kategori, keterangan, tanggal, metode_pembayaran, url_bukti, status_konfirmasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $sukses = $stmt->execute([$nama_donatur, $nominal, $kategori, $keterangan, $tanggal, $metode_pembayaran, $url_bukti, $status_konfirmasi]);

    if ($sukses) {
        echo "<script>alert('Transaksi donasi berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Aduh gagal nyimpan data!'); window.history.back();</script>";
    }
    exit;
}
?>

<div class="flex-1 overflow-y-auto p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Catat Transaksi Donasi / Keuangan</h2>
            <p class="text-sm text-gray-500 mt-1">Input data pemasukan donasi atau pengeluaran operasional</p>
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
                        <option value="Pemasukan" selected>Pemasukan (Donasi Masuk)</option>
                        <option value="Pengeluaran">Pengeluaran (Operasional Shelter)</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-5">
                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Donatur / Nama Penerima</label>
                    <input type="text" name="nama_donatur" placeholder="Contoh: Budi Santoso atau Toko Pakan A" required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Nominal (Rp)</label>
                    <input type="number" name="nominal" placeholder="Contoh: 500000" min="1" required
                        class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-5">
                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none cursor-pointer">
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Tunai">Tunai</option>
                        <option value="QRIS">QRIS / E-Wallet</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-paw-hitam">Status Konfirmasi</label>
                    <select name="status_konfirmasi" class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none cursor-pointer">
                        <option value="Menunggu" selected>Menunggu Konfirmasi</option>
                        <option value="Dikonfirmasi">Dikonfirmasi (Lunas/Sah)</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-bold text-paw-hitam">URL / Info Bukti Transfer (Opsional)</label>
                <input type="text" name="url_bukti" placeholder="Contoh: https://drive.google.com/... atau No. Ref: 12345"
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none">
            </div>

            <div class="mb-8">
                <label class="block mb-2 text-sm font-bold text-paw-hitam">Keterangan / Rincian</label>
                <textarea name="keterangan" rows="3" placeholder="Rincian donasi atau peruntukan belanja pengeluaran..."
                    class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam text-sm rounded-xl focus:ring-paw-hitam block p-3 outline-none resize-none"></textarea>
            </div>

            <div class="flex justify-end border-t border-paw-krem-gelap pt-5">
                <button type="submit" name="simpan" class="bg-paw-hitam text-paw-putih px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<?php
// Hubungkan template footer
include "../layouts/footer.php";
?>
