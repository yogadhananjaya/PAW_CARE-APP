<?php
require_once __DIR__ . '/../models/TransaksiAdopsiModel.php';

class TransaksiAdopsiController {
    private $m;

    public function __construct() { 
        $this->m = new TransaksiAdopsiModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Set penanggung jawab otomatis ke admin yang sedang login jika tidak dikirim
            if (empty($_POST['id_pengguna'])) {
                $_POST['id_pengguna'] = $_SESSION['user_id'] ?? null;
            }
            $this->m->insert($_POST);
            header('Location: index.php?page=transaksi_adopsi');
            exit;
        }
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        $pg = $this->m->getPengguna();
        include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=transaksi_adopsi');
            exit;
        }
        $data = $this->m->getById($id);
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        $pg = $this->m->getPengguna();
        include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=transaksi_adopsi');
        exit;
    }

    public function activate($id) {
        $this->m->activate($id);
        header('Location: index.php?page=transaksi_adopsi_edit&id=' . $id);
        exit;
    }
}
?>