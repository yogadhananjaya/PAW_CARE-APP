<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<style>
.jk-page { --blue-50: #eff6ff; --blue-100: #dbeafe; --blue-500: #3b82f6; --blue-600: #2563eb;
  --gray-50: #f9fafb; --gray-100: #f3f4f6; --gray-200: #e5e7eb; --gray-400: #9ca3af; --gray-500: #6b7280; --gray-600: #4b5563; --gray-700: #374151; --gray-800: #1f2937;
  --amber-50: #fffbeb; --amber-100: #fef3c7; --amber-500: #f59e0b; --amber-600: #d97706;
  --emerald-50: #ecfdf5; --emerald-100: #d1fae5; --emerald-500: #10b981; --emerald-600: #059669;
  --red-50: #fef2f2; --red-100: #fee2e2; --red-500: #ef4444; --red-600: #dc2626;
}

.jk-page { margin-left: 260px; padding: 36px 44px; min-height: 100vh; background: #f7f8fa; font-family: 'Inter', 'Plus Jakarta Sans', 'Segoe UI', sans-serif; }

.jk-page .page-title { font-size: 28px; font-weight: 700; color: #111827; letter-spacing: -0.4px; margin-bottom: 4px; }
.jk-page .page-sub   { font-size: 14px; color: #6b7280; margin-bottom: 32px; }

.jk-section { background: #fff; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03); border: 1px solid #e5e7eb; margin-bottom: 24px; overflow: hidden; }

.jk-section .section-head { display: flex; align-items: center; gap: 10px; padding: 18px 24px; border-bottom: 1px solid #f3f4f6; }
.jk-section .section-head .s-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 17px; }
.jk-section .section-head .s-icon.pending   { background: #fffbeb; color: #d97706; }
.jk-section .section-head .s-icon.confirmed { background: #eff6ff; color: #2563eb; }
.jk-section .section-head .s-icon.history   { background: #f3f4f6; color: #4b5563; }
.jk-section .section-head h3 { font-size: 16px; font-weight: 700; color: #111827; }
.jk-section .section-head .count { font-size: 13px; color: #9ca3af; font-weight: 500; margin-left: 4px; }

.jk-table { width: 100%; border-collapse: collapse; }
.jk-table th, .jk-table td { padding: 14px 20px; text-align: left; font-size: 13.5px; vertical-align: middle; }
.jk-table thead { background: #fafbfc; }
.jk-table th { font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; }
.jk-table tbody tr { border-top: 1px solid #f3f4f6; transition: background 0.15s; }
.jk-table tbody tr:hover { background: #fafbfc; }

.jk-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.jk-badge.menunggu     { background: #fffbeb; color: #b45309; }
.jk-badge.dikonfirmasi { background: #eff6ff; color: #1d4ed8; }
.jk-badge.selesai      { background: #ecfdf5; color: #047857; }
.jk-badge.batal        { background: #fef2f2; color: #b91c1c; }

.jk-actions { display: flex; gap: 6px; }
.jk-btn-icon { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: all 0.15s; background: transparent; color: #6b7280; text-decoration: none; }
.jk-btn-icon:hover { background: #f3f4f6; color: #111827; }
.jk-btn-icon.danger:hover { background: #fef2f2; color: #dc2626; }
.jk-btn-icon svg { width: 15px; height: 15px; }

.jk-empty { text-align: center; padding: 40px 20px; color: #9ca3af; }
.jk-empty .empty-icon { font-size: 32px; margin-bottom: 8px; opacity: 0.6; }
.jk-empty p { font-size: 14px; margin: 0; }

.btn-add {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 20px; border-radius: 10px; font-size: 14px; font-weight: 600;
  background: #2563eb; color: #fff; text-decoration: none; transition: background 0.2s;
}
.btn-add:hover { background: #1d4ed8; }

.jk-page .header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
</style>

<div class="jk-page">
  <div class="header-row">
    <div>
      <div class="page-title">Kelola Jadwal Pertemuan Adopter</div>
      <div class="page-sub">Konfirmasi pengajuan kunjungan dari calon adopter atau jemput hewan.</div>
    </div>
    <?php if ($_SESSION['role'] === 'SuperAdmin'): ?>
    <a href="index.php?page=jadwal_kunjungan_create" class="btn-add">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tambah Jadwal
    </a>
    <?php endif; ?>
  </div>

  <?php if (isset($_GET['confirmed'])): ?>
    <div style="background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; border-radius:10px; padding:12px 18px; margin-bottom:20px; font-weight:600; font-size:14px;">
      ✅ Jadwal kunjungan berhasil dikonfirmasi.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['success_complete'])): ?>
    <div style="background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; border-radius:10px; padding:12px 18px; margin-bottom:20px; font-weight:600; font-size:14px;">
      ✅ Pertemuan kunjungan telah sukses dilaksanakan.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['cancelled'])): ?>
    <div style="background:#fef2f2; color:#991b1b; border:1px solid #fecaca; border-radius:10px; padding:12px 18px; margin-bottom:20px; font-weight:600; font-size:14px;">
      ⚠️ Jadwal kunjungan berhasil dibatalkan.
    </div>
  <?php endif; ?>

  <!-- ========== SECTION 1: PENDING ========== -->
  <div class="jk-section">
    <div class="section-head">
      <div class="s-icon pending">&#9201;</div>
      <h3>Menunggu Konfirmasi (Pending)</h3>
      <span class="count"><?= count($pending) ?> pengajuan</span>
    </div>
    <?php if (empty($pending)): ?>
      <div class="jk-empty">
        <div class="empty-icon">&#128337;</div>
        <p>Tidak ada pengajuan kunjungan yang menanti persetujuan.</p>
      </div>
    <?php else: ?>
      <div style="overflow-x:auto;">
        <table class="jk-table">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th>Kode</th>
              <th>Adopter</th>
              <th>Hewan</th>
              <th>Metode / Tujuan</th>
              <th>Tanggal &amp; Waktu</th>
              <?php if ($_SESSION['role'] === 'SuperAdmin'): ?>
              <th style="width:100px;">Aksi</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($pending as $r): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($r['kode_jadwal_kunjungan'] ?? '') ?></td>
              <td><strong><?= htmlspecialchars($r['nama_pengadopsi']) ?></strong></td>
              <td><?= htmlspecialchars($r['nama_hewan']) ?></td>
              <td>
                <?= htmlspecialchars($r['metode']) ?>
                <?php if (!empty($r['alamat_tujuan'])): ?>
                  <br><small style="color:#9ca3af;"><?= htmlspecialchars($r['alamat_tujuan']) ?></small>
                <?php endif; ?>
              </td>
              <td><?= date('d M Y, H:i', strtotime($r['tanggal_jadwal'])) ?></td>
              <?php if ($_SESSION['role'] === 'SuperAdmin'): ?>
              <td>
                <div class="jk-actions">
                  <!-- Tombol Konfirmasi 1-klik (hanya muncul di baris Menunggu) -->
                  <a href="index.php?page=jadwal_kunjungan_confirm&id=<?= $r['id_jadwal'] ?>" class="jk-btn-icon" style="background:#ecfdf5; color:#059669;" title="Konfirmasi Jadwal" onclick="return confirm('Konfirmasi jadwal kunjungan ini?')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  </a>
                  <a href="index.php?page=jadwal_kunjungan_edit&id=<?= $r['id_jadwal'] ?>" class="jk-btn-icon" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                  </a>
                  <a href="index.php?page=jadwal_kunjungan_reject&id=<?= $r['id_jadwal'] ?>" class="jk-btn-icon danger" onclick="return confirm('Batalkan jadwal kunjungan ini?')" title="Batalkan Kunjungan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </a>
                </div>
              </td>
              <?php endif; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- ========== SECTION 2: CONFIRMED ========== -->
  <div class="jk-section">
    <div class="section-head">
      <div class="s-icon confirmed">&#128197;</div>
      <h3>Kunjungan Dikonfirmasi (Agenda Datang)</h3>
      <span class="count"><?= count($confirmed) ?> agenda</span>
    </div>
    <?php if (empty($confirmed)): ?>
      <div class="jk-empty">
        <div class="empty-icon">&#128197;</div>
        <p>Tidak ada agenda kunjungan dalam waktu dekat.</p>
      </div>
    <?php else: ?>
      <div style="overflow-x:auto;">
        <table class="jk-table">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th>Kode</th>
              <th>Adopter</th>
              <th>Hewan</th>
              <th>Metode / Tujuan</th>
              <th>Tanggal &amp; Waktu</th>
              <?php if ($_SESSION['role'] === 'SuperAdmin'): ?>
              <th style="width:100px;">Aksi</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($confirmed as $r): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($r['kode_jadwal_kunjungan'] ?? '') ?></td>
              <td><strong><?= htmlspecialchars($r['nama_pengadopsi']) ?></strong></td>
              <td><?= htmlspecialchars($r['nama_hewan']) ?></td>
              <td>
                <?= htmlspecialchars($r['metode']) ?>
                <?php if (!empty($r['alamat_tujuan'])): ?>
                  <br><small style="color:#9ca3af;"><?= htmlspecialchars($r['alamat_tujuan']) ?></small>
                <?php endif; ?>
              </td>
              <td><?= date('d M Y, H:i', strtotime($r['tanggal_jadwal'])) ?></td>
              <?php if ($_SESSION['role'] === 'SuperAdmin'): ?>
              <td>
                <div class="jk-actions">
                  <!-- Tombol Selesai Kunjungan -->
                  <a href="index.php?page=jadwal_kunjungan_complete&id=<?= $r['id_jadwal'] ?>" class="jk-btn-icon" style="background:#eff6ff; color:#2563eb;" title="Tandai Selesai" onclick="return confirm('Tandai pertemuan kunjungan ini sudah selesai dilaksanakan?')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  </a>
                  <a href="index.php?page=jadwal_kunjungan_edit&id=<?= $r['id_jadwal'] ?>" class="jk-btn-icon" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                  </a>
                  <a href="index.php?page=jadwal_kunjungan_reject&id=<?= $r['id_jadwal'] ?>" class="jk-btn-icon danger" onclick="return confirm('Batalkan jadwal kunjungan ini?')" title="Batalkan Kunjungan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </a>
                </div>
              </td>
              <?php endif; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- ========== SECTION 3: HISTORY ========== -->
  <div class="jk-section">
    <div class="section-head">
      <div class="s-icon history">&#128338;</div>
      <h3>Riwayat Kunjungan</h3>
      <span class="count"><?= count($history) ?> catatan</span>
    </div>
    <?php if (empty($history)): ?>
      <div class="jk-empty">
        <div class="empty-icon">&#128210;</div>
        <p>Belum ada riwayat kunjungan.</p>
      </div>
    <?php else: ?>
      <div style="overflow-x:auto;">
        <table class="jk-table">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th>Kode</th>
              <th>Waktu Jadwal</th>
              <th>Adopter</th>
              <th>Hewan</th>
              <th>Metode</th>
              <th>Petugas</th>
              <th>Status Akhir</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($history as $r): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($r['kode_jadwal_kunjungan'] ?? '') ?></td>
              <td><?= date('d M Y, H:i', strtotime($r['tanggal_jadwal'])) ?></td>
              <td><strong><?= htmlspecialchars($r['nama_pengadopsi']) ?></strong></td>
              <td><?= htmlspecialchars($r['nama_hewan']) ?></td>
              <td><?= htmlspecialchars($r['metode']) ?></td>
              <td><?= htmlspecialchars($r['nama_petugas'] ?? '—') ?></td>
              <td>
                <span class="jk-badge <?= strtolower($r['status_jadwal']) ?>"><?= htmlspecialchars($r['status_jadwal']) ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
