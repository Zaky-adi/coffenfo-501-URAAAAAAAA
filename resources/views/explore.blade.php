<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Filter Bar & Eksplorasi Kafe</title>
    
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Library (Lucide) -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F5EFEB;
        }
        .font-serif-title {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="text-[#3D2C24] min-h-screen flex flex-col justify-between">

    <!-- Navbar Atas -->
    <header class="bg-[#FCF9F2] border-b border-[#E5DEC9] py-4 px-6 md:px-12 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="bg-[#3D2C24] text-[#EEF2DA] p-1.5 rounded-lg font-bold text-base w-7 h-7 flex items-center justify-center">c</div>
                <span class="text-xl font-bold tracking-tight">coffenfo</span>
            </div>

            <!-- Search Bar di Tengah -->
            <div class="hidden md:flex items-center bg-[#F2EBE1] px-4 py-2 rounded-full w-96 border border-[#E5DEC9]">
                <i data-lucide="search" class="w-4 h-4 text-[#8C827A] mr-2"></i>
                <input type="text" placeholder="Cari kafe, lokasi, atau menu..." class="bg-transparent text-xs w-full focus:outline-none text-[#3D2C24] placeholder-[#8C827A]">
            </div>

            <!-- Lokasi & Profil -->
            <div class="flex items-center gap-3 text-xs md:text-sm font-medium">
                <div class="flex items-center gap-1 bg-[#F2EBE1] px-3 py-1.5 rounded-lg">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    <span>Batam Center</span>
                </div>
                <a href="{{ route('dashboard') }}" class="w-8 h-8 rounded-full bg-[#3D2C24] text-white flex items-center justify-center font-bold text-xs">
                    P
                </a>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="max-w-7xl mx-auto px-4 md:px-12 py-8 w-full">
        
        <h1 class="text-xl font-serif-title font-bold mb-4">Filter Bar & Kriteria Spesialisasi Kopi</h1>

        <!-- FILTER CAFE BOX -->
        <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-6 shadow-xs mb-10">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4 text-[#3D2C24]"></i>
                    <h3 class="font-bold text-sm">Filter Cafe</h3>
                </div>
                <button class="text-xs text-[#8C827A] hover:text-[#3D2C24] underline">Reset Filter</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs border-b border-[#E5DEC9] pb-6 mb-6">
                <!-- Specialty Kopi -->
                <div>
                    <p class="font-bold text-[#6E645E] mb-2 uppercase tracking-wider text-[10px]">SPECIALTY KOPI</p>
                    <p class="mb-2">Jenis Beans:</p>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-[#3D2C24] text-white rounded-lg font-semibold">Single Origin</span>
                        <span class="px-3 py-1 bg-white border border-[#E5DEC9] rounded-lg">Blend</span>
                    </div>
                </div>

                <!-- Roasting Level -->
                <div>
                    <p class="font-bold text-[#6E645E] mb-2 uppercase tracking-wider text-[10px]">ROASTING LEVEL</p>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-white border border-[#E5DEC9] rounded-lg">Light</span>
                        <span class="px-3 py-1 bg-[#3D2C24] text-white rounded-lg font-semibold">Medium</span>
                        <span class="px-3 py-1 bg-white border border-[#E5DEC9] rounded-lg">Dark</span>
                    </div>
                </div>

                <!-- Seduh Brewing -->
                <div>
                    <p class="font-bold text-[#6E645E] mb-2 uppercase tracking-wider text-[10px]">SEDUH BREWING</p>
                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" checked>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#3D2C24]"></div>
                        </label>
                        <span class="font-medium">Self-Brewing Available</span>
                    </div>
                </div>
            </div>

            <!-- Filter Harga & Fasilitas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                <div>
                    <p class="font-bold text-[#6E645E] mb-2 uppercase tracking-wider text-[10px]">RENTANG HARGA</p>
                    <div class="flex gap-2">
                        <span class="w-8 h-8 rounded-lg bg-white border border-[#E5DEC9] flex items-center justify-center font-bold">$</span>
                        <span class="w-8 h-8 rounded-lg bg-[#3D2C24] text-white flex items-center justify-center font-bold">$$</span>
                        <span class="w-8 h-8 rounded-lg bg-white border border-[#E5DEC9] flex items-center justify-center font-bold">$$$</span>
                        <span class="w-8 h-8 rounded-lg bg-white border border-[#E5DEC9] flex items-center justify-center font-bold">$$$$</span>
                    </div>
                </div>
                <div>
                    <p class="font-bold text-[#6E645E] mb-2 uppercase tracking-wider text-[10px]">FASILITAS & RATING</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-[#3D2C24] text-white rounded-lg">✓ WiFi</span>
                        <span class="px-3 py-1 bg-white border border-[#E5DEC9] rounded-lg">Outdoor</span>
                        <span class="px-3 py-1 bg-white border border-[#E5DEC9] rounded-lg">Plug/Colokan</span>
                        <span class="px-3 py-1 bg-white border border-[#E5DEC9] rounded-lg">Indoor AC</span>
                        <span class="px-3 py-1 bg-[#3D2C24] text-white rounded-lg">★ 4.0+</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- REKOMENDASI CAFE TERDEKAT -->
        <div class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-serif-title font-bold">Rekomendasi Cafe Terdekat</h2>
                    <p class="text-xs text-[#6E645E]">3 kafe dalam radius 5 km dari Batam Center</p>
                </div>
                <a href="#" class="text-xs font-semibold text-[#3D2C24] hover:underline">Lihat semua &gt;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Card 1 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs relative">
                    <div class="h-44 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=500&q=80" alt="Cafe" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#EEF2DA] text-[#3D2C24] text-[10px] font-semibold px-2 py-0.5 rounded-md">Buka • s/d 23:00</span>
                        <button class="absolute top-3 right-3 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm">♥</button>
                    </div>
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="font-bold text-sm">Anchor Cafe & Roastery</h4>
                        <span class="text-xs font-bold text-amber-600">★ 4.9 (200+ Review)</span>
                    </div>
                    <p class="text-[11px] text-[#6E645E] mb-3">Ruko Imperium Culinary Park Blok RK No. 11, Sukajadi, Batam Kota</p>
                    <div class="flex gap-1 mb-4 flex-wrap">
                        <span class="px-2 py-0.5 bg-white border border-[#E5DEC9] rounded text-[10px]">Single Origin Blend</span>
                        <span class="px-2 py-0.5 bg-white border border-[#E5DEC9] rounded text-[10px]">Medium Roast</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px] text-[#6E645E] border-t border-[#E5DEC9] pt-2">
                        <span>☕ 🍃 🔌</span>
                        <span>$$ • 0,8 km</span>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs relative">
                    <div class="h-44 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=500&q=80" alt="Cafe" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#EEF2DA] text-[#3D2C24] text-[10px] font-semibold px-2 py-0.5 rounded-md">Buka • s/d 22:00</span>
                        <button class="absolute top-3 right-3 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm">♥</button>
                    </div>
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="font-bold text-sm">Hooilo Coffee</h4>
                        <span class="text-xs font-bold text-amber-600">★ 4.8 (170+ Review)</span>
                    </div>
                    <p class="text-[11px] text-[#6E645E] mb-3">Ruko Kawasan Industri Tunas Pol. 1, Batam, Batam Kota</p>
                    <div class="flex gap-1 mb-4 flex-wrap">
                        <span class="px-2 py-0.5 bg-white border border-[#E5DEC9] rounded text-[10px]">Single Origin & Blend</span>
                        <span class="px-2 py-0.5 bg-white border border-[#E5DEC9] rounded text-[10px]">Self-Brewing</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px] text-[#6E645E] border-t border-[#E5DEC9] pt-2">
                        <span>☕ 🍃 🔌</span>
                        <span>$$$ • 1,2 km</span>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs relative">
                    <div class="h-44 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=500&q=80" alt="Cafe" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#EEF2DA] text-[#3D2C24] text-[10px] font-semibold px-2 py-0.5 rounded-md">Buka • s/d 23:00</span>
                        <button class="absolute top-3 right-3 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm">♥</button>
                    </div>
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="font-bold text-sm">Roleka Rumah Pamer & Kopi</h4>
                        <span class="text-xs font-bold text-amber-600">★ 4.6 (120+ Review)</span>
                    </div>
                    <p class="text-[11px] text-[#6E645E] mb-3">Taman Baloi, Batam Kota</p>
                    <div class="flex gap-1 mb-4 flex-wrap">
                        <span class="px-2 py-0.5 bg-white border border-[#E5DEC9] rounded text-[10px]">Single Origin</span>
                        <span class="px-2 py-0.5 bg-white border border-[#E5DEC9] rounded text-[10px]">Medium Roast</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px] text-[#6E645E] border-t border-[#E5DEC9] pt-2">
                        <span>☕ 🍃 🔌</span>
                        <span>$ • 2,1 km</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- PALING POPULER DI BATAM -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-serif-title font-bold">Paling Populer di Batam</h2>
                    <p class="text-xs text-[#6E645E]">Dipilih berdasarkan rating dan jumlah ulasan terbanyak</p>
                </div>
                <a href="#" class="text-xs font-semibold text-[#3D2C24] hover:underline">Lihat semua &gt;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card Populer 1 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs">
                    <div class="h-44 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://images.unsplash.com/photo-1559925393-8be0ec4767c8?auto=format&fit=crop&w=500&q=80" alt="Cafe" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#EEF2DA] text-[#3D2C24] text-[10px] font-semibold px-2 py-0.5 rounded-md">Buka • s/d 22:00</span>
                    </div>
                    <h4 class="font-bold text-sm mb-1">Meilleur Cafe & Patisserie</h4>
                    <p class="text-[11px] text-[#6E645E] mb-3">Batam Center, Batam</p>
                    <span class="text-xs font-bold text-amber-600">★ 4.9 (300+ Review)</span>
                </div>

                <!-- Card Populer 2 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs">
                    <div class="h-44 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=500&q=80" alt="Cafe" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#EEF2DA] text-[#3D2C24] text-[10px] font-semibold px-2 py-0.5 rounded-md">Buka • s/d 21:00</span>
                    </div>
                    <h4 class="font-bold text-sm mb-1">Charlotte Cafe</h4>
                    <p class="text-[11px] text-[#6E645E] mb-3">Batam Center, Batam</p>
                    <span class="text-xs font-bold text-amber-600">★ 4.8 (210+ Review)</span>
                </div>

                <!-- Card Populer 3 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs">
                    <div class="h-44 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=500&q=80" alt="Cafe" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-[#EEF2DA] text-[#3D2C24] text-[10px] font-semibold px-2 py-0.5 rounded-md">Buka • s/d 23:00</span>
                    </div>
                    <h4 class="font-bold text-sm mb-1">Lintas Coffee & Space</h4>
                    <p class="text-[11px] text-[#6E645E] mb-3">Batam Center, Batam</p>
                    <span class="text-xs font-bold text-amber-600">★ 4.7 (190+ Review)</span>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-[#FCF9F2] border-t border-[#E5DEC9] py-6 px-6 mt-16 text-center text-xs text-[#8C827A]">
        © 2026 Coffenfo. Diracik untuk pecinta kopi.
    </footer>

    <!-- Script Init Icon -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>