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
            <?php if (!empty($error_duplikat)): ?>
                <div style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:14px;">
                    ⚠️ <?= htmlspecialchars($error_duplikat) ?>
                </div>
            <?php endif; ?>
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

                <!-- Koordinator / Staf -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: block;">Koordinator Penanggung Jawab</label>
                    <select name="id_pengguna" class="form-control" required style="width:100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; font-size: 14px;">
                        <option value="">-- Pilih Koordinator --</option>
                        <?php foreach($coordinators as $c): ?>
                            <option value="<?= $c['id_pengguna'] ?>"><?= htmlspecialchars($c['nama_lengkap']) ?> (<?= htmlspecialchars($c['nama_pengguna']) ?>)</option>
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
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; width: 5%;">No</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Kode</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Hewan</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Adopter</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Status Kontrak</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Staf</th>
                        <th style="padding: 12px 10px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; text-align: center; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($data) > 0): ?>
                        <?php $no = 1; foreach($data as $row): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <!-- NO & KODE -->
                            <td style="padding: 16px 10px; font-size: 14px; font-weight: 500; color: #334155;">
                                <?= $no++ ?>
                            </td>
                            <td style="padding: 16px 10px; font-size: 14px; font-weight: 500; color: #334155;">
                                <?= htmlspecialchars($row['kode_transaksi_adopsi'] ?? '') ?>
                            </td>
                            
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
                                } elseif ($row['status_kontrak'] == 'Batal') {
                                    $statusText = 'BATAL / DITOLAK';
                                    $bg = '#fef2f2'; $color = '#b91c1c';
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
                                <?php if (($_SESSION['role'] ?? '') === 'SuperAdmin' && $row['status_kontrak'] !== 'Batal' && (empty($row['ttd_adopter']) || empty($row['ttd_admin']))): ?>
                                    <a href="index.php?page=transaksi_adopsi_reject&id=<?= $row['id_adopsi'] ?>" class="btn" onclick="return confirm('Batalkan transaksi adopsi ini?');" style="border: 1px solid #fecaca; background: #ffffff; color: #ef4444; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; vertical-align: middle;">
                                        Tolak
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="padding: 30px; text-align: center; color: #94a3b8; font-size: 14px;">
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