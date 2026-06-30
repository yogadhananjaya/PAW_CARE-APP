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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->insert($_POST);
            header('Location: index.php?page=penempatan_kandang');
            exit;
        }
        $h = $this->m->getHewan();
        $k = $this->m->getKandang();
        include __DIR__ . '/../../views/Master_Transaksi/penempatan_kandang/create.php';
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->m->update($id, $_POST);
            header('Location: index.php?page=penempatan_kandang');
            exit;
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
                'id_hewan' => $_POST['id_hewan'],
                'id_kandang' => $_POST['id_kandang'],
                'tanggal_masuk' => $_POST['tanggal_masuk'],
                'tanggal_keluar' => null
            ];

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