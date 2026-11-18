@props(['cafe'])

{{-- Satu kartu cafe. Dipakai dengan: <x-cafe-card :cafe="$cafe" />
     Gayanya (kelas "ex-") ada di bagian <style> pada explore.blade.php --}}
<article class="ex-card"
         data-beans="{{ implode('|', $cafe['beans']) }}" data-roast="{{ $cafe['roast'] }}" data-self="{{ $cafe['self'] ? 1 : 0 }}"
         data-price="{{ $cafe['price'] }}" data-rating="{{ $cafe['rating'] }}" data-fac="{{ implode('|', $cafe['fac']) }}"
         data-text="{{ mb_strtolower($cafe['name'].' '.$cafe['address'].' '.implode(' ', $cafe['tags'])) }}">

    {{-- Seluruh kartu bisa diklik menuju halaman katalog cafe --}}
    <a class="ex-hit" href="{{ route('dashboard') }}" aria-label="Lihat {{ $cafe['name'] }}"></a>

    <div class="ex-thumb" style="background:{{ $cafe['bg'] }}">
        {{-- Foto asli otomatis dipakai kalau ada di public/images/cafes/{slug}.jpg --}}
        @if (file_exists(public_path('images/cafes/'.$cafe['slug'].'.jpg')))
            <img src="{{ asset('images/cafes/'.$cafe['slug'].'.jpg') }}" alt="{{ $cafe['name'] }}">
        @else
            <svg class="icon"><use href="#i-cup"/></svg>
        @endif
        <span class="ex-open">Buka · s/d {{ $cafe['close'] }}</span>
        <button class="ex-bm" type="button" aria-label="Simpan cafe"><svg class="icon"><use href="#ex-bookmark"/></svg></button>
    </div>

    <div class="ex-info">
        <div class="ex-r1">
            <h3>{{ $cafe['name'] }}</h3>
            <span class="ex-rate"><svg class="icon"><use href="#i-star"/></svg>{{ $cafe['rating'] }} <small>({{ $cafe['reviews'] }} Review)</small></span>
        </div>
        <p class="ex-addr"><svg class="icon"><use href="#i-pin"/></svg>{{ $cafe['address'] }}</p>
        <div class="ex-tags">@foreach ($cafe['tags'] as $t)<span>{{ $t }}</span>@endforeach</div>
        <div class="ex-foot">
            <span class="ex-feat">@foreach ($cafe['feats'] as $f)<i><svg class="icon"><use href="#{{ $f }}"/></svg></i>@endforeach</span>
            <span><b>{{ $cafe['price'] }}</b> · {{ $cafe['dist'] }} km</span>
        </div>
    </div>
</article>