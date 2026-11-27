<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /** Masuk: cocokkan email/username + kata sandi dengan akun yang tersimpan. */
    public function login(Request $request)
    {
        $request->merge(['login' => mb_strtolower(trim((string) $request->input('login')))]);

        $data = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required'    => 'Email atau username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Email / username disimpan di kolom "email"
        if (! Auth::attempt(['email' => $data['login'], 'password' => $data['password']], $request->boolean('remember'))) {
            throw ValidationException::withMessages(['login' => 'Email / username atau kata sandi salah.']);
        }

        $request->session()->regenerate();

        // Setelah masuk, pengguna ke halaman Explore (atau halaman yang tadi ingin dibuka)
        return redirect()->intended(route('explore'));
    }

    /** Daftar: simpan akun baru ke database, lalu arahkan ke halaman login. */
    public function register(Request $request)
    {
        $request->merge(['login' => mb_strtolower(trim((string) $request->input('login')))]);

        $data = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'login'    => ['required', 'string', 'max:150', 'unique:users,email', self::loginRule()],
            'password' => ['required', 'string', 'min:8', 'max:100'],
            'bean'     => ['nullable', 'in:Semua,Semuanya,Single Origin,Blend'],
            'roast'    => ['nullable', 'in:Light,Medium,Dark'],
        ], self::messages());

        $bean = $data['bean'] ?? 'Semua';

        $user = new User();
        $user->name     = trim($data['name']);
        $user->email    = $data['login'];
        $user->password = Hash::make($data['password']);   // kata sandi disimpan terenkripsi
        $user->bean     = $bean === 'Semuanya' ? 'Semua' : $bean;
        $user->roast    = $data['roast'] ?? 'Medium';
        $user->role     = 'user';
        $user->save();

        // Kolom "Email / Username" di halaman login langsung terisi akun yang baru dibuat
        return redirect()->route('login')->withInput(['login' => $user->email]);
    }

    /** Keluar. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /** Aturan "Email / Username": kalau ada "@" harus email valid, kalau tidak minimal 3 karakter. */
    public static function loginRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) {
            $value = (string) $value;
            if (str_contains($value, '@')) {
                if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $fail('Format email tidak valid.');
                }
            } elseif (! preg_match('/^[a-z0-9._-]{3,}$/', $value)) {
                $fail('Username minimal 3 karakter (huruf, angka, titik, garis bawah, atau strip).');
            }
        };
    }

    /** Pesan kesalahan dalam bahasa Indonesia. */
    public static function messages(): array
    {
        return [
            'name.required'      => 'Nama wajib diisi.',
            'name.min'           => 'Nama minimal 2 karakter.',
            'login.required'     => 'Email atau username wajib diisi.',
            'login.unique'       => 'Email / username ini sudah terdaftar.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Pengulangan kata sandi tidak sama.',
            'current.required'   => 'Isi kata sandi saat ini.',
            'photo.max'          => 'Foto terlalu besar, pilih foto lain.',
            'photo.starts_with'  => 'File harus berupa gambar.',
        ];
    }
}