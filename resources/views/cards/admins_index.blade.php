@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('content')
 	<br/>
    <section class="panel panel-default">
        <div class="panel-heading">
        	<header>
				<h2><strong>Credit Cards</strong></h2>
            </header>
        </div>
        <div class="panel-body">
    	@if(count($cards_data) > 0) 
    		@foreach($cards_data as $card)

				<div class="thumbnail" style="background-color:{{ $card['background_color'] }}; font-size:17px;">
					<div class="caption">
						<h3><strong>{{ $card['card_type'] }} {{ $card['card_number'] }}</strong> <span class="pull-right"><img src="{{ $card['card_image'] }}" /></span></h3>
						<p style="margin-bottom:5px;">{{ $card['first_name'] }} {{ $card['last_name'] }}</p>
						<p class="clearfix"> <strong>Expiration Date:</strong>&nbsp;{{ $card['exp_month'] }}/{{ $card['exp_year'] }} <span class="pull-right">{{ $card['days_remaining']}}</span></p>
						<div class="clearfix">
							<a href="{{ route('cards_admins_delete',$card['id']) }}" class="btn btn-danger" role="button">Delete</a>
							<a href="{!! route('cards_admins_edit',$card['id']) !!}" class="btn btn-default" role="button">Edit</a>
						</div>
					</div>
				</div>
    		@endforeach
    	@endif
	    </div>
        <div class="panel-footer">
			<a href="{{ route('delivery_new',$customer_id) }}" class="btn btn-danger">Back</a>
            <a href="{{ route('cards_admins_add',$customer_id) }}" class="btn btn-primary">Add Card</a>
        </div>
    </section>

@stop
@section('modals')
    {!! View::make('partials.frontend.modals')->render() !!}
@stop