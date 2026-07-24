<?php
require_once __DIR__ . '/../models/RasModel.php';

class RasController {
    private $m;

    public function __construct() { 
        $this->m = new RasModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Data/ras/index.php';
    }

    public function create() {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_ras = trim($_POST['nama_ras']);
            $id_jenis = $_POST['id_jenis'];
            if ($nama_ras === '') {
                $error_duplikat = "Nama ras tidak boleh kosong!";
            } elseif (strlen($nama_ras) > 100) {
                $error_duplikat = "Nama ras tidak boleh lebih dari 100 karakter!";
            } elseif ($this->m->isDuplicate($nama_ras, $id_jenis)) {
                $error_duplikat = "Ras '{$nama_ras}' sudah terdaftar dalam jenis hewan yang dipilih!";
            } else {
                $this->m->insert($id_jenis, $nama_ras);
                header('Location: index.php?page=ras');
                exit;
            }
        }
        $jenis = $this->m->getOpsiJenis();
        include __DIR__ . '/../../views/Master_Data/ras/create.php';
    }

    public function edit($id) {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_ras = trim($_POST['nama_ras']); // trim untuk menghapus spasi kosong di depan dan belakang nama ras
            $id_jenis = $_POST['id_jenis'];
            if ($nama_ras === '') {
                $error_duplikat = "Nama ras tidak boleh kosong!";
            } elseif (strlen($nama_ras) > 100) {
                $error_duplikat = "Nama ras tidak boleh lebih dari 100 karakter!";
            } elseif ($this->m->isDuplicate($nama_ras, $id_jenis, $id)) {
                $error_duplikat = "Ras '{$nama_ras}' sudah terdaftar dalam jenis hewan yang dipilih!";
            } else {
                $this->m->update($id, $id_jenis, $nama_ras);
                header('Location: index.php?page=ras'); // Memindahkan halaman setelah berhasil di edit
                exit;
            }
        }
        $data = $this->m->getById($id);
        $jenis = $this->m->getOpsiJenis();
        include __DIR__ . '/../../views/Master_Data/ras/edit.php'; // Memanggil dan menampilkan file tampilan
    }

    public function delete($id) {
        if ($this->m->isUsedInHewan($id)) { // Memeriksa apakah data masih digunakan
            header('Location: index.php?page=ras&error_delete=1'); // Memberikan pesan eror jika data masih digunakan
            exit;
        }
        $this->m->delete($id); // Menghapus data
        header('Location: index.php?page=ras'); // Memindahkan halaman setelah berhasil di hapus
        exit;
    }
}
?>