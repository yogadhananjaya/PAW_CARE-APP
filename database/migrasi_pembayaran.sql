-- Migration: tabel pembayaran
USE paw_care;

CREATE TABLE IF NOT EXISTS pembayaran (
    id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
    kode_pembayaran VARCHAR(20) UNIQUE,
    id_pengadopsi INT NULL,
    metode VARCHAR(50) NOT NULL,
    provider VARCHAR(50) NULL,
    reference VARCHAR(100) UNIQUE,
    amount DECIMAL(15,2) NOT NULL,
    status ENUM('Pending','Success','Failed','Expired') DEFAULT 'Pending',
    metadata JSON NULL,
    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME NULL,
    FOREIGN KEY (id_pengadopsi) REFERENCES pengadopsi(id_pengadopsi) ON DELETE SET NULL
);
