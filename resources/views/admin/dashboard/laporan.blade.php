@extends('layouts.admin')

@section('title', 'Laporan Pengguna')

@section('content')
<div class="admin-shell">

    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- MAIN CONTENT --}}
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
                    <h3 class="fw-bold mb-1">Laporan Pengguna</h3>
                    <p class="text-muted small mb-0">
                        Tinjau laporan dari pengguna terhadap review dan konten di Coffenfo
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

            {{-- STATISTICS --}}
            <div class="row g-3 mb-4">

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="report-stat-card">
                        <div class="report-stat-icon orange">
                            <i class="bi bi-flag"></i>
                        </div>
                        <div>
                            <small>Total Laporan</small>
                            <h2>24</h2>
                            <span class="report-stat-note neutral">
                                +6 minggu ini
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="report-stat-card">
                        <div class="report-stat-icon red">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <small>Perlu Tindakan</small>
                            <h2 class="text-danger">5</h2>
                            <span class="report-stat-note danger">
                                Segera tinjau
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="report-stat-card">
                        <div class="report-stat-icon yellow">
                            <i class="bi bi-eye"></i>
                        </div>
                        <div>
                            <small>Sedang Ditinjau</small>
                            <h2>7</h2>
                            <span class="report-stat-note warning">
                                Oleh tim moderator
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="report-stat-card">
                        <div class="report-stat-icon green">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <small>Selesai</small>
                            <h2>12</h2>
                            <span class="report-stat-note success">
                                Bulan ini
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- TOOLBAR --}}
            <div class="report-toolbar mb-4">

                {{-- SEARCH --}}
                <div class="report-search">
                    <i class="bi bi-search"></i>
                    <input type="text"
                           class="form-control"
                           id="reportSearch"
                           placeholder="Cari ID, pelapor, atau cafe...">
                </div>

                {{-- STATUS FILTER --}}
                <div class="report-status-filters">
                    <button class="report-filter active"
                            data-status="all"
                            type="button">
                        Semua <span>24</span>
                    </button>

                    <button class="report-filter"
                            data-status="new"
                            type="button">
                        Baru <span>5</span>
                    </button>

                    <button class="report-filter"
                            data-status="review"
                            type="button">
                        Ditinjau <span>7</span>
                    </button>

                    <button class="report-filter"
                            data-status="done"
                            type="button">
                        Selesai <span>12</span>
                    </button>
                </div>

                {{-- TYPE FILTER --}}
                <select class="form-select report-select"
                        id="reportTypeFilter">
                    <option value="all">⚑ Semua jenis</option>
                    <option value="Spam / promosi">Spam / promosi</option>
                    <option value="Bahasa kasar / menyerang">
                        Bahasa kasar / menyerang
                    </option>
                    <option value="Informasi tidak akurat">
                        Informasi tidak akurat
                    </option>
                    <option value="Konten tidak relevan">
                        Konten tidak relevan
                    </option>
                    <option value="Foto tidak sesuai">Foto tidak sesuai</option>
                </select>

            </div>

            {{-- TABLE + DETAIL --}}
            <div class="row g-3">

                {{-- REPORT TABLE --}}
                <div class="col-12 col-xl-8">
                    <div class="report-table-card">

                        <div class="table-responsive">
                            <table class="table report-table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>JENIS LAPORAN</th>
                                        <th>REVIEW DILAPORKAN</th>
                                        <th>TANGGAL</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>

                                <tbody id="reportTableBody">

                                    @php
                                    $reports = [
                                        [
                                            'id' => 'LP-0412',
                                            'type' => 'Spam / promosi',
                                            'user' => 'Anonim_8821',
                                            'cafe' => 'Pippo Coffee Roasters',
                                            'date' => '25 Okt',
                                            'status' => 'new',
                                            'reporter' => 'Sari Wulandari',
                                            'time' => '25 Okt 2026, 08:40',
                                            'review' => 'Tempat jelek! Kopi basi. Hubungi WA 0812-xxxx untuk promo dan diskon besar.',
                                            'note' => 'Review ini berisi nomor WhatsApp dan promosi, bukan ulasan tentang cafe.',
                                        ],
                                        [
                                            'id' => 'LP-0411',
                                            'type' => 'Bahasa kasar / menyerang',
                                            'user' => 'Maya Lestari',
                                            'cafe' => "Kopi Atok's",
                                            'date' => '24 Okt',
                                            'status' => 'new',
                                            'reporter' => 'Dinda Pratiwi',
                                            'time' => '24 Okt 2026, 14:20',
                                            'review' => 'Overpriced dan kopinya biasa saja. Pemiliknya sombong dan menyebalkan.',
                                            'note' => 'Terdapat kalimat yang diduga menyerang pemilik cafe.',
                                        ],
                                        [
                                            'id' => 'LP-0409',
                                            'type' => 'Informasi tidak akurat',
                                            'user' => 'Fajar Nugroho',
                                            'cafe' => 'Lintas Coffe & Space',
                                            'date' => '24 Okt',
                                            'status' => 'review',
                                            'reporter' => 'Rizky Hakim',
                                            'time' => '24 Okt 2026, 10:05',
                                            'review' => 'Cafe ini sudah tutup permanen sejak bulan lalu, jangan datang ke sini.',
                                            'note' => 'Pelapor menyatakan informasi status cafe sudah tidak akurat.',
                                        ],
                                        [
                                            'id' => 'LP-0407',
                                            'type' => 'Konten tidak relevan',
                                            'user' => 'Putri Anggraini',
                                            'cafe' => 'Anchor Cafe & Roastery',
                                            'date' => '23 Okt',
                                            'status' => 'new',
                                            'reporter' => 'Budi Santoso',
                                            'time' => '23 Okt 2026, 11:30',
                                            'review' => 'Review ini bukan soal cafe, saya hanya ingin menawarkan jasa SEO untuk bisnis Anda.',
                                            'note' => 'Review tidak membahas pengalaman atau informasi tentang cafe.',
                                        ],
                                        [
                                            'id' => 'LP-0405',
                                            'type' => 'Foto tidak sesuai',
                                            'user' => 'Lina Marlina',
                                            'cafe' => 'Grind Now Coffee Bar',
                                            'date' => '22 Okt',
                                            'status' => 'review',
                                            'reporter' => 'Maya Lestari',
                                            'time' => '22 Okt 2026, 16:10',
                                            'review' => 'Latte art-nya cantik, tapi antreannya panjang sekali di akhir pekan.',
                                            'note' => 'Pelapor menandai foto yang disertakan tidak sesuai dengan cafe.',
                                        ],
                                        [
                                            'id' => 'LP-0401',
                                            'type' => 'Spam / promosi',
                                            'user' => 'Budi Santoso',
                                            'cafe' => 'Mula Patisserie',
                                            'date' => '20 Okt',
                                            'status' => 'done',
                                            'reporter' => 'Hendra Wijaya',
                                            'time' => '20 Okt 2026, 09:20',
                                            'review' => 'Review berisi promosi dan nomor WhatsApp untuk penjualan produk.',
                                            'note' => 'Laporan sudah selesai ditangani oleh moderator.',
                                        ],
                                        [
                                            'id' => 'LP-0398',
                                            'type' => 'Bahasa kasar / menyerang',
                                            'user' => 'Rizky Hakim',
                                            'cafe' => 'Hoods Coffe',
                                            'date' => '19 Okt',
                                            'status' => 'done',
                                            'reporter' => 'Putri Anggraini',
                                            'time' => '19 Okt 2026, 12:45',
                                            'review' => 'Review berisi kata-kata yang dianggap menyerang staf cafe.',
                                            'note' => 'Laporan telah diselesaikan.',
                                        ],
                                    ];
                                    @endphp

                                    @foreach($reports as $report)
                                    <tr class="report-row"
                                        data-id="{{ $report['id'] }}"
                                        data-type="{{ $report['type'] }}"
                                        data-status="{{ $report['status'] }}"
                                        data-user="{{ strtolower($report['user']) }}"
                                        data-cafe="{{ strtolower($report['cafe']) }}"
                                        data-reporter="{{ strtolower($report['reporter']) }}"
                                        data-date="{{ $report['date'] }}"
                                        data-review="{{ $report['review'] }}"
                                        data-note="{{ $report['note'] }}"
                                        data-time="{{ $report['time'] }}">

                                        <td class="report-id">
                                            {{ $report['id'] }}
                                        </td>

                                        <td>
                                            <div class="report-type">
                                                <i class="bi bi-flag"></i>
                                                {{ $report['type'] }}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="report-user-name">
                                                {{ $report['user'] }}
                                            </div>
                                            <small class="report-cafe">
                                                {{ $report['cafe'] }}
                                            </small>
                                        </td>

                                        <td class="report-date">
                                            {{ $report['date'] }}
                                        </td>

                                        <td>
                                            @if($report['status'] === 'new')
                                                <span class="report-status new">
                                                    <span></span> Baru
                                                </span>
                                            @elseif($report['status'] === 'review')
                                                <span class="report-status review">
                                                    <span></span> Ditinjau
                                                </span>
                                            @else
                                                <span class="report-status done">
                                                    <span></span> Selesai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        {{-- FOOTER --}}
                        <div class="report-table-footer">
                            <small id="reportTableInfo">
                                Menampilkan 1-7 dari 24
                            </small>

                            <div class="report-pagination">
                                <button class="report-page-btn" disabled>
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="report-page-btn active">1</button>
                                <button class="report-page-btn">2</button>
                                <button class="report-page-btn">3</button>
                                <button class="report-page-btn">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- DETAIL PANEL --}}
                <div class="col-12 col-xl-4">
                    <div class="report-detail-card" id="reportDetail">

                        <div class="report-detail-header">
                            <h5>Detail laporan</h5>
                            <span class="report-status new" id="detailStatus">
                                <span></span> Baru
                            </span>
                        </div>

                        <div class="report-detail-id" id="detailId">
                            #LP-0412
                        </div>

                        <small class="report-detail-meta" id="detailMeta">
                            Dilaporkan oleh Sari Wulandari · 25 Okt 2026, 08:40
                        </small>

                        {{-- REPORTED REVIEW --}}
                        <div class="reported-review-box">
                            <div class="reported-user">
                                <div class="reported-avatar" id="detailAvatar">AN</div>
                                <div>
                                    <strong id="detailUser">Anonim_8821</strong>
                                    <small id="detailCafe">Pippo Coffee Roasters</small>
                                </div>
                            </div>

                            <p id="detailReview">
                                Tempat jelek! Kopi basi. Hubungi WA 0812-xxxx untuk promo dan diskon besar.
                            </p>
                        </div>

                        {{-- REPORT REASON --}}
                        <div class="report-detail-section">
                            <small>Alasan laporan</small>
                            <div class="report-reason-badge" id="detailType">
                                <span></span> Spam / promosi
                            </div>
                        </div>

                        {{-- REPORTER NOTE --}}
                        <div class="report-detail-section">
                            <small>Catatan pelapor</small>
                            <p id="detailNote">
                                Review ini berisi nomor WhatsApp dan promosi,
                                bukan ulasan tentang cafe.
                            </p>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="report-detail-actions">

                            <button class="btn-report-delete"
                                    id="deleteReviewButton"
                                    type="button">
                                <i class="bi bi-trash"></i>
                                Hapus review
                            </button>

                            <button class="btn-report-hide"
                                    id="hideReviewButton"
                                    type="button">
                                <i class="bi bi-eye"></i>
                                Sembunyikan review
                            </button>

                            <button class="btn-report-dismiss"
                                    id="dismissReportButton"
                                    type="button">
                                <i class="bi bi-check"></i>
                                Tolak laporan
                            </button>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </main>
</div>
@endsection