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
    			<tbody>
    			@if (count($discounts) > 0)
    				@foreach($discounts as $discount)
    				<tr class="{{ ($discount->status == 1 ? 'success' : 'error') }}">
    					<td>{{ $discount->id }}</td>
    					<td>{{ $discount->name }}</td>
    					<td>{{ $discount->type }}</td>
    					<td>{{ $discount->group }}</td>
    					<td>{{ $discount->item }}</td>
    					<td>{{ $discount->rate }}</td>
    					<td>{{ $discount->discount }}</td>
    					<td>{{ date('n/d/Y g:ia',strtotime($discount->start_date)) }}</td>
    					<td>{{ date('n/d/Y g:ia',strtotime($discount->end_date)) }}</td>
    					<td>{{ $discount->status_label }}</td>
    					<td>
    						<a href="{{ route('discounts_edit',$discount->id) }}">Edit</a> 
    						<a style="color:#ff0000" href="#" data-toggle="modal" data-target="#delete-{{ $discount->id }}">Delete</a>
    					</td>
    				</tr>
    				@endforeach
    			@endif
    			</tbody>	
    		</table>
    	</div>
    	<div class="panel-footer">
    		<a class="btn btn-primary" href="{{ route('discounts_add') }}">Create Discount</a>
    	</div>
    </div>


@stop
@section('modals')
	@if (count($discounts) > 0)
		@foreach($discounts as $discount)
		{!! View::make('partials.discounts.delete')
			->with('id',$discount->id)
			->render() 
		!!}
		@endforeach
	@endif
@stop