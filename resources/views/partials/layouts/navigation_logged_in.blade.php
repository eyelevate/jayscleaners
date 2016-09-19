<h1 id="logo"><a href="{{ route('pages_index') }}">Jays Cleaners</a></h1>
<nav id="nav">
	<ul>
		<li><a href="{{ route('pages_index') }}">Home</a></li>
		<li class="submenu">
			<a href="#"><small>Hello </small><strong>{{ $auth->username }}</strong></a>
			<ul>
				<li><a href="{{ route('delivery_index') }}">Your Deliveries</a></li>
				<li><a href="left-sidebar.html">Services</a></li>
				<li><a href="right-sidebar.html">Business Hours</a></li>
				<li><a href="contact.html">Contact Us</a></li>
				<li class="submenu">
					<a href="#">{{ $auth->username }} menu</a>
					<ul>
						<li><a href="#">Dolore Sed</a></li>
						<li><a href="#">Consequat</a></li>
						<li><a href="#">Lorem Magna</a></li>
						<li><a href="#">Sed Magna</a></li>
						<li><a href="#">Ipsum Nisl</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a id="logout_button" href="#" class="button special">Logout</a>
			{!! Form::open(['action' => 'PagesController@postLogout', 'id'=>'logout_form', 'class'=>'form-horizontal','role'=>"form"]) !!}
			{!! Form::close() !!}
		</li>
	</ul>
</nav>