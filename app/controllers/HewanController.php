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
                                  FROM Hewan h
                                  JOIN Ras r ON h.id_ras = r.id_ras
                                  JOIN Jenis_Hewan j ON r.id_jenis = j.id_jenis
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
        FROM Ras r
        JOIN Jenis_Hewan j ON r.id_jenis = j.id_jenis
        ORDER BY r.nama_ras
    ")->fetchAll();

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/hewan/tambah.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function store()
    {

        $stmt = $this->db->prepare("
    INSERT INTO Hewan
    (
        id_ras,
        nama_hewan,
        tanggal_lahir,
        estimasi_umur,
        jenis_kelamin,
        status_adopsi
    )
    VALUES
    (
        :id_ras,
        :nama_hewan,
        :tanggal_lahir,
        :estimasi_umur,
        :jenis_kelamin,
        :status_adopsi
    )
");

        $stmt->execute([
            'id_ras' => $_POST['id_ras'],
            'nama_hewan' => $_POST['nama_hewan'],
            'tanggal_lahir' => $_POST['tanggal_lahir'],
            'estimasi_umur' => $_POST['estimasi_umur'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'status_adopsi' => $_POST['status_adopsi']
        ]);

        header("Location:index.php?action=hewan&success=Data hewan berhasil ditambahkan");
        exit;
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM Hewan
        WHERE id_hewan = :id
    ");

        $stmt->execute(['id' => $id]);
        $hewan = $stmt->fetch();

        $ras = $this->db->query("
        SELECT r.*, j.nama_jenis
        FROM Ras r
        JOIN Jenis_Hewan j ON r.id_jenis = j.id_jenis
        ORDER BY r.nama_ras
    ")->fetchAll();

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/hewan/edit.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function update()
    {

        $stmt = $this->db->prepare("
    UPDATE Hewan
    SET
        id_ras = :id_ras,
        nama_hewan = :nama_hewan,
        tanggal_lahir = :tanggal_lahir,
        estimasi_umur = :estimasi_umur,
        jenis_kelamin = :jenis_kelamin,
        status_adopsi = :status_adopsi
    WHERE id_hewan = :id
        ");

        $stmt->execute([
            'id' => $_POST['id_hewan'],
            'id_ras' => $_POST['id_ras'],
            'nama_hewan' => $_POST['nama_hewan'],
            'tanggal_lahir' => $_POST['tanggal_lahir'],
            'estimasi_umur' => $_POST['estimasi_umur'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'status_adopsi' => $_POST['status_adopsi']
        ]);

        header("Location:index.php?action=hewan&success=Data hewan berhasil diubah");
        exit;
    }

    public function delete($id)
    {

        $stmt = $this->db->prepare("
            DELETE FROM Hewan
            WHERE id_hewan=:id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        header("Location:index.php?action=hewan&success=Data hewan berhasil dihapus");
        exit;
    }
}
