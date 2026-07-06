<?php
require_once __DIR__ . '/../models/HewanModel.php';

class HewanController {
    private $model;

    public function __construct() {
        $this->model = new HewanModel();
    }

    public function index() {
        $data = $this->model->getAll();
        include __DIR__ . '/../../views/Master_Data/hewan/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_hewan = trim($_POST['nama_hewan']);
            $id_jenis = $_POST['id_jenis'];
            $id_ras = $_POST['id_ras'];
            $estimasi_umur = (int)$_POST['estimasi_umur'];
            $tanggal_intake = $_POST['tanggal_intake'];
            $sumber_intake = $_POST['sumber_intake'];
            $nama_donatur = trim($_POST['nama_donatur'] ?? '');
            $kontak_donatur = trim($_POST['kontak_donatur'] ?? '');

            $error_duplikat = null;
            
            if (isset($_FILES['url_foto_hewan']) && $_FILES['url_foto_hewan']['error'] === UPLOAD_ERR_OK) {
                $file_size = $_FILES['url_foto_hewan']['size'];
                $ext = strtolower(pathinfo($_FILES['url_foto_hewan']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($ext, $allowed)) {
                    $error_duplikat = "Format foto tidak didukung! Hanya JPG, JPEG, PNG, dan GIF.";
                } elseif ($file_size > 2 * 1024 * 1024) {
                    $error_duplikat = "Ukuran foto terlalu besar! Maksimal 2MB.";
                }
            }

            if (!$error_duplikat) {
                if ($nama_hewan === '') {
                    $error_duplikat = "Nama hewan tidak boleh kosong!";
                } elseif (strlen($nama_hewan) > 100) {
                    $error_duplikat = "Nama hewan tidak boleh lebih dari 100 karakter!";
                } elseif ($estimasi_umur < 0) {
                    $error_duplikat = "Estimasi umur tidak boleh kurang dari 0!";
                } elseif (empty($tanggal_intake)) {
                    $error_duplikat = "Tanggal masuk shelter wajib diisi!";
                } elseif ($sumber_intake === 'Donasi') {
                    if ($nama_donatur === '') {
                        $error_duplikat = "Nama donatur wajib diisi jika sumber hewan dari donasi!";
                    } elseif (strlen($nama_donatur) > 100) {
                        $error_duplikat = "Nama donatur tidak boleh lebih dari 100 karakter!";
                    } elseif ($kontak_donatur === '') {
                        $error_duplikat = "Kontak donatur wajib diisi jika sumber hewan dari donasi!";
                    } elseif (strlen($kontak_donatur) > 20) {
                        $error_duplikat = "Kontak donatur tidak boleh lebih dari 20 karakter!";
                    } elseif (!preg_match("/^[+0-9\s-]{10,20}$/", $kontak_donatur)) {
                        $error_duplikat = "Kontak donatur hanya boleh berisi angka, spasi, +, -, dengan panjang 10-20 digit!";
                    }
                }
            }

            if (!$error_duplikat) {
                if ($this->model->isDuplicate($nama_hewan, $id_jenis, $id_ras)) {
                    $error_duplikat = "Hewan dengan nama, jenis, dan ras yang sama sudah terdaftar!";
                }
            }

            if ($error_duplikat) {
                $jenis_list = $this->model->getOpsiJenis();
                $ras_list = $this->model->getOpsiRas();
                include __DIR__ . '/../../views/Master_Data/hewan/create.php';
                exit;
            }

            $foto_name = null;
            if (isset($_FILES['url_foto_hewan']) && $_FILES['url_foto_hewan']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['url_foto_hewan']['name'], PATHINFO_EXTENSION);
                $foto_name = 'hewan_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/../../assets/img/hewan/' . $foto_name;
                move_uploaded_file($_FILES['url_foto_hewan']['tmp_name'], $target_dir);
            }

            $payload = [
                'id_jenis'          => $id_jenis,
                'id_ras'            => $id_ras,
                'nama_hewan'        => $nama_hewan,
                'jenis_kelamin'     => $_POST['jenis_kelamin'],
                'estimasi_umur'     => $estimasi_umur,
                'tanggal_lahir'     => !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null,
                'status_adopsi'     => $_POST['status_adopsi'],
                'sumber_intake'     => $sumber_intake,
                'nama_donatur'      => $nama_donatur,
                'kontak_donatur'    => $kontak_donatur,
                'tanggal_intake'    => $tanggal_intake,
                'keterangan_intake' => trim($_POST['keterangan_intake'] ?? ''),
                'url_foto_hewan'    => $foto_name,
                'deskripsi'         => trim($_POST['deskripsi'] ?? ''),
                'hobi'              => trim($_POST['hobi'] ?? ''),
                'funfact'           => trim($_POST['funfact'] ?? '')
            ];

            $this->model->insert($payload);
            header('Location: index.php?page=hewan');
            exit;
        }

        $jenis_list = $this->model->getOpsiJenis();
        $ras_list = $this->model->getOpsiRas();
        include __DIR__ . '/../../views/Master_Data/hewan/create.php';
    }

    public function edit($id) {
        $hewan = $this->model->getById($id);
        $jenis_list = $this->model->getOpsiJenis(); 
        $ras_list = $this->model->getOpsiRas();
        if (!$hewan) { header('Location: index.php?page=hewan'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_hewan = trim($_POST['nama_hewan']);
            $id_jenis = $_POST['id_jenis'];
            $id_ras = $_POST['id_ras'];
            $estimasi_umur = (int)$_POST['estimasi_umur'];
            $tanggal_intake = $_POST['tanggal_intake'];
            $sumber_intake = $_POST['sumber_intake'];
            $nama_donatur = trim($_POST['nama_donatur'] ?? '');
            $kontak_donatur = trim($_POST['kontak_donatur'] ?? '');

            $error_duplikat = null;
            
            if (isset($_FILES['url_foto_hewan']) && $_FILES['url_foto_hewan']['error'] === UPLOAD_ERR_OK) {
                $file_size = $_FILES['url_foto_hewan']['size'];
                $ext = strtolower(pathinfo($_FILES['url_foto_hewan']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($ext, $allowed)) {
                    $error_duplikat = "Format foto tidak didukung! Hanya JPG, JPEG, PNG, dan GIF.";
                } elseif ($file_size > 2 * 1024 * 1024) {
                    $error_duplikat = "Ukuran foto terlalu besar! Maksimal 2MB.";
                }
            }

            if (!$error_duplikat) {
                if ($nama_hewan === '') {
                    $error_duplikat = "Nama hewan tidak boleh kosong!";
                } elseif (strlen($nama_hewan) > 100) {
                    $error_duplikat = "Nama hewan tidak boleh lebih dari 100 karakter!";
                } elseif ($estimasi_umur < 0) {
                    $error_duplikat = "Estimasi umur tidak boleh kurang dari 0!";
                } elseif (empty($tanggal_intake)) {
                    $error_duplikat = "Tanggal masuk shelter wajib diisi!";
                } elseif ($sumber_intake === 'Donasi') {
                    if ($nama_donatur === '') {
                        $error_duplikat = "Nama donatur wajib diisi jika sumber hewan dari donasi!";
                    } elseif (strlen($nama_donatur) > 100) {
                        $error_duplikat = "Nama donatur tidak boleh lebih dari 100 karakter!";
                    } elseif ($kontak_donatur === '') {
                        $error_duplikat = "Kontak donatur wajib diisi jika sumber hewan dari donasi!";
                    } elseif (strlen($kontak_donatur) > 20) {
                        $error_duplikat = "Kontak donatur tidak boleh lebih dari 20 karakter!";
                    } elseif (!preg_match("/^[+0-9\s-]{10,20}$/", $kontak_donatur)) {
                        $error_duplikat = "Kontak donatur hanya boleh berisi angka, spasi, +, -, dengan panjang 10-20 digit!";
                    }
                }
            }

            if (!$error_duplikat) {
                if ($this->model->isDuplicate($nama_hewan, $id_jenis, $id_ras, $id)) {
                    $error_duplikat = "Hewan dengan nama, jenis, dan ras yang sama sudah terdaftar!";
                }
            }

            if ($error_duplikat) {
                include __DIR__ . '/../../views/Master_Data/hewan/edit.php';
                exit;
            }

            $foto_name = $hewan['url_foto_hewan'];
            if (isset($_FILES['url_foto_hewan']) && $_FILES['url_foto_hewan']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['url_foto_hewan']['name'], PATHINFO_EXTENSION);
                $foto_name = 'hewan_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/../../assets/img/hewan/' . $foto_name;
                
                if (!empty($hewan['url_foto_hewan']) && file_exists(__DIR__ . '/../../assets/img/hewan/' . $hewan['url_foto_hewan'])) {
                    unlink(__DIR__ . '/../../assets/img/hewan/' . $hewan['url_foto_hewan']);
                }
                move_uploaded_file($_FILES['url_foto_hewan']['tmp_name'], $target_dir);
            }

            $payload = [
                'id_jenis'          => $id_jenis,
                'id_ras'            => $id_ras,
                'nama_hewan'        => $nama_hewan,
                'jenis_kelamin'     => $_POST['jenis_kelamin'],
                'estimasi_umur'     => $estimasi_umur,
                'tanggal_lahir'     => !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null,
                'status_adopsi'     => $_POST['status_adopsi'],
                'sumber_intake'     => $sumber_intake,
                'nama_donatur'      => $nama_donatur,
                'kontak_donatur'    => $kontak_donatur,
                'tanggal_intake'    => $tanggal_intake,
                'keterangan_intake' => trim($_POST['keterangan_intake'] ?? ''),
                'url_foto_hewan'    => $foto_name,
                'deskripsi'         => trim($_POST['deskripsi'] ?? ''),
                'hobi'              => trim($_POST['hobi'] ?? ''),
                'funfact'           => trim($_POST['funfact'] ?? '')
            ];

            $this->model->update($id, $payload);
            header('Location: index.php?page=hewan');
            exit;
        }

        include __DIR__ . '/../../views/Master_Data/hewan/edit.php';
    }

    public function delete($id) {
        if ($this->model->isUsed($id)) {
            header('Location: index.php?page=hewan&error=delete_failed');
            exit;
        }
        $hewan = $this->model->getById($id);
        if ($hewan && !empty($hewan['url_foto_hewan']) && file_exists(__DIR__ . '/../../assets/img/hewan/' . $hewan['url_foto_hewan'])) {
            unlink(__DIR__ . '/../../assets/img/hewan/' . $hewan['url_foto_hewan']);
        }
        $this->model->delete($id);
        header('Location: index.php?page=hewan');
        exit;
    }

    public function recommend($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM riwayat_kesehatan WHERE id_hewan = ? AND tipe = 'Vaksinasi' AND deleted_at IS NULL");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() == 0) {
            header('Location: index.php?page=hewan&error=belum_vaksinasi');
            exit;
        }
        $this->model->rekomendasikan($id);
        header('Location: index.php?page=hewan');
        exit;
    }

    public function confirm($id) {
        $this->model->setujuiRilis($id);
        header('Location: index.php?page=hewan');
        exit;
    }

    public function intake() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_hewan = trim($_POST['nama_hewan']);
            $id_jenis = $_POST['id_jenis'];
            $id_ras = $_POST['id_ras'];
            $estimasi_umur = (int)$_POST['estimasi_umur'];
            $tanggal_intake = $_POST['tanggal_intake'];
            $sumber_intake = $_POST['sumber_intake'];
            $nama_donatur = trim($_POST['nama_donatur'] ?? '');
            $kontak_donatur = trim($_POST['kontak_donatur'] ?? '');

            $error_duplikat = null;

            if (isset($_FILES['url_foto_hewan']) && $_FILES['url_foto_hewan']['error'] === UPLOAD_ERR_OK) {
                $file_size = $_FILES['url_foto_hewan']['size'];
                $ext = strtolower(pathinfo($_FILES['url_foto_hewan']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($ext, $allowed)) {
                    $error_duplikat = "Format foto tidak didukung! Hanya JPG, JPEG, PNG, dan GIF.";
                } elseif ($file_size > 2 * 1024 * 1024) {
                    $error_duplikat = "Ukuran foto terlalu besar! Maksimal 2MB.";
                }
            }

            if (!$error_duplikat) {
                if ($nama_hewan === '') {
                    $error_duplikat = "Nama hewan tidak boleh kosong!";
                } elseif (strlen($nama_hewan) > 100) {
                    $error_duplikat = "Nama hewan tidak boleh lebih dari 100 karakter!";
                } elseif ($estimasi_umur < 0) {
                    $error_duplikat = "Estimasi umur tidak boleh kurang dari 0!";
                } elseif (empty($tanggal_intake)) {
                    $error_duplikat = "Tanggal masuk shelter wajib diisi!";
                } elseif ($sumber_intake === 'Donasi') {
                    if ($nama_donatur === '') {
                        $error_duplikat = "Nama donatur wajib diisi jika sumber hewan dari donasi!";
                    } elseif (strlen($nama_donatur) > 100) {
                        $error_duplikat = "Nama donatur tidak boleh lebih dari 100 karakter!";
                    } elseif ($kontak_donatur === '') {
                        $error_duplikat = "Kontak donatur wajib diisi jika sumber hewan dari donasi!";
                    } elseif (strlen($kontak_donatur) > 20) {
                        $error_duplikat = "Kontak donatur tidak boleh lebih dari 20 karakter!";
                    } elseif (!preg_match("/^[+0-9\s-]{10,20}$/", $kontak_donatur)) {
                        $error_duplikat = "Kontak donatur hanya boleh berisi angka, spasi, +, -, dengan panjang 10-20 digit!";
                    }
                }
            }

            if (!$error_duplikat) {
                if ($this->model->isDuplicate($nama_hewan, $id_jenis, $id_ras)) {
                    $error_duplikat = "Hewan dengan nama, jenis, dan ras yang sama sudah terdaftar!";
                }
            }

            if ($error_duplikat) {
                $jenis_list = $this->model->getOpsiJenis();
                $ras_list = $this->model->getOpsiRas();
                $recentHewan = $this->model->getAll();
                include __DIR__ . '/../../views/Master_Transaksi/intake_hewan/index.php';
                exit;
            }

            $foto_name = null;
            if (isset($_FILES['url_foto_hewan']) && $_FILES['url_foto_hewan']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['url_foto_hewan']['name'], PATHINFO_EXTENSION);
                $foto_name = 'hewan_' . time() . '.' . $ext;
                $target_dir = __DIR__ . '/../../assets/img/hewan/' . $foto_name;
                move_uploaded_file($_FILES['url_foto_hewan']['tmp_name'], $target_dir);
            }

            $payload = [
                'id_jenis'          => $id_jenis,
                'id_ras'            => $id_ras,
                'nama_hewan'        => $nama_hewan,
                'jenis_kelamin'     => $_POST['jenis_kelamin'],
                'estimasi_umur'     => $estimasi_umur,
                'tanggal_lahir'     => null,
                'status_adopsi'     => 'Karantina',
                'sumber_intake'     => $sumber_intake,
                'nama_donatur'      => $nama_donatur,
                'kontak_donatur'    => $kontak_donatur,
                'tanggal_intake'    => $tanggal_intake,
                'keterangan_intake' => trim($_POST['keterangan_intake'] ?? ''),
                'url_foto_hewan'    => $foto_name,
                'deskripsi'         => trim($_POST['deskripsi'] ?? ''),
                'hobi'              => trim($_POST['hobi'] ?? ''),
                'funfact'           => trim($_POST['funfact'] ?? '')
            ];

            $this->model->insert($payload);
            header('Location: index.php?page=intake_hewan&success=1');
            exit;
        }

        // Tampilkan form intake + daftar inventaris
        $jenis_list = $this->model->getOpsiJenis();
        $ras_list = $this->model->getOpsiRas();
        $recentHewan = $this->model->getAll();
        include __DIR__ . '/../../views/Master_Transaksi/intake_hewan/index.php';
    }
}
?>