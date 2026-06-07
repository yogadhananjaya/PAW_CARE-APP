<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Adopsi - PawCare</title>
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
<body class="bg-paw-krem-utama font-sans text-paw-hitam antialiased">

    <nav class="bg-paw-putih border-b border-paw-krem-gelap px-8 py-4 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-paw text-paw-merah text-2xl"></i>
                <h1 class="text-xl font-black tracking-wide text-paw-hitam">PAWCARE ADOPSI</h1>
            </div>

            <div class="hidden md:flex items-center gap-8 font-bold text-sm">
                <a href="#" class="text-paw-hitam border-b-2 border-paw-merah pb-1">Beranda</a>
                <a href="#" class="text-gray-500 hover:text-paw-hitam transition">Katalog Hewan</a>
                <a href="#" class="text-gray-500 hover:text-paw-hitam transition">Pengajuan Saya</a>
                <a href="#" class="text-gray-500 hover:text-paw-hitam transition">Panduan Adopsi</a>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-paw-hitam">Halo, <?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</p>
                    <p class="text-xs text-gray-500">Calon Pengadopsi</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-paw-krem-gelap flex items-center justify-center text-paw-hitam font-bold border border-[#e0d6c8]">
                    <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                </div>
                <a href="index.php?action=logout" class="ml-2 text-paw-merah hover:text-red-700 transition" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xl"></i>
                </a>
            </div>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-8 py-12 md:py-20 flex flex-col md:flex-row items-center justify-between gap-10">
        <div class="md:w-1/2">
            <div class="bg-paw-krem-gelap text-paw-hitam text-xs font-bold px-4 py-2 rounded-full inline-block mb-6 border border-[#e0d6c8]">
                <i class="fa-solid fa-heart text-paw-merah mr-2"></i> Buka Hatimu, Buka Rumahmu
            </div>
            <h2 class="text-4xl md:text-5xl font-black text-paw-hitam leading-tight mb-6">
                Temukan Sahabat <br> <span class="text-paw-merah">Setia</span> Barumu.
            </h2>
            <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                Ratusan hewan di shelter kami sedang menunggu kasih sayang dari keluarga yang tepat. Apakah itu Anda? Jelajahi katalog kami dan mulai perjalanan adopsi hari ini.
            </p>
            <div class="flex gap-4">
                <a href="#katalog" class="bg-paw-hitam text-paw-putih px-8 py-4 rounded-full font-bold shadow-lg hover:bg-gray-800 hover:-translate-y-1 transition transform">
                    Lihat Hewan <i class="fa-solid fa-arrow-down ml-2"></i>
                </a>
            </div>
        </div>
        
        <div class="md:w-1/2 flex justify-center">
            <div class="w-full max-w-md h-80 bg-paw-krem-gelap rounded-[3rem] border-4 border-paw-putih shadow-xl flex items-center justify-center text-gray-400 relative overflow-hidden">
                <i class="fa-solid fa-image text-5xl mb-2"></i>
                <p class="absolute bottom-10 text-sm font-bold">Tempat Ilustrasi / Foto Hewan</p>
            </div>
        </div>
    </header>

    <section id="katalog" class="max-w-7xl mx-auto px-8 py-12">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h3 class="text-3xl font-black text-paw-hitam mb-2">Siap Diadopsi</h3>
                <p class="text-gray-500">Hewan-hewan ini sudah divaksin, sehat, dan menanti rumah baru.</p>
            </div>
            <a href="#" class="text-paw-hitam font-bold hover:underline">Lihat Semua <i class="fa-solid fa-arrow-right text-sm ml-1"></i></a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            
            <div class="bg-paw-putih rounded-3xl overflow-hidden shadow-sm border border-paw-krem-gelap hover:shadow-xl transition-shadow duration-300 group">
                <div class="h-48 bg-gray-200 relative overflow-hidden flex justify-center items-center">
                    <i class="fa-solid fa-cat text-4xl text-gray-400"></i>
                    <div class="absolute top-3 right-3 bg-paw-putih/90 text-paw-hitam text-xs font-bold px-3 py-1 rounded-full backdrop-blur-sm">
                        Kucing
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-xl font-black text-paw-hitam">Milo</h4>
                        <i class="fa-solid fa-mars text-blue-500 text-lg" title="Jantan"></i>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Persia • 2 Tahun</p>
                    <button class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam py-3 rounded-xl font-bold group-hover:bg-paw-hitam group-hover:text-paw-putih transition-colors">
                        Ajukan Adopsi
                    </button>
                </div>
            </div>

            <div class="bg-paw-putih rounded-3xl overflow-hidden shadow-sm border border-paw-krem-gelap hover:shadow-xl transition-shadow duration-300 group">
                <div class="h-48 bg-gray-200 relative overflow-hidden flex justify-center items-center">
                    <i class="fa-solid fa-dog text-4xl text-gray-400"></i>
                    <div class="absolute top-3 right-3 bg-paw-putih/90 text-paw-hitam text-xs font-bold px-3 py-1 rounded-full backdrop-blur-sm">
                        Anjing
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-xl font-black text-paw-hitam">Bella</h4>
                        <i class="fa-solid fa-venus text-pink-500 text-lg" title="Betina"></i>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Golden Retriever • 1.5 Tahun</p>
                    <button class="w-full bg-paw-krem-utama border border-paw-krem-gelap text-paw-hitam py-3 rounded-xl font-bold group-hover:bg-paw-hitam group-hover:text-paw-putih transition-colors">
                        Ajukan Adopsi
                    </button>
                </div>
            </div>

            <div class="bg-paw-putih rounded-3xl overflow-hidden shadow-sm border border-paw-merah hover:shadow-xl transition-shadow duration-300 group relative">
                <div class="absolute top-4 left-0 bg-paw-merah text-paw-putih text-[10px] font-black uppercase px-3 py-1 rounded-r-md z-10 shadow-md">
                    Butuh Cepat
                </div>
                <div class="h-48 bg-gray-200 relative overflow-hidden flex justify-center items-center">
                    <i class="fa-solid fa-cat text-4xl text-gray-400"></i>
                    <div class="absolute top-3 right-3 bg-paw-putih/90 text-paw-hitam text-xs font-bold px-3 py-1 rounded-full backdrop-blur-sm">
                        Kucing
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-xl font-black text-paw-hitam">Oreo</h4>
                        <i class="fa-solid fa-mars text-blue-500 text-lg" title="Jantan"></i>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Domestik • 6 Bulan</p>
                    <button class="w-full bg-paw-merah text-paw-putih py-3 rounded-xl font-bold hover:bg-red-700 transition-colors shadow-md">
                        Ajukan Adopsi
                    </button>
                </div>
            </div>

            <div class="bg-paw-krem-gelap rounded-3xl overflow-hidden border-2 border-dashed border-[#d1c6b4] flex flex-col items-center justify-center h-full p-6 text-center opacity-70">
                <div class="w-16 h-16 bg-paw-putih rounded-full flex items-center justify-center mb-4 text-gray-400 text-2xl shadow-sm">
                    <i class="fa-solid fa-ellipsis"></i>
                </div>
                <h4 class="text-lg font-bold text-paw-hitam mb-1">Lebih Banyak Hewan</h4>
                <p class="text-xs text-gray-500">Teman kelompokmu akan menyambungkan data asli dari database ke sini.</p>
            </div>

        </div>
    </section>

    <footer class="bg-paw-hitam text-paw-putih py-10 mt-12 border-t-4 border-paw-merah">
        <div class="max-w-7xl mx-auto px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0 text-center md:text-left">
                <div class="flex items-center gap-3 justify-center md:justify-start mb-2">
                    <i class="fa-solid fa-paw text-paw-merah text-xl"></i>
                    <h2 class="text-xl font-black tracking-wide text-paw-putih">PAWCARE</h2>
                </div>
                <p class="text-gray-400 text-sm">Menghubungkan hati, menyelamatkan nyawa.</p>
            </div>
            <div class="flex gap-6 text-sm font-bold text-gray-300">
                <a href="#" class="hover:text-paw-putih transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-paw-putih transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-paw-putih transition">Hubungi Kami</a>
            </div>
        </div>
    </footer>

</body>
</html>