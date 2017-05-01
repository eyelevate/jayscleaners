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
	{!! View::make('partials.layouts.navigation-nodelivery')
		->render()
	!!}
	</header>
@stop

@section('banner')
	<header>
		<h2>Jays Cleaners</h2>
	</header>
@stop

@section('content')
<header class="special container">
	<span class="icon fa-home fa-fw" style="font-size:25px;"></span>
	<h2>Welcome to Jays Cleaners. With over <strong>70 years</strong> of experience, <strong>let us work for you</strong>.</h2>
</header>
<section class="parallax-window" data-parallax="scroll" data-image-src="/img/website/display-4.png" style="min-height:300px;"></section>
<!-- Two -->
<br/>
<section class="wrapper style1 container special clearfix">
	<div class="row">
		<div class="6u 12u(narrower)" style="background-color:#f3f6fa;">

			<section class="read_articles" style="background-color:#F3F6FA; padding: 5px;">
				
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
		<div class="6u 12u(narrower)" style="background-color:#f3f6fa;">

			<section class="read_articles" style="background-color:#F3F6FA; padding: 5px;">
				
				<header>
					<span class="icon featured fa-check-square-o"></span>
					<h3>THE HEALTHY CLEANING ALTERNATIVE</h3>
				</header>
				<p>Jays Cleaners utilizes the SystemK4 cleaning system which is a Toxin-Free, Environmentally Safe and Healthy cleaning system. Unlike the cleaning methods of the past which relied heavily on Perchloroethylene (Perc), SystemK4 utilizes a perc-free/halogen-free, organic solvent and has been tested to be dermatologically safe, biodegradable and provides an excellent, odorless finish to every garment. </p>
				<a href="http://www.systemk4.com/en/" target="__blank" class="btn btn-lg btn-info">Learn More</a>
			</section>

		</div>


	</div>
</section>

<div class="row-fluid">
	<article class="parallax-window" data-parallax="scroll" data-image-src="/img/website/display-5.png" style="min-height:300px; position:relative;"></article>

	<!-- One -->
	<section class="wrapper style3 container special">
		
		<div class="row">
			<header class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
				<span class="icon featured fa-map-o"></span>
				<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Where to find us</h3>
			</header>
			<section class="clearfix">
			<p>
				We proudly serve the Seattle region at our conveniently located Montlake and Roosevelt locations.
			</p>
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
</div>
<section class="parallax-window" data-parallax="scroll" data-image-src="/img/website/display-2.png" style="min-height:300px;" ></section>
{!! View::make('partials.pages.services')->render() !!}


@stop

@section('modals')
	{!! View::make('partials.frontend.modals')->render() !!}
@stop