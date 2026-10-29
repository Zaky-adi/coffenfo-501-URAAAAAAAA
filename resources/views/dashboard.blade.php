<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Detail Cafe & Katalog</title>
    
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CDN Alpine.js untuk Interaktif Tab, Filter Kategori, & Stepper (- / +) -->
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
    activeTab: 'menu', 
    activeCategory: 'semua',
    cartOpen: false,
    
    menuItems: [
        { id: 1, name: 'Manual Brew In House Beans', category: 'kopi', desc: 'Signature house selection design for balance clarity', price: 45000, qty: 1, img: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=400&q=80' },
        { id: 2, name: 'Magic Potion', category: 'kopi', desc: 'Iced Latte w/ Unique Berry Hint', price: 40000, qty: 0, img: 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=400&q=80' },
        { id: 3, name: 'Jungle Mint', category: 'kopi', desc: 'Combination From Mango Juice W/ Matcha', price: 44000, qty: 0, img: 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=400&q=80' },
        { id: 4, name: 'Salted Egg Chicken', category: 'makanan', desc: 'Rice Bowl', price: 45000, qty: 1, img: 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=400&q=80' },
        { id: 5, name: 'Meatballs Bolognese', category: 'makanan', desc: 'All Day Food', price: 46000, qty: 0, img: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=400&q=80' },
        { id: 6, name: 'Magic', category: 'kopi', desc: 'Double ristretto w/ custom milk (5oz) hot only!', price: 45000, qty: 0, img: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=400&q=80' }
    ],

    get filteredMenu() {
        if (this.activeCategory === 'semua') return this.menuItems;
        return this.menuItems.filter(i => i.category === this.activeCategory);
    },
    get totalCartItems() {
        return this.menuItems.reduce((sum, item) => sum + item.qty, 0);
    },
    get totalPrice() {
        return this.menuItems.reduce((sum, item) => sum + (item.price * item.qty), 0);
    },
    formatRupiah(value) {
        return 'Rp ' + value.toLocaleString('id-ID');
    }
}">

    <!-- NAVBAR UTAMA -->
    <header class="bg-[#F8F5F0] border-b border-[#E5DEC9] py-3.5 px-6 md:px-12 sticky top-0 z-45">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="bg-[#3D2C24] text-[#EEF2DA] p-1.5 rounded-lg font-bold text-base w-7 h-7 flex items-center justify-center">c</div>
                <a href="{{ url('/') }}" class="text-xl font-bold tracking-tight">coffenfo</a>
            </div>

            <!-- Menu Navigasi Kanan -->
            <div class="flex items-center gap-6 text-xs font-semibold text-[#6E645E]">
                <a href="{{ route('explore') }}" class="hover:text-[#3D2C24]">Jelajahi Cafe</a>
                <a href="#" class="hover:text-[#3D2C24]">Cafe Tersimpan</a>
                <button @click="cartOpen = !cartOpen" class="flex items-center gap-1.5 bg-[#EEE8DC] px-3 py-1.5 rounded-full hover:bg-[#E5DEC9] transition cursor-pointer">
                    <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                    <span>Keranjang</span>
                    <span class="bg-[#C36930] text-white text-[10px] px-1.5 py-0.2 rounded-full font-bold" x-text="totalCartItems"></span>
                </button>
                <div class="w-8 h-8 rounded-full bg-[#C36930] text-white flex items-center justify-center text-xs font-bold shadow-xs">
                    RO
                </div>
            </div>
        </div>
    </header>

    <!-- SLIDE-OVER KERJANG -->
    <div x-show="cartOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-xs" @click="cartOpen = false"></div>
        <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-md bg-[#F8F5F0] border-l border-[#E5DEC9] p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center pb-4 border-b border-[#E5DEC9]">
                        <h2 class="font-serif-title font-bold text-lg">Keranjang Pesanan</h2>
                        <button @click="cartOpen = false" class="p-1 rounded-full hover:bg-[#EEE8DC] cursor-pointer">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="mt-4 space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                        <template x-for="item in menuItems.filter(i => i.qty > 0)" :key="item.id">
                            <div class="bg-white border border-[#E5DEC9] p-3 rounded-2xl flex items-center gap-3">
                                <img :src="item.img" class="w-14 h-14 rounded-xl object-cover">
                                <div class="flex-1">
                                    <h4 class="text-xs font-bold" x-text="item.name"></h4>
                                    <p class="text-xs text-[#8C827A] mt-0.5" x-text="formatRupiah(item.price)"></p>
                                </div>
                                <div class="flex items-center gap-2 bg-[#F8F5F0] border border-[#E5DEC9] px-2 py-1 rounded-xl">
                                    <button @click="if(item.qty > 0) item.qty--" class="text-xs font-bold px-1 hover:text-[#C36930] cursor-pointer">-</button>
                                    <span class="text-xs font-bold w-4 text-center" x-text="item.qty"></span>
                                    <button @click="item.qty++" class="text-xs font-bold px-1 hover:text-[#C36930] cursor-pointer">+</button>
                                </div>
                            </div>
                        </template>
                        <template x-if="totalCartItems === 0">
                            <p class="text-xs text-center text-[#8C827A] py-10">Keranjang masih kosong.</p>
                        </template>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#E5DEC9] space-y-3">
                    <div class="flex justify-between text-sm font-bold">
                        <span>Total Pesanan:</span>
                        <span class="text-[#C36930]" x-text="formatRupiah(totalPrice)"></span>
                    </div>
                    <a href="{{ route('checkout') }}" class="block w-full bg-[#3D2C24] text-white text-center py-3 rounded-2xl text-xs font-semibold hover:bg-black transition shadow-xs">
                        Lanjut ke Checkout →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <main class="max-w-7xl mx-auto px-6 md:px-12 py-6 w-full space-y-8">
        
        <!-- BREADCRUMB & GALERI FOTO KAFE (Sesuai Figma Presisi) -->
        <div class="space-y-4">
            <!-- Breadcrumb & Tombol Simpan/Bagikan Sejajar -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-2 text-xs text-[#8C827A]">
                    <a href="{{ route('explore') }}" class="hover:underline">Jelajahi Cafe</a>
                    <span>›</span>
                    <span class="text-[#3D2C24] font-medium">Grind Now Coffe Bar</span>
                </div>
                
                <div class="flex gap-2">
                    <button class="bg-white border border-[#E5DEC9] px-4 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5 shadow-xs hover:bg-[#F4EFE6] cursor-pointer">
                        <i data-lucide="bookmark" class="w-3.5 h-3.5"></i> Simpan
                    </button>
                    <button class="bg-white border border-[#E5DEC9] px-4 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5 shadow-xs hover:bg-[#F4EFE6] cursor-pointer">
                        <i data-lucide="share-2" class="w-3.5 h-3.5"></i> Bagikan
                    </button>
                </div>
            </div>
            
            <!-- Grid Galeri Foto Utama -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <!-- Foto Utama Besar di Kiri -->
                <div class="lg:col-span-8 h-72 md:h-[390px] rounded-3xl overflow-hidden relative shadow-sm bg-[#EAE2D5]">
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80" class="w-full h-full object-cover">
                    <!-- Indikator 01/06 di Kiri Bawah -->
                    <div class="absolute bottom-4 left-4 bg-black/70 backdrop-blur-xs text-white text-xs px-3.5 py-1.5 rounded-full flex items-center gap-2">
                        <span>01 / 06</span>
                        <div class="flex gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/50"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/50"></span>
                        </div>
                    </div>
                </div>

                <!-- Dua Foto Kecil di Kanan -->
                <div class="lg:col-span-4 flex flex-col gap-4">
                    <div class="h-34 md:h-[187px] rounded-3xl overflow-hidden bg-[#EAE2D5] border border-[#E5DEC9]">
                        <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                    </div>
                    <div class="h-34 md:h-[187px] rounded-3xl overflow-hidden bg-[#EAE2D5] border border-[#E5DEC9] relative">
                        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                        <!-- Tombol Panah Navigasi di Pojok Kanan Bawah Foto -->
                        <div class="absolute bottom-3 right-3 flex gap-1.5">
                            <button class="w-7 h-7 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-xs cursor-pointer text-xs font-bold">‹</button>
                            <button class="w-7 h-7 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-xs cursor-pointer text-xs font-bold">›</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HEADER JUDUL KAFE -->
            <div class="pt-2 space-y-2">
                <p class="text-[10px] font-bold text-[#8C827A] uppercase tracking-wider">KOPI SPESIALTI • ROASTERY</p>
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h1 class="text-2xl md:text-3xl font-serif-title font-bold">Grind Now Coffe Bar</h1>
                    
                    <div class="flex items-center gap-3">
                        <span class="bg-[#EAF4E9] text-[#2E7D32] text-xs font-semibold px-3.5 py-1.5 rounded-full flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Buka • 09:30 - 24:00 WIB
                        </span>
                        <a href="{{ route('checkout') }}" class="bg-[#3D2C24] text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-black transition shadow-xs flex items-center gap-1.5">
                            <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i> Pesanan
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-3 text-xs text-[#8C827A] pt-1">
                    <span class="bg-[#FEF3D6] text-[#B45309] font-bold px-2.5 py-0.5 rounded-md">★ 4.9</span>
                    <span>120+ ulasan</span>
                    <span>•</span>
                    <span>$$</span>
                    <span>•</span>
                    <span>Batam Kota</span>
                </div>

                <p class="text-xs text-[#6E645E] max-w-3xl pt-1">
                    Di era kopi serba cepat, Grind Now memilih arah sebaliknya: slow bar satu seduhan, satu perhatian penuh.
                </p>
            </div>
        </div>

        <!-- GRID UTAMA (KIRI: KENALI KOPIMU & KATALOG, KANAN: PETA & FASILITAS) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start pt-4">
            
            <!-- SISI KIRI (8 Kolom) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- KARTU "KENALI KOPIMU" -->
                <div class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 font-bold text-sm">
                        <i data-lucide="coffee" class="w-4 h-4 text-[#C36930]"></i>
                        <span>Kenali kopimu</span>
                    </div>

                    <div class="grid grid-cols-1 gap-2.5 text-xs">
                        <div class="flex justify-between border-b border-[#E5DEC9] pb-2">
                            <span class="text-[#8C827A]">Jenis Beans</span>
                            <span class="font-semibold">Single Origin / Blend</span>
                        </div>
                        <div class="flex justify-between border-b border-[#E5DEC9] pb-2">
                            <span class="text-[#8C827A]">Asal Beans</span>
                            <span class="font-semibold">Arabica</span>
                        </div>
                        <div class="flex justify-between border-b border-[#E5DEC9] pb-2">
                            <span class="text-[#8C827A]">Tingkat Roasting</span>
                            <span class="font-semibold">Light, Medium, Dark</span>
                        </div>
                        <div class="flex justify-between border-b border-[#E5DEC9] pb-2">
                            <span class="text-[#8C827A]">Metode seduh</span>
                            <span class="font-semibold">V60, Aeropress, Japanese style, espresso, dan cold brew</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-[#8C827A] italic pt-1">
                        Cita rasa: ringan, fruity, tea-like, seimbang.
                    </p>
                </div>

                <!-- CONTENT TABS (Katalog Menu | Review & Rating | Fasilitas) -->
                <div class="flex bg-[#EEE8DC] p-1.5 rounded-2xl border border-[#E0D7C3] gap-2">
                    <button @click="activeTab = 'menu'" :class="activeTab === 'menu' ? 'bg-white text-[#3D2C24] shadow-xs border border-[#E5DEC9]' : 'text-[#8C827A] hover:text-[#3D2C24]'" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer">
                        Katalog Menu
                    </button>
                    <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'bg-white text-[#3D2C24] shadow-xs border border-[#E5DEC9]' : 'text-[#8C827A] hover:text-[#3D2C24]'" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer">
                        Review & Rating
                    </button>
                    <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'bg-white text-[#3D2C24] shadow-xs border border-[#E5DEC9]' : 'text-[#8C827A] hover:text-[#3D2C24]'" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer">
                        Fasilitas
                    </button>
                </div>

                <!-- TAB 1: KATALOG MENU & DAPUR -->
                <div x-show="activeTab === 'menu'" class="space-y-6">
                    <div>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                            <div>
                                <h3 class="font-serif-title font-bold text-lg">Dari bar kopi & dapur</h3>
                                <p class="text-xs text-[#8C827A]">Pilihan favorit untuk menemani ceritamu.</p>
                            </div>
                            
                            <!-- Filter Kategori Chip -->
                            <div class="flex flex-wrap gap-1.5 text-xs">
                                <button @click="activeCategory = 'semua'" :class="activeCategory === 'semua' ? 'bg-[#3D2C24] text-white' : 'bg-white border border-[#E5DEC9] text-[#6E645E]'" class="px-3.5 py-1 rounded-full font-medium transition cursor-pointer">Semua</button>
                                <button @click="activeCategory = 'kopi'" :class="activeCategory === 'kopi' ? 'bg-[#3D2C24] text-white' : 'bg-white border border-[#E5DEC9] text-[#6E645E]'" class="px-3.5 py-1 rounded-full font-medium transition cursor-pointer">Kopi</button>
                                <button @click="activeCategory = 'makanan'" :class="activeCategory === 'makanan' ? 'bg-[#3D2C24] text-white' : 'bg-white border border-[#E5DEC9] text-[#6E645E]'" class="px-3.5 py-1 rounded-full font-medium transition cursor-pointer">Makanan</button>
                            </div>
                        </div>

                        <!-- Grid Menu Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <template x-for="item in filteredMenu" :key="item.id">
                                <div class="bg-white border border-[#E5DEC9] rounded-2xl p-3 shadow-xs flex flex-col justify-between">
                                    <div>
                                        <div class="h-32 rounded-xl overflow-hidden bg-[#EAE2D5] mb-3">
                                            <img :src="item.img" class="w-full h-full object-cover">
                                        </div>
                                        <h4 class="font-bold text-xs" x-text="item.name"></h4>
                                        <p class="text-[10px] text-[#8C827A] mt-0.5 line-clamp-2" x-text="item.desc"></p>
                                    </div>
                                    <div class="flex justify-between items-center mt-3 pt-2 border-t border-[#F4EFE6]">
                                        <span class="text-xs font-bold text-[#C36930]" x-text="formatRupiah(item.price)"></span>
                                        
                                        <!-- Tombol Stepper - 0 + Interaktif -->
                                        <div class="flex items-center gap-2 bg-[#F8F5F0] border border-[#E5DEC9] px-2 py-1 rounded-xl">
                                            <button @click="if(item.qty > 0) item.qty--" class="text-xs font-bold px-1 text-[#8C827A] hover:text-[#3D2C24] cursor-pointer">-</button>
                                            <span class="text-xs font-bold w-4 text-center" x-text="item.qty"></span>
                                            <button @click="item.qty++" class="text-xs font-bold px-1 text-[#8C827A] hover:text-[#3D2C24] cursor-pointer">+</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: REVIEW & RATING -->
                <div x-show="activeTab === 'reviews'" class="space-y-4" style="display: none;">
                    <div class="bg-white border border-[#E5DEC9] p-6 rounded-3xl space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-serif-title font-bold text-lg">Review & Rating</h3>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-3xl font-bold">4.9</span>
                                    <div>
                                        <div class="text-amber-500 text-sm">★★★★★</div>
                                        <p class="text-xs text-[#8C827A]">Dari 120+ ulasan pecinta kopi</p>
                                    </div>
                                </div>
                            </div>
                            <button class="bg-[#3D2C24] text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-black transition">Tulis ulasan</button>
                        </div>

                        <div class="pt-4 border-t border-[#E5DEC9] space-y-2">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-[#EAE2D5] flex items-center justify-center font-bold text-xs">NP</div>
                                    <div>
                                        <h5 class="text-xs font-bold">Nadia P.</h5>
                                        <p class="text-[10px] text-[#8C827A]">4 Oktober 2026</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-[#3D2C24]">★ 5.0</span>
                            </div>
                            <p class="text-xs text-[#6E645E]">Kopi susunya pas, tidak terlalu manis. Suka suasana hangatnya untuk ngobrol sore. Croissant-nya juga renyah!</p>
                        </div>

                        <div class="pt-4 border-t border-[#E5DEC9] space-y-2">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-[#EAE2D5] flex items-center justify-center font-bold text-xs">RA</div>
                                    <div>
                                        <h5 class="text-xs font-bold">Raika A.</h5>
                                        <p class="text-[10px] text-[#8C827A]">2 Oktober 2026</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-[#3D2C24]">★ 5.0</span>
                            </div>
                            <p class="text-xs text-[#6E645E]">V60 Flores-nya wangi dan bersih. Barista membantu pilih biji sesuai selera. Ada stopkontak untuk kerja santai.</p>
                        </div>

                        <div class="pt-2">
                            <a href="#" class="text-xs font-bold text-[#3D2C24] hover:underline">Lihat semua 120 ulasan →</a>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: FASILITAS -->
                <div x-show="activeTab === 'info'" class="space-y-4" style="display: none;">
                    <div class="bg-white border border-[#E5DEC9] p-6 rounded-3xl space-y-3 text-xs">
                        <h3 class="font-serif-title font-bold text-lg mb-2">Fasilitas Lengkap</h3>
                        <p><strong>Alamat:</strong> Permata Regency, Blok DD No. 9, Baloi Indah, Batam</p>
                        <p><strong>Jam Operasional:</strong> Setiap hari • 09:30 - 24:00 WIB</p>
                        <p><strong>Fasilitas:</strong> Wi-Fi gratis, Ruangan ber-AC, Parkir tersedia, Nyaman untuk kerja.</p>
                    </div>
                </div>

            </div>

            <!-- SISI KANAN (4 Kolom: Peta & Lokasi, Jam Operasional, Fasilitas) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Peta & Lokasi Card -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-5 shadow-xs space-y-4">
                    <h3 class="font-bold text-sm">Peta & Lokasi</h3>
                    <div class="h-44 rounded-2xl overflow-hidden bg-[#EAE2D5] border border-[#E5DEC9] relative">
                        <img src="https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                            <div class="bg-[#3D2C24] text-white p-2 rounded-full shadow-lg">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-xs">Grind Now Coffe Bar</h4>
                        <p class="text-[11px] text-[#8C827A] mt-0.5">Permata Regency, Blok DD No. 9, Baloi Indah, Batam</p>
                    </div>

                    <a href="https://maps.google.com" target="_blank" class="block w-full bg-[#3D2C24] text-white text-center py-2.5 rounded-xl text-xs font-semibold hover:bg-black transition shadow-xs flex items-center justify-center gap-2">
                        <i data-lucide="navigation" class="w-3.5 h-3.5"></i> Petunjuk Arah
                    </a>

                    <div class="pt-3 border-t border-[#F4EFE6] space-y-1">
                        <div class="flex items-center gap-1.5 text-xs font-bold">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-[#8C827A]"></i> Jam operasional
                        </div>
                        <p class="text-[11px] text-[#8C827A]">Setiap hari • 09:30 - 24:00 WIB</p>
                    </div>
                </div>

                <!-- Fasilitas Grid -->
                <div class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-3xl p-5 shadow-xs space-y-3">
                    <h3 class="font-bold text-sm">Fasilitas</h3>
                    <div class="grid grid-cols-2 gap-2.5 text-xs text-[#3D2C24]">
                        <div class="flex items-center gap-2 bg-white p-2.5 rounded-2xl border border-[#E5DEC9]">
                            <i data-lucide="wifi" class="w-4 h-4 text-[#8C827A]"></i> Wi-Fi gratis
                        </div>
                        <div class="flex items-center gap-2 bg-white p-2.5 rounded-2xl border border-[#E5DEC9]">
                            <i data-lucide="zap" class="w-4 h-4 text-[#8C827A]"></i> Stopkontak
                        </div>
                        <div class="flex items-center gap-2 bg-white p-2.5 rounded-2xl border border-[#E5DEC9]">
                            <i data-lucide="wind" class="w-4 h-4 text-[#8C827A]"></i> Ruangan ber-AC
                        </div>
                        <div class="flex items-center gap-2 bg-white p-2.5 rounded-2xl border border-[#E5DEC9]">
                            <i data-lucide="sun" class="w-4 h-4 text-[#8C827A]"></i> Area luar
                        </div>
                        <div class="flex items-center gap-2 bg-white p-2.5 rounded-2xl border border-[#E5DEC9]">
                            <i data-lucide="car" class="w-4 h-4 text-[#8C827A]"></i> Parkir tersedia
                        </div>
                        <div class="flex items-center gap-2 bg-white p-2.5 rounded-2xl border border-[#E5DEC9]">
                            <i data-lucide="briefcase" class="w-4 h-4 text-[#8C827A]"></i> Nyaman untuk kerja
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- SECTION: CAFE SERUPA YANG MUNGKIN ANDA SUKA -->
        <section class="space-y-4 pt-8 border-t border-[#E5DEC9]">
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="text-xl font-serif-title font-bold">Cafe Serupa yang Mungkin Anda Suka</h2>
                    <p class="text-xs text-[#8C827A]">Tempat baru, cerita kopi berikutnya.</p>
                </div>
                <div class="flex gap-1.5">
                    <button class="w-8 h-8 rounded-full bg-white border border-[#E5DEC9] flex items-center justify-center text-[#3D2C24] hover:bg-[#F4EFE6] cursor-pointer">‹</button>
                    <button class="w-8 h-8 rounded-full bg-white border border-[#E5DEC9] flex items-center justify-center text-[#3D2C24] hover:bg-[#F4EFE6]">›</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Rekomendasi 1 -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-3.5 shadow-xs space-y-3" x-data="{ bookmarked: false }">
                    <div class="h-44 rounded-2xl overflow-hidden relative bg-[#EAE2D5]">
                        <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                        <button @click="bookmarked = !bookmarked" :class="bookmarked ? 'bg-[#3D2C24] text-white' : 'bg-white/90 text-[#3D2C24] hover:bg-white'" class="absolute top-2.5 right-2.5 p-2 rounded-full transition shadow-xs cursor-pointer">
                            <i data-lucide="bookmark" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-sm">Temu Kopi</h3>
                        <span class="text-xs font-bold text-[#3D2C24]">★ 4.8</span>
                    </div>
                    <p class="text-[11px] text-[#8C827A]">Batam Kota • $$</p>
                    <div>
                        <span class="bg-[#F8F5F0] border border-[#E5DEC9] text-[#6E645E] text-[10px] px-2.5 py-1 rounded-lg font-medium">Kopi spesialti</span>
                    </div>
                </div>

                <!-- Rekomendasi 2 -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-3.5 shadow-xs space-y-3" x-data="{ bookmarked: true }">
                    <div class="h-44 rounded-2xl overflow-hidden relative bg-[#EAE2D5]">
                        <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                        <button @click="bookmarked = !bookmarked" :class="bookmarked ? 'bg-[#3D2C24] text-white' : 'bg-white/90 text-[#3D2C24] hover:bg-white'" class="absolute top-2.5 right-2.5 p-2 rounded-full transition shadow-xs cursor-pointer">
                            <i data-lucide="bookmark" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-sm">Ruang Seduh</h3>
                        <span class="text-xs font-bold text-[#3D2C24]">★ 4.7</span>
                    </div>
                    <p class="text-[11px] text-[#8C827A]">Lubuk Baja • $$</p>
                    <div>
                        <span class="bg-[#F8F5F0] border border-[#E5DEC9] text-[#6E645E] text-[10px] px-2.5 py-1 rounded-lg font-medium">Nyaman untuk kerja</span>
                    </div>
                </div>

                <!-- Rekomendasi 3 -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-3.5 shadow-xs space-y-3" x-data="{ bookmarked: false }">
                    <div class="h-44 rounded-2xl overflow-hidden relative bg-[#EAE2D5]">
                        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                        <button @click="bookmarked = !bookmarked" :class="bookmarked ? 'bg-[#3D2C24] text-white' : 'bg-white/90 text-[#3D2C24] hover:bg-white'" class="absolute top-2.5 right-2.5 p-2 rounded-full transition shadow-xs cursor-pointer">
                            <i data-lucide="bookmark" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-sm">Senja Coffee House</h3>
                        <span class="text-xs font-bold text-[#3D2C24]">★ 4.8</span>
                    </div>
                    <p class="text-[11px] text-[#8C827A]">Sekupang • $$</p>
                    <div>
                        <span class="bg-[#F8F5F0] border border-[#E5DEC9] text-[#6E645E] text-[10px] px-2.5 py-1 rounded-lg font-medium">Suasana santai</span>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#F8F5F0] border-t border-[#E5DEC9] py-8 px-6 md:px-12 mt-16 flex flex-col md:flex-row justify-between items-center text-xs text-[#8C827A] gap-4">
        <p>© 2026 Coffenfo • Diracik untuk pecinta kopi</p>
        <div class="flex gap-6">
            <a href="#" class="hover:underline">Kebijakan Privasi</a>
            <a href="#" class="hover:underline">Butuh bantuan?</a>
        </div>
    </footer>

    <!-- Init Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>