<?php
require_once __DIR__ . '/../models/TransaksiAdopsiModel.php';

class TransaksiAdopsiController {
    private $m;

    public function __construct() { 
        $this->m = new TransaksiAdopsiModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Set penanggung jawab otomatis ke admin yang sedang login jika tidak dikirim
            if (empty($_POST['id_pengguna'])) {
                $_POST['id_pengguna'] = $_SESSION['user_id'] ?? null;
            }
            // ponytail: Tolak duplikat adopter + hewan yang masih aktif
            if ($this->m->isDuplicate($_POST['id_hewan'], $_POST['id_pengadopsi'])) {
                $data = $this->m->getAll();
                $a = $this->m->getPengadopsi();
                $h = $this->m->getHewan();
                $pg = $this->m->getPengguna();
                $error_duplikat = "Adopter ini sudah memiliki transaksi adopsi aktif (Draft/Aktif) untuk hewan yang sama!";
                include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/index.php';
                exit;
            }
            $this->m->insert($_POST);
            header('Location: index.php?page=transaksi_adopsi');
            exit;
        }
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        $pg = $this->m->getPengguna();
        include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ponytail: Tolak duplikat saat edit (kecuali record sendiri)
            if ($this->m->isDuplicate($_POST['id_hewan'], $_POST['id_pengadopsi'], $id)) {
                $data = $this->m->getById($id);
                $a = $this->m->getPengadopsi();
                $h = $this->m->getHewan();
                $pg = $this->m->getPengguna();
                $error_duplikat = "Adopter ini sudah memiliki transaksi adopsi aktif untuk hewan yang sama!";
                include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/edit.php';
                exit;
            }
            $this->m->update($id, $_POST);
            header('Location: index.php?page=transaksi_adopsi');
            exit;
        }
        $data = $this->m->getById($id);
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        $pg = $this->m->getPengguna();
        include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/edit.php';
    }

    public function delete($id) {
        header('Location: index.php?page=transaksi_adopsi');
        exit;
    }

    // ponytail: Batalkan/Tolak kontrak adopsi langsung
    public function reject($id) {
        $this->m->setStatus($id, 'Batal');
        header('Location: index.php?page=transaksi_adopsi');
        exit;
    }

    public function activate($id) {
        $this->m->activate($id);
        header('Location: index.php?page=transaksi_adopsi_edit&id=' . $id);
        exit;
    }

    // ponytail: Proses tanda tangan digital admin penanggung jawab
    public function sign($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ttd_base64 = $_POST['ttd_admin'] ?? '';
            if (!empty($ttd_base64)) {
                $this->m->saveAdminSignature($id, $ttd_base64);
                header("Location: index.php?page=transaksi_adopsi_edit&id=$id&signed_success=1");
                exit;
            }
        }
        header("Location: index.php?page=transaksi_adopsi_edit&id=$id");
        exit;
    }
}
?>