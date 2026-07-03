<?php
require __DIR__ . '/../config/connect.php';
require __DIR__ . '/../app/models/PembayaranModel.php';

$pdo->beginTransaction();
try {
    $m = new PembayaranModel();
    $ref = 'demo_' . uniqid();
    $id = $m->create([
        'id_pengadopsi' => null,
        'metode' => 'QRIS',
        'provider' => 'QRIS',
        'reference' => $ref,
        'amount' => 150000,
        'status' => 'Pending',
        'metadata' => ['qris_svg' => 'data:image/svg+xml;<svg>...demo</svg>']
    ]);

    $created = $m->getByReference($ref);
    echo "CREATED_ID=" . $id . "\n";
    echo "CREATED_REF=" . $created['reference'] . "\n";
    echo "CREATED_STATUS=" . $created['status'] . "\n";
    echo "CREATED_META=" . ($created['metadata'] ?? 'NULL') . "\n";

    $m->updateStatusByReference($ref, 'Success', ['webhook_source' => 'demo', 'status' => 'Success']);
    $updated = $m->getByReference($ref);
    echo "UPDATED_STATUS=" . $updated['status'] . "\n";
    echo "UPDATED_META=" . ($updated['metadata'] ?? 'NULL') . "\n";

    $pdo->rollBack();
    echo "SIMULATION_OK\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo 'ERROR: ' . $e->getMessage() . "\n";
    exit(1);
}
