@php
    // Data contoh. Nanti pindahkan ke controller / database.
    $chips = ['Single origin', 'V60', 'Cold brew', 'Nyaman untuk kerja', 'Ada stopkontak'];
    $strip = ['Single origin', 'V60', 'Espresso', 'Cold brew', 'Cafe tenang', 'Colokan tersedia', 'Susu alternatif'];
    $steps = [
        ['Cari cafe',      'Filter lewat jenis beans, metode seduh, dan suasana yang kamu mau.'],
        ['Pesan dari menu', 'Lihat menu cafe dan pesan langsung dari halamannya.'],
        ['Tulis ulasan',   'Bagikan pengalamanmu supaya pecinta kopi lain tidak salah pilih.'],
    ];
    $cafes = [
        ['name' => 'Grind Now Coffe Bar',   'area' => 'Batam Kota',  'rating' => 4.9, 'reviews' => 120, 'tags' => ['V60', 'Single origin'], 'img' => 'cafe-1.jpg', 'bg' => '#6b4a35'],
        ['name' => 'Meilleur Cafe & Roastery', 'area' => 'Nagoya',   'rating' => 4.8, 'reviews' => 86,  'tags' => ['Espresso', 'Stopkontak'], 'img' => 'cafe-2.jpg', 'bg' => '#8a6a55'],
        ['name' => 'Mula Patisserie',       'area' => 'Sekupang',    'rating' => 4.7, 'reviews' => 64,  'tags' => ['Cold brew', 'Dessert'], 'img' => 'cafe-3.jpg', 'bg' => '#a3845f'],
    ];
    $quotes = [
        ['Aku nemu tempat V60 favoritku lewat Coffenfo. Filter beans-nya benar-benar membantu.', 'Rina · Pekerja remote'],
        ['Ulasannya jujur dan detail, jadi tidak salah pilih cafe untuk kerja seharian.', 'Dimas · Mahasiswa'],
    ];
    $loginUrl = route('role.select');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coffenfo - Temukan & review cafe favoritmu di Batam</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>

{{-- Ikon SVG (dipakai berulang lewat <use>) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-bean" viewBox="0 0 24 24"><ellipse cx="12" cy="12" rx="6.5" ry="10" transform="rotate(35 12 12)" fill="currentColor"/><path d="M8 17c4-2 5-6 8-10" stroke="#fff" stroke-width="1.4" fill="none" stroke-linecap="round"/></symbol>
    <symbol id="i-bean-o" viewBox="0 0 24 24"><ellipse cx="12" cy="12" rx="6.5" ry="10" transform="rotate(35 12 12)" fill="none" stroke="currentColor" stroke-width="1.3"/><path d="M8 17c4-2 5-6 8-10" stroke="currentColor" stroke-width="1.3" fill="none" stroke-linecap="round"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M7.5 12.5l3 3 6-6.5" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="i-cup" viewBox="0 0 24 24"><path d="M4 9h13v5a5 5 0 01-5 5H9a5 5 0 01-5-5V9zM17 10h1.5a2.5 2.5 0 010 5H17M8 3v3M12 3v3" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/></symbol>
</svg>

{{-- Navbar --}}
<header class="nav">
    <div class="wrap">
        <a href="/" class="logo"><i>c</i>Coffenfo</a>
        <nav class="links">
            <a href="#">Jelajahi cafe</a>
            <a href="#">Cafe tersimpan</a>
            <a href="#mitra">Kemitraan</a>
        </nav>
        <a href="{{ $loginUrl }}" class="btn">Masuk</a>
    </div>
</header>

{{-- Hero --}}
<section class="hero">
    <svg class="icon bean" style="top:28px;left:34px"  ><use href="#i-bean-o"/></svg>
    <svg class="icon bean" style="top:34px;right:40px;width:30px"><use href="#i-bean-o"/></svg>
    <svg class="icon bean" style="bottom:22px;left:340px;width:22px"><use href="#i-bean-o"/></svg>

    <div class="wrap">
        <div>
            <div class="eyebrow">DARI BATAM, UNTUK PECINTA KOPI</div>
            <h1>Temukan &amp; review cafe favoritmu di Batam</h1>
            <p>Dari tegukan pertama hingga tempat langganan. Cari cafe lewat jenis beans, metode seduh, dan suasana, lalu pesan dan tulis ulasanmu.</p>
            <div class="chips">
                @foreach ($chips as $c)<span class="chip">{{ $c }}</span>@endforeach
            </div>
        </div>

        <div class="art">
            <div class="badge rating"><b>★ 4.9</b> 120+ ulasan</div>
            <div class="badge open"><span class="dot"></span><b>Jam Buka</b> 09:30–24:00 WIB</div>
            <svg class="shop" viewBox="0 0 320 210" fill="none" stroke="#2e1d14" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round">
                <rect x="20" y="72" width="160" height="128" fill="#fff"/>
                <path d="M10 72l14-40h152l14 40z" fill="#fff"/>
                <path d="M36 32l-5 40M62 32l-2 40M88 32v40M114 32l2 40M140 32l4 40M166 32l7 40"/>
                <path d="M10 72q10 12 20 0q10 12 20 0q10 12 20 0q10 12 20 0q10 12 20 0q10 12 20 0q10 12 20 0q10 12 20 0q10 12 20 0" fill="#fff"/>
                <rect x="36" y="104" width="58" height="52" rx="3"/>
                <rect x="112" y="104" width="52" height="96"/>
                <circle cx="156" cy="152" r="2" fill="#2e1d14"/>
                <path d="M222 74h70l-8 126h-54z" fill="#fff"/>
                <rect x="216" y="58" width="82" height="16" rx="4" fill="#2e1d14"/>
                <path d="M227 116h60l-2 40h-56z" fill="#ecd6aa"/>
                <path d="M242 46c-7-8 7-12 0-22M262 46c-7-8 7-12 0-22"/>
            </svg>
        </div>
    </div>
</section>

{{-- Strip cokelat --}}
<div class="strip"><div class="wrap">@foreach ($strip as $s)<span>{{ $s }}</span>@endforeach</div></div>

{{-- Cara kerja --}}
<section class="block">
    <div class="wrap">
        <h2>Cara kerja Coffenfo</h2>
        <p class="sub">Tiga langkah menemukan cafe yang pas untukmu.</p>
        <div class="steps">
            @foreach ($steps as [$title, $text])
                <div class="step"><small>Langkah {{ $loop->iteration }}</small><h3>{{ $title }}</h3><p>{{ $text }}</p></div>
            @endforeach
        </div>
    </div>
</section>

{{-- Cafe pilihan --}}
<section class="block alt">
    <div class="wrap">
        <div class="sechead">
            <div><h2>Cafe pilihan minggu ini</h2><p class="sub">Dipilih dari ulasan terbaik pengunjung Batam.</p></div>
            <a href="#">Lihat semua cafe</a>
        </div>
        <div class="cards">
            @foreach ($cafes as $cafe)
                <article class="cafe">
                    {{-- Ganti div ini dengan <img src="{{ asset('images/nama-foto.jpg') }}"> kalau foto sudah ada --}}
                    @if (file_exists(public_path('images/'.$cafe['img'])))
                        <img class="photo" src="{{ asset('images/'.$cafe['img']) }}" alt="{{ $cafe['name'] }}">
                    @else
                        <div class="photo" style="background:{{ $cafe['bg'] }}"><svg class="icon"><use href="#i-cup"/></svg></div>
                    @endif
                    <div class="body">
                        <h3>{{ $cafe['name'] }}</h3>
                        <div class="meta"><span>{{ $cafe['area'] }}</span><span><span class="star">★ {{ $cafe['rating'] }}</span> ({{ $cafe['reviews'] }})</span></div>
                        @foreach ($cafe['tags'] as $t)<span class="tag">{{ $t }}</span>@endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimoni --}}
<section class="block">
    <div class="wrap">
        <h2>Kata pecinta kopi</h2>
        <p class="sub">Cerita dari pengguna Coffenfo.</p>
        <div class="quotes">
            @foreach ($quotes as [$text, $who])
                <div class="quote"><p>“{{ $text }}”</p><small>{{ $who }}</small></div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA mitra --}}
<section class="block" id="mitra" style="padding-top:0">
    <div class="wrap">
        <div class="cta">
            <svg class="icon deco" style="left:18px;bottom:10px;width:92px;transform:rotate(-20deg)"><use href="#i-bean-o"/></svg>
            <svg class="icon deco" style="left:96px;bottom:-14px;width:64px;transform:rotate(25deg)"><use href="#i-bean-o"/></svg>
            <svg class="icon deco" style="right:26px;top:14px;width:46px;transform:rotate(30deg)"><use href="#i-bean-o"/></svg>
            <div>
                <h2>Punya cafe di Batam? Jadi mitra Coffenfo</h2>
                <p>Perkenalkan cafe-mu ke ribuan pecinta kopi dan dapatkan ulasan yang jujur.</p>
                <ul class="checks">
                    <li><svg class="icon"><use href="#i-check"/></svg><div><b>Profil cafe lengkap</b><span>Tampilkan menu, beans, dan jam buka.</span></div></li>
                    <li><svg class="icon"><use href="#i-check"/></svg><div><b>Terima pesanan online</b><span>Pelanggan bisa pesan dari halaman cafe-mu.</span></div></li>
                    <li><svg class="icon"><use href="#i-check"/></svg><div><b>Kelola ulasan pelanggan</b><span>Balas dan pantau ulasan dalam satu tempat.</span></div></li>
                </ul>
            </div>

            <form class="card" method="post" action="#">
                @csrf
                <h3>Ajukan kemitraan</h3>
                <label>Nama cafe<input type="text" name="cafe_name" placeholder="Contoh: Temu Kopi"></label>
                <label>Nama pemilik<input type="text" name="owner" placeholder="Nama lengkap"></label>
                <label>Nomor WhatsApp<input type="tel" name="phone" placeholder="08xx xxxx xxxx"></label>
                <label>Kecamatan
                    <select name="district"><option>Batam Kota</option><option>Nagoya</option><option>Sekupang</option><option>Lubuk Baja</option><option>Batu Ampar</option></select>
                </label>
                <button class="btn" type="submit">Ajukan kemitraan</button>
                <small>Tim kami akan menghubungimu dalam 2 hari kerja.</small>
            </form>
        </div>
    </div>
</section>

<footer><div class="wrap"><span>© 2026 Coffenfo. Dibuat di Batam.</span><span>Kebijakan privasi · Syarat layanan</span></div></footer>

</body>
</html>