@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="admin-shell">
    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- MAIN --}}
    <main class="admin-main">
        <header class="admin-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn sidebar-toggle d-lg-none" id="sidebarToggle" aria-label="Buka navigasi">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div>
                    <h3 class="fw-bold mb-1">Dashboard Overview</h3>
                    <p class="text-muted small mb-0">Ringkasan aktivitas platform Coffenfo hari ini</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn notification-button" id="notificationButton" type="button" aria-label="Notifikasi">
                    <i class="bi bi-bell"></i><span class="notification-dot"></span>
                </button>
                <div class="admin-profile">
                    <div class="admin-avatar">AD</div>
                    <div class="d-none d-sm-block">
                        <div class="small fw-semibold">Admin Coffenfo</div>
                        <small class="text-muted">Administrator</small>
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-content container-fluid">
            {{-- STAT CARDS --}}
            @php
                $stats = [
                    ['title' => 'Total Cafe', 'value' => '48', 'change' => '+3 cafe bulan ini', 'icon' => 'bi-shop', 'tone' => 'tone-orange'],
                    ['title' => 'Review Masuk', 'value' => '320', 'change' => '+42 minggu ini', 'icon' => 'bi-chat-left-text', 'tone' => 'tone-green'],
                    ['title' => 'Pengguna Aktif', 'value' => '1.280', 'change' => '+86 bulan ini', 'icon' => 'bi-people', 'tone' => 'tone-blue'],
                    ['title' => 'Perlu Tindakan', 'value' => '5', 'change' => 'Laporan, segera tinjau', 'icon' => 'bi-exclamation-triangle', 'tone' => 'tone-red'],
                ];
            @endphp
            <div class="row g-3 g-xl-4 mb-4">
                @foreach ($stats as $stat)
                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="stat-card card h-100">
                            <div class="card-body">
                                <div class="stat-icon {{ $stat['tone'] }}"><i class="bi {{ $stat['icon'] }}"></i></div>
                                <div class="stat-info">
                                    <div class="stat-title">{{ $stat['title'] }}</div>
                                    <div class="stat-value">{{ $stat['value'] }}</div>
                                    <div class="stat-change {{ $stat['tone'] === 'tone-red' ? 'text-danger' : 'text-success' }}">{{ $stat['change'] }}</div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            {{-- CHART + POPULAR CAFES --}}
            <div class="row g-3 g-xl-4 mb-4">
                <div class="col-12 col-xl-7">
                    <section class="dashboard-card h-100">
                        <div class="card-heading">
                            <div>
                                <h5>Review masuk per minggu</h5>
                                <p>Tren ulasan pengguna</p>
                            </div>
                            <select id="chartPeriod" class="form-select form-select-sm chart-period" aria-label="Periode grafik">
                                <option value="4">4 minggu terakhir</option>
                                <option value="8" selected>8 minggu terakhir</option>
                                <option value="12">12 minggu terakhir</option>
                            </select>
                        </div>
                        <div class="chart-wrap">
                            <canvas id="reviewChart" aria-label="Grafik review mingguan"></canvas>
                        </div>
                    </section>
                </div>

                <div class="col-12 col-xl-5">
                    <section class="dashboard-card h-100">
                        <div class="card-heading">
                            <div><h5>Cafe terpopuler</h5><p>Berdasarkan rating pengguna</p></div>
                            <a href="#" class="view-all">Lihat semua</a>
                        </div>
                        @php
                            $cafes = [
                                ['Grind Now Coffe Bar', 'Batam Kota', '4.9'],
                                ['Temu Kopi', 'Batam Kota', '4.8'],
                                ['Senja Coffee House', 'Sekupang', '4.8'],
                                ['Ruang Seduh', 'Lubuk Baja', '4.7'],
                                ['Anchor Cafe & Roastery', 'Nagoya', '4.6'],
                            ];
                        @endphp
                        <div class="popular-list">
                            @foreach ($cafes as $index => $cafe)
                                <div class="popular-item">
                                    <span class="rank">{{ $index + 1 }}</span>
                                    <div class="cafe-icon"><i class="bi bi-cup-hot"></i></div>
                                    <div class="cafe-info"><strong>{{ $cafe[0] }}</strong><small>{{ $cafe[1] }}</small></div>
                                    <span class="cafe-rating"><i class="bi bi-star-fill"></i> {{ $cafe[2] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>

            {{-- ACTIVITY + REPORT --}}
            <div class="row g-3 g-xl-4">
                <div class="col-12 col-xl-7">
                    <section class="dashboard-card h-100">
                        <div class="card-heading">
                            <div><h5>Aktivitas terbaru</h5><p>Aktivitas terkini pada platform</p></div>
                            <a href="#" class="view-all">Lihat semua</a>
                        </div>
                        @php
                            $activities = [
                                ['icon' => 'bi-chat-left-text', 'tone' => 'tone-green', 'title' => 'Review baru ditambahkan', 'detail' => 'Pengguna memberikan review pada Grind Now Coffe Bar', 'time' => '5 menit lalu'],
                                ['icon' => 'bi-shop', 'tone' => 'tone-orange', 'title' => 'Cafe baru terdaftar', 'detail' => 'Kopi Seberang mengajukan pendaftaran cafe', 'time' => '25 menit lalu'],
                                ['icon' => 'bi-person-plus', 'tone' => 'tone-blue', 'title' => 'Pengguna baru bergabung', 'detail' => 'Pengguna baru mendaftar ke Coffenfo', 'time' => '1 jam lalu'],
                                ['icon' => 'bi-flag', 'tone' => 'tone-red', 'title' => 'Laporan baru diterima', 'detail' => 'Laporan review menunggu pemeriksaan admin', 'time' => '2 jam lalu'],
                            ];
                        @endphp
                        <div class="activity-list">
                            @foreach ($activities as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon {{ $activity['tone'] }}"><i class="bi {{ $activity['icon'] }}"></i></div>
                                    <div class="activity-info"><strong>{{ $activity['title'] }}</strong><p>{{ $activity['detail'] }}</p><small>{{ $activity['time'] }}</small></div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>

                <div class="col-12 col-xl-5">
                    <section class="dashboard-card h-100">
                        <div class="card-heading">
                            <div><h5>Laporan perlu ditinjau</h5><p>Daftar laporan yang membutuhkan tindakan</p></div>
                            <span class="report-count">5 laporan</span>
                        </div>
                        @php
                            $reports = [
                                ['id' => 101, 'title' => 'Review mengandung spam', 'place' => 'Grind Now Coffe Bar', 'time' => '15 menit lalu', 'type' => 'Spam'],
                                ['id' => 102, 'title' => 'Informasi cafe tidak sesuai', 'place' => 'Temu Kopi', 'time' => '1 jam lalu', 'type' => 'Informasi'],
                                ['id' => 103, 'title' => 'Foto tidak pantas', 'place' => 'Senja Coffee House', 'time' => '3 jam lalu', 'type' => 'Konten'],
                            ];
                        @endphp
                        <div class="report-list">
                            @foreach ($reports as $report)
                                <div class="report-item">
                                    <div class="report-top"><span class="report-type">{{ $report['type'] }}</span><small>{{ $report['time'] }}</small></div>
                                    <strong>{{ $report['title'] }}</strong>
                                    <p>{{ $report['place'] }}</p>
                                    <button type="button" class="btn btn-sm btn-review-report" data-report-id="{{ $report['id'] }}" data-report-title="{{ $report['title'] }}">Tinjau laporan <i class="bi bi-arrow-right"></i></button>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
