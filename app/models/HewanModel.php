<?php
require_once __DIR__ . '/../../config/connect.php';

class HewanModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() {
        $sql = "SELECT h.*, j.nama_jenis, r.nama_ras 
                FROM hewan h
                JOIN jenis_hewan j ON h.id_jenis = j.id_jenis
                JOIN ras r ON h.id_ras = r.id_ras
                ORDER BY h.id_hewan DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM hewan WHERE id_hewan = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($data) {
        $sql = "INSERT INTO hewan (id_jenis, id_ras, nama_hewan, jenis_kelamin, umur, status, foto, deskripsi) 
                VALUES (:id_jenis, :id_ras, :nama_hewan, :jenis_kelamin, :umur, :status, :foto, :deskripsi)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE hewan SET 
                    id_jenis = :id_jenis, 
                    id_ras = :id_ras, 
                    nama_hewan = :nama_hewan, 
                    jenis_kelamin = :jenis_kelamin, 
                    umur = :umur, 
                    status = :status, 
                    foto = :foto, 
                    deskripsi = :deskripsi 
                WHERE id_hewan = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM hewan WHERE id_hewan = ?"); 
        return $stmt->execute([$id]); 
    }

    public function getOpsiJenis() { 
        return $this->pdo->query("SELECT * FROM jenis_hewan ORDER BY nama_jenis ASC")->fetchAll(); 
    }

    public function getOpsiRas() { 
        return $this->pdo->query("SELECT * FROM ras ORDER BY nama_ras ASC")->fetchAll(); 
    }
}
?>