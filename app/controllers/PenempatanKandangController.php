<?php
require_once __DIR__ . '/../models/PenempatanKandangModel.php';

class PenempatanKandangController
{
    private $m;

    public function __construct()
    {
        $this->m = new PenempatanKandangModel();
    }

    public function index()
    {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Transaksi/penempatan_kandang/index.php';
    }

    public function create()
    {
        $error_validation = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_hewan = $_POST['id_hewan'] ?? '';
            $id_kandang = $_POST['id_kandang'] ?? '';
            $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';

            // Ambil info kapasitas & terisi
            $kandangs = $this->m->getKandang();
            $target_kandang = null;
            foreach ($kandangs as $kd) {
                if ($kd['id_kandang'] == $id_kandang) {
                    $target_kandang = $kd;
                    break;
                }
            }

            if (empty($id_hewan)) {
                $error_validation = "Wajib memilih hewan!";
            } elseif (empty($id_kandang)) {
                $error_validation = "Wajib memilih kandang!";
            } elseif (empty($tanggal_masuk)) {
                $error_validation = "Wajib menentukan tanggal masuk!";
            } elseif ($tanggal_masuk > date('Y-m-d')) {
                $error_validation = "Tanggal masuk tidak boleh melebihi hari ini!";
            } elseif ($this->m->isAlreadyInCage($id_hewan, $id_kandang)) {
                $error_validation = "Hewan ini sudah aktif ditempatkan di kandang tersebut!";
            } elseif (!$this->m->checkJenisCocok($id_hewan, $id_kandang)) {
                $error_validation = "Jenis hewan dan jenis kandang tidak cocok (misal: kucing harus di kandang kucing)!";
            } elseif ($target_kandang && $target_kandang['terisi'] >= $target_kandang['kapasitas']) {
                $error_validation = "Kandang '" . htmlspecialchars($target_kandang['nama_kandang']) . "' sudah penuh (Kapasitas: " . $target_kandang['kapasitas'] . ")!";
            } else {
                $this->m->insert($_POST);
                header('Location: index.php?page=penempatan_kandang');
                exit;
            }
        }
        $h = $this->m->getHewan();
        $k = $this->m->getKandang();
        include __DIR__ . '/../../views/Master_Transaksi/penempatan_kandang/create.php';
    }

    public function edit($id)
    {
        $error_validation = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_hewan = $_POST['id_hewan'] ?? '';
            $id_kandang = $_POST['id_kandang'] ?? '';
            $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';

            // Ambil info kapasitas & terisi
            $kandangs = $this->m->getKandang();
            $target_kandang = null;
            foreach ($kandangs as $kd) {
                if ($kd['id_kandang'] == $id_kandang) {
                    $target_kandang = $kd;
                    break;
                }
            }

            // Dapatkan penempatan yang sekarang untuk bypass cek kapasitas jika kandang sama
            $current = $this->m->getById($id);

            if (empty($id_hewan)) {
                $error_validation = "Wajib memilih hewan!";
            } elseif (empty($id_kandang)) {
                $error_validation = "Wajib memilih kandang!";
            } elseif (empty($tanggal_masuk)) {
                $error_validation = "Wajib menentukan tanggal masuk!";
            } elseif ($tanggal_masuk > date('Y-m-d')) {
                $error_validation = "Tanggal masuk tidak boleh melebihi hari ini!";
            } elseif (!$this->m->checkJenisCocok($id_hewan, $id_kandang)) {
                $error_validation = "Jenis hewan dan jenis kandang tidak cocok (misal: kucing harus di kandang kucing)!";
            } elseif ($current && $current['id_kandang'] != $id_kandang && $target_kandang && $target_kandang['terisi'] >= $target_kandang['kapasitas']) {
                $error_validation = "Kandang '" . htmlspecialchars($target_kandang['nama_kandang']) . "' sudah penuh (Kapasitas: " . $target_kandang['kapasitas'] . ")!";
            } else {
                $this->m->update($id, $_POST);
                header('Location: index.php?page=penempatan_kandang');
                exit;
            }
        }
        $data = $this->m->getById($id);
        $h = $this->m->getHewan();
        $k = $this->m->getKandang();
        include __DIR__ . '/../../views/Master_Transaksi/penempatan_kandang/edit.php';
    }

    public function delete($id)
    {
        $this->m->delete($id);
        header('Location: index.php?page=penempatan_kandang');
        exit;
    }

    public function koordinator()
    {
        // Anti-cache: pastikan halaman selalu membaca data terbaru dari DB
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Tangani submit form penempatan
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = [
                'id_hewan' => $_POST['id_hewan'] ?? '',
                'id_kandang' => $_POST['id_kandang'] ?? '',
                'tanggal_masuk' => $_POST['tanggal_masuk'] ?? '',
                'tanggal_keluar' => null
            ];

            // Cek kapasitas kandang
            $kandangs = $this->m->getKandang();
            $target_kandang = null;
            foreach ($kandangs as $kd) {
                if ($kd['id_kandang'] == $payload['id_kandang']) {
                    $target_kandang = $kd;
                    break;
                }
            }

            if (empty($payload['id_hewan']) || empty($payload['id_kandang']) || empty($payload['tanggal_masuk'])) {
                header("Location: index.php?page=penempatan_kandang_koordinator&error=empty_fields");
                exit;
            }

            if ($payload['tanggal_masuk'] > date('Y-m-d')) {
                header("Location: index.php?page=penempatan_kandang_koordinator&error=future_date");
                exit;
            }

            //  Cegah jika hewan sudah berada di kandang yang sama
            if ($this->m->isAlreadyInCage($payload['id_hewan'], $payload['id_kandang'])) {
                header("Location: index.php?page=penempatan_kandang_koordinator&error=duplicate");
                exit;
            }

            //  Cegah jika jenis hewan tidak cocok dengan jenis kandang
            if (!$this->m->checkJenisCocok($payload['id_hewan'], $payload['id_kandang'])) {
                header("Location: index.php?page=penempatan_kandang_koordinator&error=jenis_tidak_cocok");
                exit;
            }

            // Cegah jika kandang sudah penuh
            if ($target_kandang && $target_kandang['terisi'] >= $target_kandang['kapasitas']) {
                header("Location: index.php?page=penempatan_kandang_koordinator&error=kandang_penuh");
                exit;
            }

            $this->m->insert($payload);

            // Jika checkbox "Jadikan Tersedia" dicentang, update status hewan
            if (isset($_POST['jadikan_tersedia']) && $_POST['jadikan_tersedia'] == '1') {
                global $pdo;
                $stmt = $pdo->prepare("UPDATE hewan SET status_adopsi = 'Tersedia' WHERE id_hewan = ?");
                $stmt->execute([$payload['id_hewan']]);
            }

            // Redirect dengan timestamp untuk cache-busting
            $ts = time();
            header("Location: index.php?page=penempatan_kandang_koordinator&success=1&t=$ts");
            exit;
        }

        // Tampilkan halaman penempatan + okupansi
        $h = $this->m->getHewan();
        $k = $this->m->getKandang();
        $data = $this->m->getAll();
        $okupansi = $this->m->getOkupansi();

        include __DIR__ . '/../../views/Master_Transaksi/penempatan_kandang/koordinator.php';
    }

    //  Aksi untuk mengeluarkan hewan dari kandang secara langsung
    public function release($id)
    {
        $this->m->release($id);
        $ts = time();
        header("Location: index.php?page=penempatan_kandang_koordinator&success_release=1&t=$ts");
        exit;
    }
}
?>