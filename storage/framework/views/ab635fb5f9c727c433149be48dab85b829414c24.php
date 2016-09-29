<!DOCTYPE HTML>
<html>
	<head>
		<title>Jays Cleaners - Free Pickup And Delivery</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!-- Bootstrap 3.3.5 -->
	    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css">
	    <!-- Font Awesome -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<!--[if lte IE 8]><script src="/packages/html5up-twenty/assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="/packages/html5up-twenty/assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="/packages/html5up-twenty/assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="/packages/html5up-twenty/assets/css/ie9.css" /><![endif]-->
	
		<?php echo $__env->yieldContent('stylesheets'); ?>
	</head>
	<body class="index">
		<div id="page-wrapper">

			<!-- Header -->
				<?php echo $__env->yieldContent('navigation'); ?>

			<!-- Banner -->
				<section id="banner">

					<!--
						".inner" is set up as an inline-block so it automatically expands
						in both directions to fit whatever's inside it. This means it won't
						automatically wrap lines, so be sure to use line breaks where
						appropriate (<br />).
					-->
					<div class="inner">
						<?php echo $__env->yieldContent('banner'); ?>
					</div>

				</section>
				<section>
				<?php echo $__env->yieldContent('modals'); ?>
				</section>
			<!-- Main -->
				<article id="main">

					<?php echo $__env->yieldContent('content'); ?>

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
			<script src="/packages/html5up-twenty/assets/js/jquery.min.js"></script>
			<!-- Bootstrap 3.3.5 -->
	    	<script src="/packages/AdminLTE-2.3.0/bootstrap/js/bootstrap.min.js"></script>
			<script src="/packages/html5up-twenty/assets/js/jquery.dropotron.min.js"></script>
			<script src="/packages/html5up-twenty/assets/js/skel.min.js"></script>
			<script src="/packages/html5up-twenty/assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="/packages/html5up-twenty/assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="/packages/html5up-twenty/assets/js/jquery.scrolly.min.js"></script>
			<script src="/packages/html5up-twenty/assets/js/jquery.scrollgress.min.js"></script>
			<script src="/packages/html5up-twenty/assets/js/main.js"></script>

			<?php echo $__env->yieldContent('scripts'); ?>

	</body>
</html>