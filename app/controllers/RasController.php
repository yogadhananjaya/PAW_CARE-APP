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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST['id_jenis'], $_POST['nama_ras']);
            header('Location: index.php?page=ras');
            exit;
        }
        $jenis = $this->m->getOpsiJenis();
        include __DIR__ . '/../../views/Master_Data/ras/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST['id_jenis'], $_POST['nama_ras']);
            header('Location: index.php?page=ras');
            exit;
        }
        $data = $this->m->getById($id);
        $jenis = $this->m->getOpsiJenis();
        include __DIR__ . '/../../views/Master_Data/ras/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=ras');
        exit;
    }
}
?>