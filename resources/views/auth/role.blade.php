@php
    // Daftar peran. Mau tambah peran baru? Tambah satu baris di sini.
    $roles = [
        ['value' => 'user',  'title' => 'Pengguna',         'desc' => 'Cari cafe, pesan menu, dan tulis review', 'icon' => 'i-user'],
        ['value' => 'owner', 'title' => 'Pemilik cafe (mitra)', 'desc' => 'Kelola profil cafe, menu, dan pesanan masuk', 'icon' => 'i-store'],
        ['value' => 'admin', 'title' => 'Admin Coffenfo',   'desc' => 'Mengatur konfigurasi sistem, mengelola data, dan menjaga operasional platform.', 'icon' => 'i-shield'],
    ];
    $loginUrl = Route::has('login') ? route('login') : '#';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih peran - Coffenfo</title>
    <link rel="icon" href="{{ asset('images/logo_icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/role.css') }}">
</head>
<body>

{{-- Ikon SVG --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    <symbol id="i-store" viewBox="0 0 24 24"><path d="M4 9l1.5-5h13L20 9M4 9h16M4 9a2.7 2.7 0 005.3 0 2.7 2.7 0 005.4 0A2.7 2.7 0 0020 9M5.5 12v8h13v-8M10 20v-5h4v5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24"><path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
</svg>

<main class="panel">
    {{-- GET: pilihan peran dikirim ke halaman login sebagai ?role=... --}}
    <form method="get" action="{{ $loginUrl }}">
        <h1>Masuk sebagai siapa?</h1>
        <p>Pilih peranmu untuk melanjutkan ke Coffenfo.</p>

        <div class="roles">
            @foreach ($roles as $r)
                <label class="role">
                    <input type="radio" name="role" value="{{ $r['value'] }}" @checked($loop->first)>
                    <span class="ico"><svg><use href="#{{ $r['icon'] }}"/></svg></span>
                    <span class="tick"></span>
                    <strong>{{ $r['title'] }}</strong>
                    <small>{{ $r['desc'] }}</small>
                </label>
            @endforeach
        </div>

        <button class="btn" type="submit">Lanjutkan →</button>
    </form>

    <p class="note">Punya cafe tapi belum jadi mitra? <a href="{{ url('/') }}#mitra">Ajukan kemitraan</a></p>
</main>

</body>
</html>