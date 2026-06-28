<?php
require_once __DIR__ . '/../../config/connect.php';

class RiwayatKesehatanModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua riwayat kesehatan — hanya yang belum soft-deleted
    public function getAll() { 
        return $this->pdo->query("SELECT r.*, h.nama_hewan, v.nama_vaksin, p.nama_pengguna as perawat FROM riwayat_kesehatan r JOIN hewan h ON r.id_hewan = h.id_hewan LEFT JOIN vaksin v ON r.id_vaksin = v.id_vaksin JOIN pengguna p ON r.id_pengguna = p.id_pengguna WHERE r.deleted_at IS NULL ORDER BY r.id_riwayat DESC")->fetchAll(); 
    }

    // ponytail: Ambil rekam medis yang sudah dibatalkan (soft-deleted) untuk ditampilkan di riwayat
    public function getDeleted() {
        return $this->pdo->query("SELECT r.*, h.nama_hewan, v.nama_vaksin, p.nama_pengguna as perawat FROM riwayat_kesehatan r JOIN hewan h ON r.id_hewan = h.id_hewan LEFT JOIN vaksin v ON r.id_vaksin = v.id_vaksin JOIN pengguna p ON r.id_pengguna = p.id_pengguna WHERE r.deleted_at IS NOT NULL ORDER BY r.deleted_at DESC")->fetchAll();
    }

    // Ambil satu riwayat berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM riwayat_kesehatan WHERE id_riwayat = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Simpan riwayat baru — created_at diisi otomatis NOW()
    public function insert($d) { 
        $kode = buat_kode_otomatis('riwayat_kesehatan', 'kode_riwayat_kesehatan', 'RK');
        $stmt = $this->pdo->prepare("INSERT INTO riwayat_kesehatan (kode_riwayat_kesehatan, id_hewan, id_pengguna, tipe, id_vaksin, tanggal, deskripsi, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"); 
        return $stmt->execute([
            $kode,
            $d['id_hewan'], 
            $d['id_pengguna'],
            $d['tipe'],
            empty($d['id_vaksin']) ? null : $d['id_vaksin'], 
            $d['tanggal'], 
            $d['deskripsi']
        ]); 
    }

    // Update riwayat kesehatan
    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE riwayat_kesehatan SET id_hewan=?, id_pengguna=?, tipe=?, id_vaksin=?, tanggal=?, deskripsi=? WHERE id_riwayat=?"); 
        return $stmt->execute([
            $d['id_hewan'], 
            $d['id_pengguna'],
            $d['tipe'],
            empty($d['id_vaksin']) ? null : $d['id_vaksin'], 
            $d['tanggal'], 
            $d['deskripsi'],
            $id
        ]); 
    }

    // ponytail: Soft delete — tandai deleted_at, data tidak benar-benar hilang dari DB
    public function delete($id) { 
        $stmt = $this->pdo->prepare("UPDATE riwayat_kesehatan SET deleted_at = NOW() WHERE id_riwayat = ?"); 
        return $stmt->execute([$id]); 
    }

    // ponytail: Cek apakah user boleh edit/hapus (pemilik catatan + dalam 24 jam)
    // SuperAdmin bypass semua batasan
    public function canModify($record, $user_id, $role) {
        if ($role === 'SuperAdmin') return true;
        if ((int)$record['id_pengguna'] !== (int)$user_id) return false;
        $created = strtotime($record['created_at']);
        return (time() - $created) < 86400; // 86400 detik = 24 jam
    }

    // Ambil daftar hewan untuk dropdown
    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan FROM hewan")->fetchAll(); 
    }

    // Ambil daftar vaksin untuk dropdown
    public function getVaksin() { 
        return $this->pdo->query("SELECT id_vaksin, nama_vaksin FROM vaksin WHERE status='Tersedia'")->fetchAll(); 
    }

    // Ambil daftar perawat (pengguna yang bisa merawat)
    public function getPerawat() { 
        return $this->pdo->query("SELECT id_pengguna, nama_pengguna FROM pengguna WHERE role IN ('SuperAdmin','Pegawai')")->fetchAll(); 
    }
}
?>