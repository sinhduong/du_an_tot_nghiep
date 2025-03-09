<!--
    Item Name: Luxurious - Hotel Booking HTML Template + Admin Dashboard.
    Author: ashishmaraviya
    Version: 2.2.0
    Copyright 2024
 Author URI: https://themeforest.net/user/ashishmaraviya
-->
<!DOCTYPE html>
<html lang="en" dir="ltr">


<!-- Mirrored from maraviyainfotech.com/projects/luxurious-html-v22/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Dec 2024 10:33:57 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Best Luxurious Hotel Booking Template.">
    <meta name="keywords"
        content="hotel, booking, business, restaurant, spa, resort, landing, agency, corporate, start up, site design, new business site, business template, professional template, classic, modern">
    <meta name="author" content="ashishmaraviya">

    <title>Luxurious - Hotel Booking HTML Template + Admin Dashboard</title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/assets/img/favicon/favicon.ico') }}">

    <!-- Icon CSS -->
    <link href="{{ asset('assets/admin/assets/css/vendor/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/assets/css/vendor/remixicon.css') }}" rel="stylesheet">

    <!-- Vendor -->
    <link href='{{ asset('assets/admin/assets/css/vendor/datatables.bootstrap5.min.css') }}' rel='stylesheet'>
    <link href='{{ asset('assets/admin/assets/css/vendor/responsive.datatables.min.css') }}' rel='stylesheet'>
    <link href='{{ asset('assets/admin/assets/css/vendor/daterangepicker.css') }}' rel='stylesheet'>
    <link href="{{ asset('assets/admin/assets/css/vendor/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/assets/css/vendor/apexcharts.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/assets/css/vendor/simplebar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/assets/css/vendor/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

    </style>
    <!-- Main CSS -->

    <link id="mainCss" href="{{ asset('assets/admin/assets/css/style.css') }}" rel="stylesheet">
</head>

<body data-lh-mode="light">
    <main class="wrapper sb-default">
        <!-- Loader -->
        {{-- <div class="lh-loader">
			<span class="loader"></span>
		</div> --}}

        <!-- Header -->
        @include('admins.blocks.header')

        <!-- sidebar -->
        @include('admins.blocks.sidebar')


        <!-- Notify sidebar -->
        @include('admins.blocks.notify-sidebar')

        <!-- main content -->
        @yield('content')



        <!-- Footer -->
        @include('admins.blocks.footer')

        <!-- Feature tools -->
        <div class="lh-tools-sidebar-overlay"></div>
        <div class="lh-tools-sidebar">
            <a href="javascript:void(0)" class="lh-tools-sidebar-toggle in-out">
                <i class="ri-settings-4-line"></i>
            </a>
            <div class="lh-bar-title">
                <h6>Tools</h6>
                <a href="javascript:void(0)" class="close-tools"><i class="ri-close-line"></i></a>
            </div>
            <div class="lh-tools-detail">
                <div class="lh-tools-block">
                    <h3>Modes</h3>
                    <div class="lh-tools-info">
                        <div class="lh-tools-item mode active" data-lh-mode-tool="light">
                            <img src="{{ asset('assets/admin/assets/img/tools/light.png') }}" alt="light">
                            <p>light</p>
                        </div>
                        <div class="lh-tools-item mode" data-lh-mode-tool="dark">
                            <img src="{{ asset('assets/admin/assets/img/tools/dark.png') }}" alt="dark">
                            <p>dark</p>
                        </div>
                    </div>
                </div>
                <div class="lh-tools-block">
                    <h3>Sidebar</h3>
                    <div class="lh-tools-info">
                        <div class="lh-tools-item sidebar active" data-sidebar-mode-tool="light">
                            <img src="{{ asset('assets/admin/assets/img/tools/side-light.png') }}" alt="light">
                            <p>light</p>
                        </div>
                        <div class="lh-tools-item sidebar" data-sidebar-mode-tool="dark">
                            <img src="{{ asset('assets/admin/assets/img/tools/side-dark.png') }}" alt="dark">
                            <p>dark</p>
                        </div>
                        <div class="lh-tools-item sidebar" data-sidebar-mode-tool="bg-1">
                            <img src="{{ asset('assets/admin/assets/img/tools/side-bg-1.png') }}" alt="background">
                            <p>Bg-1</p>
                        </div>
                        <div class="lh-tools-item sidebar" data-sidebar-mode-tool="bg-2">
                            <img src="{{ asset('assets/admin/assets/img/tools/side-bg-2.png') }}" alt="background">
                            <p>Bg-2</p>
                        </div>
                        <div class="lh-tools-item sidebar" data-sidebar-mode-tool="bg-3">
                            <img src="{{ asset('assets/admin/assets/img/tools/side-bg-3.png') }}" alt="background">
                            <p>Bg-3</p>
                        </div>
                        <div class="lh-tools-item sidebar" data-sidebar-mode-tool="bg-4">
                            <img src="{{ asset('assets/admin/assets/img/tools/side-bg-4.png') }}" alt="background">
                            <p>Bg-4</p>
                        </div>
                    </div>
                </div>
                <div class="lh-tools-block">
                    <h3>Header</h3>
                    <div class="lh-tools-info">
                        <div class="lh-tools-item header active" data-header-mode="light">
                            <img src="{{ asset('assets/admin/assets/img/tools/header-light.png') }}" alt="light">
                            <p>light</p>
                        </div>
                        <div class="lh-tools-item header" data-header-mode="dark">
                            <img src="{{ asset('assets/admin/assets/img/tools/header-dark.png') }}" alt="dark">
                            <p>dark</p>
                        </div>
                    </div>
                </div>
                <div class="lh-tools-block">
                    <h3>Backgrounds</h3>
                    <div class="lh-tools-info">
                        <div class="lh-tools-item bg active" data-bg-mode="default">
                            <img src="{{ asset('assets/admin/assets/img/tools/bg-0.png') }}" alt="default">
                            <p>Default</p>
                        </div>
                        <div class="lh-tools-item bg" data-bg-mode="bg-1">
                            <img src="{{ asset('assets/admin/assets/img/tools/bg-1.png') }}" alt="bg-1">
                            <p>Bg-1</p>
                        </div>
                        <div class="lh-tools-item bg" data-bg-mode="bg-2">
                            <img src="{{ asset('assets/admin/assets/img/tools/bg-2.png') }}" alt="bg-2">
                            <p>Bg-2</p>
                        </div>
                        <div class="lh-tools-item bg" data-bg-mode="bg-3">
                            <img src="{{ asset('assets/admin/assets/img/tools/bg-3.png') }}" alt="bg-3">
                            <p>Bg-3</p>
                        </div>
                        <div class="lh-tools-item bg" data-bg-mode="bg-4">
                            <img src="{{ asset('assets/admin/assets/img/tools/bg-4.png') }}" alt="bg-4">
                            <p>Bg-4</p>
                        </div>
                        <div class="lh-tools-item bg" data-bg-mode="bg-5">
                            <img src="{{ asset('assets/admin/assets/img/tools/bg-5.png') }}" alt="bg-5">
                            <p>Bg-5</p>
                        </div>
                    </div>
                </div>
                <div class="lh-tools-block">
                    <h3>Box Design</h3>
                    <div class="lh-tools-info">
                        <div class="lh-tools-item box active" data-box-mode-tool="default">
                            <img src="{{ asset('assets/admin/assets/img/tools/box-0.png') }}" alt="default">
                            <p>Default</p>
                        </div>
                        <div class="lh-tools-item box" data-box-mode-tool="box-1">
                            <img src="{{ asset('assets/admin/assets/img/tools/box-1.png') }}" alt="box-1">
                            <p>Box-1</p>
                        </div>
                        <div class="lh-tools-item box" data-box-mode-tool="box-2">
                            <img src="{{ asset('assets/admin/assets/img/tools/box-2.png') }}" alt="box-2">
                            <p>Box-2</p>
                        </div>
                        <div class="lh-tools-item box" data-box-mode-tool="box-3">
                            <img src="{{ asset('assets/admin/assets/img/tools/box-3.png') }}" alt="box-3">
                            <p>Box-3</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Vendor Custom -->
    <script src="{{ asset('assets/admin/assets/js/vendor/jquery-3.6.4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/vendor/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/vendor/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/vendor/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- Data Tables -->
    <script src="{{ asset('assets/admin/assets/js/vendor/jquery.datatables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/vendor/datatables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/vendor/datatables.responsive.min.js') }}"></script>
    <!-- Caleddar -->
    <script src="{{ asset('assets/admin/assets/js/vendor/jquery.simple-calendar.js') }}"></script>
    <!-- Date Range Picker -->
    <script src="{{ asset('assets/admin/assets/js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/vendor/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/vendor/date-range.js') }}"></script>

    <!-- Main Custom -->
    <script src="{{ asset('assets/admin/assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/data/dashboard-chart-data.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/myjs.js') }}"></script>

    <!-- jQuery -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    @stack('script')
    <script>
        CKEDITOR.replace('editor');
    </script>

</body>


<!-- Mirrored from maraviyainfotech.com/projects/luxurious-html-v22/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Dec 2024 10:34:20 GMT -->

</html>
