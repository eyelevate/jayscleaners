<section class="wrapper style3 container special">
	<div class="row">
		<header class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
			<span class="icon featured fa-map-o"></span>
			<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Where to find us</h3>
		</header>
		<section class="clearfix">
		<p>We proudly serve the Seattle region at 2 prime locations in the Montlake and Roosevelt neighborhoods.</p>
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
</section>