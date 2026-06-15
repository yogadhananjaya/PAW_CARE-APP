<?php

class HewanController
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function index()
    {
        $stmt = $this->db->query("
                                  SELECT h.*,
                                         r.nama_ras,
                                         j.nama_jenis
                                  FROM hewan h
                                  JOIN ras r ON h.id_ras = r.id_ras
                                  JOIN jenis_hewan j ON h.id_jenis = j.id_jenis
                                  ORDER BY h.id_hewan DESC
                                ");
        $hewan = $stmt->fetchAll();

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/hewan/index.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function create()
    {
        $ras = $this->db->query("
            SELECT r.*, j.nama_jenis
            FROM ras r
            JOIN jenis_hewan j ON r.id_jenis = j.id_jenis
            ORDER BY r.nama_ras
        ")->fetchAll();

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/hewan/tambah.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function store()
    {
        // Ambil id_jenis berdasarkan id_ras
        $stmtRas = $this->db->prepare("SELECT id_jenis FROM ras WHERE id_ras = :id_ras");
        $stmtRas->execute(['id_ras' => $_POST['id_ras']]);
        $rasData = $stmtRas->fetch();
        $id_jenis = $rasData ? $rasData['id_jenis'] : 0;

        // Cek upload foto jika ada
        $foto_hewan = null;
        if (isset($_FILES['foto_hewan']) && $_FILES['foto_hewan']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['foto_hewan']['tmp_name'];
            $name = basename($_FILES['foto_hewan']['name']);
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $newName = 'hewan_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $uploadDir = __DIR__ . '/../../assets/img/hewan/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                $foto_hewan = $newName;
            }
        } elseif (isset($_POST['foto_hewan'])) {
            $foto_hewan = $_POST['foto_hewan'];
        }

        $stmt = $this->db->prepare("
            INSERT INTO hewan
            (
                id_ras,
                id_jenis,
                nama_hewan,
                umur,
                jenis_kelamin,
                status_adopsi,
                sumber_intake,
                nama_donatur,
                kontak_donatur,
                tanggal_intake,
                keterangan_intake,
                foto_hewan,
                deskripsi
            )
            VALUES
            (
                :id_ras,
                :id_jenis,
                :nama_hewan,
                :umur,
                :jenis_kelamin,
                :status_adopsi,
                :sumber_intake,
                :nama_donatur,
                :kontak_donatur,
                :tanggal_intake,
                :keterangan_intake,
                :foto_hewan,
                :deskripsi
            )
        ");

        $stmt->execute([
            'id_ras' => $_POST['id_ras'],
            'id_jenis' => $id_jenis,
            'nama_hewan' => $_POST['nama_hewan'],
            'umur' => $_POST['umur'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'status_adopsi' => $_POST['status_adopsi'],
            'sumber_intake' => $_POST['sumber_intake'],
            'nama_donatur' => !empty($_POST['nama_donatur']) ? $_POST['nama_donatur'] : null,
            'kontak_donatur' => !empty($_POST['kontak_donatur']) ? $_POST['kontak_donatur'] : null,
            'tanggal_intake' => $_POST['tanggal_intake'],
            'keterangan_intake' => !empty($_POST['keterangan_intake']) ? $_POST['keterangan_intake'] : null,
            'foto_hewan' => $foto_hewan,
            'deskripsi' => !empty($_POST['deskripsi']) ? $_POST['deskripsi'] : null
        ]);

        header("Location: index.php?action=hewan&success=Data hewan berhasil ditambahkan");
        exit;
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM hewan
            WHERE id_hewan = :id
        ");
        $stmt->execute(['id' => $id]);
        $hewan = $stmt->fetch();

        $ras = $this->db->query("
            SELECT r.*, j.nama_jenis
            FROM ras r
            JOIN jenis_hewan j ON r.id_jenis = j.id_jenis
            ORDER BY r.nama_ras
        ")->fetchAll();

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/hewan/edit.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function update()
    {
        // Ambil id_jenis berdasarkan id_ras
        $stmtRas = $this->db->prepare("SELECT id_jenis FROM ras WHERE id_ras = :id_ras");
        $stmtRas->execute(['id_ras' => $_POST['id_ras']]);
        $rasData = $stmtRas->fetch();
        $id_jenis = $rasData ? $rasData['id_jenis'] : 0;

        // Ambil data hewan lama untuk fallback foto
        $stmtOld = $this->db->prepare("SELECT foto_hewan FROM hewan WHERE id_hewan = :id");
        $stmtOld->execute(['id' => $_POST['id_hewan']]);
        $oldHewan = $stmtOld->fetch();
        $foto_hewan = $oldHewan ? $oldHewan['foto_hewan'] : null;

        // Cek upload foto jika ada
        if (isset($_FILES['foto_hewan']) && $_FILES['foto_hewan']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['foto_hewan']['tmp_name'];
            $name = basename($_FILES['foto_hewan']['name']);
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $newName = 'hewan_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $uploadDir = __DIR__ . '/../../assets/img/hewan/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                $foto_hewan = $newName;
            }
        } elseif (isset($_POST['foto_hewan'])) {
            $foto_hewan = $_POST['foto_hewan'];
        }

        $stmt = $this->db->prepare("
            UPDATE hewan
            SET
                id_ras = :id_ras,
                id_jenis = :id_jenis,
                nama_hewan = :nama_hewan,
                umur = :umur,
                jenis_kelamin = :jenis_kelamin,
                status_adopsi = :status_adopsi,
                sumber_intake = :sumber_intake,
                nama_donatur = :nama_donatur,
                kontak_donatur = :kontak_donatur,
                tanggal_intake = :tanggal_intake,
                keterangan_intake = :keterangan_intake,
                foto_hewan = :foto_hewan,
                deskripsi = :deskripsi
            WHERE id_hewan = :id
        ");

        $stmt->execute([
            'id' => $_POST['id_hewan'],
            'id_ras' => $_POST['id_ras'],
            'id_jenis' => $id_jenis,
            'nama_hewan' => $_POST['nama_hewan'],
            'umur' => $_POST['umur'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'status_adopsi' => $_POST['status_adopsi'],
            'sumber_intake' => $_POST['sumber_intake'],
            'nama_donatur' => !empty($_POST['nama_donatur']) ? $_POST['nama_donatur'] : null,
            'kontak_donatur' => !empty($_POST['kontak_donatur']) ? $_POST['kontak_donatur'] : null,
            'tanggal_intake' => $_POST['tanggal_intake'],
            'keterangan_intake' => !empty($_POST['keterangan_intake']) ? $_POST['keterangan_intake'] : null,
            'foto_hewan' => $foto_hewan,
            'deskripsi' => !empty($_POST['deskripsi']) ? $_POST['deskripsi'] : null
        ]);

        header("Location: index.php?action=hewan&success=Data hewan berhasil diubah");
        exit;
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM hewan
            WHERE id_hewan = :id
        ");
        $stmt->execute(['id' => $id]);

        header("Location: index.php?action=hewan&success=Data hewan berhasil dihapus");
        exit;
    }
}
