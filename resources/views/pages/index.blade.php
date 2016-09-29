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
	<h2>Welcome to Jays Cleaners. With over <strong>35 years</strong> of experience, <strong>let us work for you</strong>.</h2>
</header>

<!-- One -->
<section class="wrapper style2 container special-alt">
	<div class="row 50%">
		<div class="8u 12u(narrower)">

			<header>
				<h5>Where to find us</h5>
			</header>
			<p>We proudly serve the Seattle region at 2 prime locations in the Montlake and Roosevelt neighborhoods. With the ability to deliver if these locations are not suitable to your current location.</p>
			@if (count($companies) > 0)
				@foreach($companies as $company)
				<address>
					<strong>{{ $company->name }}</strong><br/>
					{{ $company->street }} <br/>
					{{ $company->city }}, {{ $company->state }} {{ $company->zipcode }} <br/>
					{{ $company->phone }}
				</address>
				@endforeach
			@endif

			<footer>
				<ul class="buttons">
					<li><a href="#" class="button">Find Out More</a></li>
				</ul>
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
				<span class="icon featured fa-check"></span>
				<header>
					<h3>This is Something</h3>
				</header>
				<p>Sed tristique purus vitae volutpat ultrices. Aliquam eu elit eget arcu commodo suscipit dolor nec nibh. Proin a ullamcorper elit, et sagittis turpis. Integer ut fermentum.</p>
			</section>

		</div>
		<div class="4u 12u(narrower)">

			<section>
				<span class="icon featured fa-check"></span>
				<header>
					<h3>Also Something</h3>
				</header>
				<p>Sed tristique purus vitae volutpat ultrices. Aliquam eu elit eget arcu commodo suscipit dolor nec nibh. Proin a ullamcorper elit, et sagittis turpis. Integer ut fermentum.</p>
			</section>

		</div>
		<div class="4u 12u(narrower)">

			<section>
				<span class="icon featured fa-check"></span>
				<header>
					<h3>Probably Something</h3>
				</header>
				<p>Sed tristique purus vitae volutpat ultrices. Aliquam eu elit eget arcu commodo suscipit dolor nec nibh. Proin a ullamcorper elit, et sagittis turpis. Integer ut fermentum.</p>
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