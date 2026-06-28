USE paw_care;

CREATE TABLE IF NOT EXISTS riwayat_kesehatan (
    id_riwayat INT AUTO_INCREMENT PRIMARY KEY,
    kode_riwayat_kesehatan VARCHAR(10) UNIQUE,
    id_hewan INT NOT NULL,
    id_pengguna INT NOT NULL,
    tipe ENUM('Perawatan','Vaksinasi') NOT NULL,
    id_vaksin INT NULL,
    tanggal DATE NOT NULL,
    deskripsi TEXT NOT NULL,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE,
    FOREIGN KEY (id_vaksin) REFERENCES vaksin(id_vaksin) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS penempatan_kandang (
    id_penempatan INT AUTO_INCREMENT PRIMARY KEY,
    kode_penempatan_kandang VARCHAR(10) UNIQUE,
    id_hewan INT NOT NULL,
    id_kandang INT NOT NULL,
    tanggal_masuk DATE NOT NULL,
    tanggal_keluar DATE NULL,
    status ENUM('Aktif','Riwayat') DEFAULT 'Aktif', 
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_kandang) REFERENCES kandang(id_kandang) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS jadwal_kunjungan (
    id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
    kode_jadwal_kunjungan VARCHAR(10) UNIQUE,
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
);

CREATE TABLE IF NOT EXISTS transaksi_adopsi (
    id_adopsi INT AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi_adopsi VARCHAR(10) UNIQUE,
    id_hewan INT NOT NULL,
    id_pengadopsi INT NOT NULL,
    id_pengguna INT NULL,
    tanggal_adopsi DATE NOT NULL,
    status_kontrak ENUM('Draft','Ditandatangani','Aktif','Batal') DEFAULT 'Draft',
    ttd_adopter LONGTEXT NULL,
    ttd_admin LONGTEXT NULL,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_pengadopsi) REFERENCES pengadopsi(id_pengadopsi) ON DELETE CASCADE,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE SET NULL
);