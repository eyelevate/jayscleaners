@extends($layout)
@section('stylesheets')

@stop
@section('scripts')
<script type="text/javascript" src="/packages/ion.sound-3.0.7/ion.sound.min.js"></script>
<script type="text/javascript" src="/js/invoices/rack.js"></script>
@stop
@section('header')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<br/>

	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="box box-primary" >
				<div class="box-header"><h4>Rack Form</h4></div>
				<div class="box-body">
					<div class="form-group">
						<label>Invoice #</label>
						<input id="invoice_id" class="rack_input form-control" type="text" value="" placeholder="invoice #"/>
					</div>
					<div class="form-group">
						<label>Rack #</label>
						<input id="rack_number" class="rack_input form-control" type="text" value="" placeholder="rack #"/>
					</div>
				</div>
				<div class="box-footer clearfix">
					

				</div>
			</div>
		</article>
		{!! Form::open(['action' => 'InvoicesController@postRack','role'=>"form"]) !!}
		{!! Form::hidden('id',$id) !!}
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="box box-info">
				<div class="box-header"><h4>Invoice Selected</h4></div>
				<div class="table-responsive">
					<table class="table table-condensed table-striped table-hover">
						<thead>
							<th>ID</th>
							<th>Rack</th>
							<th>Action</th>
						</thead>
						<tbody id="rack_tbody">
						@if ($racks) 
							@foreach($racks as $key => $value)
							<tr>
								<td>{{ $key }}</td>
								<td>{{ $value }}</td>
								<td><a invoice="{{ $key }}" class="remove btn btn-sm btn-danger">Remove</a><input type="hidden" name="rack[{{ $key }}]" value="{{ $value }}"/></td>
							</tr>
							@endforeach
						@endif
						</tbody>

					</table>
				</div>
				<div class="box-footer clearfix">
					<a class="btn btn-lg btn-danger" href="{{ ($id) ? route('customers_view',$id) : route('admins_index') }}">Back</a>
					<button type="submit" class="btn btn-lg btn-success" >Finish</button>

				</div>
			</div>
		</article>
		{!! Form::close() !!}
	</section>

@stop

@section('modals')
=
@stop