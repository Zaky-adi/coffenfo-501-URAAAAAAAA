<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Temukan & Review Cafe Favoritmu di Batam</title>
    
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CDN Alpine.js untuk interaktivitas -->
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
<body class="text-[#3D2C24] min-h-screen flex flex-col justify-between">

    <!-- NAVBAR -->
    <header class="bg-[#F4EFE6]/90 backdrop-blur-md border-b border-[#E5DEC9] py-4 px-6 md:px-12 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="bg-[#3D2C24] text-[#EEF2DA] p-1.5 rounded-lg font-bold text-base w-7 h-7 flex items-center justify-center">c</div>
                <span class="text-xl font-bold tracking-tight">Coffenfo</span>
            </div>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-xs font-semibold text-[#6E645E]">
                <a href="#" class="hover:text-[#3D2C24] transition">Jelajahi cafe</a>
                <a href="#" class="hover:text-[#3D2C24] transition">Cafe tersimpan</a>
                <a href="#" class="hover:text-[#3D2C24] transition">Kemitraan</a>
                <a href="{{ route('admin.orders') }}" class="hover:text-[#3D2C24] transition">Kelola Pesanan</a>
                <a href="{{ route('admin.moderation') }}" class="hover:text-[#3D2C24] transition">Moderasi Admin</a>
            </nav>

            <!-- Tombol Masuk yang diarahkan ke halaman login -->
            <div>
                <a href="{{ route('login') }}" class="bg-[#3D2C24] text-white px-5 py-2 rounded-xl text-xs font-semibold hover:bg-black transition shadow-xs">
                    Masuk
                </a>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="max-w-7xl mx-auto px-6 md:px-12 py-10 w-full space-y-16">
        
        <!-- 1. HERO SECTION -->
        <section class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-3xl p-8 md:p-14 relative overflow-hidden shadow-xs">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Kiri: Teks & Tag -->
                <div class="lg:col-span-7 space-y-6 z-10">
                    <p class="text-[11px] font-bold text-[#8C827A] uppercase tracking-wider">Dari Batam, untuk pecinta kopi</p>
                    
                    <h1 class="text-3xl md:text-5xl font-serif-title font-bold leading-tight">
                        Temukan & review cafe favoritmu di Batam
                    </h1>
                    
                    <p class="text-xs md:text-sm text-[#6E645E] leading-relaxed max-w-lg">
                        Dari tegukan pertama hingga tempat langganan. Cari cafe lewat jenis beans, metode seduh, dan suasana, lalu pesan dan tulis ulasanmu.
                    </p>

                    <!-- Pill Tags Kategori -->
                    <div class="flex flex-wrap items-center gap-2 pt-2">
                        <span class="bg-white border border-[#E5DEC9] px-3.5 py-1.5 rounded-full text-xs font-medium text-[#3D2C24] shadow-xs">Single origin</span>
                        <span class="bg-white border border-[#E5DEC9] px-3.5 py-1.5 rounded-full text-xs font-medium text-[#3D2C24] shadow-xs">V60</span>
                        <span class="bg-white border border-[#E5DEC9] px-3.5 py-1.5 rounded-full text-xs font-medium text-[#3D2C24] shadow-xs">Cold brew</span>
                        <span class="bg-white border border-[#E5DEC9] px-3.5 py-1.5 rounded-full text-xs font-medium text-[#3D2C24] shadow-xs">Nyaman untuk kerja</span>
                        <span class="bg-white border border-[#E5DEC9] px-3.5 py-1.5 rounded-full text-xs font-medium text-[#3D2C24] shadow-xs">Ada stopkontak</span>
                    </div>
                </div>

                <!-- Kanan: Ilustrasi & Badge -->
                <div class="lg:col-span-5 relative flex justify-center items-center">
                    <div class="w-72 h-72 md:w-80 md:h-80 bg-[#EFE8DC] rounded-full flex items-center justify-center relative shadow-inner">
                        <div class="text-center space-y-2">
                            <div class="text-5xl">☕ 🏠</div>
                            <span class="text-xs font-bold text-[#8C827A]">Anchor Cafe & Roastery</span>
                        </div>

                        <!-- Badge Rating -->
                        <div class="absolute -top-4 left-4 bg-white border border-[#E5DEC9] px-3 py-1.5 rounded-2xl shadow-md text-xs font-bold flex items-center gap-1.5">
                            <span class="text-amber-600">★ 4.9</span>
                            <span class="text-[10px] text-[#8C827A] font-normal">120+ ulasan</span>
                        </div>

                        <!-- Badge Jam Buka -->
                        <div class="absolute top-8 -right-4 bg-white border border-[#E5DEC9] px-3.5 py-1.5 rounded-2xl shadow-md text-xs font-medium flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Jam Buka <span class="text-[#8C827A]">09:30–24:00 WIB</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. CARA KERJA COFFENFO -->
        <section>
            <div class="mb-6">
                <h2 class="text-lg font-bold mb-1">Cara kerja Coffenfo</h2>
                <p class="text-xs text-[#8C827A]">Tiga langkah mudah untuk memesan dan menikmati kafe.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-2xl p-6 shadow-xs">
                    <span class="text-[10px] font-bold text-[#8C827A] uppercase tracking-wider">Langkah 1</span>
                    <h3 class="font-bold text-sm mt-1 mb-2">Cari kafe</h3>
                    <p class="text-xs text-[#6E645E]">Temukan kafe terdekat, baca ulasan, dan lihat menu spesial pilihan mereka.</p>
                </div>
                <div class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-2xl p-6 shadow-xs">
                    <span class="text-[10px] font-bold text-[#8C827A] uppercase tracking-wider">Langkah 2</span>
                    <h3 class="font-bold text-sm mt-1 mb-2">Pesan & konfirmasi</h3>
                    <p class="text-xs text-[#6E645E]">Pilih menu favoritmu, tentukan dine-in atau takeaway, lalu lakukan pembayaran aman.</p>
                </div>
                <div class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-2xl p-6 shadow-xs">
                    <span class="text-[10px] font-bold text-[#8C827A] uppercase tracking-wider">Langkah 3</span>
                    <h3 class="font-bold text-sm mt-1 mb-2">Tiba & nikmati</h3>
                    <p class="text-xs text-[#6E645E]">Barista akan menyiapkan pesanan segar dan menyajikannya langsung ke mejamu.</p>
                </div>
            </div>
        </section>

        <!-- 3. KAFE PILIHAN MINGGU INI (Sesuai Desain Figma) -->
        <section>
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-xl md:text-2xl font-serif-title font-bold mb-1">Kafe pilihan minggu ini</h2>
                    <p class="text-xs text-[#8C827A]">Dikurasi dari ulasan pelanggan di Batam.</p>
                </div>
                <a href="#" class="bg-white border border-[#E5DEC9] px-4 py-2 rounded-xl text-xs font-semibold text-[#3D2C24] hover:bg-[#F2EBE1] transition shadow-xs">
                    Lihat semua cafe
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Kafe 1: Grind Now Coffee Bar -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-4 shadow-xs flex flex-col justify-between">
                    <div>
                        <!-- Foto Kafe -->
                        <div class="h-48 rounded-2xl mb-4 overflow-hidden bg-[#EAE2D5]">
                            <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=600&q=80" alt="Grind Now Coffee Bar" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Nama & Lokasi -->
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-sm text-[#3D2C24]">Grind Now Coffee Bar</h3>
                            <span class="text-xs font-semibold text-[#3D2C24]">★ 4.9</span>
                        </div>
                        <p class="text-[11px] text-[#8C827A] mb-3">Batam Centre • $$</p>
                    </div>

                    <!-- Tag Kategori -->
                    <div>
                        <span class="inline-block bg-[#F8F5F0] border border-[#E5DEC9] px-3 py-1 rounded-lg text-[11px] font-medium text-[#6E645E]">
                            Kopi spesialti
                        </span>
                    </div>
                </div>

                <!-- Kafe 2: Meilleur Cafe & Patisserie -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-4 shadow-xs flex flex-col justify-between">
                    <div>
                        <!-- Foto Kafe -->
                        <div class="h-48 rounded-2xl mb-4 overflow-hidden bg-[#EAE2D5]">
                            <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=600&q=80" alt="Meilleur Cafe & Patisserie" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Nama & Lokasi -->
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-sm text-[#3D2C24]">Meilleur Cafe & Patisserie</h3>
                            <span class="text-xs font-semibold text-[#3D2C24]">★ 4.8</span>
                        </div>
                        <p class="text-[11px] text-[#8C827A] mb-3">Batam Centre • $$</p>
                    </div>

                    <!-- Tag Kategori -->
                    <div>
                        <span class="inline-block bg-[#F8F5F0] border border-[#E5DEC9] px-3 py-1 rounded-lg text-[11px] font-medium text-[#6E645E]">
                            Specialty Coffee & Fresh Bakery
                        </span>
                    </div>
                </div>

                <!-- Kafe 3: Mula Patisserie -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-4 shadow-xs flex flex-col justify-between">
                    <div>
                        <!-- Foto Kafe -->
                        <div class="h-48 rounded-2xl mb-4 overflow-hidden bg-[#EAE2D5]">
                            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80" alt="Mula Patisserie" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Nama & Lokasi -->
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-sm text-[#3D2C24]">Mula Patisserie</h3>
                            <span class="text-xs font-semibold text-[#3D2C24]">★ 4.8</span>
                        </div>
                        <p class="text-[11px] text-[#8C827A] mb-3">Batam Centre • $$</p>
                    </div>

                    <!-- Tag Kategori -->
                    <div>
                        <span class="inline-block bg-[#F8F5F0] border border-[#E5DEC9] px-3 py-1 rounded-lg text-[11px] font-medium text-[#6E645E]">
                            Artisanal French Crepes, Cakes, & Specialty Coffee
                        </span>
                    </div>
                </div>

            </div>
        </section>

        <!-- 4. KATA PECINTA KOPI -->
        <section>
            <div class="mb-6">
                <h2 class="text-lg font-bold">Kata pecinta kopi</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-3">
                    <p class="text-xs text-[#6E645E] italic">"Kafe di sini mantap-mantap. Suka suasana kerjanya yang tenang dan kopinya enak."</p>
                    <span class="text-[11px] font-bold text-[#3D2C24] block">— Rian P. • WFC</span>
                </div>
                <div class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-3">
                    <p class="text-xs text-[#6E645E] italic">"Fitur chekout-nya sangat membantu, tinggal pesan dari meja dan makanan langsung diantar."</p>
                    <span class="text-[11px] font-bold text-[#3D2C24] block">— Maya S. • Foodie</span>
                </div>
            </div>
        </section>

        <!-- 5. PUNYA KAFE DI BATAM? JADI MITRA COFFENFO (Formulir) -->
        <section class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-3xl p-8 md:p-12 shadow-xs">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Kiri: Info Kemitraan -->
                <div class="lg:col-span-6 space-y-4">
                    <h2 class="text-2xl font-serif-title font-bold">Punya kafe di Batam? Jadi mitra Coffenfo</h2>
                    <p class="text-xs text-[#6E645E] leading-relaxed">
                        Perkenalkan kafe Anda pada ribuan pencinta kopi di Batam, tingkatkan kunjungan harian, dan kelola pesanan langsung dari satu platform digital.
                    </p>
                    <ul class="space-y-2 text-xs text-[#6E645E]">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-700"></i>
                            <span><strong>Profil kafe & katalog menu</strong>: Tampilkan foto, suasana, dan menu andalan.</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-700"></i>
                            <span><strong>Pesan & konfirmasi meja</strong>: Terima pesanan dine-in & takeaway dari pelanggan.</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-700"></i>
                            <span><strong>Review & rating pelanggan</strong>: Bangun reputasi kafe Anda secara transparan.</span>
                        </li>
                    </ul>
                </div>

                <!-- Kanan: Form Pendaftaran Mitra -->
                <div class="lg:col-span-6 bg-white border border-[#E5DEC9] rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="font-bold text-sm">Ajukan kemitraan</h3>
                    
                    <div class="space-y-3 text-xs">
                        <div>
                            <label class="block font-semibold mb-1 text-[#6E645E]">Nama kafe</label>
                            <input type="text" placeholder="Contoh: Kopi Batam Centre" class="w-full bg-[#F8F5F0] border border-[#E5DEC9] px-3.5 py-2.5 rounded-xl focus:outline-none focus:border-[#3D2C24]">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-[#6E645E]">Nama pemilik</label>
                            <input type="text" placeholder="Nama lengkap Anda" class="w-full bg-[#F8F5F0] border border-[#E5DEC9] px-3.5 py-2.5 rounded-xl focus:outline-none focus:border-[#3D2C24]">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-[#6E645E]">Nomor WhatsApp</label>
                            <input type="text" placeholder="0812XXXXXXXX" class="w-full bg-[#F8F5F0] border border-[#E5DEC9] px-3.5 py-2.5 rounded-xl focus:outline-none focus:border-[#3D2C24]">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1 text-[#6E645E]">Wilayah Area</label>
                            <select class="w-full bg-[#F8F5F0] border border-[#E5DEC9] px-3.5 py-2.5 rounded-xl focus:outline-none focus:border-[#3D2C24]">
                                <option>Pilih Area Batam</option>
                                <option>Batam Centre</option>
                                <option>Nagoya / Lubuk Baja</option>
                                <option>Sei Beduk / Batu Aji</option>
                                <option>Sekupang</option>
                            </select>
                        </div>
                        
                        <button class="w-full bg-[#3D2C24] text-white py-3 rounded-xl font-semibold hover:bg-black transition cursor-pointer mt-2">
                            Ajukan kemitraan
                        </button>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#F4EFE6] border-t border-[#E5DEC9] py-6 px-6 md:px-12 mt-16 flex flex-col md:flex-row justify-between items-center text-xs text-[#8C827A] gap-4">
        <p>© 2026 Coffenfo • Setiap cangkir, sebuah cerita.</p>
        <div class="flex gap-4">
            <a href="{{ route('admin.moderation') }}" class="hover:text-[#3D2C24]">Admin Moderasi</a>
            <a href="{{ route('admin.orders') }}" class="hover:text-[#3D2C24]">Admin Orders</a>
            <a href="{{ route('checkout') }}" class="hover:text-[#3D2C24]">Checkout Page</a>
        </div>
    </footer>

    <!-- Init Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>