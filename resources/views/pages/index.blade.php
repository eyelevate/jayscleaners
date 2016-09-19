@extends($layout)

@section('stylesheets')

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
	<p>Welcome back <strong>{{ $auth->username }}</strong>
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
				<li><input type="submit" class="button fit" value="Repeat Last Delivery"/></li>
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
<header class="special container scrolly">
	<span class="icon fa-bar-chart-o"></span>
	<h2>As this is my <strong>twentieth</strong> freebie for HTML5 UP
	<br />
	I decided to give it a really creative name.</h2>
	<p>Turns out <strong>Twenty</strong> was the best I could come up with. Anyway, lame name aside,
	<br />
	it's minimally designed, fully responsive, built on HTML5/CSS3/<strong>skel</strong>,
	and, like all my stuff,
	<br />
	released for free under the <a href="http://html5up.net/license">Creative Commons Attribution 3.0</a> license. Have fun!</p>
</header>

<!-- One -->
<section class="wrapper style2 container special-alt">
	<div class="row 50%">
		<div class="8u 12u(narrower)">

			<header>
				<h2>Behold the <strong>icons</strong> that visualize what you’re all about. or just take up space. your call bro.</h2>
			</header>
			<p>Sed tristique purus vitae volutpat ultrices. Aliquam eu elit eget arcu comteger ut fermentum lorem. Lorem ipsum dolor sit amet. Sed tristique purus vitae volutpat ultrices. eu elit eget commodo. Sed tristique purus vitae volutpat ultrices. Aliquam eu elit eget arcu commodo.</p>
			<footer>
				<ul class="buttons">
					<li><a href="#" class="button">Find Out More</a></li>
				</ul>
			</footer>

		</div>
		<div class="4u 12u(narrower) important(narrower)">

			<ul class="featured-icons">
				<li><span class="icon fa-clock-o"><span class="label">Feature 1</span></span></li>
				<li><span class="icon fa-volume-up"><span class="label">Feature 2</span></span></li>
				<li><span class="icon fa-laptop"><span class="label">Feature 3</span></span></li>
				<li><span class="icon fa-inbox"><span class="label">Feature 4</span></span></li>
				<li><span class="icon fa-lock"><span class="label">Feature 5</span></span></li>
				<li><span class="icon fa-cog"><span class="label">Feature 6</span></span></li>
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