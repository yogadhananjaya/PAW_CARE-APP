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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST);
            header('Location: index.php?page=pengguna');
            exit;
        }
        include __DIR__ . '/../../views/Master_Data/pengguna/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=pengguna');
            exit;
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