<?php
require_once __DIR__ . '/../models/VaksinModel.php';

class VaksinController {
    private $m;

    public function __construct() { 
        $this->m = new VaksinModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Data/vaksin/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST['nama_vaksin'], $_POST['deskripsi']);
            header('Location: index.php?page=vaksin');
            exit;
        }
        include __DIR__ . '/../../views/Master_Data/vaksin/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST['nama_vaksin'], $_POST['deskripsi']);
            header('Location: index.php?page=vaksin');
            exit;
        }
        $data = $this->m->getById($id);
        include __DIR__ . '/../../views/Master_Data/vaksin/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=vaksin');
        exit;
    }
}
?>