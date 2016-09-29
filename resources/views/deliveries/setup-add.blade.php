@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/deliveries/setup-add.js"></script>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<br/>
	{!! Form::open(['action' => 'DeliveriesController@postSetupAdd','role'=>"form"]) !!}
	<div class="panel panel-primary">
		<div class="panel-heading"><h4>New Delivery Schedule</h4></div>
		<div class="panel-body">
            <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                <label class="control-label">Select who will run the route <span class="text text-danger">*</span></label>
                {!! Form::select('company_id',$companies , old('company_id'), ['class'=>'form-control']) !!}
                @if ($errors->has('company_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('company_id') }}</strong>
                    </span>
                @endif

            </div> 
            <div class="form-group{{ $errors->has('route_name') ? ' has-error' : '' }}">
                <label class="control-label">Route Name <span class="text text-danger">*</span></label>
                {!! Form::text('route_name', old('route_name'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('route_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('route_name') }}</strong>
                    </span>
                @endif

            </div> 			
            <div class="form-group{{ $errors->has('day') ? ' has-error' : '' }}">
                <label class="control-label">Day Of Week <span class="text text-danger">*</span></label>

                {!! Form::select('day',$day , old('day'), ['class'=>'form-control']) !!}
                @if ($errors->has('day'))
                    <span class="help-block">
                        <strong>{{ $errors->first('day') }}</strong>
                    </span>
                @endif

            </div>
            <div class="form-group{{ $errors->has('limit') ? ' has-error' : '' }}">
                <label class="control-label">Trip limit per day <span class="text text-danger">*</span></label>

                {!! Form::select('limit',$limits , old('limit'), ['class'=>'form-control']) !!}
                @if ($errors->has('limit'))
                    <span class="help-block">
                        <strong>{{ $errors->first('limit') }}</strong>
                    </span>
                @endif

            </div>	
  
         	<div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                <label class="control-label">Start Time <span class="text text-danger">*</span></label>
                <div class="clearfix" style="margin-bottom:10px;">
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                {!! Form::select('start_hours',$hours , old('start_hours'), ['class'=>'form-control']) !!}
	           	 	</div>
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                {!! Form::select('start_minutes',$minutes , old('start_minutes'), ['class'=>'form-control']) !!}
	           	 	</div>
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                {!! Form::select('start_ampm',$ampm , old('start_ampm'), ['class'=>'form-control']) !!}
	           	 	</div>
           	 	</div>
            </div>
         	<div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
                <label class="control-label">End Time <span class="text text-danger">*</span></label>
                <div class="clearfix" style="margin-bottom:10px;">
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                {!! Form::select('end_hours',$hours , old('end_hours'), ['class'=>'form-control']) !!}
	           	 	</div>
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                {!! Form::select('end_minutes',$minutes , old('end_minutes'), ['class'=>'form-control']) !!}
	           	 	</div>
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                {!! Form::select('end_ampm',$ampm , old('end_ampm'), ['class'=>'form-control']) !!}
	           	 	</div>
           	 	</div>
            </div>   
            <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                <label class="control-label">Zipcode(s) <span class="text text-danger">*</span></label>

                {!! Form::select('zipcode_select',$zipcodes ,'', ['id'=>'zipcode_select','class'=>'form-control']) !!}

            </div>	
            <div class="form-group">
            	<label class="control-label">Zipcode(s) Selected</label>
	            <div class="table-responsive">
	            	<table id="zipcode_table" class="table table-condensed table-striped table-hover">
	            		<thead>
	            			<tr>
	            				<th>Zipcode</th>
	            				<th>Action</th>
	            			</tr>
	            		</thead>
	            		<tbody></tbody>
	            	</table>
	           	</div>   
           	</div> 
            <div class="form-group{{ $errors->has('blackout') ? ' has-error' : '' }}">
                <label class="control-label">Blackout Date(s) <span class="text text-danger">*</span></label>

                <input id="blackout" type="text" class="form-control" value="" style="background-color:#ffffff" readonly="true">

            </div>	
            <div class="form-group">
            	<label class="control-label">Blackout Date(s) Chosen</label>
	            <div class="table-responsive">
	            	<table id="blackout_table" class="table table-condensed table-striped table-hover">
	            		<thead>
	            			<tr>
	            				<th>Date</th>
	            				<th>Action</th>
	            			</tr>
	            		</thead>
	            		<tbody></tbody>
	            	</table>
	           	</div>   
           	</div>     		
		</div>

		<div class="panel-footer clearfix">
			<a href="{{ route('delivery_setup') }}" class="btn btn-danger">Cancel</a>
			<button type="submit" class="btn btn-primary pull-right">Add</button>
		</div>

	</div>
	{!! Form::close() !!}
@stop