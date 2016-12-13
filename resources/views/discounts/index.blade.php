@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('header')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop

@section('content')
    <br/>
    <div class="panel panel-default">
    	<div class="panel-heading">
    		<h3 class="panel-title">Discount List</h3>
    	</div>
    	<div class="table-responsive">
    		<table class="table table-hover table-stripped">
    			<thead>
    				<tr>
    					<th>ID</th>
    					<th>Name</th>
    					<th>Type</th>
    					<th>Group</th>
    					<th>Item</th>
    					<th>Rate</th>
    					<th>Price</th>
    					<th>Start</th>
    					<th>End</th>
    					<th>Status</th>
    					<th>Action</th>
    				</tr>
    			</thead>	
    		</table>
    	</div>
    	<div class="panel-footer">
    		<a class="btn btn-primary" href="{{ route('discounts_add') }}">Create Discount</a>
    	</div>
    </div>


@stop
@section('modals')

@stop