<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - PawCare</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { 
            background: #fff9f0; 
            display: flex; justify-content: center; align-items: center; 
            flex-direction: column; height: 100vh; margin: 0; 
        }

        h1 { font-weight: 700; margin: 0; color: #2d3436; }
        p { font-size: 14px; font-weight: 400; line-height: 20px; letter-spacing: 0.5px; margin: 20px 0 30px; }
        a { color: #333; font-size: 13px; text-decoration: none; margin: 15px 0; }
        
        button {
            border-radius: 20px;
            border: 1px solid #ff9f43;
            background-color: #ff9f43;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
            cursor: pointer;
        }
        button:active { transform: scale(0.95); }
        button:focus { outline: none; }
        button.ghost { background-color: transparent; border-color: #FFFFFF; }

        form {
            background-color: #FFFFFF;
            display: flex; align-items: center; justify-content: center; flex-direction: column;
            padding: 0 50px; height: 100%; text-align: center;
        }

        input {
            background-color: #eee; border: none; padding: 12px 15px;
            margin: 8px 0; width: 100%; border-radius: 8px; outline: none;
        }
        
        .alert-error { width: 100%; padding: 10px; background: #ff7675; color: white; border-radius: 8px; font-size: 12px; margin-bottom: 10px; }

        /* Struktur Kontainer Slider Utama */
        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            width: 850px;
            max-width: 100%;
            min-height: 550px;
        }

        .form-container { position: absolute; top: 0; height: 100%; transition: all 0.6s ease-in-out; }

        /* Panel Kiri (Login) */
        .sign-in-container { left: 0; width: 50%; z-index: 2; }
        .container.right-panel-active .sign-in-container { transform: translateX(100%); }

        /* Panel Kanan (Registrasi) */
        .sign-up-container { left: 0; width: 50%; opacity: 0; z-index: 1; }
        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        /* Overlay Sliding */
        .overlay-container {
            position: absolute; top: 0; left: 50%; width: 50%; height: 100%;
            overflow: hidden; transition: transform 0.6s ease-in-out; z-index: 100;
        }
        .container.right-panel-active .overlay-container { transform: translateX(-100%); }

        .overlay {
            background: #ff9f43;
            background: linear-gradient(to right, #f39c12, #e67e22);
            background-repeat: no-repeat; background-size: cover; background-position: 0 0;
            color: #FFFFFF; position: relative; left: -100%; height: 100%; width: 200%;
            transform: translateX(0); transition: transform 0.6s ease-in-out;
        }
        .container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute; display: flex; align-items: center; justify-content: center;
            flex-direction: column; padding: 0 40px; text-align: center;
            top: 0; height: 100%; width: 50%; transform: translateX(0); transition: transform 0.6s ease-in-out;
        }

        .overlay-left { transform: translateX(-20%); }
        .container.right-panel-active .overlay-left { transform: translateX(0); }

        .overlay-right { right: 0; transform: translateX(0); }
        .container.right-panel-active .overlay-right { transform: translateX(20%); }
        
        .back-home { position: absolute; top: 20px; left: 20px; color: #e67e22; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 5px;}
    </style>
</head>
<body>

    <a href="index.php" class="back-home">&larr; Back to Home</a>

    <div class="container" id="container">
        
        <div class="form-container sign-up-container">
            <form action="index.php?page=process_register" method="POST">
                <h1>Create Account</h1>
                <p style="margin-bottom:15px; margin-top:5px; color:#b2bec3;">Join our pet lover community</p>
                <input type="text" name="username" placeholder="Username" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="hidden" name="role" value="User">
                <button type="submit" style="margin-top: 15px;">Sign Up</button>
            </form>
        </div>
        
        <div class="form-container sign-in-container">
            <form action="index.php?page=process_login" method="POST">
                <h1>Sign in</h1>
                <p style="margin-bottom:15px; margin-top:5px; color:#b2bec3;">Login to manage your adoptions</p>
                
                <?php if (isset($_GET['registered'])): ?>
                    <div class="alert-error" style="background:#38a169;">Akun berhasil dibuat. Silakan login dan lengkapi verifikasi data Anda.</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert-error">Username atau password salah!</div>
                <?php endif; ?>

                <input type="text" name="username" placeholder="Username" required autocomplete="off" />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Forgot your password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>
        
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        // JavaScript untuk mengaktifkan animasi slider
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        // Menambahkan class 'right-panel-active' untuk menggeser overlay ke kiri
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        // Menghapus class 'right-panel-active' untuk menggeser overlay kembali ke kanan
        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });

        // Clear chatbot sessionStorage data upon arrival at login page
        sessionStorage.removeItem('pawbot_chat_history');
        sessionStorage.removeItem('pawbot_chat_active');
    </script>
</body>
</html>