<?php
// FILE EDIT_RAS.PHP
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM Ras WHERE id_ras = '$id'"));

if(isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $id_jenis = $_POST['id_jenis'];
    mysqli_query($koneksi, "UPDATE Ras SET nama_ras = '$nama', id_jenis = '$id_jenis' WHERE id_ras = '$id'");
    echo "<script>alert('Ras Berhasil diperbarui!'); window.location='index.php';</script>";
}

include 'layout_header.php';
?>

<div class="flex flex-col items-center justify-center h-full">
    <div class="w-full max-w-md bg-paw-putih rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="p-8 bg-paw-merah text-paw-putih text-center">
            <h2 class="text-2xl font-bold">Ubah Ras Hewan</h2>
        </div>
        <form action="" method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Nama Ras</label>
                <input type="text" name="nama" value="<?= $data['nama_ras']; ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Jenis Hewan</label>
                <select name="id_jenis" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
                    <?php
                    $q = mysqli_query($koneksi, "SELECT * FROM Jenis_Hewan");
                    while($j = mysqli_fetch_array($q)) {
                        $pilih = ($j['id_jenis'] == $data['id_jenis']) ? "selected" : "";
                        echo "<option value='".$j['id_jenis']."' $pilih>".$j['nama_jenis']."</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="update" class="w-full bg-paw-merah text-paw-putih py-4 rounded-xl font-bold shadow-lg">UPDATE RAS</button>
            <a href="index.php" class="block text-center text-sm text-gray-500 font-bold uppercase tracking-widest mt-4">Batal</a>
        </form>
    </div>
</div>

<?php include 'layout_footer.php'; ?>
