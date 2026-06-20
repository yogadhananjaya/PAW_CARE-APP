USE paw_care;

CREATE TABLE IF NOT EXISTS pengadopsi (
    id_pengadopsi INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    nama_lengkap VARCHAR(150) NOT NULL,
    nik VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    status_verifikasi ENUM('Belum', 'Terverifikasi', 'Ditolak') DEFAULT 'Belum',
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS donasi (
    id_donasi INT AUTO_INCREMENT PRIMARY KEY,
    nama_donatur VARCHAR(150) NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('Pending', 'Dikonfirmasi', 'Ditolak') DEFAULT 'Pending'
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
INSERT INTO hewan (id_jenis, id_ras, nama_hewan, jenis_kelamin, umur, status, deskripsi) VALUES 
(1, 1, 'Luna', 'Betina', '1 Tahun', 'Tersedia', 'Kucing persia yang tenang, penurut, dan sangat suka dibelai di area leher.'),
(1, 2, 'Milo', 'Jantan', '6 Bulan', 'Tersedia', 'Kucing kampung rescue yang sangat aktif, sehat, dan suka mengejar bola kecil.'),
(1, 3, 'Bella', 'Betina', '2 Tahun', 'Tersedia', 'Kucing anggora cantik dengan bulu lebat. Sudah divaksin lengkap dan ramah anak.'),
(2, 4, 'Rocky', 'Jantan', '3 Tahun', 'Tersedia', 'Golden retriever pintar yang cocok untuk keluarga. Sangat setia dan mudah dilatih.'),
(2, 5, 'Chiko', 'Jantan', '1 Tahun', 'Tersedia', 'Anjing ras pomeranian mungil yang menggemaskan, lincah, dan butuh banyak perhatian.'),
(2, 6, 'Max', 'Jantan', '2 Tahun', 'Tersedia', 'Husky energik bermata biru. Membutuhkan adopter yang memiliki halaman luas untuk berlari.'),
(2, 4, 'Daisy', 'Betina', '4 Bulan', 'Tersedia', 'Anak anjing Golden yang sangat manis, sehat, dan sedang dalam masa aktif-aktifnya.'),
(1, 2, 'Oreo', 'Jantan', '1.5 Tahun', 'Tersedia', 'Kucing domestik dengan corak hitam putih menyerupai Oreo. Suka tidur di tempat hangat.'),
(3, 7, 'Snowball', 'Jantan', '8 Bulan', 'Tersedia', 'Kelinci putih bersih yang jinak, suka makan wortel, dan sudah terbiasa dengan manusia.'),
(1, 1, 'Mimi', 'Betina', '3 Bulan', 'Tersedia', 'Kitten persia abu-abu yang sangat lucu dan baru saja selesai masa karantina kesehatan.');