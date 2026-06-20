<?php
require_once __DIR__ . '/../../config/connect.php';

class KandangModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT * FROM kandang ORDER BY id_kandang DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM kandang WHERE id_kandang = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($data) { 
        $stmt = $this->pdo->prepare("INSERT INTO kandang (nama_kandang, kapasitas, status) VALUES (?, ?, ?)"); 
        return $stmt->execute([$data['nama_kandang'], $data['kapasitas'], $data['status']]); 
    }

    public function update($id, $data) { 
        $stmt = $this->pdo->prepare("UPDATE kandang SET nama_kandang=?, kapasitas=?, status=? WHERE id_kandang=?"); 
        return $stmt->execute([$data['nama_kandang'], $data['kapasitas'], $data['status'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM kandang WHERE id_kandang = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>