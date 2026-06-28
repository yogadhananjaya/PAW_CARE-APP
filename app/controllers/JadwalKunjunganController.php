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
            // ponytail: tolak jika adopter + hewan sudah punya jadwal aktif
            if ($this->m->isDuplicate($_POST['id_pengadopsi'], $_POST['id_hewan'])) {
                header('Location: index.php?page=jadwal_kunjungan_create&error=duplicate');
                exit;
            }
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
            // ponytail: tolak jika adopter + hewan sudah punya jadwal aktif di record LAIN
            if ($this->m->isDuplicate($_POST['id_pengadopsi'], $_POST['id_hewan'], $id)) {
                header('Location: index.php?page=jadwal_kunjungan_edit&id=' . $id . '&error=duplicate');
                exit;
            }
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

    // ponytail: Konfirmasi jadwal langsung tanpa buka form edit
    public function confirm($id) {
        $this->m->setStatus($id, 'Dikonfirmasi');
        header('Location: index.php?page=jadwal_kunjungan&confirmed=1');
        exit;
    }

    // ponytail: Selesaikan jadwal pertemuan langsung tanpa buka form edit
    public function complete($id) {
        $this->m->setStatus($id, 'Selesai');
        header('Location: index.php?page=jadwal_kunjungan&success_complete=1');
        exit;
    }
    // ponytail: Batalkan/Tolak jadwal kunjungan langsung
    public function reject($id) {
        $this->m->setStatus($id, 'Batal');
        header('Location: index.php?page=jadwal_kunjungan&cancelled=1');
        exit;
    }
}
?>