CREATE DATABASE IF NOT EXISTS paw_care;
USE paw_care;

CREATE TABLE IF NOT EXISTS pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('SuperAdmin', 'Pegawai', 'User') NOT NULL
);

CREATE TABLE IF NOT EXISTS jenis_hewan (
    id_jenis INT AUTO_INCREMENT PRIMARY KEY,
    nama_jenis VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS ras (
    id_ras INT AUTO_INCREMENT PRIMARY KEY,
    id_jenis INT NOT NULL,
    nama_ras VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_jenis) REFERENCES jenis_hewan(id_jenis) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS kandang (
    id_kandang INT AUTO_INCREMENT PRIMARY KEY,
    nama_kandang VARCHAR(50) NOT NULL,
    kapasitas INT NOT NULL,
    status ENUM('Tersedia', 'Penuh', 'Perbaikan') DEFAULT 'Tersedia'
);

CREATE TABLE IF NOT EXISTS vaksin (
    id_vaksin INT AUTO_INCREMENT PRIMARY KEY,
    nama_vaksin VARCHAR(100) NOT NULL,
    deskripsi TEXT
);

CREATE TABLE IF NOT EXISTS hewan (
    id_hewan INT AUTO_INCREMENT PRIMARY KEY,
    id_jenis INT NOT NULL,
    id_ras INT NOT NULL,
    nama_hewan VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Jantan', 'Betina') NOT NULL,
    umur VARCHAR(50) NOT NULL,
    status ENUM('Karantina', 'Tersedia', 'Diadopsi') DEFAULT 'Karantina',
    foto VARCHAR(255) DEFAULT NULL,
    deskripsi TEXT DEFAULT NULL,
    FOREIGN KEY (id_jenis) REFERENCES jenis_hewan(id_jenis) ON DELETE CASCADE,
    FOREIGN KEY (id_ras) REFERENCES ras(id_ras) ON DELETE CASCADE
);

-- Eksekusi pembuatan akun default di Database
INSERT INTO pengguna (username, password, role) VALUES ('pawcare', 'kelompok5', 'SuperAdmin');