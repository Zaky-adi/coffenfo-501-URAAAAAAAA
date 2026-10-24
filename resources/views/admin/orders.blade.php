<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Kelola Pesanan Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F5F0; }
    </style>
</head>
<body class="text-[#3D2C24] min-h-screen flex">

    <!-- Sidebar Admin -->
    <aside class="w-64 bg-[#2C1E16] text-[#E5DEC9] flex flex-col justify-between hidden md:flex sticky top-0 h-screen">
        <div>
            <div class="p-6 border-b border-[#3D2C24] flex items-center gap-3">
                <div class="bg-[#EEF2DA] text-[#2C1E16] p-2 rounded-xl font-bold text-base w-8 h-8 flex items-center justify-center">c</div>
                <div>
                    <h1 class="font-bold text-white text-sm">Coffenfo</h1>
                    <p class="text-[10px] text-[#A69B90]">ADMIN PANEL</p>
                </div>
            </div>
            <div class="p-4">
                <div class="bg-[#3D2C24] text-white p-3 rounded-2xl text-xs font-semibold mb-6 flex items-center justify-between">
                    <span>Linton Coffee & Space</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <p class="text-[10px] uppercase tracking-wider text-[#A69B90] px-3 mb-2 font-bold">Workspace</p>
                <nav class="space-y-1 text-xs font-medium">
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#3D2C24] text-[#A69B90] transition">Dashboard Overview</a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#3D2C24] text-[#A69B90] transition">Kelola Kafe</a>
                    <a href="{{ route('admin.moderation') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#3D2C24] text-[#A69B90] transition">Moderasi Review</a>
                    <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-[#D4A373] text-[#2C1E16] font-bold shadow-md transition">Pesanan Masuk (Orders)</a>
                </nav>
            </div>
        </div>
        <div class="p-4 border-t border-[#3D2C24]">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-[#A69B90] hover:text-white transition">Keluar ke Web</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 md:p-10 overflow-y-auto">
        <!-- Header Top -->
        <div class="flex justify-between items-center mb-8 pb-4 border-b border-[#E5DEC9]">
            <div>
                <h1 class="text-2xl font-bold">Kelola Pesanan Masuk</h1>
                <p class="text-xs text-[#8C827A]">Pantau, konfirmasi, dan selesaikan pesanan kafe dalam satu tempat.</p>
            </div>
            <div class="text-right text-xs text-[#8C827A]">
                <span class="font-medium text-[#3D2C24]">Sabtu, 24 Okt 2026</span> • 19:30 WIB
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-5 rounded-3xl border border-[#E5DEC9] shadow-xs">
                <p class="text-[11px] font-bold text-[#8C827A] mb-1">Pesanan Baru</p>
                <h3 class="text-2xl font-bold">3</h3>
                <p class="text-[10px] text-amber-600 mt-1">Perlu dikonfirmasi</p>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-[#E5DEC9] shadow-xs">
                <p class="text-[11px] font-bold text-[#8C827A] mb-1">Sedang Diproses</p>
                <h3 class="text-2xl font-bold">5</h3>
                <p class="text-[10px] text-blue-600 mt-1">Barista siapkan di dapur</p>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-[#E5DEC9] shadow-xs">
                <p class="text-[11px] font-bold text-[#8C827A] mb-1">Selesai Hari Ini</p>
                <h3 class="text-2xl font-bold">28</h3>
                <p class="text-[10px] text-emerald-600 mt-1">Pesanan telah disajikan</p>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-[#E5DEC9] shadow-xs">
                <p class="text-[11px] font-bold text-[#8C827A] mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-[#3D2C24]">Rp 2.450.000</h3>
                <p class="text-[10px] text-[#8C827A] mt-1">Pendapatan hari ini</p>
            </div>
        </div>

        <!-- Alert Banner -->
        <div class="bg-[#FFF8E7] border border-amber-200 rounded-2xl p-4 mb-6 flex justify-between items-center text-xs">
            <div class="flex items-center gap-2 text-amber-900 font-medium">
                <i data-lucide="bell" class="w-4 h-4 text-amber-700"></i>
                <span>Ada 3 pesanan baru menunggu konfirmasi. Terima pesanan agar segera masuk ke antrean dapur.</span>
            </div>
            <button class="bg-amber-800 text-white px-3 py-1.5 rounded-xl font-bold hover:bg-amber-900 transition">Lihat pesanan baru →</button>
        </div>

        <!-- Table Container -->
        <div class="bg-white border border-[#E5DEC9] rounded-3xl p-6 shadow-xs overflow-hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-sm">Daftar Pesanan <span class="text-xs text-[#8C827A] font-normal">(36 pesanan)</span></h3>
                <div class="flex gap-2">
                    <input type="text" placeholder="Cari ID pesanan atau pelanggan..." class="bg-[#F8F5F0] border border-[#E5DEC9] px-4 py-2 rounded-xl text-xs w-64 focus:outline-none focus:border-[#3D2C24]">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#E5DEC9] text-[#8C827A] font-medium">
                            <th class="pb-3">ID Pesanan</th>
                            <th class="pb-3">Nama Pelanggan</th>
                            <th class="pb-3">Tipe Order & Meja</th>
                            <th class="pb-3">Rincian Menu</th>
                            <th class="pb-3">Total Bayar & Metode</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Aksi / Kontrol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5DEC9]/50">
                        <!-- Baris 1 -->
                        <tr>
                            <td class="py-4 font-bold">#CF-1048<br><span class="text-[10px] text-[#8C827A] font-normal">19:25 WIB • Meja 11</span></td>
                            <td class="py-4 font-medium">Andi Pratama<br><span class="text-[10px] text-[#8C827A]">Pelanggan kafe</span></td>
                            <td class="py-4">Makan di Tempat<br><span class="text-[10px] text-[#8C827A]">Meja 11</span></td>
                            <td class="py-4">2× Kopi Susu Gula Aren<br><span class="text-[10px] text-[#8C827A]">Custom: Less ice, gula 50%</span></td>
                            <td class="py-4 font-bold">Rp 52.000<br><span class="text-[10px] text-emerald-600 font-normal">QRIS • Sudah dibayar</span></td>
                            <td class="py-4"><span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-[10px] font-bold">Menunggu Konfirmasi</span></td>
                            <td class="py-4 text-right space-x-1">
                                <button class="bg-[#3D2C24] text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-black transition">Terima Pesanan</button>
                                <button class="bg-red-50 text-red-600 px-2 py-1.5 rounded-lg font-semibold hover:bg-red-100 transition">Tolak</button>
                            </td>
                        </tr>
                        <!-- Baris 2 -->
                        <tr>
                            <td class="py-4 font-bold">#CF-1047<br><span class="text-[10px] text-[#8C827A] font-normal">19:20 WIB • Takeaway</span></td>
                            <td class="py-4 font-medium">Siti Alawiyah<br><span class="text-[10px] text-[#8C827A]">Pelanggan kafe</span></td>
                            <td class="py-4">Bawa Pulang<br><span class="text-[10px] text-[#8C827A]">Ambil di kasir</span></td>
                            <td class="py-4">1× Dago Cold<br><span class="text-[10px] text-[#8C827A]">Custom: Kopi jahe, tanpa es</span></td>
                            <td class="py-4 font-bold">Rp 38.000<br><span class="text-[10px] text-emerald-600 font-normal">GoPay • Sudah dibayar</span></td>
                            <td class="py-4"><span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-[10px] font-bold">Menunggu Konfirmasi</span></td>
                            <td class="py-4 text-right space-x-1">
                                <button class="bg-[#3D2C24] text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-black transition">Terima Pesanan</button>
                                <button class="bg-red-50 text-red-600 px-2 py-1.5 rounded-lg font-semibold hover:bg-red-100 transition">Tolak</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>