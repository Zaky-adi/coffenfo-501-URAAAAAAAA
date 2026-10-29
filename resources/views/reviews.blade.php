<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo - Review & Rating</title>
    
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CDN Alpine.js -->
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
    modalOpen: false,
    selectedFilter: 'semua',
    sortBy: 'terbaru',
    sortLabel: 'Urutan Terbaru',
    sortOpen: false,
    ratingInput: 5,
    hoverRating: 0,
    reviewText: '',
    uploadedPhotos: [
        'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=200&q=80'
    ],
    
    handleFileUpload(event) {
        const files = event.target.files;
        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.uploadedPhotos.push(e.target.result);
                };
                reader.readAsDataURL(files[i]);
            }
        }
    },
    removePhoto(index) {
        this.uploadedPhotos.splice(index, 1);
    },

    reviews: [
        { 
            id: 1, 
            name: 'Dinda Pratiwi', 
            date: '12 September 2024', 
            rating: 5, 
            text: 'Single origin Gayo-nya juara, aromanya wangi dan tidak pahit. Tempatnya adem, Wi-Fi kencang, dan banyak stopkontak, cocok buat kerja seharian.', 
            helpful: 115, 
            photos: [
                'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=300&q=80', 
                'https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=300&q=80',
                'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=300&q=80'
            ],
            extraPhotosCount: 3
        },
        { 
            id: 2, 
            name: 'Ricky Hakim', 
            date: '2 September 2024', 
            rating: 5, 
            text: 'Area self-brewing-nya seru, barista mau ngajarin cara V60 yang bener. Sayang akhir pekan agak penuh, jadi datang sebelum jam 4 sore.', 
            helpful: 64, 
            photos: [
                'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=300&q=80', 
                'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=300&q=80'
            ],
            extraPhotosCount: 0
        },
        { 
            id: 3, 
            name: 'Sari Wulandari', 
            date: '23 Agustus 2024', 
            rating: 4, 
            text: 'Suasana outdoor-nya enak buat sore hari. Menu-nya harganya seimbang, harganya masih wajar untuk kualitas sejenis.', 
            helpful: 21, 
            photos: [],
            extraPhotosCount: 0
        },
        { 
            id: 4, 
            name: 'Budi Santoso', 
            date: '15 Agustus 2024', 
            rating: 3, 
            text: 'Tempatnya lumayan nyaman tapi agak bising saat jam sepulang kantor.', 
            helpful: 8, 
            photos: [],
            extraPhotosCount: 0
        }
    ],

    get filteredReviews() {
        let result = this.reviews;

        if (this.selectedFilter === 'foto') {
            result = result.filter(r => r.photos.length > 0);
        } else if (this.selectedFilter === '5') {
            result = result.filter(r => r.rating === 5);
        } else if (this.selectedFilter === '4') {
            result = result.filter(r => r.rating === 4);
        }

        if (this.sortBy === 'tertinggi') {
            result = [...result].sort((a, b) => b.rating - a.rating);
        } else if (this.sortBy === 'bermanfaat') {
            result = [...result].sort((a, b) => b.helpful - a.helpful);
        } else {
            result = [...result].sort((a, b) => b.id - a.id);
        }

        return result;
    }
}">

    <!-- NAVBAR -->
    <header class="bg-[#F8F5F0] border-b border-[#E5DEC9] py-3.5 px-6 md:px-12 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-[#3D2C24] text-[#EEF2DA] p-1.5 rounded-lg font-bold text-base w-7 h-7 flex items-center justify-center">c</div>
                <a href="{{ url('/') }}" class="text-xl font-bold tracking-tight">coffenfo</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-xs font-semibold hover:underline">← Kembali ke Cafe</a>
                <div class="w-8 h-8 rounded-full bg-[#C36930] text-white flex items-center justify-center text-xs font-bold shadow-xs">
                    RO
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="max-w-7xl mx-auto px-6 md:px-12 py-8 w-full space-y-8">
        
        <div class="space-y-1">
            <h1 class="text-2xl md:text-3xl font-serif-title font-bold">Review & Rating</h1>
            <p class="text-xs text-[#8C827A]">Grind Now Coffee Bar • Batam Center, Batam</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- SISI KIRI -->
            <div class="lg:col-span-4 bg-white border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-6 sticky top-24">
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-serif-title font-bold text-[#3D2C24]">4.7</span>
                    <span class="text-xs text-[#8C827A]">/ 5.0</span>
                </div>

                <div class="text-amber-500 flex gap-1">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span class="text-gray-300">★</span>
                </div>

                <p class="text-xs text-[#8C827A] border-b border-[#E5DEC9] pb-4">Berdasarkan 120+ ulasan</p>

                <div class="space-y-2 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-4 text-[#8C827A]">5</span> <span class="text-amber-500">★</span>
                        <div class="flex-1 h-2 bg-[#F4EFE6] rounded-full overflow-hidden"><div class="bg-[#C36930] h-full w-[76%]"></div></div>
                        <span class="w-8 text-right text-[#8C827A]">76%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 text-[#8C827A]">4</span> <span class="text-amber-500">★</span>
                        <div class="flex-1 h-2 bg-[#F4EFE6] rounded-full overflow-hidden"><div class="bg-[#C36930] h-full w-[18%]"></div></div>
                        <span class="w-8 text-right text-[#8C827A]">18%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 text-[#8C827A]">3</span> <span class="text-amber-500">★</span>
                        <div class="flex-1 h-2 bg-[#F4EFE6] rounded-full overflow-hidden"><div class="bg-[#C36930] h-full w-[4%]"></div></div>
                        <span class="w-8 text-right text-[#8C827A]">4%</span>
                    </div>
                </div>

                <button @click="modalOpen = true" class="w-full bg-[#3D2C24] text-white py-3 rounded-2xl text-xs font-semibold hover:bg-black transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Tulis Review
                </button>

                <a href="{{ route('dashboard') }}" class="block w-full bg-white border border-[#E5DEC9] text-center py-3 rounded-2xl text-xs font-semibold text-[#3D2C24] hover:bg-[#F8F5F0] transition">
                    Kembali
                </a>
            </div>

            <!-- SISI KANAN -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Filter Bar dengan Dropdown Custom Estetik -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white border border-[#E5DEC9] p-4 rounded-2xl shadow-xs relative">
                    <h3 class="font-bold text-sm"><span x-text="filteredReviews.length"></span> Review</h3>
                    
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <button @click="selectedFilter = 'semua'" :class="selectedFilter === 'semua' ? 'bg-[#3D2C24] text-white' : 'bg-[#F8F5F0] text-[#6E645E]'" class="px-3.5 py-1.5 rounded-full font-medium transition cursor-pointer">Semua</button>
                        <button @click="selectedFilter = 'foto'" :class="selectedFilter === 'foto' ? 'bg-[#3D2C24] text-white' : 'bg-[#F8F5F0] text-[#6E645E]'" class="px-3.5 py-1.5 rounded-full font-medium transition cursor-pointer">Dengan Foto</button>
                        <button @click="selectedFilter = '5'" :class="selectedFilter === '5' ? 'bg-[#3D2C24] text-white' : 'bg-[#F8F5F0] text-[#6E645E]'" class="px-3.5 py-1.5 rounded-full font-medium transition cursor-pointer">★ 5</button>
                        <button @click="selectedFilter = '4'" :class="selectedFilter === '4' ? 'bg-[#3D2C24] text-white' : 'bg-[#F8F5F0] text-[#6E645E]'" class="px-3.5 py-1.5 rounded-full font-medium transition cursor-pointer">★ 4</button>
                        
                        <!-- CUSTOM DROPDOWN ALPINE.JS -->
                        <div class="relative" @click.away="sortOpen = false">
                            <button @click="sortOpen = !sortOpen" class="bg-[#F8F5F0] border border-[#E5DEC9] px-4 py-1.5 rounded-full text-xs font-medium flex items-center gap-2 hover:bg-[#EEE8DC] transition cursor-pointer">
                                <span x-text="sortLabel"></span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </button>

                            <!-- Menu Pop-up Dropdown -->
                            <div x-show="sortOpen" class="absolute right-0 mt-2 w-44 bg-white border border-[#E5DEC9] rounded-2xl shadow-xl py-2 z-50 space-y-1" style="display: none;">
                                <button @click="sortBy = 'terbaru'; sortLabel = 'Urutan Terbaru'; sortOpen = false" 
                                        :class="sortBy === 'terbaru' ? 'bg-[#F8F5F0] font-bold text-[#3D2C24]' : 'text-[#6E645E]'" 
                                        class="w-full text-left px-4 py-2 text-xs hover:bg-[#F8F5F0] transition cursor-pointer">
                                    Urutan Terbaru
                                </button>
                                <button @click="sortBy = 'tertinggi'; sortLabel = 'Rating Tertinggi'; sortOpen = false" 
                                        :class="sortBy === 'tertinggi' ? 'bg-[#F8F5F0] font-bold text-[#3D2C24]' : 'text-[#6E645E]'" 
                                        class="w-full text-left px-4 py-2 text-xs hover:bg-[#F8F5F0] transition cursor-pointer">
                                    Rating Tertinggi
                                </button>
                                <button @click="sortBy = 'bermanfaat'; sortLabel = 'Paling Bermanfaat'; sortOpen = false" 
                                        :class="sortBy === 'bermanfaat' ? 'bg-[#F8F5F0] font-bold text-[#3D2C24]' : 'text-[#6E645E]'" 
                                        class="w-full text-left px-4 py-2 text-xs hover:bg-[#F8F5F0] transition cursor-pointer">
                                    Paling Bermanfaat
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- List Ulasan -->
                <div class="space-y-4">
                    <template x-for="rev in filteredReviews" :key="rev.id">
                        <div class="bg-white border border-[#E5DEC9] rounded-3xl p-6 shadow-xs space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#3D2C24] text-white flex items-center justify-center font-bold text-xs" x-text="rev.name.substring(0,2).toUpperCase()"></div>
                                    <div>
                                        <h4 class="font-bold text-xs" x-text="rev.name"></h4>
                                        <p class="text-[10px] text-[#8C827A]" x-text="rev.date"></p>
                                    </div>
                                </div>
                                <div class="text-amber-500 text-xs">
                                    <span x-text="'★'.repeat(rev.rating)"></span><span class="text-gray-300" x-text="'★'.repeat(5 - rev.rating)"></span>
                                </div>
                            </div>

                            <p class="text-xs text-[#6E645E] leading-relaxed" x-text="rev.text"></p>

                            <template x-if="rev.photos.length > 0">
                                <div class="flex gap-2.5 pt-1">
                                    <template x-for="img in rev.photos">
                                        <div class="w-16 h-16 rounded-2xl overflow-hidden border border-[#E5DEC9] bg-[#EAE2D5]">
                                            <img :src="img" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                    <template x-if="rev.extraPhotosCount > 0">
                                        <div class="w-16 h-16 rounded-2xl bg-[#3D2C24] text-white flex items-center justify-center text-xs font-bold shadow-xs">
                                            +<span x-text="rev.extraPhotosCount"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <div class="flex justify-between items-center pt-3 border-t border-[#F4EFE6] text-xs text-[#8C827A]">
                                <button class="flex items-center gap-1.5 bg-[#F8F5F0] px-3 py-1.5 rounded-xl hover:bg-[#E5DEC9] transition cursor-pointer">
                                    <i data-lucide="thumbs-up" class="w-3.5 h-3.5"></i>
                                    <span>Bermanfaat (<span x-text="rev.helpful"></span>)</span>
                                </button>
                                <button class="hover:text-[#3D2C24] transition text-[11px]">Laporkan Review</button>
                            </div>
                        </div>
                    </template>

                    <template x-if="filteredReviews.length === 0">
                        <div class="bg-white border border-[#E5DEC9] rounded-3xl p-10 text-center space-y-2">
                            <p class="text-xs text-[#8C827A]">Belum ada ulasan untuk filter ini.</p>
                        </div>
                    </template>
                </div>

            </div>

        </div>

    </main>

    <!-- MODAL POP-UP: TULIS REVIEW -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4 py-6" style="display: none;">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs" @click="modalOpen = false"></div>
        
        <div class="bg-white border border-[#E5DEC9] rounded-3xl p-6 md:p-8 max-w-lg w-full shadow-2xl relative z-10 space-y-5">
            <div class="flex justify-between items-center pb-4 border-b border-[#E5DEC9]">
                <div>
                    <h2 class="font-serif-title font-bold text-lg">Tulis Review</h2>
                    <p class="text-[11px] text-[#8C827A]">Grind Now Coffee Bar • Batam Center, Batam</p>
                </div>
                <button @click="modalOpen = false" class="p-1 rounded-full hover:bg-[#EEE8DC] cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-[#3D2C24]">Beri Rating</label>
                <div class="flex items-center gap-1.5 cursor-pointer">
                    <template x-for="star in [1, 2, 3, 4, 5]">
                        <button type="button" 
                                @click="ratingInput = star" 
                                @mouseenter="hoverRating = star" 
                                @mouseleave="hoverRating = 0"
                                class="p-1 focus:outline-none transition transform hover:scale-110 cursor-pointer">
                            <svg class="w-7 h-7" :class="(hoverRating >= star || (hoverRating === 0 && ratingInput >= star)) ? 'text-amber-500 fill-amber-500' : 'text-gray-300 fill-gray-200'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385c.103.429-.336.765-.71.53l-4.73-2.85a.563.563 0 00-.586 0L6.982 20.54c-.374.235-.813-.101-.71-.53l1.285-5.385a.563.563 0 00-.182-.557l-4.204-3.602c-.38-.325-.178-.948.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-[#3D2C24]">Ceritakan pengalamanmu</label>
                <textarea x-model="reviewText" rows="3" placeholder="Bagikan pengalaman kopi dan suasana di kafe ini..." class="w-full bg-[#F8F5F0] border border-[#E5DEC9] rounded-2xl p-3 text-xs focus:outline-none focus:border-[#3D2C24] resize-none"></textarea>
                <div class="text-[10px] text-right text-[#8C827A]" x-text="reviewText.length + '/250'">0/250</div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-[#3D2C24]">Foto (opsional)</label>
                <label class="border-2 border-dashed border-[#E5DEC9] rounded-2xl p-5 text-center block space-y-2 bg-[#F8F5F0] hover:bg-[#F4EFE6] transition cursor-pointer">
                    <input type="file" accept="image/*" multiple @change="handleFileUpload" class="hidden">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center mx-auto shadow-xs text-[#C36930]">
                        <i data-lucide="upload" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs font-bold block text-[#3D2C24]">Unggah Foto Cafe / Kopi</span>
                    <p class="text-[10px] text-[#8C827A]">Tarik dan lepas foto di sini, atau klik untuk memilih. JPG, PNG - maks. 5MB - hingga 4 foto.</p>
                </label>

                <div class="flex flex-wrap gap-2.5 pt-1">
                    <template x-for="(photo, index) in uploadedPhotos" :key="index">
                        <div class="relative w-16 h-16 rounded-2xl overflow-hidden border border-[#E5DEC9] bg-[#EAE2D5] group">
                            <img :src="photo" class="w-full h-full object-cover">
                            <button @click="removePhoto(index)" class="absolute top-1 right-1 bg-black/60 hover:bg-black text-white w-4 h-4 rounded-full flex items-center justify-center text-[10px] cursor-pointer">
                                ✕
                            </button>
                        </div>
                    </template>
                    <template x-if="uploadedPhotos.length < 4">
                        <label class="w-16 h-16 rounded-2xl border border-dashed border-[#C36930] bg-[#F8F5F0] hover:bg-[#F4EFE6] flex items-center justify-center text-[#C36930] cursor-pointer transition">
                            <input type="file" accept="image/*" multiple @change="handleFileUpload" class="hidden">
                            <span class="text-lg font-bold">+</span>
                        </label>
                    </template>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button @click="modalOpen = false" class="px-5 py-2.5 rounded-2xl text-xs font-semibold text-[#8C827A] hover:bg-[#F8F5F0] transition cursor-pointer">Batal</button>
                <button @click="modalOpen = false; alert('Review dan foto berhasil dikirim!');" class="bg-[#3D2C24] text-white px-6 py-2.5 rounded-2xl text-xs font-semibold hover:bg-black transition shadow-xs flex items-center gap-2 cursor-pointer">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i> Kirim Review
                </button>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-[#F8F5F0] border-t border-[#E5DEC9] py-6 px-6 md:px-12 mt-16 flex justify-between items-center text-xs text-[#8C827A]">
        <p>© 2026 Coffenfo • Setiap cangkir, sebuah cerita.</p>
        <a href="{{ route('dashboard') }}" class="font-bold text-[#3D2C24] hover:underline">← Kembali ke Cafe</a>
    </footer>

    <!-- Init Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>