<?php
// app/controllers/PerawatanController.php

class PerawatanController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Menampilkan riwayat perawatan
    public function index() {
        $query = "SELECT r.*, p.nama_lengkap AS nama_pegawai, h.nama_hewan 
                  FROM riwayat_kesehatan r 
                  LEFT JOIN pengguna p ON r.id_pengguna = p.id_pengguna 
                  LEFT JOIN hewan h ON r.id_hewan = h.id_hewan 
                  WHERE r.tipe = 'Perawatan'
                  ORDER BY r.tanggal DESC";
        $stmt = $this->db->query($query);
        $perawatan = $stmt->fetchAll();

        $basePath = __DIR__ . '/../../';
        require_once $basePath . 'views/layouts/header.php';
        require_once $basePath . 'views/perawatan/index.php';
        require_once $basePath . 'views/layouts/footer.php';
    }

    // Menampilkan form tambah perawatan
    public function create() {
        // Ambil data Pengguna (Pegawai & SuperAdmin) dan Hewan
        $pegawai = $this->db->query("SELECT id_pengguna, nama_lengkap, jabatan FROM pengguna WHERE role = 'Pegawai' OR role = 'SuperAdmin'")->fetchAll();
        $hewan = $this->db->query("SELECT id_hewan, nama_hewan FROM hewan")->fetchAll();

        $basePath = __DIR__ . '/../../';
        require_once $basePath . 'views/layouts/header.php';
        require_once $basePath . 'views/perawatan/tambah.php';
        require_once $basePath . 'views/layouts/footer.php';
    }

    // Menyimpan transaksi perawatan ke database
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_hewan = $_POST['id_hewan'];
            $id_pengguna = $_POST['id_pengguna'];
            $tanggal = $_POST['tanggal_perawatan'];
            $pemeriksaan = trim($_POST['pemeriksaan']);
            $perawatan = trim($_POST['perawatan']);
            $obat = trim($_POST['pemberian_obat']);

            // Validasi input utama wajib isi
            if (empty($id_hewan) || empty($id_pengguna) || empty($tanggal)) {
                header("Location: index.php?action=perawatan_tambah&error=Hewan, Petugas, dan Tanggal wajib diisi!");
                exit;
            }

            // Gabungkan pemeriksaan, tindakan, dan obat ke dalam satu deskripsi
            $deskripsi = "";
            if ($pemeriksaan !== '') {
                $deskripsi .= "Pemeriksaan: " . $pemeriksaan . "\n";
            }
            if ($perawatan !== '') {
                $deskripsi .= "Tindakan: " . $perawatan . "\n";
            }
            if ($obat !== '') {
                $deskripsi .= "Obat: " . $obat;
            }
            $deskripsi = trim($deskripsi);

            $stmt = $this->db->prepare("INSERT INTO riwayat_kesehatan (id_hewan, id_pengguna, tipe, tanggal, deskripsi) 
                                        VALUES (:hwn, :usr, 'Perawatan', :tgl, :desc)");
            $sukses = $stmt->execute([
                'hwn' => $id_hewan,
                'usr' => $id_pengguna,
                'tgl' => $tanggal,
                'desc' => $deskripsi
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