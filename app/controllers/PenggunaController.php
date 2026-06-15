<?php
// app/controllers/PenggunaController.php

class PenggunaController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Menampilkan daftar pengguna
    public function index() {
        $stmt = $this->db->query("SELECT * FROM pengguna ORDER BY id_pengguna DESC");
        $pengguna = $stmt->fetchAll();

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/pengguna/index.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    // Menampilkan halaman tambah pengguna
    public function create() {
        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/pengguna/tambah.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    // Memproses penyimpanan data pengguna baru
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $jabatan = trim($_POST['jabatan']);
            $kontak = trim($_POST['kontak']);
            $nama_pengguna = trim($_POST['nama_pengguna']);
            $kata_sandi = $_POST['kata_sandi'];
            $role = trim($_POST['role']);

            if (empty($nama_lengkap) || empty($jabatan) || empty($kontak) || empty($nama_pengguna) || empty($kata_sandi) || empty($role)) {
                header("Location: index.php?action=pengguna_tambah&error=Semua kolom wajib diisi!");
                exit;
            }

            // Hash password
            $hashedPassword = password_hash($kata_sandi, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("INSERT INTO pengguna (nama_lengkap, jabatan, kontak, nama_pengguna, kata_sandi, role) VALUES (:nama_lengkap, :jabatan, :kontak, :nama_pengguna, :kata_sandi, :role)");
            $sukses = $stmt->execute([
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'kontak' => $kontak,
                'nama_pengguna' => $nama_pengguna,
                'kata_sandi' => $hashedPassword,
                'role' => $role
            ]);

            if ($sukses) {
                header("Location: index.php?action=pengguna&success=Data pengguna berhasil ditambahkan!");
            } else {
                header("Location: index.php?action=pengguna_tambah&error=Gagal menyimpan data pengguna.");
            }
            exit;
        }
    }

    // Menampilkan form edit pengguna
    public function edit($id) {
        $stmt = $this->db->prepare("SELECT * FROM pengguna WHERE id_pengguna = :id");
        $stmt->execute(['id' => $id]);
        $pengguna = $stmt->fetch();

        if (!$pengguna) {
            header("Location: index.php?action=pengguna&error=Data pengguna tidak ditemukan!");
            exit;
        }

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/pengguna/edit.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    // Memproses pembaruan data pengguna
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_pengguna'];
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $jabatan = trim($_POST['jabatan']);
            $kontak = trim($_POST['kontak']);
            $nama_pengguna = trim($_POST['nama_pengguna']);
            $kata_sandi = $_POST['kata_sandi'];
            $role = trim($_POST['role']);

            if (empty($nama_lengkap) || empty($jabatan) || empty($kontak) || empty($nama_pengguna) || empty($role)) {
                header("Location: index.php?action=pengguna_edit&id=" . $id . "&error=Semua kolom wajib diisi!");
                exit;
            }

            if (!empty($kata_sandi)) {
                $hashedPassword = password_hash($kata_sandi, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("UPDATE pengguna SET nama_lengkap = :nama_lengkap, jabatan = :jabatan, kontak = :kontak, nama_pengguna = :nama_pengguna, kata_sandi = :kata_sandi, role = :role WHERE id_pengguna = :id");
                $sukses = $stmt->execute([
                    'nama_lengkap' => $nama_lengkap,
                    'jabatan' => $jabatan,
                    'kontak' => $kontak,
                    'nama_pengguna' => $nama_pengguna,
                    'kata_sandi' => $hashedPassword,
                    'role' => $role,
                    'id' => $id
                ]);
            } else {
                $stmt = $this->db->prepare("UPDATE pengguna SET nama_lengkap = :nama_lengkap, jabatan = :jabatan, kontak = :kontak, nama_pengguna = :nama_pengguna, role = :role WHERE id_pengguna = :id");
                $sukses = $stmt->execute([
                    'nama_lengkap' => $nama_lengkap,
                    'jabatan' => $jabatan,
                    'kontak' => $kontak,
                    'nama_pengguna' => $nama_pengguna,
                    'role' => $role,
                    'id' => $id
                ]);
            }

            if ($sukses) {
                header("Location: index.php?action=pengguna&success=Data pengguna berhasil diperbarui!");
            } else {
                header("Location: index.php?action=pengguna_edit&id=" . $id . "&error=Gagal memperbarui data.");
            }
            exit;
        }
    }

    // Menghapus data pengguna
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM pengguna WHERE id_pengguna = :id");
        $sukses = $stmt->execute(['id' => $id]);

        if ($sukses) {
            header("Location: index.php?action=pengguna&success=Data pengguna berhasil dihapus!");
        } else {
            header("Location: index.php?action=pengguna&error=Gagal menghapus data pengguna.");
        }
        exit;
    }
}
