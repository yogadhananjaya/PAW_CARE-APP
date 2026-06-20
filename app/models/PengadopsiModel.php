<?php
require_once __DIR__ . '/../../config/connect.php';

class PengadopsiModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT p.*, u.username FROM pengadopsi p LEFT JOIN pengguna u ON p.id_pengguna = u.id_pengguna ORDER BY p.id_pengadopsi DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM pengadopsi WHERE id_pengadopsi = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($d) { 
        $stmt = $this->pdo->prepare("INSERT INTO pengadopsi (id_pengguna, nama_lengkap, nik, alamat, no_hp, status_verifikasi) VALUES (?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([$d['id_pengguna'], $d['nama_lengkap'], $d['nik'], $d['alamat'], $d['no_hp'], $d['status_verifikasi']]); 
    }

    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE pengadopsi SET id_pengguna=?, nama_lengkap=?, nik=?, alamat=?, no_hp=?, status_verifikasi=? WHERE id_pengadopsi=?"); 
        return $stmt->execute([$d['id_pengguna'], $d['nama_lengkap'], $d['nik'], $d['alamat'], $d['no_hp'], $d['status_verifikasi'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM pengadopsi WHERE id_pengadopsi = ?"); 
        return $stmt->execute([$id]); 
    }

    public function getOpsiPengguna() { 
        return $this->pdo->query("SELECT id_pengguna, username FROM pengguna WHERE role='User' ORDER BY username ASC")->fetchAll(); 
    }
}
?>