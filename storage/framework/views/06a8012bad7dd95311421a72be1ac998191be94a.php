<h1 id="logo"><a href="<?php echo e(route('pages_index')); ?>">Jays Cleaners</a></h1>
<nav id="nav">
	<ul>
		<li><a href="<?php echo e(route('pages_index')); ?>">Home</a></li>
		<li class="submenu">
			<a href="#"><small>Hello </small><strong><?php echo e(Auth::user()->username); ?></strong></a>
			<ul>
				<li><a href="<?php echo e(route('delivery_index')); ?>">Your Deliveries</a></li>
				<li><a href="left-sidebar.html">Services</a></li>
				<li><a href="right-sidebar.html">Business Hours</a></li>
				<li><a href="contact.html">Contact Us</a></li>
				<li class="submenu">
					<a href="#"><?php echo e(Auth::user()->username); ?> menu</a>
					<ul>
						<li><a href="#">Update Contact Information</a></li>
						<li><a href="#">Consequat</a></li>
						<li><a href="#">Lorem Magna</a></li>
						<li><a href="#">Sed Magna</a></li>
						<li><a href="#">Ipsum Nisl</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a  href="<?php echo e(route('pages_logout')); ?>" class="button special">Logout</a>
		</li>
	</ul>
</nav>