<?php
require_once __DIR__ . '/../../config/connect.php';

class DonasiModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT * FROM donasi ORDER BY id_donasi DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM donasi WHERE id_donasi = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($d) { 
        $stmt = $this->pdo->prepare("INSERT INTO donasi (nama_donatur, jumlah, tanggal, status) VALUES (?, ?, ?, ?)"); 
        return $stmt->execute([$d['nama_donatur'], $d['jumlah'], $d['tanggal'], $d['status']]); 
    }

    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE donasi SET nama_donatur=?, jumlah=?, tanggal=?, status=? WHERE id_donasi=?"); 
        return $stmt->execute([$d['nama_donatur'], $d['jumlah'], $d['tanggal'], $d['status'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM donasi WHERE id_donasi = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>