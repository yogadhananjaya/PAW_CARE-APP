<?php include __DIR__ . '/../../layouts/header.php'; ?>
<?php include __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="main-wrapper">
    <header class="admin-header">
        <h2>Tambah Akun Pengguna</h2>
        <a href="index.php?page=pengguna" class="btn btn-secondary">&larr; Batal</a>
    </header>
    <div class="card" style="max-width: 700px;">
        <form action="index.php?page=pengguna_create" method="POST">
            <!-- Nama Lengkap -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" required placeholder="Contoh: Budi Santoso">
            </div>

            <!-- Nama Pengguna (username) -->
            <div class="form-group">
                <label>Nama Pengguna (Username)</label>
                <input type="text" name="nama_pengguna" class="form-control" required autocomplete="off" placeholder="Contoh: budi.santoso">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="kata_sandi" class="form-control" required>
            </div>

            <div style="display:flex; gap:20px;">
                <!-- Jabatan -->
                <div class="form-group" style="flex:1;">
                    <label>Jabatan</label>
                    <select name="jabatan" class="form-control" required>
                        <option value="Koordinator">Koordinator</option>
                        <option value="Perawat Hewan">Perawat Hewan</option>
                        <option value="SuperAdmin">SuperAdmin</option>
                    </select>
                </div>

                <!-- Role -->
                <div class="form-group" style="flex:1;">
                    <label>Role Akses</label>
                    <select name="role" class="form-control">
                        <option value="Pegawai">Pegawai</option>
                        <option value="SuperAdmin">SuperAdmin</option>
                    </select>
                </div>
            </div>

            <!-- Kontak -->
            <div class="form-group">
                <label>Nomor Kontak / HP</label>
                <input type="text" name="kontak" class="form-control" required placeholder="Contoh: 08123456789">
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status Akun</label>
                <select name="status" class="form-control">
                    <option value="Aktif">Aktif</option>
                    <option value="Suspended">Suspended</option>
                    <option value="Resign">Resign</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Simpan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>