<?php
require_once __DIR__ . '/../models/JenisModel.php';

class JenisController {
    private $model;

    public function __construct() {
        $this->model = new JenisModel();
    }

    public function index() {
        $data = $this->model->getAll();
        include __DIR__ . '/../../views/Master_Data/jenis/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_jenis = trim($_POST['nama_jenis']);
            if (!empty($nama_jenis)) {
                $this->model->insert($nama_jenis);
            }
            header('Location: index.php?page=jenis');
            exit;
        }
        include __DIR__ . '/../../views/Master_Data/jenis/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_jenis = trim($_POST['nama_jenis']);
            if (!empty($nama_jenis)) {
                $this->model->update($id, $nama_jenis);
            }
            header('Location: index.php?page=jenis');
            exit;
        }
        $data = $this->model->getById($id);
        include __DIR__ . '/../../views/Master_Data/jenis/edit.php';
    }

    public function delete($id) {
        $this->model->delete($id);
        header('Location: index.php?page=jenis');
        exit;
    }
}
?>