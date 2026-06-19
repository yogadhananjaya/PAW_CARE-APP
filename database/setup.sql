-- ==========================================
-- ⚙️ SETUP AWAL & MEMBERSIHKAN DATABASE
-- ==========================================
CREATE DATABASE IF NOT EXISTS pawcare_db;
USE pawcare_db;

SET FOREIGN_KEY_CHECKS = 0;



-- ==========================================
-- 📦 1. TABEL MASTER (Independen)
-- ==========================================

CREATE TABLE jenis_hewan (
    id_jenis INT AUTO_INCREMENT PRIMARY KEY,
    nama_jenis VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE ras (
    id_ras INT AUTO_INCREMENT PRIMARY KEY,
    id_jenis INT NOT NULL,
    nama_ras VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_jenis) REFERENCES jenis_hewan(id_jenis) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE kandang (
    id_kandang INT AUTO_INCREMENT PRIMARY KEY,
    nama_kandang VARCHAR(50) NOT NULL,
    kapasitas INT NOT NULL,
    lokasi VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE vaksin (
    id_vaksin INT AUTO_INCREMENT PRIMARY KEY,
    nama_vaksin VARCHAR(100) NOT NULL,
    deskripsi TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE pengadopsi (
    id_pengadopsi INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    kata_sandi VARCHAR(255) NOT NULL,
    url_ktp VARCHAR(255) NULL,
    status_verifikasi ENUM('Belum','Menunggu','Terverifikasi','Ditolak') DEFAULT 'Belum',
    tanggal_verifikasi DATE NULL,
    catatan_verifikasi TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    jabatan ENUM('SuperAdmin','Perawat Hewan','Koordinator') NOT NULL,
    kontak VARCHAR(20) NOT NULL,
    nama_pengguna VARCHAR(50) UNIQUE NOT NULL,
    kata_sandi VARCHAR(255) NOT NULL,
    role ENUM('SuperAdmin','Pegawai') NOT NULL
) ENGINE=InnoDB;

CREATE TABLE hewan (
    id_hewan INT AUTO_INCREMENT PRIMARY KEY,
    id_ras INT NOT NULL,
    id_jenis INT NOT NULL,
    nama_hewan VARCHAR(100) NOT NULL,
    umur INT NOT NULL,
    jenis_kelamin ENUM('Jantan','Betina') NOT NULL,
    status_adopsi ENUM('Karantina','Tersedia','Dalam Proses','Diadopsi') DEFAULT 'Tersedia',
    sumber_intake ENUM('Breeding','Donasi','Legacy') NOT NULL,
    nama_donatur VARCHAR(100) NULL,
    kontak_donatur VARCHAR(20) NULL,
    tanggal_intake DATE NOT NULL,
    keterangan_intake TEXT NULL,
    foto_hewan VARCHAR(255) NULL,
    deskripsi TEXT NULL,
    FOREIGN KEY (id_ras) REFERENCES ras(id_ras) ON DELETE CASCADE,
    FOREIGN KEY (id_jenis) REFERENCES jenis_hewan(id_jenis) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE donasi (
    id_donasi INT AUTO_INCREMENT PRIMARY KEY,
    nama_donatur VARCHAR(100) NOT NULL,
    nominal DECIMAL(15,2) NOT NULL,
    kategori ENUM('Pemasukan','Pengeluaran') NOT NULL,
    keterangan VARCHAR(200) NULL,
    tanggal DATE NOT NULL,
    metode_pembayaran VARCHAR(50) NULL,
    url_bukti VARCHAR(255) NULL,
    status_konfirmasi ENUM('Menunggu','Dikonfirmasi','Ditolak') DEFAULT 'Menunggu'
) ENGINE=InnoDB;

-- ==========================================
-- 💳 2. TABEL TRANSAKSI (Dependen/Ketergantungan)
-- ==========================================

CREATE TABLE penempatan_kandang (
    id_penempatan INT AUTO_INCREMENT PRIMARY KEY,
    id_hewan INT NOT NULL,
    id_kandang INT NOT NULL,
    tanggal_masuk DATE NOT NULL,
    tanggal_keluar DATE NULL,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_kandang) REFERENCES kandang(id_kandang) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE riwayat_kesehatan (
    id_riwayat INT AUTO_INCREMENT PRIMARY KEY,
    id_hewan INT NOT NULL,
    id_pengguna INT NOT NULL,
    tipe ENUM('Perawatan','Vaksinasi') NOT NULL,
    id_vaksin INT NULL,
    tanggal DATE NOT NULL,
    deskripsi TEXT NOT NULL,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE,
    FOREIGN KEY (id_vaksin) REFERENCES vaksin(id_vaksin) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE transaksi_adopsi (
    id_adopsi INT AUTO_INCREMENT PRIMARY KEY,
    id_hewan INT NOT NULL,
    id_pengadopsi INT NOT NULL,
    id_pengguna INT NULL,
    tanggal_adopsi DATE NOT NULL,
    status_kontrak ENUM('Draft','Ditandatangani','Aktif') DEFAULT 'Draft',
    ttd_adopter LONGTEXT NULL,
    ttd_admin LONGTEXT NULL,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_pengadopsi) REFERENCES pengadopsi(id_pengadopsi) ON DELETE CASCADE,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE jadwal_kunjungan (
    id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
    id_pengadopsi INT NOT NULL,
    id_hewan INT NOT NULL,
    id_pengguna INT NULL,
    metode ENUM('Kunjungan ke Shelter','Jemput ke Rumah') NOT NULL,
    tanggal_jadwal DATETIME NOT NULL,
    alamat_tujuan TEXT NULL,
    status_jadwal ENUM('Menunggu','Dikonfirmasi','Selesai','Batal') DEFAULT 'Menunggu',
    FOREIGN KEY (id_pengadopsi) REFERENCES pengadopsi(id_pengadopsi) ON DELETE CASCADE,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE SET NULL
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================
-- 🌱 DATA SEEDER AWAL (Default Accounts & Master)
-- ==========================================

-- Data Jenis Hewan
INSERT INTO jenis_hewan (id_jenis, nama_jenis) VALUES 
(1, 'Kucing'),
(2, 'Anjing');

-- Data Ras Hewan
INSERT INTO ras (id_ras, id_jenis, nama_ras) VALUES 
(1, 1, 'Persia'),
(2, 1, 'Anggora'),
(3, 2, 'Golden Retriever'),
(4, 2, 'Bulldog');

-- Data Kandang
INSERT INTO kandang (id_kandang, nama_kandang, kapasitas, lokasi) VALUES 
(1, 'Kandang A-1', 5, 'Shelter Blok A'),
(2, 'Kandang B-2', 3, 'Shelter Blok B');

-- Data Vaksin
INSERT INTO vaksin (id_vaksin, nama_vaksin, deskripsi) VALUES 
(1, 'Rabies', 'Vaksinasi pencegah virus rabies'),
(2, 'Parvovirus', 'Vaksinasi pencegah canine parvovirus');

-- Data Pengguna (Admin/Pegawai)
-- Password default: 'password' -> '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
INSERT INTO pengguna (id_pengguna, nama_lengkap, jabatan, kontak, nama_pengguna, kata_sandi, role) VALUES 
(1, 'Super Admin PawCare', 'SuperAdmin', '081234567890', 'superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'SuperAdmin'),
(2, 'Perawat Andi', 'Perawat Hewan', '081234567891', 'perawat_andi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pegawai');

-- Data Pengadopsi (User/Adopter Test)
INSERT INTO pengadopsi (id_pengadopsi, nama, alamat, no_hp, email, kata_sandi, status_verifikasi) VALUES 
(1, 'Adopter Budi', 'Jl. Kenanga No. 10, Jakarta', '089876543210', 'adopter@pawcare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Terverifikasi');