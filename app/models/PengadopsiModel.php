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

    // Ambil satu data pengadopsi berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM pengadopsi WHERE id_pengadopsi = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Simpan data pengadopsi baru (kolom sesuai DB baru)
    public function insert($d) { 
        $kata_sandi = password_hash($d['kata_sandi'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO pengadopsi (nama, alamat, no_hp, email, kata_sandi, status_verifikasi) VALUES (?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $d['nama'], 
            $d['alamat'], 
            $d['no_hp'], 
            $d['email'], 
            $kata_sandi,
            $d['status_verifikasi'] ?? 'Belum'
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
        $stmt = $this->pdo->prepare("UPDATE pengadopsi SET nama=?, alamat=?, no_hp=?, email=?, kata_sandi=?, status_verifikasi=?, tanggal_verifikasi=?, catatan_verifikasi=? WHERE id_pengadopsi=?"); 
        return $stmt->execute([
            $d['nama'], 
            $d['alamat'], 
            $d['no_hp'], 
            $d['email'], 
            $kata_sandi,
            $d['status_verifikasi'],
            empty($d['tanggal_verifikasi']) ? null : $d['tanggal_verifikasi'],
            $d['catatan_verifikasi'] ?? null,
            $id
        ]); 
    }

    // Hapus pengadopsi berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM pengadopsi WHERE id_pengadopsi = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>