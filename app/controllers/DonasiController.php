<?php
require_once __DIR__ . '/../models/DonasiModel.php';

class DonasiController {
    private $m;

    public function __construct() { 
        $this->m = new DonasiModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Data/donasi/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST);
            header('Location: index.php?page=donasi');
            exit;
        }
        include __DIR__ . '/../../views/Master_Data/donasi/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=donasi');
            exit;
        }
        $data = $this->m->getById($id);
        include __DIR__ . '/../../views/Master_Data/donasi/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=donasi');
        exit;
    }
}
?>