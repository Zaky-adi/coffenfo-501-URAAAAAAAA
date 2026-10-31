<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Filter Bar & Rekomendasi Cafe</title>
    
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CDN Alpine.js untuk Interaktivitas Klik Toggle/Chip -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F5F0;
        }
        .font-serif-title {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="text-[#3D2C24] min-h-screen flex flex-col justify-between" x-data="{
    // State Alpine.js untuk Filter Interaktif
    jenisBeans: 'Single Origin',
    roastingLevel: 'Medium',
    selfBrewing: true,
    priceRange: '$$',
    ratingFilter: '4.0',
    facilities: { wifi: true, outdoor: false, stopkontak: true, ac: true },
    resetFilters() {
        this.jenisBeans = 'Single Origin';
        this.roastingLevel = 'Medium';
        this.selfBrewing = false;
        this.priceRange = '$$';
        this.ratingFilter = '4.0';
        this.facilities = { wifi: false, outdoor: false, stopkontak: false, ac: false };
    }
}">

    <!-- NAVBAR -->
    <header class="bg-[#F8F5F0] border-b border-[#E5DEC9] py-3.5 px-6 md:px-12 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="bg-[#3D2C24] text-[#EEF2DA] p-1.5 rounded-lg font-bold text-base w-7 h-7 flex items-center justify-center">c</div>
                <a href="{{ url('/') }}" class="text-xl font-bold tracking-tight">Coffenfo</a>
            </div>

            <!-- Search Bar Tengah -->
            <div class="hidden md:flex items-center bg-[#EEE8DC] border border-[#E0D7C3] rounded-full px-4 py-1.5 w-full max-w-md">
                <i data-lucide="search" class="w-4 h-4 text-[#8C827A] mr-2"></i>
                <input type="text" placeholder="Cari cafe, lokasi, atau menu..." class="w-full text-xs focus:outline-none bg-transparent placeholder-[#8C827A]">
            </div>

            <!-- Area Selector & Avatar -->
            <div class="flex items-center gap-3">
                <button class="bg-[#EEE8DC] border border-[#E0D7C3] px-3 py-1.5 rounded-full text-xs font-medium text-[#3D2C24] flex items-center gap-1.5">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#3D2C24]"></i>
                    <span>Batam Center</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </button>
                <div class="w-8 h-8 rounded-full bg-[#C36930] text-white flex items-center justify-center text-xs font-bold shadow-xs">
                    RO
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="max-w-7xl mx-auto px-6 md:px-12 py-6 w-full space-y-10">
        
        <!-- PANEL FILTER CAFE INTERAKTIF -->
        <div class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-6">
            
            <!-- Header Filter -->
            <div class="flex justify-between items-center pb-4 border-b border-[#E5DEC9]">
                <div class="flex items-center gap-2 font-bold text-sm">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4 text-[#3D2C24]"></i>
                    <span>Filter Cafe</span>
                </div>
                <button @click="resetFilters()" class="text-xs text-[#8C827A] hover:text-[#3D2C24] transition font-medium cursor-pointer">Hapus filter</button>
            </div>

            <!-- Baris 1: Spesialisasi Kopi -->
            <div class="space-y-3">
                <span class="text-[10px] font-bold text-[#8C827A] uppercase tracking-wider">SPESIALISASI KOPI</span>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    
                    <!-- Chips Jenis Beans -->
                    <div>
                        <label class="block text-[11px] text-[#8C827A] mb-1.5">Jenis Beans</label>
                        <div class="flex gap-2">
                            <button @click="jenisBeans = 'Single Origin'" 
                                    :class="jenisBeans === 'Single Origin' ? 'bg-[#3D2C24] text-white' : 'bg-white border border-[#E5DEC9] text-[#6E645E]'" 
                                    class="px-4 py-1.5 rounded-full text-xs font-medium transition cursor-pointer">
                                Single Origin
                            </button>
                            <button @click="jenisBeans = 'Blend'" 
                                    :class="jenisBeans === 'Blend' ? 'bg-[#3D2C24] text-white' : 'bg-white border border-[#E5DEC9] text-[#6E645E]'" 
                                    class="px-4 py-1.5 rounded-full text-xs font-medium transition cursor-pointer">
                                Blend
                            </button>
                        </div>
                    </div>

                    <!-- Chips Roasting Level -->
                    <div>
                        <label class="block text-[11px] text-[#8C827A] mb-1.5">Roasting Level</label>
                        <div class="flex gap-2">
                            <template x-for="level in ['Light', 'Medium', 'Dark']">
                                <button @click="roastingLevel = level" 
                                        :class="roastingLevel === level ? 'bg-[#3D2C24] text-white' : 'bg-white border border-[#E5DEC9] text-[#6E645E]'" 
                                        class="px-3.5 py-1.5 rounded-full text-xs font-medium transition cursor-pointer"
                                        x-text="level">
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Toggle Switch Self-Brewing -->
                    <div>
                        <label class="block text-[11px] text-[#8C827A] mb-1.5">Self-Brewing</label>
                        <button @click="selfBrewing = !selfBrewing" class="inline-flex items-center cursor-pointer gap-2.5">
                            <div class="w-9 h-5 rounded-full relative transition-colors duration-200" :class="selfBrewing ? 'bg-[#3D2C24]' : 'bg-[#D4CBB6]'">
                                <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 transition-transform duration-200" :class="selfBrewing ? 'translate-x-4' : 'translate-x-0.5'"></div>
                            </div>
                            <span class="text-xs font-medium text-[#3D2C24]">Self-Brewing Available</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Baris 2: Filter Lanjut -->
            <div class="space-y-3 pt-2 border-t border-[#E5DEC9]">
                <span class="text-[10px] font-bold text-[#8C827A] uppercase tracking-wider">FILTER LANJUT</span>
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- Chips Rentang Harga -->
                    <div class="lg:col-span-3">
                        <label class="block text-[11px] text-[#8C827A] mb-1.5">Rentang Harga</label>
                        <div class="flex gap-1.5">
                            <template x-for="p in ['$', '$$', '$$$', '$$$$']">
                                <button @click="priceRange = p" 
                                        :class="priceRange === p ? 'bg-[#3D2C24] text-white' : 'bg-white border border-[#E5DEC9] text-[#6E645E]'" 
                                        class="px-3 py-1 rounded-full text-xs font-medium transition cursor-pointer"
                                        x-text="p">
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Checkboxes Fasilitas -->
                    <div class="lg:col-span-5">
                        <label class="block text-[11px] text-[#8C827A] mb-1.5">Fasilitas</label>
                        <div class="flex flex-wrap gap-4 text-xs font-medium text-[#3D2C24] pt-1">
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" x-model="facilities.wifi" class="accent-[#3D2C24] rounded w-4 h-4"> WiFi
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" x-model="facilities.outdoor" class="accent-[#3D2C24] rounded w-4 h-4"> Outdoor
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" x-model="facilities.stopkontak" class="accent-[#3D2C24] rounded w-4 h-4"> Stopkontak
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" x-model="facilities.ac" class="accent-[#3D2C24] rounded w-4 h-4"> Indoor AC
                            </label>
                        </div>
                    </div>

                    <!-- Chips Rating -->
                    <div class="lg:col-span-4">
                        <label class="block text-[11px] text-[#8C827A] mb-1.5">Rating Minimal</label>
                        <div class="flex gap-1.5">
                            <template x-for="r in ['3.0', '3.5', '4.0', '4.5']">
                                <button @click="ratingFilter = r" 
                                        :class="ratingFilter === r ? 'bg-[#3D2C24] text-white' : 'bg-white border border-[#E5DEC9] text-[#6E645E]'" 
                                        class="px-2.5 py-1 rounded-full text-xs font-medium transition cursor-pointer flex items-center gap-1">
                                    <span>★</span> <span x-text="r + '+'"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- REKOMENDASI CAFE TERDEKAT -->
        <section class="space-y-4">
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="text-xl font-serif-title font-bold">Rekomendasi Cafe Terdekat</h2>
                    <p class="text-xs text-[#8C827A]">3 cafe dalam radius 3 km dari Batam Center</p>
                </div>
                <a href="#" class="text-xs font-medium text-[#8C827A] hover:text-[#3D2C24]">Lihat semua ›</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Kafe 1 (Interaktif Bookmark) -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-3.5 shadow-xs flex flex-col justify-between space-y-3" x-data="{ bookmarked: false }">
                    <div>
                        <div class="h-44 rounded-2xl overflow-hidden relative bg-[#EAE2D5]">
                            <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                            <div class="absolute bottom-2.5 left-2.5 bg-black/70 backdrop-blur-xs text-white text-[10px] px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Buka • s/d 23:00
                            </div>
                            
                            <!-- Tombol Bookmark Interaktif -->
                            <button @click="bookmarked = !bookmarked" 
                                    :class="bookmarked ? 'bg-[#C36930] text-white' : 'bg-white/80 text-[#3D2C24] hover:bg-white'" 
                                    class="absolute top-2.5 right-2.5 p-1.5 rounded-full transition cursor-pointer">
                                <i data-lucide="bookmark" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>

                        <div class="mt-3 flex justify-between items-start">
                            <h3 class="font-bold text-sm text-[#3D2C24]">Grind Now Coffee Bar</h3>
                            <span class="bg-[#FEF3D6] text-[#B45309] text-[10px] font-bold px-2 py-0.5 rounded-full">★ 4.9 (120+ Review)</span>
                        </div>
                        <p class="text-[11px] text-[#8C827A] flex items-center gap-1 mt-1">
                            <i data-lucide="map-pin" class="w-3 h-3"></i> Ruko Palm Spring, Batam Center
                        </p>

                        <div class="flex flex-wrap gap-1.5 mt-2.5">
                            <span class="bg-[#F8F5F0] border border-[#E5DEC9] text-[#6E645E] text-[10px] px-2.5 py-0.5 rounded-md font-medium">Single Origin & Blend</span>
                            <span class="bg-[#F8F5F0] border border-[#E5DEC9] text-[#6E645E] text-[10px] px-2.5 py-0.5 rounded-md font-medium">Medium Roast</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-[#F0EAE0] text-[11px] text-[#8C827A]">
                        <div class="flex gap-2 text-[#3D2C24]">
                            <i data-lucide="wifi" class="w-3.5 h-3.5"></i>
                            <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                            <i data-lucide="coffee" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="font-semibold text-[#3D2C24]">$$ • 0,9 km</span>
                    </div>
                </div>

                <!-- Kafe 2 -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-3.5 shadow-xs flex flex-col justify-between space-y-3" x-data="{ bookmarked: true }">
                    <div>
                        <div class="h-44 rounded-2xl overflow-hidden relative bg-[#EAE2D5]">
                            <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                            <div class="absolute bottom-2.5 left-2.5 bg-black/70 backdrop-blur-xs text-white text-[10px] px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Buka • s/d 22:00
                            </div>
                            <button @click="bookmarked = !bookmarked" 
                                    :class="bookmarked ? 'bg-[#C36930] text-white' : 'bg-white/80 text-[#3D2C24] hover:bg-white'" 
                                    class="absolute top-2.5 right-2.5 p-1.5 rounded-full transition cursor-pointer">
                                <i data-lucide="bookmark" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>

                        <div class="mt-3 flex justify-between items-start">
                            <h3 class="font-bold text-sm text-[#3D2C24]">Heedo Coffee</h3>
                            <span class="bg-[#FEF3D6] text-[#B45309] text-[10px] font-bold px-2 py-0.5 rounded-full">★ 4.8 (110+ Review)</span>
                        </div>
                        <p class="text-[11px] text-[#8C827A] flex items-center gap-1 mt-1">
                            <i data-lucide="map-pin" class="w-3 h-3"></i> Tunas, Belian, Batam Kota
                        </p>

                        <div class="flex flex-wrap gap-1.5 mt-2.5">
                            <span class="bg-[#F8F5F0] border border-[#E5DEC9] text-[#6E645E] text-[10px] px-2.5 py-0.5 rounded-md font-medium">Single Origin & Blend</span>
                            <span class="bg-[#F8F5F0] border border-[#E5DEC9] text-[#6E645E] text-[10px] px-2.5 py-0.5 rounded-md font-medium">Self-Brewing</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-[#F0EAE0] text-[11px] text-[#8C827A]">
                        <div class="flex gap-2 text-[#3D2C24]">
                            <i data-lucide="wifi" class="w-3.5 h-3.5"></i>
                            <i data-lucide="sun" class="w-3.5 h-3.5"></i>
                            <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="font-semibold text-[#3D2C24]">$$$ • 1,2 km</span>
                    </div>
                </div>

                <!-- Kafe 3 -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-3.5 shadow-xs flex flex-col justify-between space-y-3" x-data="{ bookmarked: false }">
                    <div>
                        <div class="h-44 rounded-2xl overflow-hidden relative bg-[#EAE2D5]">
                            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                            <div class="absolute bottom-2.5 left-2.5 bg-black/70 backdrop-blur-xs text-white text-[10px] px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Buka • s/d 23:00
                            </div>
                            <button @click="bookmarked = !bookmarked" 
                                    :class="bookmarked ? 'bg-[#C36930] text-white' : 'bg-white/80 text-[#3D2C24] hover:bg-white'" 
                                    class="absolute top-2.5 right-2.5 p-1.5 rounded-full transition cursor-pointer">
                                <i data-lucide="bookmark" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>

                        <div class="mt-3 flex justify-between items-start">
                            <h3 class="font-bold text-sm text-[#3D2C24]">Rubeka Rumah Sangker & Kopi</h3>
                            <span class="bg-[#FEF3D6] text-[#B45309] text-[10px] font-bold px-2 py-0.5 rounded-full">★ 4.8 (130+ Review)</span>
                        </div>
                        <p class="text-[11px] text-[#8C827A] flex items-center gap-1 mt-1">
                            <i data-lucide="map-pin" class="w-3 h-3"></i> Taman Baloi, Batam Kota
                        </p>

                        <div class="flex flex-wrap gap-1.5 mt-2.5">
                            <span class="bg-[#F8F5F0] border border-[#E5DEC9] text-[#6E645E] text-[10px] px-2.5 py-0.5 rounded-md font-medium">Single Origin</span>
                            <span class="bg-[#F8F5F0] border border-[#E5DEC9] text-[#6E645E] text-[10px] px-2.5 py-0.5 rounded-md font-medium">Medium Roast</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-[#F0EAE0] text-[11px] text-[#8C827A]">
                        <div class="flex gap-2 text-[#3D2C24]">
                            <i data-lucide="wifi" class="w-3.5 h-3.5"></i>
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="font-semibold text-[#3D2C24]">$ • 1,6 km</span>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#F8F5F0] border-t border-[#E5DEC9] py-6 px-6 md:px-12 mt-16 flex justify-between items-center text-xs text-[#8C827A]">
        <p>© 2026 Coffenfo • Setiap cangkir, sebuah cerita.</p>
        <a href="{{ url('/') }}" class="font-bold text-[#3D2C24] hover:underline">← Kembali ke Beranda</a>
    </footer>

    <!-- Init Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>