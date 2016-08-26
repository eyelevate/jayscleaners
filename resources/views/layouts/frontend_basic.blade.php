<!DOCTYPE HTML>
<html>
	<head>
		<title>Jays Cleaners - Free Delivery & Pickup</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!-- Bootstrap 3.3.5 -->
	    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css">
	    <!-- Font Awesome -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	    <!-- Ionicons -->
	    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<!--[if lte IE 8]><script src="/packages/html5up-twenty/assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="/packages/html5up-twenty/assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="/packages/html5up-twenty/assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="/packages/html5up-twenty/assets/css/ie9.css" /><![endif]-->
		<link rel="stylesheet" href="/css/pages/frontend.css" />
		@yield('stylesheets')
	</head>
	<body class="index">
		<div id="page-wrapper">

			<!-- Header -->
			@yield('navigation')


			<!-- Main -->
			<article id="main">
				<div class="container">
					<div class="row-fluid ">
					@include('flash::message')
					</div>
					@yield('content')
				</div>
			</article>

			<!-- CTA -->
			<section id="cta">

				<header>
					<h2>Ready to do <strong>something</strong>?</h2>
					<p>Proin a ullamcorper elit, et sagittis turpis integer ut fermentum.</p>
				</header>
				<footer>
					<ul class="buttons">
						<li><a href="#" class="button special">Take My Money</a></li>
						<li><a href="#" class="button">LOL Wut</a></li>
					</ul>
				</footer>

			</section>

			<!-- Footer -->
			<footer id="footer">

				<ul class="icons">
					<li><a href="#" class="icon circle fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="#" class="icon circle fa-facebook"><span class="label">Facebook</span></a></li>
					<li><a href="#" class="icon circle fa-google-plus"><span class="label">Google+</span></a></li>
					<li><a href="#" class="icon circle fa-github"><span class="label">Github</span></a></li>
					<li><a href="#" class="icon circle fa-dribbble"><span class="label">Dribbble</span></a></li>
				</ul>

				<ul class="copyright">
					<li>&copy; Untitled</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
				</ul>

			</footer>

		</div>

		<!-- Scripts -->
		<!-- jQuery 2.1.4 -->
    	<script src="/packages/AdminLTE-2.3.0/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	    <!-- jQuery UI 1.11.4 -->
	    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	    <!-- Bootstrap 3.3.5 -->
	    <script src="/packages/AdminLTE-2.3.0/bootstrap/js/bootstrap.min.js"></script>
	    <!-- Morris.js charts -->
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	    <script src="/packages/AdminLTE-2.3.0/plugins/morris/morris.min.js"></script>
	    <!-- Sparkline -->
	    <script src="/packages/AdminLTE-2.3.0/plugins/sparkline/jquery.sparkline.min.js"></script>
	    <!-- jvectormap -->
	    <script src="/packages/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	    <script src="/packages/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	    <!-- jQuery Knob Chart -->
	    <script src="/packages/AdminLTE-2.3.0/plugins/knob/jquery.knob.js"></script>
	    <!-- daterangepicker -->
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
	    <script src="/packages/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.js"></script>
	    <!-- datepicker -->
	    <script src="/packages/AdminLTE-2.3.0/plugins/datepicker/bootstrap-datepicker.js"></script>
	    <!-- Bootstrap WYSIHTML5 -->
	    <script src="/packages/AdminLTE-2.3.0/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	    <!-- Slimscroll -->
	    <script src="/packages/AdminLTE-2.3.0/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	    <!-- FastClick -->
	    <script src="/packages/AdminLTE-2.3.0/plugins/fastclick/fastclick.min.js"></script>
		<script src="/packages/html5up-twenty/assets/js/jquery.min.js"></script>
		<script src="/packages/html5up-twenty/assets/js/jquery.dropotron.min.js"></script>
		<script src="/packages/html5up-twenty/assets/js/jquery.scrolly.min.js"></script>
		<script src="/packages/html5up-twenty/assets/js/jquery.scrollgress.min.js"></script>
		<script src="/packages/html5up-twenty/assets/js/skel.min.js"></script>
		<script src="/packages/html5up-twenty/assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="/packages/html5up-twenty/assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="/packages/html5up-twenty/assets/js/main.js"></script>
		<script type="text/javascript" src="/js/pages/index.js"></script>
		@yield('scripts')

	</body>
</html>