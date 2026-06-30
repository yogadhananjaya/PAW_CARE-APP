<?php
require_once __DIR__ . '/../../config/connect.php';

class PenempatanKandangModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT p.*, h.nama_hewan, k.kode_kandang, k.nama_kandang, j.nama_jenis FROM penempatan_kandang p JOIN hewan h ON p.id_hewan = h.id_hewan JOIN kandang k ON p.id_kandang = k.id_kandang LEFT JOIN jenis_hewan j ON h.id_jenis = j.id_jenis ORDER BY p.id_penempatan DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM penempatan_kandang WHERE id_penempatan = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($d) { 
        //  otomatis nonaktifkan penempatan kandang sebelumnya untuk hewan ini
        $deactive = $this->pdo->prepare("UPDATE penempatan_kandang SET status = 'Riwayat', tanggal_keluar = ? WHERE id_hewan = ? AND status = 'Aktif'");
        $deactive->execute([$d['tanggal_masuk'], $d['id_hewan']]);

        $kode = buat_kode_otomatis('penempatan_kandang', 'kode_penempatan_kandang', 'PK');
        $stmt = $this->pdo->prepare("INSERT INTO penempatan_kandang (kode_penempatan_kandang, id_hewan, id_kandang, tanggal_masuk, tanggal_keluar) VALUES (?, ?, ?, ?, ?)"); 
        return $stmt->execute([$kode, $d['id_hewan'], $d['id_kandang'], $d['tanggal_masuk'], empty($d['tanggal_keluar']) ? null : $d['tanggal_keluar']]); 
    }

    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE penempatan_kandang SET id_hewan=?, id_kandang=?, tanggal_masuk=?, tanggal_keluar=? WHERE id_penempatan=?"); 
        return $stmt->execute([$d['id_hewan'], $d['id_kandang'], $d['tanggal_masuk'], empty($d['tanggal_keluar']) ? null : $d['tanggal_keluar'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM penempatan_kandang WHERE id_penempatan = ?"); 
        return $stmt->execute([$id]); 
    }

    //  Cek apakah hewan sudah aktif di kandang tersebut
    public function isAlreadyInCage($id_hewan, $id_kandang) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM penempatan_kandang WHERE id_hewan = ? AND id_kandang = ? AND status = 'Aktif'");
        $stmt->execute([$id_hewan, $id_kandang]);
        return $stmt->fetchColumn() > 0;
    }

    //  Cek apakah jenis hewan cocok dengan jenis kandang
    public function checkJenisCocok($id_hewan, $id_kandang) {
        $stmt = $this->pdo->prepare("SELECT id_jenis FROM hewan WHERE id_hewan = ?");
        $stmt->execute([$id_hewan]);
        $jenis_hewan = $stmt->fetchColumn();

        $stmt2 = $this->pdo->prepare("SELECT id_jenis FROM kandang WHERE id_kandang = ?");
        $stmt2->execute([$id_kandang]);
        $jenis_kandang = $stmt2->fetchColumn();

        return $jenis_hewan == $jenis_kandang;
    }

    //  Keluarkan hewan dari kandang (Ubah status jadi Riwayat dan set tanggal_keluar)
    public function release($id) {
        $stmt = $this->pdo->prepare("UPDATE penempatan_kandang SET status = 'Riwayat', tanggal_keluar = CURDATE() WHERE id_penempatan = ?");
        return $stmt->execute([$id]);
    }

    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan, status_adopsi, id_jenis FROM hewan ORDER BY nama_hewan ASC")->fetchAll(); 
    }

    public function getKandang() { 
        $sql = "SELECT k.id_kandang, k.kode_kandang, k.nama_kandang, k.kapasitas, k.id_jenis,
            (SELECT COUNT(*) FROM penempatan_kandang pk WHERE pk.id_kandang = k.id_kandang AND pk.status = 'Aktif') as terisi
            FROM kandang k
            ORDER BY k.kode_kandang ASC";
        return $this->pdo->query($sql)->fetchAll(); 
    }

    // Ambil okupansi kapasitas kandang (terisi vs kapasitas)
    public function getOkupansi() {
        $sql = "SELECT k.id_kandang, k.kode_kandang, k.nama_kandang, k.kapasitas,
            (SELECT COUNT(*) FROM penempatan_kandang pk WHERE pk.id_kandang = k.id_kandang AND pk.status = 'Aktif') as terisi
            FROM kandang k
            ORDER BY k.kode_kandang ASC";
        return $this->pdo->query($sql)->fetchAll();
    }
}
?>