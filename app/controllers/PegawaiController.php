<?php
// app/controllers/PegawaiController.php

class PegawaiController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Menampilkan halaman daftar pegawai
    public function index() {
        $stmt = $this->db->query("SELECT * FROM Pegawai ORDER BY id_pegawai DESC");
        $pegawai = $stmt->fetchAll();

        // Panggil layout dan kirim data $pegawai ke views
        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/pegawai/index.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }
    // Menampilkan halaman form tambah pegawai
    public function create() {
        $basePath = __DIR__ . '/../../';
        require_once $basePath . 'views/layouts/header.php';
        require_once $basePath . 'views/pegawai/tambah.php';
        require_once $basePath . 'views/layouts/footer.php';
    }

    // Memproses data form dan menyimpannya ke database
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama']);
            $jabatan = trim($_POST['jabatan']);
            $kontak = trim($_POST['kontak']);

            // Validasi sederhana: pastikan tidak ada yang kosong
            if (empty($nama) || empty($jabatan) || empty($kontak)) {
                header("Location: index.php?action=pegawai_tambah&error=Semua kolom wajib diisi!");
                exit;
            }

            // Simpan ke database
            $stmt = $this->db->prepare("INSERT INTO Pegawai (nama, jabatan, kontak) VALUES (:nama, :jabatan, :kontak)");
            $sukses = $stmt->execute([
                'nama' => $nama,
                'jabatan' => $jabatan,
                'kontak' => $kontak
            ]);

            // Jika sukses, kembalikan ke halaman daftar pegawai dengan pesan sukses
            if ($sukses) {
                header("Location: index.php?action=pegawai&success=Data pegawai berhasil ditambahkan!");
                exit;
            } else {
                header("Location: index.php?action=pegawai_tambah&error=Gagal menyimpan data ke database.");
                exit;
            }
        }
    }
    // Menampilkan halaman form edit dengan data lama
    public function edit($id) {
        // Ambil data pegawai berdasarkan ID
        $stmt = $this->db->prepare("SELECT * FROM Pegawai WHERE id_pegawai = :id");
        $stmt->execute(['id' => $id]);
        $pegawai = $stmt->fetch();

        // Jika data tidak ditemukan, kembalikan ke halaman awal
        if (!$pegawai) {
            header("Location: index.php?action=pegawai&error=Data pegawai tidak ditemukan!");
            exit;
        }

        $basePath = __DIR__ . '/../../';
        require_once $basePath . 'views/layouts/header.php';
        require_once $basePath . 'views/pegawai/edit.php';
        require_once $basePath . 'views/layouts/footer.php';
    }

    // Memproses pembaruan data ke database
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_pegawai'];
            $nama = trim($_POST['nama']);
            $jabatan = trim($_POST['jabatan']);
            $kontak = trim($_POST['kontak']);

            if (empty($nama) || empty($jabatan) || empty($kontak)) {
                header("Location: index.php?action=pegawai_edit&id=" . $id . "&error=Semua kolom wajib diisi!");
                exit;
            }

            $stmt = $this->db->prepare("UPDATE Pegawai SET nama = :nama, jabatan = :jabatan, kontak = :kontak WHERE id_pegawai = :id");
            $sukses = $stmt->execute([
                'nama' => $nama,
                'jabatan' => $jabatan,
                'kontak' => $kontak,
                'id' => $id
            ]);

            if ($sukses) {
                header("Location: index.php?action=pegawai&success=Data pegawai berhasil diperbarui!");
                exit;
            } else {
                header("Location: index.php?action=pegawai_edit&id=" . $id . "&error=Gagal memperbarui data.");
                exit;
            }
        }
    }

    // Menghapus data dari database
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM Pegawai WHERE id_pegawai = :id");
        $sukses = $stmt->execute(['id' => $id]);

        if ($sukses) {
            header("Location: index.php?action=pegawai&success=Data pegawai berhasil dihapus!");
        } else {
            header("Location: index.php?action=pegawai&error=Gagal menghapus data pegawai.");
        }
        exit;
    }
}