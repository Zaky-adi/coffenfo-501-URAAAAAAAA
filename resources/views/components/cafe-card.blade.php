@props(['cafe'])

{{-- Satu kartu cafe. Dipakai dengan: <x-cafe-card :cafe="$cafe" /> --}}
<article class="cafe">
    <div class="thumb" style="background:{{ $cafe['bg'] }}">
        {{-- Foto asli otomatis dipakai kalau ada di public/images/cafes/{slug}.jpg --}}
        @if (file_exists(public_path('images/cafes/'.$cafe['slug'].'.jpg')))
            <img src="{{ asset('images/cafes/'.$cafe['slug'].'.jpg') }}" alt="{{ $cafe['name'] }}">
        @else
            <svg class="icon"><use href="#i-cup"/></svg>
        @endif
        <span class="open">Buka · s/d {{ $cafe['close'] }}</span>
        <button class="fav" type="button" aria-label="Simpan cafe"><svg class="icon"><use href="#i-heart"/></svg></button>
    </div>

    <div class="info">
        <div class="r1">
            <h3>{{ $cafe['name'] }}</h3>
            <span class="rate"><svg class="icon"><use href="#i-star"/></svg>{{ $cafe['rating'] }} <small>({{ $cafe['reviews'] }} Review)</small></span>
        </div>
        <p class="addr"><svg class="icon"><use href="#i-pin"/></svg>{{ $cafe['address'] }}</p>
        <div class="tags">@foreach ($cafe['tags'] as $t)<span>{{ $t }}</span>@endforeach</div>
        <div class="foot">
            <span class="feat">@foreach ($cafe['feats'] as $f)<svg class="icon"><use href="#i-{{ $f }}"/></svg>@endforeach</span>
            <span><b>{{ $cafe['price'] }}</b> · {{ $cafe['dist'] }} km</span>
        </div>
    </div>
</article>