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
<!-- {!! View::make('partials.pages.contact-us')
	->with('companies',$companies)
    ->render()
!!} -->
{!! View::make('partials.pages.contact-us-nodelivery')
	->with('companies',$companies)
    ->render()
!!}
@stop