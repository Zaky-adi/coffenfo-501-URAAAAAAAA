<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffenfo Admin - Moderasi Review & Laporan</title>
    
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Library (Lucide) -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F5F0;
        }
    </style>
</head>
<body class="text-[#3D2C24] min-h-screen flex">

    <!-- SIDEBAR LEFT -->
    <aside class="w-64 bg-[#2A1E17] text-[#D8CFC4] flex flex-col justify-between shrink-0 min-h-screen p-4">
        <div>
            <!-- Admin Logo -->
            <div class="flex items-center gap-3 px-3 py-4 border-b border-[#3D2C24] mb-6">
                <div class="bg-[#FCF9F2] text-[#2A1E17] p-2 rounded-xl font-bold text-lg w-9 h-9 flex items-center justify-center">c</div>
                <div>
                    <h1 class="text-base font-bold text-white leading-tight">Coffenfo</h1>
                    <span class="text-[10px] text-[#A89F91] tracking-widest font-semibold uppercase">ADMIN PANEL</span>
                </div>
            </div>

            <!-- Menu Workspace -->
            <nav class="space-y-1">
                <p class="text-[10px] text-[#8C827A] font-bold uppercase px-3 mb-2 tracking-wider">Workspace</p>
                
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-[#3D2C24] hover:text-white transition">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Dashboard Overview
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-[#3D2C24] hover:text-white transition">
                    <i data-lucide="coffee" class="w-4 h-4"></i>
                    Kelola Cafe
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-[#3D2C24] hover:text-white transition">
                    <i data-lucide="flame" class="w-4 h-4"></i>
                    Kelola Beans & Roasting
                </a>

                <!-- Active Menu -->
                <a href="{{ route('admin.moderation') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold bg-[#D97736] text-white shadow-md">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        Moderasi Review
                    </div>
                </a>

                <a href="#" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-[#3D2C24] hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <i data-lucide="flag" class="w-4 h-4"></i>
                        Laporan Pengguna
                    </div>
                    <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold">5</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-[#3D2C24] hover:text-white transition">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    Pengaturan
                </a>
            </nav>
        </div>

        <!-- Logout Button -->
        <div class="pt-4 border-t border-[#3D2C24]">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium text-[#A89F91] hover:text-white transition">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                Keluar
            </a>
        </div>
    </aside>

    <!-- CONTENT RIGHT -->
    <main class="flex-1 p-8 overflow-y-auto">
        
        <!-- Header Top Bar -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-[#2A1E17]">Moderasi Review & Laporan</h1>
                <p class="text-xs text-[#8C827A] mt-1">Tinjau review masuk dan tindak laporan dari pengguna</p>
            </div>

            <!-- Profile Info Admin -->
            <div class="flex items-center gap-4">
                <button class="w-9 h-9 rounded-full bg-white border border-[#E5DEC9] flex items-center justify-center text-[#6E645E] hover:bg-[#FCF9F2] relative">
                    <i data-lucide="bell" class="w-4 h-4"></i>
                    <span class="w-2 h-2 rounded-full bg-red-500 absolute top-2 right-2"></span>
                </button>

                <div class="flex items-center gap-2.5 bg-white border border-[#E5DEC9] px-3 py-1.5 rounded-full">
                    <div class="w-7 h-7 rounded-full bg-[#3D2C24] text-white flex items-center justify-center font-bold text-xs">AD</div>
                    <div class="text-left">
                        <p class="text-xs font-bold leading-none">Admin Coffenfo</p>
                        <p class="text-[10px] text-[#8C827A]">Admin</p>
                    </div>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-[#8C827A]"></i>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white border border-[#E5DEC9] rounded-2xl p-5 flex items-center gap-4 shadow-xs">
                <div class="w-12 h-12 rounded-xl bg-[#FFF8E7] text-amber-700 flex items-center justify-center">
                    <i data-lucide="store" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs text-[#8C827A]">Total Cafe</p>
                    <h3 class="text-2xl font-bold text-[#2A1E17]">48</h3>
                    <span class="text-[10px] text-emerald-600 font-medium">+3 cafe bulan ini</span>
                </div>
            </div>

            <div class="bg-white border border-[#E5DEC9] rounded-2xl p-5 flex items-center gap-4 shadow-xs">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="message-square" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs text-[#8C827A]">Review Masuk</p>
                    <h3 class="text-2xl font-bold text-[#2A1E17]">320</h3>
                    <span class="text-[10px] text-emerald-600 font-medium">+42 minggu ini</span>
                </div>
            </div>

            <div class="bg-white border border-[#E5DEC9] rounded-2xl p-5 flex items-center gap-4 shadow-xs">
                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs text-[#8C827A]">Laporan Perlu Tindakan</p>
                    <h3 class="text-2xl font-bold text-red-600">5</h3>
                    <span class="text-[10px] text-red-500 font-medium">Segera tinjau</span>
                </div>
            </div>
        </div>

        <!-- FILTER & ACTION BAR -->
        <div class="bg-white border border-[#E5DEC9] rounded-2xl p-4 mb-6 space-y-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                
                <!-- Search Input -->
                <div class="relative w-full md:w-80">
                    <i data-lucide="search" class="w-4 h-4 text-[#8C827A] absolute left-3.5 top-3"></i>
                    <input type="text" placeholder="Cari pengguna atau cafe..." class="w-full bg-[#FCF9F2] border border-[#E5DEC9] pl-10 pr-4 py-2 rounded-xl text-xs focus:outline-none focus:border-[#3D2C24]">
                </div>

                <!-- Status Filter Pills -->
                <div class="flex flex-wrap items-center gap-2 text-xs w-full md:w-auto">
                    <button class="px-3 py-1.5 bg-[#3D2C24] text-white rounded-lg font-medium">Semua - 50</button>
                    <button class="px-3 py-1.5 bg-[#FCF9F2] border border-[#E5DEC9] text-[#6E645E] rounded-lg hover:text-[#3D2C24]">Menunggu - 18</button>
                    <button class="px-3 py-1.5 bg-[#FCF9F2] border border-[#E5DEC9] text-red-600 rounded-lg hover:bg-red-50">Dilaporkan - 5</button>
                    <button class="px-3 py-1.5 bg-[#FCF9F2] border border-[#E5DEC9] text-[#6E645E] rounded-lg hover:text-[#3D2C24]">Disetujui - 27</button>
                </div>

                <!-- Dropdown Select Filters -->
                <div class="flex items-center gap-2 text-xs">
                    <select class="bg-[#FCF9F2] border border-[#E5DEC9] px-3 py-2 rounded-xl text-[#3D2C24] focus:outline-none">
                        <option>Semua Cafe</option>
                        <option>Grind Now Coffee Bar</option>
                        <option>Lintas Coffee & Space</option>
                    </select>
                    <select class="bg-[#FCF9F2] border border-[#E5DEC9] px-3 py-2 rounded-xl text-[#3D2C24] focus:outline-none">
                        <option>30 hari terakhir</option>
                        <option>7 hari terakhir</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- TABLE MODERASI -->
        <div class="bg-white border border-[#E5DEC9] rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#FCF9F2] border-b border-[#E5DEC9] text-[11px] font-bold text-[#8C827A] uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-10 text-center"><input type="checkbox" class="rounded"></th>
                            <th class="py-3.5 px-4">Nama Pengguna</th>
                            <th class="py-3.5 px-4">Nama Cafe</th>
                            <th class="py-3.5 px-4 w-1/3">Isi Review</th>
                            <th class="py-3.5 px-4">Alasan Laporan</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5DEC9] text-xs text-[#3D2C24]">
                        
                        <!-- Row 1: Disetujui -->
                        <tr class="hover:bg-[#FCF9F2]/50 transition">
                            <td class="py-3.5 px-4 text-center"><input type="checkbox" class="rounded"></td>
                            <td class="py-3.5 px-4 font-bold">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-amber-700 text-white flex items-center justify-center text-[10px] font-bold">DP</div>
                                    Dinda Pratiwi
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium">Grind Now Coffee Bar</td>
                            <td class="py-3.5 px-4 text-[#6E645E]">Single origin Gayo-nya juara, aromanya wangi dan tidak pahit...</td>
                            <td class="py-3.5 px-4 text-[#8C827A]">--</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full text-[10px] font-bold">✓ Disetujui</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg"><i data-lucide="check-circle" class="w-4 h-4"></i></button>
                                    <button class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg"><i data-lucide="eye-off" class="w-4 h-4"></i></button>
                                    <button class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 2: Menunggu -->
                        <tr class="hover:bg-[#FCF9F2]/50 transition">
                            <td class="py-3.5 px-4 text-center"><input type="checkbox" class="rounded" checked></td>
                            <td class="py-3.5 px-4 font-bold">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-emerald-800 text-white flex items-center justify-center text-[10px] font-bold">BS</div>
                                    Budi Santoso
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium">Mula Patisserie</td>
                            <td class="py-3.5 px-4 text-[#6E645E]">Pelayanan lambat dan barista kurang ramah, pesanan salah sam...</td>
                            <td class="py-3.5 px-4 text-[#8C827A]">--</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-[10px] font-bold">● Menunggu Moderasi</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg"><i data-lucide="check-circle" class="w-4 h-4"></i></button>
                                    <button class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg"><i data-lucide="eye-off" class="w-4 h-4"></i></button>
                                    <button class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 3: Dilaporkan (Spam) -->
                        <tr class="hover:bg-[#FCF9F2]/50 transition bg-red-50/30">
                            <td class="py-3.5 px-4 text-center"><input type="checkbox" class="rounded" checked></td>
                            <td class="py-3.5 px-4 font-bold">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gray-600 text-white flex items-center justify-center text-[10px] font-bold">AN</div>
                                    Anonim_8821
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium">Pippo Coffee Roasters</td>
                            <td class="py-3.5 px-4 text-[#6E645E]">Tempat jelek! Kopi basi. Hubungi WA 0812-xxxx untuk promo dan d...</td>
                            <td class="py-3.5 px-4 text-red-600 font-semibold">🚩 Spam / promosi</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-red-100 text-red-800 px-2.5 py-1 rounded-full text-[10px] font-bold">● Dilaporkan</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg"><i data-lucide="check-circle" class="w-4 h-4"></i></button>
                                    <button class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg"><i data-lucide="eye-off" class="w-4 h-4"></i></button>
                                    <button class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 4: Disetujui -->
                        <tr class="hover:bg-[#FCF9F2]/50 transition">
                            <td class="py-3.5 px-4 text-center"><input type="checkbox" class="rounded"></td>
                            <td class="py-3.5 px-4 font-bold">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-800 text-white flex items-center justify-center text-[10px] font-bold">RH</div>
                                    Rizky Hakim
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium">Hoods Coffee</td>
                            <td class="py-3.5 px-4 text-[#6E645E]">Area self-brewing seru, barista mau ngajarin cara V60 yang benar...</td>
                            <td class="py-3.5 px-4 text-[#8C827A]">--</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full text-[10px] font-bold">✓ Disetujui</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg"><i data-lucide="check-circle" class="w-4 h-4"></i></button>
                                    <button class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg"><i data-lucide="eye-off" class="w-4 h-4"></i></button>
                                    <button class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Table Pagination Footer -->
            <div class="p-4 bg-[#FCF9F2] border-t border-[#E5DEC9] flex justify-between items-center text-xs text-[#8C827A]">
                <span>Menampilkan 1-10 dari 50 entri</span>
                <div class="flex items-center gap-1">
                    <button class="px-2.5 py-1 rounded-lg border border-[#E5DEC9] bg-white hover:bg-gray-50">&lt;</button>
                    <button class="px-2.5 py-1 rounded-lg bg-[#3D2C24] text-white font-bold">1</button>
                    <button class="px-2.5 py-1 rounded-lg border border-[#E5DEC9] bg-white hover:bg-gray-50">2</button>
                    <button class="px-2.5 py-1 rounded-lg border border-[#E5DEC9] bg-white hover:bg-gray-50">3</button>
                    <button class="px-2.5 py-1 rounded-lg border border-[#E5DEC9] bg-white hover:bg-gray-50">&gt;</button>
                </div>
            </div>
        </div>

    </main>

    <!-- Script Init Icon -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>