@extends('layouts.app')

@section('title', 'Jelajahi Cafe')

@section('content')
@php
    // ----- Kelompok filter. Mau tambah pilihan? Tambah isi 'options'. -----
    $coffee = [
        ['label' => 'Jenis Beans',    'name' => 'bean',  'type' => 'checkbox', 'options' => ['Single Origin', 'Blend']],
        ['label' => 'Roasting Level', 'name' => 'roast', 'type' => 'checkbox', 'options' => ['Light', 'Medium', 'Dark']],
    ];
    $info = [
        ['label' => 'Rentang Harga', 'name' => 'price',    'type' => 'checkbox', 'options' => ['$', '$$', '$$$', '$$$$']],
        ['label' => 'Fasilitas',     'name' => 'facility', 'type' => 'checkbox', 'options' => ['WiFi', 'Outdoor', 'Stopkontak', 'Indoor AC']],
        ['label' => 'Rating',        'name' => 'rating',   'type' => 'radio',    'options' => ['★ 3.0+', '★ 4.0+', '★ 4.5+']],
    ];

    // ----- Data contoh cafe. Nanti diganti data dari database. -----
    $c = fn ($name, $slug, $rating, $reviews, $address, $tags, $price, $dist, $close, $bg, $feats = ['bean', 'cup', 'wifi'])
        => compact('name', 'slug', 'rating', 'reviews', 'address', 'tags', 'price', 'dist', 'close', 'bg', 'feats');

    $nearby = [
        $c('Grind Now Coffee Bar', 'grind-now', '4.9', '1,200+', 'Perumahan Marina Blok 52, Batam Center', ['Single Origin', 'Medium Roast'], '$$$', '0.8', '23:00', '#6b4a35'),
        $c('Houda Coffee',         'houda',     '4.8', '870+',   'Ruko Pelangi No. 3, Batam Center',        ['Blend', 'Self-brewing'],       '$$',  '1.2', '22:00', '#7d5a42'),
        $c('Kulema Rumah Pamer & Kopi', 'kulema', '4.8', '620+', 'Taman Raya, Batam Center',                ['Single Origin', 'Light Roast'], '$$$', '2.4', '23:00', '#8a6a55'),
    ];
    $popular = [
        $c('Meilleur Cafe & Patisserie', 'meilleur',  '4.7', '960+',  'Batam Center, Batam',  ['Pastry & Patisserie', 'Light Roast'],      '$$$', '1.3', '22:00', '#9a7b62', ['bean', 'cup', 'wifi']),
        $c('Sharlotte Cafe',             'sharlotte', '4.7', '740+',  'Panbil, Batam',        ['Specialty Coffee', 'Medium Roast'],        '$$',  '1.7', '23:00', '#a3845f'),
        $c('Lintas Coffee & Space',      'lintas',    '4.7', '810+',  'Batam Center, Batam',  ['Nyaman untuk kerja', 'Light Roast'],       '$$',  '2.0', '24:00', '#6b4a35'),
        $c('Cherrietree Coffee & Eatery','cherrietree','4.6','530+',  'Batam Center, Batam',  ['Single Origin', 'Medium Roast'],           '$$$', '2.6', '22:00', '#7d5a42'),
        $c('Mula Patisserie',            'mula',      '4.6', '480+',  'Nagoya, Batam',        ['Pastry', 'Light Roast'],                   '$$',  '3.1', '21:00', '#8a6a55'),
        $c('La Vie Patisserie & Cafe',   'la-vie',    '4.6', '455+',  'Batam Center, Batam',  ['Dessert', 'Blend'],                        '$$$', '3.4', '22:00', '#9a7b62'),
    ];
@endphp

    {{-- ===== Filter ===== --}}
    <form class="filter" id="filter" method="get" action="{{ route('explore') }}">
        <div class="f-head"><strong>Filter Cafe</strong><button type="reset" class="link">Reset filter</button></div>

        <div class="f-block">
            <div class="f-title">PREFERENSI KOPI</div>
            <div class="f-cols">
                @foreach ($coffee as $g)
                    <div class="f-group">
                        <span>{{ $g['label'] }}</span>
                        <div class="chips">
                            @foreach ($g['options'] as $opt)
                                <label class="chip"><input type="{{ $g['type'] }}" name="{{ $g['name'] }}{{ $g['type'] === 'checkbox' ? '[]' : '' }}" value="{{ $opt }}"><span>{{ $opt }}</span></label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div class="f-group">
                    <span>Self-Brewing</span>
                    <label class="switch"><input type="checkbox" name="self_brewing" value="1"><i></i>Self-brewing tersedia</label>
                </div>
            </div>
        </div>

        <div class="f-block">
            <div class="f-title">INFO CAFE</div>
            <div class="f-cols">
                @foreach ($info as $g)
                    <div class="f-group">
                        <span>{{ $g['label'] }}</span>
                        <div class="chips">
                            @foreach ($g['options'] as $opt)
                                <label class="chip"><input type="{{ $g['type'] }}" name="{{ $g['name'] }}{{ $g['type'] === 'checkbox' ? '[]' : '' }}" value="{{ $opt }}"><span>{{ $opt }}</span></label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </form>

    {{-- ===== Rekomendasi terdekat ===== --}}
    <section class="sec">
        <div class="sec-head">
            <div><h2>Rekomendasi Cafe Terdekat</h2><p class="sub">{{ count($nearby) }} cafe dalam radius 5 km dari lokasimu.</p></div>
            <a href="#" class="more">Lihat semua <svg class="icon"><use href="#i-right"/></svg></a>
        </div>
        <div class="grid">
            @foreach ($nearby as $cafe)<x-cafe-card :cafe="$cafe" />@endforeach
        </div>
    </section>

    {{-- ===== Paling populer ===== --}}
    <section class="sec">
        <div class="sec-head">
            <div><h2>Paling Populer di Batam</h2><p class="sub">Dipilih berdasarkan rating dan jumlah ulasan terbanyak.</p></div>
            <a href="#" class="more">Lihat semua <svg class="icon"><use href="#i-right"/></svg></a>
        </div>
        <div class="grid">
            @foreach ($popular as $cafe)<x-cafe-card :cafe="$cafe" />@endforeach
        </div>
    </section>

@endsection

@push('scripts')
<script>
    // Tombol hati: tandai / batalkan cafe tersimpan
    document.querySelectorAll('.fav').forEach(b => b.addEventListener('click', () => b.classList.toggle('on')));
</script>
@endpush