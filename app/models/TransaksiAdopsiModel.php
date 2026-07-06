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
        return $this->pdo->query("SELECT t.*, p.nama_lengkap as nama_pengadopsi, h.nama_hewan, pg.nama_pengguna as nama_staf FROM transaksi_adopsi t JOIN pengadopsi p ON t.id_pengadopsi = p.id_pengadopsi JOIN hewan h ON t.id_hewan = h.id_hewan LEFT JOIN pengguna pg ON t.id_pengguna = pg.id_pengguna ORDER BY t.id_adopsi DESC")->fetchAll(); 
    }

    // Ambil satu transaksi berdasarkan ID dengan data lengkap
    public function getById($id) { 
        $stmt = $this->pdo->prepare("
            SELECT t.*, 
                p.nama_lengkap as nama_pengadopsi, p.alamat as alamat_adopter, p.no_hp as hp_adopter,
                h.nama_hewan, h.jenis_kelamin, h.estimasi_umur,
                j.nama_jenis as kategori_hewan, r.nama_ras,
                pg.nama_pengguna as nama_staf, pg.jabatan,
                jk.status_jadwal
            FROM transaksi_adopsi t 
            JOIN pengadopsi p ON t.id_pengadopsi = p.id_pengadopsi 
            JOIN hewan h ON t.id_hewan = h.id_hewan 
            JOIN jenis_hewan j ON h.id_jenis = j.id_jenis
            JOIN ras r ON h.id_ras = r.id_ras
            LEFT JOIN pengguna pg ON t.id_pengguna = pg.id_pengguna
            LEFT JOIN jadwal_kunjungan jk ON t.id_pengadopsi = jk.id_pengadopsi AND t.id_hewan = jk.id_hewan
            WHERE t.id_adopsi = ?
            ORDER BY jk.id_jadwal DESC LIMIT 1
        "); 
        $stmt->execute([$id]); 
        return $stmt->fetch(); 
    }

    // Simpan transaksi adopsi baru (kolom sesuai DB baru)
    public function insert($d) { 
        $kode = buat_kode_otomatis('transaksi_adopsi', 'kode_transaksi_adopsi', 'TA');
        
        //   validasi agar id_pengguna yang dimasukkan benar-benar ada di db
        $id_pengguna = null;
        if (!empty($d['id_pengguna'])) {
            $check = $this->pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE id_pengguna = ?");
            $check->execute([$d['id_pengguna']]);
            if ($check->fetchColumn() > 0) {
                $id_pengguna = $d['id_pengguna'];
            }
        }

        $stmt = $this->pdo->prepare("INSERT INTO transaksi_adopsi (kode_transaksi_adopsi, id_hewan, id_pengadopsi, id_pengguna, tanggal_adopsi, status_kontrak) VALUES (?, ?, ?, ?, ?, ?)"); 
        return $stmt->execute([
            $kode,
            $d['id_hewan'],
            $d['id_pengadopsi'],
            $id_pengguna,
            $d['tanggal_adopsi'],
            $d['status_kontrak'] ?? 'Draft'
        ]); 
    }

    //   Cek duplikat - adopter + hewan sama dengan kontrak masih aktif
    public function isDuplicate($id_hewan, $id_pengadopsi, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM transaksi_adopsi WHERE id_hewan = ? AND id_pengadopsi = ? AND status_kontrak IN ('Draft','Aktif')";
        $params = [$id_hewan, $id_pengadopsi];
        if ($exclude_id) {
            $sql .= " AND id_adopsi != ?";
            $params[] = $exclude_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // Update transaksi adopsi
    public function update($id, $d) { 
        //   validasi agar id_pengguna yang dimasukkan benar-benar ada di db
        $id_pengguna = null;
        if (!empty($d['id_pengguna'])) {
            $check = $this->pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE id_pengguna = ?");
            $check->execute([$d['id_pengguna']]);
            if ($check->fetchColumn() > 0) {
                $id_pengguna = $d['id_pengguna'];
            }
        }

        $stmt = $this->pdo->prepare("UPDATE transaksi_adopsi SET id_hewan=?, id_pengadopsi=?, id_pengguna=?, tanggal_adopsi=?, status_kontrak=? WHERE id_adopsi=?"); 
        return $stmt->execute([
            $d['id_hewan'],
            $d['id_pengadopsi'],
            $id_pengguna,
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

    // Aktifkan kontrak dan ubah status hewan menjadi Diadopsi
    public function activate($id) {
        $stmt = $this->pdo->prepare("UPDATE transaksi_adopsi SET status_kontrak = 'Aktif' WHERE id_adopsi = ?");
        $result = $stmt->execute([$id]);
        // Update status hewan menjadi final
        $stmt_h = $this->pdo->prepare("UPDATE hewan h JOIN transaksi_adopsi t ON h.id_hewan = t.id_hewan SET h.status_adopsi = 'Diadopsi' WHERE t.id_adopsi = ?");
        $stmt_h->execute([$id]);
        return $result;
    }

    // Ambil pengadopsi yang sudah terverifikasi dan "nganggur" (tidak memegang transaksi aktif Draft/Aktif)
    public function getPengadopsi() { 
        return $this->pdo->query("SELECT id_pengadopsi, nama_lengkap as nama FROM pengadopsi WHERE status_verifikasi = 'Terverifikasi' AND id_pengadopsi NOT IN (SELECT id_pengadopsi FROM transaksi_adopsi WHERE status_kontrak IN ('Draft', 'Aktif')) ORDER BY nama_lengkap ASC")->fetchAll(); 
    }

    // Ambil hewan yang tersedia dan tidak terikat transaksi aktif (Draft/Aktif)
    public function getHewan() { 
        return $this->pdo->query("SELECT id_hewan, nama_hewan FROM hewan WHERE status_adopsi IN ('Tersedia','Dalam Proses') AND id_hewan NOT IN (SELECT id_hewan FROM transaksi_adopsi WHERE status_kontrak IN ('Draft', 'Aktif'))")->fetchAll(); 
    }

    // Ambil daftar pengguna admin/koordinator untuk dropdown
    public function getPengguna() {
        return $this->pdo->query("SELECT id_pengguna, nama_pengguna FROM pengguna ORDER BY nama_pengguna ASC")->fetchAll();
    }

    // Ambil ID Koordinator pertama dari database
    public function getFirstKoordinatorId() {
        return $this->pdo->query("SELECT id_pengguna FROM pengguna WHERE jabatan = 'Koordinator' LIMIT 1")->fetchColumn();
    }

    // Ambil daftar Koordinator yang "nganggur" (tidak memegang transaksi aktif Draft/Aktif)
    public function getCoordinators() {
        return $this->pdo->query("SELECT id_pengguna, nama_lengkap, nama_pengguna FROM pengguna WHERE jabatan = 'Koordinator' AND id_pengguna NOT IN (SELECT id_pengguna FROM transaksi_adopsi WHERE status_kontrak IN ('Draft', 'Aktif') AND id_pengguna IS NOT NULL) ORDER BY nama_lengkap ASC")->fetchAll();
    }

    //   Mengubah status kontrak adopsi secara langsung
    public function setStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE transaksi_adopsi SET status_kontrak = ? WHERE id_adopsi = ?");
        $success = $stmt->execute([$status, $id]);
        
        if ($success && $status === 'Batal') {
            // Dapatkan id_hewan dari transaksi ini
            $stmt_hewan = $this->pdo->prepare("SELECT id_hewan FROM transaksi_adopsi WHERE id_adopsi = ?");
            $stmt_hewan->execute([$id]);
            $id_hewan = $stmt_hewan->fetchColumn();
            
            if ($id_hewan) {
                // Kembalikan status hewan menjadi Tersedia
                $this->pdo->prepare("UPDATE hewan SET status_adopsi = 'Tersedia' WHERE id_hewan = ?")->execute([$id_hewan]);
            }
        }
        return $success;
    }

    //   Menyimpan tanda tangan digital Admin/Koordinator
    public function saveAdminSignature($id, $ttd_base64) {
        $stmt = $this->pdo->prepare("UPDATE transaksi_adopsi SET ttd_admin = ? WHERE id_adopsi = ?");
        return $stmt->execute([$ttd_base64, $id]);
    }

    //   Assign koordinator ke transaksi (first-come-first-serve)
    public function assignKoordinator($id, $id_pengguna) {
        $stmt = $this->pdo->prepare("UPDATE transaksi_adopsi SET id_pengguna = ? WHERE id_adopsi = ? AND id_pengguna IS NULL");
        return $stmt->execute([$id_pengguna, $id]);
    }
}
?>