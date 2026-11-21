@extends('layouts.admin')

@section('title', 'Kelola Beans & Roasting')

@section('content')
<div class="admin-shell">

    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- MAIN --}}
    <main class="admin-main">

        {{-- HEADER --}}
        <header class="admin-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn sidebar-toggle d-lg-none"
                        id="sidebarToggle"
                        aria-label="Buka navigasi">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <div>
                    <h3 class="fw-bold mb-1">Kelola Beans & Roasting</h3>
                    <p class="text-muted small mb-0">
                        Atur data beans, tingkat roasting, dan metode seduh untuk filter spesialisasi kopi
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button class="btn notification-button"
                        id="notificationButton"
                        type="button">
                    <i class="bi bi-bell"></i>
                    <span class="notification-dot"></span>
                </button>

                <div class="admin-profile">
                    <div class="admin-avatar">AD</div>
                    <div class="d-none d-sm-block">
                        <div class="small fw-semibold">Admin Coffenfo</div>
                        <small class="text-muted">Admin</small>
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <div class="dashboard-content container-fluid">

            {{-- TABS --}}
            <ul class="nav beans-tabs mb-4" id="beansTab" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                            id="tab-beans"
                            data-bs-toggle="tab"
                            data-bs-target="#beans-content"
                            type="button"
                            role="tab">
                        Beans <span>24</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="tab-roasting"
                            data-bs-toggle="tab"
                            data-bs-target="#roasting-content"
                            type="button"
                            role="tab">
                        Tingkat Roasting <span>4</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="tab-process"
                            data-bs-toggle="tab"
                            data-bs-target="#process-content"
                            type="button"
                            role="tab">
                        Metode Proses <span>7</span>
                    </button>
                </li>

            </ul>

            <div class="tab-content">

                {{-- ================= BEANS TAB ================= --}}
                <div class="tab-pane fade show active"
                     id="beans-content"
                     role="tabpanel">

                    {{-- TOOLBAR --}}
                    <div class="beans-toolbar mb-4">

                        {{-- SEARCH --}}
                        <div class="beans-search">
                            <i class="bi bi-search"></i>
                            <input type="text"
                                   id="beansSearch"
                                   class="form-control"
                                   placeholder="Cari nama beans atau asal...">
                        </div>

                        {{-- ORIGIN --}}
                        <select class="form-select beans-filter"
                                id="originFilter">
                            <option value="all">Semua asal</option>
                            <option value="Aceh">Aceh</option>
                            <option value="Bajawa">Bajawa</option>
                            <option value="Toraja">Toraja</option>
                            <option value="Kintamani">Kintamani</option>
                            <option value="Garut">Garut</option>
                            <option value="Lampung">Lampung</option>
                        </select>

                        {{-- PROCESS --}}
                        <select class="form-select beans-filter"
                                id="processFilter">
                            <option value="all">Semua proses</option>
                            <option value="Natural">Natural</option>
                            <option value="Washed">Washed</option>
                            <option value="Honey">Honey</option>
                        </select>

                        {{-- ADD --}}
                        <button class="btn btn-add-beans"
                                type="button">
                            <i class="bi bi-plus-lg me-1"></i>
                            Tambah Beans
                        </button>

                    </div>

                    {{-- BEANS GRID --}}
                    <div class="row g-3 g-xl-4" id="beansGrid">

                        @php
                            $beans = [
                                [
                                    'name' => 'Gayo Wine Process',
                                    'origin' => 'Gayo, Aceh',
                                    'variety' => 'Arabica',
                                    'process' => 'Natural',
                                    'used' => 12,
                                    'roasting' => 'Light',
                                    'tags' => ['Fruity', 'Wine', 'Floral'],
                                    'level' => 1,
                                ],
                                [
                                    'name' => 'Flores Bajawa',
                                    'origin' => 'Bajawa, NTT',
                                    'variety' => 'Arabica',
                                    'process' => 'Washed',
                                    'used' => 9,
                                    'roasting' => 'Medium',
                                    'tags' => ['Chocolate', 'Nutty', 'Sweet'],
                                    'level' => 2,
                                ],
                                [
                                    'name' => 'Toraja Sapan',
                                    'origin' => 'Toraja, Sulsel',
                                    'variety' => 'Arabica',
                                    'process' => 'Honey',
                                    'used' => 8,
                                    'roasting' => 'Medium',
                                    'tags' => ['Herbal', 'Caramel', 'Spicy'],
                                    'level' => 2,
                                ],
                                [
                                    'name' => 'Kintamani Bali',
                                    'origin' => 'Kintamani, Bali',
                                    'variety' => 'Arabica',
                                    'process' => 'Washed',
                                    'used' => 7,
                                    'roasting' => 'Light',
                                    'tags' => ['Citrus', 'Tea-like', 'Bright'],
                                    'level' => 1,
                                ],
                                [
                                    'name' => 'Java Preanger',
                                    'origin' => 'Garut, Jawa Barat',
                                    'variety' => 'Arabica',
                                    'process' => 'Washed',
                                    'used' => 6,
                                    'roasting' => 'Dark',
                                    'tags' => ['Smoky', 'Cocoa', 'Bold'],
                                    'level' => 4,
                                ],
                                [
                                    'name' => 'Lampung Robusta',
                                    'origin' => 'Lampung',
                                    'variety' => 'Robusta',
                                    'process' => 'Natural',
                                    'used' => 5,
                                    'roasting' => 'Dark',
                                    'tags' => ['Earthy', 'Bitter', 'Bold'],
                                    'level' => 4,
                                ],
                            ];
                        @endphp

                        @foreach ($beans as $bean)
                            <div class="col-12 col-md-6 col-xl-4 bean-item"
                                 data-name="{{ strtolower($bean['name']) }}"
                                 data-origin="{{ $bean['origin'] }}"
                                 data-process="{{ $bean['process'] }}">

                                <div class="bean-card h-100">

                                    {{-- CARD TOP --}}
                                    <div class="bean-card-top">
                                        <div class="bean-icon">
                                            <i class="bi bi-egg"></i>
                                        </div>

                                        <span class="bean-status">
                                            <span></span> Aktif
                                        </span>
                                    </div>

                                    {{-- NAME --}}
                                    <h5 class="bean-name">
                                        {{ $bean['name'] }}
                                    </h5>

                                    <p class="bean-origin">
                                        {{ $bean['origin'] }} · {{ $bean['variety'] }}
                                    </p>

                                    {{-- DETAILS --}}
                                    <div class="bean-details">
                                        <div>
                                            <small>Proses</small>
                                            <strong>{{ $bean['process'] }}</strong>
                                        </div>

                                        <div>
                                            <small>Dipakai</small>
                                            <strong>{{ $bean['used'] }} cafe</strong>
                                        </div>
                                    </div>

                                    {{-- ROAST LEVEL --}}
                                    <div class="roast-section">
                                        <div class="roast-heading">
                                            <small>Tingkat roasting</small>
                                            <span>{{ $bean['roasting'] }}</span>
                                        </div>

                                        <div class="roast-meter">
                                            @for ($i = 1; $i <= 4; $i++)
                                                <span class="{{ $i <= $bean['level'] ? 'filled' : '' }}"></span>
                                            @endfor
                                        </div>
                                    </div>

                                    {{-- TAGS --}}
                                    <div class="bean-tags">
                                        @foreach ($bean['tags'] as $tag)
                                            <span>{{ $tag }}</span>
                                        @endforeach
                                    </div>

                                    {{-- ACTION --}}
                                    <div class="bean-card-actions">
                                        <button class="btn bean-edit-btn"
                                                type="button">
                                            <i class="bi bi-pencil me-1"></i>
                                            Edit
                                        </button>

                                        <button class="btn bean-delete-btn"
                                                type="button"
                                                title="Hapus Beans">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                {{-- ================= ROASTING TAB ================= --}}
                <div class="tab-pane fade"
                     id="roasting-content"
                     role="tabpanel">

                    <div class="beans-placeholder-grid">

                        @php
                            $roasts = [
                                ['name' => 'Light', 'description' => 'Karakter acidity lebih terasa, aroma floral dan fruity.', 'color' => '#c9843f'],
                                ['name' => 'Medium', 'description' => 'Balance antara acidity, sweetness, dan body.', 'color' => '#a96b36'],
                                ['name' => 'Medium Dark', 'description' => 'Body lebih kuat, karakter caramel dan chocolate.', 'color' => '#785033'],
                                ['name' => 'Dark', 'description' => 'Karakter smoky, bold, dan bitterness lebih dominan.', 'color' => '#38251d'],
                            ];
                        @endphp

                        <div class="row g-3">
                            @foreach ($roasts as $roast)
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="bean-card roast-type-card">
                                        <div class="roast-color"
                                             style="background: {{ $roast['color'] }}">
                                        </div>

                                        <h5 class="bean-name mt-3">
                                            {{ $roast['name'] }}
                                        </h5>

                                        <p class="bean-origin">
                                            {{ $roast['description'] }}
                                        </p>

                                        <button class="btn bean-edit-btn w-100"
                                                type="button">
                                            <i class="bi bi-pencil me-1"></i>
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                {{-- ================= PROCESS TAB ================= --}}
                <div class="tab-pane fade"
                     id="process-content"
                     role="tabpanel">

                    @php
                        $processes = [
                            'Natural',
                            'Washed',
                            'Honey',
                            'Anaerobic',
                            'Wet Hulled',
                            'Semi Washed',
                            'Carbonic Maceration',
                        ];
                    @endphp

                    <div class="row g-3">
                        @foreach ($processes as $process)
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="bean-card process-card">
                                    <div class="bean-card-top">
                                        <div class="bean-icon">
                                            <i class="bi bi-droplet"></i>
                                        </div>

                                        <span class="bean-status">
                                            <span></span> Aktif
                                        </span>
                                    </div>

                                    <h5 class="bean-name mt-3">
                                        {{ $process }}
                                    </h5>

                                    <p class="bean-origin">
                                        Metode pengolahan kopi
                                    </p>

                                    <button class="btn bean-edit-btn w-100"
                                            type="button">
                                        <i class="bi bi-pencil me-1"></i>
                                        Edit
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

            </div>

        </div>
    </main>
</div>
@endsection
