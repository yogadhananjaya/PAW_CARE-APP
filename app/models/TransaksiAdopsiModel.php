<?php
require_once __DIR__ . '/../../config/connect.php';

class TransaksiAdopsiModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua transaksi adopsi beserta nama pengadopsi dan hewan
    public function getAll() { 
        return $this->pdo->query("SELECT t.*, p.nama as nama_pengadopsi, h.nama_hewan, pg.nama_pengguna as nama_staf FROM transaksi_adopsi t JOIN pengadopsi p ON t.id_pengadopsi = p.id_pengadopsi JOIN hewan h ON t.id_hewan = h.id_hewan LEFT JOIN pengguna pg ON t.id_pengguna = pg.id_pengguna ORDER BY t.id_adopsi DESC")->fetchAll(); 
    }

    // Ambil satu transaksi berdasarkan ID dengan data lengkap
    public function getById($id) { 
        $stmt = $this->pdo->prepare("
            SELECT t.*, 
                p.nama as nama_pengadopsi, p.alamat as alamat_adopter, p.no_hp as hp_adopter,
                h.nama_hewan, h.jenis_kelamin, h.estimasi_umur,
                j.nama_jenis as kategori_hewan, r.nama_ras,
                pg.nama_pengguna as nama_staf, pg.jabatan
            FROM transaksi_adopsi t 
            JOIN pengadopsi p ON t.id_pengadopsi = p.id_pengadopsi 
            JOIN hewan h ON t.id_hewan = h.id_hewan 
            JOIN jenis_hewan j ON h.id_jenis = j.id_jenis
            JOIN ras r ON h.id_ras = r.id_ras
            LEFT JOIN pengguna pg ON t.id_pengguna = pg.id_pengguna 
            WHERE t.id_adopsi = ?
        "); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Simpan transaksi adopsi baru (kolom sesuai DB baru)
    public function insert($d) { 
        $stmt = $this->pdo->prepare("INSERT INTO transaksi_adopsi (id_hewan, id_pengadopsi, id_pengguna, tanggal_adopsi, status_kontrak) VALUES (?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $d['id_hewan'],
            $d['id_pengadopsi'],
            empty($d['id_pengguna']) ? null : $d['id_pengguna'],
            $d['tanggal_adopsi'],
            $d['status_kontrak'] ?? 'Draft'
        ]); 
    }

    // Update transaksi adopsi
    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE transaksi_adopsi SET id_hewan=?, id_pengadopsi=?, id_pengguna=?, tanggal_adopsi=?, status_kontrak=? WHERE id_adopsi=?"); 
        return $stmt->execute([
            $d['id_hewan'],
            $d['id_pengadopsi'],
            empty($d['id_pengguna']) ? null : $d['id_pengguna'],
            $d['tanggal_adopsi'],
            $d['status_kontrak'],
            $id
        ]); 
    }

    // Hapus transaksi berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM transaksi_adopsi WHERE id_adopsi = ?"); 
        return $stmt->execute([$id]); 
    }

    // Aktifkan kontrak
    public function activate($id) {
        $stmt = $this->pdo->prepare("UPDATE transaksi_adopsi SET status_kontrak = 'Aktif' WHERE id_adopsi = ?");
        return $stmt->execute([$id]);
    }

    // Ambil pengadopsi yang sudah terverifikasi untuk dropdown
    public function getPengadopsi() { 
        return $this->pdo->query("SELECT id_pengadopsi, nama FROM pengadopsi WHERE status_verifikasi = 'Terverifikasi' ORDER BY nama ASC")->fetchAll(); 
    }

    // Ambil hewan yang tersedia untuk diadopsi
    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan FROM hewan WHERE status_adopsi IN ('Tersedia','Dalam Proses')")->fetchAll(); 
    }

    // Ambil daftar pengguna admin/koordinator untuk dropdown
    public function getPengguna() {
        return $this->pdo->query("SELECT id_pengguna, nama_pengguna FROM pengguna ORDER BY nama_pengguna ASC")->fetchAll();
    }
}
?>