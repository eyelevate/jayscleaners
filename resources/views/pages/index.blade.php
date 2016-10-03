@extends($layout)

@section('stylesheets')
<link rel="stylesheet" href="/css/pages/frontend.css" />
@stop

@section('scripts')
<script type="text/javascript" src="/js/pages/index.js"></script>
@stop

@section('navigation')
	<header id="header" class="alt">
	@if($auth)
		{!! View::make('partials.layouts.navigation_logged_in')
			->render()
		!!}
	@else
		{!! View::make('partials.layouts.navigation_logged_out')
			->render()
		!!}
	@endif
	</header>
@stop

@section('banner')
	@if($auth)

	<header>
		<h2>Jays Cleaners</h2>
	</header>
	<p>Welcome back <strong>{{ Auth::user()->username }}</strong>
	<br />
	Start your delivery today!
	<br /><br/>							
	<ul class="buttons vertical">
		<li><a href="{{ route('delivery_start') }}" class="button fit">Schedule Delivery</a></li>
		@if (count($schedules) > 0)
		<li>
			<p><strong>OR</strong></p>
		</li>
		<li>
			{!! Form::open(['action' => 'PagesController@postOneTouch', 'class'=>'form-horizontal','role'=>"form"]) !!}
  			{!! csrf_field() !!}
			<ul class="buttons vertical">
				<li><input data-toggle="modal" data-target="#loading" type="submit" class="button fit" value="Repeat Last Delivery"/></li>
			</ul>  			
			{!! Form::close() !!}
		</li>
		@endif
	</ul>

	@else

	{!! Form::open(['action' => 'PagesController@postZipcodes', 'class'=>'form-horizontal','role'=>"form"]) !!}
	{!! csrf_field() !!}
	<header>
		<h2>Jays Cleaners</h2>
	</header>
	<p><strong>Free</strong> delivery and pickup!
	<br />
	Start your delivery today!
	<br /><br/>
	<header>
        {!! Form::text('zipcode', old('zipcode'), ['placeholder'=>'Enter your zipcode', 'style'=>'background-color:#ffffff; color:#000000;']) !!}
        @if ($errors->has('zipcode'))
            <span class="help-block">
                <strong style="color:#ffffff">{{ $errors->first('zipcode') }}</strong>
            </span>
        @endif
	</header>							
	<ul class="buttons vertical">
		<li><input type="submit" class="button fit" text="Start"/></li>
	</ul>
	{!! Form::close() !!}

	@endif

@stop

@section('content')
<header class="special container">
	<span class="icon fa fa-home fa-fw"></span>
	<h2>Welcome to Jays Cleaners. With over <strong>70 years</strong> of experience, <strong>let us work for you</strong>.</h2>
</header>

<!-- One -->
<section class="wrapper style2 container special-alt">
	<div class="row 50%">
		<div class="8u 12u(narrower)">

			<header>
				<h5><strong>Where to find us</strong></h5>
			</header>
			<section class="clearfix">
			<p>We proudly serve the Seattle region at 2 prime locations in the Montlake and Roosevelt neighborhoods. With the ability to deliver if these locations are not suitable to your current location.</p>
			@if (count($companies) > 0)
				@foreach($companies as $company)
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-bottom:30px;">
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<address>
							<strong>{{ $company->name }}</strong><br/>
							{{ $company->street }} <br/>
							{{ $company->city }}, {{ $company->state }} {{ $company->zipcode }} <br/>
							{{ $company->phone }}

						</address>
						
					</div>
					<a href="{{ $company->map }}" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 btn btn-warning btn-lg"><i class="fa fa-map-o"></i>&nbsp;Directions</a>
				</div>
				@endforeach
			@endif
			</section>
			<hr/>
			<header>
				<h5><strong>Store Hours</strong></h5>
			</header>
			<section class="clearfix">

				<div class="table-responsive">
					<table class="table table-condensed">	
						<thead>
							<tr style="color:#ffffff;">
								<th><strong>Day</strong></th>
								<th><strong>Hours</strong></th>
								<th><strong>Currently</strong></th>
							</tr>
						</thead>
						<tbody>
						@if (count($companies) > 0)
							@foreach($companies as $company)
								@if(count($company->store_hours) > 0 && $company->id == 1)
									@foreach($company->store_hours as $key => $value)
										@if (date('l') == $key)
										<tr class="warning" style="color:#5e5e5e; font-weight:bold;">
											<th><strong>{{ $key }}</strong></th>
											<td><strong>{{ $value }}</strong></td>
											<td><strong style="color:{{ $company['open_status'] ? 'green' : 'red' }};">{{ $company['open_status'] ? 'Open' : 'Closed' }}</strong></td>
										</tr>
										@else
										<tr style="color:#ffffff;">
											<th>{{ $key }}</th>
											<td>{{ $value }}</td>
											<td></td>
										</tr>
										@endif
									
									@endforeach
								@endif
			
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
			</section>
			<footer>

			</footer>

		</div>
		<div class="4u 12u(narrower) important(narrower)">

			<ul class="featured-icons">
				<li><span class="icon fa-clock-o"><span class="label">Feature 1</span></span></li>
				<li><span class="icon fa fa-car"><span class="label">Feature 2</span></span></li>
				<li><span class="icon fa-laptop"><span class="label">Feature 3</span></span></li>
				<li><span class="icon fa-inbox"><span class="label">Feature 4</span></span></li>
				<li><span class="icon fa-lock"><span class="label">Feature 5</span></span></li>
				<li><span class="icon fa-calendar-check-o"><span class="label">Feature 6</span></span></li>
			</ul>

		</div>
	</div>
</section>

<!-- Two -->
<section class="wrapper style1 container special">
	<div class="row">
		<div class="4u 12u(narrower)">

			<section>
				<span class="icon featured fa-history"></span>
				<header>
					<h3>About Us</h3>
				</header>
				<p>Jays Cleaners was established in the Greenlake neighborhood of Seattle over 70 years ago. Family run and operated, we at Jays Cleaners have always held the belief that the Customer expects and deserves the best and it is our duty to deliver the best.</p>
				<p>We at Jays Cleaners are relentlessly setting and maintaining high standards for quality, continuously implementing industry best practices and always paying careful attention to the many details of what makes a quality finished product (ie. stain removal, replacing cracked or chipped buttons, sewing loose hems, scrubbing collars, etc). We are always striving to deliver the best quality services, on-time, every time. Our goal is simple: 100% Customer Satisfaction!</p>
				<a href="https://www.yelp.com/biz/jays-dry-cleaners-roosevelt-seattle">Read Our Reviews</a>
			</section>

		</div>
		<div class="4u 12u(narrower)">

			<section>
				<span class="icon featured fa-check-square-o"></span>
				<header>
					<h3>THE HEALTHY CLEANING ALTERNATIVE</h3>
				</header>
				<p>Jays Cleaners utilizes the SystemK4 cleaning system which is a Toxin-Free, Environmentally Safe and Healthy cleaning system. Unlike the cleaning methods of the past which relied heavily on Perchloroethylene (Perc), SystemK4 utilizes a perc-free/halogen-free, organic solvent and has been tested to be dermatologically safe, biodegradable and provides an excellent, odorless finish to every garment. </p>
				<a href="http://www.systemk4.com/en/" target="__blank">Learn More</a>
			</section>

		</div>
		<div class="4u 12u(narrower)">

			<section>
				<span class="icon featured fa-truck"></span>
				<header>
					<h3>Delivery Specialists</h3>
				</header>
				<p>Create an Account using our <a href="{{ route('pages_registration') }}">Sign Up</a> page and set up a delivery schedule today. Returning Members can simply <a href="{{ route('pages_login') }}">Login</a> to schedule a delivery.</p>  
				<p>Special Instructions for any article or garment and delivery location (concierge, front porch, etc) can be included on each delivery schedule. Once your finished, we will send you an email confirmation.</p>
				<a href="{{ route('delivery_pickup') }}">Schedule A Delivery</a>
			</section>

		</div>

	</div>
</section>

<!-- Three -->
<section class="wrapper style3 container special">

	<header class="major">
		<h2>Next look at this <strong>cool stuff</strong></h2>
	</header>

	<div class="row">
		<div class="6u 12u(narrower)">

			<section>
				<a href="#" class="image featured"><img src="images/green_nature.png" alt="" /></a>
				<header>
					<h3>A Really Fast Train</h3>
				</header>
				<p>Sed tristique purus vitae volutpat commodo suscipit amet sed nibh. Proin a ullamcorper sed blandit. Sed tristique purus vitae volutpat commodo suscipit ullamcorper sed blandit lorem ipsum dolore.</p>
			</section>

		</div>
		<div class="6u 12u(narrower)">

			<section>
				<a href="#" class="image featured"><img src="images/pic02.jpg" alt="" /></a>
				<header>
					<h3>An Airport Terminal</h3>
				</header>
				<p>Sed tristique purus vitae volutpat commodo suscipit amet sed nibh. Proin a ullamcorper sed blandit. Sed tristique purus vitae volutpat commodo suscipit ullamcorper sed blandit lorem ipsum dolore.</p>
			</section>

		</div>
	</div>
	<div class="row">
		<div class="6u 12u(narrower)">

			<section>
				<a href="#" class="image featured"><img src="images/pic03.jpg" alt="" /></a>
				<header>
					<h3>Hyperspace Travel</h3>
				</header>
				<p>Sed tristique purus vitae volutpat commodo suscipit amet sed nibh. Proin a ullamcorper sed blandit. Sed tristique purus vitae volutpat commodo suscipit ullamcorper sed blandit lorem ipsum dolore.</p>
			</section>

		</div>
		<div class="6u 12u(narrower)">

			<section>
				<a href="#" class="image featured"><img src="images/pic04.jpg" alt="" /></a>
				<header>
					<h3>And Another Train</h3>
				</header>
				<p>Sed tristique purus vitae volutpat commodo suscipit amet sed nibh. Proin a ullamcorper sed blandit. Sed tristique purus vitae volutpat commodo suscipit ullamcorper sed blandit lorem ipsum dolore.</p>
			</section>

		</div>
	</div>

	<footer class="major">
		<ul class="buttons">
			<li><a href="#" class="button">See More</a></li>
		</ul>
	</footer>

</section>
@stop

@section('modals')
	{!! View::make('partials.frontend.modals')->render() !!}
@stop