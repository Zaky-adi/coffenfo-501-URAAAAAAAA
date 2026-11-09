@extends('layouts.app')

@section('title', 'Review & Rating')

@section('content')
@php
    // ----- Data contoh. Nanti diganti data dari database. -----
    $cafe = ['name' => 'Grind Now Coffe Bar', 'area' => 'Batam Center, Batam'];
    $dist = [5 => 78, 4 => 13, 3 => 4, 2 => 3, 1 => 2];   // persen per bintang

    // photos: jumlah foto contoh (kotak berwarna). ts: untuk pengurutan.
    $reviews = [
        ['id' => 1, 'name' => 'Dinda Pratiwi',   'ini' => 'DP', 'color' => '#c9783a', 'date' => '12 September 2026', 'ts' => '2026-09-12', 'rating' => 5, 'photos' => 6, 'helpful' => 12,
         'text' => 'Single origin-nya juara, aromanya wangi dan rasanya bersih. Tempatnya nyaman untuk kerja, stopkontak banyak dan WiFi kencang. Barista juga ramah dan sabar menjelaskan beans.'],
        ['id' => 2, 'name' => 'Rizky Hakim',     'ini' => 'RH', 'color' => '#6b8f5a', 'date' => '10 September 2026', 'ts' => '2026-09-10', 'rating' => 4, 'photos' => 0, 'helpful' => 8,
         'text' => 'Area outdoor-nya enak untuk ngobrol sore. Seduhan V60 pas, hanya saja antreannya agak panjang saat akhir pekan. Tetap akan balik lagi.'],
        ['id' => 3, 'name' => 'Sari Wulandari',  'ini' => 'SW', 'color' => '#8a5a8a', 'date' => '19 Agustus 2026',   'ts' => '2026-08-19', 'rating' => 4, 'photos' => 0, 'helpful' => 5,
         'text' => 'Suasana sederhana tapi hangat. Matcha latte dan croissant almond-nya enak. Harga masih masuk akal untuk kualitasnya.'],
        ['id' => 4, 'name' => 'Bima Saputra',    'ini' => 'BS', 'color' => '#4f7fa3', 'date' => '2 Agustus 2026',    'ts' => '2026-08-02', 'rating' => 5, 'photos' => 3, 'helpful' => 9,
         'text' => 'Manual brew-nya detail banget, ditanya mau karakter fruity atau seimbang. Rekomendasi untuk pecinta kopi serius.'],
        ['id' => 5, 'name' => 'Nadia Putri',     'ini' => 'NP', 'color' => '#b0602a', 'date' => '24 Juli 2026',      'ts' => '2026-07-24', 'rating' => 3, 'photos' => 0, 'helpful' => 2,
         'text' => 'Kopinya enak, tapi pesanan makanan agak lama datangnya. Semoga bisa lebih cepat.'],
        ['id' => 6, 'name' => 'Raka Aditya',     'ini' => 'RA', 'color' => '#6b8f5a', 'date' => '11 Juli 2026',      'ts' => '2026-07-11', 'rating' => 5, 'photos' => 2, 'helpful' => 6,
         'text' => 'Tempat favorit untuk kerja santai. Es kopi susu gula arennya konsisten enak.'],
        ['id' => 7, 'name' => 'Maya Lestari',    'ini' => 'ML', 'color' => '#8a5a8a', 'date' => '28 Juni 2026',      'ts' => '2026-06-28', 'rating' => 4, 'photos' => 0, 'helpful' => 3,
         'text' => 'Suasananya tenang di pagi hari. Parkir cukup luas.'],
        ['id' => 8, 'name' => 'Fajar Nugroho',   'ini' => 'FN', 'color' => '#c9783a', 'date' => '9 Juni 2026',       'ts' => '2026-06-09', 'rating' => 5, 'photos' => 4, 'helpful' => 7,
         'text' => 'Salted egg chicken rice bowl-nya mengejutkan enaknya. Porsinya pas.'],
    ];
    $data = collect($reviews)->map(fn ($r) => array_merge($r, ['photos' => array_fill(0, $r['photos'], null)]))->all();
@endphp

<style>
    /* ===== Halaman Review & Rating (kelas diawali "rv-") ===== */
    .rv-title{font-size:24px;font-weight:700;color:#3b2620}
    .rv-sub{margin:6px 0 22px;font-size:11px;color:#8a7f74}
    .rv-grid{display:grid;grid-template-columns:300px minmax(0,1fr);gap:22px;align-items:start}
    .rv-side{display:grid;gap:14px;position:sticky;top:80px}
    .rv-card0{padding:20px;border:1px solid #eae2d3;border-radius:14px;background:#fff}
    .rv-big{font-family:'Lora',Georgia,serif;font-size:44px;font-weight:500;line-height:1;color:#3b2620}
    .rv-big small{font-family:'Plus Jakarta Sans',sans-serif;font-size:12px;color:#8a7f74;font-weight:400}
    .rv-starsrow{margin:10px 0 4px;color:#e8a317;letter-spacing:2px;font-size:15px}
    .rv-based{font-size:10px;color:#8a7f74;margin-bottom:16px}
    .rv-bars{display:grid;gap:9px;margin-bottom:18px}
    .rv-bars div{display:grid;grid-template-columns:26px 1fr 30px;gap:8px;align-items:center;font-size:10px;color:#5b4d42}
    .rv-bars i{display:block;height:6px;border-radius:9px;background:#f0e8da;overflow:hidden}
    .rv-bars i::after{content:"";display:block;height:100%;width:var(--w);background:#cd7f3a;border-radius:9px}
    .rv-bars span{text-align:right;color:#8a7f74}
    .rv-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;height:40px;border:1px solid #3b2620;border-radius:9px;background:#3b2620;color:#fff;font:600 11.5px 'Plus Jakarta Sans',sans-serif;text-decoration:none;cursor:pointer}
    .rv-btn:hover{background:#523830}
    .rv-btn.line{background:#fff;color:#3b2620;border-color:#d8cdb8}
    .rv-btn.line:hover{background:#f4ede2}
    .rv-btn .icon{font-size:13px}

    .rv-head{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px}
    .rv-head h2{font-size:15px;font-weight:700}
    .rv-tools{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
    .rv-pill{padding:6px 13px;border:1px solid #eae2d3;border-radius:99px;background:#fff;font:500 10.5px 'Plus Jakarta Sans',sans-serif;color:#3b2620;cursor:pointer}
    .rv-pill.on{background:#3b2620;border-color:#3b2620;color:#fff}
    .rv-sort{padding:6px 10px;border:1px solid #eae2d3;border-radius:8px;background:#fff;font:500 10.5px 'Plus Jakarta Sans',sans-serif;color:#3b2620}
    .rv-list{display:grid;gap:14px}
    .rv-card{padding:18px;border:1px solid #eae2d3;border-radius:14px;background:#fff}
    .rv-who{display:flex;align-items:center;gap:10px}
    .rv-av{display:grid;place-items:center;width:34px;height:34px;border-radius:50%;color:#fff;font-size:10px;font-weight:700}
    .rv-who b{display:block;font-size:12px}.rv-who small{font-size:9.5px;color:#8a7f74}
    .rv-st{margin:10px 0 8px;font-size:12px;letter-spacing:1px}
    .rv-st .on{color:#e8a317}.rv-st .off{color:#e3dacb}
    .rv-card p{font-size:11.5px;line-height:1.7;color:#5b4d42}
    .rv-phs{display:flex;gap:8px;margin-top:12px}
    .rv-ph{display:grid;place-items:center;width:62px;height:62px;border-radius:9px;background-size:cover;background-position:center;color:rgba(255,255,255,.7);font-size:15px}
    .rv-ph.t0{background:linear-gradient(135deg,#c08a55,#6b4a35)}.rv-ph.t1{background:linear-gradient(135deg,#a37350,#523827)}
    .rv-ph.t2{background:linear-gradient(135deg,#d09a62,#7a5438)}.rv-ph.t3{background:linear-gradient(135deg,#8a6244,#3f2a1e)}
    .rv-ph.more{background:#3b2620;color:#fff;font-size:13px;font-weight:700}
    .rv-act{display:flex;justify-content:space-between;margin-top:14px;padding-top:12px;border-top:1px solid #eae2d3}
    .rv-act button{display:inline-flex;align-items:center;gap:6px;border:0;background:none;font:500 10px 'Plus Jakarta Sans',sans-serif;color:#5b4d42;cursor:pointer}
    .rv-act button:hover{color:#3b2620}
    .rv-act button.on{color:#cd7f3a;font-weight:700}
    .rv-act button:disabled{color:#a79d90;cursor:default}
    .rv-empty{padding:40px 0;text-align:center;font-size:12px;color:#8a7f74}
    .rv-more{margin-top:14px}
    [hidden]{display:none!important}

    /* modal */
    .rv-modal{position:fixed;inset:0;z-index:9999;display:grid;place-items:center;padding:16px;background:rgba(59,38,32,.62);overflow-y:auto}
    .rv-box{width:min(480px,100%);padding:24px;border-radius:16px;background:#fff;box-shadow:0 24px 60px rgba(0,0,0,.3)}
    .rv-mh{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:18px}
    .rv-mh h2{font-size:17px;font-weight:700}.rv-mh p{margin-top:3px;font-size:10px;color:#8a7f74}
    .rv-close{width:28px;height:28px;border:1px solid #eae2d3;border-radius:50%;background:#fff;font-size:12px;cursor:pointer;color:#3b2620}
    .rv-lab{display:block;margin:14px 0 8px;font-size:10.5px;font-weight:700}
    .rv-stars{display:flex;flex-direction:row-reverse;justify-content:flex-end;gap:4px}
    .rv-stars input{position:absolute;opacity:0;pointer-events:none}
    .rv-stars label{font-size:28px;line-height:1;color:#ddd3c2;cursor:pointer;transition:color .1s}
    .rv-stars input:checked ~ label,.rv-stars label:hover,.rv-stars label:hover ~ label{color:#e8a317}
    .rv-stars:has(input:focus-visible){outline:2px solid #cd7f3a;outline-offset:3px;border-radius:6px}
    .rv-ta{position:relative}
    .rv-ta textarea{width:100%;min-height:96px;padding:12px 14px 26px;border:1px solid #e8c9a8;border-radius:10px;background:#fffdf8;font:11.5px/1.6 'Plus Jakarta Sans',sans-serif;color:#3b2620;resize:vertical}
    .rv-ta textarea:focus{outline:0;border-color:#cd7f3a;box-shadow:0 0 0 3px rgba(205,127,58,.15)}
    .rv-ta span{position:absolute;right:12px;bottom:8px;font-size:9px;color:#a79d90}
    .rv-drop{display:block;padding:18px;border:1.5px dashed #e8c9a8;border-radius:12px;background:#fffaf3;text-align:center;cursor:pointer}
    .rv-drop.over{background:#fbeedd;border-color:#cd7f3a}
    .rv-drop input{position:absolute;opacity:0;width:0;height:0}
    .rv-drop .ic{display:grid;place-items:center;width:30px;height:30px;margin:0 auto 8px;border-radius:50%;background:#fbeedd;color:#cd7f3a;font-size:15px}
    .rv-drop b{display:block;font-size:11px}.rv-drop small{display:block;margin-top:4px;font-size:9px;color:#8a7f74}
    .rv-thumbs{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
    .rv-th{position:relative;display:grid;place-items:center;width:46px;height:46px;border-radius:8px;overflow:hidden;background:#f4ede2}
    .rv-th img{width:100%;height:100%;object-fit:cover}
    .rv-th button{position:absolute;top:2px;right:2px;width:16px;height:16px;padding:0;border:0;border-radius:50%;background:#3b2620;color:#fff;font-size:8px;line-height:1;cursor:pointer}
    .rv-th.add{border:1px dashed #cfc3ad;background:#fff;color:#5b4d42;font-size:16px;cursor:pointer}
    .rv-err{display:block;margin-top:6px;font-size:9.5px;color:#b03a3a}
    .rv-mf{display:grid;grid-template-columns:1fr 2fr;gap:10px;margin-top:22px}
    .rv-toast{position:fixed;left:50%;bottom:28px;z-index:10000;transform:translateX(-50%);padding:11px 18px;border-radius:99px;background:#3b2620;color:#fff;font-size:11px;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.25)}
    @media(max-width:860px){.rv-grid{grid-template-columns:1fr}.rv-side{position:static}}
</style>

{{-- Ikon khusus halaman ini --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="rv-pen" viewBox="0 0 24 24"><path d="M4 20l1-4L16.5 4.5a2.1 2.1 0 013 3L8 19l-4 1zM14 7l3 3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="rv-thumb" viewBox="0 0 24 24"><path d="M7 11v9H4v-9h3zM7 11l4-7c1.5 0 2.5 1 2.2 2.6L12.6 9H19a2 2 0 012 2.3l-1.2 7A2 2 0 0117.8 20H7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></symbol>
    <symbol id="rv-flag" viewBox="0 0 24 24"><path d="M5 21V4M5 4h11l-2 4 2 4H5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="rv-camera" viewBox="0 0 24 24"><path d="M4 8h3l1.5-2h7L17 8h3v11H4V8z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="12" cy="13.5" r="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/></symbol>
    <symbol id="rv-send" viewBox="0 0 24 24"><path d="M21 3L3 10.5l7 3 3 7L21 3zM10 13.5L21 3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></symbol>
</svg>

<nav class="crumb"><a href="{{ route('explore') }}">Jelajahi Cafe</a> › <a href="{{ route('dashboard') }}">{{ $cafe['name'] }}</a> › <b>Review &amp; Rating</b></nav>

<h1 class="rv-title">Review &amp; Rating</h1>
<p class="rv-sub">{{ $cafe['name'] }} · {{ $cafe['area'] }}</p>

<div class="rv-grid">
    {{-- ===== Ringkasan (kiri) ===== --}}
    <aside class="rv-side">
        <section class="rv-card0">
            <div class="rv-big">4.7 <small>/ 5.0</small></div>
            <div class="rv-starsrow">★★★★★</div>
            <div class="rv-based">Berdasarkan 120+ review</div>
            <div class="rv-bars">
                @foreach ($dist as $star => $pct)<div>{{ $star }} ★<i style="--w:{{ $pct }}%"></i><span>{{ $pct }}%</span></div>@endforeach
            </div>
            <button type="button" class="rv-btn" data-open><svg class="icon"><use href="#rv-pen"/></svg>Tulis Review</button>
        </section>
        <a href="{{ route('dashboard') }}" class="rv-btn">Kembali</a>
    </aside>

    {{-- ===== Daftar review (kanan) ===== --}}
    <section>
        <div class="rv-head">
            <h2><span id="rv-total">120</span> Review</h2>
            <div class="rv-tools">
                <div id="rv-filter" style="display:flex;gap:6px;flex-wrap:wrap">
                    <button type="button" class="rv-pill on" data-f="all">Semua</button>
                    <button type="button" class="rv-pill" data-f="photo">Dengan Foto</button>
                    <button type="button" class="rv-pill" data-f="5">★ 5</button>
                    <button type="button" class="rv-pill" data-f="4">★ 4</button>
                    <button type="button" class="rv-pill" data-f="3">★ 3</button>
                </div>
                <select class="rv-sort" id="rv-sort" aria-label="Urutkan review">
                    <option value="new">Urutkan: Terbaru</option>
                    <option value="high">Rating tertinggi</option>
                    <option value="low">Rating terendah</option>
                    <option value="help">Paling bermanfaat</option>
                </select>
            </div>
        </div>
        <div class="rv-list" id="rv-list"></div>
        <p class="rv-empty" id="rv-empty" hidden>Belum ada review untuk filter ini.</p>
        <button type="button" class="rv-btn line rv-more" id="rv-more">Muat Review Lainnya</button>
    </section>
</div>

{{-- ===== Modal Tulis Review ===== --}}
<div class="rv-modal" id="rv-modal" role="dialog" aria-modal="true" aria-labelledby="rv-mt" hidden>
    <form class="rv-box" id="rv-form" novalidate>
        <div class="rv-mh">
            <div><h2 id="rv-mt">Tulis Review</h2><p>{{ $cafe['name'] }} · {{ $cafe['area'] }}</p></div>
            <button type="button" class="rv-close" data-close aria-label="Tutup">✕</button>
        </div>

        <span class="rv-lab" style="margin-top:0">Beri rating</span>
        <div class="rv-stars" role="radiogroup" aria-label="Rating">
            @for ($n = 5; $n >= 1; $n--)<input type="radio" name="rating" id="rate{{ $n }}" value="{{ $n }}"><label for="rate{{ $n }}" title="{{ $n }} bintang">★</label>@endfor
        </div>
        <small class="rv-err" id="err-rating" hidden>Pilih rating dulu ya.</small>

        <label class="rv-lab" for="rv-text">Ceritakan pengalamanmu</label>
        <div class="rv-ta">
            <textarea id="rv-text" maxlength="500" placeholder="Bagikan pengalamanmu tentang kopi, suasana, atau layanan di cafe ini..."></textarea>
            <span id="rv-count">0 / 500</span>
        </div>
        <small class="rv-err" id="err-text" hidden>Tulis minimal 10 karakter.</small>

        <span class="rv-lab">Foto (opsional)</span>
        <label class="rv-drop" id="rv-drop" for="rv-file">
            <input type="file" id="rv-file" multiple accept="image/jpeg,image/png,image/webp">
            <span class="ic"><svg class="icon"><use href="#rv-camera"/></svg></span>
            <b>Unggah Foto Cafe / Kopi</b>
            <small>Tarik dan lepas foto di sini, atau klik untuk memilih</small>
            <small>JPG, PNG, WEBP · Maks 5 MB per foto</small>
        </label>
        <div class="rv-thumbs" id="rv-thumbs" hidden></div>
        <small class="rv-err" id="err-photo" hidden></small>

        <div class="rv-mf">
            <button type="button" class="rv-btn line" data-close>Batal</button>
            <button type="submit" class="rv-btn"><svg class="icon"><use href="#rv-send"/></svg>Kirim Review</button>
        </div>
    </form>
</div>
<div class="rv-toast" id="rv-toast" hidden></div>

<script id="reviews-js">
    (function () {
        const $ = id => document.getElementById(id);
        const DATA = @json($data);
        const PAGE = 3;
        let total = 120, filter = 'all', sort = 'new', shown = PAGE;

        const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        const stars = n => `<span class="on">${'★'.repeat(n)}</span><span class="off">${'★'.repeat(5 - n)}</span>`;
        const icon = id => `<svg class="icon"><use href="#${id}"/></svg>`;

        function tile(src, i) {
            return src ? `<div class="rv-ph" style="background-image:url('${src}')"></div>`
                       : `<div class="rv-ph t${i % 4}">${icon('rv-camera')}</div>`;
        }
        function photos(ph) {
            if (!ph.length) return '';
            const all = ph.length <= 4, list = all ? ph : ph.slice(0, 3);
            return `<div class="rv-phs">${list.map(tile).join('')}${all ? '' : `<div class="rv-ph more">+${ph.length - 3}</div>`}</div>`;
        }
        function card(r) {
            return `<article class="rv-card">
                <div class="rv-who"><span class="rv-av" style="background:${esc(r.color)}">${esc(r.ini)}</span><div><b>${esc(r.name)}</b><small>${esc(r.date)}</small></div></div>
                <div class="rv-st">${stars(r.rating)}</div>
                <p>${esc(r.text)}</p>
                ${photos(r.photos)}
                <div class="rv-act">
                    <button type="button" data-help="${r.id}">${icon('rv-thumb')} Bermanfaat (<span>${r.helpful}</span>)</button>
                    <button type="button" data-rep="${r.id}">${icon('rv-flag')} Laporkan Review</button>
                </div>
            </article>`;
        }

        function render() {
            let list = DATA.filter(r => filter === 'all' ? true : filter === 'photo' ? r.photos.length > 0 : r.rating === Number(filter));
            list.sort((a, b) =>
                sort === 'high' ? b.rating - a.rating || b.ts.localeCompare(a.ts) :
                sort === 'low'  ? a.rating - b.rating || b.ts.localeCompare(a.ts) :
                sort === 'help' ? b.helpful - a.helpful :
                b.ts.localeCompare(a.ts) || b.id - a.id);
            $('rv-list').innerHTML = list.slice(0, shown).map(card).join('');
            $('rv-empty').hidden = list.length > 0;
            $('rv-more').hidden = shown >= list.length;
            $('rv-total').textContent = total;
        }

        // --- Filter, urutan, muat lebih banyak ---
        const pills = document.querySelectorAll('#rv-filter .rv-pill');
        pills.forEach(p => p.addEventListener('click', () => {
            pills.forEach(x => x.classList.toggle('on', x === p));
            filter = p.dataset.f; shown = PAGE; render();
        }));
        $('rv-sort').addEventListener('change', e => { sort = e.target.value; shown = PAGE; render(); });
        $('rv-more').addEventListener('click', () => { shown += PAGE; render(); });

        // --- Bermanfaat & Laporkan ---
        $('rv-list').addEventListener('click', e => {
            const help = e.target.closest('[data-help]'), rep = e.target.closest('[data-rep]');
            if (help) {
                const r = DATA.find(x => x.id === Number(help.dataset.help));
                const on = help.classList.toggle('on');
                r.helpful += on ? 1 : -1;
                help.querySelector('span').textContent = r.helpful;
            }
            if (rep) { rep.disabled = true; rep.innerHTML = icon('rv-flag') + ' Dilaporkan'; toast('Terima kasih, laporanmu kami terima.'); }
        });

        function toast(msg) {
            const t = $('rv-toast'); t.textContent = msg; t.hidden = false;
            clearTimeout(toast.t); toast.t = setTimeout(() => t.hidden = true, 2800);
        }

        // --- Modal Tulis Review ---
        const modal = $('rv-modal'), form = $('rv-form'), text = $('rv-text');
        let files = [];
        document.body.append(modal, $('rv-toast'));   // agar menutupi seluruh layar

        function openModal() { modal.hidden = false; document.body.style.overflow = 'hidden'; document.querySelector('#rv-form input[name=rating]').focus(); }
        function closeModal() { modal.hidden = true; document.body.style.overflow = ''; }
        document.addEventListener('click', e => {
            if (e.target.closest('[data-open]')) openModal();
            else if (e.target.closest('[data-close]') || e.target === modal) closeModal();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.hidden) closeModal(); });

        text.addEventListener('input', () => { $('rv-count').textContent = text.value.length + ' / 500'; $('err-text').hidden = true; });
        form.addEventListener('change', e => { if (e.target.name === 'rating') $('err-rating').hidden = true; });

        // Foto: pilih / tarik-lepas, pratinjau, hapus
        function photoError(msg) { const el = $('err-photo'); el.textContent = msg; el.hidden = !msg; }
        function addFiles(list) {
            photoError('');
            for (const f of list) {
                if (!/^image\/(jpeg|png|webp)$/.test(f.type)) { photoError('Format foto harus JPG, PNG, atau WEBP.'); continue; }
                if (f.size > 5 * 1024 * 1024) { photoError('Ukuran foto maksimal 5 MB.'); continue; }
                if (files.length >= 5) { photoError('Maksimal 5 foto.'); break; }
                files.push({ file: f, url: URL.createObjectURL(f) });
            }
            renderThumbs();
        }
        function renderThumbs() {
            const box = $('rv-thumbs');
            box.hidden = files.length === 0;
            box.innerHTML = files.map((f, i) => `<div class="rv-th"><img src="${f.url}" alt="Foto ${i + 1}"><button type="button" data-rm="${i}" aria-label="Hapus foto">✕</button></div>`).join('')
                + (files.length < 5 ? '<label class="rv-th add" for="rv-file" title="Tambah foto">+</label>' : '');
        }
        $('rv-file').addEventListener('change', e => { addFiles(e.target.files); e.target.value = ''; });
        $('rv-thumbs').addEventListener('click', e => {
            const rm = e.target.closest('[data-rm]');
            if (rm) { const [f] = files.splice(Number(rm.dataset.rm), 1); URL.revokeObjectURL(f.url); photoError(''); renderThumbs(); }
        });
        const drop = $('rv-drop');
        ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('over'); }));
        ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('over'); }));
        drop.addEventListener('drop', e => addFiles(e.dataTransfer.files));

        // Kirim: validasi, tambahkan ke daftar paling atas
        form.addEventListener('submit', e => {
            e.preventDefault();   // SEMENTARA: nanti dikirim ke server
            const rating = Number((form.querySelector('input[name=rating]:checked') || {}).value || 0);
            const body = text.value.trim();
            $('err-rating').hidden = rating > 0;
            $('err-text').hidden = body.length >= 10;
            if (!rating || body.length < 10) return;

            DATA.unshift({
                id: Date.now(), name: 'Kamu', ini: 'KM', color: '#3b2620', date: 'Hari ini',
                ts: new Date().toISOString().slice(0, 10), rating, text: body, helpful: 0,
                photos: files.map(f => f.url)
            });
            total++; filter = 'all'; sort = 'new'; shown = PAGE;
            pills.forEach(x => x.classList.toggle('on', x.dataset.f === 'all'));
            $('rv-sort').value = 'new';
            form.reset(); files = []; renderThumbs(); photoError(''); $('rv-count').textContent = '0 / 500';
            closeModal(); render();
            toast('Review terkirim. Terima kasih!');
            $('rv-list').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        render();
    })();
</script>
@endsection