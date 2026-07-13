<?php
require_once __DIR__ . '/../../config/connect.php';

class RasModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo->query("SELECT r.*, j.nama_jenis FROM ras r JOIN jenis_hewan j ON r.id_jenis = j.id_jenis ORDER BY r.id_ras DESC")->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM ras WHERE id_ras = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    //   nama_ras + id_jenis harus unik (case-insensitive)
    public function isDuplicate($nama_ras, $id_jenis, $exclude_id = null)
    {
        $sql = "SELECT COUNT(*) FROM ras WHERE LOWER(nama_ras) = LOWER(?) AND id_jenis = ?";
        $params = [$nama_ras, $id_jenis];
        if ($exclude_id) { // Akan di execute jika bernilai lebih dari 0
            $sql .= "   AND id_ras != ?";
            $params[] = $exclude_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0; // Jika lebih dari 0 brrti ada duplikat dan mengembalikkan nilai true
    }

    public function insert($id_jenis, $nama_ras)
    {
        $kode = buat_kode_otomatis('ras', 'kode_ras', 'RS');
        $stmt = $this->pdo->prepare("INSERT INTO ras (kode_ras, id_jenis, nama_ras) VALUES (?, ?, ?)");
        return $stmt->execute([$kode, $id_jenis, $nama_ras]);
    }

    public function update($id, $id_jenis, $nama_ras)
    {
        $stmt = $this->pdo->prepare("UPDATE ras SET id_jenis = ?, nama_ras = ? WHERE id_ras = ?");
        return $stmt->execute([$id_jenis, $nama_ras, $id]);
    }

    public function isUsedInHewan($id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM hewan WHERE id_ras = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0; //lebih dari  0 ->  Mengembalikan true (Artinya: Ras masih dipakai, tidak boleh dihapus).
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM ras WHERE id_ras = ?");
        return $stmt->execute([$id]);
    }

    public function getOpsiJenis()
    {
        return $this->pdo->query("SELECT * FROM jenis_hewan ORDER BY nama_jenis ASC")->fetchAll();
    }
}
?>