@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')

<header id="header" class="reveal">
{!! View::make('partials.layouts.navigation_logged_out')
    ->render()
!!} 
</header>
@stop


@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">

        </div>
    </div>
@stop
@section('modals')
    {!! View::make('partials.frontend.modals')->render() !!}
@stop