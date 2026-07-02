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
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_vaksin = empty($_POST['id_vaksin']) ? null : $_POST['id_vaksin'];
            if ($_POST['tipe'] === 'Vaksinasi' && $this->m->perluPerawatanDulu($_POST['id_hewan'])) {
                $error_duplikat = "Hewan ini belum memiliki catatan Perawatan! Lakukan pemeriksaan umum (Perawatan) terlebih dahulu sebelum mencatat Vaksinasi.";
            } elseif ($_POST['tipe'] === 'Karantina Selesai' && $this->m->perluVaksinasiDulu($_POST['id_hewan'])) {
                $error_duplikat = "Hewan ini belum memiliki catatan Vaksinasi! Lakukan Vaksinasi terlebih dahulu sebelum menyelesaikan Karantina.";
            } elseif ($this->m->isDuplicate($_POST['id_hewan'], $_POST['tipe'], $id_vaksin, $_POST['tanggal'])) {
                $error_duplikat = "Rekam medis duplikat! Hewan ini sudah memiliki catatan " . ($_POST['tipe'] === 'Vaksinasi' ? 'vaksinasi dengan vaksin yang sama' : ($_POST['tipe'] === 'Karantina Selesai' ? 'Karantina Selesai' : 'perawatan')) . " pada tanggal tersebut.";
            } else {
                $this->m->insert($_POST);
                // Selesaikan Perawatan jika ini Vaksinasi lanjutan
                $dari = intval($_POST['dari_sebelumnya'] ?? 0);
                if ($dari > 0) {
                    $this->m->delete($dari);
                }
                // Karantina Selesai: auto rilis hewan ke Tersedia + rekomendasi
                if ($_POST['tipe'] === 'Karantina Selesai') {
                    $this->m->rilisKarantina($_POST['id_hewan']);
                }
                header('Location: index.php?page=riwayat_kesehatan');
                exit;
            }
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

        // Tolak jika bukan pemilik (PIC) catatan ini
        if (!$record || !$this->m->canModify($record, $user_id, $role)) {
            header('Location: index.php?page=riwayat_kesehatan&alert=locked');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_vaksin = empty($_POST['id_vaksin']) ? null : $_POST['id_vaksin'];
            if ($_POST['tipe'] === 'Vaksinasi' && $this->m->perluPerawatanDulu($_POST['id_hewan'])) {
                $error_duplikat = "Hewan ini belum memiliki catatan Perawatan! Lakukan pemeriksaan umum (Perawatan) terlebih dahulu sebelum mencatat Vaksinasi.";
            } elseif ($this->m->isDuplicate($_POST['id_hewan'], $_POST['tipe'], $id_vaksin, $_POST['tanggal'], $id)) {
                $error_duplikat = "Rekam medis duplikat! Hewan ini sudah memiliki catatan " . ($_POST['tipe'] === 'Vaksinasi' ? 'vaksinasi dengan vaksin yang sama' : 'perawatan') . " pada tanggal tersebut.";
            } else {
                $this->m->update($id, $_POST);
                header('Location: index.php?page=riwayat_kesehatan');
                exit;
            }
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

        // Tolak penghapusan jika bukan pemilik (PIC) catatan ini
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