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
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_jenis = trim($_POST['nama_jenis']);
            if ($nama_jenis === '') {
                $error_duplikat = "Nama jenis hewan tidak boleh kosong!";
            } elseif (strlen($nama_jenis) > 50) {
                $error_duplikat = "Nama jenis hewan tidak boleh lebih dari 50 karakter!";
            } elseif ($this->model->isDuplicate($nama_jenis)) {
                $error_duplikat = "Jenis hewan '{$nama_jenis}' sudah terdaftar!";
            } else {
                $this->model->insert($nama_jenis);
                header('Location: index.php?page=jenis');
                exit;
            }
        }
        include __DIR__ . '/../../views/Master_Data/jenis/create.php';
    }

    public function edit($id) {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_jenis = trim($_POST['nama_jenis']);
            if ($nama_jenis === '') {
                $error_duplikat = "Nama jenis hewan tidak boleh kosong!";
            } elseif (strlen($nama_jenis) > 50) {
                $error_duplikat = "Nama jenis hewan tidak boleh lebih dari 50 karakter!";
            } elseif ($this->model->isDuplicate($nama_jenis, $id)) {
                $error_duplikat = "Jenis hewan '{$nama_jenis}' sudah terdaftar!";
            } else {
                $this->model->update($id, $nama_jenis);
                header('Location: index.php?page=jenis');
                exit;
            }
        }
        $data = $this->model->getById($id);
        include __DIR__ . '/../../views/Master_Data/jenis/edit.php';
    }

    public function delete($id) {
        if ($this->model->isUsed($id)) {
            header('Location: index.php?page=jenis&error_delete=1');
            exit;
        }
        $this->model->delete($id);
        header('Location: index.php?page=jenis');
        exit;
    }
}
?>