<?php
require_once __DIR__ . '/../../config/connect.php';

class JadwalKunjunganModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua jadwal kunjungan beserta nama pengadopsi, hewan, dan petugas
    public function getAll() { 
        return $this->pdo->query("SELECT j.*, p.nama_lengkap as nama_pengadopsi, h.nama_hewan, u.nama_lengkap as nama_petugas FROM jadwal_kunjungan j JOIN pengadopsi p ON j.id_pengadopsi = p.id_pengadopsi JOIN hewan h ON j.id_hewan = h.id_hewan LEFT JOIN pengguna u ON j.id_pengguna = u.id_pengguna ORDER BY j.tanggal_jadwal DESC")->fetchAll(); 
    }

    // Ambil satu jadwal berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM jadwal_kunjungan WHERE id_jadwal = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Simpan jadwal baru (kolom sesuai DB baru)
    public function insert($d) { 
        $kode = buat_kode_otomatis('jadwal_kunjungan', 'kode_jadwal_kunjungan', 'JK');
        $stmt = $this->pdo->prepare("INSERT INTO jadwal_kunjungan (kode_jadwal_kunjungan, id_pengadopsi, id_hewan, id_pengguna, metode, tanggal_jadwal, alamat_tujuan, status_jadwal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $kode,
            $d['id_pengadopsi'], 
            $d['id_hewan'], 
            empty($d['id_pengguna']) ? null : $d['id_pengguna'],
            $d['metode'],
            $d['tanggal_jadwal'], 
            $d['alamat_tujuan'] ?? null,
            $d['status_jadwal'] ?? 'Menunggu'
        ]); 
    }

    // ponytail: Cek duplikat - adopter + hewan sama dengan status masih aktif
    public function isDuplicate($id_pengadopsi, $id_hewan, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM jadwal_kunjungan WHERE id_pengadopsi = ? AND id_hewan = ? AND status_jadwal IN ('Menunggu','Dikonfirmasi')";
        $params = [$id_pengadopsi, $id_hewan];
        if ($exclude_id) {
            $sql .= " AND id_jadwal != ?";
            $params[] = $exclude_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // Update jadwal kunjungan
    public function update($id, $d) { 
        $stmt = $this->pdo->prepare("UPDATE jadwal_kunjungan SET id_pengadopsi=?, id_hewan=?, id_pengguna=?, metode=?, tanggal_jadwal=?, alamat_tujuan=?, status_jadwal=? WHERE id_jadwal=?"); 
        return $stmt->execute([
            $d['id_pengadopsi'], 
            $d['id_hewan'], 
            empty($d['id_pengguna']) ? null : $d['id_pengguna'],
            $d['metode'],
            $d['tanggal_jadwal'], 
            $d['alamat_tujuan'] ?? null,
            $d['status_jadwal'],
            $id
        ]); 
    }

    // Hapus jadwal berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM jadwal_kunjungan WHERE id_jadwal = ?"); 
        return $stmt->execute([$id]); 
    }

    // ponytail: Ubah status jadwal (Dikonfirmasi / Selesai / Batal)
    public function setStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE jadwal_kunjungan SET status_jadwal = ? WHERE id_jadwal = ?");
        return $stmt->execute([$status, $id]);
    }

    // Ambil daftar pengadopsi untuk dropdown
    public function getPengadopsi() { 
        return $this->pdo->query("SELECT id_pengadopsi, nama_lengkap as nama FROM pengadopsi ORDER BY nama_lengkap ASC")->fetchAll(); 
    }

    // Ambil daftar hewan tersedia untuk dropdown
    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan FROM hewan WHERE status_adopsi = 'Tersedia'")->fetchAll(); 
    }

    // Ambil daftar pengguna (koordinator/perawat) untuk dropdown
    public function getPengguna() {
        return $this->pdo->query("SELECT id_pengguna, nama_pengguna FROM pengguna WHERE role='Pegawai' ORDER BY nama_pengguna ASC")->fetchAll();
    }
}
?>