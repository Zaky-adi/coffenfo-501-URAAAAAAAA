document.addEventListener('DOMContentLoaded', () => {
    // Review chart using Chart.js
    const chartCanvas = document.getElementById('reviewChart');
    const periodSelect = document.getElementById('chartPeriod');

    const chartDatasets = {
        4: { labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'], values: [57, 74, 66, 82] },
        8: { labels: ['31 Agu', '7 Sep', '14 Sep', '21 Sep', '28 Sep', '5 Okt', '12 Okt', 'Minggu ini'], values: [38, 52, 46, 61, 57, 74, 66, 82] },
        12: { labels: ['Jul 1', 'Jul 2', 'Jul 3', 'Jul 4', 'Agu 1', 'Agu 2', 'Agu 3', 'Sep 1', 'Sep 2', 'Sep 3', 'Okt 1', 'Kini'], values: [25, 32, 38, 42, 52, 46, 61, 57, 74, 66, 70, 82] }
    };

    let reviewChart = null;
    function renderReviewChart(period = 8) {
        if (!chartCanvas || typeof Chart === 'undefined') return;
        const data = chartDatasets[period] || chartDatasets[8];
        if (reviewChart) reviewChart.destroy();

        reviewChart = new Chart(chartCanvas, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Review',
                    data: data.values,
                    backgroundColor: data.values.map((_, index) =>
                        index === data.values.length - 1 ? '#c9823e' : '#edcba9'
                    ),
                    borderRadius: 7,
                    borderSkipped: false,
                    barPercentage: 0.62,
                    categoryPercentage: 0.75
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: context => `${context.parsed.y} review` } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#927c6c', maxRotation: 0, autoSkip: true } },
                    y: { beginAtZero: true, ticks: { precision: 0, color: '#927c6c' }, grid: { color: '#f1e9e2' } }
                }
            }
        });
    }

    renderReviewChart(Number(periodSelect?.value || 8));
    periodSelect?.addEventListener('change', event => renderReviewChart(Number(event.target.value)));

    // Mobile sidebar
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar?.classList.add('sidebar-open');
        overlay?.classList.add('overlay-active');
        document.body.classList.add('sidebar-locked');
    }
    function closeSidebar() {
        sidebar?.classList.remove('sidebar-open');
        overlay?.classList.remove('overlay-active');
        document.body.classList.remove('sidebar-locked');
    }
    toggle?.addEventListener('click', openSidebar);
    overlay?.addEventListener('click', closeSidebar);
    sidebar?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
        if (window.innerWidth < 992) closeSidebar();
    }));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeSidebar();
    });

    // Notification demo feedback
    document.getElementById('notificationButton')?.addEventListener('click', () => {
        alert('Belum ada notifikasi baru.');
    });

    // Report review buttons (prototype interaction)
    document.querySelectorAll('.btn-review-report').forEach(button => {
        button.addEventListener('click', () => {
            const title = button.dataset.reportTitle || 'Laporan';
            const id = button.dataset.reportId || '-';
            alert(`Tinjau laporan #${id}\\n${title}\\n\\nIni masih demo frontend. Nantinya tombol ini diarahkan ke halaman detail laporan.`);
        });
    });

    // Bootstrap tooltips if any exist in markup
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(element => {
        if (window.bootstrap?.Tooltip) new bootstrap.Tooltip(element);
    });

    console.info('Coffenfo dashboard initialized.');
});

// =========================================
// SETTINGS PAGE
// =========================================

document.addEventListener('DOMContentLoaded', function () {

    const settingsNavItems = document.querySelectorAll(
        '.settings-nav-item'
    );

    const settingsSections = document.querySelectorAll(
        '.settings-section'
    );

    const saveSettingsButton = document.getElementById(
        'saveSettingsButton'
    );

    const adminName = document.getElementById('adminName');
    const adminEmail = document.getElementById('adminEmail');

    const profileDisplayName = document.getElementById(
        'profileDisplayName'
    );

    const profileDisplayEmail = document.getElementById(
        'profileDisplayEmail'
    );

    const adminPhoto = document.getElementById('adminPhoto');
    const settingsAvatar = document.getElementById('settingsAvatar');

    // NAVIGATION
    settingsNavItems.forEach(function (item) {
        item.addEventListener('click', function () {

            const targetId = item.dataset.settingsTab;
            const targetSection = document.getElementById(
                'settings-' + targetId
            );

            settingsNavItems.forEach(nav => {
                nav.classList.remove('active');
            });

            item.classList.add('active');

            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // PROFILE PREVIEW
    if (adminName && profileDisplayName) {
        adminName.addEventListener('input', function () {
            profileDisplayName.textContent =
                adminName.value || 'Admin Coffenfo';
        });
    }

    if (adminEmail && profileDisplayEmail) {
        adminEmail.addEventListener('input', function () {
            profileDisplayEmail.textContent =
                adminEmail.value || 'admin@coffenfo.id';
        });
    }

    // PHOTO PREVIEW
    if (adminPhoto && settingsAvatar) {
        adminPhoto.addEventListener('change', function (event) {

            const file = event.target.files[0];

            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Silakan pilih file gambar.');
                adminPhoto.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                settingsAvatar.innerHTML = '';

                const img = document.createElement('img');
                img.src = e.target.result;

                settingsAvatar.appendChild(img);
            };

            reader.readAsDataURL(file);
        });
    }

    // SAVE DEMO
    if (saveSettingsButton) {
        saveSettingsButton.addEventListener('click', function () {

            const settings = {
                adminName: document.getElementById('adminName')?.value,
                adminEmail: document.getElementById('adminEmail')?.value,
                adminRole: document.getElementById('adminRole')?.value,
                adminWhatsapp: document.getElementById('adminWhatsapp')?.value,

                platformName: document.getElementById('platformName')?.value,
                defaultCity: document.getElementById('defaultCity')?.value,
                language: document.getElementById('platformLanguage')?.value,
                timezone: document.getElementById('platformTimezone')?.value,

                moderation: {
                    approval: document.getElementById('approvalToggle')?.checked,
                    profanity: document.getElementById('profanityToggle')?.checked,
                    spam: document.getElementById('spamToggle')?.checked,
                    autoHide: document.getElementById('autoHideToggle')?.checked
                },

                notifications: {
                    reports: document.getElementById('reportNotifToggle')?.checked,
                    reviews: document.getElementById('reviewNotifToggle')?.checked,
                    cafes: document.getElementById('cafeNotifToggle')?.checked,
                    weekly: document.getElementById('weeklyNotifToggle')?.checked
                }
            };

            // Sementara demo: simpan ke localStorage
            localStorage.setItem(
                'coffenfoAdminSettings',
                JSON.stringify(settings)
            );

            alert('Pengaturan berhasil disimpan (demo browser).');
        });
    }

});