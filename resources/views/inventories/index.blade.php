@extends($layout)
@section('stylesheets')

@stop
@section('scripts')
<script src="/js/inventories/index.js" type="text/javascript"></script>
@stop
@section('header')
	<h1> Inventory <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li class="active">Inventory</li>
	</ol>
	<div class="row clearfix">
		<!-- Inventory Group -->
		<a href="#" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box bg-primary" style="padding-bottom:10px" data-toggle="modal" data-target="#group-add">
				<div class="inner">
					<h4>Add Group</h4>
					<p>Add inventory group</p>
				</div>
		        <div class="icon">
		          <i class="ion-ios-plus-outline"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Inventory Group Edit -->
		<a id="inventory-edit" href="#" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box bg-yellow" style="padding-bottom:10px" data-toggle="modal" data-target="#group-edit">
				<div class="inner">
					<h4>Edit Group</h4>
					<p>Edit inventory group</p>
				</div>
		        <div class="icon">
		          <i class="ion-edit"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Inventory Item -->
		<a href="#" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box bg-primary" style="padding-bottom:10px" data-toggle="modal" data-target="#item-add">
				<div class="inner">
					<h4>Add Item</h4>
					<p>Add inventory item</p>
				</div>
		        <div class="icon">
		          <i class="ion-plus"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Inventory Item Edit -->
		<a id="inventory-item-edit" href="#" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box bg-yellow" style="padding-bottom:10px" data-toggle="modal" data-target="#item-edit">
				<div class="inner">
					<h4>Edit Item</h4>
					<p>Edit inventory item</p>
				</div>
		        <div class="icon">
		          <i class="ion-edit"></i>
		        </div>

			</div>
		</a><!-- ./col -->

	</div>
@stop
@section('content')
<div class="nav-tabs-custom">
	{!! Form::open(['action' => 'InventoriesController@postOrder', 'class'=>'form-horizontal','role'=>"form",'id'=>'group-form']) !!}
		{!! csrf_field() !!}
		<!-- Tabs within a box -->
		<ul id="inventory-group-ul" class="nav nav-tabs">
			<?php $idx = -1;?>
			@if(isset($inventories))
				@foreach($inventories as $inventory)
				<?php $idx++;?>
				<li class="{{ ($idx == 0) ? 'active' : '' }}" class="cursor:pointer">
					<a href="#sales-dryclean" data-toggle="tab">{{ $inventory->name }}</a>
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
	{!! Form::close() !!}
	<div class="tab-content no-padding">
		<!-- Morris chart - Sales -->
		<div class="chart tab-pane active" id="sales-dryclean" style="position: relative; min-height: 300px;"></div>
		<div class="chart tab-pane" id="sales-laundry" style="position: relative; min-height: 300px;"></div>
		<div class="chart tab-pane" id="sales-alteration" style="position: relative; min-height: 300px;"></div>
		<div class="chart tab-pane" id="sales-household" style="position: relative; min-height: 300px;"></div>
		<div class="chart tab-pane" id="sales-other" style="position: relative; min-height: 300px;"></div>
	</div>
</div><!-- /.nav-tabs-custom -->


@stop
@section('modals')

	{!! View::make('partials.inventories.group-add') 
		->with('companies',$companies)
		->render()
	!!}
	{!! View::make('partials.inventories.group-edit') 
		->with('companies',$companies)
		->render()
	!!}
	{!! View::make('partials.inventories.item-add')
		->with('companies',$companies)
		->render()
	 !!}
	{!! View::make('partials.inventories.item-edit')
		->with('companies',$companies)
		->render()
	 !!}
	{!! View::make('partials.inventories.group-delete')
		->render()
	 !!}
	{!! View::make('partials.inventories.item-delete')
		->render()
	 !!}
@stop