@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')

<header id="header" class="reveal">
@if(Auth::check())
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
@section('content')
<section class="wrapper style3 container special">
	<header class="major">
		<h2>Our <strong>Prices</strong></h2>
	</header>
	@if (count($price_list) > 0)
		@foreach($price_list as $key => $value)
		<div class="thumbnail">
			<div class="caption">
				<h3><strong>{{ $key }}</strong></h3>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<thead>
							<tr>
								<th style="text-align:left">Name</th>
								<th style="text-align:left">Base Price</th>
							</tr>
						</thead>
						<tbody>
						@if (count($value) > 0)
							@foreach($value as $item => $price)
							<tr>
								<td style="text-align:left">{{ $item }}</td>
								<td style="text-align:left">{{ $price }}</td>
							</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>	
		@endforeach
	@endif
	<p>Cant find the item you are looking for? Do not fret! We are only displaying prices of our most popular items and of items that have the most inquiry. Please give us a call if you want a specific price of your garment that is not listed above!</p>
</section>
@stop
@section('modals')
    {!! View::make('partials.frontend.modals')->render() !!}
@stop