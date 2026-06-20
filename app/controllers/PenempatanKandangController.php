<?php
require_once __DIR__ . '/../models/PenempatanKandangModel.php';

class PenempatanKandangController {
    private $m;

    public function __construct() { 
        $this->m = new PenempatanKandangModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Transaksi/penempatan_kandang/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST);
            header('Location: index.php?page=penempatan_kandang');
            exit;
        }
        $h = $this->m->getHewan();
        $k = $this->m->getKandang();
        include __DIR__ . '/../../views/Master_Transaksi/penempatan_kandang/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=penempatan_kandang');
            exit;
        }
        $data = $this->m->getById($id);
        $h = $this->m->getHewan();
        $k = $this->m->getKandang();
        include __DIR__ . '/../../views/Master_Transaksi/penempatan_kandang/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=penempatan_kandang');
        exit;
    }
}
?>