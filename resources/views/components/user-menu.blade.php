{{--
    Menu pengguna + logout.
    Simpan sebagai: resources/views/components/user-menu.blade.php
    Pakai di layout / profil dengan:  <x-user-menu />
    (ganti chip "Tamu / Pengguna" yang lama dengan tag di atas)
--}}
@php
    $u          = auth()->user();
    $name       = $u->name ?? 'Tamu';
    $sub        = $u->email ?? ($u->username ?? 'Belum masuk');
    $roleLabel  = (($u->role ?? null) === 'admin') ? 'Admin' : 'Pengguna';
    $logoutUrl  = Route::has('logout') ? route('logout') : url('/logout');
    $loginUrl   = Route::has('role.select') ? route('role.select') : url('/login');
@endphp

<div class="um">
    <input type="checkbox" id="umToggle" class="um-toggle">

    {{-- Chip di pojok kanan atas --}}
    <label for="umToggle" class="um-btn" aria-label="Menu pengguna">
        <span class="um-avatar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/></svg>
        </span>
        <span class="um-text"><b>{{ $name }}</b><small>{{ $roleLabel }}</small></span>
        <svg class="um-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
    </label>

    {{-- Klik di luar = tutup --}}
    <label for="umToggle" class="um-overlay"></label>

    {{-- Isi dropdown --}}
    <div class="um-panel">
        <div class="um-head">
            <span class="um-avatar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            </span>
            <div><b>{{ $name }}</b><small>{{ $sub }}</small></div>
        </div>

        @auth
            <a href="{{ url('/profil') }}" class="um-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                Profil Saya
            </a>
            <a href="{{ url('/pesanan') }}" class="um-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6"/></svg>
                Pesanan Saya
            </a>

            <div class="um-sep"></div>

            {{-- Logout harus POST + @csrf di Laravel --}}
            <form method="POST" action="{{ $logoutUrl }}">
                @csrf
                <button type="submit" class="um-item danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
                    Keluar
                </button>
            </form>
        @else
            <a href="{{ $loginUrl }}" class="um-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><path d="M10 17l5-5-5-5M15 12H3"/></svg>
                Masuk
            </a>
        @endauth
    </div>
</div>