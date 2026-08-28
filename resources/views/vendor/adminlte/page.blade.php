@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
    <style>
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
