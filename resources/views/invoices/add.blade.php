@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')

@stop
@section('content')
<div class="row">
	<ul class=" no-padding" >
		<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none"><button type="button" id="actual_number" class="btn btn-default" style="font-size:30px; width:100%"/>--</button></li>
		@for($i=1;$i<=9;$i++)
		<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none"><button type="button" id="number-{{ $i }}" class="number btn btn-primary" style="font-size:30px; width:100%">{{ $i }}</button></li>
		@endfor
		<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none"><button type="button" id="number-0" class="number btn btn-primary" style="font-size:30px; width:100%">0</button></li>
	</ul>
</div>
<br/>
<div class="row">
	<div class="nav-tabs-custom col-lg-8 col-md-8 col-sm-8">
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

@stop

@section('modals')

@stop