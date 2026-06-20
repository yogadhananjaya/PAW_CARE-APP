<?php
require_once __DIR__ . '/../../config/connect.php';

class PenggunaModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT * FROM pengguna ORDER BY id_pengguna DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($data) { 
        $stmt = $this->pdo->prepare("INSERT INTO pengguna (username, password, role) VALUES (?, ?, ?)"); 
        return $stmt->execute([$data['username'], $data['password'], $data['role']]); 
    }

    public function update($id, $data) { 
        $stmt = $this->pdo->prepare("UPDATE pengguna SET username=?, password=?, role=? WHERE id_pengguna=?"); 
        return $stmt->execute([$data['username'], $data['password'], $data['role'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM pengguna WHERE id_pengguna = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>