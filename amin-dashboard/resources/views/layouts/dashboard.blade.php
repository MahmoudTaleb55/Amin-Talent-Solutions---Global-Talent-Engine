<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet">
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    @stack('styles')
</head>
<body class="g-sidenav-show bg-gray-100">
    @yield('sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @yield('navbar')
        <div class="container-fluid py-4">
            @yield('content')
            @yield('footer')
        </div>
    </main>
    <!-- Core JS Files -->
    <script src="https://demos.creative-tim.com/soft-ui-dashboard/assets/js/core/popper.min.js"></script>
    <script src="https://demos.creative-tim.com/soft-ui-dashboard/assets/js/core/bootstrap.min.js"></script>
    <script src="https://demos.creative-tim.com/soft-ui-dashboard/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="https://demos.creative-tim.com/soft-ui-dashboard/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="https://demos.creative-tim.com/soft-ui-dashboard/assets/js/plugins/chartjs.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="https://demos.creative-tim.com/soft-ui-dashboard/assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
    @stack('scripts')
</body>
</html>
