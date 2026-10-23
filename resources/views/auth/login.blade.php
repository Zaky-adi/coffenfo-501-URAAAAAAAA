<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Login</title>
    
    <!-- Memanggil Tailwind CSS via CDN agar langsung bisa digunakan -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    
    <!-- Icon Library (Lucide) -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#F5EFEB] min-h-screen flex items-center justify-center p-4 font-['Plus_Jakarta_Sans'] text-[#3D2C24]">

    <!-- Kotak Utama Pembungkus (Card) -->
    <div class="w-full max-w-4xl bg-[#FCF9F2] rounded-3xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
        
        <!-- BAGIAN KIRI: Banner & Ilustrasi -->
        <div class="md:col-span-5 bg-[#EEF2DA] p-8 flex flex-col justify-between">
            <div>
                <!-- Logo -->
                <div class="flex items-center gap-2 mb-10">
                    <span class="bg-[#3D2C24] text-[#EEF2DA] px-2.5 py-1 rounded-lg font-bold">c</span>
                    <span class="font-bold text-lg">coffenfo</span>
                </div>
                <!-- Judul -->
                <h1 class="text-3xl font-['Playfair_Display'] font-bold leading-tight mb-3">
                    Temukan & Review Cafe Favoritmu di Batam
                </h1>
                <p class="text-xs text-[#6E645E]">
                    Dari tegukan pertama hingga tempat langganan. Temukan cerita kopi berikutnya bersama Coffenfo.
                </p>
            </div>
            <!-- Catatan Kaki Kiri -->
            <div class="text-xs text-[#6E645E] mt-6">
                ❤️ Tempat kopi bertemu cerita.
            </div>
        </div>

        <!-- BAGIAN KANAN: Form Login -->
        <div class="md:col-span-7 p-8 md:p-12 bg-white flex flex-col justify-center">
            
            <!-- Tombol Tab (Masuk / Daftar) -->
            <div class="bg-[#F2EBE1] p-1 rounded-xl flex mb-8">
                 <!-- Tombol Masuk tetap aktif -->
                <a href="{{ route('login') }}" class="flex-1 py-2 text-center text-sm font-semibold rounded-lg bg-white shadow-sm text-[#3D2C24] flex items-center justify-center gap-1.5 transition">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Masuk
                </a>
    
                <!-- Tombol Daftar diubah menjadi link yang mengarah ke halaman register -->
                <a href="{{ route('register') }}" class="flex-1 py-2 text-center text-sm font-medium text-[#8C827A] hover:text-[#3D2C24] transition">
                  Daftar
                 </a>
            </div>
            

            <h2 class="text-2xl font-['Playfair_Display'] font-bold mb-1">Senang kamu kembali.</h2>
            <p class="text-xs text-gray-500 mb-6">Masuk untuk menemukan cafe dan berbagi review.</p>

            <!-- Form Input -->
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold mb-1">Email / Username</label>
                    <input type="text" placeholder="Email atau username kamu" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#3D2C24]">
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Kata Sandi</label>
                    <input type="password" placeholder="Masukkan kata sandi" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#3D2C24]">
                </div>

                <div class="text-right">
                    <a href="#" class="text-xs text-[#3D2C24] underline">Lupa Password?</a>
                </div>

                <button type="submit" class="w-full bg-[#3D2C24] text-white py-3 rounded-xl text-sm font-medium hover:bg-black transition">
                    Masuk Ke Coffenfo →
                </button>
            </form>

        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>