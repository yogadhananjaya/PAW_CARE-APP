<?php

class VaksinController
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function index()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM Vaksin
            ORDER BY id_vaksin DESC
        ");

        $vaksin = $stmt->fetchAll();

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/vaksin/index.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function create()
    {
        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/vaksin/tambah.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location:index.php?action=vaksin");
            exit;
        }

        $stmt = $this->db->prepare("
            INSERT INTO Vaksin
            (
                nama_vaksin,
                jadwal,
                keterangan
            )
            VALUES
            (
                :nama_vaksin,
                :jadwal,
                :keterangan
            )
        ");

        $stmt->execute([
            'nama_vaksin' => $_POST['nama_vaksin'],
            'jadwal'      => $_POST['jadwal'],
            'keterangan'  => $_POST['keterangan']
        ]);

        header("Location:index.php?action=vaksin&success=Data vaksin berhasil ditambahkan");
        exit;
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM Vaksin
            WHERE id_vaksin = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $vaksin = $stmt->fetch();
        if (!$vaksin) {
            header("Location:index.php?action=vaksin&error=Data vaksin tidak ditemukan");
            exit;
        }
        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/vaksin/edit.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location:index.php?action=vaksin");
            exit;
        }

        $stmt = $this->db->prepare("
            UPDATE Vaksin
            SET
                nama_vaksin = :nama_vaksin,
                jadwal = :jadwal,
                keterangan = :keterangan
            WHERE id_vaksin = :id
        ");

        $stmt->execute([
            'id'          => $_POST['id_vaksin'],
            'nama_vaksin' => $_POST['nama_vaksin'],
            'jadwal'      => $_POST['jadwal'],
            'keterangan'  => $_POST['keterangan']
        ]);

        header("Location:index.php?action=vaksin&success=Data vaksin berhasil diubah");
        exit;
    }

    public function delete($id)
    {
        try {

            $stmt = $this->db->prepare("
            DELETE FROM Vaksin
            WHERE id_vaksin = :id
        ");

            $stmt->execute([
                'id' => $id
            ]);

            header("Location:index.php?action=vaksin&success=Data vaksin berhasil dihapus");
        } catch (PDOException $e) {

            header("Location:index.php?action=vaksin&error=Data vaksin masih digunakan");
        }

        exit;
    }
}
