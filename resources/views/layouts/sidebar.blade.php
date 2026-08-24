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

    <a href="#" class="menu active">
        <i class="fas fa-house"></i>
        Dashboard
    </a>

    <a href="#">
        <i class="fas fa-user-graduate"></i>
        Data Siswa
    </a>

    <div class="menu-title">
        PEMBAYARAN
    </div>

    <a href="#">
        <i class="fas fa-file-invoice"></i>
        Rekap Pembayaran
    </a>

    <a href="#">
        <i class="fas fa-wallet"></i>
        Pembayaran IPP
    </a>

    <a href="#">
        <i class="fas fa-address-card"></i>
        Daftar Ulang
    </a>

    <a href="#">
        <i class="fas fa-school"></i>
        Sarana & Prasarana
    </a>

    <a href="#">
        <i class="fas fa-book-open"></i>
        Kegiatan Intrakurikuler
    </a>

   <div class="menu-title">
    LAINNYA
</div>

<form action="{{ route('logout') }}" method="POST" style="margin:0;">
    @csrf
    <button type="submit" class="menu" style="width:100%;border:none;background:none;color:inherit;text-align:left;padding:0;margin:0;cursor:pointer;">
        <i class="fas fa-right-from-bracket"></i>
        Logout
    </button>
</form>

</div>  