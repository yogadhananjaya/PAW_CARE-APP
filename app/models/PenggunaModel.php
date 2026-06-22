<?php
require_once __DIR__ . '/../../config/connect.php';

class PenggunaModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua data pengguna, urutkan dari terbaru
    public function getAll() { 
        return $this->pdo->query("SELECT * FROM pengguna ORDER BY id_pengguna DESC")->fetchAll(); 
    }

    // Ambil satu data pengguna berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Simpan data pengguna baru (kolom sesuai DB baru)
    public function insert($data) { 
        $kata_sandi = password_hash($data['kata_sandi'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO pengguna (nama_lengkap, jabatan, kontak, nama_pengguna, kata_sandi, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $data['nama_lengkap'], 
            $data['jabatan'], 
            $data['kontak'],
            $data['nama_pengguna'], 
            $kata_sandi, 
            $data['role'],
            $data['status'] ?? 'Aktif'
        ]); 
    }

    // Update data pengguna (jika password diisi baru, hash ulang)
    public function update($id, $data) { 
        if (!empty($data['kata_sandi'])) {
            $kata_sandi = password_hash($data['kata_sandi'], PASSWORD_DEFAULT);
        } else {
            $existing = $this->getById($id);
            $kata_sandi = $existing['kata_sandi'];
        }
        $stmt = $this->pdo->prepare("UPDATE pengguna SET nama_lengkap=?, jabatan=?, kontak=?, nama_pengguna=?, kata_sandi=?, role=?, status=? WHERE id_pengguna=?"); 
        return $stmt->execute([
            $data['nama_lengkap'], 
            $data['jabatan'], 
            $data['kontak'],
            $data['nama_pengguna'], 
            $kata_sandi, 
            $data['role'],
            $data['status'] ?? 'Aktif',
            $id
        ]); 
    }

    // Hapus pengguna berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM pengguna WHERE id_pengguna = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>