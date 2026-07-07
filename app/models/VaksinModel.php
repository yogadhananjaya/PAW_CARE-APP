<?php
require_once __DIR__ . '/../../config/connect.php';

class VaksinModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("
            SELECT v.*, j.nama_jenis
            FROM vaksin v
            LEFT JOIN jenis_hewan j ON v.id_jenis = j.id_jenis
            ORDER BY v.id_vaksin DESC
        ")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT v.*, v.id_jenis as id_jenis_list FROM vaksin v WHERE v.id_vaksin = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    //   nama_vaksin harus unik (case-insensitive)
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

    public function insert($nama, $id_jenis, $desk, $status, $stok) { 
        $kode = buat_kode_otomatis('vaksin', 'kode_vaksin', 'VK');
        $stmt = $this->pdo->prepare("INSERT INTO vaksin (kode_vaksin, nama_vaksin, id_jenis, deskripsi, status, stok) VALUES (?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([$kode, $nama, $id_jenis, $desk, $status, $stok]);
    }

    public function update($id, $nama, $id_jenis, $desk, $status, $stok) { 
        $stmt = $this->pdo->prepare("UPDATE vaksin SET nama_vaksin=?, id_jenis=?, deskripsi=?, status=?, stok=? WHERE id_vaksin=?"); 
        return $stmt->execute([$nama, $id_jenis, $desk, $status, $stok, $id]);
    }

    public function isUsed($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM riwayat_kesehatan WHERE id_vaksin = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM vaksin WHERE id_vaksin = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>
