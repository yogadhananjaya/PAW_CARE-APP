<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawCare - Design with ease</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="min-h-screen w-full bg-[url('assets/img/cat.jpg')] bg-cover bg-center bg-no-repeat relative flex flex-col items-center justify-center">
    
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 text-center px-4 w-full max-w-4xl mx-auto flex flex-col items-center">
        
        <div class="bg-white/90 backdrop-blur-sm text-gray-800 text-sm font-semibold py-2 px-5 rounded-full inline-flex items-center gap-2 mb-8 shadow-sm">
            Selamat Datang di Ekosistem PawCare <i class="fa-solid fa-paw text-[#DE3B3B]"></i>
        </div>

        <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 tracking-tight drop-shadow-lg">
            Rawat dengan kasih.
        </h1>

        <p class="text-lg md:text-xl text-gray-100 max-w-2xl mx-auto mb-10 drop-shadow-md leading-relaxed">
            Sistem cerdas yang mengerti kebutuhan shelter Anda. <br class="hidden md:block">
            Kelola adopsi dan perawatan medis, agar Anda bisa bernapas lega.
        </p>

        <div class="flex flex-col sm:flex-row gap-5 items-center justify-center">
            
            <a href="index.php?action=login" class="bg-white text-gray-900 font-bold py-3.5 px-8 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:bg-gray-100 transition-all duration-300 transform hover:-translate-y-1 flex items-center gap-3">
                Mulai Sekarang <i class="fa-solid fa-arrow-right text-sm"></i>
            </a>

            <a href="#" class="text-white font-semibold py-3 px-6 rounded-full hover:bg-white/10 transition-colors duration-300 drop-shadow-md flex items-center gap-2">
                <i class="fa-regular fa-circle-play text-lg"></i> Lihat Katalog
            </a>
            
        </div>
    </div>

</body>
</html>