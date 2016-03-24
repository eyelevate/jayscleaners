@extends($layout)
@section('stylesheets')
<link href='/packages/fullcalendar-2.6.1/fullcalendar.css' rel='stylesheet' />
<link href='/packages/fullcalendar-2.6.1/fullcalendar.print.css' rel='stylesheet' media='print' />
<link rel="stylesheet" href="/css/colors/index.css">
<link rel="stylesheet" href="/css/invoices/add.css">
@stop
@section('scripts')
<script type="text/javascript" src="/packages/number/jquery.number.min.js"></script>
<script type="text/javascript" src="/packages/numeric/jquery.numeric.js"></script>
<script src='/packages/fullcalendar-2.6.1/lib/moment.min.js'></script>
<script src='/packages/fullcalendar-2.6.1/fullcalendar.min.js'></script>
<script type="text/javascript" src="/js/invoices/index.js"></script>
@stop
@section('header')

@stop
@section('content')
<div class=" clearfix">
	<div class="row">
		<div class="box box-primary col-lg-12 col-md-12 col-sm-12" style="border-radius:0px;">
			<div class="box-body">	
				<ul class=" no-padding" >
					<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none; height:65px;"><button type="button" id="number-c" class="number-clear btn btn-primary" style="font-size:30px; height:60px; width:100%">C</button></li>
					@for($i=1;$i<=9;$i++)
					<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none; height:65px;"><button type="button" id="number-{{ $i }}" class="number btn btn-primary" style="font-size:30px; height:60px; width:100%">{{ $i }}</button></li>
					@endfor
					<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none; height:65px;"><button type="button" id="number-0" class="number btn btn-primary" style="font-size:30px; height:60px; width:100%">0</button></li>
					<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none; height:65px;">
						<button type="button" id="actual_number" class="btn btn-default" style="font-size:30px; height:60px; width:100%"/><span id="itemQtySpan">--</span></button>
						<div class="hide">
							<input type="hidden" value="0" id="itemQty"/>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-7 col-md-7 col-sm-7">

		<div class="nav-tabs-custom" >
			<!-- Tabs within a box -->
			<ul id="inventory-group-ul" class="nav nav-tabs no-padding">
				<?php $idx = -1;?>
				@if(isset($inventories))
					@foreach($inventories as $inventory)
					<?php $idx++;?>
					<li class="{{ ($idx == 0) ? 'active' : '' }}" class="cursor:pointer">
						<a href="#sales-{{ $inventory->id }}" data-toggle="tab">{{ $inventory->name }}</a>
						<div class="hide">
							{{ Form::hidden('inventory['.$idx.'][id]',$inventory->id,['class'=>'inventory-id']) }}
							{{ Form::hidden('inventory['.$idx.'][name]',$inventory->name,['class'=>'inventory-name']) }}
							{{ Form::hidden('inventory['.$idx.'][description]',$inventory->description,['class'=>'inventory-description']) }}
							{{ Form::hidden('inventory['.$idx.'][ordered]',$inventory->ordered,['class'=>'inventory-order']) }}
						</div>
					</li>
					@endforeach
				@endif

			</ul>

			<ul class="tab-content no-padding">
				<!-- Morris chart - Sales -->
				<?php $idx = -1;?>
				@if(isset($items))
					@foreach($items as $key => $value)
					<?php $idx++;?>
					<li class="chart tab-pane {{ ($idx == 0) ? 'active' : '' }}" id="sales-{{ $key }}" style="position: relative; min-height: 300px; padding-top:5px;">
					@if(isset($value['items']))
						@foreach($value['items'] as $item)
						<a id="item-{{ $item->id }}" href="#" class="items col-lg-3 col-md-4 col-xs-6 ">
							<!-- small box -->
							<div class="small-box bg-gray clearfix" style="max-height:125px; overflow:hidden">
								<div class="inner" style="padding-bottom:50px;">
									<h4><strong>{{ $item->name }}</strong></h4>
									<small><strong>{{ $item->description }}</strong></small>
							        <div class="icon" style="z-index:0">
							          <img src="/{{ $item->image }}" style="max-width:64px; opacity:0.8"/>
							        </div>
								</div>
						        <div class="small-box-footer" style="position:absolute; width:100%; bottom:0px; background:rgba(0,0,0,0.6); font-size:15px;"><strong>{{ $item->price }}</strong></i></div>
							</div>
							<div class="hide">
								{{ Form::hidden('item['.$item->id.'][id]',$item->id,['class'=>'item-id']) }}
								{{ Form::hidden('item['.$item->id.'][name]',$item->name,['class'=>'item-name']) }}
								{{ Form::hidden('item['.$item->id.'][description]',$item->description,['class'=>'item-description']) }}
								{{ Form::hidden('item['.$item->id.'][ordered]',$item->ordered,['class'=>'item-order']) }}
								{{ Form::hidden('item['.$item->id.'][price]',$item->price,['class'=>'item-price']) }}
								{{ Form::hidden('item['.$item->id.'][image]',$item->image,['class'=>'item-image']) }}
								{{ Form::hidden('item['.$item->id.'][tags]',$item->tags,['class'=>'item-tags']) }}
								{{ Form::hidden('item['.$item->id.'][inventory_id]',$item->ordered,['class'=>'item-inventory_id']) }}

							</div>
						</a><!-- ./col -->					
						@endforeach
					@endif
					</li>
					@endforeach
				@endif
			</ul>
		</div><!-- /.nav-tabs-custom -->
	</div>

	<div class="col-lg-5 col-md-5 col-sm-5" >
		<div class="box box-primary">
			<div class="box-body table-responsive">
				<table id="invoiceSummaryTable" class="table table-hover">
					<thead>
						<tr>
							<th><a id="editQty" href="#" class="btn btn-info" data-toggle="modal" data-target="#qty"><strong>Qty</strong></a></th>
							<th><a id="editItem" href="#" class="btn btn-disabled" style="color:#5e5e5e; cursor:default;"><strong>Item</strong></a></th>
							<th><a id="editColor" href="#" class="btn btn-info" data-toggle="modal" data-target="#color"><strong>Color</strong></a></th>
							<th><a id="editMemo" href="#" class="btn btn-info" data-toggle="modal" data-target="#memo-table"><strong>Memo</strong></a></th>
							<th><a id="editPrice" href="#" class="btn btn-info" data-toggle="modal" data-target="#price"><strong>Price</strong></a></th>
						</tr>
					</thead>
					<tbody><tr></tr></tbody>
					<tfoot>
						<tr>
							<td colspan="3" style="border:none;"></td>
							<td>Subtotal</td>
							<th id="invoiceItem-subtotal"></th>
						</tr>
						<tr>
							<td colspan="3" style="border:none;"></td>
							<td>Tax</td>
							<th id="invoiceItem-tax"></th>
						</tr>
						<tr>
							<td colspan="3" style="border:none;"></td>
							<td>Discount</td>
							<th id="invoiceItem-discount"></th>
						</tr>
						<tr>
							<td colspan="3" style="border:none;"></td>
							<th>Total</th>
							<th id="invoiceItem-total"></th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="panel-footer clearfix">
				<div id="openCalendar" class="small-box bg-yellow col-lg-6 col-md-6" style="padding-bottom:10px" data-toggle="modal" data-target="#calendar">
					<div class="inner">
						<h4>{{ date('D, n/d, g:ia',strtotime($turnaround_date)) }}</h4>
						<p>Due Date</p>
					</div>
			        <div class="icon">
			          <i class="ion-calendar"></i>
			        </div>

				</div>
				<div class="small-box bg-green col-lg-6 col-md-6" style="padding-bottom:10px" data-toggle="modal" data-target="#">
					<div class="inner">
						<h4>Finish</h4>
						<p>Print & Create RFID</p>
					</div>
			        <div class="icon">
			          <i class="ion-ios-printer"></i>
			        </div>

				</div>
			</div>
		</div>
	</div><!-- /.nav-tabs-custom -->	
</div>
{!! Form::open(['action' => 'InvoicesController@postAdd','id'=>'invoice-form', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
{{ Form::hidden('customer_id',$customer->id) }}
{{ Form::hidden('due_date',null,['id'=>'due_date'])}}
{{ Form::hidden('subtotal',null,['id'=>'subtotal']) }}
{{ Form::hidden('tax',null,['id'=>'tax']) }}
{{ Form::hidden('total',null,['id'=>'total']) }}
{!! Form::close() !!}
<div id="eventFormDataDiv" class="hide">
	{{ Form::hidden('turnaround',$turnaround,['id'=>'turnaround']) }}
	{{ Form::hidden('turnaround_date',$turnaround_date,['id'=>'turnaround_date']) }}
	{{ Form::hidden('store_hours',$store_hours,['id'=>'store_hours']) }}
	{{ Form::hidden('tax_rate',$tax_rate,['id'=>'tax_rate']) }}
</div>
@stop

@section('modals')
	{!! View::make('partials.invoices.colors')
		->with('colors',$colors)
		->render()
	!!}
	{!! View::make('partials.invoices.memos-table')
		->render()
	!!}
	{!! View::make('partials.invoices.memos')
		->with('memos',$memos)
		->render()
	!!}	 
	{!! View::make('partials.invoices.prices')
		->render()
	!!}	 
	{!! View::make('partials.invoices.priceCalculator')
		->render()
	!!}	 
	{!! View::make('partials.invoices.qty')
		->render()
	!!}	
	{!! View::make('partials.invoices.calendar')
		->with('turnaround_date',$turnaround_date)
		->with('hours',$hours)
		->with('minutes',$minutes)
		->with('ampm',$ampm)
		->render()
	!!} 
@stop