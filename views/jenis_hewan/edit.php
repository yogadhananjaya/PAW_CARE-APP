<?php
    include 'koneksi.php';
    $id = $_GET['id'];
    $data = mysqli_query($koneksi, "SELECT * FROM Jenis_Hewan WHERE id_jenis='$id'");
    $row = mysqli_fetch_array($data);
    if(isset($_POST['update'])) {
        $nama_jenis = $_POST['nama_jenis'];
        mysqli_query($koneksi, "UPDATE Jenis_Hewan SET nama_jenis='$nama_jenis' WHERE id_jenis='$id'");
        echo "<script>alert('Jenis hewan berhasil diupdate!'); window.location='index.php';</script>";
    }
    include 'layout_header.php';
?>

<div class="flex flex-col items-center justify-center h-full">
    <div class="w-full max-w-md bg-paw-putih rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="p-8 bg-paw-merah text-paw-putih text-center">
            <h2 class="text-2xl font-bold">Ubah Jenis Hewan</h2>
        </div>
        <form action="" method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Nama Jenis</label>
                <input type="text" name="nama_jenis" value="<?= $data['nama_jenis']; ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-paw-merah transition" required>
            </div>
            <button type="submit" name="update" class="w-full bg-paw-merah text-paw-putih py-4 rounded-xl font-bold shadow-lg">UPDATE JENIS</button>
            <a href="index.php" class="block text-center text-sm text-gray-500 font-bold uppercase tracking-widest mt-4">Batal</a>
        </form>
    </div>
</div>

<?php include 'layout_footer.php'; ?>