USE paw_care;

CREATE TABLE IF NOT EXISTS riwayat_kesehatan (
    id_riwayat INT AUTO_INCREMENT PRIMARY KEY,
    id_hewan INT NOT NULL,
    id_vaksin INT DEFAULT NULL,
    id_pengguna INT NOT NULL, -- Merujuk pada Pegawai/Perawat
    tanggal_periksa DATE NOT NULL,
    diagnosa TEXT NOT NULL,
    tindakan TEXT NOT NULL,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_vaksin) REFERENCES vaksin(id_vaksin) ON DELETE SET NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS penempatan_kandang (
    id_penempatan INT AUTO_INCREMENT PRIMARY KEY,
    id_hewan INT NOT NULL,
    id_kandang INT NOT NULL,
    tanggal_masuk DATE NOT NULL,
    tanggal_keluar DATE DEFAULT NULL,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_kandang) REFERENCES kandang(id_kandang) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS jadwal_kunjungan (
    id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
    id_pengadopsi INT NOT NULL,
    id_hewan INT NOT NULL,
    tanggal_kunjungan DATETIME NOT NULL,
    status ENUM('Menunggu', 'Dikonfirmasi', 'Selesai', 'Dibatalkan') DEFAULT 'Menunggu',
    FOREIGN KEY (id_pengadopsi) REFERENCES pengadopsi(id_pengadopsi) ON DELETE CASCADE,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS transaksi_adopsi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_pengadopsi INT NOT NULL,
    id_hewan INT NOT NULL,
    tanggal_adopsi DATE NOT NULL,
    status_adopsi ENUM('Proses', 'Disetujui', 'Ditolak') DEFAULT 'Proses',
    e_contract VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (id_pengadopsi) REFERENCES pengadopsi(id_pengadopsi) ON DELETE CASCADE,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE
);