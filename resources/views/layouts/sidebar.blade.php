<aside class="main-sidebar sidebar-light-primary elevation-0">

    {{-- Brand Logo --}}
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-0">
        <span class="brand-text">
            <span class="d-block" style="font-size: 13.5px; font-weight: 700; line-height: 1.2;">SMK Muhammadiyah</span>
            <span class="d-block text-muted" style="font-size: 11px; font-weight: 500;">Margasari</span>
        </span>
    </a>

    {{-- Sidebar Content --}}
    <div class="sidebar">
        <nav class="pt-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') || request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- MASTER DATA --}}
                <li class="nav-header">MASTER DATA</li>
                <li class="nav-item">
                    <a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') || request()->is('siswa*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Data Siswa</p>
                    </a>
                </li>

                {{-- PEMBAYARAN --}}
                <li class="nav-header">PEMBAYARAN</li>
                <li class="nav-item">
                    <a href="{{ route('rekap.index') }}" class="nav-link {{ request()->routeIs('rekap.*') || request()->is('rekap*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>Rekap Pembayaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ipp.index') }}" class="nav-link {{ request()->routeIs('ipp.*') || request()->is('ipp*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Pembayaran IPP</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('du.index') }}" class="nav-link {{ request()->routeIs('du.*') || request()->is('du*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Daftar Ulang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sarpras.index') }}" class="nav-link {{ request()->routeIs('sarpras.*') || request()->is('sarpras*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <p>Sarana & Prasarana</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ki.index') }}" class="nav-link {{ request()->routeIs('ki.*') || request()->is('ki*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Kegiatan Intrakurikuler</p>
                    </a>
                </li>

                {{-- DANA BOS --}}
                <li class="nav-header">DANA BOS</li>
                <li class="nav-item">
                    <a href="{{ route('bos.index') }}" class="nav-link {{ request()->routeIs('bos.*') || request()->is('bos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>Dana BOS</p>
                    </a>
                </li>

                {{-- PENGELUARAN --}}
                <li class="nav-header">PENGELUARAN</li>
                <li class="nav-item">
                    <a href="{{ route('pengeluaran.index') }}" class="nav-link {{ request()->routeIs('pengeluaran.*') || request()->is('pengeluaran*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Pengeluaran</p>
                    </a>
                </li>

                {{-- LAPORAN --}}
                <li class="nav-header">LAPORAN</li>
                <li class="nav-item">
                    <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') || request()->is('laporan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Laporan</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>

</aside>