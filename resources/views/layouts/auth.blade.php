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
	<link href='{{ asset('assets/admin/assets/css/vendor/datatables.bootstrap5.min.css')}}' rel='stylesheet'>
	<link href='{{ asset('assets/admin/assets/css/vendor/responsive.datatables.min.css')}}' rel='stylesheet'>
	<link href='{{ asset('assets/admin/assets/css/vendor/daterangepicker.css')}}' rel='stylesheet'>
	<link href="{{ asset('assets/admin/assets/css/vendor/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{ asset('assets/admin/assets/css/vendor/apexcharts.css')}}" rel="stylesheet">
	<link href="{{ asset('assets/admin/assets/css/vendor/simplebar.css')}}" rel="stylesheet">
	<link href="{{ asset('assets/admin/assets/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</style>
	<!-- Main CSS -->

	<link id="mainCss"  href="{{ asset('assets/admin/assets/css/style.css')}}" rel="stylesheet">
</head>

<body data-lh-mode="light">
	<main class="wrapper sb-default">
		<section class="auth-section anim">
			<div class="lh-login-page">
				<div class="container-fluid">
					<div class="row">
						<div class="offset-lg-6 col-lg-6">
							<div class="content-detail">
								<div class="main-info">
									<div class="hero-container">
										<!-- Signup form -->

										@yield('content')
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

<!-- Vendor Custom -->
<script src="{{ asset('assets/admin/assets/js/vendor/jquery-3.6.4.min.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/vendor/simplebar.min.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/vendor/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/vendor/apexcharts.min.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/vendor/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/vendor/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- Data Tables -->
<script src="{{ asset('assets/admin/assets/js/vendor/jquery.datatables.min.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/vendor/datatables.bootstrap5.min.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/vendor/datatables.responsive.min.js')}}"></script>
<!-- Caleddar -->
<script src="{{ asset('assets/admin/assets/js/vendor/jquery.simple-calendar.js')}}"></script>
<!-- Date Range Picker -->
<script src="{{ asset('assets/admin/assets/js/vendor/moment.min.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/vendor/daterangepicker.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/vendor/date-range.js')}}"></script>

<!-- Main Custom -->
<script src="{{ asset('assets/admin/assets/js/main.js')}}"></script>
<script src="{{ asset('assets/admin/assets/js/data/dashboard-chart-data.js')}}"></script>


</body>


<!-- Mirrored from maraviyainfotech.com/projects/luxurious-html-v22/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Dec 2024 10:34:20 GMT -->
</html>
