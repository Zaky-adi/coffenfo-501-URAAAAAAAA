@extends('layouts.app')

@section('title', 'Semua Pesanan')

@section('content')
@php
    // ----- Data contoh. Nanti diganti data dari database. -----
    // items: [nama, jumlah, harga satuan, slug foto atau null]
    $orders = [
        ['shop' => 'Kopi Senja Nagoya',    'id' => '#CF-100718-C04', 'date' => '4 Nov 2026, 14:31',  'status' => 'Diproses',
         'items' => [['Manual Brew in House Beans', 1, 45000, 'manual-brew'], ['Salted Egg Chicken', 1, 45000, 'salted-egg']], 'actions' => ['Lacak Pesanan']],
        ['shop' => 'Kopi Senja Nagoya',    'id' => '#CF-091024-B17', 'date' => '3 Nov 2026, 10:07',  'status' => 'Selesai',
         'items' => [['Es Kopi Susu Aren', 2, 28000, null]], 'actions' => ['Pesan Lagi', 'Tulis Review']],
        ['shop' => 'Kopi Senja Nagoya',    'id' => '#CF-081011-A09', 'date' => '1 Nov 2026, 09:08',  'status' => 'Dibatalkan',
         'items' => [['Croissant Almond', 1, 32000, null]], 'actions' => ['Pesan Lagi']],
        ['shop' => 'Grind Now Coffee Bar', 'id' => '#CF-071902-D21', 'date' => '28 Okt 2026, 19:20', 'status' => 'Selesai',
         'items' => [['Magic', 1, 45000, 'magic'], ['Meatballs Bolognese', 1, 46000, 'meatballs']], 'actions' => ['Pesan Lagi', 'Tulis Review']],
        ['shop' => 'Temu Kopi',            'id' => '#CF-061555-E33', 'date' => '25 Okt 2026, 15:55', 'status' => 'Selesai',
         'items' => [['Jungle Mint', 2, 44000, 'jungle-mint']], 'actions' => ['Pesan Lagi', 'Tulis Review']],
        ['shop' => 'Ruang Seduh',          'id' => '#CF-051230-F12', 'date' => '20 Okt 2026, 12:30', 'status' => 'Dibatalkan',
         'items' => [['Flat White', 1, 32000, null]], 'actions' => ['Pesan Lagi']],
    ];
    $statusClass = ['Diproses' => 'proc', 'Selesai' => 'done', 'Dibatalkan' => 'cancel'];
    $actionMap   = ['Lacak Pesanan' => ['dark', '#'], 'Pesan Lagi' => ['line', route('explore')], 'Tulis Review' => ['dark', route('reviews')]];
    $filters     = ['Semua', 'Diproses', 'Selesai', 'Dibatalkan'];
    $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
@endphp

<style>
    /* ===== Halaman Semua Pesanan (kelas diawali "ord-") ===== */
    .ord-head{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;margin:10px 0 22px}
    .ord-head small{display:block;font-size:9px;font-weight:700;letter-spacing:.06em;color:#cd7f3a;margin-bottom:6px}
    .ord-head h1{font-family:'Lora',Georgia,serif;font-size:32px;font-weight:500;line-height:1.1}
    .ord-head p{margin-top:6px;font-size:11.5px;color:#8a7f74}
    .ord-pills{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px}
    .ord-pill{display:inline-flex;align-items:center;gap:7px;padding:7px 15px;border:1px solid #eae2d3;border-radius:99px;background:#fff;font:500 11px 'Plus Jakarta Sans',sans-serif;color:#3b2620;cursor:pointer}
    .ord-pill i{font-style:normal;font-size:9px;padding:1px 7px;border-radius:99px;background:#f4ede2}
    .ord-pill.on{background:#3b2620;border-color:#3b2620;color:#fff}
    .ord-pill.on i{background:rgba(255,255,255,.2)}
    .ord-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;align-items:start}
    .ord-card{padding:18px;border:1px solid #eae2d3;border-radius:14px;background:#fff}
    .ord-card[hidden]{display:none}
    .ord-top{display:flex;justify-content:space-between;gap:10px;margin-bottom:12px}
    .ord-top strong{display:block;font-size:13px}
    .ord-top small{display:block;margin-top:3px;font-size:9.5px;color:#8a7f74}
    .ord-st{align-self:flex-start;padding:4px 11px;border-radius:99px;font-size:9.5px;font-weight:700}
    .ord-st.proc{background:#fbe9d6;color:#b0602a}.ord-st.done{background:#dff0e3;color:#2f7d4f}.ord-st.cancel{background:#fde2e2;color:#b03a3a}
    .ord-item{display:grid;grid-template-columns:40px minmax(0,1fr) auto;gap:12px;align-items:center;padding:8px 0}
    .ord-th{position:relative;display:grid;place-items:center;width:40px;height:40px;border-radius:8px;background:#f4ede2;color:#cd7f3a;font-size:16px;overflow:hidden}
    .ord-th img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
    .ord-item b{display:block;font-size:12px}
    .ord-item small{font-size:10px;color:#8a7f74}
    .ord-item span{font-size:12px;font-weight:700}
    .ord-total{display:flex;justify-content:space-between;align-items:center;margin:8px 0 14px;padding-top:12px;border-top:1px solid #eae2d3;font-size:11px;color:#8a7f74}
    .ord-total b{font-size:15px;color:#3b2620}
    .ord-acts{display:grid;gap:8px}.ord-acts.two{grid-template-columns:1fr 1fr}
    .ord-btn{display:flex;align-items:center;justify-content:center;height:38px;border:1px solid #3b2620;border-radius:9px;background:#3b2620;color:#fff;font:600 11.5px 'Plus Jakarta Sans',sans-serif;text-decoration:none}
    .ord-btn:hover{background:#523830}
    .ord-btn.line{background:#fff;color:#3b2620;border-color:#d8cdb8}
    .ord-btn.line:hover{background:#f4ede2}
    .ord-empty{padding:60px 0;text-align:center;font-size:13px;color:#8a7f74}
    .ord-empty[hidden]{display:none}
    @media(max-width:760px){.ord-grid{grid-template-columns:1fr}.ord-head{flex-direction:column;align-items:flex-start}}
</style>

<nav class="crumb"><a href="{{ url('/') }}">Beranda</a> › <b>Semua Pesanan</b></nav>

<div class="ord-head">
    <div>
        <small>PESANANMU</small>
        <h1>Semua Pesanan</h1>
        <p>Riwayat lengkap pesananmu di Coffenfo.</p>
    </div>
    <a href="{{ route('explore') }}" class="ord-btn" style="padding:0 20px">Pesan lagi</a>
</div>

<div class="ord-pills" id="ord-filter">
    @foreach ($filters as $f)
        <button type="button" class="ord-pill {{ $loop->first ? 'on' : '' }}" data-f="{{ $f }}">{{ $f }}<i>{{ $f === 'Semua' ? count($orders) : collect($orders)->where('status', $f)->count() }}</i></button>
    @endforeach
</div>

<div class="ord-grid" id="ord-grid">
    @foreach ($orders as $o)
        <article class="ord-card" data-status="{{ $o['status'] }}">
            <div class="ord-top">
                <div><strong>{{ $o['shop'] }}</strong><small>{{ $o['id'] }}</small><small>{{ $o['date'] }}</small></div>
                <span class="ord-st {{ $statusClass[$o['status']] }}">{{ $o['status'] }}</span>
            </div>
            @php $total = 0; @endphp
            @foreach ($o['items'] as [$name, $qty, $price, $slug])
                @php $total += $qty * $price; @endphp
                <div class="ord-item">
                    <div class="ord-th">
                        @if ($slug && file_exists(public_path("images/menu/$slug.jpg")))<img src="{{ asset("images/menu/$slug.jpg") }}" alt="{{ $name }}">@else<svg class="icon"><use href="#i-cup"/></svg>@endif
                    </div>
                    <div><b>{{ $name }}</b><small>Qty {{ $qty }}</small></div>
                    <span>{{ $rp($price) }}</span>
                </div>
            @endforeach
            <div class="ord-total"><span>Total</span><b>{{ $rp($total) }}</b></div>
            <div class="ord-acts {{ count($o['actions']) > 1 ? 'two' : '' }}">
                @foreach ($o['actions'] as $a)<a href="{{ $actionMap[$a][1] }}" class="ord-btn {{ $actionMap[$a][0] === 'line' ? 'line' : '' }}">{{ $a }}</a>@endforeach
            </div>
        </article>
    @endforeach
</div>
<p class="ord-empty" id="ord-empty" hidden>Belum ada pesanan dengan status ini.</p>

<script id="orders-js">
    // Filter status pesanan
    const pills = document.querySelectorAll('#ord-filter .ord-pill');
    const cards = document.querySelectorAll('#ord-grid .ord-card');
    pills.forEach(p => p.addEventListener('click', () => {
        pills.forEach(x => x.classList.toggle('on', x === p));
        let shown = 0;
        cards.forEach(c => {
            const hide = p.dataset.f !== 'Semua' && c.dataset.status !== p.dataset.f;
            c.hidden = hide;
            if (!hide) shown++;
        });
        document.getElementById('ord-empty').hidden = shown > 0;
    }));
</script>
@endsection