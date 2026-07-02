<?php
require_once __DIR__ . '/../config/connect.php';

echo "=== MEMULAI PENGISIAN DATA DUMMY (SEEDER LENGKAP) ===\n";

try {
    // Matikan foreign key checks sementara untuk membersihkan tabel
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Kosongkan semua tabel utama dan transaksi
    $pdo->exec("TRUNCATE TABLE donasi");
    $pdo->exec("TRUNCATE TABLE transaksi_adopsi");
    $pdo->exec("TRUNCATE TABLE jadwal_kunjungan");
    $pdo->exec("TRUNCATE TABLE penempatan_kandang");
    $pdo->exec("TRUNCATE TABLE riwayat_kesehatan");
    $pdo->exec("TRUNCATE TABLE hewan");
    $pdo->exec("TRUNCATE TABLE vaksin");
    $pdo->exec("TRUNCATE TABLE kandang");
    $pdo->exec("TRUNCATE TABLE ras");
    $pdo->exec("TRUNCATE TABLE jenis_hewan");
    $pdo->exec("TRUNCATE TABLE pengadopsi");
    $pdo->exec("TRUNCATE TABLE pengguna");
    
    echo "Semua tabel berhasil dikosongkan.\n";

    // 1. Insert Pengguna (Staff/Admin)
    $pengguna = [
        ['nama_lengkap' => 'Super Admin', 'jabatan' => 'SuperAdmin', 'kontak' => '08000000000', 'nama_pengguna' => 'pawcare', 'kata_sandi' => 'kelompok5', 'role' => 'SuperAdmin', 'status' => 'Aktif'],
        ['nama_lengkap' => 'Budi Perawat', 'jabatan' => 'Perawat Hewan', 'kontak' => '08123456789', 'nama_pengguna' => 'budi', 'kata_sandi' => 'password123', 'role' => 'Pegawai', 'status' => 'Aktif'],
        ['nama_lengkap' => 'Siti Koordinator', 'jabatan' => 'Koordinator', 'kontak' => '08987654321', 'nama_pengguna' => 'siti', 'kata_sandi' => 'password123', 'role' => 'Pegawai', 'status' => 'Aktif']
    ];
    $stmt = $pdo->prepare("INSERT INTO pengguna (kode_pengguna, nama_lengkap, jabatan, kontak, nama_pengguna, kata_sandi, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $idx_pengguna = 1;
    foreach ($pengguna as $p) {
        $kode = "PG" . str_pad($idx_pengguna++, 4, "0", STR_PAD_LEFT);
        // Khusus pawcare (SuperAdmin) menggunakan kata sandi plain sesuai hardcode auth.php
        $pwd = ($p['nama_pengguna'] === 'pawcare') ? $p['kata_sandi'] : password_hash($p['kata_sandi'], PASSWORD_DEFAULT);
        $stmt->execute([$kode, $p['nama_lengkap'], $p['jabatan'], $p['kontak'], $p['nama_pengguna'], $pwd, $p['role'], $p['status']]);
    }
    echo "Sukses memasukkan data dummy Pengguna.\n";

    // Ambil ID pengguna
    $id_admin = $pdo->query("SELECT id_pengguna FROM pengguna WHERE nama_pengguna = 'pawcare'")->fetchColumn();
    $id_staff = $pdo->query("SELECT id_pengguna FROM pengguna WHERE nama_pengguna = 'budi'")->fetchColumn();

    // 2. Insert Jenis Hewan
    $jenis = [
        ['kode' => 'JH0001', 'nama' => 'Kucing'],
        ['kode' => 'JH0002', 'nama' => 'Anjing'],
        ['kode' => 'JH0003', 'nama' => 'Kelinci']
    ];
    $stmt = $pdo->prepare("INSERT INTO jenis_hewan (id_jenis, kode_jenis_hewan, nama_jenis) VALUES (?, ?, ?)");
    $idx_jenis = 1;
    foreach ($jenis as $j) {
        $stmt->execute([$idx_jenis, $j['kode'], $j['nama']]);
        $idx_jenis++;
    }
    echo "Sukses memasukkan data dummy Jenis Hewan.\n";

    // Ambil ID jenis hewan
    $id_kucing = $pdo->query("SELECT id_jenis FROM jenis_hewan WHERE nama_jenis = 'Kucing'")->fetchColumn();
    $id_anjing = $pdo->query("SELECT id_jenis FROM jenis_hewan WHERE nama_jenis = 'Anjing'")->fetchColumn();
    $id_kelinci = $pdo->query("SELECT id_jenis FROM jenis_hewan WHERE nama_jenis = 'Kelinci'")->fetchColumn();

    // 3. Insert Ras
    $ras = [
        ['id_ras' => 1, 'kode' => 'R0001', 'id_jenis' => $id_kucing, 'nama' => 'Persia'],
        ['id_ras' => 2, 'kode' => 'R0002', 'id_jenis' => $id_kucing, 'nama' => 'Anggora'],
        ['id_ras' => 3, 'kode' => 'R0003', 'id_jenis' => $id_anjing, 'nama' => 'Golden Retriever'],
        ['id_ras' => 4, 'kode' => 'R0004', 'id_jenis' => $id_anjing, 'nama' => 'Bulldog'],
        ['id_ras' => 5, 'kode' => 'R0005', 'id_jenis' => $id_kelinci, 'nama' => 'Angora Bunny']
    ];
    $stmt = $pdo->prepare("INSERT INTO ras (id_ras, kode_ras, id_jenis, nama_ras) VALUES (?, ?, ?, ?)");
    foreach ($ras as $r) {
        $stmt->execute([$r['id_ras'], $r['kode'], $r['id_jenis'], $r['nama']]);
    }
    echo "Sukses memasukkan data dummy Ras Hewan.\n";

    // 4. Insert Kandang
    $kandang = [
        ['kode' => 'KND-001', 'nama' => 'Kandang Kucing A', 'id_jenis' => $id_kucing, 'kapasitas' => 5, 'status' => 'Tersedia'],
        ['kode' => 'KND-002', 'nama' => 'Kandang Anjing A', 'id_jenis' => $id_anjing, 'kapasitas' => 3, 'status' => 'Tersedia'],
        ['kode' => 'KND-003', 'nama' => 'Kandang Kelinci A', 'id_jenis' => $id_kelinci, 'kapasitas' => 4, 'status' => 'Tersedia']
    ];
    $stmt = $pdo->prepare("INSERT INTO kandang (kode_kandang, nama_kandang, id_jenis, kapasitas, status) VALUES (?, ?, ?, ?, ?)");
    foreach ($kandang as $k) {
        $stmt->execute([$k['kode'], $k['nama'], $k['id_jenis'], $k['kapasitas'], $k['status']]);
    }
    echo "Sukses memasukkan data dummy Kandang.\n";

    // Ambil ID kandang
    $id_kandang_kucing = $pdo->query("SELECT id_kandang FROM kandang WHERE kode_kandang = 'KND-001'")->fetchColumn();
    $id_kandang_anjing = $pdo->query("SELECT id_kandang FROM kandang WHERE kode_kandang = 'KND-002'")->fetchColumn();

    // 5. Insert Vaksin
    $vaksin = [
        ['kode' => 'VK0001', 'nama' => 'Feline Tricat', 'deskripsi' => 'Vaksinasi untuk Kucing mencegah FPV, FRV, dan FCV.', 'status' => 'Tersedia', 'stok' => 12, 'jenis' => ['Kucing']],
        ['kode' => 'VK0002', 'nama' => 'Rabies Vaccine',  'deskripsi' => 'Vaksin anti rabies untuk Kucing & Anjing.', 'status' => 'Tersedia', 'stok' => 3,  'jenis' => ['Kucing','Anjing']],
        ['kode' => 'VK0003', 'nama' => 'Eurican 4',      'deskripsi' => 'Vaksinasi lengkap untuk Anjing.', 'status' => 'Tersedia', 'stok' => 0,  'jenis' => ['Anjing']]
    ];
    $stmt = $pdo->prepare("INSERT INTO vaksin (kode_vaksin, nama_vaksin, deskripsi, status, stok) VALUES (?, ?, ?, ?, ?)");
    $stmt_pivot = $pdo->prepare("INSERT INTO vaksin_jenis (id_vaksin, id_jenis) VALUES (?, ?)");
    foreach ($vaksin as $v) {
        $stmt->execute([$v['kode'], $v['nama'], $v['deskripsi'], $v['status'], $v['stok'] ?? 0]);
        $id_vaksin = $pdo->lastInsertId();
        foreach ($v['jenis'] as $nama_jenis) {
            $id_jenis = $pdo->prepare("SELECT id_jenis FROM jenis_hewan WHERE nama_jenis = ?");
            $id_jenis->execute([$nama_jenis]);
            $ij = $id_jenis->fetchColumn();
            if ($ij) $stmt_pivot->execute([$id_vaksin, $ij]);
        }
    }
    echo "Sukses memasukkan data dummy Vaksin.\n";

    // Ambil ID vaksin
    $id_vaksin_tricat = $pdo->query("SELECT id_vaksin FROM vaksin WHERE kode_vaksin = 'VK0001'")->fetchColumn();
    $id_vaksin_rabies = $pdo->query("SELECT id_vaksin FROM vaksin WHERE kode_vaksin = 'VK0002'")->fetchColumn();

    // 6. Insert Pengadopsi (Adopter)
    $pengadopsi = [
        ['nama_lengkap' => 'Rian Adopter', 'nama_pengguna' => 'rian', 'alamat' => 'Jl. Merdeka No. 10, Jakarta', 'no_hp' => '08123123123', 'email' => 'rian@adopter.com', 'kata_sandi' => 'password123', 'url_ktp' => null, 'status_verifikasi' => 'Terverifikasi', 'tanggal_verifikasi' => '2026-06-01', 'catatan_verifikasi' => 'KTP Valid dan telah disurvei.'],
        ['nama_lengkap' => 'Diana Adopter', 'nama_pengguna' => 'diana', 'alamat' => 'Jl. Mawar No. 45, Bandung', 'no_hp' => '087788990011', 'email' => 'diana@adopter.com', 'kata_sandi' => 'password123', 'url_ktp' => null, 'status_verifikasi' => 'Menunggu', 'tanggal_verifikasi' => null, 'catatan_verifikasi' => null]
    ];
    $stmt = $pdo->prepare("INSERT INTO pengadopsi (kode_pengadopsi, nama_lengkap, nama_pengguna, alamat, no_hp, email, kata_sandi, url_ktp, status_verifikasi, tanggal_verifikasi, catatan_verifikasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $idx_adopter = 1;
    foreach ($pengadopsi as $ad) {
        $kode = "AD" . str_pad($idx_adopter++, 4, "0", STR_PAD_LEFT);
        $hashed_pwd = password_hash($ad['kata_sandi'], PASSWORD_DEFAULT);
        $stmt->execute([$kode, $ad['nama_lengkap'], $ad['nama_pengguna'], $ad['alamat'], $ad['no_hp'], $ad['email'], $hashed_pwd, $ad['url_ktp'], $ad['status_verifikasi'], $ad['tanggal_verifikasi'], $ad['catatan_verifikasi']]);
    }
    echo "Sukses memasukkan data dummy Pengadopsi.\n";

    // Ambil ID pengadopsi
    $id_adopter_rian = $pdo->query("SELECT id_pengadopsi FROM pengadopsi WHERE nama_pengguna = 'rian'")->fetchColumn();

    // 7. Insert Hewan
    $hewan = [
        [
            'kode' => 'HW0001', 
            'id_ras' => 1, // Persia
            'id_jenis' => $id_kucing, 
            'nama' => 'Bella', 
            'umur' => 12, 
            'gender' => 'Betina', 
            'tgl_lahir' => '2025-06-01', 
            'status' => 'Tersedia', 
            'rekomendasi' => 1, 
            'sumber' => 'Donasi', 
            'donatur' => 'Budianto', 
            'kontak' => '08123456789', 
            'tgl_intake' => '2026-06-01', 
            'keterangan' => 'Kondisi sehat walafiat, manja.',
            'url_foto' => null,
            'deskripsi' => 'Kucing persia putih bermata biru yang manis.',
            'hobi' => 'Bermain dengan bola benang wol merah',
            'funfact' => 'Suka tidur siang telentang dan mendengkur pelan ketika diusap lehernya.'
        ],
        [
            'kode' => 'HW0002', 
            'id_ras' => 3, // Golden
            'id_jenis' => $id_anjing, 
            'nama' => 'Bruno', 
            'umur' => 24, 
            'gender' => 'Jantan', 
            'tgl_lahir' => '2024-06-01', 
            'status' => 'Tersedia', 
            'rekomendasi' => 0, 
            'sumber' => 'Breeding', 
            'donatur' => null, 
            'kontak' => null, 
            'tgl_intake' => '2026-06-15', 
            'keterangan' => 'Sangat aktif dan ramah dengan anak-anak.',
            'url_foto' => null,
            'deskripsi' => 'Anjing Golden Retriever jantan bertubuh tegap.',
            'hobi' => 'Menangkap piring terbang (frisbee)',
            'funfact' => 'Bisa tersenyum menampakkan giginya ketika merasa senang atau diajak bermain.'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO hewan (
        kode_hewan, id_ras, id_jenis, nama_hewan, estimasi_umur, jenis_kelamin, 
        tanggal_lahir, status_adopsi, rekomendasi_adopsi, sumber_intake, 
        nama_donatur, kontak_donatur, tanggal_intake, keterangan_intake, 
        url_foto_hewan, deskripsi, hobi, funfact
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($hewan as $h) {
        $stmt->execute([
            $h['kode'], $h['id_ras'], $h['id_jenis'], $h['nama'], $h['umur'], $h['gender'],
            $h['tgl_lahir'], $h['status'], $h['rekomendasi'], $h['sumber'],
            $h['donatur'], $h['kontak'], $h['tgl_intake'], $h['keterangan'],
            $h['url_foto'], $h['deskripsi'], $h['hobi'], $h['funfact']
        ]);
    }
    echo "Sukses memasukkan data dummy Hewan.\n";

    // Ambil ID hewan
    $id_hewan_bella = $pdo->query("SELECT id_hewan FROM hewan WHERE nama_hewan = 'Bella'")->fetchColumn();
    $id_hewan_bruno = $pdo->query("SELECT id_hewan FROM hewan WHERE nama_hewan = 'Bruno'")->fetchColumn();

    // 8. Insert Riwayat Kesehatan
    $riwayat = [
        ['kode' => 'RK0001', 'id_hewan' => $id_hewan_bella, 'id_pengguna' => $id_staff, 'tipe' => 'Vaksinasi', 'id_vaksin' => $id_vaksin_tricat, 'tanggal' => '2026-06-10', 'deskripsi' => 'Pemberian vaksin Tricat dosis pertama.'],
        ['kode' => 'RK0002', 'id_hewan' => $id_hewan_bruno, 'id_pengguna' => $id_staff, 'tipe' => 'Perawatan', 'id_vaksin' => null, 'tanggal' => '2026-06-20', 'deskripsi' => 'Pemberian obat cacing rutin dan pemotongan kuku.']
    ];
    $stmt = $pdo->prepare("INSERT INTO riwayat_kesehatan (kode_riwayat_kesehatan, id_hewan, id_pengguna, tipe, id_vaksin, tanggal, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($riwayat as $rw) {
        $stmt->execute([$rw['kode'], $rw['id_hewan'], $rw['id_pengguna'], $rw['tipe'], $rw['id_vaksin'], $rw['tanggal'], $rw['deskripsi']]);
    }
    echo "Sukses memasukkan data dummy Riwayat Kesehatan.\n";

    // 9. Insert Penempatan Kandang
    $penempatan = [
        ['kode' => 'PK0001', 'id_hewan' => $id_hewan_bella, 'id_kandang' => $id_kandang_kucing, 'tgl_masuk' => '2026-06-02', 'tgl_keluar' => null, 'status' => 'Aktif'],
        ['kode' => 'PK0002', 'id_hewan' => $id_hewan_bruno, 'id_kandang' => $id_kandang_anjing, 'tgl_masuk' => '2026-06-16', 'tgl_keluar' => null, 'status' => 'Aktif']
    ];
    $stmt = $pdo->prepare("INSERT INTO penempatan_kandang (kode_penempatan_kandang, id_hewan, id_kandang, tanggal_masuk, tanggal_keluar, status) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($penempatan as $pn) {
        $stmt->execute([$pn['kode'], $pn['id_hewan'], $pn['id_kandang'], $pn['tgl_masuk'], $pn['tgl_keluar'], $pn['status']]);
    }
    echo "Sukses memasukkan data dummy Penempatan Kandang.\n";

    // 10. Insert Jadwal Kunjungan
    $jadwal = [
        ['kode' => 'JK0001', 'id_pengadopsi' => $id_adopter_rian, 'id_hewan' => $id_hewan_bella, 'id_pengguna' => $id_admin, 'metode' => 'Kunjungan ke Shelter', 'tanggal' => '2026-07-02 10:00:00', 'alamat' => null, 'status' => 'Menunggu']
    ];
    $stmt = $pdo->prepare("INSERT INTO jadwal_kunjungan (kode_jadwal_kunjungan, id_pengadopsi, id_hewan, id_pengguna, metode, tanggal_jadwal, alamat_tujuan, status_jadwal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($jadwal as $jd) {
        $stmt->execute([$jd['kode'], $jd['id_pengadopsi'], $jd['id_hewan'], $jd['id_pengguna'], $jd['metode'], $jd['tanggal'], $jd['alamat'], $jd['status']]);
    }
    echo "Sukses memasukkan data dummy Jadwal Kunjungan.\n";

    // 11. Insert Transaksi Adopsi
    $adopsi = [
        ['kode' => 'TA0001', 'id_hewan' => $id_hewan_bella, 'id_pengadopsi' => $id_adopter_rian, 'id_pengguna' => $id_admin, 'tanggal' => '2026-06-25', 'status' => 'Draft', 'ttd_adopter' => null, 'ttd_admin' => null]
    ];
    $stmt = $pdo->prepare("INSERT INTO transaksi_adopsi (kode_transaksi_adopsi, id_hewan, id_pengadopsi, id_pengguna, tanggal_adopsi, status_kontrak, ttd_adopter, ttd_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($adopsi as $adp) {
        $stmt->execute([$adp['kode'], $adp['id_hewan'], $adp['id_pengadopsi'], $adp['id_pengguna'], $adp['tanggal'], $adp['status'], $adp['ttd_adopter'], $adp['ttd_admin']]);
    }
    echo "Sukses memasukkan data dummy Transaksi Adopsi.\n";

    // 12. Insert Donasi
    $donasi = [
        ['kode' => 'DN0001', 'nama' => 'Rian Adopter', 'id_pengadopsi' => $id_adopter_rian, 'nominal' => 250000.00, 'kategori' => 'Pemasukan', 'keterangan' => 'Donasi peduli kucing Bella.', 'tanggal' => '2026-06-26', 'metode' => 'Transfer Bank', 'bukti' => null, 'status' => 'Dikonfirmasi']
    ];
    $stmt = $pdo->prepare("INSERT INTO donasi (kode_donasi, nama_donatur, id_pengadopsi, nominal, kategori, keterangan, tanggal, metode_pembayaran, url_bukti, status_konfirmasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($donasi as $dn) {
        $stmt->execute([$dn['kode'], $dn['nama'], $dn['id_pengadopsi'], $dn['nominal'], $dn['kategori'], $dn['keterangan'], $dn['tanggal'], $dn['metode'], $dn['bukti'], $dn['status']]);
    }
    echo "Sukses memasukkan data dummy Donasi.\n";

    // Aktifkan kembali foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "\n=== PENGISIAN DATA DUMMY SELESAI DENGAN SUKSES ===\n";

} catch (Exception $e) {
    // Pastikan foreign key diaktifkan kembali jika terjadi error
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    die("Gagal mengisi data dummy: " . $e->getMessage() . "\n");
}
?>
