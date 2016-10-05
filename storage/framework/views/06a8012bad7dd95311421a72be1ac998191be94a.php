<h1 id="logo"><a href="<?php echo e(route('pages_index')); ?>">Jays Cleaners</a></h1>
<nav id="nav">
	<ul>
		<li><a href="<?php echo e(route('pages_index')); ?>">Home</a></li>
		<li class="submenu">
			<a href="#"><small>Hello </small><strong><?php echo e(Auth::user()->username); ?></strong></a>
			<ul>
				<li><a href="<?php echo e(route('delivery_index')); ?>">Your Deliveries</a></li>
				<li><a href="<?php echo e(route('pages_services')); ?>">Services</a></li>
				<li><a href="<?php echo e(route('pages_business_hours')); ?>">Business Hours</a></li>
				<li><a href="<?php echo e(route('pages_contact_us')); ?>">Contact Us</a></li>
				<li class="submenu">
					<a href="#"><?php echo e(Auth::user()->username); ?> menu</a>
					<ul>
						<li><a href="<?php echo e(route('pages_update_contact')); ?>">Update User</a></li>
						<li><a href="<?php echo e(route('address_index')); ?>">Manage address(es)</a></li>
						<li><a href="<?php echo e(route('cards_index')); ?>">Manage card(s)</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a href="<?php echo e(route('pages_logout')); ?>" class="button special">Logout</a>
		</li>
	</ul>
</nav>