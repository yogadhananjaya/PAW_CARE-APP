<?php
// AMBIL KONEKSI DAN HEADER
include "koneksi.php";
include "layout_header.php";

// Perintah SQL untuk mengambil semua data pengadopsi
$query = "SELECT * FROM Pengadopsi ORDER BY id_pengadopsi DESC";
$hasil = mysqli_query($koneksi, $query);
?>

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-bold text-paw-hitam">Data Pengadopsi</h2>
        <p class="text-gray-500 mt-1">Daftar orang yang ingin atau sudah mengadopsi hewan.</p>
    </div>
    <a href="tambah.php" class="bg-paw-hitam text-paw-putih px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Pengadopsi
    </a>
</div>

<!-- TABEL DATA -->
<div class="bg-paw-putih rounded-2xl shadow-sm border border-[#e0d6c8] overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-paw-krem-gelap border-b border-[#e0d6c8]">
                <th class="px-6 py-4 font-bold text-sm uppercase text-gray-700">No</th>
                <th class="px-6 py-4 font-bold text-sm uppercase text-gray-700">Nama</th>
                <th class="px-6 py-4 font-bold text-sm uppercase text-gray-700">No. HP</th>
                <th class="px-6 py-4 font-bold text-sm uppercase text-gray-700">Email</th>
                <th class="px-6 py-4 font-bold text-sm uppercase text-gray-700">Alamat</th>
                <th class="px-6 py-4 font-bold text-sm uppercase text-gray-700 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0d6c8]">
            <?php 
            $no = 1;
            // Ambil data satu-satu pake perulangan while
            while ($row = mysqli_fetch_array($hasil)) { 
            ?>
            <tr class="hover:bg-paw-krem-utama transition">
                <td class="px-6 py-4 text-gray-600 font-medium"><?= $no++; ?></td>
                <td class="px-6 py-4 font-bold text-paw-hitam"><?= $row['nama']; ?></td>
                <td class="px-6 py-4 text-gray-600"><?= $row['no_hp']; ?></td>
                <td class="px-6 py-4 text-gray-600"><?= $row['email']; ?></td>
                <td class="px-6 py-4 text-gray-600 line-clamp-1"><?= $row['alamat']; ?></td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <!-- Tombol Edit -->
                        <a href="edit.php?id=<?= $row['id_pengadopsi']; ?>" class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm border border-blue-100">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <!-- Tombol Hapus -->
                        <a href="hapus.php?id=<?= $row['id_pengadopsi']; ?>" onclick="return confirm('Beneran mau hapus data ini?')" class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-50 text-paw-merah hover:bg-paw-merah hover:text-white transition shadow-sm border border-red-100">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php } ?>

            <?php if (mysqli_num_rows($hasil) == 0) { ?>
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                    Belum ada data pengadopsi nih...
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include "layout_footer.php"; ?>
