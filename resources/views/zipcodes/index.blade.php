@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<br/>
	<div class="panel panel-info">
		<div class="panel-heading"><h4>Zipcodes</h4></div>
		<div class="table-responsive">
			<table class="table table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Zipcode</th>
						<th>Status</th>
						<th>Created</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@if(count($zipcodes) > 0)
					@foreach($zipcodes as $zipcode)
					<tr>
						<td>{{ $zipcode->id }}</td>
						<td>{{ $zipcode->zipcode }}</td>
						<td>{{ $zipcode->status }}</td>
						<td>{{ date('D n/d/Y',strtotime($zipcode->created_at)) }}</td>
						<td>
							<a href="/zipcodes/edit/{{ $zipcode->id }}" class="btn btn-info btn-sm">edit</a>&nbsp;
							<a href="{{ route('zipcodes_delete',$zipcode->id) }}" class="btn btn-sm btn-danger">delete</a>
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
		<div class="panel-footer clearfix">
			<a href="{{ route('admins_index') }}" class="btn btn-danger btn-lg">Home</a>
			<a href="{{ route('zipcodes_add') }}" class="btn btn-primary btn-lg pull-right">Add</a>
		</div>
	</div>

@stop