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
        $res = $stmt->execute([$status, $meta_json, $reference]);

        if ($status === 'Success') {
            $pay = $this->getByReference($reference);
            if ($pay) {
                $pay_meta = json_decode($pay['metadata'] ?? 'null', true) ?: [];
                $id_hewan = $pay_meta['id_hewan'] ?? null;
                $id_pengadopsi = $pay['id_pengadopsi'] ?? null;

                if ($id_hewan) {
                    // Update status hewan jadi Diadopsi
                    $this->pdo->prepare("UPDATE hewan SET status_adopsi = 'Diadopsi' WHERE id_hewan = ?")->execute([$id_hewan]);

                    if ($id_pengadopsi) {
                        // Update status transaksi adopsi jadi Aktif
                        $this->pdo->prepare("UPDATE transaksi_adopsi SET status_kontrak = 'Aktif' WHERE id_hewan = ? AND id_pengadopsi = ? AND status_kontrak = 'Ditandatangani'")->execute([$id_hewan, $id_pengadopsi]);
                    }
                }
            }
        }

        return $res;
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
