@extends('layouts.app')

@section('title', 'Grind Now Coffe Bar')

{{-- Header hijau khusus halaman detail --}}
@section('header')
<header class="hbar">
    <div class="wrap">
        <div class="h-row">
            <a href="{{ url('/') }}" class="logo"><img src="{{ asset('images/logo_icon.png') }}" alt="">coffenfo</a>
            <nav>
                <a href="{{ route('explore') }}" class="on">Jelajah Cafe</a>
                <a href="#" data-open="orders">Riwayat Pesanan</a>
                <span class="loc2"><svg class="icon"><use href="#i-pin"/></svg>Batam</span>
            </nav>
        </div>
        <div class="fine"><span>© 2026 Coffenfo • Dibuat untuk pecinta kopi.</span><span class="r"><a href="#">Kebijakan Privasi</a><a href="#">Butuh bantuan?</a></span></div>
        <div class="fine"><span>Halaman contoh • Cafe, ulasan, menu, dan gambar adalah konten ilustrasi.</span></div>
    </div>
</header>
@endsection

@section('content')
@php
    // ----- Data contoh. Nanti diganti data dari database. -----
    $cafe = [
        'slug' => 'grind-now', 'name' => 'Grind Now Coffe Bar', 'kind' => 'KOPI SPESIALTI • ROASTERY',
        'rating' => '4.9', 'reviews' => '120+', 'price' => '$$', 'area' => 'Batam Kota',
        'about' => 'Di era kopi serba cepat, Grind Now memilih arah sebaliknya : slow bar satu seduhan, satu perhatian penuh.',
        'know' => ['Jenis Beans' => 'Single Origin / Blend', 'Asal Beans' => 'Arabica', 'Tingkat Roasting' => 'Light, Medium, Dark', 'Metode seduh' => 'V60, Aeropress, Japanese style, espresso, dan cold brew'],
        'taste' => 'ringan, fruity, tea-like, seimbang.',
        'name_map' => 'Grind Now Coffee Bar', 'address' => 'Permata Regency, Blok DD No. 9, Baloi Indah, Batam',
        'hours' => 'Setiap hari • 08:30 – 24:00 WIB',
        // Koordinat contoh (sekitar Batam Center). Ganti dengan koordinat asli cafe:
        // di Google Maps klik kanan lokasi cafe -> klik angka koordinat -> salin.
        'lat' => 1.1301, 'lng' => 104.0529,
    ];
    $gallery = collect(range(1, 6))->map(fn ($n) => [
        'src' => file_exists(public_path("images/cafes/{$cafe['slug']}-{$n}.jpg")) ? asset("images/cafes/{$cafe['slug']}-{$n}.jpg") : null,
        'bg'  => ['#6b4a35', '#8a6a55', '#a3845f', '#7d5a42', '#9a7b62', '#6f4f3a'][$n - 1],
    ])->all();

    // [nama, deskripsi, harga, kategori, slug foto, warna cadangan]
    $menu = [
        ['Manual Brew in House Beans', 'Signature house selection designed for balance clarity', 45000, 'Kopi',     'manual-brew', '#6b4a35'],
        ['Magic Potion',               'Iced Latte W Unique Berry Hint',                          40000, 'Kopi',     'magic-potion', '#7d5a42'],
        ['Jungle Mint',                'Combination From Mango Juice W Matcha',                   44000, 'Non-kopi', 'jungle-mint', '#7a8a55'],
        ['Salted Egg Chicken',         'Rice Bowl',                                               45000, 'Makanan',  'salted-egg', '#a3845f'],
        ['Meatballs Bolognese',        'All Day Food',                                            46000, 'Makanan',  'meatballs', '#8a6a55'],
        ['Magic',                      'Double ristretto w custom milk (5oz) Hot only',           45000, 'Kopi',     'magic', '#9a7b62'],
    ];
    $cats = ['Semua', 'Kopi', 'Non-kopi', 'Makanan'];

    $facilities = [['Wi-Fi gratis', 'wifi'], ['Stopkontak', 'plug'], ['Ruangan ber-AC', 'snow'], ['Area luar', 'leaf'], ['Parkir tersedia', 'car'], ['Nyaman untuk kerja', 'laptop']];

    $reviews = [
        ['Nadia P.', 'NP', '4 Oktober 2026', 'Kopi susunya pas, tidak terlalu manis. Suka suasana hangatnya untuk ngobrol sore. Croissant-nya juga renyah!'],
        ['Raka A.',  'RA', '2 Oktober 2026', 'V60 Flores-nya wangi dan bersih. Barista membantu pilih biji sesuai selera. Ada stopkontak untuk kerja santai.'],
    ];

    // [nama, slug foto, rating, area, harga, tag, warna cadangan]
    $similar = [
        ['Temu Kopi',          'temu-kopi',   '4.8', 'Batam Kota', '$$', 'Kopi spesialti',    '#a3845f'],
        ['Ruang Seduh',        'ruang-seduh', '4.7', 'Lubuk Baja', '$$', 'Nyaman untuk kerja', '#8a6a55'],
        ['Senja Coffee House', 'senja',       '4.8', 'Sekupang',   '$$', 'Suasana santai',    '#6f8a55'],
    ];
@endphp

<nav class="crumb"><svg class="icon"><use href="#i-home"/></svg><a href="{{ route('explore') }}">Jelajah Cafe</a><svg class="icon"><use href="#i-right"/></svg><b>{{ $cafe['name'] }}</b></nav>

{{-- ===== Galeri: foto asli dibaca dari public/images/cafes/grind-now-1.jpg sampai -6.jpg ===== --}}
<div class="gallery" id="gallery">
    <div class="ph"><svg class="icon cup"><use href="#i-cup"/></svg>
        <div class="g-count"><span id="gn">01 / 06</span><span class="dots">@foreach ($gallery as $g)<i></i>@endforeach</span></div>
    </div>
    <div class="ph"><svg class="icon cup"><use href="#i-cup"/></svg>
        <div class="g-act">
            <button class="chipbtn" type="button" id="save"><svg class="icon"><use href="#i-bookmark"/></svg>Simpan</button>
            <button class="chipbtn" type="button"><svg class="icon"><use href="#i-share"/></svg>Bagikan</button>
        </div>
    </div>
    <div class="ph"><svg class="icon cup"><use href="#i-cup"/></svg>
        <div class="g-nav">
            <button class="round" type="button" id="prev" aria-label="Foto sebelumnya"><svg class="icon"><use href="#i-left"/></svg></button>
            <button class="round" type="button" id="next" aria-label="Foto berikutnya"><svg class="icon"><use href="#i-right"/></svg></button>
        </div>
    </div>
</div>

{{-- ===== Judul ===== --}}
<div class="d-head">
    <div>
        <div class="eyebrow">{{ $cafe['kind'] }}</div>
        <h1>{{ $cafe['name'] }}</h1>
        <div class="meta">
            <span class="score"><svg class="icon"><use href="#i-star-o"/></svg>{{ $cafe['rating'] }}</span>
            <span>{{ $cafe['reviews'] }} ulasan</span>
            <b>{{ $cafe['price'] }}</b>
            <span class="p"><svg class="icon"><use href="#i-pin"/></svg>{{ $cafe['area'] }}</span>
        </div>
    </div>
    <div class="d-act">
        <span class="status">Buka • 09:30 - 24:00 WIB</span>
        <a href="{{ route('checkout') }}" class="btn-o" data-open="cart"><svg class="icon"><use href="#i-cart"/></svg>Pesanan</a>
    </div>
</div>
<p class="desc">{{ $cafe['about'] }}</p>
<hr class="rule">

<div class="detail">
    {{-- ===== Kolom kiri ===== --}}
    <div>
        <section class="know">
            <h3><svg class="icon"><use href="#i-bean"/></svg>Kenali kopimu</h3>
            <dl>@foreach ($cafe['know'] as $k => $v)<dt>{{ $k }}</dt><dd>{{ $v }}</dd>@endforeach</dl>
            <p>Cita rasa: {{ $cafe['taste'] }}</p>
        </section>

        <div class="tabs-d" id="tabs" role="tablist">
            <button type="button" role="tab" class="on" data-tab="menu" aria-selected="true">Katalog Menu</button>
            <button type="button" role="tab" data-tab="ulasan" aria-selected="false">Review &amp; Rating</button>
            <button type="button" role="tab" data-tab="fas" aria-selected="false">Fasilitas</button>
        </div>

        <section class="d-sec" id="menu">
            <h2 class="serif">Dari bar kopi &amp; dapur</h2>
            <p class="sub">Pilihan favorit untuk menemani ceritamu.</p>
            <div class="pills">@foreach ($cats as $c)<button type="button" class="pill {{ $loop->first ? 'on' : '' }}" data-cat="{{ $c }}">{{ $c }}</button>@endforeach</div>
            <div class="menu-grid">
                @foreach ($menu as [$name, $desc, $price, $cat, $slug, $bg])
                    <article class="menu-card" data-cat="{{ $cat }}">
                        <div class="ph {{ file_exists(public_path("images/menu/$slug.jpg")) ? 'has' : '' }}" style="background:{{ $bg }}">
                            @if (file_exists(public_path("images/menu/$slug.jpg")))<img src="{{ asset("images/menu/$slug.jpg") }}" alt="{{ $name }}">@endif
                            <svg class="icon cup"><use href="#i-cup"/></svg>
                        </div>
                        <div class="t"><strong>{{ $name }}</strong><small>{{ $desc }}</small><b>Rp{{ number_format($price, 0, ',', '.') }}</b></div>
                    </article>
                @endforeach
            </div>
            <p class="note-s">Harga contoh per porsi dalam Rupiah. Ketersediaan &amp; harga dapat berubah.</p>
        </section>

        <section class="d-sec" id="ulasan">
            <div class="rv-top"><h2 class="serif" style="font-size:18px">Review &amp; Rating</h2><a href="{{ route('reviews') }}">Tulis ulasan</a></div>
            <div class="rv-sum"><div class="big">{{ $cafe['rating'] }}</div><div><div class="stars">★★★★★</div><small>Dari {{ $cafe['reviews'] }} ulasan pecinta kopi</small></div></div>
            @foreach ($reviews as [$who, $ini, $when, $text])
                <div class="rv"><div class="av">{{ $ini }}</div><div><strong>{{ $who }}</strong><small>{{ $when }}</small></div><span class="sc">★ 5.0</span><p>{{ $text }}</p></div>
            @endforeach
            <a href="{{ route('reviews') }}" class="all">Lihat semua 128 ulasan →</a>
        </section>
    <section class="d-sec" id="fas" hidden>
            <h2 class="serif">Fasilitas</h2>
            <p class="sub">Yang bisa kamu nikmati di {{ $cafe['name'] }}.</p>
            <div class="fac-grid">@foreach ($facilities as [$label, $icon])<div><span class="ic"><svg class="icon"><use href="#i-{{ $icon }}"/></svg></span>{{ $label }}</div>@endforeach</div>
        </section>
    </div>

    {{-- ===== Kolom kanan ===== --}}
    <aside class="aside">
        <section class="box">
            <h3 class="serif">Peta &amp; Lokasi</h3>
            <style>
                /* Gaya khusus halaman ini */
                .menu-card .ph{aspect-ratio:2.1/1}   /* foto menu lebih lebar, seperti Figma */
                .tabs-d{position:sticky;top:10px;z-index:5;box-shadow:0 6px 16px rgba(59,38,32,.07)}   /* tab ikut menempel saat scroll */
                .tabs-d a{transition:background .15s,color .15s}
                /* Content tabs: persis seperti di Figma */
                .tabs-d{background:#f5f0e6;padding:5px;gap:6px}
                .tabs-d button{display:flex;align-items:center;justify-content:center;gap:7px;border:1px solid transparent;background:none;border-radius:8px;padding:10px 0;font:500 11px 'Plus Jakarta Sans',sans-serif;color:#5b4d42;cursor:pointer;transition:background .15s,color .15s,border-color .15s}
                .tabs-d button:hover{color:var(--ink)}
                .tabs-d button.on{background:#fffdf8;border-color:#eadfc9;color:var(--ink);font-weight:700;box-shadow:0 1px 3px rgba(59,38,32,.08)}
                .tabs-d button.on::before{content:"";width:5px;height:5px;border-radius:50%;background:#d98a4b}
                .tabs-d button:focus-visible{outline:2px solid var(--ink);outline-offset:2px}
                .d-sec[hidden]{display:none}
                .fac-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
                .fac-grid div{display:flex;align-items:center;gap:10px;background:#fff;border:1px solid var(--line);border-radius:10px;padding:12px;font-size:11px}
                .fac-grid .ic{width:30px;height:30px;border-radius:8px;background:#f4ede2;display:grid;place-items:center;font-size:15px;color:#5b4d42;flex:none}
                @media(max-width:560px){.fac-grid{grid-template-columns:1fr 1fr}}
                /* Tombol simpan: biasa = kotak putih, tersimpan = bulat cokelat (seperti Figma) */
                .sim-card .bm{cursor:pointer;transition:background .15s,color .15s}
                .sim-card .bm.on{background:#3b2620;color:#fff;border-radius:50%}
                .sim-card .bm.on svg,.chipbtn.on svg{fill:currentColor}
                .chipbtn.on{background:#3b2620;color:#fff}
                /* Peta Google Maps */
                .box .map{height:200px;border-radius:10px;overflow:hidden;background:#eef0e0}
                .box .map iframe{display:block;width:100%;height:100%;border:0}
            </style>
            <div class="map">
                {{-- Google Maps tanpa API key. Bisa digeser dan di-zoom seperti Google Maps biasa. --}}
                <iframe title="Peta lokasi {{ $cafe['name_map'] }}" loading="lazy" allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q={{ $cafe['lat'] }},{{ $cafe['lng'] }}&z=16&hl=id&output=embed"></iframe>
            </div>
            <strong>{{ $cafe['name_map'] }}</strong>
            <p class="addr-d">{{ $cafe['address'] }}</p>
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $cafe['lat'] }},{{ $cafe['lng'] }}" target="_blank" rel="noopener" class="btn-dark"><svg class="icon"><use href="#i-nav"/></svg>Petunjuk Arah</a>
            <div class="hours"><svg class="icon"><use href="#i-clock"/></svg><div><b>Jam operasional</b><small>{{ $cafe['hours'] }}</small></div></div>
        </section>

        <div id="fasilitas-side">
            <h3 class="serif fac-t">Fasilitas</h3>
            <div class="fac" style="margin-top:20px">@foreach ($facilities as [$label, $icon])<div><span class="ic"><svg class="icon"><use href="#i-{{ $icon }}"/></svg></span>{{ $label }}</div>@endforeach</div>
        </div>
    </aside>
</div>

{{-- ===== Cafe serupa ===== --}}
<div class="sim-head">
    <div><h2 class="serif">Cafe Serupa yang Mungkin Anda Suka</h2><p>Tempat baru, cerita berikutnya.</p></div>
    <div class="arrows"><button class="round" type="button" aria-label="Sebelumnya"><svg class="icon"><use href="#i-left"/></svg></button><button class="round" type="button" aria-label="Berikutnya"><svg class="icon"><use href="#i-right"/></svg></button></div>
</div>
<div class="sim">
    @foreach ($similar as [$name, $slug, $rate, $area, $price, $tag, $bg])
        <a href="{{ route('dashboard') }}" class="sim-card">
            <div class="ph {{ file_exists(public_path("images/cafes/$slug.jpg")) ? 'has' : '' }}" style="background:{{ $bg }}">
                @if (file_exists(public_path("images/cafes/$slug.jpg")))<img src="{{ asset("images/cafes/$slug.jpg") }}" alt="{{ $name }}">@endif
                <svg class="icon cup"><use href="#i-cup"/></svg>
                <span class="bm" role="button" tabindex="0" aria-label="Simpan cafe"><svg class="icon"><use href="#i-bookmark"/></svg></span>
            </div>
            <div class="t"><div class="r1"><strong>{{ $name }}</strong><span>★ {{ $rate }}</span></div><small>{{ $area }} • {{ $price }}</small><em>{{ $tag }}</em></div>
        </a>
    @endforeach
</div>
{{-- Laci Keranjang & Riwayat Pesanan (komponen: resources/views/components/drawers.blade.php) --}}
<x-drawers />

{{-- Skrip ditaruh di sini (bukan @push) supaya pasti ikut tampil di layout apa pun --}}
<script id="coffenfo-js">
    // --- Galeri: 3 foto tampil sekaligus, tombol panah menggeser ---
    const G = @json($gallery);
    const slots = document.querySelectorAll('#gallery > .ph');
    const dots  = document.querySelectorAll('#gallery .dots i');
    let i = 0;
    function show() {
        slots.forEach((el, k) => {
            const g = G[(i + k) % G.length];
            el.style.background = g.src ? `url(${g.src}) center/cover` : g.bg;
            el.classList.toggle('has', !!g.src);
        });
        document.getElementById('gn').textContent = String(i + 1).padStart(2, '0') + ' / ' + String(G.length).padStart(2, '0');
        dots.forEach((d, n) => d.classList.toggle('on', n === i));
    }
    document.getElementById('prev').onclick = () => { i = (i - 1 + G.length) % G.length; show(); };
    document.getElementById('next').onclick = () => { i = (i + 1) % G.length; show(); };
    show();

    // --- Filter menu per kategori ---
    const pills = document.querySelectorAll('.pill');
    pills.forEach(p => p.addEventListener('click', () => {
        pills.forEach(x => x.classList.toggle('on', x === p));
        document.querySelectorAll('.menu-card').forEach(c => c.hidden = p.dataset.cat !== 'Semua' && c.dataset.cat !== p.dataset.cat);
    }));

    // --- Content tabs: klik tab = tampilkan panelnya ---
    const tabs   = [...document.querySelectorAll('#tabs button')];
    const panels = { menu: document.getElementById('menu'), ulasan: document.getElementById('ulasan'), fas: document.getElementById('fas') };

    function openTab(name) {
        Object.entries(panels).forEach(([key, el]) => el.hidden = key !== name);
        tabs.forEach(b => {
            const on = b.dataset.tab === name;
            b.classList.toggle('on', on);
            b.setAttribute('aria-selected', on);
        });
    }
    tabs.forEach(b => b.addEventListener('click', () => openTab(b.dataset.tab)));

    // Bisa dibuka langsung dari alamat: /dashboard#ulasan atau /dashboard#fas
    const fromHash = location.hash.slice(1);
    if (panels[fromHash]) openTab(fromHash);

    // --- Tombol simpan di kartu cafe serupa ---
    document.querySelectorAll('.bm').forEach(b => {
        const toggle = e => { e.preventDefault(); e.stopPropagation(); b.classList.toggle('on'); };
        b.addEventListener('click', toggle);
        b.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') toggle(e); });
    });

    // --- Tombol Simpan ---
    document.getElementById('save').addEventListener('click', e => e.currentTarget.classList.toggle('on'));
</script>
@endsection