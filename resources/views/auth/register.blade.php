<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Halaman Daftar</title>
    
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    
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
<body class="min-h-screen flex flex-col justify-between items-center p-4 md:p-8 text-[#3D2C24]">

    <!-- Container Utama -->
    <div class="w-full max-w-5xl my-auto">
        <div class="bg-[#FCF9F2] rounded-3xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12 min-h-[620px]">
            
            <!-- SISI KIRI: Branding & Ilustrasi (Sama seperti Login) -->
            <div class="md:col-span-5 bg-[#EEF2DA] p-8 md:p-10 flex flex-col justify-between relative">
                <div>
                    <!-- Logo -->
                    <div class="flex items-center gap-2 mb-12">
                        <div class="bg-[#3D2C24] text-[#EEF2DA] p-1.5 rounded-lg flex items-center justify-center font-bold text-lg w-8 h-8">
                            c
                        </div>
                        <span class="text-xl font-bold tracking-tight text-[#3D2C24]">coffenfo</span>
                    </div>

                    <!-- Location Tag -->
                    <div class="flex items-center gap-1.5 text-xs tracking-wider uppercase text-[#6E645E] font-medium mb-3">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        <span>Dari Batam, Untuk Pecinta Kopi</span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl font-serif-title text-[#3D2C24] leading-tight mb-4">
                        Temukan & Review Cafe Favoritmu di Batam
                    </h1>

                    <!-- Description -->
                    <p class="text-sm text-[#6E645E] leading-relaxed">
                        Dari tegukan pertama hingga tempat langganan. Temukan cerita kopi berikutnya bersama Coffenfo.
                    </p>
                </div>

                <!-- Coffee Shop Illustration Container -->
                <div class="my-8 relative flex justify-center items-center">
                    <div class="w-56 h-56 md:w-64 md:h-64 rounded-full bg-[#E3D0BE] absolute opacity-70"></div>
                    
                    <div class="relative z-10 w-48 bg-white/40 backdrop-blur-xs p-4 rounded-xl border border-[#3D2C24]/20 text-center shadow-sm">
                        <div class="border-2 border-[#3D2C24] rounded-md p-3 bg-[#FFFDF9]">
                            <div class="bg-[#3D2C24] text-white text-[10px] font-bold py-0.5 tracking-wider uppercase rounded-xs mb-2">
                                COFFENFO
                            </div>
                            <div class="flex justify-between border-b-2 border-[#3D2C24] pb-1 mb-2">
                                <span class="w-3 h-3 border-b-2 border-r-2 border-[#3D2C24] rounded-b-full"></span>
                                <span class="w-3 h-3 border-b-2 border-r-2 border-[#3D2C24] rounded-b-full"></span>
                                <span class="w-3 h-3 border-b-2 border-r-2 border-[#3D2C24] rounded-b-full"></span>
                                <span class="w-3 h-3 border-b-2 border-r-2 border-[#3D2C24] rounded-b-full"></span>
                                <span class="w-3 h-3 border-b-2 border-r-2 border-[#3D2C24] rounded-b-full"></span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 h-16 items-end">
                                <div class="border-2 border-[#3D2C24] h-12 rounded flex flex-col items-center justify-center bg-amber-50/50">
                                    <i data-lucide="coffee" class="w-5 h-5 text-[#3D2C24]"></i>
                                </div>
                                <div class="border-2 border-[#3D2C24] h-14 rounded-t flex items-center justify-end pr-1 bg-amber-100/30">
                                    <div class="w-1 h-1 bg-[#3D2C24] rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Subtext -->
                <div class="flex items-center gap-2 text-xs text-[#6E645E] font-medium">
                    <i data-lucide="heart" class="w-3.5 h-3.5 text-[#3D2C24]"></i>
                    <span>Tempat kopi bertemu cerita.</span>
                </div>
            </div>

            <!-- SISI KANAN: Form Register Section -->
            <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-center bg-[#FFFDF9]">
                
                <div class="max-w-md mx-auto w-full">
                    <!-- Tab Navigation Switcher -->
                    <div class="bg-[#F2EBE1] p-1 rounded-xl flex mb-6">
                        <a href="{{ route('login') }}" class="flex-1 py-2 text-center text-sm font-medium text-[#8C827A] hover:text-[#3D2C24] transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="flex-1 py-2 text-center text-sm font-semibold rounded-lg bg-white shadow-xs text-[#3D2C24] flex items-center justify-center gap-1.5 transition">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Daftar
                        </a>
                    </div>

                    <!-- Welcome Heading -->
                    <div class="mb-5">
                        <h2 class="text-2xl md:text-3xl font-serif-title text-[#3D2C24] mb-1">
                            Cerita kopimu dimulai di sini.
                        </h2>
                        <p class="text-xs text-[#8C827A]">
                            Buat akun dan temukan cafe yang sesuai seleramu.
                        </p>
                    </div>

                    <!-- Form Register -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-[#3D2C24] mb-1">Nama Lengkap</label>
                            <input 
                                type="text" 
                                name="name" 
                                placeholder="Nama lengkapmu"
                                required 
                                class="w-full px-4 py-2.5 rounded-xl border border-[#E5DEC9] bg-white text-sm text-[#3D2C24] placeholder-[#B5ADA4] focus:outline-none focus:ring-2 focus:ring-[#3D2C24]/20 focus:border-[#3D2C24] transition"
                            >
                        </div>

                        <!-- Email / Username -->
                        <div>
                            <label class="block text-xs font-bold text-[#3D2C24] mb-1">Email / Username</label>
                            <input 
                                type="text" 
                                name="email" 
                                placeholder="Email atau username kamu"
                                required 
                                class="w-full px-4 py-2.5 rounded-xl border border-[#E5DEC9] bg-white text-sm text-[#3D2C24] placeholder-[#B5ADA4] focus:outline-none focus:ring-2 focus:ring-[#3D2C24]/20 focus:border-[#3D2C24] transition"
                            >
                        </div>

                        <!-- Kata Sandi -->
                        <div>
                            <label class="block text-xs font-bold text-[#3D2C24] mb-1">Kata Sandi</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password"
                                    placeholder="Buat kata sandi"
                                    required 
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#E5DEC9] bg-white text-sm text-[#3D2C24] placeholder-[#B5ADA4] focus:outline-none focus:ring-2 focus:ring-[#3D2C24]/20 focus:border-[#3D2C24] transition pr-10"
                                >
                                <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#A89F91] hover:text-[#3D2C24]">
                                    <i data-lucide="eye-off" id="eyeIcon" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <p class="text-[10px] text-[#A89F91] mt-1">Gunakan minimal 8 karakter.</p>
                        </div>

                        <!-- Preferensi Beans Favorit -->
                        <div>
                            <label class="block text-xs font-bold text-[#3D2C24] mb-1">Preferensi Beans Favorit</label>
                            <select name="bean_preference" class="w-full px-4 py-2.5 rounded-xl border border-[#E5DEC9] bg-white text-sm text-[#3D2C24] focus:outline-none focus:ring-2 focus:ring-[#3D2C24]/20 focus:border-[#3D2C24] transition">
                                <option value="Semuanya">Semuanya</option>
                                <option value="Single Origin">Single Origin</option>
                                <option value="Blend">Blend</option>
                            </select>
                            <p class="text-[10px] text-[#A89F91] mt-1">Pilihan: Single Origin, Blend, Semuanya</p>
                        </div>

                        <!-- Roasting Profile Favorit -->
                        <div>
                            <label class="block text-xs font-bold text-[#3D2C24] mb-1">Roasting Profile Favorit</label>
                            <select name="roasting_profile" class="w-full px-4 py-2.5 rounded-xl border border-[#E5DEC9] bg-white text-sm text-[#3D2C24] focus:outline-none focus:ring-2 focus:ring-[#3D2C24]/20 focus:border-[#3D2C24] transition">
                                <option value="Medium">Medium</option>
                                <option value="Light">Light</option>
                                <option value="Dark">Dark</option>
                            </select>
                            <p class="text-[10px] text-[#A89F91] mt-1">Pilihan: Light, Medium, Dark</p>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-[#3D2C24] hover:bg-[#2A1E18] text-white py-3 px-4 rounded-xl font-medium text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition duration-200 mt-3"
                        >
                            <span>Buat Akun Baru</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </form>

                    <!-- Terms Notice -->
                    <p class="text-center text-[10px] text-[#A89F91] mt-5">
                        Dengan mendaftar, kamu menyetujui Syarat & Ketentuan dan Kebijakan Privasi Coffenfo.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- Page Footer -->
    <footer class="w-full text-center text-xs text-[#A89F91] py-4">
        © 2026 Coffenfo. Diracik untuk pecinta kopi.
    </footer>

    <!-- Script Init Icon & Toggle Password -->
    <script>
        lucide.createIcons();

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>