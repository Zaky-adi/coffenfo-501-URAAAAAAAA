<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Dashboard & Katalog Cafe</title>
    
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

            <!-- Menu Navigasi / Info -->
            <div class="flex items-center gap-3 text-xs md:text-sm font-medium">
                <span class="bg-[#F2EBE1] px-3 py-1.5 rounded-lg">Jelajahi Kafe</span>
                <span class="text-[#6E645E]">Koleksi Tersimpan</span>
                <div class="flex items-center gap-1 bg-[#F2EBE1] px-3 py-1.5 rounded-lg">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    <span>Batam</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-[#3D2C24] text-white flex items-center justify-center font-bold text-xs">
                    Profil
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama Dashboard -->
    <main class="max-w-7xl mx-auto px-4 md:px-12 py-8 w-full">
        
        <!-- Breadcrumb & Galeri Foto Kafe -->
        <div class="mb-6">
            <p class="text-xs text-[#6E645E] mb-3">Jelajahi Cafe &gt; <span class="font-bold text-[#3D2C24]">Grind Now Coffee Bar</span></p>
            
            <!-- Grid Foto -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Foto Besar Kiri -->
                <div class="md:col-span-8 h-[320px] md:h-[400px] rounded-2xl overflow-hidden shadow-sm relative">
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1000&q=80" alt="Cafe Interior" class="w-full h-full object-cover">
                </div>
                <!-- Foto Kecil Kanan -->
                <div class="md:col-span-4 flex flex-col gap-4">
                    <div class="h-[152px] md:h-[192px] rounded-2xl overflow-hidden shadow-sm">
                        <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=600&q=80" alt="Coffee Bar" class="w-full h-full object-cover">
                    </div>
                    <div class="h-[152px] md:h-[192px] rounded-2xl overflow-hidden shadow-sm">
                        <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=600&q=80" alt="Coffee Cup" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Judul Kafe & Tombol Aksi -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-[#E5DEC9] pb-6 mb-6 gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1 flex-wrap">
                    <h1 class="text-3xl md:text-4xl font-serif-title font-bold">Grind Now Coffee Bar</h1>
                    <span class="bg-[#EEF2DA] text-[#3D2C24] text-xs font-semibold px-2.5 py-1 rounded-md">Buka • 09:00 - 23:00 WIB</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-[#6E645E]">
                    <span class="bg-[#F2EBE1] px-2 py-0.5 rounded font-bold text-[#3D2C24]">4.9</span>
                    <span>(128 ulasan)</span>
                    <span>•</span>
                    <span>Coffee Bar</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 bg-white border border-[#E5DEC9] rounded-xl text-xs font-semibold hover:bg-gray-50 flex items-center gap-1.5 shadow-xs">
                    <i data-lucide="bookmark" class="w-4 h-4"></i> Simpan
                </button>
                <button class="px-4 py-2 bg-white border border-[#E5DEC9] rounded-xl text-xs font-semibold hover:bg-gray-50 flex items-center gap-1.5 shadow-xs">
                    <i data-lucide="share-2" class="w-4 h-4"></i> Bagikan
                </button>
            </div>
        </div>

        <!-- Info Singkat / Deskripsi -->
        <p class="text-sm text-[#6E645E] leading-relaxed mb-8 max-w-4xl">
            Grind Now Coffee Bar adalah tempat nongkrong asyik dengan suasana industrial modern, pilihan biji kopi pilihan, serta barista ramah yang siap meracik kopi terbaik untuk harimu.
        </p>

        <!-- LAYOUT UTAMA KONTEN BAWAH (Grid Kiri & Kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- SISI KIRI: Kenali Kopimu, Menu, & Review -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Kenali Kopimu Box -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-6 shadow-xs">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="coffee" class="w-5 h-5 text-[#3D2C24]"></i>
                        <h3 class="font-bold text-base">Kenali Kopimu</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6 text-xs mb-4">
                        <div><span class="text-[#6E645E]">Jenis Beans:</span> <span class="font-semibold">Single Origin & Blend</span></div>
                        <div><span class="text-[#6E645E]">Asal Beans:</span> <span class="font-semibold">Arabika</span></div>
                        <div><span class="text-[#6E645E]">Roasting Profile:</span> <span class="font-semibold">Light, Medium, Dark</span></div>
                        <div><span class="text-[#6E645E]">Metode Seduh:</span> <span class="font-semibold">V60, Aeropress, Espresso Style, dan iced brew</span></div>
                    </div>
                    <p class="text-xs text-[#6E645E]">Cocok untuk nugas, WFC, or santai bareng teman.</p>
                </div>

                <!-- Tab Navigasi Menu & Review -->
                <div>
                    <div class="flex gap-2 border-b border-[#E5DEC9] pb-4 mb-4 overflow-x-auto">
                        <button class="px-5 py-2 bg-white border border-[#E5DEC9] rounded-xl text-xs font-semibold shadow-xs">Katalog Menu</button>
                        <button class="px-5 py-2 text-xs font-medium text-[#8C827A] hover:text-[#3D2C24]">Review & Rating</button>
                        <button class="px-5 py-2 text-xs font-medium text-[#8C827A] hover:text-[#3D2C24]">Fasilitas</button>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-serif-title font-bold">Dari bar kopi & dapur</h3>
                        <p class="text-xs text-[#6E645E]">Pilihan Coffenfo untuk rekomendasi terbaikmu.</p>
                    </div>

                    <!-- Filter Kategori Menu -->
                    <div class="flex gap-2 mb-6 overflow-x-auto">
                        <span class="px-4 py-1.5 bg-[#3D2C24] text-white rounded-full text-xs font-semibold whitespace-nowrap">Semua</span>
                        <span class="px-4 py-1.5 bg-white border border-[#E5DEC9] rounded-full text-xs font-medium text-[#6E645E] whitespace-nowrap">Kopi</span>
                        <span class="px-4 py-1.5 bg-white border border-[#E5DEC9] rounded-full text-xs font-medium text-[#6E645E] whitespace-nowrap">Non-Kopi</span>
                        <span class="px-4 py-1.5 bg-white border border-[#E5DEC9] rounded-full text-xs font-medium text-[#6E645E] whitespace-nowrap">Cemilan</span>
                    </div>

                    <!-- Grid Menu Card (6 Item) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        
                        <!-- Menu 1 -->
                        <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="h-32 rounded-xl overflow-hidden mb-3">
                                    <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=400&q=80" alt="Menu" class="w-full h-full object-cover">
                                </div>
                                <h4 class="font-bold text-sm mb-1">Manual Brew V60 House Blend</h4>
                                <p class="text-[11px] text-[#6E645E] line-clamp-2 mb-2">Segar dan kaya rasa khas biji pilihan terbaik nusantara.</p>
                            </div>
                            <span class="font-bold text-sm text-[#3D2C24]">Rp45.000</span>
                        </div>

                        <!-- Menu 2 -->
                        <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="h-32 rounded-xl overflow-hidden mb-3">
                                    <img src="https://images.unsplash.com/photo-1534778101976-62847782c213?auto=format&fit=crop&w=400&q=80" alt="Menu" class="w-full h-full object-cover">
                                </div>
                                <h4 class="font-bold text-sm mb-1">Mugh Potion</h4>
                                <p class="text-[11px] text-[#6E645E] line-clamp-2 mb-2">Es kopi susu signature khas dari Grind Now.</p>
                            </div>
                            <span class="font-bold text-sm text-[#3D2C24]">Rp38.000</span>
                        </div>

                        <!-- Menu 3 -->
                        <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="h-32 rounded-xl overflow-hidden mb-3">
                                    <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=400&q=80" alt="Menu" class="w-full h-full object-cover">
                                </div>
                                <h4 class="font-bold text-sm mb-1">Jungle Mint</h4>
                                <p class="text-[11px] text-[#6E645E] line-clamp-2 mb-2">Espresso dengan kesegaran mint dan soda pilihan.</p>
                            </div>
                            <span class="font-bold text-sm text-[#3D2C24]">Rp44.000</span>
                        </div>

                        <!-- Menu 4 -->
                        <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="h-32 rounded-xl overflow-hidden mb-3">
                                    <img src="https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=400&q=80" alt="Menu" class="w-full h-full object-cover">
                                </div>
                                <h4 class="font-bold text-sm mb-1">Salted Egg Chicken</h4>
                                <p class="text-[11px] text-[#6E645E] line-clamp-2 mb-2">Nasi ayam saus telur asin gurih nan nikmat.</p>
                            </div>
                            <span class="font-bold text-sm text-[#3D2C24]">Rp48.000</span>
                        </div>

                        <!-- Menu 5 -->
                        <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="h-32 rounded-xl overflow-hidden mb-3">
                                    <img src="https://images.unsplash.com/photo-1621996346565-e3d5d6281298?auto=format&fit=crop&w=400&q=80" alt="Menu" class="w-full h-full object-cover">
                                </div>
                                <h4 class="font-bold text-sm mb-1">Meatballs Bolognese</h4>
                                <p class="text-[11px] text-[#6E645E] line-clamp-2 mb-2">Pasta lezat dengan saus tomat khas Italia dan bakso.</p>
                            </div>
                            <span class="font-bold text-sm text-[#3D2C24]">Rp46.000</span>
                        </div>

                        <!-- Menu 6 -->
                        <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="h-32 rounded-xl overflow-hidden mb-3">
                                    <img src="https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=400&q=80" alt="Menu" class="w-full h-full object-cover">
                                </div>
                                <h4 class="font-bold text-sm mb-1">Caffe Latte</h4>
                                <p class="text-[11px] text-[#6E645E] line-clamp-2 mb-2">Perpaduan espresso dan steamed milk yang lembut.</p>
                            </div>
                            <span class="font-bold text-sm text-[#3D2C24]">Rp37.000</span>
                        </div>

                    </div>
                </div>

                <!-- Review & Rating Section -->
                <div>
                    <h3 class="text-lg font-serif-title font-bold mb-4">Review & Rating</h3>
                    <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-5 mb-4 shadow-xs flex items-center gap-6">
                        <div class="text-center">
                            <span class="text-4xl font-bold font-serif-title">4.9</span>
                            <div class="flex text-amber-500 text-xs mt-1 justify-center">★★★★★</div>
                        </div>
                        <div class="text-xs text-[#6E645E]">
                            <p class="font-semibold text-[#3D2C24]">Dari 128 ulasan pengunjung lokal</p>
                            <p>Sebagian besar pengunjung memuji kenyamanan tempat dan kualitas kopinya.</p>
                        </div>
                    </div>

                    <!-- List Ulasan -->
                    <div class="space-y-3">
                        <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-xl p-4 text-xs">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold">Nadya P.</span>
                                <span class="text-amber-600 font-bold">★ 5.0</span>
                            </div>
                            <p class="text-[#6E645E]">Tempatnya cozy banget, cocok buat kerja atau nugas. Baristanya ramah dan kopinya enak!</p>
                        </div>
                        <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-xl p-4 text-xs">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold">Raka A.</span>
                                <span class="text-amber-600 font-bold">★ 5.0</span>
                            </div>
                            <p class="text-[#6E645E]">Rekomendasi manual brew-nya juara. Suasana malam hari juga adem dan estetik.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SISI KANAN: Peta & Lokasi + Fasilitas -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Peta & Lokasi Box -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-5 shadow-xs">
                    <h3 class="font-bold text-sm mb-3">Peta & Lokasi</h3>
                    <!-- Dummy Map Image -->
                    <div class="h-36 rounded-xl overflow-hidden mb-3 border border-[#E5DEC9] relative">
                        <img src="https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=500&q=80" alt="Map" class="w-full h-full object-cover">
                    </div>
                    <p class="font-bold text-xs mb-1">Grind Now Coffee Bar</p>
                    <p class="text-[11px] text-[#6E645E] mb-4">Komplek Batam Centre, No. 20, Batam Kota, Batam</p>
                    
                    <button class="w-full bg-[#3D2C24] text-white py-2.5 rounded-xl text-xs font-semibold hover:bg-black transition flex items-center justify-center gap-1.5 mb-4 shadow-sm">
                        <i data-lucide="navigation" class="w-3.5 h-3.5"></i> Petunjuk Arah
                    </button>

                    <div class="border-t border-[#E5DEC9] pt-3 flex items-center gap-2 text-[11px] text-[#6E645E]">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-[#3D2C24]"></i>
                        <span>Buka operasional: 09:00 - 23:00 WIB</span>
                    </div>
                </div>

                <!-- Fasilitas Box -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-5 shadow-xs">
                    <h3 class="font-bold text-sm mb-4">Fasilitas</h3>
                    <div class="grid grid-cols-2 gap-3 text-xs text-[#6E645E]">
                        <div class="flex items-center gap-2"><i data-lucide="wifi" class="w-4 h-4 text-[#3D2C24]"></i><span>Wi-Fi Gratis</span></div>
                        <div class="flex items-center gap-2"><i data-lucide="sun" class="w-4 h-4 text-[#3D2C24]"></i><span>Outdoor Area</span></div>
                        <div class="flex items-center gap-2"><i data-lucide="wind" class="w-4 h-4 text-[#3D2C24]"></i><span>Ruangan AC</span></div>
                        <div class="flex items-center gap-2"><i data-lucide="utensils" class="w-4 h-4 text-[#3D2C24]"></i><span>Food Bar</span></div>
                        <div class="flex items-center gap-2"><i data-lucide="car" class="w-4 h-4 text-[#3D2C24]"></i><span>Parkir Luas</span></div>
                        <div class="flex items-center gap-2"><i data-lucide="zap" class="w-4 h-4 text-[#3D2C24]"></i><span>Colokan Listrik</span></div>
                    </div>
                </div>

            </div>

        </div>

        <!-- BAGIAN BAWAH: Cafe Serupa yang Mungkin Anda Suka -->
        <div class="mt-16 border-t border-[#E5DEC9] pt-10">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-serif-title font-bold">Cafe Serupa yang Mungkin Anda Suka</h3>
                    <p class="text-xs text-[#6E645E]">Tempat baru, cerita kopi berikutnya.</p>
                </div>
                <div class="flex gap-2">
                    <button class="w-8 h-8 rounded-full border border-[#E5DEC9] bg-white flex items-center justify-center text-xs hover:bg-gray-50">&lt;</button>
                    <button class="w-8 h-8 rounded-full border border-[#E5DEC9] bg-white flex items-center justify-center text-xs hover:bg-gray-50">&gt;</button>
                </div>
            </div>

            <!-- Grid 3 Cafe Serupa -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Cafe 1 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs">
                    <div class="h-44 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=500&q=80" alt="Cafe" class="w-full h-full object-cover">
                    </div>
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="font-bold text-sm">Temu Kopi</h4>
                        <span class="text-xs font-bold bg-[#EEF2DA] px-2 py-0.5 rounded">★ 4.8</span>
                    </div>
                    <p class="text-[11px] text-[#6E645E] mb-3">Batam Kota • $$</p>
                    <span class="inline-block px-3 py-1 bg-white border border-[#E5DEC9] rounded-lg text-[10px] font-medium text-[#6E645E]">Rekomendasi</span>
                </div>

                <!-- Cafe 2 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs">
                    <div class="h-44 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://images.unsplash.com/photo-1559925393-8be0ec4767c8?auto=format&fit=crop&w=500&q=80" alt="Cafe" class="w-full h-full object-cover">
                    </div>
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="font-bold text-sm">Ruang Seduh</h4>
                        <span class="text-xs font-bold bg-[#EEF2DA] px-2 py-0.5 rounded">★ 4.7</span>
                    </div>
                    <p class="text-[11px] text-[#6E645E] mb-3">Lubuk Baja • $$</p>
                    <span class="inline-block px-3 py-1 bg-white border border-[#E5DEC9] rounded-lg text-[10px] font-medium text-[#6E645E]">Suasana adem & santai</span>
                </div>

                <!-- Cafe 3 -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-2xl p-3 shadow-xs">
                    <div class="h-44 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=500&q=80" alt="Cafe" class="w-full h-full object-cover">
                    </div>
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="font-bold text-sm">Senja Coffee House</h4>
                        <span class="text-xs font-bold bg-[#EEF2DA] px-2 py-0.5 rounded">★ 4.6</span>
                    </div>
                    <p class="text-[11px] text-[#6E645E] mb-3">Sekupang • $$</p>
                    <span class="inline-block px-3 py-1 bg-white border border-[#E5DEC9] rounded-lg text-[10px] font-medium text-[#6E645E]">Suasana senja</span>
                </div>

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-[#FCF9F2] border-t border-[#E5DEC9] py-6 px-6 mt-16 text-center text-xs text-[#8C827A]">
        © 2026 Coffenfo. Platform review kafe terbaik di Batam.
    </footer>

    <!-- Script Init Icon -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>