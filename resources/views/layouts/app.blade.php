<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Coffenfo') - Coffenfo</title>
    <link rel="icon" href="{{ asset('images/logo_icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>

{{-- Ikon SVG (dipakai lewat <use href="#nama">) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-11.5A7 7 0 005 9.5C5 14.8 12 21 12 21z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="9.5" r="2.5" fill="none" stroke="currentColor" stroke-width="2"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24"><path d="M12 3l2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.5l6.1-.9z" fill="currentColor"/></symbol>
    <symbol id="i-heart" viewBox="0 0 24 24"><path d="M12 20s-7.5-4.6-7.5-10.2A4.3 4.3 0 0112 7.6a4.3 4.3 0 017.5 2.2C19.5 15.4 12 20 12 20z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></symbol>
    <symbol id="i-down" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="i-right" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="i-bean" viewBox="0 0 24 24"><ellipse cx="12" cy="12" rx="6.5" ry="10" transform="rotate(35 12 12)" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8 17c4-2 5-6 8-10" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></symbol>
    <symbol id="i-cup" viewBox="0 0 24 24"><path d="M4 9h13v5a5 5 0 01-5 5H9a5 5 0 01-5-5V9zM17 10h1.5a2.5 2.5 0 010 5H17M8 3v3M12 3v3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="i-wifi" viewBox="0 0 24 24"><path d="M3 9a14 14 0 0118 0M6 12.5a9.5 9.5 0 0112 0M9 16a5 5 0 016 0" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/><circle cx="12" cy="19.5" r="1.3" fill="currentColor"/></symbol>
</svg>

<header class="nav">
    <div class="wrap">
        <a href="{{ url('/') }}" class="logo"><img src="{{ asset('images/logo_icon.png') }}" alt="">Coffenfo</a>

        <form class="search" action="{{ route('explore') }}" method="get">
            <svg class="icon"><use href="#i-search"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari cafe, lokasi, atau menu...">
        </form>

        <button class="loc" type="button"><svg class="icon"><use href="#i-pin"/></svg>Batam Center<svg class="icon"><use href="#i-down"/></svg></button>
        <div class="avatar">{{ auth()->check() ? strtoupper(mb_substr(auth()->user()->name, 0, 2)) : 'AK' }}</div>
    </div>
</header>

<main class="page">
    <div class="wrap">
        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>