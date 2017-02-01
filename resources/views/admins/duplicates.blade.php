@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<div class="panel panel-default">
		<div class="panel-header">

		</div>
		<div class="panel-body">

		</div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th>ID</th>
						
					</tr>
				</thead>
				<tbody>
				@if (count($duplicates) > 0)
					@foreach($duplicates as $key => $value)
					<tr>
						<td>{{ $key }}</td>
						<td>{{ $value }}</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
	</div>
@stop

@section('modals')


@stop