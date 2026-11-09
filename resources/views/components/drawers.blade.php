@php
    // ----- Data contoh. Nanti diganti data dari database / session. -----
    // Keranjang: [nama, deskripsi, harga satuan, jumlah, slug foto]
    $cart = [
        ['Manual Brew in House Beans', 'Signature house selection designed for balance clarity', 45000, 1, 'manual-brew'],
        ['Salted Egg Chicken',         '',                                                        45000, 1, 'salted-egg'],
    ];

    // Riwayat pesanan. items: [nama, jumlah, harga satuan, slug foto atau null]
    $orders = [
        ['shop' => 'Kopi Senja Nagoya', 'id' => '#CF-100718-C04', 'date' => '4 Nov 2026, 14:31', 'status' => 'Diproses',
         'items' => [['Manual Brew in House Beans', 1, 45000, 'manual-brew'], ['Salted Egg Chicken', 1, 45000, 'salted-egg']], 'actions' => ['Lacak Pesanan']],
        ['shop' => 'Kopi Senja Nagoya', 'id' => '#CF-091024-B17', 'date' => '3 Nov 2026, 10:07', 'status' => 'Selesai',
         'items' => [['Es Kopi Susu Aren', 2, 28000, null]], 'actions' => ['Pesan Lagi', 'Tulis Review']],
        ['shop' => 'Kopi Senja Nagoya', 'id' => '#CF-081011-A09', 'date' => '1 Nov 2026, 09:08', 'status' => 'Dibatalkan',
         'items' => [['Croissant Almond', 1, 32000, null]], 'actions' => ['Pesan Lagi']],
    ];
    $statusClass = ['Diproses' => 'proc', 'Selesai' => 'done', 'Dibatalkan' => 'cancel'];
    $actionMap   = ['Lacak Pesanan' => ['dark', '#'], 'Pesan Lagi' => ['line', route('explore')], 'Tulis Review' => ['dark', route('reviews')]];
    $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
@endphp

<style>
    /* ===== Laci Keranjang & Riwayat Pesanan (semua kelas diawali "cf-" agar tidak bentrok) ===== */
    .cf-dr,.cf-dr *{box-sizing:border-box}
    .cf-ov{position:fixed;inset:0;z-index:9990;background:rgba(59,38,32,.38);opacity:0;transition:opacity .25s}
    .cf-ov.show{opacity:1}
    .cf-ov[hidden]{display:none}

    .cf-dr{position:fixed;top:14px;right:14px;left:auto;bottom:auto;z-index:9999;display:flex;flex-direction:column;align-items:stretch;justify-content:flex-start;
           width:min(400px,calc(100vw - 28px));max-height:calc(100vh - 28px);margin:0;padding:0;border:0;border-radius:16px;overflow:hidden;
           background:#fffdf8;color:#3b2620;font-family:'Plus Jakarta Sans',system-ui,sans-serif;text-align:left;
           box-shadow:0 18px 50px rgba(59,38,32,.24);transform:translateX(125%);transition:transform .28s ease;visibility:hidden}
    .cf-dr.open{transform:none;visibility:visible}
    .cf-dr.tall{height:calc(100vh - 28px)}

    .cf-head{display:flex;justify-content:space-between;align-items:flex-start;width:100%;padding:24px 24px 14px}
    .cf-head small{display:block;font-size:9px;font-weight:700;letter-spacing:.06em;color:#cd7f3a;margin-bottom:6px}
    .cf-head h2{margin:0;font-family:'Lora',Georgia,serif;font-size:28px;font-weight:500;line-height:1.1;text-align:left}
    .cf-x{flex:none;width:30px;height:30px;border:1px solid #eae2d3;border-radius:50%;background:#fff;font-size:13px;line-height:1;color:#3b2620;cursor:pointer}
    .cf-x:hover{background:#f4ede2}
    .cf-line{height:0;margin:0 24px;border:0;border-top:1px solid #e3c9a8}
    .cf-body{flex:1 1 auto;min-height:0;width:100%;overflow-y:auto;padding:4px 24px 14px}
    .cf-foot{margin:0 24px;padding:16px 0 22px;border-top:1px solid #e3c9a8}
    .cf-tall-foot{margin:0;padding:16px 24px 22px;border-top:1px solid #eae2d3}
    .cf-btn{display:flex;align-items:center;justify-content:center;width:100%;height:42px;margin:0;border:1px solid #3b2620;border-radius:9px;background:#3b2620;color:#fff;font:600 12px 'Plus Jakarta Sans',sans-serif;text-decoration:none;cursor:pointer}
    .cf-btn:hover{background:#523830}
    .cf-btn.line{background:#fff;color:#3b2620;border-color:#d8cdb8}
    .cf-btn.line:hover{background:#f4ede2}
    .cf-btn[aria-disabled=true]{opacity:.4;pointer-events:none}

    /* keranjang */
    .cf-item{display:grid;grid-template-columns:84px minmax(0,1fr);gap:14px;align-items:start;padding:16px 0;border-bottom:1px solid #eae2d3}
    .cf-item:last-of-type{border-bottom:0}
    .cf-th{position:relative;display:grid;place-items:center;width:100%;aspect-ratio:1.1/1;border-radius:9px;background:#f4ede2;color:#cd7f3a;font-size:22px;overflow:hidden}
    .cf-th img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
    .cf-item b{display:block;font-size:12.5px;line-height:1.3}
    .cf-item small{display:block;margin-top:4px;font-size:9.5px;line-height:1.5;color:#8a7f74}
    .cf-row{display:flex;justify-content:space-between;align-items:center;margin-top:10px}
    .cf-step{display:inline-flex;align-items:center;gap:8px;font-size:10.5px}
    .cf-step button{width:21px;height:21px;padding:0;border:1px solid #d8cdb8;border-radius:5px;background:#fff;font-size:13px;line-height:1;color:#3b2620;cursor:pointer}
    .cf-step button:hover{background:#f4ede2}
    .cf-q{min-width:12px;text-align:center;font-weight:600}
    .cf-price{font-size:12.5px;font-weight:700;white-space:nowrap}
    .cf-empty{padding:40px 0;text-align:center;font-size:12px;color:#8a7f74}
    .cf-empty[hidden]{display:none}

    /* riwayat */
    .cf-pills{display:flex;flex-wrap:wrap;gap:7px;padding:4px 0 16px}
    .cf-pill{padding:6px 13px;border:1px solid #eae2d3;border-radius:99px;background:#fff;font:500 10px 'Plus Jakarta Sans',sans-serif;color:#3b2620;cursor:pointer}
    .cf-pill.on{background:#3b2620;border-color:#3b2620;color:#fff}
    .cf-order{margin-bottom:14px;padding:14px;border:1px solid #eae2d3;border-radius:12px;background:#fff}
    .cf-order[hidden]{display:none}
    .cf-top{display:flex;justify-content:space-between;gap:10px;margin-bottom:12px}
    .cf-top strong{display:block;font-size:11.5px}
    .cf-top small{display:block;margin-top:2px;font-size:8.5px;color:#8a7f74}
    .cf-st{align-self:flex-start;padding:3px 10px;border-radius:99px;font-size:8.5px;font-weight:700}
    .cf-st.proc{background:#fbe9d6;color:#b0602a}.cf-st.done{background:#dff0e3;color:#2f7d4f}.cf-st.cancel{background:#fde2e2;color:#b03a3a}
    .cf-oi{display:grid;grid-template-columns:36px minmax(0,1fr) auto;gap:10px;align-items:center;padding:7px 0}
    .cf-oi .cf-th{aspect-ratio:1/1;border-radius:7px;font-size:15px}
    .cf-oi b{display:block;font-size:10.5px}
    .cf-oi small{margin:0;font-size:9px;color:#8a7f74}
    .cf-oi span{font-size:10.5px;font-weight:700}
    .cf-total{display:flex;justify-content:space-between;align-items:center;margin:8px 0 12px;padding-top:10px;border-top:1px solid #eae2d3;font-size:9.5px;color:#8a7f74}
    .cf-total b{font-size:12.5px;color:#3b2620}
    .cf-acts{display:grid;gap:8px}.cf-acts.two{grid-template-columns:1fr 1fr}
    .cf-acts .cf-btn{height:34px;font-size:10.5px}
</style>

{{-- Latar gelap di belakang laci (klik untuk menutup) --}}
<div id="cf-ov" class="cf-ov" hidden data-close></div>

{{-- ===== Keranjang ===== --}}
<div id="cf-cart" class="cf-dr" role="dialog" aria-modal="true" aria-label="Keranjang" aria-hidden="true">
    <div class="cf-head"><div><small>PESANANMU</small><h2>Keranjang</h2></div><button type="button" class="cf-x" data-close aria-label="Tutup">✕</button></div>
    <hr class="cf-line">
    <div class="cf-body" id="cf-list">
        @foreach ($cart as [$name, $desc, $price, $qty, $slug])
            <div class="cf-item" data-price="{{ $price }}" data-qty="{{ $qty }}">
                <div class="cf-th">
                    @if (file_exists(public_path("images/menu/$slug.jpg")))<img src="{{ asset("images/menu/$slug.jpg") }}" alt="{{ $name }}">@else<svg class="icon"><use href="#i-cup"/></svg>@endif
                </div>
                <div>
                    <b>{{ $name }}</b>
                    @if ($desc)<small>{{ $desc }}</small>@endif
                    <div class="cf-row">
                        <div class="cf-step"><button type="button" data-step="-1" aria-label="Kurangi">−</button><span class="cf-q">{{ $qty }}</span><button type="button" data-step="1" aria-label="Tambah">+</button></div>
                        <span class="cf-price">{{ $rp($price * $qty) }}</span>
                    </div>
                </div>
            </div>
        @endforeach
        <p class="cf-empty" id="cf-empty" hidden>Keranjangmu masih kosong.</p>
    </div>
    <div class="cf-foot"><a href="{{ route('checkout') }}" class="cf-btn" id="cf-go">Lanjut Checkout</a></div>
</div>

{{-- ===== Riwayat Pesanan ===== --}}
<div id="cf-orders" class="cf-dr tall" role="dialog" aria-modal="true" aria-label="Riwayat Pesanan" aria-hidden="true">
    <div class="cf-head"><div><small>PESANANMU</small><h2>Riwayat Pesanan</h2></div><button type="button" class="cf-x" data-close aria-label="Tutup">✕</button></div>
    <hr class="cf-line">
    <div class="cf-body">
        <div class="cf-pills" id="cf-filter" style="padding-top:14px">
            @foreach (['Semua', 'Diproses', 'Selesai', 'Dibatalkan'] as $f)<button type="button" class="cf-pill {{ $loop->first ? 'on' : '' }}" data-f="{{ $f }}">{{ $f }}</button>@endforeach
        </div>

        @foreach ($orders as $o)
            <article class="cf-order" data-status="{{ $o['status'] }}">
                <div class="cf-top">
                    <div><strong>{{ $o['shop'] }}</strong><small>{{ $o['id'] }}</small><small>{{ $o['date'] }}</small></div>
                    <span class="cf-st {{ $statusClass[$o['status']] }}">{{ $o['status'] }}</span>
                </div>
                @php $total = 0; @endphp
                @foreach ($o['items'] as [$name, $qty, $price, $slug])
                    @php $total += $qty * $price; @endphp
                    <div class="cf-oi">
                        <div class="cf-th">
                            @if ($slug && file_exists(public_path("images/menu/$slug.jpg")))<img src="{{ asset("images/menu/$slug.jpg") }}" alt="{{ $name }}">@else<svg class="icon"><use href="#i-cup"/></svg>@endif
                        </div>
                        <div><b>{{ $name }}</b><small>Qty {{ $qty }}</small></div>
                        <span>{{ $rp($price) }}</span>
                    </div>
                @endforeach
                <div class="cf-total"><span>Total</span><b>{{ $rp($total) }}</b></div>
                <div class="cf-acts {{ count($o['actions']) > 1 ? 'two' : '' }}">
                    @foreach ($o['actions'] as $a)<a href="{{ $actionMap[$a][1] }}" class="cf-btn {{ $actionMap[$a][0] === 'line' ? 'line' : '' }}">{{ $a }}</a>@endforeach
                </div>
            </article>
        @endforeach
    </div>
    <div class="cf-tall-foot"><a href="#" class="cf-btn">Lihat Semua Pesanan</a></div>
</div>

<script id="drawers-js">
    (function () {
        const overlay = document.getElementById('cf-ov');
        const drawers = { cart: document.getElementById('cf-cart'), orders: document.getElementById('cf-orders') };

        // Pindahkan ke <body> supaya posisinya selalu relatif ke layar, apa pun induknya
        document.body.append(overlay, drawers.cart, drawers.orders);

        function openDrawer(name) {
            overlay.hidden = false;
            Object.entries(drawers).forEach(([key, el]) => {
                el.classList.toggle('open', key === name);
                el.setAttribute('aria-hidden', key !== name);
            });
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => overlay.classList.add('show'));
        }
        function closeDrawer() {
            overlay.classList.remove('show');
            Object.values(drawers).forEach(el => { el.classList.remove('open'); el.setAttribute('aria-hidden', true); });
            document.body.style.overflow = '';
            setTimeout(() => { overlay.hidden = true; }, 260);
        }

        // Tombol apa pun dengan data-open="cart" / "orders" membuka laci; data-close menutup
        document.addEventListener('click', e => {
            const opener = e.target.closest('[data-open]');
            if (opener && drawers[opener.dataset.open]) { e.preventDefault(); openDrawer(opener.dataset.open); return; }
            if (e.target.closest('[data-close]')) closeDrawer();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

        // --- Keranjang: tombol − dan + mengubah jumlah dan harga ---
        const rp = n => 'Rp ' + n.toLocaleString('id-ID');
        function refreshCart() {
            const empty = !document.querySelector('#cf-list .cf-item');
            document.getElementById('cf-empty').hidden = !empty;
            document.getElementById('cf-go').setAttribute('aria-disabled', empty);
        }
        document.querySelectorAll('#cf-list .cf-item').forEach(row => {
            const unit = Number(row.dataset.price);
            let qty = Number(row.dataset.qty);
            const out = row.querySelector('.cf-q'), price = row.querySelector('.cf-price');
            row.querySelectorAll('[data-step]').forEach(btn => btn.addEventListener('click', () => {
                qty = Math.max(0, qty + Number(btn.dataset.step));
                out.textContent = qty;
                price.textContent = rp(unit * qty);
                if (qty === 0) { row.remove(); refreshCart(); }
            }));
        });

        // --- Riwayat: filter status ---
        const pills = document.querySelectorAll('#cf-filter .cf-pill');
        pills.forEach(p => p.addEventListener('click', () => {
            pills.forEach(x => x.classList.toggle('on', x === p));
            document.querySelectorAll('#cf-orders .cf-order').forEach(c => { c.hidden = p.dataset.f !== 'Semua' && c.dataset.status !== p.dataset.f; });
        }));
    })();
</script>