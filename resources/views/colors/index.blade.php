@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/css/colors/index.css">
@stop
@section('scripts')

<script src="/packages/ddslick/ddslick.min.js"></script>

<script src="/js/colors/index.js" type="text/javascript"></script>
@stop

@section('header')
	<h1> Colors <small>Control panel</small></h1>
	<br/>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="{{ route('admins_settings') }}"> Settings</a></li>
		<li class="active">Colors</li>
	</ol>
	<div class="row clearfix">
		<!-- Inventory Group -->
		<a href="#" class="col-lg-6 col-md-6 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-primary" style="padding-bottom:10px" data-toggle="modal" data-target="#add">
				<div class="inner">
					<h4>Add Color</h4>
					<p>Add new color</p>
				</div>
		        <div class="icon">
		          <i class="ion-ios-plus-outline"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Inventory Group Edit -->
		<a id="inventory-edit" href="#" class="col-lg-6 col-md-6 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow" style="padding-bottom:10px" data-toggle="modal" data-target="#edit">
				<div class="inner">
					<h4>Edit Color</h4>
					<p>Edit existing color</p>
				</div>
		        <div class="icon">
		          <i class="ion-edit"></i>
		        </div>

			</div>
		</a><!-- ./col -->


	</div>
@stop

@section('content')
{!! Form::open(['action' => 'ColorsController@postOrder','id'=>'color-form', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Color Selection</h3>
	</div>
	<div class="box-body clearfix">
		<ul id="colorsUl" class="no-padding" style="list-style:none;">
		@if(isset($colors))
			<?php $idx = 0; ?>
			@foreach($colors as $color)
				<?php $idx++; ?>
			<li id="color-{{ $color->id }}" class=" col-lg-1 col-md-1 col-xs-1" style="cursor:pointer;">
				<!-- small box -->
				<a href="#" class="small-box "style="background-color:{{ $color->color }};">
					{{ Form::hidden('id',$color->id,['class'=>'colorsId']) }}
					{{ Form::hidden('color',$color->color,['class'=>'colorsColor']) }}
					{{ Form::hidden('name', $color->name,['class'=>'colorsName']) }}
					{{ Form::hidden('color['.$color->id.'][order]',$idx)}}
				</a>
			</li>
			@endforeach
		@endif
		</ul>
	</div>
</div>
{!! Form::close() !!}

@stop

@section('modals')
{!! View::make('partials.colors.add') 
	->render()
!!}
{!! View::make('partials.colors.edit') 
	->render()
!!}
{!! View::make('partials.colors.delete') 
	->render()
!!}
@stop