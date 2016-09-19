<h1 id="logo"><a href="{{ route('pages_index') }}">Jays Cleaners</a></h1>
<nav id="nav">
	<ul>
		<li><a href="{{ route('pages_index') }}">Home</a></li>
		<li class="current"><a href="{{ route('pages_login') }}">Login</a></li>
		<li class="submenu">
			<a href="#">About Us</a>
			<ul>
				<li><a href="{{ route('delivery_pickup') }}">Schedule Delivery</a></li>
				<li><a href="left-sidebar.html">Services</a></li>
				<li><a href="right-sidebar.html">Business Hours</a></li>
				<li><a href="contact.html">Contact Us</a></li>
			</ul>
		</li>
		<li><a href="{{ route('pages_registration') }}" class="button special">Sign Up</a></li>
	</ul>
</nav>