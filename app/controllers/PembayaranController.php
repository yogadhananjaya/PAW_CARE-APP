<?php
require_once __DIR__ . '/../models/PembayaranModel.php';

class PembayaranController {
    private $m;

    public function __construct() {
        $this->m = new PembayaranModel();
    }

    public function index() {
        // list pembayaran untuk admin/user
        $stmt = $GLOBALS['pdo']->query("SELECT * FROM pembayaran ORDER BY id_pembayaran DESC");
        $data = $stmt->fetchAll();
        include __DIR__ . '/../../views/pembayaran/index.php';
    }

    public function create() {
        // quick-create via GET for demo: ?amount=160000&metode=VA|QRIS|GOPAY|OVO|CreditCard
        // NOTE: This is a local simulator. For production, replace this flow with calls
        // to your payment gateway SDK / API (Midtrans, Xendit, etc.) and handle
        // real callbacks/webhooks with signature verification.
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['amount']) && isset($_GET['metode'])) {
            $amount = floatval($_GET['amount']);
            $metode = $_GET['metode'];
            
            $id_pengadopsi = null;
            if (isset($_SESSION['user_id'])) {
                $stmt_adopter = $GLOBALS['pdo']->prepare("SELECT id_pengadopsi FROM pengadopsi WHERE id_pengguna = ?");
                $stmt_adopter->execute([$_SESSION['user_id']]);
                $adopter = $stmt_adopter->fetch();
                if ($adopter) {
                    $id_pengadopsi = $adopter['id_pengadopsi'];
                }
            }

            $provider = $metode;
            $reference = uniqid('pay_');

            // metadata holds provider-specific response data (VA number, deeplink, QR svg)
            $metadata = [];
            // Simulate provider behavior
            if ($metode === 'VA' || $metode === 'Transfer' || stripos($metode, 'Bank') !== false) {
                $va = 'VA' . rand(10000000,99999999);
                $metadata['va_number'] = $va;
            } elseif ($metode === 'CreditCard') {
                $metadata['auth_required'] = true;
            } elseif (in_array($metode, ['GOPAY','OVO'])) {
                $metadata['deeplink'] = $provider . '://pay?ref=' . $reference;
            } elseif ($metode === 'QRIS' || stripos($metode, 'QRIS') !== false) {
                $qr_payload = 'PawCare|'.$reference.'|'.$amount;
                $metadata['qris_svg'] = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . urlencode($qr_payload);
            }

            if (isset($_GET['id_hewan'])) {
                $metadata['id_hewan'] = intval($_GET['id_hewan']);
            }

            // Simpan record pembayaran dengan status 'Pending'. Reference digunakan
            // untuk menautkan update webhook yang datang kemudian.
            $this->m->create([
                'id_pengadopsi' => $id_pengadopsi,
                'metode' => $metode,
                'provider' => $provider,
                'reference' => $reference,
                'amount' => $amount,
                'status' => 'Pending',
                'metadata' => $metadata
            ]);

            header('Location: index.php?page=pembayaran_result&ref=' . urlencode($reference));
            exit;
        }

        // normal form view
        include __DIR__ . '/../../views/pembayaran/create.php';
    }

    public function result() {
        $ref = $_GET['ref'] ?? null;
        $data = $this->m->getByReference($ref);
        include __DIR__ . '/../../views/pembayaran/result.php';
    }

    public function callback() {
        // Accept POST webhook with JSON {reference, status}
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || empty($input['reference'])) {
            http_response_code(400);
            echo json_encode(['error' => 'invalid payload']);
            exit;
        }
        $ref = $input['reference'];
        $status = $input['status'] ?? 'Failed';
        // Update payment status based on webhook. In production verify signature.
        $this->m->updateStatusByReference($ref, $status, $input);
        echo json_encode(['ok' => true]);
        exit;
    }

}

?>
