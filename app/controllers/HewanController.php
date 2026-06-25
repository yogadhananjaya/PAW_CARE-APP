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
            // Proses upload url_foto_hewan (kolom baru)
            $foto_name = null;
            if (isset($_FILES['url_foto_hewan']) && $_FILES['url_foto_hewan']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['url_foto_hewan']['name'], PATHINFO_EXTENSION);
                $foto_name = 'hewan_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/../../assets/img/hewan/' . $foto_name;
                move_uploaded_file($_FILES['url_foto_hewan']['tmp_name'], $target_dir);
            }

            // Buat payload sesuai kolom DB baru
            $payload = [
                'id_jenis'          => $_POST['id_jenis'],
                'id_ras'            => $_POST['id_ras'],
                'nama_hewan'        => trim($_POST['nama_hewan']),
                'jenis_kelamin'     => $_POST['jenis_kelamin'],
                'estimasi_umur'     => (int)$_POST['estimasi_umur'],
                'tanggal_lahir'     => !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null,
                'status_adopsi'     => $_POST['status_adopsi'],
                'sumber_intake'     => $_POST['sumber_intake'],
                'nama_donatur'      => trim($_POST['nama_donatur'] ?? ''),
                'kontak_donatur'    => trim($_POST['kontak_donatur'] ?? ''),
                'tanggal_intake'    => $_POST['tanggal_intake'],
                'keterangan_intake' => trim($_POST['keterangan_intake'] ?? ''),
                'url_foto_hewan'    => $foto_name,
                'deskripsi'         => trim($_POST['deskripsi'] ?? '')
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
            // Gunakan url_foto_hewan lama jika tidak ada upload baru
            $foto_name = $hewan['url_foto_hewan'];
            if (isset($_FILES['url_foto_hewan']) && $_FILES['url_foto_hewan']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['url_foto_hewan']['name'], PATHINFO_EXTENSION);
                $foto_name = 'hewan_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/../../assets/img/hewan/' . $foto_name;
                
                // Hapus foto lama jika ada
                if (!empty($hewan['url_foto_hewan']) && file_exists(__DIR__ . '/../../assets/img/hewan/' . $hewan['url_foto_hewan'])) {
                    unlink(__DIR__ . '/../../assets/img/hewan/' . $hewan['url_foto_hewan']);
                }
                move_uploaded_file($_FILES['url_foto_hewan']['tmp_name'], $target_dir);
            }

            $payload = [
                'id_jenis'          => $_POST['id_jenis'],
                'id_ras'            => $_POST['id_ras'],
                'nama_hewan'        => trim($_POST['nama_hewan']),
                'jenis_kelamin'     => $_POST['jenis_kelamin'],
                'estimasi_umur'     => (int)$_POST['estimasi_umur'],
                'tanggal_lahir'     => !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null,
                'status_adopsi'     => $_POST['status_adopsi'],
                'sumber_intake'     => $_POST['sumber_intake'],
                'nama_donatur'      => trim($_POST['nama_donatur'] ?? ''),
                'kontak_donatur'    => trim($_POST['kontak_donatur'] ?? ''),
                'tanggal_intake'    => $_POST['tanggal_intake'],
                'keterangan_intake' => trim($_POST['keterangan_intake'] ?? ''),
                'url_foto_hewan'    => $foto_name,
                'deskripsi'         => trim($_POST['deskripsi'] ?? '')
            ];

            $this->model->update($id, $payload);
            header('Location: index.php?page=hewan');
            exit;
        }

        include __DIR__ . '/../../views/Master_Data/hewan/edit.php';
    }

    public function delete($id) {
        $hewan = $this->model->getById($id);
        // Hapus file url_foto_hewan jika ada
        if ($hewan && !empty($hewan['url_foto_hewan']) && file_exists(__DIR__ . '/../../assets/img/hewan/' . $hewan['url_foto_hewan'])) {
            unlink(__DIR__ . '/../../assets/img/hewan/' . $hewan['url_foto_hewan']);
        }
        $this->model->delete($id);
        header('Location: index.php?page=hewan');
        exit;
    }

    public function intake() {
        // Tangani submit form intake
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $foto_name = null;
            if (isset($_FILES['url_foto_hewan']) && $_FILES['url_foto_hewan']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['url_foto_hewan']['name'], PATHINFO_EXTENSION);
                $foto_name = 'hewan_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/../../assets/img/hewan/' . $foto_name;
                move_uploaded_file($_FILES['url_foto_hewan']['tmp_name'], $target_dir);
            }

            $payload = [
                'id_jenis'          => $_POST['id_jenis'],
                'id_ras'            => $_POST['id_ras'],
                'nama_hewan'        => trim($_POST['nama_hewan']),
                'jenis_kelamin'     => $_POST['jenis_kelamin'],
                'estimasi_umur'     => (int)$_POST['estimasi_umur'],
                'tanggal_lahir'     => null,
                'status_adopsi'     => 'Karantina',
                'sumber_intake'     => $_POST['sumber_intake'],
                'nama_donatur'      => trim($_POST['nama_donatur'] ?? ''),
                'kontak_donatur'    => trim($_POST['kontak_donatur'] ?? ''),
                'tanggal_intake'    => $_POST['tanggal_intake'],
                'keterangan_intake' => trim($_POST['keterangan_intake'] ?? ''),
                'url_foto_hewan'    => $foto_name,
                'deskripsi'         => trim($_POST['deskripsi'] ?? '')
            ];

            $this->model->insert($payload);
            header('Location: index.php?page=intake_hewan&success=1');
            exit;
        }

        // Tampilkan form intake + daftar inventaris
        $jenis_list = $this->model->getOpsiJenis();
        $ras_list = $this->model->getOpsiRas();
        $recentHewan = $this->model->getAll();
        include __DIR__ . '/../../views/Master_Transaksi/intake_hewan/index.php';
    }
}
?>