<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in'])) {
    header("Location: /PAW_CARE-APP/index.php?action=login");
    exit;
}

// AMBIL URL HALAMAN SAAT INI
$url_sekarang = $_SERVER['REQUEST_URI'];

$kelas_aktif = "flex items-center gap-3 px-4 py-2.5 bg-paw-hitam text-paw-putih rounded-xl mb-1 shadow-md transition";
$kelas_biasa = "flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1";

$kelas_dashboard = $kelas_biasa;
if (strpos($url_sekarang, 'action=dashboard') !== false) { $kelas_dashboard = $kelas_aktif; }

$kelas_hewan = $kelas_biasa;
if (strpos($url_sekarang, 'views/hewan') !== false) { $kelas_hewan = $kelas_aktif; }

$kelas_jenis = $kelas_biasa;
if (strpos($url_sekarang, 'views/jenis_hewan') !== false) { $kelas_jenis = $kelas_aktif; }

$kelas_ras = $kelas_biasa;
if (strpos($url_sekarang, 'views/ras') !== false) { $kelas_ras = $kelas_aktif; }

$kelas_pengadopsi = $kelas_biasa;
if (strpos($url_sekarang, 'views/pengadopsi') !== false) { $kelas_pengadopsi = $kelas_aktif; }

$kelas_pengguna = $kelas_biasa;
if (strpos($url_sekarang, 'action=pengguna') !== false) { $kelas_pengguna = $kelas_aktif; }

$kelas_perawatan = $kelas_biasa;
if (strpos($url_sekarang, 'action=perawatan') !== false) { $kelas_perawatan = $kelas_aktif; }

$kelas_vaksin = $kelas_biasa;
if (strpos($url_sekarang, 'views/vaksin') !== false) { $kelas_vaksin = $kelas_aktif; }

$kelas_kandang = $kelas_biasa;
if (strpos($url_sekarang, 'views/kandang') !== false) { $kelas_kandang = $kelas_aktif; }

$kelas_vaksinasi = $kelas_biasa;
if (strpos($url_sekarang, 'views/vaksinasi') !== false) { $kelas_vaksinasi = $kelas_aktif; }

$kelas_penempatan = $kelas_biasa;
if (strpos($url_sekarang, 'views/penempatan') !== false) { $kelas_penempatan = $kelas_aktif; }

$kelas_adopsi = $kelas_biasa;
if (strpos($url_sekarang, 'views/adopsi') !== false) { $kelas_adopsi = $kelas_aktif; }

$kelas_donasi = $kelas_biasa;
if (strpos($url_sekarang, 'views/donasi') !== false) { $kelas_donasi = $kelas_aktif; }
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawCare Shelter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'paw-krem-utama': '#FDFBF7',
                        'paw-krem-gelap': '#EFE9DB',
                        'paw-hitam': '#1A1A1A',
                        'paw-putih': '#FFFFFF',
                        'paw-merah': '#DE3B3B'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-paw-krem-utama font-sans text-paw-hitam antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-paw-krem-gelap flex flex-col h-full border-r border-[#e0d6c8]">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#e0d6c8]">
            <i class="fa-solid fa-paw text-paw-merah text-2xl"></i>
            <h1 class="text-xl font-bold tracking-wide text-paw-hitam">PAW SHELTER</h1>
        </div>

        <div class="flex-1 overflow-y-auto px-4 pb-4">
            <p class="text-xs text-gray-500 font-bold mt-5 mb-2 uppercase tracking-widest">Utama</p>
            <a href="/PAW_CARE-APP/index.php?action=dashboard"
                class="<?php echo $kelas_dashboard; ?>">
                <i class="fa-solid fa-chart-pie w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <p class="text-xs text-gray-500 font-bold mt-6 mb-2 uppercase tracking-widest">Data Master</p>
            <div>
                <button onclick="klikMenuHewan()"
                    class="w-full flex items-center justify-between px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1 font-bold">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-dog w-5"></i><span>Daftar Hewan</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div id="dropdownHewan" class="hidden pl-4 pr-2 py-1">
                    <a href="/PAW_CARE-APP/index.php?action=hewan" class="<?php echo $kelas_hewan; ?>">
                        <i class="fa-solid fa-paw w-5"></i><span class="font-bold">Hewan</span>
                    </a>
                    <a href="/PAW_CARE-APP/views/jenis_hewan/index.php" class="<?php echo $kelas_jenis; ?>">
                        <i class="fa-solid fa-tags w-5"></i><span class="font-bold">Jenis</span>
                    </a>
                    <a href="/PAW_CARE-APP/views/ras/index.php" class="<?php echo $kelas_ras; ?>">
                        <i class="fa-solid fa-dna w-5"></i><span class="font-bold">Ras</span>
                    </a>
                </div>
            </div>

            <!-- Ingatan Browser untuk Menu Hewan -->
            <script>
                // 1. Cek ingatan browser (Local Storage)
                var menuHewan = document.getElementById("dropdownHewan");
                var statusMenu = localStorage.getItem("ingatMenuHewan");

                if (statusMenu == "terbuka") {
                    menuHewan.classList.remove("hidden");
                } else {
                    menuHewan.classList.add("hidden");
                }

                // 2. Fungsi saat tombol diklik
                function klikMenuHewan() {
                    if (menuHewan.classList.contains("hidden")) {
                        menuHewan.classList.remove("hidden");
                        localStorage.setItem("ingatMenuHewan", "terbuka");
                    } else {
                        menuHewan.classList.add("hidden");
                        localStorage.setItem("ingatMenuHewan", "tertutup");
                    }
                }
            </script>
            <a href="/PAW_CARE-APP/views/vaksin/index.php" class="<?php echo $kelas_vaksin; ?>">
                <i class="fa-solid fa-syringe w-5"></i><span class="font-bold">Vaksin</span>
            </a>
            <a href="/PAW_CARE-APP/views/kandang/index.php" class="<?php echo $kelas_kandang; ?>">
                <i class="fa-solid fa-box-open w-5"></i><span class="font-bold">Kandang</span>
            </a>
            <a href="/PAW_CARE-APP/views/pengadopsi/index.php" class="<?php echo $kelas_pengadopsi; ?>">
                <i class="fa-solid fa-users w-5"></i><span class="font-bold">Pengadopsi</span>
            </a>
            <a href="/PAW_CARE-APP/views/donasi/index.php" class="<?php echo $kelas_donasi; ?>">
                <i class="fa-solid fa-hand-holding-dollar w-5"></i><span class="font-bold">Donasi</span>
            </a>

            <?php if ($_SESSION['role'] === 'Superadmin'): ?>
                <a href="/PAW_CARE-APP/index.php?action=pengguna" class="<?php echo $kelas_pengguna; ?>">
                    <i class="fa-solid fa-user-gear w-5"></i><span class="font-bold">Pengguna</span>
                </a>
            <?php endif; ?>



            <p class="text-xs text-gray-500 font-bold mt-6 mb-2 uppercase tracking-widest">Transaksi</p>
            <a href="/PAW_CARE-APP/views/vaksinasi/index.php" class="<?php echo $kelas_vaksinasi; ?>">
                <i class="fa-solid fa-notes-medical w-5"></i><span>Vaksinasi</span>
            </a>
            <a href="/PAW_CARE-APP/views/penempatan_kandang/index.php" class="<?php echo $kelas_penempatan; ?>">
                <i class="fa-solid fa-door-open w-5"></i><span>Penempatan</span>
            </a>

            <?php if ($_SESSION['role'] === 'Superadmin' || $_SESSION['role'] === 'Staff'): ?>
                <a href="/PAW_CARE-APP/index.php?action=perawatan" class="<?php echo $kelas_perawatan; ?>">
                    <i class="fa-solid fa-stethoscope w-5"></i><span class="font-bold">Perawatan</span>
                </a>
            <?php endif; ?>

            <a href="/PAW_CARE-APP/views/adopsi/index.php" class="<?php echo $kelas_adopsi; ?>">
                <i class="fa-solid fa-hand-holding-heart w-5"></i><span>Adopsi</span>
            </a>
        </div>

        <div class="p-4 border-t border-[#e0d6c8] flex justify-between items-center bg-paw-krem-gelap">
            <div class="flex items-center gap-3">
                <div
                    class="w-9 h-9 rounded-full bg-paw-hitam text-paw-putih flex items-center justify-center font-bold shadow">
                    <?= strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <div class="leading-tight">
                    <p class="text-sm font-bold text-paw-hitam"><?= htmlspecialchars($_SESSION['username']); ?></p>
                    <p class="text-xs text-gray-600"><?= $_SESSION['role']; ?></p>
                </div>
            </div>
            <a href="/PAW_CARE-APP/index.php?action=logout" class="text-paw-merah hover:text-red-700 transition"
                title="Logout">
                <i class="fa-solid fa-power-off text-lg"></i>
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative">