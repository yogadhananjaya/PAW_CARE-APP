<?php
require_once __DIR__ . '/../models/HewanModel.php';

class HewanController {
    private $model;

    public function __construct() {
        $this->model = new HewanModel();
    }

    public function index() {
        $data = $this->model->getAll();
        include __DIR__ . '/../../views/Master_Data/hewan/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $foto_name = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto_name = 'hewan_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/../../assets/img/hewan/' . $foto_name;
                move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir);
            }

            $payload = [
                'id_jenis' => $_POST['id_jenis'],
                'id_ras' => $_POST['id_ras'],
                'nama_hewan' => trim($_POST['nama_hewan']),
                'jenis_kelamin' => $_POST['jenis_kelamin'],
                'umur' => trim($_POST['umur']),
                'status' => $_POST['status'],
                'foto' => $foto_name,
                'deskripsi' => trim($_POST['deskripsi'])
            ];

            $this->model->insert($payload);
            header('Location: index.php?page=hewan');
            exit;
        }

        $jenis_list = $this->model->getOpsiJenis();
        $ras_list = $this->model->getOpsiRas();
        include __DIR__ . '/../../views/Master_Data/hewan/create.php';
    }

    public function edit($id) {
        $hewan = $this->model->getById($id);
        $jenis_list = $this->model->getOpsiJenis(); 
        $ras_list = $this->model->getOpsiRas();
        if (!$hewan) { header('Location: index.php?page=hewan'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $foto_name = $hewan['foto']; 
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto_name = 'hewan_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/../../assets/img/hewan/' . $foto_name;
                
                if (!empty($hewan['foto']) && file_exists(__DIR__ . '/../../assets/img/hewan/' . $hewan['foto'])) {
                    unlink(__DIR__ . '/../../assets/img/hewan/' . $hewan['foto']);
                }
                move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir);
            }

            $payload = [
                'id_jenis' => $_POST['id_jenis'],
                'id_ras' => $_POST['id_ras'],
                'nama_hewan' => trim($_POST['nama_hewan']),
                'jenis_kelamin' => $_POST['jenis_kelamin'],
                'umur' => trim($_POST['umur']),
                'status' => $_POST['status'],
                'foto' => $foto_name,
                'deskripsi' => trim($_POST['deskripsi'])
            ];

            $this->model->update($id, $payload);
            header('Location: index.php?page=hewan');
            exit;
        }

        $jenis_list = $this->model->getOpsiJenis();
        $ras_list = $this->model->getOpsiRas();
        include __DIR__ . '/../../views/Master_Data/hewan/edit.php';
    }

    public function delete($id) {
        $hewan = $this->model->getById($id);
        if ($hewan && !empty($hewan['foto']) && file_exists(__DIR__ . '/../../assets/img/hewan/' . $hewan['foto'])) {
            unlink(__DIR__ . '/../../assets/img/hewan/' . $hewan['foto']);
        }
        $this->model->delete($id);
        header('Location: index.php?page=hewan');
        exit;
    }
}
?>