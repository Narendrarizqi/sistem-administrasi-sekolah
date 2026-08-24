<footer class="main-footer">
    @hasSection('footer')
        @yield('footer')
    @else
        <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2 text-muted" style="font-size: 12px;">
            <div>
                © {{ date('Y') }} Bagus Narendra Rizqi Ananto. All rights reserved.
            </div>
            <div>
                Aplikasi Pembayaran Sekolah
            </div>
        </div>
    @endif
</footer>
