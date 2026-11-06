@extends('layouts.auth')

@section('title', 'Masuk')
@section('copy', '© 2026 Coffenfo. RPL 501')

@section('content')
@php
    // Peran yang dibawa dari halaman "Pilih peran" (?role=...)
    $roleLabels = ['user' => 'Pengguna', 'owner' => 'Pemilik cafe (mitra)', 'admin' => 'Admin Coffenfo'];
    $role = (string) request('role');
    $roleLabel = $roleLabels[$role] ?? null;
@endphp
    <h2>Senang kamu kembali.</h2>
    <p class="sub">Masuk untuk menemukan cafe dan berbagi review.</p>

    @if ($roleLabel)
        <div><span class="pill">Masuk sebagai {{ $roleLabel }} · <a href="{{ route('role.select') }}">ganti</a></span></div>
    @endif

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('login') }}">
        @csrf
        @if ($roleLabel)<input type="hidden" name="role" value="{{ $role }}">@endif

        <div class="field">
            <label for="login">Email / Username</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="Email atau username kamu" autocomplete="username" required>
        </div>

        <div class="field">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
            <button type="button" class="eye" data-target="password" aria-label="Tampilkan kata sandi">
                <svg class="icon on"><use href="#i-eye-off"/></svg>
                <svg class="icon off"><use href="#i-eye"/></svg>
            </button>
        </div>

        <a href="#" class="forgot">Lupa Password?</a>

        <button class="btn" type="submit">Masuk Ke Coffenfo <svg class="icon"><use href="#i-arrow"/></svg></button>
        <p class="note">Secangkir kopi, banyak cerita. Yuk, masuk jaringmu.</p>
    </form>
@endsection