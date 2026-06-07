<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - PawCare</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-login { width: 100%; padding: 10px; background-color: #28a745; border: none; color: white; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn-login:hover { background-color: #218838; }
        .alert { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-size: 14px; }
        .link-login { display: block; text-align: center; margin-top: 15px; font-size: 14px; color: #4e73df; text-decoration: none; }
        .link-login:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Buat Akun Baru</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert">
            <?= htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=register_process" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="nama_pengguna" required autocomplete="off" placeholder="Masukkan username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="kata_sandi" required placeholder="Buat password">
        </div>
        <div class="form-group">
            <label for="role">Hak Akses</label>
            <select id="role" name="status_pengguna" required>
                <option value="" disabled selected>Pilih hak akses</option>
                <option value="Superadmin">Superadmin</option>
                <option value="Staff">Staff</option>
                <option value="User">User (Pengadopsi)</option>
            </select>
        </div>
        <button type="submit" class="btn-login">Daftar Akun</button>
        
        <a href="index.php?action=login" class="link-login">Sudah punya akun? Kembali ke Login</a>
    </form>
</div>

</body>
</html>