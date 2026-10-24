<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Review & Rating</title>
    
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
                <a href="{{ route('explore') }}" class="bg-[#F2EBE1] px-3 py-1.5 rounded-lg hover:bg-[#E5DEC9] transition">Jelajahi Kafe</a>
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
        
        <!-- Breadcrumb / Header Title -->
        <div class="mb-6">
            <span class="text-xs text-[#8C827A]">Review & Rating</span>
            <h1 class="text-2xl font-serif-title font-bold">Review & Rating</h1>
            <p class="text-xs text-[#6E645E]">Lintas Coffee & Space • Batam Center, Batam</p>
        </div>

        <!-- Grid Layout Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- KOLOM KIRI: Ringkasan Rating & Tombol Tulis Review -->
            <div class="lg:col-span-4 bg-[#FCF9F2] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs">
                <h2 class="font-bold text-sm mb-4">Rating Keseluruhan</h2>
                
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-5xl font-extrabold font-serif-title">4.7</span>
                    <span class="text-xs text-[#8C827A]">/ 5.0</span>
                </div>

                <!-- Bintang Kuning -->
                <div class="flex text-amber-500 gap-1 mb-2">
                    ★★★★★
                </div>
                <p class="text-xs text-[#6E645E] mb-6">Berdasarkan 120+ review</p>

                <!-- Baris Statistik Rating 1 - 5 -->
                <div class="space-y-2 text-xs text-[#6E645E] mb-8">
                    <div class="flex items-center gap-2">
                        <span class="w-3">5</span> <span class="text-amber-500">★</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden"><div class="bg-[#3D2C24] h-full w-[76%]"></div></div>
                        <span class="w-8 text-right">76%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3">4</span> <span class="text-amber-500">★</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden"><div class="bg-[#3D2C24] h-full w-[16%]"></div></div>
                        <span class="w-8 text-right">16%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3">3</span> <span class="text-amber-500">★</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden"><div class="bg-[#3D2C24] h-full w-[4%]"></div></div>
                        <span class="w-8 text-right">4%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3">2</span> <span class="text-amber-500">★</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden"><div class="bg-[#3D2C24] h-full w-[2%]"></div></div>
                        <span class="w-8 text-right">2%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3">1</span> <span class="text-amber-500">★</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden"><div class="bg-[#3D2C24] h-full w-[1%]"></div></div>
                        <span class="w-8 text-right">1%</span>
                    </div>
                </div>

                <!-- Tombol Tulis Review -->
                <button class="w-full bg-[#3D2C24] text-white py-3 rounded-xl text-xs font-semibold flex items-center justify-center gap-2 hover:bg-black transition">
                    <i data-lucide="edit-3" class="w-4 h-4"></i> Tulis Review
                </button>
            </div>

            <!-- KOLOM KANAN: Daftar Review & Filter -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Bar Filter & Sort -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#FCF9F2] p-4 rounded-2xl border border-[#E5DEC9]">
                    <div class="flex flex-wrap gap-2 text-xs">
                        <button class="px-3 py-1.5 bg-[#3D2C24] text-white rounded-lg font-medium">Semua</button>
                        <button class="px-3 py-1.5 bg-white border border-[#E5DEC9] text-[#6E645E] rounded-lg hover:text-[#3D2C24]">Dengan Foto</button>
                        <button class="px-3 py-1.5 bg-white border border-[#E5DEC9] text-[#6E645E] rounded-lg hover:text-[#3D2C24]">⭐ 5</button>
                        <button class="px-3 py-1.5 bg-white border border-[#E5DEC9] text-[#6E645E] rounded-lg hover:text-[#3D2C24]">⭐ 4</button>
                        <button class="px-3 py-1.5 bg-white border border-[#E5DEC9] text-[#6E645E] rounded-lg hover:text-[#3D2C24]">⭐ 3</button>
                    </div>

                    <div class="text-xs flex items-center gap-2 text-[#6E645E] self-end sm:self-auto">
                        <span>Urutkan:</span>
                        <select class="bg-white border border-[#E5DEC9] px-3 py-1.5 rounded-lg font-medium text-[#3D2C24] focus:outline-none">
                            <option>Terbaru</option>
                            <option>Rating Tertinggi</option>
                            <option>Paling Bermanfaat</option>
                        </select>
                    </div>
                </div>

                <!-- Card Review 1 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#3D2C24] text-white flex items-center justify-center font-bold text-sm">
                                DP
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Dinda Pratiwi</h4>
                                <p class="text-[11px] text-[#8C827A]">12 September 2026</p>
                            </div>
                        </div>
                        <div class="text-amber-500 text-xs">★★★★★</div>
                    </div>

                    <p class="text-xs text-[#3D2C24] leading-relaxed">
                        Single origin Gayo-nya juara, aromanya wangi dan tidak pahit. Tempatnya adem, WiFi kencang, dan banyak stopkontak, cocok buat kerja seharian.
                    </p>

                    <!-- Foto Review -->
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        <div class="w-20 h-20 rounded-xl bg-[#E5DEC9] flex items-center justify-center text-[#6E645E] text-xs font-semibold shrink-0">☕</div>
                        <div class="w-20 h-20 rounded-xl bg-[#E5DEC9] flex items-center justify-center text-[#6E645E] text-xs font-semibold shrink-0">☕</div>
                        <div class="w-20 h-20 rounded-xl bg-[#E5DEC9] flex items-center justify-center text-[#6E645E] text-xs font-semibold shrink-0">☕</div>
                        <div class="w-20 h-20 rounded-xl bg-[#3D2C24] text-white flex items-center justify-center text-xs font-bold shrink-0">+3</div>
                    </div>

                    <div class="flex justify-between items-center text-[11px] text-[#8C827A] pt-3 border-t border-[#E5DEC9]">
                        <button class="flex items-center gap-1.5 hover:text-[#3D2C24] font-medium bg-[#F2EBE1] px-3 py-1.5 rounded-lg">
                            <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i> Bermanfaat (12)
                        </button>
                        <button class="hover:text-red-600">Laporkan Review</button>
                    </div>
                </div>

                <!-- Card Review 2 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#6E645E] text-white flex items-center justify-center font-bold text-sm">
                                RH
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Rizky Hakim</h4>
                                <p class="text-[11px] text-[#8C827A]">3 September 2026</p>
                            </div>
                        </div>
                        <div class="text-amber-500 text-xs">★★★★★</div>
                    </div>

                    <p class="text-xs text-[#3D2C24] leading-relaxed">
                        Area self-brewing-nya seru, barista mau ngajarin cara V60 yang benar. Sayang akhir pekan agak penuh, jadi datang sebelum jam 4 sore.
                    </p>

                    <!-- Foto Review -->
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        <div class="w-20 h-20 rounded-xl bg-[#E5DEC9] flex items-center justify-center text-[#6E645E] text-xs font-semibold shrink-0">☕</div>
                        <div class="w-20 h-20 rounded-xl bg-[#E5DEC9] flex items-center justify-center text-[#6E645E] text-xs font-semibold shrink-0">☕</div>
                    </div>

                    <div class="flex justify-between items-center text-[11px] text-[#8C827A] pt-3 border-t border-[#E5DEC9]">
                        <button class="flex items-center gap-1.5 hover:text-[#3D2C24] font-medium bg-[#F2EBE1] px-3 py-1.5 rounded-lg">
                            <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i> Bermanfaat (8)
                        </button>
                        <button class="hover:text-red-600">Laporkan Review</button>
                    </div>
                </div>

                <!-- Tombol Muat Review Lainnya -->
                <div class="text-center pt-4">
                    <button class="px-6 py-3 bg-[#FCF9F2] border border-[#E5DEC9] rounded-xl text-xs font-semibold text-[#3D2C24] hover:bg-[#F2EBE1] transition shadow-xs">
                        Muat Review Lainnya
                    </button>
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