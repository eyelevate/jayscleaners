@extends($layout)
@section('stylesheets')

@stop
@section('scripts')
<script src="/packages/ddslick/ddslick.min.js"></script>
<script src="/js/inventories/index.js" type="text/javascript"></script>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('header')
<h1> Inventory <small>Control panel</small></h1>
<br/>
<ol class="breadcrumb">
	<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
	<li><a href="{{ route('admins_settings') }}"> Settings</a></li>
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
	{!! Form::close() !!}

	<ul class="tab-content no-padding">
		<!-- Morris chart - Sales -->
		<?php $idx = -1;?>
		@if(isset($items))
			@foreach($items as $key => $value)
			<?php $idx++;?>
			<li class="chart tab-pane {{ ($idx == 0) ? 'active' : '' }}" id="sales-{{ $key }}" style="position: relative; min-height: 300px; padding:5px;">
				<div class="table-responsive">			
					<table class="table table-condensed table-hover table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Order</th>
								<th>Name</th>
								<th>Qty</th>
								<th>Tags</th>
								<th>Desc</th>
								<th>Price</th>
								<th>Img</th>
								<th>A.</th>
							</tr>
						</thead>
						<tbody class="sortable-tbody">
						@if(isset($value['items']))
							@foreach($value['items'] as $item)
							<tr style="cursor:pointer;" id="item_list-{{ $item->id }}" order="{{ $item->ordered }}">
								<td>{{ $item->id }}</td>
								<td class="item_order">{{ $item->ordered }}</td>
								<td>{{ $item->name }}</td>
								<td>{{ $item->quantity }}</td>
								<td>{{ $item->tags }}</td>
								<td>{{ $item->description }}</td>
								<td>{{ $item->price }}</td>
								<td>{{ $item->image }}</td>
								<td><button class="button" data-toggle="modal" data-target="#item_edit-{{ $item->id }}">Edit</button></td>
							</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
			</li>
			@endforeach
		@endif
	</ul>
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
		->with('group_select',$group_select)
		->with('icon_select',$icon_select)
		->with('tags_select',$tags_select)
		->with('quantity_select',$quantity_select)
		->render()
	 !!}
	@if(isset($items))
		@foreach($items as $key => $value)
			@if(isset($value['items']))
				@foreach($value['items'] as $item)
				{!! View::make('partials.inventories.item-edit')
					->with('companies',$companies)
					->with('item',$item)
					->with('group_select',$group_select)
					->with('icon_select',$icon_select)
					->with('tags_select',$tags_select)
					->with('quantity_select',$quantity_select)
					->render()
				!!}
				@endforeach
			@endif
		@endforeach
	@endif

	{!! View::make('partials.inventories.group-delete')
		->render()
	 !!}
	{!! View::make('partials.inventories.item-delete')
		->render()
	 !!}
@stop