@extends('layouts.app')

@section('title', 'Profil Saya')

{{-- Header putih dengan lonceng dan kartu profil --}}
@section('header')
<header class="pf-bar">
    <div class="wrap pf-bar-in">
        <a href="{{ route('explore') }}" class="pf-logo"><img src="{{ asset('images/logo_icon.png') }}" alt="">Coffenfo</a>
        <div class="pf-right">
            <button type="button" class="pf-bell" aria-label="Notifikasi"><svg class="icon"><use href="#pf-bell"/></svg></button>
            <div class="pf-pill">
                <span class="pf-av sm" id="pf-hav"></span>
                <div><b id="pf-hname">Tamu</b><small>Pengguna</small></div>
                <svg class="icon"><use href="#pf-down"/></svg>
            </div>
        </div>
    </div>
</header>
@endsection

@section('content')
@php
    // ----- Data awal AKUN KOSONG. Kalau sudah ada login sungguhan, nama & email otomatis terisi dari akun itu. -----
    $user = [
        'name'   => auth()->user()->name  ?? '',
        'email'  => auth()->user()->email ?? '',
        'bean'   => 'Semua',
        'roast'  => 'Medium',
        'review' => 0,
        'fav'    => 0,
        'since'  => '',
    ];
    $beans = ['Semua', 'Single Origin', 'Blend'];
    $roasts = ['Light', 'Medium', 'Dark'];
@endphp

{{-- Ikon khusus halaman ini --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="pf-bell" viewBox="0 0 24 24"><path d="M6 17V11a6 6 0 0112 0v6l1.5 2h-15L6 17zM10 21h4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="pf-lock" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8 11V8a4 4 0 018 0v3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    <symbol id="pf-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    <symbol id="pf-down" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
</svg>

<style>
    /* ===== Halaman Profil (kelas diawali "pf-") ===== */
    .pf-bar{background:#fff;border-bottom:1px solid #eae2d3}
    .pf-bar-in{display:flex;justify-content:space-between;align-items:center;height:60px}
    .pf-logo{display:flex;align-items:center;gap:8px;font-weight:700;font-size:15px}
    .pf-logo img{width:22px;height:22px;object-fit:contain}
    .pf-right{display:flex;align-items:center;gap:12px}
    .pf-bell{display:grid;place-items:center;width:30px;height:30px;border:1px solid #eae2d3;border-radius:50%;background:#fff;color:#3b2620;font-size:14px;cursor:pointer}
    .pf-pill{display:flex;align-items:center;gap:8px;padding:4px 12px 4px 4px;border-radius:99px;background:#f1e9dc;font-size:10px}
    .pf-pill b{display:block;font-size:10px}.pf-pill small{display:block;font-size:8.5px;color:#8a7f74}
    .pf-pill .icon{font-size:11px;color:#8a7f74}
    .pf-av{display:grid;place-items:center;flex:none;width:44px;height:44px;border-radius:50%;background:#3b2620 center/cover;color:#fff;font-size:14px;font-weight:700}
    .pf-av .icon{font-size:20px}.pf-av.sm .icon{font-size:14px}
    .pf-muted{color:#a79d90!important;font-weight:500!important}
    .pf-av.sm{width:26px;height:26px;font-size:9px}

    .pf-wrap{max-width:700px;margin:0 auto}
    .pf-title{font-family:'Lora',Georgia,serif;font-size:28px;font-weight:500;color:#3b2620}
    .pf-sub{margin:6px 0 22px;font-size:10.5px;color:#8a7f74}
    .pf-card{margin-bottom:18px;padding:22px 24px;border:1px solid #eae2d3;border-radius:14px;background:#fff}
    .pf-card h2{margin-bottom:16px;font-family:'Lora',Georgia,serif;font-size:15px;font-weight:600}
    .pf-who{display:flex;align-items:center;gap:14px;margin-bottom:18px}
    .pf-who b{display:block;font-size:13px}.pf-who small{font-size:10px;color:#8a7f74}
    .pf-who .pf-btn{margin-left:auto}
    .pf-btn{padding:8px 16px;border:1px solid #d8cdb8;border-radius:8px;background:#fff;font:600 10.5px 'Plus Jakarta Sans',sans-serif;color:#3b2620;cursor:pointer}
    .pf-btn:hover{background:#f4ede2}
    .pf-btn.dark{background:#3b2620;border-color:#3b2620;color:#fff}
    .pf-btn.dark:hover{background:#523830}
    .pf-btn.red{border-color:#ecc2bb;color:#c0392b}
    .pf-btn.red:hover{background:#fdeceb}
    .pf-btn.solid-red{background:#c0392b;border-color:#c0392b;color:#fff}
    .pf-btn.solid-red:hover{background:#a52f22}
    .pf-two{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    .pf-f label{display:block;margin-bottom:6px;font-size:9.5px;font-weight:700}
    .pf-in{display:flex;align-items:center;gap:8px;height:38px;padding:0 12px;border:1px solid #eae2d3;border-radius:8px;background:#fffdf8}
    .pf-in:focus-within{border-color:#3b2620}
    .pf-in input{flex:1;min-width:0;border:0;background:none;font:11px 'Plus Jakarta Sans',sans-serif;color:#3b2620;outline:0}
    .pf-in.ro{background:#f1ebe0;color:#8a7f74}
    .pf-in.ro input{color:#8a7f74;cursor:not-allowed}
    .pf-in .icon{font-size:12px;color:#a79d90}
    .pf-hint{display:block;margin-top:6px;font-size:8.5px;color:#a79d90}
    .pf-err{display:block;margin-top:6px;font-size:9px;color:#b03a3a}
    .pf-pass{display:flex;justify-content:space-between;align-items:center;margin-top:18px;padding-top:16px;border-top:1px solid #eae2d3}
    .pf-pass span{display:block;font-size:9.5px;font-weight:700;margin-bottom:5px}
    .pf-pass em{font-style:normal;font-size:12px;letter-spacing:2px}
    .pf-link{border:0;background:none;font:600 10px 'Plus Jakarta Sans',sans-serif;color:#cd7f3a;cursor:pointer}
    .pf-sel{width:100%;height:38px;padding:0 34px 0 12px;border:1px solid #eae2d3;border-radius:8px;background:#fffdf8 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237d7266' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E") no-repeat right 12px center;appearance:none;font:11px 'Plus Jakarta Sans',sans-serif;color:#3b2620;cursor:pointer}
    .pf-sel:focus{outline:2px solid #3b2620;outline-offset:1px}
    .pf-prefs{display:flex;align-items:center;gap:8px;margin-top:16px;font-size:9px;color:#8a7f74}
    .pf-prefs i{font-style:normal;padding:3px 10px;border-radius:99px;background:#f7e5cf;color:#8a5a22;font-size:8.5px;font-weight:600}
    .pf-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
    .pf-stat{padding:14px 16px;border:1px solid #eae2d3;border-radius:10px;background:#faf6ef}
    .pf-stat b{display:block;font-family:'Lora',Georgia,serif;font-size:21px;font-weight:500}
    .pf-stat small{display:block;margin-top:4px;font-size:8.5px;color:#8a7f74}
    .pf-danger{display:flex;justify-content:space-between;align-items:center;gap:16px}
    .pf-danger b{display:block;font-size:10.5px;margin-bottom:3px}.pf-danger small{font-size:9px;color:#8a7f74}
    .pf-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:4px}
    .pf-actions .pf-btn{padding:10px 20px}

    /* modal kecil (kata sandi & hapus akun) */
    .pf-modal{position:fixed;inset:0;z-index:9999;display:grid;place-items:center;padding:16px;background:rgba(59,38,32,.55);overflow-y:auto}
    .pf-modal[hidden]{display:none}
    .pf-box{width:min(400px,100%);padding:24px;border-radius:14px;background:#fff;box-shadow:0 24px 60px rgba(0,0,0,.3)}
    .pf-box h3{font-family:'Lora',Georgia,serif;font-size:18px;font-weight:600;margin-bottom:6px}
    .pf-box p{margin-bottom:16px;font-size:10.5px;line-height:1.6;color:#6b5f54}
    .pf-box .pf-f{margin-bottom:12px}
    .pf-mf{display:flex;justify-content:flex-end;gap:10px;margin-top:18px}
    .pf-toast{position:fixed;left:50%;bottom:28px;z-index:10000;transform:translateX(-50%);padding:11px 18px;border-radius:99px;background:#3b2620;color:#fff;font-size:11px;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.25)}
    .pf-toast[hidden]{display:none}
    @media(max-width:620px){.pf-two,.pf-stats{grid-template-columns:1fr}.pf-danger{flex-direction:column;align-items:flex-start}}
</style>

<div class="pf-wrap">
    <h1 class="pf-title">Profil Saya</h1>
    <p class="pf-sub">Kelola data akun dan preferensi kopimu.</p>

    <form id="pf-form" novalidate>
        {{-- ===== Informasi Akun ===== --}}
        <section class="pf-card">
            <h2>Informasi Akun</h2>
            <div class="pf-who">
                <span class="pf-av" id="pf-av"></span>
                <div><b id="pf-name"></b><small id="pf-mail"></small></div>
                <button type="button" class="pf-btn" id="pf-photo-btn">Ubah foto</button>
                <input type="file" id="pf-photo" accept="image/jpeg,image/png,image/webp" hidden>
            </div>
            <small class="pf-err" id="err-photo" hidden></small>

            <div class="pf-two">
                <div class="pf-f">
                    <label for="pf-fullname">Nama Lengkap</label>
                    <div class="pf-in"><input type="text" id="pf-fullname" value="{{ $user['name'] }}" placeholder="Nama lengkap kamu" autocomplete="name"></div>
                    <small class="pf-err" id="err-name" hidden>Nama minimal 2 karakter.</small>
                </div>
                <div class="pf-f">
                    <label for="pf-email">Email / Username</label>
                    <div class="pf-in"><input type="text" id="pf-email" value="{{ $user['email'] }}" placeholder="Email atau username kamu" autocomplete="username" inputmode="email"></div>
                    <small class="pf-hint">Gunakan email atau username yang aktif.</small>
                    <small class="pf-err" id="err-email" hidden>Masukkan email yang valid, atau username minimal 3 karakter.</small>
                </div>
            </div>

            <div class="pf-pass">
                <div><span>Kata Sandi</span><em>••••••••••</em></div>
                <button type="button" class="pf-link" data-open="pass">Ubah kata sandi</button>
            </div>
        </section>

        {{-- ===== Preferensi Kopi ===== --}}
        <section class="pf-card">
            <h2>Preferensi Kopi</h2>
            <div class="pf-two">
                <div class="pf-f">
                    <label for="pf-bean">Preferensi Beans Favorit</label>
                    <select class="pf-sel" id="pf-bean">@foreach ($beans as $o)<option @selected($o === $user['bean'])>{{ $o }}</option>@endforeach</select>
                    <small class="pf-hint">Pilihan: Single Origin, Blend, Semua.</small>
                </div>
                <div class="pf-f">
                    <label for="pf-roast">Roasting Profile Favorit</label>
                    <select class="pf-sel" id="pf-roast">@foreach ($roasts as $o)<option @selected($o === $user['roast'])>{{ $o }}</option>@endforeach</select>
                    <small class="pf-hint">Pilihan: Light, Medium, Dark.</small>
                </div>
            </div>
            <div class="pf-prefs">Preferensi: <i id="chip-bean">Beans: {{ $user['bean'] }}</i><i id="chip-roast">Roast: {{ $user['roast'] }}</i></div>
        </section>

        {{-- ===== Aktivitas ===== --}}
        <section class="pf-card">
            <h2>Aktivitas Saya</h2>
            <div class="pf-stats">
                <div class="pf-stat"><b id="pf-review">{{ $user['review'] }}</b><small>Review Ditulis</small></div>
                <div class="pf-stat"><b id="pf-fav">{{ $user['fav'] }}</b><small>Cafe Favorit</small></div>
                <div class="pf-stat"><b id="pf-since">—</b><small>Bergabung Sejak</small></div>
            </div>
        </section>

        {{-- ===== Zona Berbahaya ===== --}}
        <section class="pf-card">
            <h2>Zona Berbahaya</h2>
            <div class="pf-danger">
                <div><b>Hapus akun</b><small>Akun dan semua review milikmu akan dihapus permanen.</small></div>
                <button type="button" class="pf-btn red" data-open="del">Hapus akun</button>
            </div>
        </section>

        <div class="pf-actions">
            <button type="button" class="pf-btn" id="pf-cancel">Batal</button>
            <button type="submit" class="pf-btn dark">Simpan perubahan</button>
        </div>
    </form>
</div>

{{-- ===== Modal: ubah kata sandi (tidak ada di Figma, dibuat sederhana) ===== --}}
<div class="pf-modal" id="m-pass" role="dialog" aria-modal="true" aria-labelledby="mp-t" hidden>
    <form class="pf-box" id="pass-form" novalidate>
        <h3 id="mp-t">Ubah kata sandi</h3>
        <p>Gunakan minimal 8 karakter agar akunmu aman.</p>
        <div class="pf-f"><label for="pw-old">Kata sandi saat ini</label><div class="pf-in"><input type="password" id="pw-old" autocomplete="current-password"></div></div>
        <div class="pf-f"><label for="pw-new">Kata sandi baru</label><div class="pf-in"><input type="password" id="pw-new" autocomplete="new-password"></div></div>
        <div class="pf-f"><label for="pw-new2">Ulangi kata sandi baru</label><div class="pf-in"><input type="password" id="pw-new2" autocomplete="new-password"></div></div>
        <small class="pf-err" id="err-pass" hidden></small>
        <div class="pf-mf"><button type="button" class="pf-btn" data-close>Batal</button><button type="submit" class="pf-btn dark">Simpan</button></div>
    </form>
</div>

{{-- ===== Modal: konfirmasi hapus akun ===== --}}
<div class="pf-modal" id="m-del" role="dialog" aria-modal="true" aria-labelledby="md-t" hidden>
    <div class="pf-box">
        <h3 id="md-t">Hapus akun?</h3>
        <p>Akun dan semua review milikmu akan dihapus permanen dan tidak bisa dikembalikan.</p>
        <div class="pf-mf" style="margin-top:6px"><button type="button" class="pf-btn" data-close>Batal</button><button type="button" class="pf-btn solid-red" id="del-ok">Ya, hapus akun</button></div>
    </div>
</div>
<div class="pf-toast" id="pf-toast" hidden></div>

<script id="profile-js">
    (function () {
        const $ = id => document.getElementById(id);
        const KEY = 'coffenfo.profile';          // tempat profil disimpan di browser ini
        const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const initialsOf = n => n.trim().split(/\s+/).filter(Boolean).slice(0, 2).map(w => w[0].toUpperCase()).join('');
        const USER_ICON = '<svg class="icon"><use href="#pf-user"/></svg>';

        // Profil tersimpan = apa yang ada di browser, kalau belum ada pakai data awal dari server (kosong)
        let saved = Object.assign({ name: '', email: '', bean: 'Semua', roast: 'Medium', review: 0, fav: 0, since: '', photo: '' }, @json($user));
        try { Object.assign(saved, JSON.parse(localStorage.getItem(KEY) || 'null') || {}); } catch (e) {}
        let pendingPhoto = '';

        function persist() {
            try { localStorage.setItem(KEY, JSON.stringify(saved)); }
            catch (e) { toast('Penyimpanan browser penuh, foto tidak ikut tersimpan.'); }
        }
        function toast(msg) {
            const t = $('pf-toast'); t.textContent = msg; t.hidden = false;
            clearTimeout(toast.t); toast.t = setTimeout(() => t.hidden = true, 2800);
        }
        function paintAvatars(photo, name) {
            [$('pf-av'), $('pf-hav')].forEach(el => {
                el.style.backgroundImage = photo ? `url('${photo}')` : '';
                if (photo) el.textContent = '';
                else if (name.trim()) el.textContent = initialsOf(name);
                else el.innerHTML = USER_ICON;
            });
        }
        // Tampilkan semua data tersimpan ke halaman
        function render() {
            $('pf-name').textContent = saved.name || 'Nama belum diisi';
            $('pf-name').classList.toggle('pf-muted', !saved.name);
            $('pf-mail').textContent = saved.email || 'Email belum diisi';
            $('pf-hname').textContent = saved.name || 'Tamu';
            $('pf-review').textContent = saved.review;
            $('pf-fav').textContent = saved.fav;
            $('pf-since').textContent = saved.since || '—';
            $('pf-fullname').value = saved.name;
            $('pf-email').value = saved.email;
            $('pf-bean').value = saved.bean;
            $('pf-roast').value = saved.roast;
            chips();
            paintAvatars(saved.photo, saved.name);
        }
        const chips = () => { $('chip-bean').textContent = 'Beans: ' + $('pf-bean').value; $('chip-roast').textContent = 'Roast: ' + $('pf-roast').value; };

        const modals = { pass: $('m-pass'), del: $('m-del') };
        document.body.append(modals.pass, modals.del, $('pf-toast'));

        // --- Ubah foto: validasi, kecilkan ke 256 px, tampilkan pratinjau ---
        function shrink(file) {
            return new Promise((resolve, reject) => {
                const img = new Image(), url = URL.createObjectURL(file);
                img.onload = () => {
                    const size = 256, c = document.createElement('canvas');
                    c.width = c.height = size;
                    const r = Math.max(size / img.width, size / img.height), w = img.width * r, h = img.height * r;
                    c.getContext('2d').drawImage(img, (size - w) / 2, (size - h) / 2, w, h);
                    URL.revokeObjectURL(url);
                    resolve(c.toDataURL('image/jpeg', 0.85));
                };
                img.onerror = reject;
                img.src = url;
            });
        }
        $('pf-photo-btn').addEventListener('click', () => $('pf-photo').click());
        $('pf-photo').addEventListener('change', async e => {
            const f = e.target.files[0], err = $('err-photo');
            err.hidden = true;
            if (!f) return;
            if (!/^image\/(jpeg|png|webp)$/.test(f.type)) { err.textContent = 'Format foto harus JPG, PNG, atau WEBP.'; err.hidden = false; return; }
            if (f.size > 5 * 1024 * 1024) { err.textContent = 'Ukuran foto maksimal 5 MB.'; err.hidden = false; return; }
            try { pendingPhoto = await shrink(f); paintAvatars(pendingPhoto, $('pf-fullname').value); }
            catch (x) { err.textContent = 'Foto tidak bisa dibaca, coba foto lain.'; err.hidden = false; }
        });

        // --- Isian berubah ---
        $('pf-bean').addEventListener('change', chips);
        $('pf-roast').addEventListener('change', chips);
        $('pf-email').addEventListener('input', () => { $('err-email').hidden = true; });
        $('pf-fullname').addEventListener('input', () => {
            $('err-name').hidden = true;
            if (!(pendingPhoto || saved.photo)) paintAvatars('', $('pf-fullname').value);
        });

        // --- Simpan perubahan ---
        $('pf-form').addEventListener('submit', e => {
            e.preventDefault();   // SEMENTARA: disimpan di browser ini saja. Nanti dikirim ke server / database.
            const name = $('pf-fullname').value.trim();
            $('err-name').hidden = name.length >= 2;
            if (name.length < 2) { $('pf-fullname').focus(); return; }

            // Email / username: kalau ada "@" harus berbentuk email, kalau tidak minimal 3 karakter (huruf, angka, . _ -)
            const email = $('pf-email').value.trim();
            const emailOk = email.includes('@') ? /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) : /^[A-Za-z0-9._-]{3,}$/.test(email);
            $('err-email').hidden = emailOk;
            if (!emailOk) { $('pf-email').focus(); return; }

            const d = new Date();
            Object.assign(saved, {
                name, email, bean: $('pf-bean').value, roast: $('pf-roast').value,
                photo: pendingPhoto || saved.photo,
                since: saved.since || (MONTHS[d.getMonth()] + ' ' + d.getFullYear())
            });
            pendingPhoto = '';
            persist(); render();
            toast('Perubahan disimpan.');
        });

        // --- Batal: kembalikan ke data tersimpan ---
        $('pf-cancel').addEventListener('click', () => {
            pendingPhoto = ''; $('pf-photo').value = '';
            $('err-name').hidden = true; $('err-email').hidden = true; $('err-photo').hidden = true;
            render();
        });

        // --- Modal: buka / tutup ---
        function openModal(key) { modals[key].hidden = false; document.body.style.overflow = 'hidden'; const f = modals[key].querySelector('input'); if (f) f.focus(); }
        function closeModals() { Object.values(modals).forEach(m => m.hidden = true); document.body.style.overflow = ''; }
        document.addEventListener('click', e => {
            const o = e.target.closest('[data-open]');
            if (o) openModal(o.dataset.open);
            else if (e.target.closest('[data-close]') || Object.values(modals).includes(e.target)) closeModals();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModals(); });

        // --- Ubah kata sandi ---
        $('pass-form').addEventListener('submit', e => {
            e.preventDefault();   // SEMENTARA: nanti dikirim ke server
            const err = $('err-pass'), a = $('pw-old').value, b = $('pw-new').value, c = $('pw-new2').value;
            const msg = !a ? 'Isi kata sandi saat ini.' : b.length < 8 ? 'Kata sandi baru minimal 8 karakter.' : b !== c ? 'Pengulangan kata sandi tidak sama.' : '';
            err.textContent = msg; err.hidden = !msg;
            if (msg) return;
            $('pass-form').reset(); closeModals(); toast('Kata sandi diperbarui.');
        });

        // --- Hapus akun: hapus profil dari browser lalu kembali ke beranda ---
        $('del-ok').addEventListener('click', () => {   // SEMENTARA: nanti hapus akun di server
            try { localStorage.removeItem(KEY); } catch (e) {}
            location.href = @json(url('/'));
        });

        render();
    })();
</script>
@endsection