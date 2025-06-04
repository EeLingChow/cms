<!DOCTYPE html>
<html lang="en">

<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <title>{{ config('admin.name') }} @yield('title')</title>
    <meta name="robots" content="noindex">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1" name="viewport" />

    <!--begin::Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <!--end::Fonts -->

    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ cdn('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <!--end::Page Vendors Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{ cdn('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ cdn('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <link href="{{ cdn('assets/css/skins/header/base/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ cdn('assets/css/skins/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ cdn('assets/css/skins/brand/dark.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ cdn('assets/css/skins/aside/dark.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Layout Skins -->

    <link href="{{ cdn('assets/css/app.css?2') }}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ cdn('assets/media/logos/favicon.ico') }}" />
    <script src="{{ cdn('assets/js/svg-inject.min.js') }}"></script>
</head>

<!-- end::Head -->

<!-- begin::Body -->

<body
    class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

    <!-- begin:: Page -->

    <!-- begin:: Header Mobile -->
    <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
        <div class="kt-header-mobile__logo">
            <a href="/" class="logo-title">
                <img alt="Logo" src="{{ cdn('assets/media/logos/logo-light.png') }}" />
                <!-- {{ config('admin.name') }} -->
            </a>
        </div>
        <div class="kt-header-mobile__toolbar">
            <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left"
                id="kt_aside_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i
                    class="flaticon-more"></i></button>
        </div>
    </div>
    <!-- end:: Header Mobile -->

    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            <!-- begin:: Aside -->
            @if (!request()->has('ap'))
                @include('admins._layouts.aside')
            @endif
            <!-- end:: Aside -->

            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                <!-- begin:: Header -->
                @include('admins._layouts.header')
                <!-- end:: Header -->

                <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                    <!-- begin:: Subheader -->
                    @include('admins._layouts.subheader')
                    <!-- end:: Subheader -->

                    <!-- begin:: Content -->
                    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid"
                        data-section="content">
                        @yield('content')
                    </div>
                    <!-- end:: Content -->
                </div>

                <!-- begin:: Footer -->
                @include('admins._layouts.footer')
                <!-- end:: Footer -->
            </div>
        </div>
    </div>

    <!-- end:: Page -->

    <!-- begin::Quick Panel -->
    @include('admins._layouts.quick-panel')
    <!-- end::Quick Panel -->

    <!-- begin::Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>
    <!-- end::Scrolltop -->

    <!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#22b9ff",
                    "light": "#ffffff",
                    "dark": "#282a3c",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };

        var _ctrl, _p;

    </script>
    <!-- end::Global Config -->

    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/ace.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/mode-html.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.4.6/theme-twilight.js"></script>
    <script src="{{ cdn('assets/plugins/global/plugins.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ cdn('assets/js/scripts.bundle.js') }}" type="text/javascript"></script>
    <!--end::Global Theme Bundle -->

    <!--begin::Page Vendors(used by this page) -->
    <script src="{{ cdn('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript">
    </script>
    <script src="{{ cdn('assets/js/app.js') }}" type="text/javascript"></script>
    <!--end::Page Vendors -->

    <!--begin::Page Scripts(used by this page) -->
    <div class="page-script">
        @yield('page-script')
    </div>
</body>

<!-- end::Body -->

</html>
