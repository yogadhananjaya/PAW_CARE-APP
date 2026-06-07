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

            // Cari pengguna berdasarkan username
            $stmt = $this->db->prepare("SELECT * FROM Pengguna WHERE nama_pengguna = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            // Verifikasi user dan password (menggunakan password_verify untuk keamanan hash)
            if ($user && password_verify($password, $user['kata_sandi'])) {
                // Jika sukses, buat session
                $_SESSION['logged_in'] = true;
                $_SESSION['id_pengguna'] = $user['id_pengguna'];
                $_SESSION['username'] = $user['nama_pengguna'];
                $_SESSION['role'] = $user['status_pengguna']; // Superadmin, Staff, atau User

                // Alihkan ke halaman utama dashboard
                header("Location: index.php?action=dashboard");
                exit;
            } else {
                // Jika gagal, kembalikan ke login dengan pesan error
                header("Location: index.php?action=login&error=Username atau Password salah!");
                exit;
            }
        }
    }
    
    public function registerProcess() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['nama_pengguna']);
            $email = trim($_POST['email']);
            $password = $_POST['kata_sandi'];
            $no_telp = trim($_POST['no_telp']);
            $tgl_lahir = $_POST['tgl_lahir'];
            $jenis_kelamin = $_POST['jenis_kelamin'];
            $captcha_jawab = $_POST['captcha_jawab'];
            
            // Hak akses dikunci keras (hardcoded) menjadi User
            $role = 'User';

            // 1. Verifikasi Captcha Anti-Robot
            if ($captcha_jawab != $_SESSION['captcha_hasil']) {
                // Reset captcha agar berganti soal
                unset($_SESSION['captcha_hasil']); 
                header("Location: index.php?action=register&from=register&error=Jawaban matematika (Captcha) salah!");
                exit;
            }

            // 2. Cek apakah username atau email sudah pernah digunakan
            $stmtCheck = $this->db->prepare("SELECT id_pengguna FROM Pengguna WHERE nama_pengguna = :user OR email = :email");
            $stmtCheck->execute(['user' => $username, 'email' => $email]);
            if ($stmtCheck->rowCount() > 0) {
                header("Location: index.php?action=register&from=register&error=Username atau Email sudah terdaftar!");
                exit;
            }

            // 3. Enkripsi password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 4. Simpan ke database dengan field baru
            $stmt = $this->db->prepare("INSERT INTO Pengguna (nama_pengguna, email, kata_sandi, no_telp, tgl_lahir, jenis_kelamin, status_pengguna) 
                                        VALUES (:user, :email, :pass, :telp, :tgl, :jk, :role)");
            $sukses = $stmt->execute([
                'user' => $username,
                'email' => $email,
                'pass' => $hashedPassword,
                'telp' => $no_telp,
                'tgl' => $tgl_lahir,
                'jk' => $jenis_kelamin,
                'role' => $role
            ]);

            // Reset captcha setelah sukses mendaftar
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