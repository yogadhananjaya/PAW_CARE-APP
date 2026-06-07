<?php
// Generate Captcha Penjumlahan Sederhana
if (!isset($_SESSION['captcha_hasil'])) {
    $angka1 = rand(1, 9);
    $angka2 = rand(1, 9);
    $_SESSION['captcha_tanya'] = "$angka1 + $angka2";
    $_SESSION['captcha_hasil'] = $angka1 + $angka2;
}
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentikasi - PawCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Konfigurasi Warna via CSS Variables untuk Animasi */
        :root {
            --paw-krem: #FDFBF7;
            --paw-krem-gelap: #EFE9DB;
            --paw-hitam: #1A1A1A;
            --paw-putih: #FFFFFF;
            --paw-merah: #DE3B3B;
        }

        /* Styling Khusus Animasi Sliding */
        .auth-container {
            background-color: var(--paw-putih);
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.15), 0 10px 10px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            width: 800px;
            max-width: 100%;
            min-height: 550px;
        }

        .form-container { position: absolute; top: 0; height: 100%; transition: all 0.6s ease-in-out; }
        .sign-in-container { left: 0; width: 50%; z-index: 2; }
        .sign-up-container { left: 0; width: 50%; opacity: 0; z-index: 1; }

        .auth-container.right-panel-active .sign-in-container { transform: translateX(100%); }
        .auth-container.right-panel-active .sign-up-container { transform: translateX(100%); opacity: 1; z-index: 5; animation: show 0.6s; }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        .overlay-container { position: absolute; top: 0; left: 50%; width: 50%; height: 100%; overflow: hidden; transition: transform 0.6s ease-in-out; z-index: 100; }
        .auth-container.right-panel-active .overlay-container { transform: translateX(-100%); }

        /* Background Merah pada Panel Geser */
        .overlay {
            background: var(--paw-merah);
            background: linear-gradient(to right, #DE3B3B, #b82d2d);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }
        .auth-container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel { position: absolute; display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 0 40px; text-align: center; top: 0; height: 100%; width: 50%; transform: translateX(0); transition: transform 0.6s ease-in-out; }
        .overlay-left { transform: translateX(-20%); }
        .auth-container.right-panel-active .overlay-left { transform: translateX(0); }
        .overlay-right { right: 0; transform: translateX(0); }
        .auth-container.right-panel-active .overlay-right { transform: translateX(20%); }

        /* Styling Form Input */
        .auth-form { background-color: var(--paw-putih); display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 0 40px; height: 100%; text-align: center; }
        .auth-input { background-color: var(--paw-krem); border: 1px solid var(--paw-krem-gelap); padding: 12px 15px; margin: 8px 0; width: 100%; border-radius: 8px; outline: none; transition: border 0.3s; color: var(--paw-hitam); }
        .auth-input:focus { border-color: var(--paw-hitam); }
        .btn-ghost { background-color: transparent; border-color: #FFFFFF; }
    </style>
</head>
<body class="h-screen w-full bg-paw-krem-utama bg-[url('assets/img/background-placeholder.jpg')] bg-cover bg-center flex justify-center items-center relative">

    <div class="absolute inset-0 bg-paw-krem-utama/80 backdrop-blur-sm"></div>

    <div class="auth-container z-10" id="container">
        
        <div class="form-container sign-up-container">
            <form action="index.php?action=register_process" method="POST" class="auth-form px-10">
                <h2 class="text-3xl font-black text-[#1A1A1A] mb-2">Buat Akun User</h2>
                <p class="text-xs text-gray-500 mb-4">Daftar untuk mulai mengadopsi hewan.</p>
                
                <?php if (isset($_GET['error']) && isset($_GET['from']) && $_GET['from'] == 'register'): ?>
                    <p class="text-xs text-red-500 mb-2 font-bold"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>

                <div class="grid grid-cols-2 gap-2 w-full">
                    <input type="text" name="nama_pengguna" placeholder="Username" class="auth-input text-sm" required autocomplete="off" />
                    <input type="email" name="email" placeholder="Alamat Email" class="auth-input text-sm" required />
                    
                    <input type="password" name="kata_sandi" placeholder="Password" class="auth-input text-sm" required />
                    <input type="text" name="no_telp" placeholder="Nomor Telepon/WA" class="auth-input text-sm" required />
                    
                    <input type="date" name="tgl_lahir" class="auth-input text-sm text-gray-500" required title="Tanggal Lahir" />
                    <select name="jenis_kelamin" class="auth-input text-sm cursor-pointer" required>
                        <option value="" disabled selected>Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                
                <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 mt-2 flex justify-between items-center">
                    <label class="text-sm font-bold text-gray-700">Berapa <?= $_SESSION['captcha_tanya']; ?>?</label>
                    <input type="number" name="captcha_jawab" placeholder="Jawab" class="w-20 border border-gray-300 rounded px-2 py-1 text-center outline-none focus:border-paw-hitam" required>
                </div>
                
                <button type="submit" class="mt-4 bg-[#1A1A1A] text-white px-10 py-3 rounded-full font-bold hover:bg-gray-800 transition shadow-md w-full">Daftar Sekarang</button>
            </form>
        </div>
        
        <div class="form-container sign-in-container">
            <form action="index.php?action=login_process" method="POST" class="auth-form">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4 text-[#DE3B3B] text-2xl"><i class="fa-solid fa-paw"></i></div>
                <h2 class="text-3xl font-black text-[#1A1A1A] mb-4">Masuk</h2>
                
                <?php if (isset($_GET['error']) && (!isset($_GET['from']) || $_GET['from'] != 'register')): ?>
                    <p class="text-xs text-red-500 mb-2 font-bold"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <p class="text-xs text-green-600 mb-2 font-bold"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_GET['success']); ?></p>
                <?php endif; ?>

                <input type="text" name="nama_pengguna" placeholder="Username" class="auth-input" required autocomplete="off" />
                <input type="password" name="kata_sandi" placeholder="Password" class="auth-input" required />
                
                <button type="submit" class="mt-6 bg-[#DE3B3B] text-white px-10 py-3 rounded-full font-bold hover:bg-red-700 transition shadow-md w-full">Masuk</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h2 class="text-3xl font-bold mb-4">Sudah Punya Akun?</h2>
                    <p class="mb-6 text-sm opacity-90">Masuk dengan akun yang sudah terdaftar untuk mengakses dashboard shelter.</p>
                    <button class="border-2 border-white text-white px-10 py-3 rounded-full font-bold hover:bg-white hover:text-[#DE3B3B] transition" id="signIn">Masuk</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h2 class="text-3xl font-bold mb-4">Halo, Kawan!</h2>
                    <p class="mb-6 text-sm opacity-90">Belum memiliki akun? Daftarkan diri Anda sekarang untuk mulai membantu hewan-hewan kami.</p>
                    <button class="border-2 border-white text-white px-10 py-3 rounded-full font-bold hover:bg-white hover:text-[#DE3B3B] transition" id="signUp">Buat Akun</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        // Event Listener untuk menggeser panel
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });

        // Logika Pintar: Jika ada error dari form register, otomatis buka panel register saat diload
        <?php if(isset($_GET['from']) && $_GET['from'] == 'register'): ?>
            container.classList.add("right-panel-active");
        <?php endif; ?>
    </script>
</body>
</html>