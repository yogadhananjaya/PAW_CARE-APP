<?php
require_once __DIR__ . '/../models/PengadopsiModel.php';

class PengadopsiController {
    private $m;

    public function __construct() { 
        $this->m = new PengadopsiModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Data/pengadopsi/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST);
            header('Location: index.php?page=pengadopsi');
            exit;
        }
        $users = $this->m->getOpsiPengguna();
        include __DIR__ . '/../../views/Master_Data/pengadopsi/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=pengadopsi');
            exit;
        }
        $data = $this->m->getById($id);
        $users = $this->m->getOpsiPengguna();
        include __DIR__ . '/../../views/Master_Data/pengadopsi/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=pengadopsi');
        exit;
    }
}
?>