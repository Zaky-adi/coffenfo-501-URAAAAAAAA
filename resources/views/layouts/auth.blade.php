<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Coffenfo</title>
    <link rel="icon" href="{{ asset('images/logo_icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

{{-- Ikon SVG --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-bean" viewBox="0 0 24 24"><ellipse cx="12" cy="12" rx="6.5" ry="10" transform="rotate(35 12 12)" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M8 17c4-2 5-6 8-10" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-11.5A7 7 0 005 9.5C5 14.8 12 21 12 21z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="9.5" r="2.5" fill="none" stroke="currentColor" stroke-width="2"/></symbol>
    <symbol id="i-heart" viewBox="0 0 24 24"><path d="M12 20s-7.5-4.6-7.5-10.2A4.3 4.3 0 0112 7.6a4.3 4.3 0 017.5 2.2C19.5 15.4 12 20 12 20z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24"><path d="M2 12s3.6-6.5 10-6.5S22 12 22 12s-3.6 6.5-10 6.5S2 12 2 12z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1.8"/></symbol>
    <symbol id="i-eye-off" viewBox="0 0 24 24"><path d="M2 12s3.6-6.5 10-6.5S22 12 22 12s-3.6 6.5-10 6.5S2 12 2 12z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M4 4l16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#f7dcc8"/><path d="M7.5 12.3l3 3 6-6.6" fill="none" stroke="#cd7f3a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
</svg>

<main class="shell">

    {{-- Panel kiri: halaman anak boleh menggantinya lewat @section('left') --}}
    @hasSection('left')
        @yield('left')
    @else
    <section class="left">
        <a href="{{ url('/') }}" class="logo"><span><img src="{{ asset('images/logo_icon.png') }}" alt=""></span>Coffenfo</a>

        <div class="eyebrow"><svg class="icon"><use href="#i-pin"/></svg>DARI BATAM, UNTUK PECINTA KOPI</div>
        <h1>Temukan &amp; Review Cafe Favoritmu di Batam</h1>
        <p>Dari tegukan pertama hingga tempat langganan. Temukan cerita kopi berikutnya bersama Coffenfo.</p>

        <div class="art">
            <svg viewBox="0 0 300 230" fill="none" stroke="#3b2620" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round">
                <circle cx="150" cy="130" r="82" fill="#f3dfca" stroke="none"/>
                <rect x="72" y="104" width="156" height="104" fill="#fffdf8"/>
                <path d="M62 104l12-36h152l12 36z" fill="#fffdf8"/>
                <path d="M80 68l-4 36M104 68l-2 36M128 68v36M152 68v36M176 68v36M200 68l2 36M224 68l4 36"/>
                <path d="M62 104q9 10 18 0q9 10 18 0q9 10 18 0q9 10 18 0q9 10 18 0q9 10 18 0q9 10 18 0q9 10 18 0q9 10 18 0" fill="#fffdf8"/>
                <text x="150" y="62" text-anchor="middle" font-size="8" font-weight="700" fill="#3b2620" stroke="none" font-family="Plus Jakarta Sans,sans-serif">COFFENFO</text>
                <rect x="86" y="122" width="62" height="56" rx="3"/><path d="M86 150h62M117 122v56"/>
                <rect x="166" y="122" width="40" height="86"/>
                <path d="M104 160h26v10a6 6 0 01-6 6h-14a6 6 0 01-6-6z" fill="#f3dfca"/>
                <use href="#i-bean" x="26" y="52" width="24" height="24" color="#3b2620"/>
                <use href="#i-bean" x="252" y="62" width="24" height="24" color="#3b2620"/>
                <use href="#i-bean" x="40" y="196" width="22" height="22" color="#3b2620"/>
                <use href="#i-bean" x="244" y="190" width="22" height="22" color="#3b2620"/>
                <path d="M24 130h16l-2 30H26z"/><path d="M262 140h16l-2 30h-12z"/>
            </svg>
        </div>

        <div class="tagline"><svg class="icon"><use href="#i-heart"/></svg>Tempat kopi bertemu cerita</div>
    </section>

    @endif

    {{-- Panel kanan: tab + isi halaman --}}
    <section class="right">
        @if (! View::hasSection('no_tabs'))
            <nav class="tabs">
            <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'on' : '' }}">Masuk</a>
            <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'on' : '' }}">Daftar</a>
        </nav>
        @endif

        @yield('content')
    </section>
</main>

<p class="copy">@yield('copy', '© 2026 Coffenfo. Dibuat untuk pecinta kopi.')</p>

<script>
    // Tombol mata: tampilkan / sembunyikan kata sandi (dipakai di login & register)
    document.querySelectorAll('.eye').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.classList.toggle('show', show);
        });
    });
</script>
</body>
</html>