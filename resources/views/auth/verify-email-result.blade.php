<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <title>Verifikasi Email Coffenfo</title>
</head>
<body>
    <main>
        @if ($verified)
            <h1>Email berhasil diverifikasi</h1>
            <p>Akun Coffenfo Anda sudah siap digunakan.</p>
            <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
        @else
            <h1>Tautan verifikasi tidak berlaku</h1>
            <p>Tautan ini salah, sudah digunakan, atau telah kedaluwarsa.</p>
            <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
        @endif
    </main>
</body>
</html>
