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

    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('lib/assets/vendor/css/pages/page-auth.css') }}"/>
    <!-- Helpers -->
    <script src="{{ asset('lib/assets/vendor/js/helpers.js') }} "></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('lib/assets/js/config.js') }} "></script>
    <!-- Scripts
    @vite(['resources/css/app.css', 'resources/js/app.js']) -->
</head>
<body class="font-sans antialiased">
<div class="authentication-wrapper authentication-cover authentication-bg">
    <div class="authentication-inner row">
        <!-- /Left Text -->
        <div class="d-none d-lg-flex col-lg-7 p-0">
            <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                <img
                        src="{{ asset('lib/assets/img/illustrations/auth-login-illustration-light.png') }} "
                        alt="auth-login-cover"
                        class="img-fluid my-5 auth-illustration"
                        data-app-light-img="illustrations/auth-login-illustration-light.png"
                        data-app-dark-img="illustrations/auth-login-illustration-dark.png"
                />

                <img
                        src="{{ asset('lib/assets/img/illustrations/bg-shape-image-light.png') }} "
                        alt="auth-login-cover"
                        class="platform-bg"
                        data-app-light-img="illustrations/bg-shape-image-light.png"
                        data-app-dark-img="illustrations/bg-shape-image-dark.png"
                />
            </div>
        </div>
        {{ $slot }}
    </div>

</div>
<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('lib/assets/vendor/libs/jquery/jquery.js') }} "></script>
<script src="{{ asset('lib/assets/vendor/libs/popper/popper.js') }} "></script>
<script src="{{ asset('lib/assets/vendor/js/bootstrap.js') }} "></script>
<script src="{{ asset('lib/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }} "></script>
<script src="{{ asset('lib/assets/vendor/libs/node-waves/node-waves.js') }} "></script>

<script src="{{ asset('lib/assets/vendor/libs/hammer/hammer.js') }} "></script>
<script src="{{ asset('lib/assets/vendor/libs/i18n/i18n.js') }} "></script>
<script src="{{ asset('lib/assets/vendor/libs/typeahead-js/typeahead.js') }} "></script>

<script src="{{ asset('lib/assets/vendor/js/menu.js') }} "></script>
<!-- endbuild -->

<script src="{{ asset('lib/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }} "></script>
<script src="{{ asset('lib/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }} "></script>
<script src="{{ asset('lib/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }} "></script>

<!-- Main JS -->
<script src="{{ asset('lib/assets/js/main.js') }} "></script>
<script src="{{ asset('lib/assets/js/pages-auth.js') }} "></script>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('ti-eye-off');
            toggleIcon.classList.add('ti-eye');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('ti-eye');
            toggleIcon.classList.add('ti-eye-off');
        }
    }
</script>
</body>
</html>
