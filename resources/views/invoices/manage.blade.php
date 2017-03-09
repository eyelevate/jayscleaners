@extends($layout)
@section('stylesheets')
@stop
@section('scripts')
<script type="text/javascript" src="/packages/number/jquery.number.min.js"></script>
<script type="text/javascript" src="/packages/numeric/jquery.numeric.js"></script>
<script type="text/javascript" src="/packages/priceformat/priceformat.min.js"></script>
<script type="text/javascript" src="/js/invoices/manage.js"></script>

@stop
@section('header')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<br/>
{!! Form::open(['action' => 'InvoicesController@postSearch','role'=>"form"]) !!}
<div class="box box-primary clearfix">
	<div class="box-header">
		<h3 class="box-title">Search Invoice</h3>
	</div>
	
	<div class="box-body">	
        <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
            <label class="control-label">Search <span class="text text-danger">*</span></label>

            <div class="">
                {!! Form::text('search', old('search') ? old('search') : (isset($invoice_id)) ? $invoice_id : NULL, ['id'=>'search_query','class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('search'))
                    <span class="help-block">
                        <strong>{{ $errors->first('search') }}</strong>
                    </span>
                @endif
            </div>
        </div>
	</div>
	<div class="box-footer clearfix">
		<input class="btn btn-success btn-lg" type="submit" value="Search" />
	</div>
</div>
{!! Form::close() !!}

{!! Form::open(['action'=>'InvoicesController@postManage','role'=>'form']) !!}
{!! Form::hidden('invoice_id',$invoice_id) !!}
<div class="box box-success clearfix">
	<div class="box-header">
		<h3 class="box-title">{{ ($invoice_id) ? 'Invoice Detail #'.$invoice_id : 'No Invoice Selected' }}</h3>
	</div>
	<div class="table-responsive">
		<table class="table-bordered table-striped table-hover table-condensed col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<thead>
				<tr>
					<th class="col-sm-2 col-md-1 col-lg-1">Qty</th>
					<th class="col-sm-7 col-md-9 col-lg-9">Item</th>
					<th class="col-sm-3 col-md-2 col-lg-2">Subtotal</th>
				</tr>
			</thead>
			<tbody>
			@if (count($invoices) > 0)
				@foreach($invoices as $invoice)
					@if (count($invoice->item_details) > 0)
						@foreach($invoice->item_details as $ikey => $item)
						<tr style="cursor:pointer" >
							<td style="text-align:center;" data-toggle="modal" data-target="#expand-{{ $ikey }}">{{ $item['qty'] }}</td>
							<td data-toggle="modal" data-target="#expand-{{ $ikey }}">
								{{ $item['item'] }}

								@if (count($item['color']) > 0)
								<br/>
									<?php $color_string = ''; ?>
									@foreach($item['color'] as $color_name => $color_count)
										<?php $color_string .= $color_name.' - '.$color_count.', ';?>
									@endforeach
									{{ rtrim($color_string,', ') }}
								@endif
							</td>
							<td ><input class="item_value" old="{{ money_format('%i',$item['subtotal']) }}" name="item[{{ $ikey }}]" class="col-sm-12 col-xs-12 col-md-12 col-lg-12" type="text" value="{{ money_format('%i',$item['subtotal']) }}"/></td>
						</tr>
						@endforeach
					@endif
				@endforeach
			@endif
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" style="text-align:right;">Total Qty </th>
					<th>{{ (count($invoices) > 0) ? $invoices[0]['quantity'] : NULL }}</th>
				</tr>
				<tr>
					<th colspan="2" style="text-align:right;">Total Subtotal </th>
					<th>{{ (count($invoices) > 0) ? $invoices[0]['pretax_html'] : NULL }}</th>
				</tr>
				<tr>
					<th colspan="2" style="text-align:right;">Total Tax </th>
					<th>{{ (count($invoices) > 0) ? money_format('$%i',$invoices[0]['tax']) : NULL }}</th>
				</tr>
				<tr>
					<th colspan="2" style="text-align:right;">Total Aftertax </th>
					<th>{{ (count($invoices) > 0) ? $invoices[0]['total_html'] : NULL }}</th>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="box-footer clearfix">
		<div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">

            <div class="col-md-6">
                {!! Form::select('company_id',$companies , {{ (count($invoices) > 0) ? $invoices[0]['company_id'] : Auth::user()->company_id }}, ['class'=>'form-control']) !!}
                @if ($errors->has('company_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('company_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>
		<button class="btn btn-lg btn-success" type="submit">Edit Prices</button>
	</div>
</div>
{!! Form::close() !!}

@stop

@section('modals')
	@if (count($split) > 0)
		@foreach($split as $spl_key =>$spl_value)
			{!! View::make('partials.invoices.manage')
				->with('items',$split[$spl_key]['items'])
				->with('invoice_id',$invoice_id)
				->with('item_id',$spl_key)
				->with('subtotal',$split[$spl_key]['total_subtotal'])
				->render()
			!!}
		@endforeach

	@endif
	
@stop

