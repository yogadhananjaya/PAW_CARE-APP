<?php
require_once __DIR__ . '/../../config/connect.php';

class RiwayatKesehatanModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua riwayat kesehatan beserta nama hewan, vaksin, dan perawat
    public function getAll() { 
        return $this->pdo->query("SELECT r.*, h.nama_hewan, v.nama_vaksin, p.nama_pengguna as perawat FROM riwayat_kesehatan r JOIN hewan h ON r.id_hewan = h.id_hewan LEFT JOIN vaksin v ON r.id_vaksin = v.id_vaksin JOIN pengguna p ON r.id_pengguna = p.id_pengguna ORDER BY r.id_riwayat DESC")->fetchAll(); 
    }

    // Ambil satu riwayat berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM riwayat_kesehatan WHERE id_riwayat = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Simpan riwayat baru (kolom sesuai DB baru: tipe, tanggal, deskripsi)
    public function insert($d) { 
        $stmt = $this->pdo->prepare("INSERT INTO riwayat_kesehatan (id_hewan, id_pengguna, tipe, id_vaksin, tanggal, deskripsi) VALUES (?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $d['id_hewan'], 
            $d['id_pengguna'],
            $d['tipe'],
            empty($d['id_vaksin']) ? null : $d['id_vaksin'], 
            $d['tanggal'], 
            $d['deskripsi']
        ]); 
    }

    // Update riwayat kesehatan
    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE riwayat_kesehatan SET id_hewan=?, id_pengguna=?, tipe=?, id_vaksin=?, tanggal=?, deskripsi=? WHERE id_riwayat=?"); 
        return $stmt->execute([
            $d['id_hewan'], 
            $d['id_pengguna'],
            $d['tipe'],
            empty($d['id_vaksin']) ? null : $d['id_vaksin'], 
            $d['tanggal'], 
            $d['deskripsi'],
            $id
        ]); 
    }

    // Hapus riwayat berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM riwayat_kesehatan WHERE id_riwayat = ?"); 
        return $stmt->execute([$id]); 
    }

    // Ambil daftar hewan untuk dropdown
    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan FROM hewan")->fetchAll(); 
    }

    // Ambil daftar vaksin untuk dropdown
    public function getVaksin() { 
        return $this->pdo->query("SELECT id_vaksin, nama_vaksin FROM vaksin WHERE status='Tersedia'")->fetchAll(); 
    }

    // Ambil daftar perawat (pengguna yang bisa merawat)
    public function getPerawat() { 
        return $this->pdo->query("SELECT id_pengguna, nama_pengguna FROM pengguna WHERE role IN ('SuperAdmin','Pegawai')")->fetchAll(); 
    }
}
?>