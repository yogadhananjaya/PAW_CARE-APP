<?php
require_once __DIR__ . '/../../config/connect.php';

class JenisModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT * FROM jenis_hewan ORDER BY id_jenis DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM jenis_hewan WHERE id_jenis = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($nama_jenis) { 
        $stmt = $this->pdo->prepare("INSERT INTO jenis_hewan (nama_jenis) VALUES (?)"); 
        return $stmt->execute([$nama_jenis]); 
    }

    public function update($id, $nama_jenis) { 
        $stmt = $this->pdo->prepare("UPDATE jenis_hewan SET nama_jenis = ? WHERE id_jenis = ?"); 
        return $stmt->execute([$nama_jenis, $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM jenis_hewan WHERE id_jenis = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>