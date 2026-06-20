<?php
require_once __DIR__ . '/../models/RiwayatKesehatanModel.php';

class RiwayatKesehatanController {
    private $m;

    public function __construct() { 
        $this->m = new RiwayatKesehatanModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Transaksi/riwayat_kesehatan/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST);
            header('Location: index.php?page=riwayat_kesehatan');
            exit;
        }
        $h = $this->m->getHewan();
        $v = $this->m->getVaksin();
        $p = $this->m->getPerawat();
        include __DIR__ . '/../../views/Master_Transaksi/riwayat_kesehatan/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=riwayat_kesehatan');
            exit;
        }
        $data = $this->m->getById($id);
        $h = $this->m->getHewan();
        $v = $this->m->getVaksin();
        $p = $this->m->getPerawat();
        include __DIR__ . '/../../views/Master_Transaksi/riwayat_kesehatan/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=riwayat_kesehatan');
        exit;
    }
}
?>