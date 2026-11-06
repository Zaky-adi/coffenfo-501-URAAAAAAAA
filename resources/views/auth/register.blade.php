@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
    <h2>Cerita kopimu dimulai di sini.</h2>
    <p class="sub">Buat akun dan temukan cafe yang sesuai seleramu.</p>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('register') }}" class="compact">
        @csrf

        <div class="field">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama lengkapmu" autocomplete="name" required>
        </div>

        <div class="field">
            <label for="login">Email / Username</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="Email atau username kamu" autocomplete="username" required>
        </div>

        <div class="field">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Buat kata sandi" autocomplete="new-password" minlength="8" required>
            <button type="button" class="eye eye-top" data-target="password" aria-label="Tampilkan kata sandi">
                <svg class="icon on"><use href="#i-eye-off"/></svg>
                <svg class="icon off"><use href="#i-eye"/></svg>
            </button>
            <small class="hint">Gunakan minimal 8 karakter.</small>
        </div>

        <div class="field">
            <label for="bean">Preferensi Beans Favorit</label>
            <select id="bean" name="bean">
                @foreach (['Semuanya', 'Single Origin', 'Blend'] as $opt)
                    <option value="{{ $opt }}" @selected(old('bean') === $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <small class="hint">Pilihan: Single Origin, Blend, Semuanya.</small>
        </div>

        <div class="field">
            <label for="roast">Roasting Profile Favorit</label>
            <select id="roast" name="roast">
                @foreach (['Medium', 'Light', 'Dark'] as $opt)
                    <option value="{{ $opt }}" @selected(old('roast') === $opt)>{{ $opt }}</option>
                @endforeach
            </select>
            <small class="hint">Pilihan: Light, Medium, Dark.</small>
        </div>

        <button class="btn" type="submit">Buat Akun Baru <svg class="icon"><use href="#i-arrow"/></svg></button>
        <p class="note">Dengan mendaftar, kamu menyetujui <a href="#">Syarat &amp; Ketentuan</a> dan <a href="#">Kebijakan Privasi</a> Coffenfo.</p>
    </form>
@endsection