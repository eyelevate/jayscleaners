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
{!! 
	View::make('partials.pages.business-hours')
	->with('companies',$companies)
	->render() 
!!}
@stop