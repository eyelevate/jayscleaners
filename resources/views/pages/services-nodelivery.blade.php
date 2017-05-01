@extends($layout)

@section('stylesheets')

@stop

@section('scripts')
<script type="text/javascript" src="/packages/Readmore/readmore.min.js"></script>
<script type="text/javascript" src="/js/pages/index.js"></script>
@stop

@section('navigation')
<header id="header" class="reveal">
{!! View::make('partials.layouts.navigation-nodelivery')
    ->render()
!!}
</header>
@stop


@section('content')
{!! View::make('partials.pages.services')->render() !!}
@stop
