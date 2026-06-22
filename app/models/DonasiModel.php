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
        return $this->pdo->query("SELECT d.*, p.nama AS nama_pengadopsi FROM donasi d LEFT JOIN pengadopsi p ON d.id_pengadopsi = p.id_pengadopsi ORDER BY d.id_donasi DESC")->fetchAll(); 
    }

    // Ambil satu data donasi berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT d.*, p.nama AS nama_pengadopsi FROM donasi d LEFT JOIN pengadopsi p ON d.id_pengadopsi = p.id_pengadopsi WHERE d.id_donasi = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Simpan data donasi baru (kolom sesuai DB baru)
    public function insert($d) { 
        $stmt = $this->pdo->prepare("INSERT INTO donasi (nama_donatur, id_pengadopsi, nominal, kategori, keterangan, tanggal, metode_pembayaran, status_konfirmasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $d['nama_donatur'], 
            !empty($d['id_pengadopsi']) ? $d['id_pengadopsi'] : null,
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
        $stmt = $this->pdo->prepare("UPDATE donasi SET nama_donatur=?, id_pengadopsi=?, nominal=?, kategori=?, keterangan=?, tanggal=?, metode_pembayaran=?, status_konfirmasi=? WHERE id_donasi=?"); 
        return $stmt->execute([
            $d['nama_donatur'], 
            !empty($d['id_pengadopsi']) ? $d['id_pengadopsi'] : null,
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
}
?>