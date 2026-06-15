<?php
// app/controllers/AuthController.php

class AuthController {
    private $db;

    // Konekkan ke database lewat instance PDO dari koneksi.php
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function loginProcess() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['nama_pengguna']);
            $password = $_POST['kata_sandi'];

            // 1. Cari di tabel pengguna (Admin/Pegawai)
            $stmt = $this->db->prepare("SELECT * FROM pengguna WHERE nama_pengguna = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['kata_sandi'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['id_pengguna'] = $user['id_pengguna'];
                $_SESSION['username'] = $user['nama_pengguna'];
                // Petakan role ke format lama agar kompatibel dengan sistem
                if ($user['role'] === 'SuperAdmin') {
                    $_SESSION['role'] = 'Superadmin';
                } elseif ($user['role'] === 'Pegawai') {
                    $_SESSION['role'] = 'Staff';
                } else {
                    $_SESSION['role'] = $user['role'];
                }

                header("Location: index.php?action=dashboard");
                exit;
            }

            // 2. Cari di tabel pengadopsi (User/Adopter)
            $stmtAdopter = $this->db->prepare("SELECT * FROM pengadopsi WHERE email = :username OR nama = :username");
            $stmtAdopter->execute(['username' => $username]);
            $adopter = $stmtAdopter->fetch();

            if ($adopter && password_verify($password, $adopter['kata_sandi'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['id_pengadopsi'] = $adopter['id_pengadopsi'];
                $_SESSION['username'] = $adopter['nama'];
                $_SESSION['role'] = 'User';

                header("Location: index.php?action=dashboard");
                exit;
            }

            // Jika gagal
            header("Location: index.php?action=login&error=Username/Email atau Password salah!");
            exit;
        }
    }
    
    public function registerProcess() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $password = $_POST['kata_sandi'];
            $no_hp = trim($_POST['no_hp']);
            $alamat = trim($_POST['alamat']);
            $captcha_jawab = $_POST['captcha_jawab'];

            // 1. Verifikasi Captcha Anti-Robot
            if ($captcha_jawab != $_SESSION['captcha_hasil']) {
                unset($_SESSION['captcha_hasil']); 
                header("Location: index.php?action=register&from=register&error=Jawaban matematika (Captcha) salah!");
                exit;
            }

            // 2. Cek apakah email sudah pernah digunakan
            $stmtCheck = $this->db->prepare("SELECT id_pengadopsi FROM pengadopsi WHERE email = :email");
            $stmtCheck->execute(['email' => $email]);
            if ($stmtCheck->rowCount() > 0) {
                header("Location: index.php?action=register&from=register&error=Email sudah terdaftar!");
                exit;
            }

            // 3. Enkripsi password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 4. Simpan ke database pengadopsi
            $stmt = $this->db->prepare("INSERT INTO pengadopsi (nama, email, kata_sandi, no_hp, alamat, status_verifikasi) 
                                        VALUES (:nama, :email, :pass, :no_hp, :alamat, 'Belum')");
            $sukses = $stmt->execute([
                'nama' => $nama,
                'email' => $email,
                'pass' => $hashedPassword,
                'no_hp' => $no_hp,
                'alamat' => $alamat
            ]);

            unset($_SESSION['captcha_hasil']);

            if ($sukses) {
                header("Location: index.php?action=login&success=Akun berhasil dibuat! Silakan login.");
                exit;
            } else {
                header("Location: index.php?action=register&from=register&error=Terjadi kesalahan sistem.");
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}