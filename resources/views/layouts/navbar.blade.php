<nav class="navbar navbar-expand-lg shadow-sm bg-white px-4">

    <span class="fw-semibold fs-5">
        Dashboard
    </span>

    <div class="ms-auto d-flex align-items-center">
        <span class="me-3 badge bg-primary text-white" style="font-size:0.9rem;padding:0.5rem 0.8rem;border-radius:8px;">Administrator</span>
        @includeWhen(View::exists('components.header.user-dropdown'), 'components.header.user-dropdown')
    </div>

</nav>