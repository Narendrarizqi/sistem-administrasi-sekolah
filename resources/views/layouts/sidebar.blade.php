<div class="sidebar">

    <div class="logo-area">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <div>
            <h5>SMK Muhammadiyah</h5>
            <span>Margasari</span>
        </div>
    </div>

    <div class="menu-title">
        MENU
    </div>

    <a href="{{ route('dashboard') }}" class="menu {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        Dashboard
    </a>

    <div class="menu-title">
        MASTER DATA
    </div>

    <a href="{{ route('siswa.index') }}" class="menu {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
        <i class="fas fa-user-graduate"></i>
        Data Siswa
    </a>

    <div class="menu-title">
        PEMBAYARAN
    </div>

    <a href="{{ route('rekap.index') }}" class="menu {{ request()->routeIs('rekap.*') ? 'active' : '' }}">
        <i class="fas fa-table"></i>
        Rekap Pembayaran
    </a>

    <a href="{{ route('ipp.index') }}" class="menu {{ request()->routeIs('ipp.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i>
        Pembayaran IPP
    </a>

    <a href="{{ route('du.index') }}" class="menu {{ request()->routeIs('du.*') ? 'active' : '' }}">
        <i class="fas fa-file-invoice"></i>
        Daftar Ulang
    </a>

    <a href="{{ route('sarpras.index') }}" class="menu {{ request()->routeIs('sarpras.*') ? 'active' : '' }}">
        <i class="fas fa-school"></i>
        Sarana & Prasarana
    </a>

    <a href="{{ route('ki.index') }}" class="menu {{ request()->routeIs('ki.*') ? 'active' : '' }}">
        <i class="fas fa-book-open"></i>
        Kegiatan Intrakurikuler
    </a>

    <div class="menu-title">
        DANA BOS
    </div>

    <a href="{{ route('bos.index') }}" class="menu {{ request()->routeIs('bos.*') ? 'active' : '' }}">
        <i class="fas fa-hand-holding-usd"></i>
        Dana BOS
    </a>

    <div class="menu-title">
        PENGELUARAN
    </div>

    <a href="{{ route('pengeluaran.index') }}" class="menu {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
        <i class="fas fa-money-bill-wave"></i>
        Pengeluaran
    </a>

    <div class="menu-title">
        LAPORAN
    </div>

    <a href="{{ route('laporan.index') }}" class="menu {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
        <i class="fas fa-book"></i>
        Laporan
    </a>

    <div class="menu-title">
        LAINNYA
    </div>

    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit" class="menu" style="width:100%;border:none;background:none;color:inherit;text-align:left;padding:0;margin:0;cursor:pointer;">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </form>

</div>