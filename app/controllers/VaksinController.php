<?php
require_once __DIR__ . '/../models/VaksinModel.php';

class VaksinController {
    private $m;

    public function __construct() { 
        $this->m = new VaksinModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Data/vaksin/index.php';
    }

    public function create() {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_vaksin = trim($_POST['nama_vaksin']);
            $stok = (int)($_POST['stok'] ?? 0);
            $deskripsi = trim($_POST['deskripsi'] ?? '');

            if ($nama_vaksin === '') {
                $error_duplikat = "Nama vaksin tidak boleh kosong!";
            } elseif (strlen($nama_vaksin) > 50) {
                $error_duplikat = "Nama vaksin tidak boleh lebih dari 50 karakter!";
            } elseif (empty($_POST['id_jenis'])) {
                $error_duplikat = "Jenis hewan wajib dipilih!";
            } elseif ($stok < 0) {
                $error_duplikat = "Stok vaksin tidak boleh kurang dari 0!";
            } elseif ($this->m->isDuplicate($nama_vaksin)) {
                $error_duplikat = "Vaksin '" . htmlspecialchars($nama_vaksin) . "' sudah terdaftar!";
            } else {
                $this->m->insert($nama_vaksin, (int)$_POST['id_jenis'], $deskripsi, $_POST['status'], $stok);
                header('Location: index.php?page=vaksin');
                exit;
            }
        }
        include __DIR__ . '/../../views/Master_Data/vaksin/create.php';
    }

    public function edit($id) {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_vaksin = trim($_POST['nama_vaksin']);
            $stok = (int)($_POST['stok'] ?? 0);
            $deskripsi = trim($_POST['deskripsi'] ?? '');

            if ($nama_vaksin === '') {
                $error_duplikat = "Nama vaksin tidak boleh kosong!";
            } elseif (strlen($nama_vaksin) > 50) {
                $error_duplikat = "Nama vaksin tidak boleh lebih dari 50 karakter!";
            } elseif (empty($_POST['id_jenis'])) {
                $error_duplikat = "Jenis hewan wajib dipilih!";
            } elseif ($stok < 0) {
                $error_duplikat = "Stok vaksin tidak boleh kurang dari 0!";
            } elseif ($this->m->isDuplicate($nama_vaksin, $id)) {
                $error_duplikat = "Vaksin '" . htmlspecialchars($nama_vaksin) . "' sudah terdaftar!";
            } else {
                $this->m->update($id, $nama_vaksin, (int)$_POST['id_jenis'], $deskripsi, $_POST['status'], $stok);
                header('Location: index.php?page=vaksin');
                exit;
            }
        }
        $data = $this->m->getById($id);
        include __DIR__ . '/../../views/Master_Data/vaksin/edit.php';
    }

    public function delete($id) {
        if ($this->m->isUsed($id)) {
            header('Location: index.php?page=vaksin&error_delete=1');
            exit;
        }
        $this->m->delete($id);
        header('Location: index.php?page=vaksin');
        exit;
    }
}
?>