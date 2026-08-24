@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    $rawTitle = View::getSection('page_heading') ?? View::getSection('page_title') ?? View::getSection('title') ?? 'Dashboard';
    $pageDisplayTitle = trim(explode('—', explode('-', $rawTitle)[0])[0]);
    if (empty($pageDisplayTitle) || strtolower($pageDisplayTitle) === 'adminlte 3') {
        $pageDisplayTitle = 'Dashboard';
    }
@endphp

<nav class="main-header navbar navbar-expand navbar-dark">

    {{-- SISI KIRI: Tombol Toggle Sidebar Squircle « + Tulisan Dashboard dengan Ikon Rumah (Spasi Pas Presisi) --}}
    <ul class="navbar-nav align-items-center">
        {{-- Tombol Toggle Sidebar (Squircle Putih dengan Ikon Double Chevron «) --}}
        <li class="nav-item d-flex align-items-center">
            <a class="nav-link nav-pushmenu-btn d-flex align-items-center justify-content-center" data-widget="pushmenu" href="#" role="button" title="Buka/Tutup Sidebar">
                <i class="fas fa-angle-double-left toggler-double-chevron"></i>
            </a>
        </li>

        {{-- Tulisan Dashboard di Navbar Hijau dengan Ikon Rumah --}}
        <li class="nav-item d-flex align-items-center" style="margin-left: 10px;">
            <span class="navbar-brand-page-title d-inline-flex align-items-center text-white font-weight-bold">
                <i class="fas fa-home mr-2" style="font-size: 14px; opacity: 0.95;"></i> {{ $pageDisplayTitle }}
            </span>
        </li>

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- SISI KANAN: Tanggal (Pill Ringkas & Elegan) + User Profile Dropdown --}}
    <ul class="navbar-nav ml-auto align-items-center">

        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- 1. Tombol Tanggal & Jam Ringkas (Membuka Sidebar / Drawer Tanggal Sebelah Kanan) --}}
        <li class="nav-item mr-2.5">
            <button type="button" class="btn nav-date-pill d-inline-flex align-items-center" id="btnToggleCalendarDrawer" title="Buka Kalender & Waktu">
                <i class="far fa-calendar-alt nav-date-icon mr-1.5"></i>
                <span id="navLiveDateText" class="nav-date-text">Memuat...</span>
                <span class="nav-date-dot mx-1.5">·</span>
                <span id="navLiveTimeText" class="nav-time-text">00:00</span>
                <i class="fas fa-chevron-right nav-date-chevron ml-1.5"></i>
            </button>
        </li>

        {{-- 2. User Menu Link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>
</nav>

{{-- =========================================================
     SIDEBAR / DRAWER KANAN: KALENDER & WAKTU INTERAKTIF
     ========================================================= --}}
<div class="calendar-drawer-backdrop" id="calendarDrawerBackdrop" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1040; background: rgba(15,23,42,0.35);"></div>

<aside class="calendar-drawer-panel" id="calendarDrawerPanel" style="position: fixed; top: 0; right: 0; bottom: 0; width: 340px; max-width: 90vw; background: #ffffff; z-index: 1050; transform: translateX(100%); transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: -4px 0 24px rgba(0,0,0,0.15); overflow-y: auto;">
    {{-- Drawer Header --}}
    <div class="calendar-drawer-header d-flex align-items-center justify-content-between" style="background: #15803d; color: #ffffff; padding: 16px 20px;">
        <div class="d-flex align-items-center">
            <div class="drawer-header-icon mr-2" style="width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.2); color: #ffffff; display: inline-flex; align-items: center; justify-content: center;">
                <i class="far fa-calendar-alt" style="color: #ffffff; font-size: 14px;"></i>
            </div>
            <div>
                <h6 class="drawer-title m-0 font-weight-bold" style="color: #ffffff; font-size: 15px;">Kalender & Waktu</h6>
                <span class="drawer-sub" style="color: rgba(255, 255, 255, 0.85); font-size: 11.5px;">Panel waktu dan kalender kerja</span>
            </div>
        </div>
        <button type="button" class="btn-close-drawer" id="btnCloseCalendarDrawer" title="Tutup" style="width: 30px; height: 30px; border-radius: 8px; background: rgba(255,255,255,0.2); color: #ffffff; border: none; display: inline-flex; align-items: center; justify-content: center; cursor: pointer;">
            <i class="fas fa-times" style="color: #ffffff; font-size: 13px;"></i>
        </button>
    </div>

    {{-- Drawer Body --}}
    <div class="calendar-drawer-body">

        {{-- Realtime Digital Clock Box --}}
        <div class="drawer-clock-card mb-3">
            <div class="drawer-clock-time" id="drawerClockTime">00:00:00</div>
            <div class="drawer-clock-date" id="drawerClockDate">Memuat tanggal...</div>
        </div>

        {{-- Interactive Monthly Calendar --}}
        <div class="drawer-calendar-box mb-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="font-weight-bold text-dark m-0" id="calCurrentMonthYear" style="font-size: 13.5px;">-</h6>
                <div class="cal-nav-buttons d-flex gap-1">
                    <button type="button" class="btn-cal-nav" id="btnPrevMonth" title="Bulan Sebelumnya">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="btn-cal-nav" id="btnNextMonth" title="Bulan Selanjutnya">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <table class="table-mini-cal">
                <thead>
                    <tr>
                        <th class="text-danger">Min</th>
                        <th>Sen</th>
                        <th>Sel</th>
                        <th>Rab</th>
                        <th>Kam</th>
                        <th>Jum</th>
                        <th class="text-primary">Sab</th>
                    </tr>
                </thead>
                <tbody id="calDaysGrid">
                    {{-- Populated by JS --}}
                </tbody>
            </table>
        </div>

        {{-- Quick Summary Card --}}
        <div class="drawer-info-card">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-school text-success mr-2" style="font-size: 14px;"></i>
                <span class="font-weight-bold text-dark" style="font-size: 13px;">SMK Muhammadiyah Margasari</span>
            </div>
            <p class="text-muted small mb-2" style="font-size: 11.5px; line-height: 1.4;">
                Gunakan panel ini untuk mengecek tanggal transaksi dan jadwal operasional pembayaran sekolah.
            </p>
            <div class="d-flex justify-content-between align-items-center pt-2 border-top" style="font-size: 11.5px;">
                <span class="text-muted">Status Sistem</span>
                <span class="badge badge-success" style="font-size: 10.5px; padding: 3px 8px; border-radius: 999px;">
                    <i class="fas fa-circle mr-1" style="font-size: 8px;"></i> Online
                </span>
            </div>
        </div>

    </div>
</aside>

@push('js')
<script>
    (function () {
        // ── Realtime Clock & Date Update (Ringkas & Presisi) ──
        function updateLiveDateTime() {
            const now = new Date();
            
            // Format Hari & Tanggal Singkat (contoh: "Min, 23 Agu")
            const optionsDate = { weekday: 'short', day: 'numeric', month: 'short' };
            const dateStr = now.toLocaleDateString('id-ID', optionsDate);

            // Format Jam Menit
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeStr = hours + ':' + minutes;
            const fullTimeStr = hours + ':' + minutes + ':' + seconds;

            const fullDateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const fullDateStr = now.toLocaleDateString('id-ID', fullDateOptions);

            const navDateEl = document.getElementById('navLiveDateText');
            const navTimeEl = document.getElementById('navLiveTimeText');
            const drawerTimeEl = document.getElementById('drawerClockTime');
            const drawerDateEl = document.getElementById('drawerClockDate');

            if (navDateEl) navDateEl.textContent = dateStr;
            if (navTimeEl) navTimeEl.textContent = timeStr;
            if (drawerTimeEl) drawerTimeEl.textContent = fullTimeStr + ' WIB';
            if (drawerDateEl) drawerDateEl.textContent = fullDateStr;
        }

        updateLiveDateTime();
        setInterval(updateLiveDateTime, 1000);

        // ── Calendar Drawer Toggle ──
        const btnToggle = document.getElementById('btnToggleCalendarDrawer');
        const btnClose = document.getElementById('btnCloseCalendarDrawer');
        const drawerPanel = document.getElementById('calendarDrawerPanel');
        const backdrop = document.getElementById('calendarDrawerBackdrop');

        function openDrawer() {
            if (drawerPanel && backdrop) {
                backdrop.style.display = 'block';
                setTimeout(function() {
                    drawerPanel.classList.add('is-open');
                    backdrop.classList.add('is-open');
                }, 10);
                renderMiniCalendar(currentCalDate);
            }
        }

        function closeDrawer() {
            if (drawerPanel && backdrop) {
                drawerPanel.classList.remove('is-open');
                backdrop.classList.remove('is-open');
                setTimeout(function () {
                    backdrop.style.display = 'none';
                }, 280);
            }
        }

        if (btnToggle) btnToggle.addEventListener('click', openDrawer);
        if (btnClose) btnClose.addEventListener('click', closeDrawer);
        if (backdrop) backdrop.addEventListener('click', closeDrawer);

        // ── Mini Calendar Logic ──
        let currentCalDate = new Date();

        function renderMiniCalendar(dateObj) {
            const grid = document.getElementById('calDaysGrid');
            const label = document.getElementById('calCurrentMonthYear');
            if (!grid || !label) return;

            const year = dateObj.getFullYear();
            const month = dateObj.getMonth();

            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            label.textContent = monthNames[month] + ' ' + year;

            const firstDayIndex = new Date(year, month, 1).getDay(); // 0 is Sunday
            const totalDays = new Date(year, month + 1, 0).getDate();
            const prevTotalDays = new Date(year, month, 0).getDate();

            const today = new Date();
            const isCurrentMonth = (today.getFullYear() === year && today.getMonth() === month);

            let html = '<tr>';
            let dayCount = 0;

            // Previous month trailing days
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                const prevDay = prevTotalDays - i;
                html += '<td class="cal-day other-month">' + prevDay + '</td>';
                dayCount++;
            }

            // Current month days
            for (let day = 1; day <= totalDays; day++) {
                if (dayCount % 7 === 0 && dayCount !== 0) {
                    html += '</tr><tr>';
                }

                const isToday = isCurrentMonth && (today.getDate() === day);
                const dayClass = isToday ? 'cal-day is-today' : 'cal-day';

                html += '<td class="' + dayClass + '"><span>' + day + '</span></td>';
                dayCount++;
            }

            // Next month leading days
            let nextDay = 1;
            while (dayCount % 7 !== 0) {
                html += '<td class="cal-day other-month">' + nextDay + '</td>';
                nextDay++;
                dayCount++;
            }

            html += '</tr>';
            grid.innerHTML = html;
        }

        const btnPrev = document.getElementById('btnPrevMonth');
        const btnNext = document.getElementById('btnNextMonth');

        if (btnPrev) {
            btnPrev.addEventListener('click', function () {
                currentCalDate.setMonth(currentCalDate.getMonth() - 1);
                renderMiniCalendar(currentCalDate);
            });
        }

        if (btnNext) {
            btnNext.addEventListener('click', function () {
                currentCalDate.setMonth(currentCalDate.getMonth() + 1);
                renderMiniCalendar(currentCalDate);
            });
        }

        renderMiniCalendar(currentCalDate);
    })();
</script>
@endpush
