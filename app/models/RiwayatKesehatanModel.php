<?php
require_once __DIR__ . '/../../config/connect.php';

class RiwayatKesehatanModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT r.*, h.nama_hewan, v.nama_vaksin, p.username as perawat FROM riwayat_kesehatan r JOIN hewan h ON r.id_hewan = h.id_hewan LEFT JOIN vaksin v ON r.id_vaksin = v.id_vaksin JOIN pengguna p ON r.id_pengguna = p.id_pengguna ORDER BY r.id_riwayat DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM riwayat_kesehatan WHERE id_riwayat = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($d) { 
        $stmt = $this->pdo->prepare("INSERT INTO riwayat_kesehatan (id_hewan, id_vaksin, id_pengguna, tanggal_periksa, diagnosa, tindakan) VALUES (?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([$d['id_hewan'], empty($d['id_vaksin']) ? null : $d['id_vaksin'], $d['id_pengguna'], $d['tanggal_periksa'], $d['diagnosa'], $d['tindakan']]); 
    }

    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE riwayat_kesehatan SET id_hewan=?, id_vaksin=?, id_pengguna=?, tanggal_periksa=?, diagnosa=?, tindakan=? WHERE id_riwayat=?"); 
        return $stmt->execute([$d['id_hewan'], empty($d['id_vaksin']) ? null : $d['id_vaksin'], $d['id_pengguna'], $d['tanggal_periksa'], $d['diagnosa'], $d['tindakan'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM riwayat_kesehatan WHERE id_riwayat = ?"); 
        return $stmt->execute([$id]); 
    }

    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan FROM hewan")->fetchAll(); 
    }

    public function getVaksin() { 
        return $this->pdo->query("SELECT id_vaksin, nama_vaksin FROM vaksin")->fetchAll(); 
    }

    public function getPerawat() { 
        return $this->pdo->query("SELECT id_pengguna, username FROM pengguna WHERE role IN ('Pegawai', 'SuperAdmin')")->fetchAll(); 
    }
}
?>