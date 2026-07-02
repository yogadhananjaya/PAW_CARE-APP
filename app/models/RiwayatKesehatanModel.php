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
        return $this->pdo->query("SELECT r.*, h.nama_hewan, jh.nama_jenis, v.nama_vaksin, p.nama_pengguna as perawat FROM riwayat_kesehatan r JOIN hewan h ON r.id_hewan = h.id_hewan LEFT JOIN jenis_hewan jh ON h.id_jenis = jh.id_jenis LEFT JOIN vaksin v ON r.id_vaksin = v.id_vaksin JOIN pengguna p ON r.id_pengguna = p.id_pengguna WHERE r.deleted_at IS NULL ORDER BY r.id_riwayat DESC")->fetchAll(); 
    }

    // Ambil rekam medis yang sudah dibatalkan (soft-deleted) untuk ditampilkan di riwayat
    public function getDeleted() {
        return $this->pdo->query("SELECT r.*, h.nama_hewan, jh.nama_jenis, v.nama_vaksin, p.nama_pengguna as perawat FROM riwayat_kesehatan r JOIN hewan h ON r.id_hewan = h.id_hewan LEFT JOIN jenis_hewan jh ON h.id_jenis = jh.id_jenis LEFT JOIN vaksin v ON r.id_vaksin = v.id_vaksin JOIN pengguna p ON r.id_pengguna = p.id_pengguna WHERE r.deleted_at IS NOT NULL ORDER BY r.deleted_at DESC")->fetchAll();
    }

    // Ambil satu riwayat berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM riwayat_kesehatan WHERE id_riwayat = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Cek duplikat: Vaksinasi = hewan+vaksin+tanggal, Perawatan/Karantina = hewan+tipe+tanggal
    public function isDuplicate($id_hewan, $tipe, $id_vaksin, $tanggal, $exclude_id = null) {
        if ($tipe === 'Vaksinasi' && !empty($id_vaksin)) {
            $sql = "SELECT COUNT(*) FROM riwayat_kesehatan WHERE id_hewan = ? AND tipe = 'Vaksinasi' AND id_vaksin = ? AND tanggal = ? AND deleted_at IS NULL";
            $params = [$id_hewan, $id_vaksin, $tanggal];
        } else {
            $sql = "SELECT COUNT(*) FROM riwayat_kesehatan WHERE id_hewan = ? AND tipe = ? AND tanggal = ? AND deleted_at IS NULL";
            $params = [$id_hewan, $tipe, $tanggal];
        }
        if ($exclude_id) {
            $sql .= " AND id_riwayat != ?";
            $params[] = $exclude_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // Cek prasyarat: sebelum Vaksinasi, hewan harus sudah punya Perawatan dulu
    public function perluPerawatanDulu($id_hewan) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM riwayat_kesehatan WHERE id_hewan = ? AND tipe = 'Perawatan' AND deleted_at IS NULL");
        $stmt->execute([$id_hewan]);
        return $stmt->fetchColumn() == 0;
    }

    // Cek prasyarat: sebelum Karantina Selesai, hewan harus sudah punya Vaksinasi dulu
    public function perluVaksinasiDulu($id_hewan) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM riwayat_kesehatan WHERE id_hewan = ? AND tipe = 'Vaksinasi' AND deleted_at IS NULL");
        $stmt->execute([$id_hewan]);
        return $stmt->fetchColumn() == 0;
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

    // Soft delete — tandai deleted_at, data tidak benar-benar hilang dari DB
    public function delete($id) { 
        $stmt = $this->pdo->prepare("UPDATE riwayat_kesehatan SET deleted_at = NOW() WHERE id_riwayat = ?"); 
        return $stmt->execute([$id]); 
    }

    // Cek apakah user boleh edit/hapus (hanya PIC yang membuat catatan)
    // SuperAdmin bypass semua batasan
    public function canModify($record, $user_id, $role) {
        if ($role === 'SuperAdmin') {
            return true;
        }
        if ((int)$record['id_pengguna'] === (int)$user_id) {
            return true;
        } else {
            return false;
        }
    }

    // Ambil daftar hewan untuk dropdown
    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan, id_jenis FROM hewan")->fetchAll(); 
    }

    // Ambil daftar vaksin untuk dropdown (semua status, Habis/Discontinue disabled)
    public function getVaksin() { 
        return $this->pdo->query("
            SELECT v.id_vaksin, v.nama_vaksin, v.status, v.stok, GROUP_CONCAT(vj.id_jenis) as id_jenis_list
            FROM vaksin v
            LEFT JOIN vaksin_jenis vj ON v.id_vaksin = vj.id_vaksin
            GROUP BY v.id_vaksin
            ORDER BY v.status='Tersedia' DESC, v.nama_vaksin ASC
        ")->fetchAll(); 
    }

    // Ambil daftar perawat (hanya yang memiliki jabatan Perawat Hewan)
    public function getPerawat() { 
        return $this->pdo->query("SELECT id_pengguna, nama_pengguna FROM pengguna WHERE jabatan = 'Perawat Hewan'")->fetchAll(); 
    }

    // ponytail: after Karantina Selesai, set rekomendasi untuk approval Koordinator
    public function rilisKarantina($id_hewan) {
        $stmt = $this->pdo->prepare("UPDATE hewan SET rekomendasi_adopsi = 1 WHERE id_hewan = ?");
        return $stmt->execute([$id_hewan]);
    }
}
?>