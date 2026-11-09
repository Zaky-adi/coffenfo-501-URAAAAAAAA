@extends('layouts.app')

@section('title', 'Checkout')

{{-- Header putih dengan langkah-langkah checkout --}}
@section('header')
<header class="co-bar">
    <div class="wrap co-bar-in">
        <a href="{{ url('/') }}" class="co-logo"><img src="{{ asset('images/logo_icon.png') }}" alt="">Coffenfo</a>
        <ol class="co-steps">
            <li class="done"><svg class="icon"><use href="#co-check"/></svg>Keranjang</li>
            <i></i>
            <li class="on"><b>2</b>Checkout</li>
            <i></i>
            <li>Pesanan selesai</li>
        </ol>
        <span class="co-secure"><svg class="icon"><use href="#co-lock"/></svg>Checkout aman</span>
    </div>
</header>
@endsection

@section('content')
@php
    // ----- Data contoh. Nanti diambil dari keranjang (session / database). -----
    // [nama, deskripsi, harga satuan, jumlah, slug foto]
    $items = [
        ['Manual Brew in House Beans', 'Signature house selection designed for balance clarity', 45000, 1, 'manual-brew'],
        ['Salted Egg Chicken',         '',                                                        45000, 1, 'salted-egg'],
    ];
    $cafeName = 'Grind Now Coffe Bar';
    $taxRate  = 0.10;

    $subtotal = collect($items)->sum(fn ($i) => $i[2] * $i[3]);
    $tax      = (int) round($subtotal * $taxRate);
    $total    = $subtotal + $tax;
    $rp       = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
    $payUrl   = Route::has('checkout.pay') ? route('checkout.pay') : '#';

    // [kode, judul, ikon, label kecil, teks bantuan, tulisan tombol]
    $methods = [
        ['ewallet', 'E-Wallet (QRIS / GoPay / OVO / ShopeePay)',      'co-scan', ['QRIS', 'GoPay', 'OVO', 'ShopeePay'],
         'Scan QR atau lanjutkan ke e-wallet setelah menekan Bayar Sekarang.',             'Bayar Sekarang'],
        ['bank',    'Transfer Bank / Virtual Account (BCA / Mandiri / BNI)', 'co-bank', ['BCA', 'Mandiri', 'BNI'],
         'Nomor Virtual Account akan dibuat setelah kamu menekan Buat Pesanan.',           'Buat Pesanan'],
    ];
@endphp

{{-- Ikon khusus halaman ini --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="co-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8 12.3l2.8 2.8L16 9.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="co-lock" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8 11V8a4 4 0 018 0v3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    <symbol id="co-shield" viewBox="0 0 24 24"><path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="co-scan" viewBox="0 0 24 24"><path d="M4 8V5a1 1 0 011-1h3M16 4h3a1 1 0 011 1v3M20 16v3a1 1 0 01-1 1h-3M8 20H5a1 1 0 01-1-1v-3M4 12h16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    <symbol id="co-bank" viewBox="0 0 24 24"><path d="M3 9l9-5 9 5M5 10v8M9.5 10v8M14.5 10v8M19 10v8M3 20h18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="co-table" viewBox="0 0 24 24"><path d="M4 9h16M6 9v10M18 9v10M8 9V6h8v3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="co-cup" viewBox="0 0 24 24"><path d="M4 9h13v5a5 5 0 01-5 5H9a5 5 0 01-5-5V9zM17 10h1.5a2.5 2.5 0 010 5H17M8 3v3M12 3v3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="co-tick" viewBox="0 0 24 24"><path d="M6 12.5l4 4 8-9" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="co-arrow-l" viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="co-arrow-r" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
</svg>

<style>
    /* ===== Halaman Checkout (kelas diawali "co-") ===== */
    .co-bar{background:#fff;border-bottom:1px solid #eae2d3}
    .co-bar-in{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;height:64px}
    .co-logo{display:flex;align-items:center;gap:8px;font-weight:700;font-size:16px}
    .co-logo img{width:24px;height:24px;object-fit:contain}
    .co-steps{display:flex;align-items:center;gap:14px;list-style:none;font-size:10.5px;color:#8a7f74}
    .co-steps li{display:flex;align-items:center;gap:7px}
    .co-steps i{display:block;width:46px;height:1px;background:#d9d0c0}
    .co-steps .done{color:#3b2620}.co-steps .done .icon{font-size:14px}
    .co-steps .on{color:#3b2620;font-weight:700}
    .co-steps .on b{display:grid;place-items:center;width:18px;height:18px;border-radius:50%;background:#3b2620;color:#fff;font-size:9px}
    .co-secure{justify-self:end;display:flex;align-items:center;gap:6px;font-size:10px;color:#3b2620}

    .co-back{display:inline-flex;align-items:center;gap:8px;font-size:10.5px;font-weight:500;color:#3b2620;margin-bottom:22px}
    .co-back .icon{font-size:13px}
    .co-title{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:22px}
    .co-title h1{font-size:28px;font-weight:700;line-height:1.15;color:#3b2620}
    .co-title p{margin-top:8px;font-size:11px;color:#8a7f74}
    .co-shop{display:inline-flex;align-items:center;gap:7px;background:#f1f1d3;border-radius:8px;padding:7px 12px;font-size:10px;font-weight:600;white-space:nowrap}

    .co-grid{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:18px;align-items:start}
    .co-card{background:#fff;border:1px solid #eae2d3;border-radius:12px;padding:20px;margin-bottom:18px}
    .co-card:last-child{margin-bottom:0}
    .co-card h3{font-size:12.5px;font-weight:700;margin-bottom:14px}
    .co-card .co-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
    .co-card .co-row h3{margin:0}
    .co-card .co-row span{font-size:9.5px;color:#8a7f74}

    /* tipe pesanan */
    .co-seg{display:grid;grid-template-columns:1fr 1fr;gap:6px;background:#f4efe6;border-radius:10px;padding:5px;margin-bottom:18px}
    .co-seg label{position:relative}
    .co-seg input{position:absolute;opacity:0;pointer-events:none}
    .co-seg span{display:block;text-align:center;padding:12px 8px;border-radius:7px;font-size:10.5px;font-weight:600;color:#5b4d42;cursor:pointer;transition:.15s}
    .co-seg input:checked + span{background:#3b2620;color:#fff}
    .co-seg input:focus-visible + span{outline:2px solid #cd7f3a;outline-offset:2px}
    .co-field label{display:block;font-size:10.5px;font-weight:700;margin-bottom:7px}
    .co-input{display:flex;align-items:center;gap:10px;border:1px solid #eae2d3;border-radius:9px;background:#fffdf8;padding:0 14px;height:42px}
    .co-input .icon{font-size:15px;color:#5b4d42}
    .co-input input{flex:1;border:0;background:none;font:12px 'Plus Jakarta Sans',sans-serif;color:#3b2620;outline:0}
    .co-input:focus-within{border-color:#3b2620}
    .co-hint{display:block;margin-top:8px;font-size:9px;color:#8a7f74}
    #table-field[hidden]{display:none}

    /* ringkasan pesanan */
    .co-item{display:grid;grid-template-columns:84px minmax(0,1fr);gap:16px;align-items:start;padding:16px 0;border-bottom:1px solid #eae2d3}
    .co-item:first-of-type{padding-top:0}
    .co-item:last-of-type{border-bottom:0;padding-bottom:0}
    .co-th{position:relative;display:grid;place-items:center;width:84px;height:76px;border-radius:9px;background:#f4ede2;color:#cd7f3a;font-size:24px;overflow:hidden}
    .co-th img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
    .co-item b{display:block;font-size:12.5px;font-weight:600}
    .co-item small{display:block;margin-top:4px;font-size:9.5px;color:#8a7f74}
    .co-line{display:flex;justify-content:space-between;align-items:center;margin-top:10px}
    .co-step{display:inline-flex;align-items:center;gap:9px;font-size:10.5px}
    .co-step button{width:20px;height:20px;padding:0;border:1px solid #d8cdb8;border-radius:5px;background:#fff;font-size:13px;line-height:1;color:#3b2620;cursor:pointer}
    .co-step button:hover{background:#f4ede2}
    .co-q{min-width:12px;text-align:center;font-weight:600}
    .co-price{font-size:12.5px;font-weight:700}
    .co-empty{padding:26px 0;text-align:center;font-size:11.5px;color:#8a7f74}
    .co-empty[hidden]{display:none}
    .co-empty a{color:#cd7f3a;font-weight:600;text-decoration:underline}
    .co-note{display:flex;align-items:center;gap:12px;background:#f1f1d3;border:1px solid #e6e6bd;border-radius:10px;padding:14px 16px;font-size:10px;color:#5b4d42}
    .co-note .icon{font-size:17px;color:#3b2620;flex:none}
    .co-note b{display:block;font-size:10.5px;color:#3b2620;margin-bottom:2px}

    /* pembayaran */
    .co-pay{position:relative;display:grid;grid-template-columns:15px minmax(0,1fr) auto;gap:0 12px;align-items:center;border:1px solid #eae2d3;border-radius:10px;padding:14px;margin-bottom:10px;background:#fff;font-size:10.5px;font-weight:600;cursor:pointer;transition:.15s}
    .co-pay:last-of-type{margin-bottom:0}
    .co-pay input{position:absolute;opacity:0;pointer-events:none}
    .co-pay .dot{width:15px;height:15px;border:1.5px solid #cfc3ad;border-radius:50%;background:#fff;position:relative}
    .co-pay .t{line-height:1.4}
    .co-pay .icon{font-size:16px;color:#3b2620}
    .co-pay .more{display:none;grid-column:2 / 4;margin-top:10px;font-weight:400}
    .co-pay .tags{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px}
    .co-pay .tags em{font-style:normal;font-size:8.5px;font-weight:700;padding:3px 9px;border:1px solid #e6dfcf;border-radius:5px;background:#fff}
    .co-pay small{display:block;font-size:9.5px;line-height:1.55;color:#6b5f54}
    .co-pay:hover{border-color:#cfc3ad}
    .co-pay:has(input:checked){border-color:#3b2620;background:#fbf6e6;box-shadow:0 0 0 1px #3b2620}
    .co-pay:has(input:checked) .more{display:block}
    .co-pay input:checked ~ .dot{border:1.5px solid #3b2620}
    .co-pay input:checked ~ .dot::after{content:"";position:absolute;inset:2.5px;border-radius:50%;background:#3b2620}
    .co-pay:has(input:focus-visible){outline:2px solid #cd7f3a;outline-offset:2px}
    .co-sum div{display:flex;justify-content:space-between;align-items:center;font-size:10px;color:#5b4d42;margin-bottom:11px}
    .co-sum div b{font-weight:700;color:#3b2620}
    .co-sum .tot{margin:14px 0 0;padding-top:14px;border-top:1px solid #eae2d3}
    .co-sum .tot span{font-size:10.5px;color:#3b2620}
    .co-sum .tot b{font-size:20px}
    .co-submit{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;height:46px;margin-top:18px;border:0;border-radius:10px;background:#3b2620;color:#fff;font:600 11.5px 'Plus Jakarta Sans',sans-serif;cursor:pointer}
    .co-submit:hover{background:#523830}
    .co-submit:disabled{opacity:.4;cursor:not-allowed}
    .co-submit .icon{font-size:14px}
    .co-safe{display:flex;align-items:center;justify-content:center;gap:7px;margin-top:14px;font-size:9px;color:#8a7f74}
    .co-foot{display:flex;justify-content:space-between;margin-top:40px;padding-top:20px;border-top:1px solid #eae2d3;font-size:9.5px;color:#8a7f74}
    .co-foot b{color:#3b2620}

    /* pop-out pesanan berhasil */
    .co-done{position:fixed;inset:0;z-index:9999;display:grid;place-items:center;padding:24px;overflow-y:auto;background:radial-gradient(ellipse 70% 55% at 50% 36%,rgba(246,236,170,.6),rgba(255,253,246,.97) 72%),#fffdf6}
    .co-done[hidden]{display:none}
    .co-done-in{max-width:480px;text-align:center;animation:co-rise .4s ease both}
    .co-ok{display:grid;place-items:center;width:66px;height:66px;margin:0 auto 18px;border-radius:50%;background:#2e7d5b;color:#fff;font-size:30px;box-shadow:0 0 0 10px rgba(46,125,91,.12);animation:co-pop .5s .1s cubic-bezier(.2,1.5,.4,1) both}
    .co-ord{display:block;margin-bottom:10px;font-size:8.5px;font-weight:700;letter-spacing:.1em;color:#b0402a}
    .co-done h2{margin-bottom:12px;font-family:'Lora',Georgia,serif;font-size:34px;font-weight:500;line-height:1.15;color:#3b2620}
    .co-done p{max-width:340px;margin:0 auto 22px;font-size:11px;line-height:1.7;color:#5b4d42}
    .co-info{display:grid;grid-template-columns:1fr 1fr;max-width:400px;margin:0 auto 22px;border:1px solid #eae2d3;border-radius:12px;background:#fff}
    .co-info div{padding:14px 10px}.co-info div+div{border-left:1px solid #eae2d3}
    .co-info small{display:block;margin-bottom:6px;font-size:8.5px;color:#8a7f74}
    .co-info b{font-family:'Lora',Georgia,serif;font-size:21px;font-weight:500;color:#3b2620}
    .co-go{display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border-radius:8px;background:#3b2620;color:#fff;font-size:10.5px;font-weight:600}
    .co-go:hover{background:#523830}
    .co-go .icon{font-size:12px}
    @keyframes co-rise{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
    @keyframes co-pop{from{opacity:0;transform:scale(.4)}to{opacity:1;transform:none}}
    @media(prefers-reduced-motion:reduce){.co-done-in,.co-ok{animation:none}}

    @media(max-width:860px){.co-grid{grid-template-columns:1fr}.co-bar-in{grid-template-columns:1fr auto}.co-steps{display:none}.co-title{flex-direction:column}}
</style>

<a href="{{ route('dashboard') }}" class="co-back"><svg class="icon"><use href="#co-arrow-l"/></svg>Kembali ke keranjang</a>

<div class="co-title">
    <div>
        <h1>Checkout Pesanan</h1>
        <p>Kopi pilihanmu, tinggal satu langkah lagi. Cek pesanan dan pilih pembayaran.</p>
    </div>
    <span class="co-shop"><svg class="icon"><use href="#co-cup"/></svg>{{ $cafeName }}</span>
</div>

<form method="post" action="{{ $payUrl }}" class="co-grid" id="co-form">
    @csrf

    {{-- ===== Kiri ===== --}}
    <div>
        <section class="co-card">
            <h3>Tipe pesanan</h3>
            <div class="co-seg">
                <label><input type="radio" name="type" value="dine-in" checked><span>Makan di Tempat (Dine-in)</span></label>
                <label><input type="radio" name="type" value="takeaway"><span>Bawa Pulang (Takeaway)</span></label>
            </div>
            <div class="co-field" id="table-field">
                <label for="table">Nomor Meja</label>
                <div class="co-input"><svg class="icon"><use href="#co-table"/></svg><input type="text" id="table" name="table" value="12" inputmode="numeric" maxlength="3"></div>
                <small class="co-hint">Lihat nomor meja di tempat kamu duduk.</small>
            </div>
        </section>

        <section class="co-card">
            <div class="co-row"><h3>Ringkasan pesanan</h3><span id="co-count">{{ count($items) }} item</span></div>
            <div id="co-items">
                @foreach ($items as [$name, $desc, $price, $qty, $slug])
                    <div class="co-item" data-price="{{ $price }}" data-qty="{{ $qty }}">
                        <div class="co-th">
                            @if (file_exists(public_path("images/menu/$slug.jpg")))<img src="{{ asset("images/menu/$slug.jpg") }}" alt="{{ $name }}">@else<svg class="icon"><use href="#co-cup"/></svg>@endif
                        </div>
                        <div>
                            <b>{{ $name }}</b>
                            @if ($desc)<small>{{ $desc }}</small>@endif
                            <div class="co-line">
                                <div class="co-step"><button type="button" data-step="-1" aria-label="Kurangi">−</button><span class="co-q">{{ $qty }}</span><button type="button" data-step="1" aria-label="Tambah">+</button></div>
                                <span class="co-price">{{ $rp($price * $qty) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="co-empty" id="co-empty" hidden>Pesananmu kosong. <a href="{{ route('explore') }}">Cari cafe</a></p>
        </section>

        <div class="co-note">
            <svg class="icon"><use href="#co-cup"/></svg>
            <div><b>Dibuat segar, diantar ke mejamu.</b>Barista akan menyiapkan pesanan setelah pembayaran berhasil.</div>
        </div>
    </div>

    {{-- ===== Kanan ===== --}}
    <div>
        <section class="co-card">
            <h3>Metode pembayaran</h3>
            @foreach ($methods as [$code, $title, $icon, $tags, $help, $btn])
                <label class="co-pay">
                    <input type="radio" name="method" value="{{ $code }}" data-btn="{{ $btn }}" required>
                    <span class="dot"></span>
                    <span class="t">{{ $title }}</span>
                    <svg class="icon"><use href="#{{ $icon }}"/></svg>
                    <span class="more">
                        <span class="tags">@foreach ($tags as $t)<em>{{ $t }}</em>@endforeach</span>
                        <small>{{ $help }}</small>
                    </span>
                </label>
            @endforeach
        </section>

        <section class="co-card co-sum">
            <h3>Rincian pembayaran</h3>
            <div><span>Subtotal</span><b id="co-sub">{{ $rp($subtotal) }}</b></div>
            <div><span>Tax &amp; Service {{ (int) ($taxRate * 100) }}%</span><b id="co-tax">{{ $rp($tax) }}</b></div>
            <div class="tot"><span>Total Payment</span><b id="co-total">{{ $rp($total) }}</b></div>
        </section>

        <button type="submit" class="co-submit" id="co-submit"><span id="co-label">Bayar Sekarang</span> (<span id="co-btn-total">{{ $rp($total) }}</span>) <svg class="icon"><use href="#co-arrow-r"/></svg></button>
        <p class="co-safe"><svg class="icon"><use href="#co-shield"/></svg>Pembayaran digital aman &amp; terenkripsi</p>
    </div>
</form>

<div class="co-foot"><span>© 2026 Coffenfo · Setiap cangkir, sebuah cerita.</span><b>Butuh bantuan? Hubungi cafe</b></div>

{{-- Pop-out "Pesanan berhasil" (muncul setelah tombol bayar ditekan) --}}
<div class="co-done" id="co-done" role="dialog" aria-modal="true" aria-labelledby="co-done-title" hidden>
    <div class="co-done-in">
        <div class="co-ok"><svg class="icon"><use href="#co-tick"/></svg></div>
        <small class="co-ord" id="co-ord">PESANAN #OX-1051</small>
        <h2 id="co-done-title">Pesananmu berhasil dibuat!</h2>
        <p>Barista sedang menyiapkan pesananmu. Kamu akan mendapat notifikasi saat pesanan siap diambil.</p>
        <div class="co-info"><div><small>Estimasi siap</small><b>12–15 menit</b></div><div><small>Total pembayaran</small><b id="co-done-total">{{ $rp($total) }}</b></div></div>
        <a href="{{ route('explore') }}" class="co-go" id="co-go">Kembali jelajahi cafe <svg class="icon"><use href="#co-arrow-r"/></svg></a>
    </div>
</div>

<script id="checkout-js">
    (function () {
        const TAX = {{ $taxRate }};
        const rp = n => 'Rp ' + n.toLocaleString('id-ID');
        const $ = id => document.getElementById(id);

        // --- Dine-in / Takeaway: nomor meja hanya untuk dine-in ---
        const tableField = $('table-field'), tableInput = $('table');
        document.querySelectorAll('input[name="type"]').forEach(r => r.addEventListener('change', () => {
            const dine = r.value === 'dine-in' && r.checked;
            if (r.checked) { tableField.hidden = !dine; tableInput.required = dine; }
        }));

        // --- Ringkasan: ubah jumlah, hitung ulang semuanya ---
        function recalc() {
            let sub = 0, lines = 0;
            document.querySelectorAll('#co-items .co-item').forEach(row => { sub += Number(row.dataset.price) * Number(row.dataset.qty); lines++; });
            const tax = Math.round(sub * TAX), total = sub + tax;
            $('co-sub').textContent = rp(sub);
            $('co-tax').textContent = rp(tax);
            $('co-total').textContent = rp(total);
            $('co-btn-total').textContent = rp(total);
            $('co-count').textContent = lines + ' item';
            $('co-empty').hidden = lines > 0;
            $('co-submit').disabled = lines === 0;
        }
        document.querySelectorAll('#co-items .co-item').forEach(row => {
            const unit = Number(row.dataset.price);
            row.querySelectorAll('[data-step]').forEach(btn => btn.addEventListener('click', () => {
                const qty = Math.max(0, Number(row.dataset.qty) + Number(btn.dataset.step));
                row.dataset.qty = qty;
                row.querySelector('.co-q').textContent = qty;
                row.querySelector('.co-price').textContent = rp(unit * qty);
                if (qty === 0) row.remove();
                recalc();
            }));
        });

        // --- Tulisan tombol mengikuti metode pembayaran (E-Wallet: Bayar Sekarang, Transfer: Buat Pesanan) ---
        document.querySelectorAll('input[name="method"]').forEach(r => r.addEventListener('change', () => {
            if (r.checked) $('co-label').textContent = r.dataset.btn;
        }));

        // --- Kirim pesanan: tampilkan pop-out "Pesanan berhasil" ---
        const form = $('co-form'), done = $('co-done');
        document.body.append(done);   // agar menutupi seluruh layar
        form.addEventListener('submit', e => {
            e.preventDefault();   // SEMENTARA: nanti kirim ke server, lalu tampilkan pop-out dari hasilnya
            $('co-ord').textContent = 'PESANAN #OX-' + Math.floor(1000 + Math.random() * 9000);
            $('co-done-total').textContent = $('co-total').textContent;
            done.hidden = false;
            document.body.style.overflow = 'hidden';
            $('co-go').focus();
        });
    })();
</script>
@endsection