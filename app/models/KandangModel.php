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
        $stmt = $this->pdo->prepare("INSERT INTO kandang (kode_kandang, nama_kandang, kapasitas, status) VALUES (?, ?, ?, ?)"); 
        return $stmt->execute([$data['kode_kandang'], $data['nama_kandang'], $data['kapasitas'], $data['status']]); 
    }

    public function update($id, $data) { 
        $stmt = $this->pdo->prepare("UPDATE kandang SET kode_kandang=?, nama_kandang=?, kapasitas=?, status=? WHERE id_kandang=?"); 
        return $stmt->execute([$data['kode_kandang'], $data['nama_kandang'], $data['kapasitas'], $data['status'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM kandang WHERE id_kandang = ?"); 
        return $stmt->execute([$id]); 
    }

    // Fungsi untuk mengambil ID berikutnya untuk auto-generate kode kandang
    public function getNextId() {
        $stmt = $this->pdo->query("SELECT MAX(id_kandang) as max_id FROM kandang");
        $row = $stmt->fetch();
        return ($row['max_id'] ? $row['max_id'] : 0) + 1;
    }
}
?>