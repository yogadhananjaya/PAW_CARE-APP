<?php
require_once __DIR__ . '/../../config/connect.php';

class PengadopsiModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua data pengadopsi, urutkan dari terbaru
    public function getAll() { 
        return $this->pdo->query("SELECT * FROM pengadopsi ORDER BY id_pengadopsi DESC")->fetchAll(); 
    }

    // ponytail: Ambil pengadopsi yang sudah terverifikasi saja
    public function getVerifiedAdopters() {
        return $this->pdo->query("SELECT * FROM pengadopsi WHERE status_verifikasi = 'Terverifikasi' ORDER BY nama_lengkap ASC")->fetchAll();
    }

    // Ambil satu data pengadopsi berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM pengadopsi WHERE id_pengadopsi = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Ambil data pengadopsi berdasarkan email
    public function getByEmail($email) { 
        $stmt = $this->pdo->prepare("SELECT * FROM pengadopsi WHERE email = ?"); 
        $stmt->execute([$email]); 
        return $stmt->fetch(); 
    }

    // Simpan data pengadopsi baru (kolom sesuai DB baru)
    public function insert($d) { 
        $kode = buat_kode_otomatis('pengadopsi', 'kode_pengadopsi', 'AD');
        $kata_sandi = password_hash($d['kata_sandi'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO pengadopsi (kode_pengadopsi, nama_lengkap, nama_pengguna, alamat, no_hp, email, kata_sandi, status_verifikasi, url_ktp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $kode,
            $d['nama_lengkap'], 
            $d['nama_pengguna'], 
            $d['alamat'], 
            $d['no_hp'], 
            $d['email'], 
            $kata_sandi,
            $d['status_verifikasi'] ?? 'Belum',
            $d['url_ktp'] ?? null
        ]); 
    }

    // Update data pengadopsi
    public function update($id, $d) { 
        if (!empty($d['kata_sandi'])) {
            $kata_sandi = password_hash($d['kata_sandi'], PASSWORD_DEFAULT);
        } else {
            $existing = $this->getById($id);
            $kata_sandi = $existing['kata_sandi'];
        }
        $stmt = $this->pdo->prepare("UPDATE pengadopsi SET nama_lengkap=?, nama_pengguna=?, alamat=?, no_hp=?, email=?, kata_sandi=?, status_verifikasi=?, tanggal_verifikasi=?, catatan_verifikasi=?, url_ktp=? WHERE id_pengadopsi=?"); 
        return $stmt->execute([
            $d['nama_lengkap'], 
            $d['nama_pengguna'], 
            $d['alamat'], 
            $d['no_hp'], 
            $d['email'], 
            $kata_sandi,
            $d['status_verifikasi'],
            empty($d['tanggal_verifikasi']) ? null : $d['tanggal_verifikasi'],
            $d['catatan_verifikasi'] ?? null,
            $d['url_ktp'] ?? null,
            $id
        ]); 
    }

    // Hapus pengadopsi berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM pengadopsi WHERE id_pengadopsi = ?"); 
        return $stmt->execute([$id]); 
    }

    // ponytail: nama_pengguna (username) harus unik lintas pengadopsi dan pengguna
    public function isDuplicateUsername($nama_pengguna, $exclude_id = null) {
        // Cek di tabel pengadopsi
        $sql1 = "SELECT COUNT(*) FROM pengadopsi WHERE LOWER(nama_pengguna) = LOWER(?)";
        $params1 = [$nama_pengguna];
        if ($exclude_id) {
            $sql1 .= " AND id_pengadopsi != ?";
            $params1[] = $exclude_id;
        }
        $stmt1 = $this->pdo->prepare($sql1);
        $stmt1->execute($params1);
        if ($stmt1->fetchColumn() > 0) {
            return true;
        }

        // Cek di tabel pengguna
        $stmt2 = $this->pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE LOWER(nama_pengguna) = LOWER(?)");
        $stmt2->execute([$nama_pengguna]);
        return $stmt2->fetchColumn() > 0;
    }
}
?>