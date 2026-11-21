@extends('layouts.admin')

@section('title', 'Kelola Cafe')

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
                    <h3 class="fw-bold mb-1">Kelola Cafe</h3>
                    <p class="text-muted small mb-0">
                        Kelola data cafe mitra, verifikasi, dan status tampil di Coffenfo
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button class="btn notification-button"
                        id="notificationButton"
                        type="button"
                        aria-label="Notifikasi">
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

            {{-- STATISTICS --}}
            <div class="row g-3 g-xl-4 mb-4">

                {{-- TOTAL CAFE --}}
                <div class="col-12 col-md-4">
                    <div class="cafe-stat-card">
                        <div class="cafe-stat-icon orange">
                            <i class="bi bi-shop"></i>
                        </div>

                        <div>
                            <small>Total Cafe</small>
                            <h2>48</h2>
                            <span class="stat-note success">
                                +3 cafe bulan ini
                            </span>
                        </div>
                    </div>
                </div>

                {{-- CAFE AKTIF --}}
                <div class="col-12 col-md-4">
                    <div class="cafe-stat-card">
                        <div class="cafe-stat-icon green">
                            <i class="bi bi-check-lg"></i>
                        </div>

                        <div>
                            <small>Cafe Aktif</small>
                            <h2>41</h2>
                            <span class="stat-note success">
                                85% dari total cafe
                            </span>
                        </div>
                    </div>
                </div>

                {{-- MENUNGGU --}}
                <div class="col-12 col-md-4">
                    <div class="cafe-stat-card">
                        <div class="cafe-stat-icon yellow">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>

                        <div>
                            <small>Menunggu Verifikasi</small>
                            <h2>4</h2>
                            <span class="stat-note warning">
                                Perlu ditinjau
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FILTER & ACTIONS --}}
            <div class="cafe-toolbar mb-4">

                {{-- SEARCH --}}
                <div class="cafe-search">
                    <i class="bi bi-search"></i>

                    <input type="text"
                           id="cafeSearch"
                           placeholder="Cari nama cafe atau pemilik..."
                           class="form-control">
                </div>

                {{-- STATUS FILTER --}}
                <div class="cafe-status-filters">
                    <button class="filter-pill active" data-status="all">
                        Semua <span>48</span>
                    </button>

                    <button class="filter-pill" data-status="aktif">
                        Aktif <span>41</span>
                    </button>

                    <button class="filter-pill" data-status="menunggu">
                        Menunggu <span>4</span>
                    </button>

                    <button class="filter-pill" data-status="nonaktif">
                        Nonaktif <span>3</span>
                    </button>
                </div>

                {{-- AREA --}}
                <div class="dropdown">
                    <button class="btn cafe-area-button dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown">
                        <i class="bi bi-shop me-1"></i>
                        <span id="selectedArea">Semua Area</span>
                    </button>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item area-option" href="#" data-area="all">
                                Semua Area
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item area-option" href="#" data-area="Batam Kota">
                                Batam Kota
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item area-option" href="#" data-area="Nagoya">
                                Nagoya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item area-option" href="#" data-area="Sekupang">
                                Sekupang
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item area-option" href="#" data-area="Lubuk Baja">
                                Lubuk Baja
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item area-option" href="#" data-area="Batu Aji">
                                Batu Aji
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- ADD CAFE --}}
                <button class="btn btn-add-cafe" type="button">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Cafe
                </button>

            </div>

            {{-- TABLE --}}
            <div class="cafe-table-card">

                <div class="table-responsive">
                    <table class="table cafe-table align-middle mb-0">

                        <thead>
                            <tr>
                                <th class="checkbox-column"></th>
                                <th>Nama Cafe</th>
                                <th>Pemilik</th>
                                <th>Area</th>
                                <th>Rating</th>
                                <th>Menu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="cafeTableBody">

                            @php
                                $cafes = [
                                    [
                                        'name' => 'Grind Now Coffee Bar',
                                        'owner' => 'Reza Mahendra',
                                        'area' => 'Batam Kota',
                                        'rating' => '4.9 (128)',
                                        'menu' => 24,
                                        'status' => 'aktif',
                                        'color' => '#38251d',
                                    ],
                                    [
                                        'name' => 'Temu Kopi',
                                        'owner' => 'Intan Permata',
                                        'area' => 'Batam Kota',
                                        'rating' => '4.8 (96)',
                                        'menu' => 18,
                                        'status' => 'aktif',
                                        'color' => '#cbb38a',
                                    ],
                                    [
                                        'name' => 'Senja Coffee House',
                                        'owner' => 'Dimas Aditya',
                                        'area' => 'Sekupang',
                                        'rating' => '4.8 (74)',
                                        'menu' => 21,
                                        'status' => 'aktif',
                                        'color' => '#a6b88c',
                                    ],
                                    [
                                        'name' => 'Ruang Seduh',
                                        'owner' => 'Lestari Putri',
                                        'area' => 'Lubuk Baja',
                                        'rating' => '4.7 (58)',
                                        'menu' => 16,
                                        'status' => 'aktif',
                                        'color' => '#bf8658',
                                    ],
                                    [
                                        'name' => 'Anchor Cafe & Roastery',
                                        'owner' => 'Hendra Wijaya',
                                        'area' => 'Nagoya',
                                        'rating' => '4.6 (41)',
                                        'menu' => 19,
                                        'status' => 'menunggu',
                                        'color' => '#60717b',
                                    ],
                                    [
                                        'name' => 'Hoods Coffee',
                                        'owner' => 'Alvin Pratama',
                                        'area' => 'Batu Aji',
                                        'rating' => '4.5 (33)',
                                        'menu' => 14,
                                        'status' => 'aktif',
                                        'color' => '#80604d',
                                    ],
                                    [
                                        'name' => 'Cangkir Lama',
                                        'owner' => 'Sari Wulandari',
                                        'area' => 'Batam Kota',
                                        'rating' => '4.4 (27)',
                                        'menu' => 12,
                                        'status' => 'nonaktif',
                                        'color' => '#9b9185',
                                    ],
                                    [
                                        'name' => 'Pippo Coffe Roasters',
                                        'owner' => 'Michael Tan',
                                        'area' => 'Nagoya',
                                        'rating' => 'Belum ada',
                                        'menu' => 9,
                                        'status' => 'menunggu',
                                        'color' => '#92566d',
                                    ],
                                ];
                            @endphp

                            @foreach ($cafes as $cafe)
                                <tr data-status="{{ $cafe['status'] }}"
                                    data-area="{{ $cafe['area'] }}"
                                    data-search="{{ strtolower($cafe['name'].' '.$cafe['owner']) }}">

                                    {{-- CHECKBOX --}}
                                    <td class="text-center">
                                        <input type="checkbox"
                                               class="cafe-checkbox">
                                    </td>

                                    {{-- CAFE NAME --}}
                                    <td>
                                        <div class="cafe-name-cell">
                                            <div class="cafe-logo"
                                                 style="background: {{ $cafe['color'] }}">
                                                <i class="bi bi-shop"></i>
                                            </div>

                                            <div>
                                                <strong>{{ $cafe['name'] }}</strong>
                                                <small>{{ $cafe['area'] }}, Batam</small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- OWNER --}}
                                    <td>{{ $cafe['owner'] }}</td>

                                    {{-- AREA --}}
                                    <td>{{ $cafe['area'] }}</td>

                                    {{-- RATING --}}
                                    <td class="cafe-rating">
                                        <i class="bi bi-star"></i>
                                        {{ $cafe['rating'] }}
                                    </td>

                                    {{-- MENU --}}
                                    <td>{{ $cafe['menu'] }}</td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if ($cafe['status'] === 'aktif')
                                            <span class="cafe-status status-active">
                                                <span></span> Aktif
                                            </span>
                                        @elseif ($cafe['status'] === 'menunggu')
                                            <span class="cafe-status status-pending">
                                                <span></span> Menunggu verifikasi
                                            </span>
                                        @else
                                            <span class="cafe-status status-inactive">
                                                <span></span> Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td>
                                        <div class="cafe-actions">
                                            <button class="cafe-action edit"
                                                    title="Edit Cafe">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button class="cafe-action view"
                                                    title="Lihat Cafe">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <button class="cafe-action delete"
                                                    title="Hapus Cafe">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                {{-- TABLE FOOTER --}}
                <div class="cafe-table-footer">
                    <small id="cafeTableInfo">
                        Menampilkan 1-8 dari 48 entri
                    </small>

                    <div class="pagination-buttons">
                        <button class="page-btn" disabled>
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>

                        <button class="page-btn">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </main>
</div>
@endsection
