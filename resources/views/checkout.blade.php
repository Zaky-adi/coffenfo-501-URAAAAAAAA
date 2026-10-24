<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Checkout Pesanan</title>
    
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CDN Alpine.js untuk interaktivitas -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Library (Lucide) -->
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
<body x-data="{ orderType: 'dine-in', paymentMethod: 'ewallet' }" class="text-[#3D2C24] min-h-screen flex flex-col justify-between">

    <!-- Header / Navbar Checkout -->
    <header class="bg-[#FCF9F2] border-b border-[#E5DEC9] py-4 px-6 md:px-12 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="bg-[#3D2C24] text-[#EEF2DA] p-1.5 rounded-lg font-bold text-base w-7 h-7 flex items-center justify-center">c</div>
                <span class="text-xl font-bold tracking-tight">coffenfo</span>
            </div>

            <!-- Stepper Progress -->
            <div class="hidden md:flex items-center gap-6 text-xs font-medium text-[#8C827A]">
                <span class="flex items-center gap-1.5"><span class="w-5 h-5 rounded-full bg-[#E5DEC9] text-[#3D2C24] flex items-center justify-center text-[10px] font-bold">1</span> Keranjang</span>
                <div class="w-8 h-[1px] bg-[#E5DEC9]"></div>
                <span class="flex items-center gap-1.5 text-[#3D2C24] font-bold"><span class="w-5 h-5 rounded-full bg-[#3D2C24] text-white flex items-center justify-center text-[10px]">2</span> Checkout</span>
                <div class="w-8 h-[1px] bg-[#E5DEC9]"></div>
                <span class="flex items-center gap-1.5"><span class="w-5 h-5 rounded-full bg-[#E5DEC9] text-[#8C827A] flex items-center justify-center text-[10px]">3</span> Pesanan selesai</span>
            </div>

            <!-- Bantuan / Info Aman -->
            <div class="flex items-center gap-1.5 text-xs text-[#6E645E]">
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                <span>Checkout aman</span>
            </div>
        </div>
    </header>

    <!-- Konten Utama Checkout -->
    <main class="max-w-7xl mx-auto px-4 md:px-12 py-8 w-full">
        
        <!-- Kembali & Judul -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-xs text-[#8C827A] hover:text-[#3D2C24] mb-2 font-medium">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke keranjang
            </a>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
                <div>
                    <h1 class="text-2xl font-serif-title font-bold">Checkout Pesanan</h1>
                    <p class="text-xs text-[#6E645E]">Kopi pilihanmu, tinggal satu langkah lagi. Cek pesanan dan pilih pembayaran.</p>
                </div>
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="store" class="w-3.5 h-3.5 text-[#8C827A]"></i>
                    Anchor Cafe & Roastery
                </div>
            </div>
        </div>

        <!-- Grid Layout Checkout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- KOLOM KIRI: Tipe Pesanan, Meja, & Ringkasan Pesanan -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Box Tipe Pesanan -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <h3 class="font-bold text-sm">Tipe Pesanan</h3>
                    
                    <!-- Toggle Dine-in / Takeaway -->
                    <div class="grid grid-cols-2 gap-2 bg-[#F2EBE1] p-1.5 rounded-2xl">
                        <button @click="orderType = 'dine-in'" 
                                :class="orderType === 'dine-in' ? 'bg-[#3D2C24] text-white shadow-xs' : 'text-[#6E645E] hover:text-[#3D2C24]'"
                                class="py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
                            Makan di Tempat (Dine-in)
                        </button>
                        <button @click="orderType = 'takeaway'" 
                                :class="orderType === 'takeaway' ? 'bg-[#3D2C24] text-white shadow-xs' : 'text-[#6E645E] hover:text-[#3D2C24]'"
                                class="py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
                            Bawa Pulang (Takeaway)
                        </button>
                    </div>

                    <!-- Nomor Meja (Muncul jika Dine-in) -->
                    <div x-show="orderType === 'dine-in'" class="space-y-1.5 pt-2">
                        <label class="block text-xs font-bold">Nomor Meja</label>
                        <div class="relative">
                            <i data-lucide="hash" class="w-4 h-4 text-[#8C827A] absolute left-3.5 top-3"></i>
                            <input type="text" value="12" class="w-full bg-white border border-[#E5DEC9] pl-10 pr-4 py-2.5 rounded-xl text-xs font-medium focus:outline-none focus:border-[#3D2C24]">
                        </div>
                        <p class="text-[10px] text-[#8C827A]">Lihat nomor meja di tempat kamu duduk.</p>
                    </div>
                </div>

                <!-- Box Ringkasan Pesanan -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-sm">Ringkasan Pesanan</h3>
                        <span class="text-xs text-[#8C827A]">3 item</span>
                    </div>

                    <!-- Item 1 -->
                    <div class="flex items-center justify-between gap-4 py-3 border-b border-[#E5DEC9]">
                        <div class="flex items-center gap-3">
                            <div class="w-16 h-16 rounded-2xl bg-[#E5DEC9] flex items-center justify-center text-xl shrink-0">☕</div>
                            <div>
                                <h4 class="font-bold text-xs">Manual Brew In House Beans</h4>
                                <p class="text-[11px] text-[#8C827A]">Signature house selection design for balance clarity</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="flex items-center gap-2 mb-1 justify-end">
                                <button class="w-6 h-6 rounded-lg bg-white border border-[#E5DEC9] flex items-center justify-center text-xs font-bold hover:bg-[#F2EBE1]">-</button>
                                <span class="text-xs font-bold">1</span>
                                <button class="w-6 h-6 rounded-lg bg-white border border-[#E5DEC9] flex items-center justify-center text-xs font-bold hover:bg-[#F2EBE1]">+</button>
                            </div>
                            <span class="text-xs font-bold">Rp 45.000</span>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-center justify-between gap-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-16 h-16 rounded-2xl bg-[#E5DEC9] flex items-center justify-center text-xl shrink-0">🍳</div>
                            <div>
                                <h4 class="font-bold text-xs">Salted Egg Chicken</h4>
                                <p class="text-[11px] text-[#8C827A]">Crispy chicken with rich salted egg sauce</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="flex items-center gap-2 mb-1 justify-end">
                                <button class="w-6 h-6 rounded-lg bg-white border border-[#E5DEC9] flex items-center justify-center text-xs font-bold hover:bg-[#F2EBE1]">-</button>
                                <span class="text-xs font-bold">2</span>
                                <button class="w-6 h-6 rounded-lg bg-white border border-[#E5DEC9] flex items-center justify-center text-xs font-bold hover:bg-[#F2EBE1]">+</button>
                            </div>
                            <span class="text-xs font-bold">Rp 45.000</span>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-[#FFF8E7] border border-amber-200/80 rounded-2xl p-4 flex items-start gap-3">
                    <i data-lucide="coffee" class="w-5 h-5 text-amber-700 shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-xs font-bold text-amber-900">Dibuat segar, diantar ke mejamu.</h4>
                        <p class="text-[11px] text-amber-800/80 mt-0.5">Barista akan menyiapkan pesanan setelah pembayaran berhasil.</p>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Metode Pembayaran & Rincian Harga -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Box Metode Pembayaran -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <h3 class="font-bold text-sm">Metode Pembayaran</h3>

                    <!-- Opsi E-Wallet -->
                    <label @click="paymentMethod = 'ewallet'" 
                           :class="paymentMethod === 'ewallet' ? 'border-[#3D2C24] bg-white shadow-xs' : 'border-[#E5DEC9] bg-[#FCF9F2]'"
                           class="block border-2 rounded-2xl p-4 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment" checked class="accent-[#3D2C24]">
                                <span class="text-xs font-bold">E-Wallet (QRIS / GoPay / OVO / ShopeePay)</span>
                            </div>
                            <i data-lucide="qr-code" class="w-5 h-5 text-[#3D2C24]"></i>
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] text-[#8C827A] mb-2">
                            <span class="bg-[#F2EBE1] px-2 py-0.5 rounded font-medium">QRIS</span>
                            <span class="bg-[#F2EBE1] px-2 py-0.5 rounded font-medium">GoPay</span>
                            <span class="bg-[#F2EBE1] px-2 py-0.5 rounded font-medium">OVO</span>
                            <span class="bg-[#F2EBE1] px-2 py-0.5 rounded font-medium">ShopeePay</span>
                        </div>
                        <p class="text-[11px] text-[#6E645E]">Scan QR atau lanjutkan ke e-wallet setelah menekan Bayar Sekarang.</p>
                    </label>

                    <!-- Opsi Transfer Bank -->
                    <label @click="paymentMethod = 'bank'" 
                           :class="paymentMethod === 'bank' ? 'border-[#3D2C24] bg-white shadow-xs' : 'border-[#E5DEC9] bg-[#FCF9F2]'"
                           class="block border-2 rounded-2xl p-4 cursor-pointer transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment" class="accent-[#3D2C24]">
                                <span class="text-xs font-bold">Transfer Bank / Virtual Account (BCA / Mandiri)</span>
                            </div>
                            <i data-lucide="landmark" class="w-5 h-5 text-[#8C827A]"></i>
                        </div>
                    </label>
                </div>

                <!-- Box Rincian Pembayaran -->
                <div class="bg-[#FCF9F2] border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <h3 class="font-bold text-sm">Rincian Pembayaran</h3>

                    <div class="space-y-2 text-xs text-[#6E645E] border-b border-[#E5DEC9] pb-4">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-medium text-[#3D2C24]">Rp 135.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax & Service 10%</span>
                            <span class="font-medium text-[#3D2C24]">Rp 9.500</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-sm font-bold pt-1">
                        <span>Total Payment</span>
                        <span class="text-base text-[#3D2C24]">Rp 144.500</span>
                    </div>

                    <!-- Tombol Bayar Sekarang -->
                    <button class="w-full bg-[#3D2C24] text-white py-3.5 rounded-xl text-xs font-semibold flex items-center justify-center gap-2 hover:bg-black transition cursor-pointer shadow-md">
                        <span>Bayar Sekarang (Rp 144.500)</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>

                    <div class="flex items-center justify-center gap-1.5 text-[10px] text-[#8C827A] pt-1">
                        <i data-lucide="lock" class="w-3 h-3"></i>
                        <span>Pembayaran digital aman & terenkripsi</span>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-[#FCF9F2] border-t border-[#E5DEC9] py-6 px-6 mt-16 flex flex-col md:flex-row justify-between items-center text-xs text-[#8C827A] gap-4">
        <p>© 2026 Coffenfo • Setiap cangkir, sebuah cerita.</p>
        <a href="#" class="hover:text-[#3D2C24] font-medium">Butuh bantuan? Hubungi kafe</a>
    </footer>

    <!-- Script Init Icon -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>