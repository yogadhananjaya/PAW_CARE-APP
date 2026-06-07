-- Buat Database
CREATE DATABASE IF NOT EXISTS pawcare_db;
USE pawcare_db;

-- =======================================================
-- TAHAP 1: TABEL MASTER (Tanpa Foreign Key)
-- =======================================================

CREATE TABLE Jenis_Hewan (
    id_jenis INT AUTO_INCREMENT PRIMARY KEY,
    nama_jenis VARCHAR(50) NOT NULL
);

CREATE TABLE Kandang (
    id_kandang INT AUTO_INCREMENT PRIMARY KEY,
    kode_kandang VARCHAR(20) NOT NULL,
    kapasitas INT NOT NULL
);

CREATE TABLE Vaksin (
    id_vaksin INT AUTO_INCREMENT PRIMARY KEY,
    nama_vaksin VARCHAR(100) NOT NULL,
    jadwal VARCHAR(50),
    keterangan VARCHAR(255)
);

CREATE TABLE Pengadopsi (
    id_pengadopsi INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat VARCHAR(255),
    no_hp VARCHAR(20),
    email VARCHAR(100),
    surat_keterangan VARCHAR(255)
);

-- Tabel Master Pegawai
CREATE TABLE Pegawai (
    id_pegawai INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50),
    kontak VARCHAR(20)
);

-- =======================================================
-- TAHAP 2: TABEL MASTER DENGAN FOREIGN KEY
-- =======================================================

CREATE TABLE Ras (
    id_ras INT AUTO_INCREMENT PRIMARY KEY,
    id_jenis INT NOT NULL,
    nama_ras VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_jenis) REFERENCES Jenis_Hewan(id_jenis) ON DELETE CASCADE
);

-- Tabel Master Pengguna (Sudah digabung dengan kolom email, no_telp, dll)
CREATE TABLE Pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    id_pegawai INT NULL,
    nama_pengguna VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NULL,
    kata_sandi VARCHAR(255) NOT NULL,
    no_telp VARCHAR(20) NULL,
    tgl_lahir DATE NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NULL,
    status_pengguna ENUM('Superadmin', 'Staff', 'User') NOT NULL,
    FOREIGN KEY (id_pegawai) REFERENCES Pegawai(id_pegawai) ON DELETE SET NULL
);

-- Tabel Inti Hewan
CREATE TABLE Hewan (
    id_hewan INT AUTO_INCREMENT PRIMARY KEY,
    id_ras INT NOT NULL,
    id_jenis INT NOT NULL,
    nama_hewan VARCHAR(100) NOT NULL,
    tanggal_lahir DATE,
    estimasi_umur INT,
    jenis_kelamin VARCHAR(15),
    status_adopsi VARCHAR(30) DEFAULT 'Tersedia',
    FOREIGN KEY (id_ras) REFERENCES Ras(id_ras),
    FOREIGN KEY (id_jenis) REFERENCES Jenis_Hewan(id_jenis)
);

-- =======================================================
-- TAHAP 3: TABEL TRANSAKSI
-- =======================================================

-- Tabel Transaksi Perawatan
CREATE TABLE Perawatan (
    id_perawatan INT AUTO_INCREMENT PRIMARY KEY,
    id_pegawai INT NOT NULL,
    id_hewan INT NOT NULL,
    perawatan VARCHAR(255),
    pemeriksaan VARCHAR(255),
    pemberian_obat VARCHAR(255),
    tanggal_perawatan DATE NOT NULL,
    FOREIGN KEY (id_pegawai) REFERENCES Pegawai(id_pegawai),
    FOREIGN KEY (id_hewan) REFERENCES Hewan(id_hewan) ON DELETE CASCADE
);

-- Tabel Penempatan Kandang
CREATE TABLE Penempatan_Kandang (
    id_penempatan INT AUTO_INCREMENT PRIMARY KEY,
    id_hewan INT NOT NULL,
    id_kandang INT NOT NULL,
    tanggal_masuk DATE NOT NULL,
    tanggal_keluar DATE,
    FOREIGN KEY (id_hewan) REFERENCES Hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_kandang) REFERENCES Kandang(id_kandang)
);

-- Tabel Vaksinasi
CREATE TABLE Vaksinasi (
    id_vaksinasi INT AUTO_INCREMENT PRIMARY KEY,
    id_hewan INT NOT NULL,
    id_vaksin INT NOT NULL,
    tanggal_vaksin DATE NOT NULL,
    FOREIGN KEY (id_hewan) REFERENCES Hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_vaksin) REFERENCES Vaksin(id_vaksin)
);

-- Tabel Adopsi
CREATE TABLE Adopsi (
    id_adopsi INT AUTO_INCREMENT PRIMARY KEY,
    id_pengadopsi INT NOT NULL,
    id_hewan INT NOT NULL,
    tanggal_adopsi DATE NOT NULL,
    FOREIGN KEY (id_pengadopsi) REFERENCES Pengadopsi(id_pengadopsi),
    FOREIGN KEY (id_hewan) REFERENCES Hewan(id_hewan)
);

-- =======================================================
-- DATA AWAL (SEEDING)
-- =======================================================

-- Tambahan Akun Superadmin Awal untuk Pengembangan (Development)
-- Username: superadmin
-- Password: password
INSERT INTO Pengguna (nama_pengguna, email, kata_sandi, status_pengguna) 
VALUES (
    'superadmin', 
    'admin@pawcare.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'Superadmin'
);
-- Tambahan Akun Staff Awal
-- Username: staff
-- Password: password
INSERT INTO Pengguna (nama_pengguna, email, kata_sandi, status_pengguna) 
VALUES (
    'staff', 
    'staff@pawcare.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'Staff'
);

-- Tambahan Akun User Awal (Pengadopsi)
-- Username: adopter
-- Password: password
INSERT INTO Pengguna (nama_pengguna, email, kata_sandi, no_telp, tgl_lahir, jenis_kelamin, status_pengguna) 
VALUES (
    'adopter', 
    'adopter@pawcare.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    '081234567890',
    '1998-05-15',
    'Laki-laki',
    'User'
);