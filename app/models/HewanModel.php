<?php
require_once __DIR__ . '/../../config/connect.php';

class HewanModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua hewan beserta nama jenis dan ras (JOIN)
    public function getAll() {
        $sql = "SELECT h.*, j.nama_jenis, r.nama_ras 
                FROM hewan h
                JOIN jenis_hewan j ON h.id_jenis = j.id_jenis
                JOIN ras r ON h.id_ras = r.id_ras
                ORDER BY h.id_hewan DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Ambil satu hewan berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM hewan WHERE id_hewan = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // ponytail: Cek duplikat nama hewan dengan jenis & ras yang sama
    public function isDuplicate($nama_hewan, $id_jenis, $id_ras, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM hewan WHERE LOWER(nama_hewan) = LOWER(?) AND id_jenis = ? AND id_ras = ?";
        $params = [$nama_hewan, $id_jenis, $id_ras];
        if ($exclude_id) {
            $sql .= " AND id_hewan != ?";
            $params[] = $exclude_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // Simpan hewan baru (kolom sesuai DB baru)
    public function insert($data) {
        $data['kode_hewan'] = buat_kode_otomatis('hewan', 'kode_hewan', 'HW');
        $data['rekomendasi_adopsi'] = $data['rekomendasi_adopsi'] ?? 0;
        $sql = "INSERT INTO hewan (kode_hewan, id_jenis, id_ras, nama_hewan, jenis_kelamin, estimasi_umur, tanggal_lahir, status_adopsi, rekomendasi_adopsi, sumber_intake, nama_donatur, kontak_donatur, tanggal_intake, keterangan_intake, url_foto_hewan, deskripsi) 
                VALUES (:kode_hewan, :id_jenis, :id_ras, :nama_hewan, :jenis_kelamin, :estimasi_umur, :tanggal_lahir, :status_adopsi, :rekomendasi_adopsi, :sumber_intake, :nama_donatur, :kontak_donatur, :tanggal_intake, :keterangan_intake, :url_foto_hewan, :deskripsi)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    // Update data hewan (kolom sesuai DB baru)
    public function update($id, $data) {
        $sql = "UPDATE hewan SET 
                    id_jenis = :id_jenis, 
                    id_ras = :id_ras, 
                    nama_hewan = :nama_hewan, 
                    jenis_kelamin = :jenis_kelamin, 
                    estimasi_umur = :estimasi_umur, 
                    tanggal_lahir = :tanggal_lahir,
                    status_adopsi = :status_adopsi, 
                    sumber_intake = :sumber_intake,
                    nama_donatur = :nama_donatur,
                    kontak_donatur = :kontak_donatur,
                    tanggal_intake = :tanggal_intake,
                    keterangan_intake = :keterangan_intake,
                    url_foto_hewan = :url_foto_hewan, 
                    deskripsi = :deskripsi 
                WHERE id_hewan = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    // Rekomendasikan hewan untuk diadopsi (Aksi Perawat)
    public function rekomendasikan($id) {
        $stmt = $this->pdo->prepare("UPDATE hewan SET rekomendasi_adopsi = 1 WHERE id_hewan = ?");
        return $stmt->execute([$id]);
    }

    // Setujui rilis hewan ke katalog (Aksi Koordinator)
    public function setujuiRilis($id) {
        $stmt = $this->pdo->prepare("UPDATE hewan SET status_adopsi = 'Tersedia' WHERE id_hewan = ?");
        return $stmt->execute([$id]);
    }

    // Hapus hewan berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM hewan WHERE id_hewan = ?"); 
        return $stmt->execute([$id]); 
    }

    // Ambil daftar jenis hewan untuk dropdown
    public function getOpsiJenis() { 
        return $this->pdo->query("SELECT * FROM jenis_hewan ORDER BY nama_jenis ASC")->fetchAll(); 
    }

    // Ambil daftar ras hewan untuk dropdown
    public function getOpsiRas() { 
        return $this->pdo->query("SELECT * FROM ras ORDER BY nama_ras ASC")->fetchAll(); 
    }
}
?>