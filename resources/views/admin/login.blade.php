@extends('layouts.auth')

@section('title', 'Masuk Admin')
@section('no_tabs', '1')
@section('copy', '© 2026 Coffenfo. Panel admin.')

{{-- Panel kiri khusus admin --}}
@section('left')
<section class="left">
    <a href="{{ url('/') }}" class="logo">
        <span><img src="{{ asset('images/logo_icon.png') }}" alt=""></span>Coffenfo
        <em class="tag">ADMIN</em>
    </a>

    <i class="rule"></i>
    <h1>Kelola Coffenfo Dengan Mudah</h1>

    <ul class="checklist">
        @foreach (['Kelola data dan akun pengguna', 'Moderasi review dan cafe', 'Pantau aktivitas dan laporan platform'] as $item)
            <li><svg class="icon"><use href="#i-check"/></svg>{{ $item }}</li>
        @endforeach
    </ul>

    <div class="art">
        <svg viewBox="0 0 260 190" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="125" cy="120" r="52" fill="#ece6c6" stroke="none"/>
            <g stroke="#7a4a35" transform="rotate(-14 95 90)">
                <ellipse cx="95" cy="90" rx="30" ry="48"/><path d="M88 46c20 22-12 40 10 88"/>
            </g>
            <g stroke="#b0603a" transform="rotate(22 150 140)">
                <ellipse cx="150" cy="140" rx="24" ry="40"/><path d="M144 104c16 18-10 32 8 72"/>
            </g>
            <g stroke="#b0603a" transform="rotate(30 188 70)">
                <ellipse cx="188" cy="70" rx="11" ry="16"/><path d="M185 56c6 8-4 14 3 28"/>
            </g>
            <path d="M22 176c10-4 20-12 24-26-9 5-18 14-24 26zM30 168l16-16" stroke="#cd7f3a"/>
            <circle cx="62" cy="52" r="1.2" fill="#7a4a35" stroke="none"/><circle cx="214" cy="128" r="1.4" fill="#b0603a" stroke="none"/>
        </svg>
    </div>

    <div class="left-foot"><i class="rule"></i>Dari Batam, untuk pecinta kopi</div>
</section>
@endsection

@section('content')
<div class="admin-form">
    <h2>Masuk ke panel admin</h2>
    <p class="sub">Gunakan akun administrator yang telah terdaftar untuk mengakses panel pengelolaan Coffenfo.</p>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="field">
            <label for="email">Email Administrator</label>
            <input type="text" id="email" name="email" value="{{ old('email') }}" placeholder="admin@coffenfo.com" autocomplete="username" required>
        </div>

        <div class="field">
            <label for="password">Kata sandi</label>
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
            <button type="button" class="eye" data-target="password" aria-label="Tampilkan kata sandi">
                <svg class="icon on"><use href="#i-eye-off"/></svg>
                <svg class="icon off"><use href="#i-eye"/></svg>
            </button>
        </div>

        <a href="#" class="forgot orange">Lupa password?</a>

        <button class="btn" type="submit">Masuk ke panel admin <svg class="icon"><use href="#i-arrow"/></svg></button>
        <hr class="divider">
    </form>
</div>
@endsection