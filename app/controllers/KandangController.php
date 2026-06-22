<?php
require_once __DIR__ . '/../models/KandangModel.php';

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST);
            header('Location: index.php?page=kandang');
            exit;
        }
        // Membuat kode kandang otomatis, contoh: KND-001, KND-002, dst.
        $nextId = $this->m->getNextId();
        $auto_kode = "KND-" . str_pad($nextId, 3, "0", STR_PAD_LEFT);
        
        include __DIR__ . '/../../views/Master_Data/kandang/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=kandang');
            exit;
        }
        $data = $this->m->getById($id);
        include __DIR__ . '/../../views/Master_Data/kandang/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=kandang');
        exit;
    }
}
?>