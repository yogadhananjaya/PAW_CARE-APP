<?php
require_once __DIR__ . '/../models/DonasiModel.php';
require_once __DIR__ . '/../models/PengadopsiModel.php';

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
        $error_validation = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nominal = (float)($_POST['nominal'] ?? 0);
            $nama_donatur = trim($_POST['nama_donatur'] ?? '');
            $tanggal = $_POST['tanggal'] ?? '';

            if ($nominal <= 0) {
                $error_validation = "Nominal donasi harus lebih besar dari 0!";
            } elseif (empty($nama_donatur)) {
                $error_validation = "Nama donatur / instansi tidak boleh kosong!";
            } elseif (empty($tanggal)) {
                $error_validation = "Tanggal transaksi tidak boleh kosong!";
            } else {
                $this->m->insert($_POST);
                header('Location: index.php?page=donasi');
                exit;
            }
        }
        include __DIR__ . '/../../views/Master_Data/donasi/create.php';
    }

    public function edit($id) {
        $error_validation = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nominal = (float)($_POST['nominal'] ?? 0);
            $nama_donatur = trim($_POST['nama_donatur'] ?? '');
            $tanggal = $_POST['tanggal'] ?? '';

            if ($nominal <= 0) {
                $error_validation = "Nominal donasi harus lebih besar dari 0!";
            } elseif (empty($nama_donatur)) {
                $error_validation = "Nama donatur / instansi tidak boleh kosong!";
            } elseif (empty($tanggal)) {
                $error_validation = "Tanggal transaksi tidak boleh kosong!";
            } else {
                $this->m->update($id, $_POST);
                header('Location: index.php?page=donasi');
                exit;
            }
        }
        $data = $this->m->getById($id);
        include __DIR__ . '/../../views/Master_Data/donasi/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=donasi');
        exit;
    }

    // Aksi untuk menyetujui donasi  
    public function confirm($id) {
        $this->m->updateStatus($id, 'Dikonfirmasi');
        header('Location: index.php?page=donasi');
        exit;
    }

    // Aksi untuk menolak donasi  
    public function reject($id) {
        $this->m->updateStatus($id, 'Ditolak');
        header('Location: index.php?page=donasi');
        exit;
    }
}
?>