<?php
require_once __DIR__ . '/../models/JadwalKunjunganModel.php';

class JadwalKunjunganController {
    private $m;

    public function __construct() { 
        $this->m = new JadwalKunjunganModel(); 
    }

    public function index() {
        $all = $this->m->getAll();
        $pending   = array_filter($all, fn($r) => $r['status_jadwal'] === 'Menunggu');
        $confirmed = array_filter($all, fn($r) => $r['status_jadwal'] === 'Dikonfirmasi');
        $history   = array_filter($all, fn($r) => in_array($r['status_jadwal'], ['Selesai', 'Batal']));
        include __DIR__ . '/../../views/Master_Transaksi/jadwal_kunjungan/index.php';
    }

    public function create() {
        $error_validation = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pengadopsi = $_POST['id_pengadopsi'] ?? '';
            $id_hewan = $_POST['id_hewan'] ?? '';
            $tanggal_jadwal = $_POST['tanggal_jadwal'] ?? '';

            if (empty($id_pengadopsi)) {
                $error_validation = "Wajib memilih pengadopsi!";
            } elseif (empty($id_hewan)) {
                $error_validation = "Wajib memilih hewan!";
            } elseif (empty($tanggal_jadwal)) {
                $error_validation = "Wajib menentukan tanggal dan jam kunjungan!";
            } elseif (date('Y-m-d', strtotime($tanggal_jadwal)) < date('Y-m-d')) {
                $error_validation = "Tanggal kunjungan tidak boleh di masa lampau!";
            } elseif ($this->m->isDuplicate($id_pengadopsi, $id_hewan)) {
                $error_validation = "Pengadopsi ini sudah memiliki jadwal kunjungan aktif untuk hewan tersebut!";
            } else {
                $this->m->insert($_POST);
                header('Location: index.php?page=jadwal_kunjungan');
                exit;
            }
        }
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        $p = $this->m->getPengguna();
        include __DIR__ . '/../../views/Master_Transaksi/jadwal_kunjungan/create.php';
    }

    public function edit($id) {
        $error_validation = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pengadopsi = $_POST['id_pengadopsi'] ?? '';
            $id_hewan = $_POST['id_hewan'] ?? '';
            $tanggal_jadwal = $_POST['tanggal_jadwal'] ?? '';

            if (empty($id_pengadopsi)) {
                $error_validation = "Wajib memilih pengadopsi!";
            } elseif (empty($id_hewan)) {
                $error_validation = "Wajib memilih hewan!";
            } elseif (empty($tanggal_jadwal)) {
                $error_validation = "Wajib menentukan tanggal dan jam kunjungan!";
            } elseif (date('Y-m-d', strtotime($tanggal_jadwal)) < date('Y-m-d')) {
                $error_validation = "Tanggal kunjungan tidak boleh di masa lampau!";
            } elseif ($this->m->isDuplicate($id_pengadopsi, $id_hewan, $id)) {
                $error_validation = "Pengadopsi ini sudah memiliki jadwal kunjungan aktif untuk hewan tersebut!";
            } else {
                $this->m->update($id, $_POST);
                header('Location: index.php?page=jadwal_kunjungan');
                exit;
            }
        }
        $data = $this->m->getById($id);
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        $p = $this->m->getPengguna();
        include __DIR__ . '/../../views/Master_Transaksi/jadwal_kunjungan/edit.php';
    }

    public function delete($id) {
        $this->m->delete($id);
        header('Location: index.php?page=jadwal_kunjungan');
        exit;
    }

    //  Konfirmasi jadwal langsung tanpa buka form edit
    public function confirm($id) {
        $this->m->setStatus($id, 'Dikonfirmasi');
        header('Location: index.php?page=jadwal_kunjungan&confirmed=1');
        exit;
    }

    //  Selesaikan jadwal pertemuan langsung tanpa buka form edit
    public function complete($id) {
        $this->m->setStatus($id, 'Selesai');
        header('Location: index.php?page=jadwal_kunjungan&success_complete=1');
        exit;
    }
    //  Batalkan/Tolak jadwal kunjungan langsung
    public function reject($id) {
        $this->m->setStatus($id, 'Batal');
        header('Location: index.php?page=jadwal_kunjungan&cancelled=1');
        exit;
    }
}
?>