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

    // ponytail: nama_vaksin harus unik (case-insensitive)
    public function isDuplicate($nama_vaksin, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM vaksin WHERE LOWER(nama_vaksin) = LOWER(?)";
        $params = [$nama_vaksin];
        if ($exclude_id) {
            $sql .= " AND id_vaksin != ?";
            $params[] = $exclude_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function insert($nama, $desk, $status) { 
        $kode = buat_kode_otomatis('vaksin', 'kode_vaksin', 'VK');
        $stmt = $this->pdo->prepare("INSERT INTO vaksin (kode_vaksin, nama_vaksin, deskripsi, status) VALUES (?, ?, ?, ?)"); 
        return $stmt->execute([$kode, $nama, $desk, $status]); 
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