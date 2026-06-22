<?php
require_once __DIR__ . '/../../config/connect.php';

class VaksinModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT * FROM vaksin ORDER BY id_vaksin DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM vaksin WHERE id_vaksin = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($nama, $desk, $status) { 
        $stmt = $this->pdo->prepare("INSERT INTO vaksin (nama_vaksin, deskripsi, status) VALUES (?, ?, ?)"); 
        return $stmt->execute([$nama, $desk, $status]); 
    }

    public function update($id, $nama, $desk, $status) { 
        $stmt = $this->pdo->prepare("UPDATE vaksin SET nama_vaksin=?, deskripsi=?, status=? WHERE id_vaksin=?"); 
        return $stmt->execute([$nama, $desk, $status, $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM vaksin WHERE id_vaksin = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>