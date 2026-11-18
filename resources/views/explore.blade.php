@extends('layouts.app')

@section('title', 'Jelajahi Cafe')

@section('content')
@php
    // ----- Kelompok filter. Mau tambah pilihan? Tambah isi 'options'. -----
    // type: chip = tombol biasa, fac = tombol dengan lingkaran centang, rate = pilih satu (bintang)
    $coffee = [
        ['label' => 'Jenis Beans',    'name' => 'bean',  'type' => 'chip', 'input' => 'checkbox', 'options' => ['Single Origin', 'Blend']],
        ['label' => 'Roasting Level', 'name' => 'roast', 'type' => 'chip', 'input' => 'checkbox', 'options' => ['Light', 'Medium', 'Dark']],
    ];
    $general = [
        ['label' => 'Rentang harga', 'name' => 'price',    'type' => 'chip', 'input' => 'checkbox', 'options' => ['$', '$$', '$$$', '$$$$']],
        ['label' => 'Fasilitas',     'name' => 'facility', 'type' => 'fac',  'input' => 'checkbox', 'options' => ['WiFi', 'Outdoor', 'Stopkontak', 'Indoor AC']],
        ['label' => 'Rating',        'name' => 'rating',   'type' => 'rate', 'input' => 'radio',    'options' => ['3.0', '3.5', '4.0', '4.5']],
    ];

    // ----- Data contoh cafe. Nanti diganti data dari database. -----
    $c = fn ($name, $slug, $rating, $reviews, $address, $tags, $price, $dist, $close, $bg, $beans, $roast, $self, $fac)
        => compact('name', 'slug', 'rating', 'reviews', 'address', 'tags', 'price', 'dist', 'close', 'bg', 'beans', 'roast', 'self', 'fac') + ['feats' => ['i-bean', 'i-cup', 'ex-plug']];

    $nearby = [
        $c('Grind Now Coffee Bar',        'grind-now', '4.9', '1,200+', 'Perumahan Marina Blok 52 No. 5, Batam Center, Batam',     ['Single Origin & Blend', 'Medium Roast'], '$$',  '0.8', '20.00', '#6b4a35', ['Single Origin', 'Blend'], 'Medium', false, ['WiFi', 'Stopkontak', 'Indoor AC']),
        $c('Houda Coffee',                'houda',     '4.8', '770+',   'Ruko Kawasan Botanic Tower No. 3, Botani, Batam Kota',   ['Single Origin', 'Self-Brewing'],         '$$$', '1.2', '22.00', '#7d5a42', ['Single Origin'],         'Medium', true,  ['WiFi', 'Stopkontak', 'Indoor AC']),
        $c('Rubeka Rumah Panen & Kopi',   'rubeka',    '4.8', '330+',   'Taman Raya, Batam Center, Batam',                        ['Single Origin', 'Medium Roast'],         '$$',  '2.6', '22.00', '#8a6a55', ['Single Origin'],         'Medium', false, ['WiFi', 'Outdoor']),
    ];
    $popular = [
        $c('Meilleur Cafe & Patisserie',  'meilleur',   '4.7', '420+', 'Batam Center, Batam',                                 ['Ethiopia Sidamo', 'Light Roast', 'Coffee & Artisanal French Pastry (Croissant, Tart, Cakes)'], '$$',  '1.5', '22.00', '#9a7b62', ['Single Origin'],         'Light',  false, ['WiFi', 'Indoor AC']),
        $c('Sharlotte Cafe',              'sharlotte',  '4.6', '170+', 'Batam Center, Batam',                                 ['Java Preanger', 'Medium Roast', 'Specialty Coffee & Fresh Bakery / Dessert'],                  '$$$', '1.7', '21.00', '#a3845f', ['Single Origin'],         'Medium', false, ['WiFi', 'Stopkontak', 'Indoor AC']),
        $c('Lintas Coffee & Space',       'lintas',     '4.7', '550+', 'Batam Center, Batam',                                 ['Aceh Gayo Wine', 'Light Roast', 'Specialty Coffee & Manual Brew Focus'],                       '$$',  '3.0', '24.00', '#6b4a35', ['Single Origin'],         'Light',  true,  ['WiFi', 'Stopkontak', 'Outdoor']),
        $c('Chemistree Coffee & Eatery',  'chemistree', '4.6', '380+', 'Batam Center, Batam',                                 ['Toraja Sapan', 'Medium Roast', 'House Espresso Blend & Aesthetic Indoor Setting'],             '$$$', '2.2', '22.00', '#7d5a42', ['Single Origin', 'Blend'], 'Medium', false, ['WiFi', 'Indoor AC']),
        $c('Mula Patisserie',             'mula',       '4.7', '580+', 'Nuvo Greenland, Tiban Teling, Batam Center',          ['Ethiopia Yirgacheffe', 'Light Roast', 'Artisanal French Croque, Cakes, & Specialty Coffee'],   '$$$', '1.7', '21.00', '#8a6a55', ['Single Origin'],         'Light',  false, ['WiFi', 'Indoor AC']),
        $c('La Vie Patisserie & Cafe',    'la-vie',     '4.6', '310+', 'Ruko Victoria 2, Tiban Teling, Batam Center',         ['Java Preanger', 'Medium Roast', 'French Chocolate, Macarons, & Specialty Coffee'],            '$$',  '1.4', '22.00', '#9a7b62', ['Single Origin'],         'Medium', false, ['Outdoor', 'WiFi']),
    ];
@endphp

{{-- Ikon khusus halaman ini --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="ex-bookmark" viewBox="0 0 24 24"><path d="M6 3h12v18l-6-4-6 4z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></symbol>
    <symbol id="ex-plug" viewBox="0 0 24 24"><path d="M9 3v5M15 3v5M6 8h12v3a6 6 0 01-12 0V8zM12 17v4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="ex-sliders" viewBox="0 0 24 24"><path d="M4 7h10M18 7h2M4 17h2M10 17h10M14 4v6M6 14v6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></symbol>
</svg>

<style>
    /* ===== Halaman Explore (kelas diawali "ex-") ===== */
    .ex-filter{padding:18px 22px 20px;border:1px solid #eae2d3;border-radius:14px;background:#fff}
    .ex-fh{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
    .ex-fh strong{display:flex;align-items:center;gap:8px;font-size:12.5px}
    .ex-fh .icon{font-size:14px}
    .ex-reset{border:0;background:none;font:600 10px 'Plus Jakarta Sans',sans-serif;color:#cd7f3a;cursor:pointer}
    .ex-lab{margin:16px 0 10px;font-size:8.5px;font-weight:700;letter-spacing:.07em;color:#cd7f3a}
    .ex-cols{display:grid;gap:18px}
    .ex-cols.a{grid-template-columns:1fr 1.1fr 1fr}
    .ex-cols.b{grid-template-columns:.9fr 1.7fr 1.2fr}
    .ex-gl{display:block;margin-bottom:8px;font-size:9.5px;color:#8a7f74}
    .ex-chips{display:flex;flex-wrap:wrap;gap:7px}
    .ex-chip{position:relative}
    .ex-chip input{position:absolute;opacity:0;pointer-events:none}
    .ex-chip span{display:inline-flex;align-items:center;gap:7px;padding:6px 16px;border:1px solid #e4dccb;border-radius:99px;background:#fff;font-size:10px;color:#3b2620;cursor:pointer;transition:.15s}
    .ex-chip span:hover{border-color:#cfc3ad}
    .ex-chip input:checked + span{background:#3b2620;border-color:#3b2620;color:#fff}
    .ex-chip input:focus-visible + span{outline:2px solid #cd7f3a;outline-offset:2px}
    /* fasilitas: lingkaran centang di kiri */
    .ex-chip.fac span{padding:6px 13px 6px 8px}
    .ex-chip.fac i{position:relative;width:13px;height:13px;border:1.5px solid #cfc3ad;border-radius:50%;background:#fff;flex:none}
    .ex-chip.fac input:checked + span{background:#fff;color:#3b2620;border-color:#3b2620;box-shadow:0 0 0 1px #3b2620}
    .ex-chip.fac input:checked + span i{background:#3b2620;border-color:#3b2620}
    .ex-chip.fac input:checked + span i::after{content:"";position:absolute;left:3.5px;top:1px;width:3px;height:6px;border:solid #fff;border-width:0 1.5px 1.5px 0;transform:rotate(45deg)}
    .ex-chip.rate span{padding:6px 14px}
    /* saklar self-brewing */
    .ex-switch{display:inline-flex;align-items:center;gap:10px;font-size:10px;cursor:pointer}
    .ex-switch input{position:absolute;opacity:0}
    .ex-switch i{position:relative;width:34px;height:20px;border-radius:99px;background:#ddd3c2;transition:.15s}
    .ex-switch i::after{content:"";position:absolute;top:3px;left:3px;width:14px;height:14px;border-radius:50%;background:#fff;transition:.15s;box-shadow:0 1px 2px rgba(0,0,0,.2)}
    .ex-switch input:checked + i{background:#e0833a}
    .ex-switch input:checked + i::after{left:17px}
    .ex-switch input:focus-visible + i{outline:2px solid #3b2620;outline-offset:2px}

    /* judul bagian */
    .ex-sec{margin-top:32px}
    .ex-sh{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:16px}
    .ex-sh h2{font-family:'Lora',Georgia,serif;font-size:18px;font-weight:600}
    .ex-sh p{margin-top:4px;font-size:10px;color:#8a7f74}
    .ex-more{display:flex;align-items:center;gap:3px;font-size:10px;font-weight:600;color:#cd7f3a}
    .ex-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;align-items:stretch}
    .ex-empty{padding:30px 0;text-align:center;font-size:12px;color:#8a7f74}

    /* kartu cafe */
    .ex-card{position:relative;display:flex;flex-direction:column;overflow:hidden;border:1px solid #eae2d3;border-radius:14px;background:#fff;transition:box-shadow .15s,transform .15s}
    .ex-card[hidden]{display:none}
    .ex-card:hover{box-shadow:0 10px 26px rgba(59,38,32,.1);transform:translateY(-2px)}
    .ex-hit{position:absolute;inset:0;z-index:1}
    .ex-thumb{position:relative;display:grid;place-items:center;aspect-ratio:16/10;color:rgba(255,255,255,.8);font-size:40px}
    .ex-thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
    .ex-open{position:absolute;left:10px;bottom:10px;display:flex;align-items:center;gap:5px;padding:4px 10px;border-radius:99px;background:#fff;font-size:8.5px;font-weight:600;color:#3b2620}
    .ex-open::before{content:"";width:5px;height:5px;border-radius:50%;background:#14b87a}
    .ex-bm{position:absolute;right:10px;top:10px;z-index:2;display:grid;place-items:center;width:26px;height:26px;padding:0;border:0;border-radius:7px;background:#fff;color:#3b2620;font-size:13px;cursor:pointer;transition:.15s}
    .ex-bm.on{background:#3b2620;color:#fff;border-radius:50%}
    .ex-bm.on svg{fill:currentColor}
    .ex-info{display:flex;flex-direction:column;flex:1;padding:14px 15px 12px}
    .ex-r1{display:flex;justify-content:space-between;align-items:flex-start;gap:8px}
    .ex-r1 h3{font-size:12.5px;font-weight:700;line-height:1.35}
    .ex-rate{display:flex;align-items:center;gap:3px;flex:none;padding:3px 7px;border-radius:5px;background:#f7e7cf;color:#b0602a;font-size:8.5px;font-weight:700;white-space:nowrap}
    .ex-rate .icon{color:#e8a317}
    .ex-rate small{font-size:8px;font-weight:500}
    .ex-addr{display:flex;gap:5px;margin:8px 0 10px;font-size:9.5px;line-height:1.45;color:#8a7f74}
    .ex-addr .icon{margin-top:1px;flex:none;color:#a79d90}
    .ex-tags{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:12px}
    .ex-tags span{padding:3px 8px;border-radius:5px;background:#f7ece0;color:#8a5a22;font-size:8.5px;font-weight:600;line-height:1.4}
    .ex-foot{display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:10px;border-top:1px solid #eae2d3;font-size:10px;color:#8a7f74}
    .ex-foot b{color:#3b2620}
    .ex-feat{display:flex;gap:6px}
    .ex-feat i{display:grid;place-items:center;width:22px;height:22px;border-radius:50%;background:#fbeedd;color:#cd7f3a;font-size:11px}

    @media(max-width:900px){.ex-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.ex-cols.a,.ex-cols.b{grid-template-columns:1fr 1fr}}
    @media(max-width:560px){.ex-grid,.ex-cols.a,.ex-cols.b{grid-template-columns:1fr}}
</style>

{{-- ===== Filter ===== --}}
<form class="ex-filter" id="ex-form" onsubmit="return false">
    <div class="ex-fh"><strong><svg class="icon"><use href="#ex-sliders"/></svg>Filter Cafe</strong><button type="reset" class="ex-reset">Reset filter</button></div>

    @foreach ([['PREFERENSI KOPI', $coffee, 'a', true], ['FILTER UMUM', $general, 'b', false]] as [$title, $groups, $cls, $hasSwitch])
        <div class="ex-lab">{{ $title }}</div>
        <div class="ex-cols {{ $cls }}">
            @foreach ($groups as $g)
                <div>
                    <span class="ex-gl">{{ $g['label'] }}</span>
                    <div class="ex-chips">
                        @foreach ($g['options'] as $opt)
                            <label class="ex-chip {{ $g['type'] }}">
                                <input type="{{ $g['input'] }}" name="{{ $g['name'] }}{{ $g['input'] === 'checkbox' ? '[]' : '' }}" value="{{ $opt }}">
                                <span>@if ($g['type'] === 'fac')<i></i>@endif{{ $g['type'] === 'rate' ? '★ '.$opt.'+' : $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
            @if ($hasSwitch)
                <div>
                    <span class="ex-gl">Self-Brewing</span>
                    <label class="ex-switch"><input type="checkbox" name="selfbrew" value="1"><i></i>Self-Brewing Available</label>
                </div>
            @endif
        </div>
    @endforeach
</form>

{{-- ===== Rekomendasi terdekat ===== --}}
<section class="ex-sec" data-section>
    <div class="ex-sh">
        <div><h2>Rekomendasi Cafe Terdekat</h2><p><span data-count>{{ count($nearby) }}</span> cafe dalam radius 5 km dari lokasi Batam Center.</p></div>
        <a href="#" class="ex-more">Lihat semua <svg class="icon"><use href="#i-right"/></svg></a>
    </div>
    <div class="ex-grid">@foreach ($nearby as $cafe)<x-cafe-card :cafe="$cafe" />@endforeach</div>
    <p class="ex-empty" hidden>Tidak ada cafe yang cocok dengan filter ini.</p>
</section>

{{-- ===== Paling populer ===== --}}
<section class="ex-sec" data-section>
    <div class="ex-sh">
        <div><h2>Paling Populer di Batam</h2><p>Skor berdasarkan rating dan ulasan terbanyak.</p></div>
        <a href="#" class="ex-more">Lihat semua <svg class="icon"><use href="#i-right"/></svg></a>
    </div>
    <div class="ex-grid">@foreach ($popular as $cafe)<x-cafe-card :cafe="$cafe" />@endforeach</div>
    <p class="ex-empty" hidden>Tidak ada cafe yang cocok dengan filter ini.</p>
</section>

<script id="explore-js">
    (function () {
        const form  = document.getElementById('ex-form');
        const cards = [...document.querySelectorAll('.ex-card')];
        const q     = (new URLSearchParams(location.search).get('q') || '').trim().toLowerCase();
        const picked = name => [...form.querySelectorAll(`input[name="${name}"]:checked`)].map(i => i.value);
        const list   = v => (v || '').split('|');

        // --- Saring kartu sesuai filter (antar kelompok: harus cocok semua; dalam satu kelompok: salah satu) ---
        function apply() {
            const beans = picked('bean[]'), roast = picked('roast[]'), price = picked('price[]'), fac = picked('facility[]');
            const rating = Number(picked('rating')[0] || 0), self = form.selfbrew.checked;
            cards.forEach(c => {
                const d = c.dataset;
                c.hidden = !(
                    (!beans.length || beans.some(b => list(d.beans).includes(b))) &&
                    (!roast.length || roast.includes(d.roast)) &&
                    (!price.length || price.includes(d.price)) &&
                    (!fac.length   || fac.every(f => list(d.fac).includes(f))) &&
                    (!rating || Number(d.rating) >= rating) &&
                    (!self || d.self === '1') &&
                    (!q || d.text.includes(q))
                );
            });
            document.querySelectorAll('[data-section]').forEach(sec => {
                const n = [...sec.querySelectorAll('.ex-card')].filter(c => !c.hidden).length;
                sec.querySelector('.ex-empty').hidden = n > 0;
                const out = sec.querySelector('[data-count]'); if (out) out.textContent = n;
            });
        }
        form.addEventListener('change', apply);
        form.addEventListener('reset', () => setTimeout(apply));

        // Rating: klik lagi pilihan yang sama untuk membatalkannya
        form.querySelectorAll('input[name="rating"]').forEach(r => r.addEventListener('click', () => {
            if (r.dataset.was === '1') { r.checked = false; r.dataset.was = '0'; apply(); }
            else { form.querySelectorAll('input[name="rating"]').forEach(x => x.dataset.was = '0'); r.dataset.was = '1'; }
        }));

        // Tombol simpan (bookmark)
        document.querySelectorAll('.ex-bm').forEach(b => b.addEventListener('click', () => b.classList.toggle('on')));

        apply();   // juga menerapkan pencarian dari kolom cari di atas (?q=...)
    })();
</script>
@endsection