<?php
require_once __DIR__ . '/../models/PenggunaModel.php';

class PenggunaController {
    private $m;

    public function __construct() { 
        $this->m = new PenggunaModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Data/pengguna/index.php';
    }

    public function create() {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['nama_pengguna']);
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $kontak = trim($_POST['kontak']);
            $kata_sandi = $_POST['kata_sandi'];

            // Validasi SuperAdmin hanya boleh pawcare
            if ($_POST['role'] === 'SuperAdmin' || $_POST['jabatan'] === 'SuperAdmin') {
                $error_duplikat = "Tidak boleh membuat SuperAdmin baru selain pawcare!";
            } elseif (strlen($nama_lengkap) < 3 || strlen($nama_lengkap) > 50) {
                $error_duplikat = "Nama lengkap harus diisi 3-50 karakter!";
            } elseif (!preg_match('/^[a-zA-Z0-9._]{4,20}$/', $username)) {
                $error_duplikat = "Format username salah! Harus 4-20 karakter dan hanya boleh berisi huruf, angka, titik (.), atau garis bawah (_). Tanpa spasi.";
            } elseif (strlen($kata_sandi) < 5 || strlen($kata_sandi) > 50) {
                $error_duplikat = "Kata sandi harus diisi 5-50 karakter!";
            } elseif (!preg_match('/^[+0-9\s-]{10,20}$/', $kontak)) {
                $error_duplikat = "Nomor kontak tidak valid! Gunakan angka, spasi, tanda hubung (-), atau plus (+) sepanjang 10-20 karakter.";
            } elseif ($this->m->isDuplicate($username)) {
                $error_duplikat = "Username '" . htmlspecialchars($username) . "' sudah digunakan!";
            } elseif ($this->m->isDuplicateKontak($kontak)) {
                $error_duplikat = "Nomor kontak '" . htmlspecialchars($kontak) . "' sudah terdaftar!";
            } else {
                $_POST['nama_pengguna'] = $username;
                $_POST['nama_lengkap'] = $nama_lengkap;
                $_POST['kontak'] = $kontak;
                $this->m->insert($_POST);
                header('Location: index.php?page=pengguna');
                exit;
            }
        }
        include __DIR__ . '/../../views/Master_Data/pengguna/create.php';
    }

    public function edit($id) {
        $error_duplikat = null;
        $user = $this->m->getById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['nama_pengguna']);
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $kontak = trim($_POST['kontak']);
            $kata_sandi = $_POST['kata_sandi'];
            
            // Pengguna selain pawcare tidak boleh diubah jadi SuperAdmin
            if ($user['nama_pengguna'] !== 'pawcare' && ($_POST['role'] === 'SuperAdmin' || $_POST['jabatan'] === 'SuperAdmin')) {
                $error_duplikat = "Hanya pawcare yang boleh menjadi SuperAdmin!";
            } elseif (strlen($nama_lengkap) < 3 || strlen($nama_lengkap) > 50) {
                $error_duplikat = "Nama lengkap harus diisi 3-50 karakter!";
            } elseif (!preg_match('/^[a-zA-Z0-9._]{4,20}$/', $username)) {
                $error_duplikat = "Format username salah! Harus 4-20 karakter dan hanya boleh berisi huruf, angka, titik (.), atau garis bawah (_). Tanpa spasi.";
            } elseif (!empty($kata_sandi) && (strlen($kata_sandi) < 5 || strlen($kata_sandi) > 50)) {
                $error_duplikat = "Kata sandi baru harus diisi 5-50 karakter!";
            } elseif (!preg_match('/^[+0-9\s-]{10,20}$/', $kontak)) {
                $error_duplikat = "Nomor kontak tidak valid! Gunakan angka, spasi, tanda hubung (-), atau plus (+) sepanjang 10-20 karakter.";
            } elseif ($this->m->isDuplicate($username, $id)) {
                $error_duplikat = "Username '" . htmlspecialchars($username) . "' sudah digunakan oleh pengguna lain!";
            } elseif ($this->m->isDuplicateKontak($kontak, $id)) {
                $error_duplikat = "Nomor kontak '" . htmlspecialchars($kontak) . "' sudah terdaftar pada pengguna lain!";
            } else {
                // Jika pawcare diedit, paksa agar role & jabatan tetap SuperAdmin
                if ($user['nama_pengguna'] === 'pawcare') {
                    $_POST['role'] = 'SuperAdmin';
                    $_POST['jabatan'] = 'SuperAdmin';
                }
                $_POST['nama_pengguna'] = $username;
                $_POST['nama_lengkap'] = $nama_lengkap;
                $_POST['kontak'] = $kontak;
                $this->m->update($id, $_POST);
                header('Location: index.php?page=pengguna');
                exit;
            }
        }
        $data = $this->m->getById($id);
        include __DIR__ . '/../../views/Master_Data/pengguna/edit.php';
    }

    public function delete($id) {
        $user = $this->m->getById($id);
        if ($user && $user['nama_pengguna'] === 'pawcare') {
            header('Location: index.php?page=pengguna');
            exit;
        }
        if ($this->m->isUsed($id)) {
            header('Location: index.php?page=pengguna&error_delete=1');
            exit;
        }
        $this->m->delete($id);
        header('Location: index.php?page=pengguna');
        exit;
    }
}
?>