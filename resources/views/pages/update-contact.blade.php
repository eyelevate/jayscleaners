@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')
<header id="header" class="reveal">
{!! View::make('partials.layouts.navigation_logged_in')
    ->render()
!!}
</header>
@stop


@section('content')

@stop