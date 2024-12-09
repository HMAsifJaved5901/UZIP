<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="light-style layout-navbar-fixed layout-menu-fixed"
      dir="ltr"
      data-theme="theme-default"
      data-assets-path="{{ asset('lib/assets/') }}"
      data-template="vertical-menu-template-no-customizer">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>{{ config('app.name', 'UziWeb') }}</title>

    <meta name="description" content=""/>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('lib/assets/img/favicon/favicon.ico') }}"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
            rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/fonts/fontawesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/fonts/tabler-icons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/fonts/flag-icons.css') }}"/>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/css/rtl/core.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/css/rtl/theme-default.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/assets/css/demo.css') }}">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/node-waves/node-waves.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/typeahead-js/typeahead.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/apex-charts/apex-charts.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/swiper/swiper.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet"
          href="{{ asset('lib/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet"
          href="{{ asset('lib/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">

    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/css/pages/page-auth.css') }}"/>
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/css/pages/cards-advance.css') }}">

    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}"/>
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/select2/select2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/sweetalert2/sweetalert2.css') }}"/>
    <!-- Helpers -->
    <script src="{{ asset('lib/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('lib/assets/js/config.js') }}"></script>
   {{ $links ?? '' }}

    <!-- Scripts
    @vite(['resources/css/app.css', 'resources/js/app.js']) -->
</head>
<body class="font-sans antialiased">
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        @include('layouts.navigation')
        <div class="layout-page">
            <!-- Navbar -->
        @include('layouts.topbar')
        <!-- / Navbar -->
            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
                <!-- Footer -->
            @include('layouts.footer')
            <!-- / Footer -->
                <div class="content-backdrop fade"></div>
            </div>
        </div>

        <!-- / Menu -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('lib/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/node-waves/node-waves.js') }}"></script>

<script src="{{ asset('lib/assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

<script src="{{ asset('lib/assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('lib/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

<script src="{{ asset('lib/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/select2/select2.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('lib/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('lib/assets/js/dashboards-analytics.js') }}"></script>
<script src="{{ asset('lib/assets/js/pages-auth.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/cleavejs/cleave.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
<script src="{{ asset('lib/assets/js/modal-add-role.js') }}"></script>
<script src="{{ asset('lib/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('lib/assets/js/extended-ui-sweetalert2.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{ $scripts ?? '' }}
</body>
</html>
