<?php
// app/controllers/PerawatanController.php

class PerawatanController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Menampilkan riwayat perawatan
    public function index() {
        // Menggunakan LEFT JOIN untuk mengambil nama dari tabel Pegawai dan Hewan
        $query = "SELECT pr.*, pg.nama AS nama_pegawai, h.nama_hewan 
                  FROM Perawatan pr 
                  LEFT JOIN Pegawai pg ON pr.id_pegawai = pg.id_pegawai 
                  LEFT JOIN Hewan h ON pr.id_hewan = h.id_hewan 
                  ORDER BY pr.tanggal_perawatan DESC";
        $stmt = $this->db->query($query);
        $perawatan = $stmt->fetchAll();

        $basePath = __DIR__ . '/../../';
        require_once $basePath . 'views/layouts/header.php';
        require_once $basePath . 'views/perawatan/index.php';
        require_once $basePath . 'views/layouts/footer.php';
    }

    // Menampilkan form tambah perawatan
    public function create() {
        // Ambil data Pegawai dan Hewan untuk diisi ke dalam dropdown (select)
        $pegawai = $this->db->query("SELECT id_pegawai, nama, jabatan FROM Pegawai")->fetchAll();
        $hewan = $this->db->query("SELECT id_hewan, nama_hewan FROM Hewan")->fetchAll();

        $basePath = __DIR__ . '/../../';
        require_once $basePath . 'views/layouts/header.php';
        require_once $basePath . 'views/perawatan/tambah.php';
        require_once $basePath . 'views/layouts/footer.php';
    }

    // Menyimpan transaksi perawatan ke database
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_hewan = $_POST['id_hewan'];
            $id_pegawai = $_POST['id_pegawai'];
            $tanggal = $_POST['tanggal_perawatan'];
            $pemeriksaan = trim($_POST['pemeriksaan']);
            $perawatan = trim($_POST['perawatan']);
            $obat = trim($_POST['pemberian_obat']);

            // Validasi input utama wajib isi
            if (empty($id_hewan) || empty($id_pegawai) || empty($tanggal)) {
                header("Location: index.php?action=perawatan_tambah&error=Hewan, Pegawai, dan Tanggal wajib diisi!");
                exit;
            }

            $stmt = $this->db->prepare("INSERT INTO Perawatan (id_pegawai, id_hewan, perawatan, pemeriksaan, pemberian_obat, tanggal_perawatan) 
                                        VALUES (:pgw, :hwn, :rawat, :periksa, :obat, :tgl)");
            $sukses = $stmt->execute([
                'pgw' => $id_pegawai,
                'hwn' => $id_hewan,
                'rawat' => $perawatan,
                'periksa' => $pemeriksaan,
                'obat' => $obat,
                'tgl' => $tanggal
            ]);

            if ($sukses) {
                header("Location: index.php?action=perawatan&success=Log perawatan berhasil dicatat!");
            } else {
                header("Location: index.php?action=perawatan_tambah&error=Gagal menyimpan riwayat perawatan.");
            }
            exit;
        }
    }
}