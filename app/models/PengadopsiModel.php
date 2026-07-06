<?php
require_once __DIR__ . '/../../config/connect.php';

class PengadopsiModel {
    private $pdo;

    public function __construct() { 
        global $pdo; 
        $this->pdo = $pdo; 
    }

    // Ambil semua data pengadopsi, urutkan dari terbaru
    public function getAll() { 
        return $this->pdo->query("SELECT * FROM pengadopsi ORDER BY id_pengadopsi DESC")->fetchAll(); 
    }

    //   Ambil pengadopsi yang sudah terverifikasi saja
    public function getVerifiedAdopters() {
        return $this->pdo->query("SELECT * FROM pengadopsi WHERE status_verifikasi = 'Terverifikasi' ORDER BY nama_lengkap ASC")->fetchAll();
    }

    // Ambil satu data pengadopsi berdasarkan ID
    public function getById($id) { 
        $stmt = $this->pdo->prepare("SELECT * FROM pengadopsi WHERE id_pengadopsi = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Cek apakah pengadopsi boleh mengajukan adopsi lagi (belum melakukan adopsi dalam 1 bulan)
    public function canAdoptAgain($id_pengadopsi) {
        $stmt = $this->pdo->prepare("SELECT tanggal_adopsi FROM transaksi_adopsi WHERE id_pengadopsi = ? AND status_kontrak IN ('Ditandatangani', 'Aktif') AND tanggal_adopsi >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ORDER BY tanggal_adopsi DESC LIMIT 1");
        $stmt->execute([$id_pengadopsi]);
        $row = $stmt->fetch();
        return $row ? false : true; // false = harus menunggu
    }

    // Ambil data pengadopsi berdasarkan email
    public function getByEmail($email) { 
        $stmt = $this->pdo->prepare("SELECT * FROM pengadopsi WHERE email = ?"); 
        $stmt->execute([$email]); 
        return $stmt->fetch(); 
    }

    private function createLinkedPengguna($pengadopsi_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pengadopsi WHERE id_pengadopsi = ?");
        $stmt->execute([$pengadopsi_id]);
        $adopter = $stmt->fetch();

        if (!$adopter || $adopter['status_verifikasi'] !== 'Terverifikasi' || !empty($adopter['id_pengguna'])) {
            return false;
        }

        $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE LOWER(nama_pengguna) = LOWER(?)");
        $stmtCheck->execute([$adopter['nama_pengguna']]);
        if ($stmtCheck->fetchColumn() > 0) {
            return false;
        }

        $kode = buat_kode_otomatis('pengguna', 'kode_pengguna', 'PG');
        $stmtInsert = $this->pdo->prepare("INSERT INTO pengguna (kode_pengguna, nama_lengkap, jabatan, kontak, nama_pengguna, kata_sandi, role, status) VALUES (?, ?, 'Pengadopsi', ?, ?, ?, 'User', 'Aktif')");
        $success = $stmtInsert->execute([
            $kode,
            $adopter['nama_lengkap'],
            $adopter['no_hp'],
            $adopter['nama_pengguna'],
            $adopter['kata_sandi']
        ]);

        if ($success) {
            $new_id_pengguna = $this->pdo->lastInsertId();
            $stmtUpdate = $this->pdo->prepare("UPDATE pengadopsi SET id_pengguna = ? WHERE id_pengadopsi = ?");
            $stmtUpdate->execute([$new_id_pengguna, $pengadopsi_id]);
            return true;
        }

        return false;
    }

    // Simpan data pengadopsi baru (kolom sesuai DB baru)
    public function insert($d) { 
        $kode = buat_kode_otomatis('pengadopsi', 'kode_pengadopsi', 'AD');
        $kata_sandi = password_hash($d['kata_sandi'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO pengadopsi (kode_pengadopsi, nama_lengkap, nama_pengguna, alamat, no_hp, email, kata_sandi, status_verifikasi, url_ktp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
        $success = $stmt->execute([
            $kode,
            $d['nama_lengkap'], 
            $d['nama_pengguna'], 
            $d['alamat'], 
            $d['no_hp'], 
            $d['email'], 
            $kata_sandi,
            $d['status_verifikasi'] ?? 'Belum',
            $d['url_ktp'] ?? null
        ]);

        if ($success) {
            $lastId = $this->pdo->lastInsertId();
            if (($d['status_verifikasi'] ?? 'Belum') === 'Terverifikasi') {
                $this->createLinkedPengguna($lastId);
            }
        }

        return $success;
    }

    // Update data pengadopsi
    public function update($id, $d) { 
        if (!empty($d['kata_sandi'])) {
            $kata_sandi = password_hash($d['kata_sandi'], PASSWORD_DEFAULT);
        } else {
            $existing = $this->getById($id);
            $kata_sandi = $existing['kata_sandi'];
        }
        $stmt = $this->pdo->prepare("UPDATE pengadopsi SET nama_lengkap=?, nama_pengguna=?, alamat=?, no_hp=?, email=?, kata_sandi=?, status_verifikasi=?, tanggal_verifikasi=?, catatan_verifikasi=?, url_ktp=? WHERE id_pengadopsi=?"); 
        $success = $stmt->execute([
            $d['nama_lengkap'], 
            $d['nama_pengguna'], 
            $d['alamat'], 
            $d['no_hp'], 
            $d['email'], 
            $kata_sandi,
            $d['status_verifikasi'],
            empty($d['tanggal_verifikasi']) ? null : $d['tanggal_verifikasi'],
            $d['catatan_verifikasi'] ?? null,
            $d['url_ktp'] ?? null,
            $id
        ]);

        if ($success && $d['status_verifikasi'] === 'Terverifikasi') {
            $this->createLinkedPengguna($id);
        }

        return $success;
    }

    public function isUsed($id) {
        $stmt1 = $this->pdo->prepare("SELECT COUNT(*) FROM transaksi_adopsi WHERE id_pengadopsi = ? AND status_kontrak != 'Batal'");
        $stmt1->execute([$id]);
        if ($stmt1->fetchColumn() > 0) return true;

        $stmt2 = $this->pdo->prepare("SELECT COUNT(*) FROM jadwal_kunjungan WHERE id_pengadopsi = ? AND status_jadwal != 'Batal'");
        $stmt2->execute([$id]);
        if ($stmt2->fetchColumn() > 0) return true;

        return false;
    }

    // Hapus pengadopsi berdasarkan ID
    public function delete($id) { 
        $stmt = $this->pdo->prepare("DELETE FROM pengadopsi WHERE id_pengadopsi = ?"); 
        return $stmt->execute([$id]); 
    }

    //   nama_pengguna (username) harus unik lintas pengadopsi dan pengguna
    public function isDuplicateUsername($nama_pengguna, $exclude_id = null) {
        // Cek di tabel pengadopsi
        $sql1 = "SELECT COUNT(*) FROM pengadopsi WHERE LOWER(nama_pengguna) = LOWER(?)";
        $params1 = [$nama_pengguna];
        if ($exclude_id) {
            $sql1 .= " AND id_pengadopsi != ?";
            $params1[] = $exclude_id;
        }
        $stmt1 = $this->pdo->prepare($sql1);
        $stmt1->execute($params1);
        if ($stmt1->fetchColumn() > 0) {
            return true;
        }

        // Cek di tabel pengguna
        $stmt2 = $this->pdo->prepare("SELECT id_pengguna FROM pengguna WHERE LOWER(nama_pengguna) = LOWER(?)");
        $stmt2->execute([$nama_pengguna]);
        $existing_pengguna_id = $stmt2->fetchColumn();
        if (!$existing_pengguna_id) {
            return false;
        }

        if ($exclude_id) {
            $stmt3 = $this->pdo->prepare("SELECT id_pengguna FROM pengadopsi WHERE id_pengadopsi = ?");
            $stmt3->execute([$exclude_id]);
            $linked_pengguna_id = $stmt3->fetchColumn();
            if ($linked_pengguna_id && $linked_pengguna_id == $existing_pengguna_id) {
                return false;
            }
        }

        return true;
    }
}
?>