@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')
<header id="header" class="reveal">
{!! View::make('partials.layouts.navigation-nodelivery')
    ->render()
!!}
</header>
@stop


@section('content')
{!! 
	View::make('partials.pages.business-hours')
	->with('companies',$companies)
	->render() 
!!}
@stop