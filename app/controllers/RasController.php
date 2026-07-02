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
            if ($this->m->isDuplicate($nama_ras, $id_jenis)) {
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
            $nama_ras = trim($_POST['nama_ras']);
            $id_jenis = $_POST['id_jenis'];
            if ($this->m->isDuplicate($nama_ras, $id_jenis, $id)) {
                $error_duplikat = "Ras '{$nama_ras}' sudah terdaftar dalam jenis hewan yang dipilih!";
            } else {
                $this->m->update($id, $id_jenis, $nama_ras);
                header('Location: index.php?page=ras');
                exit;
            }
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