@extends($layout)
@section('stylesheets')

@stop
@section('scripts')
<script type="text/javascript" src="/packages/chartjs/chartjs.min.js"></script>
<script type="text/javascript" src="/js/zipcode_requests/index.js"></script>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<br/>
<div class="box box-success">
	<div class="box-header">
		<i class="ion ion-clipboard"></i>
		<h3 class="box-title">Request Chart</h3>
	</div>
	<div class="box-body">
		<div class="chart">
			<!-- Sales Chart Canvas -->
			<canvas id="reportsChart" style="height: 350px;"></canvas>
		</div>
	</div>
	<div class="box-footer">
		<button>Accept Zipcode Request</button>
	</div>
</div>
<div class="panel panel-info">
	<div class="panel-heading"><h3>Zipcode Requests</h3></div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Zipcode</th>
					<th>Name</th>
					<th>Comment</th>
					<th>Ip</th>
					<th>Created</th>
				</tr>
			</thead>
			<tbody>
			@if (count($zipcodes) > 0)
				@foreach($zipcodes as $zipcode)
				<tr>
					<td>{{ $zipcode->id }}</td>
					<td>{{ $zipcode->zipcode }}</td>
					<td>{{ $zipcode->name }}</td>
					<td>{{ $zipcode->comment }}</td>
					<td>{{ $zipcode->ip }}</td>
					<td>{{ date('D n/d/Y',strtotime($zipcode->created_at)) }}</td>

				</tr>
				@endforeach
			@endif
			</tbody>
		</table>
	</div>
	<div class="panel-footer"></div>
</div>
@stop