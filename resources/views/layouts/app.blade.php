<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','SMK Muhammadiyah Margasari')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('css')
<style>
/* Background */
.content-wrapper{
    background:#f4f6f9 !important;
}

/* Sidebar */
.main-sidebar{
    background:linear-gradient(180deg,#0d5d2d,#0a4b24) !important;
}

/* Brand */
.brand-link{
    background:#0b5b2d !important;
    border-bottom:1px solid rgba(255,255,255,.08)!important;
}

/* Navbar */
.main-header{
    background:linear-gradient(90deg,#13823d,#179344)!important;
    border:none!important;
}

/* Sidebar Text */
.nav-sidebar .nav-link{
    color:#ecf0f1!important;
    border-radius:8px;
    margin:4px 8px;
}

/* Make custom sidebar fixed and scrollable */
/* Make custom sidebar fixed and scrollable */
.sidebar,
.main-sidebar {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    height: 100vh !important;
    width: 260px !important;
    padding: 20px !important;
    overflow-y: auto !important;
    z-index: 1030 !important;
    background: linear-gradient(180deg,#0d5d2d,#0a4b24) !important;
}

/* Ensure main content does not sit under the fixed sidebar */
.main-content{
    margin-left:260px !important;
}

/* Hover */
.nav-sidebar .nav-link:hover{
    background:rgba(255,255,255,.12)!important;
}

/* Active */
.nav-sidebar .nav-link.active{
    background:#27ae60!important;
    color:white!important;
    font-weight:600;
}

/* Judul menu */
.nav-header{
    color:#cfd8dc!important;
    font-size:12px;
    letter-spacing:1px;
}
</style>
</head>

<body>

<div class="wrapper">

    @include('layouts.sidebar')

    <div class="main-content">

        @include('layouts.navbar')

        <main class="p-4">
            @yield('content')
        </main>

    </div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            function autoDismissAlerts() {
                setTimeout(function () {
                    var alerts = document.querySelectorAll('.alert.alert-dismissible, .alert-success, .alert-info, .alert-danger.alert-dismissible');
                    alerts.forEach(function (alert) {
                        alert.style.transition = 'opacity 0.5s ease, margin 0.3s ease, padding 0.3s ease, height 0.3s ease';
                        alert.style.opacity = '0';
                        setTimeout(function () {
                            alert.style.display = 'none';
                            if (alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 500);
                    });
                }, 4000);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', autoDismissAlerts);
            } else {
                autoDismissAlerts();
            }
        })();
    </script>
</body>
</html>