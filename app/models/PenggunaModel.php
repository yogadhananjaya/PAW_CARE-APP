<?php
require_once __DIR__ . '/../../config/connect.php';

class PenggunaModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua data pengguna, urutkan dari terbaru
    public function getAll() { 
        return $this->pdo->query("SELECT * FROM pengguna ORDER BY id_pengguna DESC")->fetchAll(); 
    }

    // Ambil satu data pengguna berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // ponytail: nama_pengguna (username) harus unik lintas pengguna dan pengadopsi
    public function isDuplicate($nama_pengguna, $exclude_id = null) {
        // Cek di tabel pengguna
        $sql1 = "SELECT COUNT(*) FROM pengguna WHERE LOWER(nama_pengguna) = LOWER(?)";
        $params1 = [$nama_pengguna];
        if ($exclude_id) {
            $sql1 .= " AND id_pengguna != ?";
            $params1[] = $exclude_id;
        }
        $stmt1 = $this->pdo->prepare($sql1);
        $stmt1->execute($params1);
        if ($stmt1->fetchColumn() > 0) {
            return true;
        }

        // Cek di tabel pengadopsi
        $stmt2 = $this->pdo->prepare("SELECT COUNT(*) FROM pengadopsi WHERE LOWER(nama_pengguna) = LOWER(?)");
        $stmt2->execute([$nama_pengguna]);
        return $stmt2->fetchColumn() > 0;
    }

    // ponytail: nomor kontak (HP) harus unik
    public function isDuplicateKontak($kontak, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM pengguna WHERE kontak = ?";
        $params = [$kontak];
        if ($exclude_id) {
            $sql .= " AND id_pengguna != ?";
            $params[] = $exclude_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // Simpan data pengguna baru (kolom sesuai DB baru)
    public function insert($data) { 
        $kode = buat_kode_otomatis('pengguna', 'kode_pengguna', 'PG');
        $kata_sandi = password_hash($data['kata_sandi'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO pengguna (kode_pengguna, nama_lengkap, jabatan, kontak, nama_pengguna, kata_sandi, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $kode,
            $data['nama_lengkap'], 
            $data['jabatan'], 
            $data['kontak'],
            $data['nama_pengguna'], 
            $kata_sandi, 
            $data['role'],
            $data['status'] ?? 'Aktif'
        ]); 
    }

    // Update data pengguna (jika password diisi baru, hash ulang)
    public function update($id, $data) { 
        if (!empty($data['kata_sandi'])) {
            $kata_sandi = password_hash($data['kata_sandi'], PASSWORD_DEFAULT);
        } else {
            $existing = $this->getById($id);
            $kata_sandi = $existing['kata_sandi'];
        }
        $stmt = $this->pdo->prepare("UPDATE pengguna SET nama_lengkap=?, jabatan=?, kontak=?, nama_pengguna=?, kata_sandi=?, role=?, status=? WHERE id_pengguna=?"); 
        return $stmt->execute([
            $data['nama_lengkap'], 
            $data['jabatan'], 
            $data['kontak'],
            $data['nama_pengguna'], 
            $kata_sandi, 
            $data['role'],
            $data['status'] ?? 'Aktif',
            $id
        ]); 
    }

    // Hapus pengguna berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM pengguna WHERE id_pengguna = ?"); 
        return $stmt->execute([$id]); 
    }
}
?>