@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.search').Zebra_DatePicker();
});

</script>
@stop

@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
{!! Form::open(array('action' => 'AdminsController@postRackHistory','role'=>"form")) !!}
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Rack History Search</h3>
		</div>
		<div class="panel-body">
			<div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                <label class="control-label">Company <span class="text text-danger">*</span></label>

                {!! Form::select('company_id',[''=>'Select Company','1'=>'Roosevelt','2'=>'Montlake'], (isset($company_id)) ? $company_id : Auth::user()->company_id , ['class'=>'company_id form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('company_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('company_id') }}</strong>
                    </span>
                @endif

            </div>
			<div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
                <label class="control-label">Search Date <span class="text text-danger">*</span></label>

                {!! Form::text('search', (isset($search)) ? $search : NULL , ['class'=>'search form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('search'))
                    <span class="help-block">
                        <strong>{{ $errors->first('search') }}</strong>
                    </span>
                @endif

            </div>

		</div>
		<div class="panel-footer">
			<input type="submit" class="btn btn-info btn-lg" value="Search"/>
		</div>
	</div>

{!! Form::close() !!}
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Rack History Search</h3>
		</div>
		<div class="panel-body">


		</div>
		<div class="table-responsive">
			<table class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th>Inv #</th>
						<th>Cust #</th>
						<th>Name</th>
						<th>Date</th>
						<th>Rack</th>
					</tr>
				</thead>
				<tbody>
				@if (isset($history))
					@if (count($history) > 0)
						@foreach($history as $h)
						<tr>
							<td>{{ $h->id }}</td>
							<td>{{ $h->customer_id }}</td>
							<td>{{ ucFirst($h['customer']->last_name).', '.ucFirst($h['customer']->first_name) }}</td>
							<td>{{ date('n/d/Y g:i:s a',strtotime($h->rack_date)) }}</td>
							<td>{{ $h->rack }}</td>
						</tr>
						@endforeach
					@endif
				@endif
				</tbody>
			
			</table>
		</div>
		<div>
		</div>
	</div>

@stop