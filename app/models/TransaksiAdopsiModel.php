<?php
require_once __DIR__ . '/../../config/connect.php';

class TransaksiAdopsiModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    public function getAll() { 
        return $this->pdo->query("SELECT t.*, p.nama_lengkap, h.nama_hewan FROM transaksi_adopsi t JOIN pengadopsi p ON t.id_pengadopsi = p.id_pengadopsi JOIN hewan h ON t.id_hewan = h.id_hewan ORDER BY t.id_transaksi DESC")->fetchAll(); 
    }

    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM transaksi_adopsi WHERE id_transaksi = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    public function insert($d) { 
        $stmt = $this->pdo->prepare("INSERT INTO transaksi_adopsi (id_pengadopsi, id_hewan, tanggal_adopsi, status_adopsi, e_contract) VALUES (?, ?, ?, ?, ?)"); 
        return $stmt->execute([$d['id_pengadopsi'], $d['id_hewan'], $d['tanggal_adopsi'], $d['status_adopsi'], $d['e_contract']]); 
    }

    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE transaksi_adopsi SET id_pengadopsi=?, id_hewan=?, tanggal_adopsi=?, status_adopsi=?, e_contract=? WHERE id_transaksi=?"); 
        return $stmt->execute([$d['id_pengadopsi'], $d['id_hewan'], $d['tanggal_adopsi'], $d['status_adopsi'], $d['e_contract'], $id]); 
    }

    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM transaksi_adopsi WHERE id_transaksi = ?"); 
        return $stmt->execute([$id]); 
    }

    public function getPengadopsi() { 
        // Hanya memunculkan pengadopsi yang sudah terverifikasi KTP-nya
        return $this->pdo->query("SELECT id_pengadopsi, nama_lengkap FROM pengadopsi WHERE status_verifikasi = 'Terverifikasi'")->fetchAll(); 
    }

    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan FROM hewan")->fetchAll(); 
    }
}
?>