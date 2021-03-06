<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
		<?php echo $__env->yieldContent('stylesheets'); ?>
	</head>
	<body class="index">
		<div id="page-wrapper">

			<!-- Header -->
			<?php echo $__env->yieldContent('navigation'); ?>


			<!-- Main -->
			<div id="main" style="margin-bottom:0px; padding-bottom:0px;">

				<div class="row-fluid ">
				<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				</div>
				<?php echo $__env->yieldContent('content'); ?>
				<section class="wrapper style3 container special">
					<header>
						<h2>Need assistance? You contact us by <strong>phone</strong> or <strong>email</strong></h2>
					</header>
					<ul>
						<li class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin-bottom:20px;">
							<ul>
								<li><strong><u>For website / technical assistance</u></strong></li>
								<li><i class="fa fa-envelope" aria-hidden="true"></i> Email Us: <strong>wondo@jayscleaners.com</strong></li>
								<li><i class="fa fa-mobile" aria-hidden="true"></i> Phone Us: <strong>(206) 328-8158</strong></li>
							</ul>
						</li>

						<li class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin-bottom:20px;">
							<ul>
								<li><strong><u>For delivery / general assistance</u></strong></li>
								<li><i class="fa fa-envelope" aria-hidden="true"></i> Email Us: <strong>young@jayscleaners.com</strong></li>
								<li><i class="fa fa-mobile" aria-hidden="true"></i> Phone Us: <strong>(206) 453-5930</strong></li>
							</ul>
						</li>
					</ul>

				</section>

			</div>
			<p></p>


			<!-- Footer -->
			<footer id="footer">

				<ul class="icons">
					<li><a target="__blank" href="https://www.yelp.com/biz/jays-dry-cleaners-roosevelt-seattle" class="icon circle fa-yelp" style="color:#ffffff; background-color:#DB6B67;"><span class="label">Yelp</span></a></li>
					<li><a target="__blank" href="https://plus.google.com/+JaysDryCleanersRooseveltSeattle" class="icon circle fa-google-plus"><span class="label">Google+</span></a></li>
					<li><a target="__blank" href="https://twitter.com/mrjayscleaners" class="icon circle fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a target="__blank" href="https://www.facebook.com/Jays-Cleaners-863927957082219/" class="icon circle fa-facebook"><span class="label">Facebook</span></a></li>	
				</ul>
				<ul class="icons">
					<li>
						<!-- (c) 2005, 2016. Authorize.Net is a registered trademark of CyberSource Corporation --> 
						<div class="AuthorizeNetSeal"> 
							<script type="text/javascript" language="javascript">var ANS_customer_id="ebe6e342-11f7-41de-b5f8-d1e8cdfa46c5";</script> 
							<script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> 
							<a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Online Payments</a> 
						</div>
					</li>
				</ul>
				<ul class="copyright">
					<li>&copy; <?php echo e(date('Y')); ?></li><li> Jays Cleaners</li>
					<li><a href="<?php echo e(route('pages_terms')); ?>">Terms & Privay Policy</a></li>
				</ul>

			</footer>
			
		</div>
		<?php echo $__env->yieldContent('modals'); ?>
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

		<?php echo $__env->yieldContent('scripts'); ?>

	</body>
</html>