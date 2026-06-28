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
            $username = $_POST['nama_pengguna'];
            // ponytail: username hanya boleh alfanumerik, titik, underscore, 4-20 karakter, tanpa spasi
            if (!preg_match('/^[a-zA-Z0-9._]{4,20}$/', $username)) {
                $error_duplikat = "Format username salah! Harus 4-20 karakter dan hanya boleh berisi huruf, angka, titik (.), atau garis bawah (_). Tanpa spasi.";
            } elseif ($this->m->isDuplicate($username)) {
                $error_duplikat = "Username '" . htmlspecialchars($username) . "' sudah digunakan!";
            } elseif ($this->m->isDuplicateKontak($_POST['kontak'])) {
                $error_duplikat = "Nomor kontak '" . htmlspecialchars($_POST['kontak']) . "' sudah terdaftar!";
            } else {
                $this->m->insert($_POST);
                header('Location: index.php?page=pengguna');
                exit;
            }
        }
        include __DIR__ . '/../../views/Master_Data/pengguna/create.php';
    }

    public function edit($id) {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['nama_pengguna'];
            // ponytail: username hanya boleh alfanumerik, titik, underscore, 4-20 karakter, tanpa spasi
            if (!preg_match('/^[a-zA-Z0-9._]{4,20}$/', $username)) {
                $error_duplikat = "Format username salah! Harus 4-20 karakter dan hanya boleh berisi huruf, angka, titik (.), atau garis bawah (_). Tanpa spasi.";
            } elseif ($this->m->isDuplicate($username, $id)) {
                $error_duplikat = "Username '" . htmlspecialchars($username) . "' sudah digunakan oleh pengguna lain!";
            } elseif ($this->m->isDuplicateKontak($_POST['kontak'], $id)) {
                $error_duplikat = "Nomor kontak '" . htmlspecialchars($_POST['kontak']) . "' sudah terdaftar pada pengguna lain!";
            } else {
                $this->m->update($id, $_POST);
                header('Location: index.php?page=pengguna');
                exit;
            }
        }
        $data = $this->m->getById($id);
        include __DIR__ . '/../../views/Master_Data/pengguna/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=pengguna');
        exit;
    }
}
?>