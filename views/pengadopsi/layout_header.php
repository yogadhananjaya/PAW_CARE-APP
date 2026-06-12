<?php
// FILE INI BERISI SIDEBAR LENGKAP (SAMA DENGAN DASHBOARD)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Data simulasi jika belum ada login
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = "superadmin";
    $_SESSION['role'] = "Superadmin";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengadopsi - PawCare</title>
    <!-- Kita pakai Tailwind CSS supaya tampilannya bagus tapi gampang -->
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

    <!-- BAGIAN SIDEBAR (YANG DI SAMPING) -->
    <aside class="w-64 bg-paw-krem-gelap flex flex-col h-full border-r border-[#e0d6c8]">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#e0d6c8]">
            <i class="fa-solid fa-paw text-paw-merah text-2xl"></i>
            <h1 class="text-xl font-bold tracking-wide text-paw-hitam">PAW SHELTER</h1>
        </div>

        <div class="flex-1 overflow-y-auto px-4 pb-4">
            <!-- MENU UTAMA -->
            <p class="text-xs text-gray-500 font-bold mt-5 mb-2 uppercase tracking-widest">Utama</p>
            <a href="../../index.php?action=dashboard" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-chart-pie w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- DATA MASTER -->
            <p class="text-xs text-gray-500 font-bold mt-6 mb-2 uppercase tracking-widest">Data Master</p>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-dog w-5"></i><span>Hewan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-list w-5"></i><span>Jenis</span>
            </a>
            <a href="../ras/index.php" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-tags w-5"></i><span>Ras</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-syringe w-5"></i><span>Vaksin</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-box-open w-5"></i><span>Kandang</span>
            </a>
            
            <!-- MENU PENGADOPSI YANG SEDANG AKTIF -->
            <a href="index.php" class="flex items-center gap-3 px-4 py-2.5 bg-paw-hitam text-paw-putih rounded-xl mb-1 shadow-md transition font-bold">
                <i class="fa-solid fa-users w-5"></i><span>Pengadopsi</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-user-tie w-5"></i><span>Pegawai & User</span>
            </a>

            <!-- TRANSAKSI -->
            <p class="text-xs text-gray-500 font-bold mt-6 mb-2 uppercase tracking-widest">Transaksi</p>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-notes-medical w-5"></i><span>Vaksinasi</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-door-open w-5"></i><span>Penempatan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-stethoscope w-5"></i><span>Perawatan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-paw-hitam hover:bg-paw-putih rounded-lg transition-colors mb-1">
                <i class="fa-solid fa-hand-holding-heart w-5"></i><span>Adopsi</span>
            </a>
        </div>

        <!-- Profil Bawah -->
        <div class="p-4 border-t border-[#e0d6c8] flex justify-between items-center bg-paw-krem-gelap">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-paw-hitam text-paw-putih flex items-center justify-center font-bold shadow">
                    <?= strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <div class="leading-tight">
                    <p class="text-sm font-bold text-paw-hitam"><?= htmlspecialchars($_SESSION['username']); ?></p>
                    <p class="text-xs text-gray-600"><?= $_SESSION['role']; ?></p>
                </div>
            </div>
            <a href="../../index.php?action=logout" class="text-paw-merah hover:text-red-700 transition">
                <i class="fa-solid fa-power-off text-lg"></i>
            </a>
        </div>
    </aside>

    <!-- BAGIAN KONTEN TENAH -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative p-8 overflow-y-auto">
