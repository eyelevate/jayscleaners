@extends($layout)

@section('stylesheets')
<link rel="stylesheet" href="/css/pages/frontend.css" />
@stop

@section('scripts')
<script type="text/javascript" src="/packages/Readmore/readmore.min.js"></script>
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
	<span class="icon fa-home fa-fw" style="font-size:25px;"></span>
	<h2>Welcome to Jays Cleaners. With over <strong>70 years</strong> of experience, <strong>let us work for you</strong>.</h2>
</header>
<section class="parallax-window" data-parallax="scroll" data-image-src="/imgs/website/display-4.png" style="min-height:400px;" data-androidFix="false" data-iosFix="false"></section>
<!-- Two -->
<br/>
<section class="wrapper style1 container special">
	<div class="row">
		<div class="4u 12u(narrower)">

			<section class="read_articles">
				
				<header>
					<span class="icon featured fa-history"></span>
					<h3>About Us</h3>
				</header>
				<p>
					Jays Cleaners was established in the Greenlake neighborhood of Seattle over 70 years ago. 
					Family run and operated, we at Jays Cleaners have always held the belief that the Customer expects and deserves the best and it is our duty to deliver the best.
				</p>
				<p>
					We at Jays Cleaners are relentlessly setting and maintaining high standards for quality, continuously implementing industry best practices and always paying careful attention to the many details of what makes a quality finished product (ie. stain removal, replacing cracked or chipped buttons, sewing loose hems, scrubbing collars, etc). We are always striving to deliver the best quality services, on-time, every time. 
					Our goal is simple: 100% Customer Satisfaction!
				</p>
				<a href="https://www.yelp.com/biz/jays-dry-cleaners-roosevelt-seattle" class="btn btn-lg btn-info">Read Our Reviews</a>
			</section>

		</div>
		<div class="4u 12u(narrower)">

			<section class="read_articles">
				
				<header>
					<span class="icon featured fa-check-square-o"></span>
					<h3>THE HEALTHY CLEANING ALTERNATIVE</h3>
				</header>
				<p>Jays Cleaners utilizes the SystemK4 cleaning system which is a Toxin-Free, Environmentally Safe and Healthy cleaning system. Unlike the cleaning methods of the past which relied heavily on Perchloroethylene (Perc), SystemK4 utilizes a perc-free/halogen-free, organic solvent and has been tested to be dermatologically safe, biodegradable and provides an excellent, odorless finish to every garment. </p>
				<a href="http://www.systemk4.com/en/" target="__blank" class="btn btn-lg btn-info">Learn More</a>
			</section>

		</div>
		<div class="4u 12u(narrower)">

			<section class="read_articles">
				
				<header>
					<span class="icon featured fa-truck"></span>
					<h3>Delivery Specialists</h3>
				</header>
				<p>Create an Account using our <a href="{{ route('pages_registration') }}">Sign Up</a> page and set up a delivery schedule today. Returning Members can simply <a href="{{ route('pages_login') }}">Login</a> to schedule a delivery.</p>  
				<p>Special Instructions for any article or garment and delivery location (concierge, front porch, etc) can be included on each delivery schedule. Once your finished, we will send you an email confirmation.</p>
				<a href="{{ route('delivery_pickup') }}" class="btn btn-lg btn-info">Schedule A Delivery</a>
			</section>

		</div>

	</div>
</section>
<section class="parallax-window" data-parallax="scroll" data-image-src="/imgs/website/display-3.png" style="min-height:400px;" data-androidFix="false" data-iosFix="false"></section>

<!-- One -->
<section class="wrapper style3 container special">

	<div class="row">
		<header class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
			<span class="icon featured fa-map-o"></span>
			<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Where to find us</h3>
		</header>
		<section class="clearfix">
		<p>We proudly serve the Seattle region at 2 prime locations in the Montlake and Roosevelt neighborhoods. With the ability to deliver if these locations are not suitable to your current location.</p>
		@if (count($companies) > 0)
			@foreach($companies as $company)
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-bottom:30px;">
				<div style="margin-bottom:10px;">
					<address>
						<strong class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{{ $company->name }}</strong>
						<span class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{{ $company->street }}</span>
						<span class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{{ $company->city }}, {{ $company->state }} {{ $company->zipcode }}</span>
						<span class="col-xs-12 col-sm-12 col-md-12 col-lg-12">{{ $company->phone }}</span>

					</address>
					<a href="{{ $company->map }}" class="btn btn-warning btn-lg"><i class="fa fa-map-marker"></i>&nbsp;Directions</a>
				</div>

				
			</div>
			@endforeach
		@endif
		</section>
	</div>
	<div id="store_hours" class="row">
		<header class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
			<span class="icon featured fa-clock-o"></span>
			<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Store Hours</h3>
		</header>
		<section class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="table-responsive">
				<table class="table table-condensed">	
					<thead>
						<tr>
							<th style="text-align:right;"><strong>Day</strong></th>
							<th style="text-align:center;"><strong>Hours</strong></th>
							<th style="text-align:left;"><strong>Currently</strong></th>
						</tr>
					</thead>
					<tbody>
					@if (count($companies) > 0)
						@foreach($companies as $company)
							@if(count($company->store_hours) > 0 && $company->id == 1)
								@foreach($company->store_hours as $key => $value)
									@if (date('l') == $key)
									<tr class="warning" style="color:#5e5e5e; font-weight:bold;">
										<th style="text-align:right;"><strong>{{ $key }}</strong></th>
										<td style="text-align:center;"><strong>{{ $value }}</strong></td>
										<td style="text-align:left;"><strong style="color:{{ $company['open_status'] ? 'green' : 'red' }};">{{ $company['open_status'] ? 'Open' : 'Closed' }}</strong></td>
									</tr>
									@else
									<tr>
										<th style="text-align:right;">{{ $key }}</th>
										<td style="text-align:center;">{{ $value }}</td>
										<td style="text-align:left;"></td>
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


	</div>


</section>
<section class="parallax-window" data-parallax="scroll" data-image-src="/imgs/website/display-2.png" style="min-height:400px;" data-androidFix="false" data-iosFix="false"></section>
{!! View::make('partials.pages.services')->render() !!}


@stop

@section('modals')
	{!! View::make('partials.frontend.modals')->render() !!}
@stop