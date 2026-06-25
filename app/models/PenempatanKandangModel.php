<?php
require_once __DIR__ . '/../../config/connect.php';

class PenempatanKandangModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT p.*, h.nama_hewan, k.kode_kandang, k.nama_kandang FROM penempatan_kandang p JOIN hewan h ON p.id_hewan = h.id_hewan JOIN kandang k ON p.id_kandang = k.id_kandang ORDER BY p.id_penempatan DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM penempatan_kandang WHERE id_penempatan = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($d) { 
        $stmt = $this->pdo->prepare("INSERT INTO penempatan_kandang (id_hewan, id_kandang, tanggal_masuk, tanggal_keluar) VALUES (?, ?, ?, ?)"); 
        return $stmt->execute([$d['id_hewan'], $d['id_kandang'], $d['tanggal_masuk'], empty($d['tanggal_keluar']) ? null : $d['tanggal_keluar']]); 
    }

    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE penempatan_kandang SET id_hewan=?, id_kandang=?, tanggal_masuk=?, tanggal_keluar=? WHERE id_penempatan=?"); 
        return $stmt->execute([$d['id_hewan'], $d['id_kandang'], $d['tanggal_masuk'], empty($d['tanggal_keluar']) ? null : $d['tanggal_keluar'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM penempatan_kandang WHERE id_penempatan = ?"); 
        return $stmt->execute([$id]); 
    }

    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan, status_adopsi FROM hewan ORDER BY nama_hewan ASC")->fetchAll(); 
    }

    public function getKandang() { 
        $sql = "SELECT k.id_kandang, k.kode_kandang, k.nama_kandang, k.kapasitas,
            (SELECT COUNT(*) FROM penempatan_kandang pk WHERE pk.id_kandang = k.id_kandang AND pk.status = 'Aktif') as terisi
            FROM kandang k
            ORDER BY k.kode_kandang ASC";
        return $this->pdo->query($sql)->fetchAll(); 
    }

    // Ambil okupansi kapasitas kandang (terisi vs kapasitas)
    public function getOkupansi() {
        $sql = "SELECT k.id_kandang, k.kode_kandang, k.nama_kandang, k.kapasitas,
            (SELECT COUNT(*) FROM penempatan_kandang pk WHERE pk.id_kandang = k.id_kandang AND pk.status = 'Aktif') as terisi
            FROM kandang k
            ORDER BY k.kode_kandang ASC";
        return $this->pdo->query($sql)->fetchAll();
    }
}
?>