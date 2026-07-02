CREATE DATABASE IF NOT EXISTS paw_care;
USE paw_care;

CREATE TABLE IF NOT EXISTS pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    kode_pengguna VARCHAR(10) UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    jabatan ENUM('SuperAdmin','Perawat Hewan','Koordinator') NOT NULL,
    kontak VARCHAR(20) NOT NULL,
    nama_pengguna VARCHAR(50) UNIQUE NOT NULL,
    kata_sandi VARCHAR(255) NOT NULL,
    role ENUM('SuperAdmin','User') NOT NULL,
    status ENUM('Aktif','Suspended','Resign') DEFAULT 'Aktif'
);

CREATE TABLE IF NOT EXISTS jenis_hewan (
    id_jenis INT AUTO_INCREMENT PRIMARY KEY,
    kode_jenis_hewan VARCHAR(10) UNIQUE,
    nama_jenis VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS ras (
    id_ras INT AUTO_INCREMENT PRIMARY KEY,
    kode_ras VARCHAR(10) UNIQUE,
    id_jenis INT NOT NULL,
    nama_ras VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_jenis) REFERENCES jenis_hewan(id_jenis) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS kandang (
    id_kandang INT AUTO_INCREMENT PRIMARY KEY,
    kode_kandang VARCHAR(20) UNIQUE NOT NULL,
    nama_kandang VARCHAR(50) NOT NULL,
    id_jenis INT NOT NULL,
    kapasitas INT NOT NULL,
    status ENUM('Tersedia', 'Penuh', 'Perbaikan') DEFAULT 'Tersedia'
);

CREATE TABLE IF NOT EXISTS vaksin (
   id_vaksin INT AUTO_INCREMENT PRIMARY KEY,
    kode_vaksin VARCHAR(10) UNIQUE,
    nama_vaksin VARCHAR(100) NOT NULL,
    deskripsi TEXT NULL,
    status ENUM('Tersedia','Habis','Discontinue') DEFAULT 'Tersedia',
    stok INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS vaksin_jenis (
    id_vaksin INT NOT NULL,
    id_jenis INT NOT NULL,
    PRIMARY KEY (id_vaksin, id_jenis),
    FOREIGN KEY (id_vaksin) REFERENCES vaksin(id_vaksin) ON DELETE CASCADE,
    FOREIGN KEY (id_jenis) REFERENCES jenis_hewan(id_jenis) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS hewan (
    id_hewan INT AUTO_INCREMENT PRIMARY KEY,
    kode_hewan VARCHAR(10) UNIQUE,
    id_ras INT NOT NULL,
    id_jenis INT NOT NULL,
    nama_hewan VARCHAR(100) NOT NULL,
    estimasi_umur INT NOT NULL, 
    jenis_kelamin ENUM('Jantan','Betina') NOT NULL,
    tanggal_lahir DATE NULL, 
    status_adopsi ENUM('Karantina','Tersedia','Dalam Proses','Diadopsi','Meninggal') DEFAULT 'Tersedia',
    rekomendasi_adopsi TINYINT DEFAULT 0,
    sumber_intake ENUM('Breeding','Donasi','Legacy') NOT NULL,
    nama_donatur VARCHAR(100) NULL,
    kontak_donatur VARCHAR(20) NULL,
    tanggal_intake DATE NOT NULL,
    keterangan_intake TEXT NULL,
    url_foto_hewan VARCHAR(255) NULL,
    deskripsi TEXT NULL,
    FOREIGN KEY (id_ras) REFERENCES ras(id_ras) ON DELETE CASCADE,
    FOREIGN KEY (id_jenis) REFERENCES jenis_hewan(id_jenis) ON DELETE CASCADE
);

-- Eksekusi pembuatan akun default di Database
INSERT INTO pengguna (nama_lengkap, jabatan, kontak, nama_pengguna, kata_sandi, role, status) 
VALUES ('Super Admin', 'SuperAdmin', '08000000000', 'pawcare', 'kelompok5', 'SuperAdmin', 'Aktif');

-- Trigger untuk menghitung estimasi_umur otomatis (dalam bulan) saat data dimasukkan
DROP TRIGGER IF EXISTS sebelum_tambah_hewan;
DELIMITER //
CREATE TRIGGER sebelum_tambah_hewan
BEFORE INSERT ON hewan
FOR EACH ROW
BEGIN
    IF NEW.tanggal_lahir IS NOT NULL AND NEW.tanggal_lahir != '0000-00-00' THEN
        SET NEW.estimasi_umur = TIMESTAMPDIFF(MONTH, NEW.tanggal_lahir, CURDATE());
    END IF;
END;
//
DELIMITER ;

-- Trigger untuk menghitung estimasi_umur otomatis (dalam bulan) saat data diubah
DROP TRIGGER IF EXISTS sebelum_ubah_hewan;
DELIMITER //
CREATE TRIGGER sebelum_ubah_hewan
BEFORE UPDATE ON hewan
FOR EACH ROW
BEGIN
    IF NEW.tanggal_lahir IS NOT NULL AND NEW.tanggal_lahir != '0000-00-00' THEN
        SET NEW.estimasi_umur = TIMESTAMPDIFF(MONTH, NEW.tanggal_lahir, CURDATE());
    END IF;
END;
//
DELIMITER ;