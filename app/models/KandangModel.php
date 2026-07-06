<?php
require_once __DIR__ . '/../../config/connect.php';

class KandangModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT k.*, j.nama_jenis FROM kandang k LEFT JOIN jenis_hewan j ON k.id_jenis = j.id_jenis ORDER BY k.id_kandang DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM kandang WHERE id_kandang = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    //   kode_kandang dan nama_kandang harus unik
    public function isDuplicate($kode_kandang, $nama_kandang, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM kandang WHERE (LOWER(kode_kandang) = LOWER(?) OR LOWER(nama_kandang) = LOWER(?))";
        $params = [$kode_kandang, $nama_kandang];
        if ($exclude_id) {
            $sql .= " AND id_kandang != ?";
            $params[] = $exclude_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function insert($data) { 
        $stmt = $this->pdo->prepare("INSERT INTO kandang (kode_kandang, nama_kandang, id_jenis, kapasitas, status) VALUES (?, ?, ?, ?, ?)"); 
        return $stmt->execute([$data['kode_kandang'], $data['nama_kandang'], $data['id_jenis'], $data['kapasitas'], $data['status']]); 
    }

    public function update($id, $data) { 
        $stmt = $this->pdo->prepare("UPDATE kandang SET kode_kandang=?, nama_kandang=?, id_jenis=?, kapasitas=?, status=? WHERE id_kandang=?"); 
        return $stmt->execute([$data['kode_kandang'], $data['nama_kandang'], $data['id_jenis'], $data['kapasitas'], $data['status'], $id]); 
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