@extends('layouts.admin')

@section('title', 'Pengaturan')

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
                    <h3 class="fw-bold mb-1">Pengaturan</h3>
                    <p class="text-muted small mb-0">
                        Kelola profil admin, platform, moderasi, dan notifikasi
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button class="btn notification-button"
                        id="notificationButton"
                        type="button">
                    <i class="bi bi-bell"></i>
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

            <div class="settings-layout">

                {{-- SETTINGS NAVIGATION --}}
                <aside class="settings-nav-card">
                    <button class="settings-nav-item active"
                            data-settings-tab="profil"
                            type="button">
                        <i class="bi bi-people"></i>
                        <span>Profil Admin</span>
                    </button>

                    <button class="settings-nav-item"
                            data-settings-tab="platform"
                            type="button">
                        <i class="bi bi-sun"></i>
                        <span>Platform</span>
                    </button>

                    <button class="settings-nav-item"
                            data-settings-tab="moderasi"
                            type="button">
                        <i class="bi bi-shield-check"></i>
                        <span>Moderasi</span>
                    </button>

                    <button class="settings-nav-item"
                            data-settings-tab="notifikasi"
                            type="button">
                        <i class="bi bi-bell"></i>
                        <span>Notifikasi</span>
                    </button>
                </aside>

                {{-- SETTINGS PANELS --}}
                <div class="settings-panels">

                    {{-- PROFIL ADMIN --}}
                    <section class="settings-section"
                             id="settings-profil">

                        <div class="settings-card">
                            <h5 class="settings-card-title">Profil admin</h5>

                            <div class="admin-profile-edit">
                                <div class="settings-avatar" id="settingsAvatar">
                                    AD
                                </div>

                                <div class="admin-profile-info">
                                    <strong id="profileDisplayName">
                                        Admin Coffenfo
                                    </strong>
                                    <small id="profileDisplayEmail">
                                        admin@coffenfo.id
                                    </small>
                                </div>

                                <label for="adminPhoto"
                                       class="btn settings-outline-btn ms-auto">
                                    Ubah foto
                                </label>

                                <input type="file"
                                       id="adminPhoto"
                                       accept="image/*"
                                       hidden>
                            </div>

                            <div class="row g-3 mt-2">

                                <div class="col-md-6">
                                    <label class="settings-label">
                                        Nama lengkap
                                    </label>
                                    <input type="text"
                                           class="form-control settings-input"
                                           id="adminName"
                                           value="Admin Coffenfo">
                                </div>

                                <div class="col-md-6">
                                    <label class="settings-label">
                                        Email
                                    </label>
                                    <input type="email"
                                           class="form-control settings-input"
                                           id="adminEmail"
                                           value="admin@coffenfo.id">
                                </div>

                                <div class="col-md-6">
                                    <label class="settings-label">
                                        Peran
                                    </label>
                                    <select class="form-select settings-input"
                                            id="adminRole">
                                        <option>Super Admin</option>
                                        <option>Admin</option>
                                        <option>Moderator</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="settings-label">
                                        Nomor WhatsApp
                                    </label>
                                    <input type="tel"
                                           class="form-control settings-input"
                                           id="adminWhatsapp"
                                           value="0812 3456 7890">
                                </div>

                            </div>
                        </div>
                    </section>

                    {{-- PLATFORM --}}
                    <section class="settings-section"
                             id="settings-platform">

                        <div class="settings-card">
                            <h5 class="settings-card-title">
                                Pengaturan platform
                            </h5>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="settings-label">
                                        Nama platform
                                    </label>
                                    <input type="text"
                                           class="form-control settings-input"
                                           id="platformName"
                                           value="Coffenfo">
                                </div>

                                <div class="col-md-6">
                                    <label class="settings-label">
                                        Kota default
                                    </label>
                                    <select class="form-select settings-input"
                                            id="defaultCity">
                                        <option>Batam</option>
                                        <option>Tanjung Pinang</option>
                                        <option>Jakarta</option>
                                        <option>Bandung</option>
                                        <option>Surabaya</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="settings-label">
                                        Bahasa
                                    </label>
                                    <select class="form-select settings-input"
                                            id="platformLanguage">
                                        <option>Bahasa Indonesia</option>
                                        <option>English</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="settings-label">
                                        Zona waktu
                                    </label>
                                    <select class="form-select settings-input"
                                            id="platformTimezone">
                                        <option>WIB (UTC+7)</option>
                                        <option>WITA (UTC+8)</option>
                                        <option>WIT (UTC+9)</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </section>

                    {{-- MODERASI --}}
                    <section class="settings-section"
                             id="settings-moderasi">

                        <div class="settings-card">
                            <h5 class="settings-card-title">
                                Moderasi review
                            </h5>

                            <div class="settings-toggle-row">
                                <div>
                                    <strong>
                                        Review baru perlu persetujuan admin
                                    </strong>
                                    <small>
                                        Review tampil setelah disetujui moderator.
                                    </small>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input settings-switch"
                                           type="checkbox"
                                           id="approvalToggle">
                                </div>
                            </div>

                            <div class="settings-toggle-row">
                                <div>
                                    <strong>Filter otomatis kata kasar</strong>
                                    <small>
                                        Tandai review berisi kata kasar secara otomatis.
                                    </small>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input settings-switch"
                                           type="checkbox"
                                           id="profanityToggle">
                                </div>
                            </div>

                            <div class="settings-toggle-row">
                                <div>
                                    <strong>Deteksi spam & promosi</strong>
                                    <small>
                                        Tandai review berisi nomor kontak atau tautan promosi.
                                    </small>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input settings-switch"
                                           type="checkbox"
                                           id="spamToggle">
                                </div>
                            </div>

                            <div class="settings-toggle-row">
                                <div>
                                    <strong>Sembunyikan setelah 3 laporan</strong>
                                    <small>
                                        Review disembunyikan otomatis menunggu ditinjau.
                                    </small>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input settings-switch"
                                           type="checkbox"
                                           id="autoHideToggle">
                                </div>
                            </div>

                        </div>
                    </section>

                    {{-- NOTIFIKASI --}}
                    <section class="settings-section"
                             id="settings-notifikasi">

                        <div class="settings-card">
                            <h5 class="settings-card-title">
                                Notifikasi admin
                            </h5>

                            <div class="settings-toggle-row">
                                <div>
                                    <strong>Laporan baru</strong>
                                    <small>
                                        Beri notifikasi ketika pengguna mengirim laporan.
                                    </small>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input settings-switch"
                                           type="checkbox"
                                           id="reportNotifToggle"
                                           checked>
                                </div>
                            </div>

                            <div class="settings-toggle-row">
                                <div>
                                    <strong>Review menunggu moderasi</strong>
                                    <small>
                                        Notifikasi review baru yang perlu diperiksa.
                                    </small>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input settings-switch"
                                           type="checkbox"
                                           id="reviewNotifToggle"
                                           checked>
                                </div>
                            </div>

                            <div class="settings-toggle-row">
                                <div>
                                    <strong>Verifikasi cafe baru</strong>
                                    <small>
                                        Notifikasi cafe yang mendaftar di Coffenfo.
                                    </small>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input settings-switch"
                                           type="checkbox"
                                           id="cafeNotifToggle"
                                           checked>
                                </div>
                            </div>

                            <div class="settings-toggle-row">
                                <div>
                                    <strong>Ringkasan aktivitas mingguan</strong>
                                    <small>
                                        Terima ringkasan statistik platform setiap minggu.
                                    </small>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input settings-switch"
                                           type="checkbox"
                                           id="weeklyNotifToggle">
                                </div>
                            </div>

                        </div>
                    </section>

                    {{-- SAVE --}}
                    <div class="settings-save-bar">
                        <button type="button"
                                class="btn settings-save-btn"
                                id="saveSettingsButton">
                            <i class="bi bi-check2 me-1"></i>
                            Simpan Pengaturan
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>
@endsection