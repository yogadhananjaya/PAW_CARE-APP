USE paw_care;

CREATE TABLE IF NOT EXISTS pengadopsi (
    id_pengadopsi INT AUTO_INCREMENT PRIMARY KEY,
    kode_pengadopsi VARCHAR(10) UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    nama_pengguna VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    nik VARCHAR(30) NULL,
    kata_sandi VARCHAR(255) NOT NULL,   
    url_ktp VARCHAR(255) NULL,
    status_verifikasi ENUM('Belum','Menunggu','Terverifikasi','Ditolak') DEFAULT 'Belum',
    tanggal_verifikasi DATE NULL,
    catatan_verifikasi TEXT NULL,
    id_pengguna INT NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS donasi (
    id_donasi INT AUTO_INCREMENT PRIMARY KEY,
    kode_donasi VARCHAR(10) UNIQUE,
    nama_donatur VARCHAR(100) NOT NULL,
    nominal DECIMAL(15,2) NOT NULL,
    kategori ENUM('Pemasukan','Pengeluaran') NOT NULL,
    keterangan VARCHAR(200) NULL,
    tanggal DATE NOT NULL,
    metode_pembayaran ENUM('Transfer Bank','Tunai','E-Wallet') NULL,
    url_bukti VARCHAR(255) NULL,
    status_konfirmasi ENUM('Menunggu','Dikonfirmasi','Ditolak') DEFAULT 'Menunggu'
);

-- 1. Insert Data Jenis Hewan
INSERT INTO jenis_hewan (id_jenis, nama_jenis) VALUES 
(1, 'Kucing'), 
(2, 'Anjing'), 
(3, 'Kelinci');

-- 2. Insert Data Ras Hewan
INSERT INTO ras (id_ras, id_jenis, nama_ras) VALUES 
(1, 1, 'Persia'), 
(2, 1, 'Domestik / Kampung'), 
(3, 1, 'Anggora'),
(4, 2, 'Golden Retriever'), 
(5, 2, 'Pomeranian'), 
(6, 2, 'Siberian Husky'),
(7, 3, 'Anggora Inggris');

-- 3. Insert 10 Data Dummy Hewan (Semua Status 'Tersedia' agar muncul di Katalog)
INSERT INTO hewan (id_jenis, id_ras, nama_hewan, jenis_kelamin, estimasi_umur, status_adopsi, sumber_intake, tanggal_intake, deskripsi) VALUES 
(1, 1, 'Luna', 'Betina', 1, 'Tersedia', 'Donasi', '2025-01-10', 'Kucing persia yang tenang, penurut, dan sangat suka dibelai di area leher.'),
(1, 2, 'Milo', 'Jantan', 1, 'Tersedia', 'Donasi', '2025-02-15', 'Kucing kampung rescue yang sangat aktif, sehat, dan suka mengejar bola kecil.'),
(1, 3, 'Bella', 'Betina', 2, 'Tersedia', 'Breeding', '2024-11-20', 'Kucing anggora cantik dengan bulu lebat. Sudah divaksin lengkap dan ramah anak.'),
(2, 4, 'Rocky', 'Jantan', 3, 'Tersedia', 'Donasi', '2024-08-05', 'Golden retriever pintar yang cocok untuk keluarga. Sangat setia dan mudah dilatih.'),
(2, 5, 'Chiko', 'Jantan', 1, 'Tersedia', 'Breeding', '2025-03-01', 'Anjing ras pomeranian mungil yang menggemaskan, lincah, dan butuh banyak perhatian.'),
(2, 6, 'Max', 'Jantan', 2, 'Tersedia', 'Legacy', '2024-12-10', 'Husky energik bermata biru. Membutuhkan adopter yang memiliki halaman luas untuk berlari.'),
(2, 4, 'Daisy', 'Betina', 1, 'Tersedia', 'Breeding', '2025-04-20', 'Anak anjing Golden yang sangat manis, sehat, dan sedang dalam masa aktif-aktifnya.'),
(1, 2, 'Oreo', 'Jantan', 2, 'Tersedia', 'Donasi', '2025-01-25', 'Kucing domestik dengan corak hitam putih menyerupai Oreo. Suka tidur di tempat hangat.'),
(3, 7, 'Snowball', 'Jantan', 1, 'Tersedia', 'Donasi', '2025-05-01', 'Kelinci putih bersih yang jinak, suka makan wortel, dan sudah terbiasa dengan manusia.'),
(1, 1, 'Mimi', 'Betina', 1, 'Karantina', 'Donasi', '2025-06-01', 'Kitten persia abu-abu yang sangat lucu dan baru saja selesai masa karantina kesehatan.');