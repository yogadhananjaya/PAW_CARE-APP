<header class="flex justify-between items-center px-8 py-5 bg-paw-putih shadow-sm z-10">
    <h2 class="text-2xl font-bold text-paw-hitam tracking-tight">Dashboard Analitik</h2>
    
    <?php
    $hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
    $bulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
    $tanggal_sekarang = $hari[date("w")] . ", " . date("j") . " " . $bulan[date("n")] . " " . date("Y");
    ?>
    <p class="text-sm font-medium text-gray-500 bg-paw-krem-utama px-4 py-2 rounded-lg border border-paw-krem-gelap">
        <i class="fa-regular fa-calendar-days mr-2"></i><?= $tanggal_sekarang; ?>
    </p>
</header>

<div class="flex-1 overflow-y-auto p-8">

    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-paw-putih p-6 rounded-2xl shadow-sm border border-paw-krem-gelap flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Total Hewan</p>
                <h3 class="text-3xl font-black text-paw-hitam">124</h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-paw-krem-gelap flex items-center justify-center text-2xl text-paw-hitam">
                <i class="fa-solid fa-cat"></i>
            </div>
        </div>
        <div class="bg-paw-putih p-6 rounded-2xl shadow-sm border border-paw-krem-gelap flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Kandang Tersedia</p>
                <h3 class="text-3xl font-black text-paw-hitam">12 <span class="text-lg text-gray-400 font-medium">/ 30</span></h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-paw-krem-gelap flex items-center justify-center text-2xl text-paw-hitam">
                <i class="fa-solid fa-warehouse"></i>
            </div>
        </div>
        <div class="bg-paw-putih p-6 rounded-2xl shadow-sm border border-paw-krem-gelap flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Adopsi Sukses</p>
                <h3 class="text-3xl font-black text-paw-hitam">18</h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl text-green-600">
                <i class="fa-solid fa-handshake-angle"></i>
            </div>
        </div>
        <div class="bg-paw-putih p-6 rounded-2xl shadow-sm border border-paw-merah flex justify-between items-center relative overflow-hidden">
            <div class="absolute right-0 top-0 w-2 h-full bg-paw-merah"></div>
            <div>
                <p class="text-sm text-paw-merah font-bold mb-1">Butuh Perawatan</p>
                <h3 class="text-3xl font-black text-paw-hitam">5 <span class="text-lg font-medium text-gray-500">Ekor</span></h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center text-2xl text-paw-merah">
                <i class="fa-solid fa-briefcase-medical"></i>
            </div>
        </div>
    </div>

    <div class="bg-paw-putih p-6 rounded-2xl shadow-sm border border-paw-krem-gelap">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-paw-hitam">Manajemen Data Hewan</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola master data hewan shelter</p>
            </div>
            <div class="flex gap-3">
                <button class="bg-paw-hitam text-paw-putih px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md">
                    <i class="fa-solid fa-plus mr-2"></i>Tambah Hewan
                </button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-paw-krem-gelap">
            <table class="w-full text-sm text-left text-paw-hitam">
                <thead class="text-xs bg-paw-krem-gelap uppercase font-bold text-paw-hitam">
                    <tr>
                        <th scope="col" class="px-5 py-4">ID</th>
                        <th scope="col" class="px-5 py-4">Nama Hewan</th>
                        <th scope="col" class="px-5 py-4">Jenis / Ras</th>
                        <th scope="col" class="px-5 py-4">Kandang</th>
                        <th scope="col" class="px-5 py-4 text-center">Status</th>
                        <th scope="col" class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paw-krem-gelap">
                    <tr class="hover:bg-paw-krem-utama/50 transition">
                        <td class="px-5 py-4 font-bold">HWN-001</td>
                        <td class="px-5 py-4 font-medium">Milo</td>
                        <td class="px-5 py-4 text-gray-600">Kucing / Persia</td>
                        <td class="px-5 py-4 text-gray-600">A-01</td>
                        <td class="px-5 py-4 text-center">
                            <span class="bg-green-100 text-green-700 border border-green-200 px-3 py-1 rounded-full text-xs font-bold">Sehat</span>
                        </td>
                        <td class="px-5 py-4 text-center text-lg">
                            <a href="#" class="text-paw-hitam hover:text-blue-600 transition mx-2"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="#" class="text-paw-merah hover:text-red-700 transition mx-2"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <tr class="hover:bg-paw-krem-utama/50 transition">
                        <td class="px-5 py-4 font-bold">HWN-002</td>
                        <td class="px-5 py-4 font-medium">Rex</td>
                        <td class="px-5 py-4 text-gray-600">Anjing / Golden Retriever</td>
                        <td class="px-5 py-4 text-gray-600">B-05</td>
                        <td class="px-5 py-4 text-center">
                            <span class="bg-red-100 text-paw-merah border border-red-200 px-3 py-1 rounded-full text-xs font-bold">Belum Vaksin</span>
                        </td>
                        <td class="px-5 py-4 text-center text-lg">
                            <a href="#" class="text-paw-hitam hover:text-blue-600 transition mx-2"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="#" class="text-paw-merah hover:text-red-700 transition mx-2"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>