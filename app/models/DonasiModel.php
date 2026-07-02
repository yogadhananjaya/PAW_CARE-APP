<?php
require_once __DIR__ . '/../../config/connect.php';

class DonasiModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua data donasi, urutkan dari terbaru
    public function getAll() { 
        return $this->pdo->query("SELECT * FROM donasi ORDER BY id_donasi DESC")->fetchAll(); 
    }

    // Ambil satu data donasi berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM donasi WHERE id_donasi = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Simpan data donasi baru (kolom sesuai DB baru)
    public function insert($d) { 
        $kode = buat_kode_otomatis('donasi', 'kode_donasi', 'DN');
        $stmt = $this->pdo->prepare("INSERT INTO donasi (kode_donasi, nama_donatur, nominal, kategori, keterangan, tanggal, metode_pembayaran, status_konfirmasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $kode,
            $d['nama_donatur'], 
            $d['nominal'], 
            $d['kategori'],
            $d['keterangan'] ?? null,
            $d['tanggal'], 
            $d['metode_pembayaran'] ?? null,
            $d['status_konfirmasi'] ?? 'Menunggu'
        ]); 
    }

    // Update data donasi
    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE donasi SET nama_donatur=?, nominal=?, kategori=?, keterangan=?, tanggal=?, metode_pembayaran=?, status_konfirmasi=? WHERE id_donasi=?"); 
        return $stmt->execute([
            $d['nama_donatur'], 
            $d['nominal'], 
            $d['kategori'],
            $d['keterangan'] ?? null,
            $d['tanggal'], 
            $d['metode_pembayaran'] ?? null,
            $d['status_konfirmasi'],
            $id
        ]); 
    }

    // Hapus donasi berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM donasi WHERE id_donasi = ?"); 
        return $stmt->execute([$id]); 
    }

    // Perbarui status konfirmasi donasi (Gaya pemula)
    public function updateStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE donasi SET status_konfirmasi = ? WHERE id_donasi = ?");
        $hasil = $stmt->execute([$status, $id]);
        return $hasil;
    }
}
?>