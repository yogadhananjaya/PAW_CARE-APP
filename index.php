<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/config/connect.php';
require_once __DIR__ . '/config/auth.php';

sync_pengadopsi_session();

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    // ==================== HALAMAN UTAMA & AUTENTIKASI ====================
    case 'home':
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'SuperAdmin') header('Location: index.php?page=dashboard_superadmin');
            elseif ($_SESSION['role'] == 'Koordinator') header('Location: index.php?page=dashboard_koordinator');
            elseif (in_array($_SESSION['role'], ['Perawat', 'Perawat Hewan'])) header('Location: index.php?page=dashboard_staff');
            else header('Location: index.php?page=dashboard_user');
            exit;
        }
        include __DIR__ . '/views/user/dashboard_default.php';
        break;

    case 'landing':
        // Rute untuk kembali ke Landing Page (Halaman Utama) secara langsung
        include __DIR__ . '/views/user/dashboard_default.php';
        break;
        
    case 'login':
        if (isset($_SESSION['role'])) { header('Location: index.php?page=home'); exit; }
        include __DIR__ . '/views/login.php';
        break;
        
    case 'process_login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && login($_POST['username'], $_POST['password'])) {
            header('Location: index.php?page=home');
        } else {
            header('Location: index.php?page=login&error=1');
        }
        break;

    case 'process_register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/app/models/PengadopsiModel.php';
            $adopterModel = new PengadopsiModel();
            
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
                echo "<script>alert('Gagal: Username hanya boleh terdiri dari huruf, angka, underscore, dan panjang antara 4 sampai 20 karakter.'); window.history.back();</script>";
                exit;
            }

            // Validasi Password  
            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
                echo "<script>alert('Gagal: Password minimal harus 8 karakter dan mengandung kombinasi huruf serta angka.'); window.history.back();</script>";
                exit;
            }

            if ($adopterModel->isDuplicateUsername($username)) {
                echo "<script>alert('Gagal: Username \"" . htmlspecialchars($username) . "\" sudah terdaftar! Silakan gunakan username lain.'); window.history.back();</script>";
                exit;
            }

            // Hanya simpan ke pengadopsi — pengguna dibuat otomatis saat login pertama
            $adopterModel->insert([
                'nama_lengkap' => $username,
                'nama_pengguna' => $username,
                'alamat' => '-',
                'no_hp' => '-',
                'email' => $username . '@pawcare.com',
                'kata_sandi' => $password,
                'status_verifikasi' => 'Belum',
                'url_ktp' => null
            ]);
            
            header('Location: index.php?page=login&registered=1');
            exit;
        }
        break;
    
    case 'process_verifikasi':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $adopter_id = null;
            if (isset($_SESSION['pending_adopter_id'])) {
                $adopter_id = $_SESSION['pending_adopter_id'];
            } else {
                $stmt_adopter = $pdo->prepare("SELECT id_pengadopsi FROM pengadopsi WHERE id_pengguna = ?");
                $stmt_adopter->execute([$user_id]);
                $adopter_id = $stmt_adopter->fetchColumn();
            }
            
            if (!$adopter_id) {
                echo "<script>alert('Profil pengadopsi tidak ditemukan. Silakan logout dan coba kembali.'); window.location.href='index.php?page=dashboard_user';</script>";
                exit;
            }
            
            $nama_lengkap = trim($_POST['nama_lengkap']);
            $nik = trim($_POST['nik']);
            $no_hp = trim($_POST['no_hp']);
            $alamat = trim($_POST['alamat']);
            $email = trim($_POST['email'] ?? '');

            // 1. Validasi Nama Lengkap (hanya huruf dan spasi, min 3 karakter)
            if (!preg_match("/^[a-zA-Z\s]{3,100}$/", $nama_lengkap)) {
                echo "<script>alert('Gagal: Nama lengkap hanya boleh terdiri dari huruf dan spasi, minimal 3 karakter.'); window.history.back();</script>";
                exit;
            }

            // 2. Validasi NIK (harus tepat 16 digit angka)
            if (!preg_match("/^[0-9]{16}$/", $nik)) {
                echo "<script>alert('Gagal: NIK harus terdiri dari tepat 16 digit angka.'); window.history.back();</script>";
                exit;
            }

            // 3. Validasi Nomor HP / WhatsApp (angka saja, panjang 10-15 digit)
            if (!preg_match("/^[0-9]{10,15}$/", $no_hp)) {
                echo "<script>alert('Gagal: Nomor WhatsApp hanya boleh berisi angka dengan panjang 10 hingga 15 digit.'); window.history.back();</script>";
                exit;
            }

            // 4. Validasi Alamat (minimal 10 karakter)
            if (strlen($alamat) < 10) {
                echo "<script>alert('Gagal: Alamat domisili harus diisi lengkap, minimal 10 karakter.'); window.history.back();</script>";
                exit;
            }

            $url_ktp = null;
            if (isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto_ktp']['tmp_name'];
                $fileName = $_FILES['foto_ktp']['name'];
                $fileSize = $_FILES['foto_ktp']['size'];
                $fileType = $_FILES['foto_ktp']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    if ($fileSize <= 2 * 1024 * 1024) { // Maksimal 2MB
                        $uploadFileDir = __DIR__ . '/uploads/ktp/';
                        if (!is_dir($uploadFileDir)) {
                            mkdir($uploadFileDir, 0755, true);
                        }
                        $newFileName = 'ktp_' . $user_id . '_' . time() . '.' . $fileExtension;
                        $dest_path = $uploadFileDir . $newFileName;
                        
                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            $url_ktp = 'uploads/ktp/' . $newFileName;
                        }
                    } else {
                        echo "<script>alert('Gagal: Ukuran file KTP tidak boleh melebihi 2MB.'); window.history.back();</script>";
                        exit;
                    }
                } else {
                    echo "<script>alert('Gagal: Format file KTP hanya boleh JPG, JPEG, atau PNG.'); window.history.back();</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Gagal: Foto KTP wajib diunggah.'); window.history.back();</script>";
                exit;
            }
            
            // Lakukan update data diri lengkap pengadopsi yang sudah terbuat saat register (ubah ke status 'Menunggu')
            $stmt = $pdo->prepare("UPDATE pengadopsi SET nama_lengkap = ?, nik = ?, alamat = ?, no_hp = ?, email = ?, url_ktp = ?, status_verifikasi = 'Menunggu' WHERE id_pengadopsi = ?");
            $stmt->execute([
                trim($_POST['nama_lengkap']), 
                trim($_POST['nik']), 
                trim($_POST['alamat']), 
                trim($_POST['no_hp']),
                $email,
                $url_ktp,
                $adopter_id
            ]);
            
            // Langsung kembalikan ke dashboard user agar melihat status sukses
            header('Location: index.php?page=dashboard_user');
            exit;
        }
        break;
    case 'chatbot_api':
        header('Content-Type: application/json; charset=UTF-8');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['reply' => 'Sesi berakhir. Silakan login kembali untuk berinteraksi dengan PawBot.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['reply' => 'Metode tidak didukung.']);
            exit;
        }

        $user_message = trim($_POST['message'] ?? '');
        if (empty($user_message)) {
            echo json_encode(['reply' => 'Pesan tidak boleh kosong.']);
            exit;
        }
        
        // Tarik data hewan yang tersedia
        $stmt = $pdo->query("SELECT h.nama_hewan, j.nama_jenis, r.nama_ras, h.jenis_kelamin, h.estimasi_umur, h.deskripsi, h.hobi, h.funfact FROM hewan h JOIN jenis_hewan j ON h.id_jenis = j.id_jenis JOIN ras r ON h.id_ras = r.id_ras WHERE h.status_adopsi = 'Tersedia'");
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $db_context = "Data hewan di shelter saat ini:\n";
        foreach ($pets as $p) {
            $db_context .= "- Nama: {$p['nama_hewan']} | Spesies: {$p['nama_jenis']} ({$p['nama_ras']}) | Gender: {$p['jenis_kelamin']} | Umur: {$p['estimasi_umur']} bulan | Hobi: {$p['hobi']} | Fun Fact: {$p['funfact']} | Deskripsi: {$p['deskripsi']}\n";
        }

        $system_prompt = "Kamu adalah PawBot, asisten AI pemberi rekomendasi adopsi hewan dari PawCare. TUGAS UTAMA: Berikan rekomendasi hewan yang cocok berdasarkan preferensi atau pertanyaan user. ATURAN KETAT:\n1. Kamu HANYA BOLEH merekomendasikan hewan dari daftar 'Data hewan di shelter saat ini' yang diberikan.\n2. Jangan pernah menyebutkan atau merekomendasikan hewan yang tidak ada di daftar database tersebut.\n3. Jika user mencari spesifikasi atau kriteria hewan yang tidak tersedia, katakan dengan jujur bahwa kriteria tersebut sedang tidak tersedia di database, lalu tawarkan opsi hewan lain yang paling mendekati dari data yang ada.\n4. Jawablah dengan ramah, komunikatif, dan menggunakan bahasa Indonesia yang santun namun ringkas.";

        // ── Helper: rule-based offline matching ──────────────────────────────
        $offline_reply = function() use ($pets, $user_message) {
            $msg_lower = mb_strtolower($user_message, 'UTF-8');
            $matched_pets = [];

            // Keyword maps for Indonesian terms
            $keyword_groups = [
                'kucing'   => ['kucing', 'cat', 'kitty'],
                'anjing'   => ['anjing', 'dog', 'puppy'],
                'kelinci'  => ['kelinci', 'rabbit', 'bunny'],
                'jantan'   => ['jantan', 'male'],
                'betina'   => ['betina', 'female'],
                'muda'     => ['muda', 'kecil', 'bayi', 'kitten'],
                'tua'      => ['tua', 'dewasa'],
                'aktif'    => ['aktif', 'lincah', 'energik', 'bermain'],
                'manja'    => ['manja', 'penurut', 'tenang', 'jinak'],
                'all'      => ['ada hewan', 'hewan apa', 'daftar hewan', 'semua hewan', 'apa saja', 'hewan saja'],
            ];

            foreach ($pets as $p) {
                $haystack = mb_strtolower(
                    $p['nama_hewan'] . ' ' . $p['nama_jenis'] . ' ' . $p['nama_ras'] . ' ' .
                    $p['jenis_kelamin'] . ' ' . ($p['deskripsi'] ?? '') . ' ' .
                    ($p['hobi'] ?? '') . ' ' . ($p['funfact'] ?? ''),
                    'UTF-8'
                );
                $match = false;

                // Direct substring match
                if (mb_strpos($haystack, $msg_lower) !== false) {
                    $match = true;
                }

                // Keyword-group matching
                foreach ($keyword_groups as $group => $words) {
                    $inMsg = false; $inHay = false;
                    foreach ($words as $w) {
                        if (mb_strpos($msg_lower, $w) !== false) $inMsg = true;
                        if (mb_strpos($haystack, $w) !== false || mb_strpos($haystack, $group) !== false) $inHay = true;
                    }
                    if ($inMsg && $inHay) { $match = true; break; }
                }

                // Age match  (e.g. "2 bulan")
                if (preg_match('/(\d+)\s*bulan/', $msg_lower, $ageMatch)) {
                    if ((int)$ageMatch[1] === (int)$p['estimasi_umur']) $match = true;
                }

                // Individual pet name search
                if (mb_strpos($msg_lower, mb_strtolower($p['nama_hewan'], 'UTF-8')) !== false) $match = true;

                if ($match) $matched_pets[] = $p;
            }

            if (!empty($matched_pets)) {
                $reply = "🐾 Berikut rekomendasi hewan yang cocok dengan pencarian Anda:\n\n";
                foreach ($matched_pets as $idx => $mp) {
                    $num = $idx + 1;
                    $reply .= "{$num}. **{$mp['nama_hewan']}** ({$mp['nama_jenis']} - {$mp['nama_ras']})\n";
                    $reply .= "   • Gender: {$mp['jenis_kelamin']}\n";
                    $reply .= "   • Umur: {$mp['estimasi_umur']} bulan\n";
                    if (!empty($mp['hobi']))    $reply .= "   • Hobi: {$mp['hobi']}\n";
                    if (!empty($mp['funfact'])) $reply .= "   • Fun Fact: {$mp['funfact']}\n";
                    if (!empty($mp['deskripsi'])) $reply .= "   • Deskripsi: {$mp['deskripsi']}\n";
                    $reply .= "\n";
                }
                $reply .= "Silakan tanya lagi jika ingin rekomendasi berdasarkan jenis, gender, umur, atau karakter hewan 😊";
            } else {
                // General questions / greetings
                $greetings = ['halo', 'hai', 'hi', 'hello', 'selamat', 'pagi', 'siang', 'malam', 'apa kabar'];
                foreach ($greetings as $g) {
                    if (mb_strpos($msg_lower, $g) !== false) {
                        return "Halo! Saya PawBot 🐾 Asisten adopsi PawCare. Silakan tanyakan rekomendasi hewan yang ingin Anda adopsi, misalnya: 'kucing betina yang manja' atau 'anjing aktif'.";
                    }
                }
                $adopsi_words = ['adopsi', 'cara', 'prosedur', 'syarat', 'daftar', 'bagaimana'];
                foreach ($adopsi_words as $w) {
                    if (mb_strpos($msg_lower, $w) !== false) {
                        return "Untuk mengadopsi hewan di PawCare:\n1. Pastikan akun Anda sudah terverifikasi oleh admin.\n2. Pilih hewan di tab Katalog Hewan.\n3. Klik 'Adopsi Sekarang' dan ikuti panduan wizard.\n4. Jadwalkan kunjungan shelter.\n5. Tanda tangani kontrak adopsi digital.\n\nAda yang ingin ditanyakan lebih lanjut? 🐶";
                    }
                }
                $reply = "Maaf, saya belum menemukan hewan yang cocok dengan kata kunci tersebut.\n\n";
                $reply .= "Saat ini tersedia hewan berikut di shelter kami:\n";
                foreach (array_slice($pets, 0, 6) as $pet) {
                    $reply .= "• {$pet['nama_hewan']} ({$pet['nama_jenis']} - {$pet['nama_ras']})\n";
                }
                $reply .= "\nCoba tanya dengan kata kunci seperti: kucing, anjing, kelinci, jantan, betina, manja, aktif, atau nama hewan tertentu 🐾";
            }
            return $reply;
        };

        // ── Try Gemini API if key looks valid (starts with AIza) ─────────────
        $api_key = get_env_var('GEMINI_API_KEY', '');
        $use_gemini = !empty($api_key);

        if ($use_gemini) {
            $payload = [
                "contents" => [
                    ["role" => "user", "parts" => [["text" => $system_prompt . "\n\n" . $db_context . "\n\nPertanyaan User: " . $user_message]]]
                ],
                "generationConfig" => ["temperature" => 0.2]
            ];

            $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $api_key);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode($payload),
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT        => 10,
            ]);

            $response  = curl_exec($ch);
            $curl_err  = curl_errno($ch);
            curl_close($ch);

            if (!$curl_err && $response) {
                $result = json_decode($response, true);
                if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    echo json_encode(['reply' => $result['candidates'][0]['content']['parts'][0]['text']]);
                    exit;
                }
                // Key valid format tapi API menolak (quota/invalid) → fallback offline
            }
            // cURL error atau response kosong → fallback offline
        }

        // ── Offline fallback ─────────────────────────────────────────────────
        echo json_encode(['reply' => $offline_reply()]);
        exit;
        break;

    case 'logout':
        logout();
        header('Location: index.php');
        break;

    case 'donasi_proses':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_donatur = trim($_POST['nama_donatur'] ?? '');
            $nominal = floatval($_POST['nominal'] ?? 0);
            $metode_pembayaran = $_POST['metode_pembayaran'] ?? 'Transfer Bank';
            $keterangan = trim($_POST['keterangan'] ?? '');

            if (empty($nama_donatur) || strlen($nama_donatur) < 3 || !preg_match("/^[A-Za-z\s]+$/", $nama_donatur)) {
                echo "<script>alert('Gagal: Nama donatur harus diisi huruf minimal 3 karakter.'); window.history.back();</script>";
                exit;
            }
            if ($nominal < 10000) {
                echo "<script>alert('Gagal: Nominal donasi minimal Rp 10.000'); window.history.back();</script>";
                exit;
            }

            $kode = buat_kode_otomatis('donasi', 'kode_donasi', 'DN');
            $stmt = $pdo->prepare("INSERT INTO donasi (kode_donasi, nama_donatur, nominal, kategori, keterangan, tanggal, metode_pembayaran, status_konfirmasi) VALUES (?, ?, ?, 'Pemasukan', ?, CURDATE(), ?, 'Menunggu')");
            $stmt->execute([$kode, $nama_donatur, $nominal, $keterangan, $metode_pembayaran]);
            $id_donasi = $pdo->lastInsertId();

            header("Location: index.php?page=donasi_bayar&id=" . $id_donasi);
            exit;
        }
        break;

    case 'donasi_bayar':
        $id = intval($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM donasi WHERE id_donasi = ?");
        $stmt->execute([$id]);
        $donasi = $stmt->fetch();
        if (!$donasi) {
            echo "Donasi tidak ditemukan.";
            exit;
        }
        include __DIR__ . '/views/user/donasi_bayar.php';
        break;

    case 'donasi_sukses':
        $id = intval($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("UPDATE donasi SET status_konfirmasi = 'Dikonfirmasi' WHERE id_donasi = ?");
        $stmt->execute([$id]);
        echo "<script>alert('Terima kasih atas donasi Anda! Donasi Anda telah kami terima.'); window.location.href='index.php?page=home';</script>";
        exit;
        break;

    // ==================== DASHBOARD BERDASARKAN ROLE ====================
    case 'dashboard_superadmin':
        if (!check_access(['SuperAdmin'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/dashboard_superadmin.php';
        break;

    case 'dashboard_staff':
        if (!check_access(['Koordinator', 'Perawat', 'SuperAdmin'])) { header('Location: index.php?page=login'); exit; }
        if ($_SESSION['role'] == 'Koordinator') { header('Location: index.php?page=dashboard_koordinator'); exit; }
        include __DIR__ . '/views/user/dashboard_staff.php';
        break;

    case 'dashboard_koordinator':
        if (!check_access(['Koordinator', 'SuperAdmin'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/dashboard_koordinator.php';
        break;

    case 'dashboard_user':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/dashboard_user.php';
        break;

    case 'pembayaran':
        if (!check_access(['User','Koordinator','SuperAdmin','Perawat','Perawat Hewan'])) { header('Location: index.php?page=login'); exit; }
        require_once __DIR__ . '/app/controllers/PembayaranController.php';
        $pembayaranCtrl = new PembayaranController();
        $pembayaranCtrl->index();
        break;

    case 'pembayaran_create':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        require_once __DIR__ . '/app/controllers/PembayaranController.php';
        $pembayaranCtrl = new PembayaranController();
        $pembayaranCtrl->create();
        break;

    case 'pembayaran_result':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        require_once __DIR__ . '/app/controllers/PembayaranController.php';
        $pembayaranCtrl = new PembayaranController();
        $pembayaranCtrl->result();
        break;

    case 'pembayaran_callback':
        require_once __DIR__ . '/app/controllers/PembayaranController.php';
        $pembayaranCtrl = new PembayaranController();
        $pembayaranCtrl->callback();
        break;

    // --- WIZARD ADOPSI BARU (Langkah 1-4) ---
    case 'hewan_detail':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        $id_hewan = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $stmt_hewan = $pdo->prepare("SELECT h.*, r.nama_ras, j.nama_jenis FROM hewan h JOIN ras r ON h.id_ras = r.id_ras JOIN jenis_hewan j ON h.id_jenis = j.id_jenis WHERE h.id_hewan = ?");
        $stmt_hewan->execute([$id_hewan]);
        $hewan = $stmt_hewan->fetch();
        if (!$hewan) {
            echo "<script>alert('Hewan tidak ditemukan.'); window.history.back();</script>";
            exit;
        }
        include __DIR__ . '/views/user/hewan_detail.php';
        break;

    case 'proses_adopsi':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/proses_adopsi.php';
        break;

    case 'proses_adopsi_submit':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?page=dashboard_user'); exit; }
        if (!isset($_SESSION['user_id'])) { header('Location: index.php?page=login'); exit; }

        // Ambil id_pengadopsi dan validasi cooldown 1 bulan via model helper
        require_once __DIR__ . '/app/models/PengadopsiModel.php';
        $pm = new PengadopsiModel();
        $stmt_adopter = $pdo->prepare("SELECT id_pengadopsi FROM pengadopsi WHERE id_pengguna = ?");
        $stmt_adopter->execute([$_SESSION['user_id']]);
        $adopter_data = $stmt_adopter->fetch();

        if (!$adopter_data) {
            // jika belum punya profil pengadopsi, minta lengkapi
            echo "<script>alert('Profil pengadopsi belum lengkap. Harap isi data diri terlebih dahulu.'); window.location.href='index.php?page=dashboard_user';</script>";
            exit;
        }

        $id_pengadopsi  = $adopter_data['id_pengadopsi'];
        // Jika tidak boleh mengajukan (masih dalam masa 1 bulan), stop dan tunjukkan pesan
        if (!$pm->canAdoptAgain($id_pengadopsi)) {
            echo "<script>alert('Gagal: Anda harus menunggu 1 bulan sejak adopsi terakhir sebelum dapat mengajukan adopsi baru.'); window.location.href='index.php?page=dashboard_user&tab=katalog';</script>";
            exit;
        }

        $id_hewan       = intval($_POST['id_hewan'] ?? 0);
        $ttd_base64     = $_POST['tanda_tangan_png'] ?? '';
        $metode_bayar   = htmlspecialchars($_POST['metode_pembayaran'] ?? 'Transfer Bank');
        $metode_kunjungan = htmlspecialchars($_POST['metode'] ?? 'Kunjungan ke Shelter');
        $tanggal_jadwal   = $_POST['tanggal_jadwal'] ?? date('Y-m-d H:i:s');
        $alamat_tujuan    = ($metode_kunjungan === 'Jemput ke Rumah') ? htmlspecialchars($_POST['alamat_tujuan'] ?? '') : null;

        // Pastikan hewan masih tersedia sebelum disimpan (cegah double booking)
        $stmt_cek = $pdo->prepare("SELECT id_hewan FROM hewan WHERE id_hewan = ? AND status_adopsi = 'Tersedia'");
        $stmt_cek->execute([$id_hewan]);
        if (!$stmt_cek->fetch()) {
            echo "<script>alert('Maaf, hewan ini sudah tidak tersedia.'); window.location.href='index.php?page=dashboard_user&tab=katalog';</script>";
            exit;
        }

        // Buat kode transaksi otomatis
        $kode_transaksi = buat_kode_otomatis('transaksi_adopsi', 'kode_transaksi_adopsi', 'TA');
        // Simpan transaksi adopsi baru (status 'Ditandatangani' karena tanda tangan digital sudah di-submit)
        $stmt_insert = $pdo->prepare("INSERT INTO transaksi_adopsi (kode_transaksi_adopsi, id_hewan, id_pengadopsi, tanggal_adopsi, status_kontrak, ttd_adopter) VALUES (?, ?, ?, CURDATE(), 'Ditandatangani', ?)");
        $stmt_insert->execute([$kode_transaksi, $id_hewan, $id_pengadopsi, $ttd_base64]);

        // Ubah status hewan menjadi 'Dalam Proses' untuk mencegah user lain mengajukan adopsi pada hewan yang sama
        $pdo->prepare("UPDATE hewan SET status_adopsi = 'Dalam Proses' WHERE id_hewan = ?")->execute([$id_hewan]);

        // Simpan jadwal kunjungan (dipakai oleh tim untuk konfirmasi kunjungan/adopsi)
        $kode_jadwal = buat_kode_otomatis('jadwal_kunjungan', 'kode_jadwal_kunjungan', 'JK');
        $stmt_jadwal = $pdo->prepare("INSERT INTO jadwal_kunjungan (kode_jadwal_kunjungan, id_pengadopsi, id_hewan, metode, tanggal_jadwal, alamat_tujuan, status_jadwal) VALUES (?, ?, ?, ?, ?, ?, 'Menunggu')");
        $stmt_jadwal->execute([$kode_jadwal, $id_pengadopsi, $id_hewan, $metode_kunjungan, $tanggal_jadwal, $alamat_tujuan]);

        if (isset($_POST['redirect_ke_pembayaran'])) {
            $amount = floatval($_POST['amount_pembayaran'] ?? 160000);
            $metode = $_POST['metode_pembayaran'] ?? 'QRIS';
            header("Location: index.php?page=pembayaran_create&amount=" . urlencode($amount) . "&metode=" . urlencode($metode) . "&id_hewan=" . urlencode($id_hewan));
            exit;
        }

        echo "<script>alert('✅ Pengajuan adopsi berhasil! Jadwal kunjungan telah dibuat. Silakan tunggu konfirmasi dari tim PawCare.'); window.location.href='index.php?page=dashboard_user&tab=pengajuan';</script>";
        exit;

    // --- FITUR TANDA TANGAN & SIMPAN (lama, dipertahankan) ---
    case 'tanda_tangan':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        include __DIR__ . '/views/user/tanda_tangan.php';
        break;

    case 'simpan_ttd':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) { header('Location: index.php?page=login'); exit; }
            
            // Ambil id_pengadopsi dari tabel pengadopsi berdasarkan user yang sedang login
            $stmt_adopter = $pdo->prepare("SELECT id_pengadopsi FROM pengadopsi WHERE id_pengguna = ?");
            $stmt_adopter->execute([$_SESSION['user_id']]);
            $adopter = $stmt_adopter->fetch();
            
            if ($adopter) {
                $id_hewan = $_POST['id_transaksi']; // yang dikirim dari tanda_tangan.php adalah id_hewan
                $ttd_base64 = $_POST['ttd_base64'];
                
                // Simpan data ke dalam transaksi_adopsi dengan status 'Ditandatangani'
                $stmt = $pdo->prepare("INSERT INTO transaksi_adopsi (id_hewan, id_pengadopsi, tanggal_adopsi, status_kontrak, ttd_adopter) VALUES (?, ?, CURDATE(), 'Ditandatangani', ?)");
                $stmt->execute([$id_hewan, $adopter['id_pengadopsi'], $ttd_base64]);
                
                echo "<script>alert('Tanda tangan berhasil disimpan. Adopsi Sah!'); window.location.href='index.php?page=dashboard_user&tab=pengajuan';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan: Profil pengadopsi belum lengkap.'); window.location.href='index.php?page=dashboard_user';</script>";
            }
            exit;
        }
        break;

    // --- KONTRAK ADOPSI (View & Sign untuk Adopter) ---
    case 'kontrak_adopsi':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        require_once __DIR__ . '/app/models/TransaksiAdopsiModel.php';
        $m = new TransaksiAdopsiModel();
        $id = intval($_GET['id'] ?? 0);
        // Verifikasi kepemilikan transaksi
        $stmt_own = $pdo->prepare("SELECT t.id_adopsi FROM transaksi_adopsi t JOIN pengadopsi p ON t.id_pengadopsi = p.id_pengadopsi WHERE t.id_adopsi = ? AND p.id_pengguna = ?");
        $stmt_own->execute([$id, $_SESSION['user_id']]);
        if (!$stmt_own->fetch()) {
            header('Location: index.php?page=dashboard_user&tab=pengajuan');
            exit;
        }
        // Proses tanda tangan adopter
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ttd_base64 = $_POST['ttd_adopter'] ?? '';
            if (!empty($ttd_base64)) {
                $stmt_sign = $pdo->prepare("UPDATE transaksi_adopsi SET ttd_adopter = ?, status_kontrak = 'Ditandatangani' WHERE id_adopsi = ? AND status_kontrak = 'Draft' AND ttd_adopter IS NULL");
                $stmt_sign->execute([$ttd_base64, $id]);
                header("Location: index.php?page=kontrak_adopsi&id=$id&signed=1");
                exit;
            }
        }
        $data = $m->getById($id);
        include __DIR__ . '/views/user/kontrak_adopsi.php';
        break;

    case 'transaksi_adopsi_sign':
        require_once __DIR__ . '/app/controllers/TransaksiAdopsiController.php';
        $ctrl = new TransaksiAdopsiController();
        $id = intval($_GET['id'] ?? 0);
        $ctrl->sign($id);
        break;

    // --- BATALKAN PENGAJUAN ADOPSI (oleh Adopter) ---
    case 'proses_adopsi_batal':
        if (!check_access(['User'])) { header('Location: index.php?page=login'); exit; }
        $id = intval($_GET['id'] ?? 0);
        $stmt_b = $pdo->prepare("SELECT t.id_hewan, t.status_kontrak FROM transaksi_adopsi t JOIN pengadopsi p ON t.id_pengadopsi = p.id_pengadopsi WHERE t.id_adopsi = ? AND p.id_pengguna = ?");
        $stmt_b->execute([$id, $_SESSION['user_id']]);
        $trx = $stmt_b->fetch();
        if ($trx && $trx['status_kontrak'] == 'Draft') {
            $pdo->prepare("UPDATE transaksi_adopsi SET status_kontrak = 'Batal' WHERE id_adopsi = ?")->execute([$id]);
            $pdo->prepare("UPDATE hewan SET status_adopsi = 'Tersedia' WHERE id_hewan = ?")->execute([$trx['id_hewan']]);
        }
        header('Location: index.php?page=dashboard_user&tab=pengajuan');
        exit;

    // --- KARANTINA SELESAI (one-click dari Riwayat Kesehatan) ---
    case 'riwayat_kesehatan_karantina':
        if (!check_access(['Perawat'])) { header('Location: index.php?page=login'); exit; }
        require_once __DIR__ . '/app/models/RiwayatKesehatanModel.php';
        $m = new RiwayatKesehatanModel();
        $id = intval($_GET['id'] ?? 0);
        $vaksinasi = $m->getById($id);
        if ($vaksinasi && $vaksinasi['tipe'] === 'Vaksinasi') {
            $m->insert([
                'id_hewan' => $vaksinasi['id_hewan'],
                'id_pengguna' => $vaksinasi['id_pengguna'],
                'tipe' => 'Karantina Selesai',
                'id_vaksin' => null,
                'tanggal' => date('Y-m-d'),
                'deskripsi' => 'Karantina selesai, hewan siap rilis.'
            ]);
            $m->delete($id); // soft-delete Vaksinasi
            $m->rilisKarantina($vaksinasi['id_hewan']); // rekomendasi_adopsi = 1
        }
        header('Location: index.php?page=riwayat_kesehatan');
        exit;

    // --- INTEGRASI MIDTRANS PAYMENT GATEWAY SNAP ---
    case 'bayar_donasi':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama_donatur'];
            $jumlah = $_POST['jumlah'];
            $order_id = 'DONASI-' . time(); // Order ID unik

            // 1. Simpan ke database dengan status Pending
            $stmt = $pdo->prepare("INSERT INTO donasi (nama_donatur, jumlah, tanggal, status) VALUES (?, ?, CURDATE(), 'Pending')");
            $stmt->execute([$nama, $jumlah]);

            // 2. Request Token API Midtrans
            // Ganti SERVER_KEY_ANDA dengan kunci server dari dashboard sandbox Midtrans Anda
            $server_key = "SERVER_KEY_ANDA"; 
            
            $transaction = array(
                'transaction_details' => array( 'order_id' => $order_id, 'gross_amount' => $jumlah ),
                'customer_details' => array( 'first_name' => $nama )
            );

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://app.sandbox.midtrans.com/snap/v1/transactions",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($transaction),
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Authorization: Basic " . base64_encode($server_key . ":")
                ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response);

            // 3. Redirect user ke halaman pembayaran Midtrans
            if(isset($result->redirect_url)) {
                header("Location: " . $result->redirect_url);
            } else {
                echo "Gagal memproses Payment Gateway.";
            }
            exit;
        }
        break;
        
    // --- MIDTRANS WEBHOOK (Notifikasi Otomatis) ---
    case 'midtrans_webhook':
        // Ini akan ditembak oleh server Midtrans ketika user selesai bayar
        $json_result = file_get_contents('php://input');
        $notif = json_decode($json_result);

        if($notif && $notif->transaction_status == 'settlement') {
            // Ubah status otomatis dari Pending menjadi Dikonfirmasi tanpa campur tangan Admin
            $stmt = $pdo->prepare("UPDATE donasi SET status = 'Dikonfirmasi' WHERE status = 'Pending' AND id_donasi = (SELECT MAX(id_donasi) FROM donasi)");
            $stmt->execute();
        }
        http_response_code(200);
        exit;
        break;
        
    // ==================== ROUTING DINAMIS MVC (MASTER & TRANSAKSI) ====================
    default:
        // Cek apakah $page memiliki format "nama_entitas" atau "nama_entitas_action"
        $parts = explode('_', $page);
        $action = 'index';
        $entity = $page;

        // Mendeteksi _create, _edit, _delete, _confirm, _reject, _activate, _intake, _koordinator, _recommend, _release, _complete, _sign dari string parameter page
        if (in_array(end($parts), ['create', 'edit', 'delete', 'confirm', 'reject', 'activate', 'intake', 'koordinator', 'recommend', 'release', 'complete', 'sign'])) {
            $action = array_pop($parts);
            $entity = implode('_', $parts);
        }

        // Daftar entitas yang valid di sistem
        $valid_entities = [
            'hewan', 'jenis', 'ras', 'kandang', 'vaksin', 'pengguna', 'pengadopsi', 'donasi',
            'riwayat_kesehatan', 'penempatan_kandang', 'jadwal_kunjungan', 'transaksi_adopsi',
            'intake_hewan'
        ];

        // Entitas Master (hanya SuperAdmin)
        $master_entities = ['hewan', 'jenis', 'ras', 'kandang', 'vaksin', 'pengguna', 'pengadopsi', 'donasi'];

        if (in_array($entity, $valid_entities)) {
            //  validasi rbac ketat sesuai matriks hak akses
            if (!check_rbac($entity, $action)) {
                $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
                if ($user_role == 'SuperAdmin') {
                    header('Location: index.php?page=dashboard_superadmin');
                } elseif ($user_role == 'Koordinator') {
                    header('Location: index.php?page=dashboard_koordinator');
                } elseif ($user_role == 'Perawat' || $user_role == 'Perawat Hewan') {
                    header('Location: index.php?page=dashboard_staff');
                } else {
                    header('Location: index.php?page=login');
                }
                exit;
            }
            
            // Route khusus: intake_hewan pakai HewanController
            if ($entity == 'intake_hewan') {
                require_once __DIR__ . '/app/controllers/HewanController.php';
                $hewanCtrl = new HewanController();
                $hewanCtrl->intake();
                break;
            }
            
            // Route khusus: penempatan_kandang dengan action koordinator
            if ($entity == 'penempatan_kandang' && $action == 'koordinator') {
                require_once __DIR__ . '/app/controllers/PenempatanKandangController.php';
                $pkCtrl = new PenempatanKandangController();
                $pkCtrl->koordinator();
                break;
            }
            
            // Mengubah format snake_case (riwayat_kesehatan) menjadi CamelCase (RiwayatKesehatanController)
            $ctrlName = str_replace(' ', '', ucwords(str_replace('_', ' ', $entity))) . 'Controller';
            $ctrlFile = __DIR__ . '/app/controllers/' . $ctrlName . '.php';
            
            if (file_exists($ctrlFile)) {
                require_once $ctrlFile;
                $controller = new $ctrlName();
                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                
                if ($action === 'index') $controller->index();
                elseif ($action === 'create') $controller->create();
                elseif ($action === 'edit') $controller->edit($id);
                elseif ($action === 'delete') $controller->delete($id);
                elseif ($action === 'confirm') $controller->confirm($id);
                elseif ($action === 'reject') $controller->reject($id);
                elseif ($action === 'activate') $controller->activate($id);
                elseif ($action === 'intake') $controller->intake();
                elseif ($action === 'recommend') $controller->recommend($id);
                elseif ($action === 'release') $controller->release($id);
                elseif ($action === 'complete') $controller->complete($id);
                elseif ($action === 'sign') $controller->sign($id);
                break;
            }
        }
        
        // Fitur Laporan PDF Donasi Pemasukan
        if ($page == 'report_donasi_pemasukan' && check_access(['SuperAdmin'])) {
            require_once __DIR__ . '/app/controllers/ReportController.php';
            $ctrl = new ReportController();
            $ctrl->laporanDonasiPemasukan();
            break;
        }

        // Fitur Laporan PDF Donasi
        if ($page == 'report_donasi' && check_access(['SuperAdmin'])) {
            require_once __DIR__ . '/app/controllers/ReportController.php';
            $ctrl = new ReportController();
            $ctrl->laporanDonasi();
            break;
        }

        // Fitur Laporan Excel Donasi
        if ($page == 'report_donasi_excel' && check_access(['SuperAdmin'])) {
            require_once __DIR__ . '/app/controllers/ReportController.php';
            $ctrl = new ReportController();
            $ctrl->laporanDonasiExcel();
            break;
        }

        // Jika tidak masuk rute manapun
        echo "<h1 style='text-align:center; margin-top:50px;'>404 Not Found</h1><p style='text-align:center;'>Halaman tidak tersedia.</p>";
        break;
}
?>