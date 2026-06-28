<?php
require_once __DIR__ . '/../models/RiwayatKesehatanModel.php';

class RiwayatKesehatanController {
    private $m;

    public function __construct() { 
        $this->m = new RiwayatKesehatanModel(); 
    }

    public function index() {
        $data         = $this->m->getAll();
        $deleted_data = $this->m->getDeleted();
        include __DIR__ . '/../../views/Master_Transaksi/riwayat_kesehatan/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST);
            header('Location: index.php?page=riwayat_kesehatan');
            exit;
        }
        $h = $this->m->getHewan();
        $v = $this->m->getVaksin();
        $p = $this->m->getPerawat();
        include __DIR__ . '/../../views/Master_Transaksi/riwayat_kesehatan/create.php';
    }

    public function edit($id) {
        $record = $this->m->getById($id);
        $user_id = $_SESSION['user_id'] ?? 0;
        $role    = $_SESSION['role'] ?? '';

        // ponytail: Tolak jika tidak memiliki hak akses (bukan pemilik atau lebih dari 24 jam)
        if (!$record || !$this->m->canModify($record, $user_id, $role)) {
            header('Location: index.php?page=riwayat_kesehatan&alert=locked');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=riwayat_kesehatan');
            exit;
        }
        $data = $record;
        $h = $this->m->getHewan();
        $v = $this->m->getVaksin();
        $p = $this->m->getPerawat();
        include __DIR__ . '/../../views/Master_Transaksi/riwayat_kesehatan/edit.php';
    }

    public function delete($id) {
        $record  = $this->m->getById($id);
        $user_id = $_SESSION['user_id'] ?? 0;
        $role    = $_SESSION['role'] ?? '';

        // ponytail: Tolak penghapusan jika tidak memiliki hak akses
        if (!$record || !$this->m->canModify($record, $user_id, $role)) {
            header('Location: index.php?page=riwayat_kesehatan&alert=locked');
            exit;
        }

        $this->m->delete($id);
        header('Location: index.php?page=riwayat_kesehatan');
        exit;
    }
}
?>