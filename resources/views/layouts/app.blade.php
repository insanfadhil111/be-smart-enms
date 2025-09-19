<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/img/apple-icon.png') }}">
    <link rel="icon" type="image" href="{{ asset('/img/iotlab.jpg') }}">
    <title>
        EnMS Gedung 3
    </title>
    @yield('tambahanHead')
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link rel="stylesheet" id="pagestyle" href="{{asset('assets/css/nucleo-icons.css')}}">
    <link rel="stylesheet" id="pagestyle" href="{{asset('assets/css/nucleo-svg.css')}}">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/aaa1eaf0f7.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    {{--
    <link id="pagestyle" href="./assets/css/argon-dashboard.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" id="pagestyle" href="{{asset('assets/css/argon-dashboard.css')}}">
</head>

<body class="{{ $class ?? '' }}">
    @guest
    @yield('content')
    @endguest

    @auth
    @if (in_array(request()->route()->getName(), ['sign-in-static', 'sign-up-static', 'login', 'register',
    'recover-password', 'rtl', 'virtual-reality']))
    @yield('content')
    @else
    @if (!in_array(request()->route()->getName(), ['profile', 'profile-static']))
    <div class="min-height-200 bg-primary position-absolute w-100"></div>
    @elseif (in_array(request()->route()->getName(), ['profile-static', 'profile']))
    <div class="position-absolute w-100 min-height-300 top-0"
        style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
        <span class="mask bg-primary opacity-6"></span>
    </div>
    @endif
    @include('layouts.navbars.auth.sidenav')
    <main class="main-content border-radius-lg">
        @yield('content')
    </main>
    @include('components.fixed-plugin')
    @endif
    @endauth

    <!--   Core JS Files   -->
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const darkToggle = document.getElementById('dark-version');
            const sidenav = document.querySelector('.sidenav');
            const sidenavButtons = document.querySelectorAll('[onclick="sidebarType(this)"]');

            // === Restore Sidebar Color ===
            const savedSidebarColor = localStorage.getItem('sidebar-color');
            if (savedSidebarColor && sidenav) {
                sidenav.setAttribute('data-color', savedSidebarColor);

                // Update badge color active state
                document.querySelectorAll('.badge.filter').forEach(badge => {
                    badge.classList.toggle('active', badge.getAttribute('data-color') === savedSidebarColor);
                });
            }

            // === Sidebar Color Change Handler ===
            window.sidebarColor = function (element) {
                const newColor = element.getAttribute('data-color');
                if (!sidenav) return;

                sidenav.setAttribute('data-color', newColor);
                localStorage.setItem('sidebar-color', newColor);

                // Update badge color active state
                document.querySelectorAll('.badge.filter').forEach(badge => {
                    badge.classList.remove('active');
                });
                element.classList.add('active');
            };

            // === Restore Dark Mode ===
            const isDarkMode = localStorage.getItem('dark-mode') === 'enabled';
            if (isDarkMode) {
                document.body.classList.add('dark-version');
                if (darkToggle) darkToggle.checked = true;
            }

            // === Restore Sidenav Type ===
            const savedSidenavClass = localStorage.getItem('sidenav-class');
            if (savedSidenavClass && sidenav) {
                sidenav.classList.remove('bg-white', 'bg-default');
                sidenav.classList.add(savedSidenavClass);

                // Update button state
                sidenavButtons.forEach(btn => {
                    btn.classList.toggle('active', btn.getAttribute('data-class') === savedSidenavClass);
                });
            }

            // === Dark Mode Toggle Handler ===
            if (darkToggle) {
                darkToggle.addEventListener('change', function () {
                    if (this.checked) {
                        document.body.classList.add('dark-version');
                        localStorage.setItem('dark-mode', 'enabled');
                    } else {
                        document.body.classList.remove('dark-version');
                        localStorage.setItem('dark-mode', 'disabled');
                    }
                });
            }

            // === Sidenav Type Change Handler ===
            window.sidebarType = function (element) {
                const newClass = element.getAttribute('data-class');
                if (!sidenav) return;

                sidenav.classList.remove('bg-white', 'bg-default');
                sidenav.classList.add(newClass);
                localStorage.setItem('sidenav-class', newClass);

                // Update button active states
                sidenavButtons.forEach(btn => btn.classList.remove('active'));
                element.classList.add('active');
            };
        });
    </script>
    <script src="assets/js/argon-dashboard.js"></script>
    @stack('js');
</body>

</html>