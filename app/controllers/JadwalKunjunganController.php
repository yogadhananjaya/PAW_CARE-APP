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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST);
            header('Location: index.php?page=jadwal_kunjungan');
            exit;
        }
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        $p = $this->m->getPengguna();
        include __DIR__ . '/../../views/Master_Transaksi/jadwal_kunjungan/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=jadwal_kunjungan');
            exit;
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
}
?>