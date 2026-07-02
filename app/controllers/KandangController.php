<?php
require_once __DIR__ . '/../models/KandangModel.php';
require_once __DIR__ . '/../models/JenisModel.php';

class KandangController {
    private $m;

    public function __construct() { 
        $this->m = new KandangModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Data/kandang/index.php';
    }

    public function create() {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->m->isDuplicate($_POST['kode_kandang'], $_POST['nama_kandang'])) {
                $error_duplikat = "Kode kandang atau nama kandang tersebut sudah terdaftar!";
            } else {
                $this->m->insert($_POST);
                header('Location: index.php?page=kandang');
                exit;
            }
        }
        $nextId = $this->m->getNextId();
        $auto_kode = "KND-" . str_pad($nextId, 3, "0", STR_PAD_LEFT);
        $jenisModel = new JenisModel();
        $jenis_list = $jenisModel->getAll();
        include __DIR__ . '/../../views/Master_Data/kandang/create.php';
    }

    public function edit($id) {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->m->isDuplicate($_POST['kode_kandang'], $_POST['nama_kandang'], $id)) {
                $error_duplikat = "Kode kandang atau nama kandang tersebut sudah terdaftar!";
            } else {
                $this->m->update($id, $_POST);
                header('Location: index.php?page=kandang');
                exit;
            }
        }
        $data = $this->m->getById($id);
        $jenisModel = new JenisModel();
        $jenis_list = $jenisModel->getAll();
        include __DIR__ . '/../../views/Master_Data/kandang/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=kandang');
        exit;
    }
}
?>