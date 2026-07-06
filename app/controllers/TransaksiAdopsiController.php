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
        $coordinators = $this->m->getCoordinators();
        include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/index.php';
    }

    public function create() {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pengadopsi = $_POST['id_pengadopsi'] ?? '';
            $id_hewan = $_POST['id_hewan'] ?? '';
            $tanggal_adopsi = $_POST['tanggal_adopsi'] ?? '';

            if (empty($id_pengadopsi)) {
                $error_duplikat = "Wajib memilih pengadopsi!";
            } elseif (empty($id_hewan)) {
                $error_duplikat = "Wajib memilih hewan!";
            } elseif (empty($tanggal_adopsi)) {
                $error_duplikat = "Wajib menentukan tanggal adopsi!";
            } elseif ($tanggal_adopsi > date('Y-m-d')) {
                $error_duplikat = "Tanggal adopsi tidak boleh melebihi hari ini!";
            } else {
                // Set penanggung jawab otomatis ke Koordinator jika belum diset
                if (empty($_POST['id_pengguna'])) {
                    if (($_SESSION['jabatan'] ?? '') === 'Koordinator' || ($_SESSION['role'] ?? '') === 'Koordinator') {
                        $_POST['id_pengguna'] = $_SESSION['user_id'];
                    } else {
                        $_POST['id_pengguna'] = $this->m->getFirstKoordinatorId() ?: null;
                    }
                }
                //  Tolak duplikat adopter + hewan yang masih aktif
                if ($this->m->isDuplicate($id_hewan, $id_pengadopsi)) {
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
        }
        $a = $this->m->getPengadopsi();
        $h = $this->m->getHewan();
        $pg = $this->m->getPengguna();
        include __DIR__ . '/../../views/Master_Transaksi/transaksi_adopsi/create.php';
    }

    public function edit($id) {
        $error_duplikat = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pengadopsi = $_POST['id_pengadopsi'] ?? '';
            $id_hewan = $_POST['id_hewan'] ?? '';
            $tanggal_adopsi = $_POST['tanggal_adopsi'] ?? '';

            if (empty($id_pengadopsi)) {
                $error_duplikat = "Wajib memilih pengadopsi!";
            } elseif (empty($id_hewan)) {
                $error_duplikat = "Wajib memilih hewan!";
            } elseif (empty($tanggal_adopsi)) {
                $error_duplikat = "Wajib menentukan tanggal adopsi!";
            } elseif ($tanggal_adopsi > date('Y-m-d')) {
                $error_duplikat = "Tanggal adopsi tidak boleh melebihi hari ini!";
            } else {
                //  Tolak duplikat saat edit (kecuali record sendiri)
                if ($this->m->isDuplicate($id_hewan, $id_pengadopsi, $id)) {
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

    //  Batalkan/Tolak kontrak adopsi langsung
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

    //  Proses tanda tangan digital admin penanggung jawab (Hanya Koordinator yang ditunjuk)
    public function sign($id) {
        if (($_SESSION['role'] ?? '') !== 'Koordinator') {
            header("Location: index.php?page=transaksi_adopsi_edit&id=$id&error=only_koordinator_can_sign");
            exit;
        }
        $data = $this->m->getById($id);
        // Jika sudah ada penanggung jawab, hanya dia yang boleh tanda tangan
        if (!empty($data['id_pengguna']) && $data['id_pengguna'] != $_SESSION['user_id']) {
            header("Location: index.php?page=transaksi_adopsi_edit&id=$id&error=not_assigned_koordinator");
            exit;
        }
        // Jadwal kunjungan harus sudah Selesai dulu
        $jk_status = $data['status_jadwal'] ?? '';
        if ($jk_status !== 'Selesai') {
            header("Location: index.php?page=transaksi_adopsi_edit&id=$id&error=jadwal_belum_selesai");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ttd_base64 = $_POST['ttd_admin'] ?? '';
            if (!empty($ttd_base64)) {
                // Auto-assign koordinator jika belum ada penanggung jawab
                if (empty($data['id_pengguna'])) {
                    $this->m->assignKoordinator($id, $_SESSION['user_id']);
                }
                $this->m->saveAdminSignature($id, $ttd_base64);
                // Setelah Koordinator tanda tangan, kontrak langsung AKTIF
                $this->m->activate($id);
                header("Location: index.php?page=transaksi_adopsi_edit&id=$id&signed_success=1");
                exit;
            }
        }
        header("Location: index.php?page=transaksi_adopsi_edit&id=$id");
        exit;
    }
}
?>