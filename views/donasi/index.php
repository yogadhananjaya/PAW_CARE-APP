<?php
// Hubungkan database dan template header
include "../../config/koneksi.php";
include "../layouts/header.php";

// 1. Hitung total pemasukan yang sudah dikonfirmasi
$query_masuk = $pdo->query("SELECT SUM(nominal) AS total FROM donasi WHERE kategori = 'Pemasukan' AND status_konfirmasi = 'Dikonfirmasi'");
$data_masuk = $query_masuk->fetch();
$total_pemasukan = $data_masuk['total'];
if ($total_pemasukan == null) {
    $total_pemasukan = 0;
}

// 2. Hitung total pengeluaran yang sudah dikonfirmasi
$query_keluar = $pdo->query("SELECT SUM(nominal) AS total FROM donasi WHERE kategori = 'Pengeluaran' AND status_konfirmasi = 'Dikonfirmasi'");
$data_keluar = $query_keluar->fetch();
$total_pengeluaran = $data_keluar['total'];
if ($total_pengeluaran == null) {
    $total_pengeluaran = 0;
}

// 3. Hitung sisa uang kas
$kas_bersih = $total_pemasukan - $total_pengeluaran;

// 4. Ambil semua data donasi untuk ditampilkan di tabel
$query_tabel = $pdo->query("SELECT * FROM donasi ORDER BY id_donasi DESC");
?>

<div class="flex-1 overflow-y-auto p-8">

    <!-- Header Halaman -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Manajemen Data Donasi & Keuangan</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola arus kas masuk dan keluar shelter</p>
        </div>
        <a href="tambah.php"
            class="bg-paw-hitam text-paw-putih px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Catat Transaksi Baru
        </a>
    </div>

    <!-- Ringkasan Keuangan Cards -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <!-- Pemasukan -->
        <div class="bg-paw-putih p-6 rounded-2xl shadow-sm border border-paw-krem-gelap flex justify-between items-center relative overflow-hidden">
            <div class="absolute left-0 top-0 w-2 h-full bg-green-500"></div>
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Total Pemasukan (Dikonfirmasi)</p>
                <h3 class="text-2xl font-black text-green-600">Rp <?php echo number_format($total_pemasukan, 0, ',', '.'); ?></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-xl text-green-600">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-paw-putih p-6 rounded-2xl shadow-sm border border-paw-krem-gelap flex justify-between items-center relative overflow-hidden">
            <div class="absolute left-0 top-0 w-2 h-full bg-paw-merah"></div>
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Total Pengeluaran (Dikonfirmasi)</p>
                <h3 class="text-2xl font-black text-paw-merah">Rp <?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-xl text-paw-merah">
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>
        </div>

        <!-- Saldo Kas Bersih -->
        <div class="bg-paw-putih p-6 rounded-2xl shadow-sm border border-paw-krem-gelap flex justify-between items-center relative overflow-hidden">
            <div class="absolute left-0 top-0 w-2 h-full bg-blue-500"></div>
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Saldo Kas Bersih</p>
                <h3 class="text-2xl font-black text-blue-600">Rp <?php echo number_format($kas_bersih, 0, ',', '.'); ?></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-xl text-blue-600">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
        </div>
    </div>

    <!-- Alert Status -->
    <?php if (isset($_GET['success'])) { ?>
        <div class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 mb-6 rounded-r-lg font-medium text-sm flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i><?php echo $_GET['success']; ?>
        </div>
    <?php } ?>

    <?php if (isset($_GET['error'])) { ?>
        <div class="bg-red-100 border-l-4 border-paw-merah text-paw-merah p-4 mb-6 rounded-r-lg font-medium text-sm flex items-center shadow-sm">
            <i class="fa-solid fa-circle-xmark text-xl mr-3"></i><?php echo $_GET['error']; ?>
        </div>
    <?php } ?>

    <!-- BAGIAN TABEL DONASI -->
    <div class="bg-paw-putih rounded-2xl shadow-sm border border-paw-krem-gelap overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-paw-krem-gelap text-xs uppercase text-paw-hitam font-bold">
                        <th class="px-6 py-4 w-12">No</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Donatur / Penerima</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paw-krem-gelap">
                    <?php
                    $no = 1;
                    while ($d = $query_tabel->fetch()) {
                    ?>
                        <tr class="hover:bg-paw-krem-utama/50 transition">
                            <td class="px-6 py-4 text-sm text-gray-400 font-medium"><?php echo $no; $no++; ?></td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap"><?php echo $d['tanggal']; ?></td>
                            <td class="px-6 py-4 font-bold text-paw-hitam"><?php echo $d['nama_donatur']; ?></td>
                            <td class="px-6 py-4">
                                <?php if ($d['kategori'] == 'Pemasukan') { ?>
                                    <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-bold border border-green-200">
                                        Pemasukan
                                    </span>
                                <?php } else { ?>
                                    <span class="bg-red-100 text-paw-merah text-xs px-2.5 py-1 rounded-full font-bold border border-red-200">
                                        Pengeluaran
                                    </span>
                                <?php } ?>
                            </td>
                            <td class="px-6 py-4 font-bold <?php if ($d['kategori'] == 'Pemasukan') { echo 'text-green-600'; } else { echo 'text-paw-merah'; } ?>">
                                <?php if ($d['kategori'] == 'Pemasukan') { echo '+'; } else { echo '-'; } ?> Rp <?php echo number_format($d['nominal'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                                <?php echo $d['keterangan']; ?>
                            </td>
                            <td class="px-6 py-4 text-gray-600"><?php echo $d['metode_pembayaran']; ?></td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <?php if ($d['status_konfirmasi'] == 'Dikonfirmasi') { ?>
                                    <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-bold">
                                        <i class="fa-solid fa-circle-check mr-1"></i> Dikonfirmasi
                                    </span>
                                <?php } else if ($d['status_konfirmasi'] == 'Ditolak') { ?>
                                    <span class="bg-red-100 text-paw-merah text-xs px-2.5 py-1 rounded-full font-bold">
                                        <i class="fa-solid fa-circle-xmark mr-1"></i> Ditolak
                                    </span>
                                <?php } else { ?>
                                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2.5 py-1 rounded-full font-bold">
                                        <i class="fa-solid fa-clock mr-1"></i> Menunggu
                                    </span>
                                <?php } ?>
                            </td>
                            <td class="px-6 py-4 text-center w-32">
                                <div class="flex justify-center gap-2">
                                    <a href="edit.php?id=<?php echo $d['id_donasi']; ?>"
                                        class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-paw-hitam hover:text-paw-putih transition shadow-sm">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <a href="hapus.php?id=<?php echo $d['id_donasi']; ?>"
                                        onclick="return confirm('Yakin ingin menghapus data donasi ini?')"
                                        class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:bg-paw-merah hover:text-paw-putih transition shadow-sm">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if ($query_tabel->rowCount() == 0) { ?>
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-gray-500 italic">
                                Belum ada riwayat transaksi donasi...
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php
// Hubungkan template footer
include "../layouts/footer.php";
?>
