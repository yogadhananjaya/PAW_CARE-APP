<?php
require_once __DIR__ . '/../models/PengadopsiModel.php';

class PengadopsiController {
    private $m;

    public function __construct() { 
        $this->m = new PengadopsiModel(); 
    }

    public function index() {
        $data = $this->m->getAll();
        include __DIR__ . '/../../views/Master_Data/pengadopsi/index.php';
    }

    public function create() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $nama_pengguna = trim($_POST['nama_pengguna']);
            $email = trim($_POST['email']);
            $no_hp = trim($_POST['no_hp']);
            $alamat = trim($_POST['alamat']);
            $kata_sandi = $_POST['kata_sandi'] ?? '';

            // Cek keunikan email
            $db_check = $this->m->getByEmail($email);
            // Cek keunikan username
            $db_check_user = $this->m->isDuplicateUsername($nama_pengguna);
            
            if ($nama_lengkap === '') {
                $error = "Nama lengkap wajib diisi!";
            } elseif (strlen($nama_lengkap) > 100) {
                $error = "Nama lengkap tidak boleh lebih dari 100 karakter!";
            } elseif (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $nama_pengguna)) {
                $error = "Username hanya boleh berupa huruf, angka, underscore, dan panjang antara 4 sampai 20 karakter!";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format email tidak valid!";
            } elseif (strlen($email) > 100) {
                $error = "Email tidak boleh lebih dari 100 karakter!";
            } elseif (!preg_match('/^[+0-9\s-]{10,20}$/', $no_hp)) {
                $error = "Nomor HP wajib 10-20 digit (hanya angka, spasi, +, -)!";
            } elseif ($alamat === '') {
                $error = "Alamat tidak boleh kosong!";
            } elseif (strlen($alamat) > 255) {
                $error = "Alamat tidak boleh lebih dari 255 karakter!";
            } elseif ($kata_sandi === '') {
                $error = "Kata sandi wajib diisi!";
            } elseif ($db_check) {
                $error = "Email '" . htmlspecialchars($email) . "' sudah terdaftar! Gunakan email lain.";
            } elseif ($db_check_user) {
                $error = "Username '" . htmlspecialchars($nama_pengguna) . "' sudah terdaftar! Gunakan username lain.";
            } else {
                // Upload foto KTP
                $foto_name = null;
                if (isset($_FILES['url_ktp']) && $_FILES['url_ktp']['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
                    $file_type = $_FILES['url_ktp']['type'];
                    $file_size = $_FILES['url_ktp']['size'];

                    if (!in_array($file_type, $allowed_types)) {
                        $error = "Format foto KTP tidak didukung! Hanya diperbolehkan JPG, JPEG, atau PNG.";
                    } elseif ($file_size > 2 * 1024 * 1024) {
                        $error = "Ukuran foto KTP melebihi batas 2MB!";
                    } else {
                        $folder = __DIR__ . '/../../assets/img/ktp/';
                        if (!is_dir($folder)) {
                            mkdir($folder, 0777, true);
                        }
                        
                        $nama_file_asli = $_FILES['url_ktp']['name'];
                        $ekstensi = pathinfo($nama_file_asli, PATHINFO_EXTENSION);
                        $foto_name = 'ktp_' . time() . '.' . $ekstensi;
                        $target = $folder . $foto_name;
                        move_uploaded_file($_FILES['url_ktp']['tmp_name'], $target);
                    }
                }

                if ($error === null) {
                    $_POST['url_ktp'] = $foto_name;
                    $this->m->insert($_POST);
                    header('Location: index.php?page=pengadopsi');
                    exit;
                }
            }
        }
        include __DIR__ . '/../../views/Master_Data/pengadopsi/create.php';
    }

    public function edit($id) {
        $pengadopsi = $this->m->getById($id);
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $nama_pengguna = trim($_POST['nama_pengguna']);
            $email = trim($_POST['email']);
            $no_hp = trim($_POST['no_hp']);
            $alamat = trim($_POST['alamat']);

            // Cek keunikan email
            $db_check = $this->m->getByEmail($email);
            // Cek keunikan username
            $db_check_user = $this->m->isDuplicateUsername($nama_pengguna, $id);
            
            if ($nama_lengkap === '') {
                $error = "Nama lengkap wajib diisi!";
            } elseif (strlen($nama_lengkap) > 100) {
                $error = "Nama lengkap tidak boleh lebih dari 100 karakter!";
            } elseif (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $nama_pengguna)) {
                $error = "Username hanya boleh berupa huruf, angka, underscore, dan panjang antara 4 sampai 20 karakter!";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format email tidak valid!";
            } elseif (strlen($email) > 100) {
                $error = "Email tidak boleh lebih dari 100 karakter!";
            } elseif (!preg_match('/^[+0-9\s-]{10,20}$/', $no_hp)) {
                $error = "Nomor HP wajib 10-20 digit (hanya angka, spasi, +, -)!";
            } elseif ($alamat === '') {
                $error = "Alamat tidak boleh kosong!";
            } elseif (strlen($alamat) > 255) {
                $error = "Alamat tidak boleh lebih dari 255 karakter!";
            } elseif ($db_check && $db_check['id_pengadopsi'] != $id) {
                $error = "Email '" . htmlspecialchars($email) . "' sudah terdaftar pada pengadopsi lain! Gunakan email lain.";
            } elseif ($db_check_user) {
                $error = "Username '" . htmlspecialchars($nama_pengguna) . "' sudah terdaftar pada pengadopsi lain! Gunakan username lain.";
            } else {
                $foto_name = $pengadopsi['url_ktp'];
                if (isset($_FILES['url_ktp']) && $_FILES['url_ktp']['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
                    $file_type = $_FILES['url_ktp']['type'];
                    $file_size = $_FILES['url_ktp']['size'];

                    if (!in_array($file_type, $allowed_types)) {
                        $error = "Format foto KTP tidak didukung! Hanya diperbolehkan JPG, JPEG, atau PNG.";
                    } elseif ($file_size > 2 * 1024 * 1024) {
                        $error = "Ukuran foto KTP melebihi batas 2MB!";
                    } else {
                        $folder = __DIR__ . '/../../assets/img/ktp/';
                        if (!is_dir($folder)) {
                            mkdir($folder, 0777, true);
                        }

                        // Hapus foto lama jika ada
                        if (!empty($pengadopsi['url_ktp'])) {
                            $foto_lama = $folder . $pengadopsi['url_ktp'];
                            if (file_exists($foto_lama)) {
                                unlink($foto_lama);
                            }
                        }

                        $nama_file_asli = $_FILES['url_ktp']['name'];
                        $ekstensi = pathinfo($nama_file_asli, PATHINFO_EXTENSION);
                        $foto_name = 'ktp_' . time() . '.' . $ekstensi;
                        $target = $folder . $foto_name;
                        move_uploaded_file($_FILES['url_ktp']['tmp_name'], $target);
                    }
                }

                if ($error === null) {
                    $_POST['url_ktp'] = $foto_name;
                    $this->m->update($id, $_POST);
                    header('Location: index.php?page=pengadopsi');
                    exit;
                }
            }
        }
        $data = $this->m->getById($id);
        include __DIR__ . '/../../views/Master_Data/pengadopsi/edit.php';
    }

    public function delete($id) {
        if ($this->m->isUsed($id)) {
            header('Location: index.php?page=pengadopsi&error_delete=1');
            exit;
        }
        $pengadopsi = $this->m->getById($id);
        if ($pengadopsi && !empty($pengadopsi['url_ktp'])) {
            $foto = __DIR__ . '/../../assets/img/ktp/' . $pengadopsi['url_ktp'];
            if (file_exists($foto)) {
                unlink($foto);
            }
        }
        $this->m->delete($id);
        header('Location: index.php?page=pengadopsi');
        exit;
    }
}
?>