@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ file_exists(public_path('css/custom.css')) ? filemtime(public_path('css/custom.css')) : time() }}">
    @stack('css')
    @yield('css')
    <style>
        /* ===============================================================
           GLOBAL COMPACT SIDEBAR DESIGN (215px) - UNIFORM ACROSS ALL PAGES
           =============================================================== */
        :root {
            --sidebar-width: 215px;
        }

        /* Sidebar Container (Compact, Modern SaaS, Pure White) */
        .main-sidebar,
        .main-sidebar::before,
        aside.main-sidebar {
            background: #ffffff !important;
            border-right: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
            overflow-x: hidden !important;
        }

        .main-sidebar .sidebar {
            overflow-x: hidden !important;
            padding-left: 6px !important;
            padding-right: 6px !important;
        }

        @media (min-width: 768px) {
            body:not(.sidebar-collapse) .main-sidebar,
            body:not(.sidebar-collapse) .main-sidebar::before,
            body:not(.sidebar-collapse).layout-fixed .main-sidebar,
            body:not(.sidebar-collapse).layout-fixed .main-sidebar::before,
            body:not(.sidebar-collapse) aside.main-sidebar {
                width: var(--sidebar-width) !important;
                min-width: var(--sidebar-width) !important;
                max-width: var(--sidebar-width) !important;
            }

            body:not(.sidebar-collapse) .content-wrapper,
            body:not(.sidebar-collapse) .main-header,
            body:not(.sidebar-collapse) .main-footer,
            body:not(.sidebar-collapse).layout-fixed .content-wrapper,
            body:not(.sidebar-collapse).layout-fixed .main-header,
            body:not(.sidebar-collapse).layout-fixed .main-footer {
                margin-left: var(--sidebar-width) !important;
            }

            .sidebar-mini.sidebar-collapse .main-sidebar:hover,
            .sidebar-mini.sidebar-collapse .main-sidebar:hover::before,
            .sidebar-mini-lg.sidebar-collapse .main-sidebar:hover,
            .sidebar-mini-lg.sidebar-collapse .main-sidebar:hover::before {
                width: var(--sidebar-width) !important;
            }

            .sidebar-mini.sidebar-collapse .main-sidebar:hover .brand-link,
            .sidebar-mini-lg.sidebar-collapse .main-sidebar:hover .brand-link {
                width: var(--sidebar-width) !important;
            }
        }

        @media (max-width: 767.98px) {
            .main-sidebar,
            .main-sidebar::before,
            aside.main-sidebar {
                width: var(--sidebar-width) !important;
            }
        }

        .content-wrapper,
        .main-header,
        .main-footer {
            transition: margin-left 0.32s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Brand / Logo Area in Sidebar (57px height, fits 215px) */
        .brand-link {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            border-right: 1px solid #e2e8f0 !important;
            padding: 0 12px !important;
            height: 57px !important;
            min-height: 57px !important;
            display: flex !important;
            align-items: center !important;
            gap: 9px !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
            transition: width 0.32s cubic-bezier(0.4, 0, 0.2, 1),
                padding 0.32s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        body:not(.sidebar-collapse) .brand-link,
        body:not(.sidebar-collapse).layout-fixed .brand-link {
            width: var(--sidebar-width) !important;
            max-width: var(--sidebar-width) !important;
        }

        .brand-link .brand-image {
            width: 32px !important;
            height: 32px !important;
            max-height: 32px !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
            border-radius: 50% !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid #e2e8f0 !important;
        }

        .brand-link .brand-text {
            color: #64748b !important;
            font-size: 11px !important;
            line-height: 1.15 !important;
            font-weight: 500 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1),
                visibility 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .brand-link .brand-text b,
        .brand-link .brand-text strong {
            color: #0f172a !important;
            font-size: 12.5px !important;
            font-weight: 700 !important;
            line-height: 1.15 !important;
            letter-spacing: -0.01em !important;
            white-space: nowrap !important;
            margin-bottom: 1px !important;
        }

        .brand-link .brand-text br {
            display: none !important;
        }

        /* Sidebar Section Headers (MASTER DATA, PEMBAYARAN, etc.) */
        .nav-sidebar .nav-header {
            color: #94a3b8 !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            letter-spacing: .08em !important;
            text-transform: uppercase !important;
            padding: 10px 10px 3px !important;
            white-space: nowrap !important;
        }

        /* Sidebar Menu Links */
        .nav-sidebar .nav-link {
            color: #64748b !important;
            border-radius: 7px !important;
            margin: 1.5px 4px !important;
            padding: 6.5px 10px !important;
            font-size: 12.5px !important;
            font-weight: 550 !important;
            line-height: 1.35 !important;
            display: flex !important;
            align-items: center !important;
            transition: background-color .15s ease, color .15s ease !important;
        }

        .nav-sidebar .nav-link p {
            margin-bottom: 0 !important;
            font-size: 12.5px !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .nav-sidebar .nav-link .nav-icon {
            color: #64748b !important;
            font-size: 13.5px !important;
            width: 18px !important;
            text-align: center !important;
            margin-right: 8px !important;
            opacity: 0.85 !important;
            flex-shrink: 0 !important;
            transition: color .15s ease !important;
        }

        .nav-sidebar .nav-link:hover {
            background: #F1F5F9 !important;
            color: #1E293B !important;
        }

        .nav-sidebar .nav-link:hover .nav-icon {
            color: #1E293B !important;
        }

        /* Active Menu Item: Soft Green Pill */
        .nav-sidebar .nav-link.active {
            background: rgba(22, 163, 74, 0.10) !important;
            color: #16A34A !important;
            font-weight: 600 !important;
            box-shadow: none !important;
        }

        .nav-sidebar .nav-link.active .nav-icon {
            color: #16A34A !important;
        }

        .nav-sidebar .nav-treeview .nav-link.active {
            background: rgba(22, 163, 74, 0.08) !important;
            color: #15803D !important;
        }

        /* Collapsed Sidebar State */
        body.sidebar-collapse .brand-link {
            width: 4.6rem !important;
            padding: 0 !important;
            justify-content: center !important;
        }

        body.sidebar-collapse .brand-link .brand-text {
            display: none !important;
        }

        body.sidebar-collapse .brand-link .brand-image {
            margin: 0 auto !important;
        }

        .sidebar-collapse .brand-link .brand-text,
        .sidebar-collapse .nav-sidebar .nav-link p,
        .sidebar-collapse .nav-sidebar .nav-header {
            transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        /* Global Perfect Centering for Alert Close Button */
        .alert.alert-dismissible,
        .alert-dismissible {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            min-height: 46px !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            padding-left: 16px !important;
            padding-right: 48px !important;
        }

        .alert-dismissible .close,
        .alert .close,
        button.close[data-dismiss="alert"] {
            position: absolute !important;
            top: 0 !important;
            bottom: 0 !important;
            right: 14px !important;
            left: auto !important;
            margin: auto 0 !important;
            height: 24px !important;
            width: 24px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            font-size: 22px !important;
            font-weight: 300 !important;
            text-shadow: none !important;
            opacity: .55 !important;
            background: transparent !important;
            border: none !important;
            outline: none !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }

        .alert-dismissible .close > span,
        .alert .close > span,
        button.close[data-dismiss="alert"] > span {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 0 !important;
            font-size: 22px !important;
            height: 100% !important;
            margin-top: -3px !important;
        }

        .alert-dismissible .close:hover,
        .alert .close:hover,
        button.close[data-dismiss="alert"]:hover {
            opacity: 1 !important;
            transform: scale(1.15) !important;
        }
        /* Select2 Bootstrap 4 Theme Polish */
        .select2-container--bootstrap4 .select2-selection--single {
            height: 38px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            font-size: 13.5px !important;
            background-color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        }

        .select2-container--bootstrap4.select2-container--focus .select2-selection--single,
        .select2-container--bootstrap4.select2-container--open .select2-selection--single {
            border-color: #16a34a !important;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15) !important;
        }

        .select2-container--bootstrap4 .select2-dropdown {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            z-index: 9999 !important;
        }

        .select2-container--bootstrap4 .select2-search--dropdown {
            padding: 8px !important;
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            font-size: 13px !important;
            background: #ffffff !important;
        }

        .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field:focus {
            border-color: #16a34a !important;
            box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.15) !important;
            outline: none !important;
        }

        .select2-container--bootstrap4 .select2-results__option {
            padding: 8px 12px !important;
            font-size: 13px !important;
            color: #1e293b !important;
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
            background-color: #16a34a !important;
            color: #ffffff !important;
        }

        .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
            background-color: #f0fdf4 !important;
            color: #15803d !important;
            font-weight: 600 !important;
        }

        .select2-container--bootstrap4 .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%) !important;
            right: 10px !important;
        }

        .select2-selection__clear,
        .select2-container--bootstrap4 .select2-selection__clear {
            display: none !important;
        }
    </style>
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation (fullscreen mode) --}}
        @if($preloaderHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('layouts.sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @include('adminlte::partials.footer.footer')

        {{-- Right Control Sidebar --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script>
        (function () {
            // 1. Auto dismiss alert notifications after 4 seconds
            function autoDismissAlerts() {
                setTimeout(function () {
                    var alerts = document.querySelectorAll('.alert.alert-dismissible, .alert-success, .alert-info, .alert-danger.alert-dismissible');
                    alerts.forEach(function (alert) {
                        if (window.jQuery && typeof window.jQuery(alert).fadeTo === 'function') {
                            window.jQuery(alert).fadeTo(500, 0).slideUp(300, function () {
                                window.jQuery(this).remove();
                            });
                        } else {
                            alert.style.transition = 'opacity 0.5s ease, margin 0.3s ease, padding 0.3s ease, height 0.3s ease';
                            alert.style.opacity = '0';
                            setTimeout(function () {
                                alert.style.display = 'none';
                                if (alert.parentNode) {
                                    alert.parentNode.removeChild(alert);
                                }
                            }, 500);
                        }
                    });
                }, 4000);
            }

            // 2. Universal Searchable Select2 for Student Dropdowns
            function initSearchableSelects(container) {
                if (!window.jQuery || typeof window.jQuery.fn.select2 !== 'function') return;

                var $root = container ? window.jQuery(container) : window.jQuery(document);
                $root.find('select.select2, select[name="siswa_id"], select[name="siswa_ids[]"], #modal_sarpras_siswa_id, #modal_ipp_siswa_id, #modal_du_siswa_id, #modal_ki_siswa_id, #modal_siswa_id, #siswa_id').each(function () {
                    var $select = window.jQuery(this);
                    if ($select.hasClass('select2-hidden-accessible')) {
                        return;
                    }
                    var $modal = $select.closest('.modal');
                    var placeholderText = $select.find('option[value=""]').first().text() || $select.find('option:first').text() || '-- Cari / Ketik Nama Siswa --';

                    $select.select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        placeholder: placeholderText.trim(),
                        allowClear: false,
                        dropdownParent: $modal.length ? $modal : window.jQuery(document.body)
                    });

                    // Trigger native change event so all preview event listeners work
                    $select.on('change.select2bridge', function () {
                        this.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function () {
                    autoDismissAlerts();
                    initSearchableSelects(document);
                });
            } else {
                autoDismissAlerts();
                initSearchableSelects(document);
            }

            if (window.jQuery) {
                window.jQuery(document).on('shown.bs.modal', function (e) {
                    initSearchableSelects(e.target);
                });
            }
        })();
    </script>
@stop
