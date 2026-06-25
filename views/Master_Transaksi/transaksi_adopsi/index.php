op
<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <div>
            <h2>Manajemen Transaksi Adopsi & Kontrak</h2>
            <p>Inisiasi adopsi baru dan tandatangani e-contract yang telah ditandatangani adopter.</p>
        </div>
    </header>



    <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 25px; align-items: start;">
        <!-- Kolom Kiri: Form Inisiasi Adopsi -->
        <div class="card" style="padding: 25px; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <h3 style="font-size: 16px; font-weight: 700; color: #4f46e5; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                ➕ Inisiasi Adopsi Baru
            </h3>
            <form action="index.php?page=transaksi_adopsi_create" method="POST">
                <!-- Adopter -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">Adopter (Hanya Terverifikasi)</label>
                    <select name="id_pengadopsi" class="form-control" required style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; font-size: 14px;">
                        <option value="">-- Pilih Adopter --</option>
                        <?php foreach($a as $ad): ?>
                            <option value="<?= $ad['id_pengadopsi'] ?>"><?= htmlspecialchars($ad['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Hewan -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">Hewan (Tersedia / Proses)</label>
                    <select name="id_hewan" class="form-control" required style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; font-size: 14px;">
                        <option value="">-- Pilih Hewan --</option>
                        <?php foreach($h as $hw): ?>
                            <option value="<?= $hw['id_hewan'] ?>"><?= htmlspecialchars($hw['nama_hewan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tanggal Mulai Adopsi -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">Tanggal Mulai Adopsi</label>
                    <input type="date" name="tanggal_adopsi" class="form-control" value="<?= date('Y-m-d') ?>" required style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; font-size: 14px;">
                </div>

                <button type="submit" class="btn" style="width: 100%; background: #4f46e5; color: #ffffff; font-weight: 600; padding: 12px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; transition: background 0.2s; text-align: center;">
                    Mulai Proses Adopsi
                </button>
            </form>
        </div>

        <!-- Kolom Kanan: Daftar Transaksi Terdaftar -->
        <div class="card" style="padding: 25px; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                📋 Transaksi Adopsi Terdaftar
            </h3>
            <table class="crud-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Hewan</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Adopter</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Status Kontrak</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Staf</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; text-align: center; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($data) > 0): ?>
                        <?php foreach($data as $row): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <!-- HEWAN -->
                            <td style="padding: 16px 10px;">
                                <div style="font-weight: 700; color: #0f172a;"><?= htmlspecialchars($row['nama_hewan']) ?></div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;"><?= htmlspecialchars(date('d M Y', strtotime($row['tanggal_adopsi']))) ?></div>
                            </td>
                            
                            <!-- ADOPTER -->
                            <td style="padding: 16px 10px; font-size: 14px; font-weight: 500; color: #334155;">
                                <?= htmlspecialchars($row['nama_pengadopsi']) ?>
                            </td>
                            
                            <!-- STATUS KONTRAK -->
                            <td style="padding: 16px 10px;">
                                <?php
                                $statusText = strtoupper($row['status_kontrak']);
                                $bg = '#fff3cd'; $color = '#d97706';
                                if ($row['status_kontrak'] == 'Draft') {
                                    $statusText = 'MENUNGGU ADOPTER';
                                    $bg = '#fff4e6'; $color = '#d97706';
                                } elseif ($row['status_kontrak'] == 'Ditandatangani') {
                                    $statusText = 'DITANDATANGANI';
                                    $bg = '#e0f2fe'; $color = '#0369a1';
                                } elseif ($row['status_kontrak'] == 'Aktif') {
                                    $statusText = 'AKTIF';
                                    $bg = '#dcfce7'; $color = '#15803d';
                                }
                                ?>
                                <span style="padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; background-color: <?= $bg ?>; color: <?= $color ?>; display: inline-block;">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            
                            <!-- STAF -->
                            <td style="padding: 16px 10px; font-size: 14px; color: #475569; font-weight: 500;">
                                <?= htmlspecialchars($row['nama_staf'] ?? 'Belum ditunjuk') ?>
                            </td>
                            
                            <!-- AKSI -->
                            <td style="padding: 16px 10px; text-align: center; white-space: nowrap;">
                                <a href="index.php?page=transaksi_adopsi_edit&id=<?= $row['id_adopsi'] ?>" class="btn" style="border: 1px solid #cbd5e1; background: #ffffff; color: #475569; padding: 6px 14px; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none; display: inline-block; cursor: pointer; transition: background 0.2s; margin-right: 4px;">
                                    Buka Kontrak
                                </a>
                                <a href="index.php?page=transaksi_adopsi_delete&id=<?= $row['id_adopsi'] ?>" class="btn" onclick="return confirm('Hapus transaksi adopsi ini?');" style="border: 1px solid #fecaca; background: #ffffff; color: #ef4444; padding: 6px 8px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; vertical-align: middle;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="padding: 30px; text-align: center; color: #94a3b8; font-size: 14px;">
                                Belum ada data transaksi adopsi terdaftar.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../../layouts/footer.php'; ?>