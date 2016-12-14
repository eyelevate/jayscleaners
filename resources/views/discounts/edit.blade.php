@extends($layout)

@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop

@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/discounts/add.js"></script>
@stop

@section('header')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop

@section('content')
    <br/>
    {!! Form::open(['action' => 'DiscountsController@postEdit','role'=>"form"]) !!}
    {!! Form::hidden('id',$discounts->id) !!}
    <div class="panel panel-default">
    	<div class="panel-heading">
    		<h3 class="panel-title">Discount List</h3>
    	</div>
    	<div class="panel-body">
    		<div class="form-group {{ $errors->has('company_id') ? ' has-error' : '' }}">
				<label class="control-label">Company</label>
				{{ Form::select('company_id',$companies,old('company_id') ? old('company_id') : $discounts->company_id,['class'=>"form-control"]) }}
                @if ($errors->has('company_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('company_id') }}</strong>
                    </span>
                @endif
			</div>
    		<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
				<label class="control-label">Name</label>
				{{ Form::text('name',old('name') ? old('name') : $discounts->name,['class'=>"form-control",'placeholder'=>'Name']) }}
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
			</div>
			<div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
				<label class="control-label">Type</label>
				{{ Form::select('type',['1'=>'Rate Discount','2'=>'Price Discount'],old('type') ? old('type') : $discounts->type,['class'=>"form-control",'placeholder'=>'type']) }}
                @if ($errors->has('type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
			</div>
			<div id="rate" class="form-group {{ $errors->has('rate') ? ' has-error' : '' }}">
				<label class="control-label">Rate</label>
				{{ Form::text('rate',old('rate') ? old('rate') : $discounts->rate,['class'=>"form-control",'placeholder'=>'rate']) }}
                @if ($errors->has('rate'))
                    <span class="help-block">
                        <strong>{{ $errors->first('rate') }}</strong>
                    </span>
                @endif
			</div>
			<div id="price" class="form-group {{ $errors->has('price') ? ' has-error' : '' }}">
				<label class="control-label">Price</label>
				{{ Form::text('price',old('price') ? old('price') : $discounts->price,['class'=>"form-control",'placeholder'=>'price']) }}
                @if ($errors->has('price'))
                    <span class="help-block">
                        <strong>{{ $errors->first('price') }}</strong>
                    </span>
                @endif
			</div>
			<div class="form-group {{ $errors->has('inventory_id') ? ' has-error' : '' }}">
				<label class="control-label">Inventory Group</label>
				{{ Form::select('inventory_id',$inventories,old('inventory_id') ? old('inventory_id') : $discounts->inventory_id,['class'=>"form-control",'placeholder'=>'Select Inventory Group']) }}
                @if ($errors->has('inventory_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('inventory_id') }}</strong>
                    </span>
                @endif
			</div>
			<div class="form-group {{ $errors->has('inventory_item_id') ? ' has-error' : '' }}">
				<label class="control-label">Inventory Item</label>
				{{ Form::select('inventory_item_id',$items,old('inventory_item_id') ? old('inventory_item_id') : $discounts->inventory_item_id,['class'=>"form-control",'placeholder'=>'Select Item']) }}
                @if ($errors->has('inventory_item_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('inventory_item_id') }}</strong>
                    </span>
                @endif
			</div>
			<div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
				<label class="control-label">Start Date</label>
				{{ Form::text('start_date',old('start_date') ? old('start_date') : date('Y-m-d',strtotime($discounts->start_date)),['class'=>"datePicker form-control",'placeholder'=>'start_date']) }}
                @if ($errors->has('start_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('start_date') }}</strong>
                    </span>
                @endif
			</div>
			<div class="form-group {{ $errors->has('end_date') ? ' has-error' : '' }}">
				<label class="control-label">End Date</label>
				{{ Form::text('end_date',old('end_date') ? old('end_date') : date('Y-m-d',strtotime($discounts->end_date)),['class'=>"datePicker form-control",'placeholder'=>'end_date']) }}
                @if ($errors->has('end_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('end_date') }}</strong>
                    </span>
                @endif
			</div>
			<div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
				<label class="control-label">Status</label>
				{{ Form::select('status',['1'=>'Active','2'=>'Not Active'],old('status') ? old('status') : $discounts->status,['class'=>"form-control",'placeholder'=>'Select Inventory Group']) }}
                @if ($errors->has('status'))
                    <span class="help-block">
                        <strong>{{ $errors->first('status') }}</strong>
                    </span>
                @endif
			</div>
    	</div>
    	<div class="panel-footer">
    		<a class="btn btn-danger" href="{{ route('discounts_index') }}">Back</a>
    		<button class="btn btn-primary" type="submit">Edit Discount</button>
    	</div>
    </div>
    {!! Form::close() !!}


@stop
@section('modals')

@stop