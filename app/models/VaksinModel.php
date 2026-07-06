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
            SELECT v.*, GROUP_CONCAT(j.nama_jenis SEPARATOR ', ') as nama_jenis
            FROM vaksin v
            LEFT JOIN vaksin_jenis vj ON v.id_vaksin = vj.id_vaksin
            LEFT JOIN jenis_hewan j ON vj.id_jenis = j.id_jenis
            GROUP BY v.id_vaksin
            ORDER BY v.id_vaksin DESC
        ")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT v.*, GROUP_CONCAT(vj.id_jenis) as id_jenis_list FROM vaksin v LEFT JOIN vaksin_jenis vj ON v.id_vaksin = vj.id_vaksin WHERE v.id_vaksin = ? GROUP BY v.id_vaksin"); 
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

    public function insert($nama, $id_jenis_arr, $desk, $status, $stok) { 
        $kode = buat_kode_otomatis('vaksin', 'kode_vaksin', 'VK');
        $stmt = $this->pdo->prepare("INSERT INTO vaksin (kode_vaksin, nama_vaksin, deskripsi, status, stok) VALUES (?, ?, ?, ?, ?)"); 
        $stmt->execute([$kode, $nama, $desk, $status, $stok]);
        $id_vaksin = $this->pdo->lastInsertId();
        if (!empty($id_jenis_arr)) {
            $stmt_pivot = $this->pdo->prepare("INSERT INTO vaksin_jenis (id_vaksin, id_jenis) VALUES (?, ?)");
            foreach ($id_jenis_arr as $id_jenis) {
                if (!empty($id_jenis)) $stmt_pivot->execute([$id_vaksin, $id_jenis]);
            }
        }
        return true;
    }

    public function update($id, $nama, $id_jenis_arr, $desk, $status, $stok) { 
        $stmt = $this->pdo->prepare("UPDATE vaksin SET nama_vaksin=?, deskripsi=?, status=?, stok=? WHERE id_vaksin=?"); 
        $result = $stmt->execute([$nama, $desk, $status, $stok, $id]);
        // Replace pivot entries
        $this->pdo->prepare("DELETE FROM vaksin_jenis WHERE id_vaksin = ?")->execute([$id]);
        if (!empty($id_jenis_arr)) {
            $stmt_pivot = $this->pdo->prepare("INSERT INTO vaksin_jenis (id_vaksin, id_jenis) VALUES (?, ?)");
            foreach ($id_jenis_arr as $id_jenis) {
                if (!empty($id_jenis)) $stmt_pivot->execute([$id, $id_jenis]);
            }
        }
        return $result;
    }

    public function isUsed($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM riwayat_kesehatan WHERE id_vaksin = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function delete($id) { 
        $this->pdo->prepare("DELETE FROM vaksin_jenis WHERE id_vaksin = ?")->execute([$id]);
        $stmt = $this->pdo->prepare("DELETE FROM vaksin WHERE id_vaksin = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>
