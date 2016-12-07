@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')
<header id="header" class="reveal">
@if (Auth::check())
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
		<div id="store_hours" class="row">
			<header class="clearfix col-xs-12 col-sm-12" style="">
				<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Select Account Payment Method</h3>
			</header>
			<section class="clearfix col-xs-12 col-sm-12">
				<a class="btn btn-lg btn-default btn-block" href="{{ route('accounts_oneTimePayment') }}">One Time Payment</a>
			</section>
			<section class="clearfix col-xs-12 col-sm-12">
				<a class="btn btn-lg btn-primary btn-block" href="{{ route('accounts_memberPayment') }}">Membership Payment</a>
			</section>
		</div>


	</section>

@stop
@section('modals')

@stop