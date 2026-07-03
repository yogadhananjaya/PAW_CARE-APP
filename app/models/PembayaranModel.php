<?php
require_once __DIR__ . '/../../config/connect.php';

class PembayaranModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function create($data) {
        $kode = buat_kode_otomatis('pembayaran', 'kode_pembayaran', 'PM');
        $stmt = $this->pdo->prepare("INSERT INTO pembayaran (kode_pembayaran, id_pengadopsi, metode, provider, reference, amount, status, metadata, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $meta = isset($data['metadata']) ? json_encode($data['metadata']) : null;
        $stmt->execute([
            $kode,
            $data['id_pengadopsi'] ?? null,
            $data['metode'],
            $data['provider'] ?? null,
            $data['reference'] ?? uniqid('ref_'),
            $data['amount'],
            $data['status'] ?? 'Pending',
            $meta
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updateStatusByReference($reference, $status, $meta = null) {
        // Merge webhook payload into metadata JSON and update status.
        // Uses JSON_MERGE_PRESERVE so existing metadata is kept.
        $stmt = $this->pdo->prepare("UPDATE pembayaran SET status = ?, metadata = COALESCE(metadata, JSON_ARRAY()), metadata = JSON_MERGE_PRESERVE(metadata, ?), updated_at = NOW() WHERE reference = ?");
        $meta_json = json_encode(['webhook' => $meta]);
        return $stmt->execute([$status, $meta_json, $reference]);
    }

    public function getByReference($reference) {
        $stmt = $this->pdo->prepare("SELECT * FROM pembayaran WHERE reference = ?");
        $stmt->execute([$reference]);
        return $stmt->fetch();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pembayaran WHERE id_pembayaran = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}

?>
