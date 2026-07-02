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
            // Cek keunikan email
            $db_check = $this->m->getByEmail($_POST['email']);
            // Cek keunikan username
            $db_check_user = $this->m->isDuplicateUsername($_POST['nama_pengguna']);
            
            // ponytail: Validasi format username (huruf, angka, underscore, 4-20 karakter)
            if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', trim($_POST['nama_pengguna']))) {
                $error = "Username hanya boleh berupa huruf, angka, underscore, dan panjang antara 4 sampai 20 karakter!";
            } elseif ($db_check) {
                $error = "Email '" . htmlspecialchars($_POST['email']) . "' sudah terdaftar! Gunakan email lain.";
            } elseif ($db_check_user) {
                $error = "Username '" . htmlspecialchars($_POST['nama_pengguna']) . "' sudah terdaftar! Gunakan username lain.";
            } else {
                // Upload foto KTP (Gaya pemula)
                $foto_name = null;
                if (isset($_FILES['url_ktp']) && $_FILES['url_ktp']['error'] === UPLOAD_ERR_OK) {
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

                $_POST['url_ktp'] = $foto_name;
                $this->m->insert($_POST);
                header('Location: index.php?page=pengadopsi');
                exit;
            }
        }
        // Pengadopsi buat akun mandiri, tidak perlu pilih pengguna
        include __DIR__ . '/../../views/Master_Data/pengadopsi/create.php';
    }

    public function edit($id) {
        $pengadopsi = $this->m->getById($id);
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cek keunikan email
            $db_check = $this->m->getByEmail($_POST['email']);
            // Cek keunikan username
            $db_check_user = $this->m->isDuplicateUsername($_POST['nama_pengguna'], $id);
            
            // ponytail: Validasi format username (huruf, angka, underscore, 4-20 karakter)
            if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', trim($_POST['nama_pengguna']))) {
                $error = "Username hanya boleh berupa huruf, angka, underscore, dan panjang antara 4 sampai 20 karakter!";
            } elseif ($db_check && $db_check['id_pengadopsi'] != $id) {
                $error = "Email '" . htmlspecialchars($_POST['email']) . "' sudah terdaftar pada pengadopsi lain! Gunakan email lain.";
            } elseif ($db_check_user) {
                $error = "Username '" . htmlspecialchars($_POST['nama_pengguna']) . "' sudah terdaftar pada pengadopsi lain! Gunakan username lain.";
            } else {
                $foto_name = $pengadopsi['url_ktp'];
                if (isset($_FILES['url_ktp']) && $_FILES['url_ktp']['error'] === UPLOAD_ERR_OK) {
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

                $_POST['url_ktp'] = $foto_name;
                $this->m->update($id, $_POST);
                header('Location: index.php?page=pengadopsi');
                exit;
            }
        }
        $data = $this->m->getById($id);
        include __DIR__ . '/../../views/Master_Data/pengadopsi/edit.php';
    }

    public function delete($id) {
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