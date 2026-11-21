<aside class="sidebar" id="adminSidebar">

    {{-- BRAND --}}
    <div class="sidebar-brand">
        <h4 class="fw-bold mb-0">☕ Coffenfo</h4>
        <small>ADMIN PANEL</small>
    </div>

    {{-- WORKSPACE --}}
    <div class="sidebar-section">
        <small class="sidebar-heading">WORKSPACE</small>

        <nav class="nav flex-column mt-3 gap-2">

            {{-- DASHBOARD --}}
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link sidebar-link
               {{ request()->routeIs('admin.dashboard') ? 'active-menu' : '' }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard Overview</span>
            </a>

            {{-- KELOLA --}}
            <a href="{{ route('admin.kelola') }}"
               class="nav-link sidebar-link
               {{ request()->routeIs('admin.kelola') ? 'active-menu' : '' }}">
                <i class="bi bi-gear"></i>
                <span>Kelola</span>
            </a>

            {{-- BEANS & ROASTING --}}
            <a href="{{ route('admin.kelolabeans') }}"
               class="nav-link sidebar-link
               {{ request()->routeIs('admin.kelolabeans') ? 'active-menu' : '' }}">
                <i class="bi bi-fire"></i>
                <span>Kelola Beans & Roasting</span>
            </a>

            {{-- MODERASI REVIEW --}}
            <a href="{{ route('admin.review') }}"
               class="nav-link sidebar-link
               {{ request()->routeIs('admin.review') ? 'active-menu' : '' }}">
                <i class="bi bi-shield-check"></i>
                <span>Moderasi Review</span>
            </a>

            {{-- LAPORAN --}}
            <a href="{{ route('admin.laporan') }}"
               class="nav-link sidebar-link
               {{ request()->routeIs('admin.laporan*') ? 'active-menu' : '' }}">
                <i class="bi bi-flag"></i>
                <span>Laporan Pengguna</span>

                <span class="badge bg-danger rounded-pill ms-auto">
                    5
                </span>
            </a>

            {{-- PENGATURAN --}}
            <a href="{{ route('admin.pengaturan') }}"
               class="nav-link sidebar-link
               {{ request()->routeIs('admin.pengaturan*') ? 'active-menu' : '' }}">
                <i class="bi bi-sliders"></i>
                <span>Pengaturan</span>
            </a>

        </nav>
    </div>

    {{-- SIDEBAR FOOTER --}}
    <div class="sidebar-footer">

        <div class="sidebar-admin-avatar">AD</div>

        <div class="min-w-0">
            <div class="fw-semibold small">Admin Coffenfo</div>
            <small>Administrator</small>
        </div>

    </div>

</aside>

{{-- MOBILE OVERLAY --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>