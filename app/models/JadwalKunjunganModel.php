<?php
require_once __DIR__ . '/../../config/connect.php';

class JadwalKunjunganModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT j.*, p.nama_lengkap, h.nama_hewan FROM jadwal_kunjungan j JOIN pengadopsi p ON j.id_pengadopsi = p.id_pengadopsi JOIN hewan h ON j.id_hewan = h.id_hewan ORDER BY j.id_jadwal DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM jadwal_kunjungan WHERE id_jadwal = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($d) { 
        $stmt = $this->pdo->prepare("INSERT INTO jadwal_kunjungan (id_pengadopsi, id_hewan, tanggal_kunjungan, status) VALUES (?, ?, ?, ?)"); 
        return $stmt->execute([$d['id_pengadopsi'], $d['id_hewan'], $d['tanggal_kunjungan'], $d['status']]); 
    }

    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE jadwal_kunjungan SET id_pengadopsi=?, id_hewan=?, tanggal_kunjungan=?, status=? WHERE id_jadwal=?"); 
        return $stmt->execute([$d['id_pengadopsi'], $d['id_hewan'], $d['tanggal_kunjungan'], $d['status'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM jadwal_kunjungan WHERE id_jadwal = ?"); 
        return $stmt->execute([$id]); 
    }

    public function getPengadopsi() { 
        return $this->pdo->query("SELECT id_pengadopsi, nama_lengkap FROM pengadopsi")->fetchAll(); 
    }

    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan FROM hewan WHERE status = 'Tersedia'")->fetchAll(); 
    }
}
?>