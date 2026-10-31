<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Checkout Pesanan</title>
    
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CDN Alpine.js untuk Interaktif Tipe Pesanan, Pembayaran, Stepper, & Modal Sukses -->
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
    orderType: 'dine-in', // 'dine-in' atau 'takeaway'
    paymentMethod: 'wallet', // 'wallet' atau 'bank'
    successModal: false,
    
    // Data Pesanan
    items: [
        { id: 1, name: 'Manual Brew In House Beans', desc: 'Signature house selection design for balance clarity', price: 45000, qty: 1, img: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=200&q=80' },
        { id: 2, name: 'Salted Egg Chicken', desc: 'Rice Bowl', price: 45000, qty: 1, img: 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=200&q=80' }
    ],

    get subtotal() {
        return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
    },
    get tax() {
        return this.subtotal * 0.1;
    },
    get totalPayment() {
        return this.subtotal + this.tax;
    },
    formatRupiah(value) {
        return 'Rp ' + value.toLocaleString('id-ID');
    }
}">

    <!-- NAVBAR -->
    <header class="bg-[#F8F5F0] border-b border-[#E5DEC9] py-3.5 px-6 md:px-12 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-[#3D2C24] text-[#EEF2DA] p-1.5 rounded-lg font-bold text-base w-7 h-7 flex items-center justify-center">c</div>
                <a href="{{ url('/') }}" class="text-xl font-bold tracking-tight">Coffenfo</a>
            </div>

            <!-- Steps Indicator -->
            <div class="hidden md:flex items-center gap-6 text-xs font-semibold text-[#8C827A]">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#EAE2D5] text-[#3D2C24] flex items-center justify-center text-[10px]">✓</span>
                    <span>Keranjang</span>
                </div>
                <div class="w-8 h-[1px] bg-[#E5DEC9]"></div>
                <div class="flex items-center gap-2 text-[#3D2C24]">
                    <span class="w-5 h-5 rounded-full bg-[#3D2C24] text-white flex items-center justify-center text-[10px] font-bold">2</span>
                    <span>Checkout</span>
                </div>
                <div class="w-8 h-[1px] bg-[#E5DEC9]"></div>
                <span>Pesanan selesai</span>
            </div>

            <div class="flex items-center gap-2 text-xs font-semibold text-[#8C827A]">
                <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                <span>Checkout aman</span>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA CHECKOUT -->
    <main class="max-w-7xl mx-auto px-6 md:px-12 py-8 w-full space-y-6">
        
        <!-- Breadcrumb Kembali -->
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-xs text-[#8C827A] hover:text-[#3D2C24] transition font-medium">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke keranjang
        </a>

        <!-- Header Title -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-serif-title font-bold">Checkout Pesanan</h1>
                <p class="text-xs text-[#8C827A] mt-1">Kopi pilihanmu, tinggal satu langkah lagi. Cek pesanan dan pilih pembayaran.</p>
            </div>
            
            <div class="bg-white border border-[#E5DEC9] px-4 py-2 rounded-2xl text-xs font-semibold shadow-xs flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Grind Now Coffee Bar
            </div>
        </div>

        <!-- GRID UTAMA (KIRI: TIPE PESANAN & RINGKASAN, KANAN: PEMBAYARAN & RINCIAN) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- SISI KIRI (7 Kolom) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- CARD 1: TIPE PESANAN -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-5">
                    <h3 class="font-bold text-sm">Tipe pesanan</h3>
                    
                    <!-- Toggle Switch Dine-in / Takeaway -->
                    <div class="grid grid-cols-2 bg-[#F8F5F0] p-1.5 rounded-2xl border border-[#E5DEC9]">
                        <button @click="orderType = 'dine-in'" 
                                :class="orderType === 'dine-in' ? 'bg-[#3D2C24] text-white shadow-xs' : 'text-[#8C827A] hover:text-[#3D2C24]'" 
                                class="py-2.5 rounded-xl text-xs font-bold transition cursor-pointer">
                            Makan di Tempat (Dine-in)
                        </button>
                        <button @click="orderType = 'takeaway'" 
                                :class="orderType === 'takeaway' ? 'bg-[#3D2C24] text-white shadow-xs' : 'text-[#8C827A] hover:text-[#3D2C24]'" 
                                class="py-2.5 rounded-xl text-xs font-bold transition cursor-pointer">
                            Bawa Pulang (Takeaway)
                        </button>
                    </div>

                    <!-- Input Nomor Meja (Hanya tampil jika Dine-in) -->
                    <div x-show="orderType === 'dine-in'" class="space-y-2">
                        <label class="block text-xs font-bold text-[#8C827A] uppercase tracking-wider">Nomor Meja</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#8C827A]">
                                <i data-lucide="car" class="w-4 h-4"></i> <!-- Icon kursi/meja -->
                            </span>
                            <input type="text" value="12" class="w-full bg-white border border-[#E5DEC9] rounded-2xl py-3 pl-10 pr-4 text-xs font-bold focus:outline-none focus:border-[#3D2C24]">
                        </div>
                        <p class="text-[11px] text-[#8C827A]">Lihat nomor meja di tempat kamu duduk.</p>
                    </div>

                    <div x-show="orderType === 'takeaway'" class="space-y-2" style="display: none;">
                        <p class="text-xs text-[#8C827A] bg-[#F8F5F0] p-3 rounded-2xl border border-[#E5DEC9]">Pesanan akan disiapkan dalam wadah bawa pulang. Silakan ambil di kasir saat nomor dipanggil.</p>
                    </div>
                </div>

                <!-- CARD 2: RINGKASAN PESANAN -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-sm">Ringkasan pesanan</h3>
                        <span class="text-xs text-[#8C827A]" x-text="items.length + ' item'">2 item</span>
                    </div>

                    <div class="space-y-4">
                        <template x-for="item in items" :key="item.id">
                            <div class="flex items-center justify-between gap-4 pb-4 border-b border-[#F4EFE6] last:border-b-0 last:pb-0">
                                <div class="flex items-center gap-3">
                                    <img :src="item.img" class="w-14 h-14 rounded-2xl object-cover bg-[#EAE2D5]">
                                    <div>
                                        <h4 class="font-bold text-xs" x-text="item.name"></h4>
                                        <p class="text-[10px] text-[#8C827A] mt-0.5" x-text="item.desc"></p>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <span class="text-xs font-bold text-[#3D2C24]" x-text="formatRupiah(item.price * item.qty)"></span>
                                    
                                    <!-- Stepper - 0 + -->
                                    <div class="flex items-center gap-2 bg-[#F8F5F0] border border-[#E5DEC9] px-2.5 py-1 rounded-xl">
                                        <button @click="if(item.qty > 0) item.qty--" class="text-xs font-bold text-[#8C827A] hover:text-[#3D2C24] cursor-pointer">-</button>
                                        <span class="text-xs font-bold w-4 text-center" x-text="item.qty"></span>
                                        <button @click="item.qty++" class="text-xs font-bold text-[#8C827A] hover:text-[#3D2C24] cursor-pointer">+</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Info Banner Kecil -->
                <div class="bg-[#F4EFE6] border border-[#E5DEC9] rounded-2xl p-4 flex items-start gap-3">
                    <i data-lucide="coffee" class="w-5 h-5 text-[#3D2C24] shrink-0 mt-0.5"></i>
                    <div class="text-xs space-y-0.5">
                        <p class="font-bold">Dibuat segar, diantar ke mejamu.</p>
                        <p class="text-[#8C827A]">Barista akan menyiapkan pesanan setelah pembayaran berhasil.</p>
                    </div>
                </div>

            </div>

            <!-- SISI KANAN (5 Kolom: Metode Pembayaran & Rincian Pembayaran) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- CARD 3: METODE PEMBAYARAN -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <h3 class="font-bold text-sm">Metode pembayaran</h3>

                    <div class="space-y-3">
                        <!-- Opsi 1: E-Wallet -->
                        <label @click="paymentMethod = 'wallet'" 
                               :class="paymentMethod === 'wallet' ? 'border-[#3D2C24] bg-[#F8F5F0]' : 'border-[#E5DEC9] bg-white'" 
                               class="flex items-center justify-between p-4 rounded-2xl border transition cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment" :checked="paymentMethod === 'wallet'" class="accent-[#3D2C24]">
                                <div>
                                    <span class="font-bold text-xs block">E-Wallet (QRIS / GoPay / OVO / ShopeePay)</span>
                                    <div class="flex gap-1.5 mt-1">
                                        <span class="text-[9px] bg-white border border-[#E5DEC9] px-1.5 py-0.5 rounded font-bold">QRIS</span>
                                        <span class="text-[9px] bg-white border border-[#E5DEC9] px-1.5 py-0.5 rounded font-bold">GoPay</span>
                                        <span class="text-[9px] bg-white border border-[#E5DEC9] px-1.5 py-0.5 rounded font-bold">OVO</span>
                                        <span class="text-[9px] bg-white border border-[#E5DEC9] px-1.5 py-0.5 rounded font-bold">ShopeePay</span>
                                    </div>
                                </div>
                            </div>
                            <i data-lucide="qr-code" class="w-5 h-5 text-[#8C827A]"></i>
                        </label>

                        <!-- Opsi 2: Transfer Bank / Virtual Account -->
                        <label @click="paymentMethod = 'bank'" 
                               :class="paymentMethod === 'bank' ? 'border-[#3D2C24] bg-[#F8F5F0]' : 'border-[#E5DEC9] bg-white'" 
                               class="flex items-center justify-between p-4 rounded-2xl border transition cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment" :checked="paymentMethod === 'bank'" class="accent-[#3D2C24]">
                                <div>
                                    <span class="font-bold text-xs block">Transfer Bank / Virtual Account (BCA | Mandiri | BNI)</span>
                                    <div class="flex gap-1.5 mt-1">
                                        <span class="text-[9px] bg-white border border-[#E5DEC9] px-1.5 py-0.5 rounded font-bold">BCA</span>
                                        <span class="text-[9px] bg-white border border-[#E5DEC9] px-1.5 py-0.5 rounded font-bold">Mandiri</span>
                                        <span class="text-[9px] bg-white border border-[#E5DEC9] px-1.5 py-0.5 rounded font-bold">BNI</span>
                                    </div>
                                </div>
                            </div>
                            <i data-lucide="landmark" class="w-5 h-5 text-[#8C827A]"></i>
                        </label>
                    </div>
                </div>

                <!-- CARD 4: RINCIAN PEMBAYARAN -->
                <div class="bg-white border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                    <h3 class="font-bold text-sm">Rincian pembayaran</h3>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between text-[#8C827A]">
                            <span>Subtotal</span>
                            <span class="font-bold text-[#3D2C24]" x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-[#8C827A] pb-3 border-b border-[#E5DEC9]">
                            <span>Tax & Service 10%</span>
                            <span class="font-bold text-[#3D2C24]" x-text="formatRupiah(tax)"></span>
                        </div>
                        <div class="flex justify-between text-sm font-bold pt-1">
                            <span>Total Payment</span>
                            <span class="text-[#C36930] text-base" x-text="formatRupiah(totalPayment)"></span>
                        </div>
                    </div>

                    <!-- Tombol Bayar Sekarang (Memicu Modal Pop-up Sukses) -->
                    <button @click="successModal = true" class="w-full bg-[#3D2C24] text-white py-3.5 rounded-2xl text-xs font-semibold hover:bg-black transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                        <span>Bayar Sekarang <span x-text="formatRupiah(totalPayment)"></span></span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>

                    <p class="text-[10px] text-center text-[#8C827A] flex items-center justify-center gap-1">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i> Pembayaran digital aman & terverifikasi
                    </p>
                </div>

            </div>

        </div>

    </main>

    <!-- POP-UP MODAL / PESANAN BERHASIL (Sesuai Gambar Figma Pop-out) -->
    <div x-show="successModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4" style="display: none;">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs" @click="successModal = false"></div>
        
        <div class="bg-white border border-[#E5DEC9] rounded-3xl p-8 max-w-lg w-full shadow-2xl relative z-10 text-center space-y-6">
            <!-- Icon Check hijau -->
            <div class="w-14 h-14 bg-[#2E7D32] text-white rounded-full flex items-center justify-center mx-auto shadow-md">
                <i data-lucide="check" class="w-7 h-7"></i>
            </div>

            <div class="space-y-1">
                <span class="text-[10px] font-bold text-[#C36930] tracking-widest uppercase">PESANAN #PCF-1051</span>
                <h2 class="text-2xl font-serif-title font-bold">Pesananmu berhasil dibuat!</h2>
                <p class="text-xs text-[#8C827A] max-w-sm mx-auto">Barista sedang menyiapkan pesananmu. Kamu akan mendapat notifikasi saat pesanan siap diambil.</p>
            </div>

            <!-- Card Estimasi & Total -->
            <div class="grid grid-cols-2 gap-4 bg-[#F8F5F0] border border-[#E5DEC9] p-4 rounded-2xl">
                <div>
                    <span class="text-[10px] text-[#8C827A] block uppercase tracking-wider font-medium">Estimasi siap</span>
                    <span class="text-sm font-bold text-[#3D2C24]">12–15 menit</span>
                </div>
                <div>
                    <span class="text-[10px] text-[#8C827A] block uppercase tracking-wider font-medium">Total pembayaran</span>
                    <span class="text-sm font-bold text-[#3D2C24]" x-text="formatRupiah(totalPayment)"></span>
                </div>
            </div>

            <a href="{{ url('/') }}" class="inline-block w-full bg-[#3D2C24] text-white text-center py-3.5 rounded-2xl text-xs font-semibold hover:bg-black transition shadow-xs">
                Kembali jelajahi cafe →
            </a>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-[#F8F5F0] border-t border-[#E5DEC9] py-6 px-6 md:px-12 mt-16 flex justify-between items-center text-xs text-[#8C827A]">
        <p>© 2026 Coffenfo • Setiap cangkir, sebuah cerita.</p>
        <a href="#" class="font-bold text-[#3D2C24] hover:underline">Butuh bantuan? Hubungi cafe</a>
    </footer>

    <!-- Init Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>