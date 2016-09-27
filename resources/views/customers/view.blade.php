@extends($layout)

@section('stylesheets')

@stop
@section('scripts')
<script type="text/javascript" src="/js/customers/view.js"></script>
@stop
@section('header')
	<div class="row clearfix">
		<!-- last 10 -->
		<a href="#" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box bg-aqua" style="padding-bottom:10px" data-toggle="modal" data-target="#last10Customers">
				<div class="inner">
					<h4>Last 10</h4>
					<p>Customers</p>
				</div>
		        <div class="icon">
		          <i class="ion-ios-list"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Rack -->
		<a href="#" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box bg-aqua" style="padding-bottom:10px">
				<div class="inner">
					<h4>Rack</h4>
					<p>Rack Invoices</p>
				</div>
		        <div class="icon">
		          <i class="ion-ios-barcode"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Pickup -->
		<a href="{{ (isset($customers)) ? route('invoices_pickup',$customers->id) : '#' }}" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box {{ (isset($customers)) ? 'bg-green' : 'bg-default'}}" style="padding-bottom:10px">
				<div class="inner">
					<h4>Pickup</h4>
					<p>Finish Invoice</p>
				</div>
		        <div class="icon">
		          <i class="ion-cash"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Drop Off -->
		<a href="{{ (isset($customers)) ? route('invoices_dropoff',$customers->id) : '#' }}" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box {{ (isset($customers)) ? 'bg-primary' : 'bg-default'}}" style="padding-bottom:10px">
				<div class="inner">
					<h4>Drop</h4>
					<p>New Invoice</p>
				</div>
		        <div class="icon">
		          <i class="ion-ios-paper"></i>
		        </div>

			</div>
		</a><!-- ./col -->


	</div>
@stop
@section('content')
	@if(isset($customers))
		{!! View::make('partials.customers.view-id')
			->with('customers',$customers)
			->with('schedules',$schedules)
			->with('invoices',$invoices)
			->render()
		!!}		
	@else
		{!! View::make('partials.customers.view') !!}
	@endif


@stop
@section('modals')
	<!-- Modals -->
	{!! View::make('partials.customers.last10')
		->with('last10',$last10)
		->render()
	 !!}
	{!! View::make('partials.customers.reprint-card') !!}
	{!! View::make('partials.customers.reprint-invoice') !!}

	<!-- Invoice data -->
	@if(isset($invoices))
		@foreach($invoices as $invoice)
		{!! View::make('partials.customers.invoice_items')
			->with('invoice_id',$invoice->id)
			->with('items',$invoice->items)
			->render()
		!!}	
		@endforeach
	@endif

@stop