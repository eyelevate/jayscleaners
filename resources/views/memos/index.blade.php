@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/css/memos/index.css">
@stop
@section('scripts')

<script src="/packages/ddslick/ddslick.min.js"></script>

<script src="/js/memos/index.js" type="text/javascript"></script>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('header')
	<h1> Memos <small>Control panel</small></h1>
	<br/>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="{{ route('admins_settings') }}"> Settings</a></li>
		<li class="active">Memos</li>
	</ol>
	<div class="row clearfix">
		<!-- Inventory Group -->
		<a href="#" class="col-lg-6 col-md-6 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-primary" style="padding-bottom:10px" data-toggle="modal" data-target="#add">
				<div class="inner">
					<h4>Add Memo</h4>
					<p>Add new memo</p>
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
					<h4>Edit Memo</h4>
					<p>Edit existing memo</p>
				</div>
		        <div class="icon">
		          <i class="ion-edit"></i>
		        </div>

			</div>
		</a><!-- ./col -->


	</div>
@stop

@section('content')
{!! Form::open(['action' => 'MemosController@postOrder','id'=>'memo-form', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Memo Selection</h3>
	</div>
	<div class="box-body clearfix">
		<ul id="memosUl" class="no-padding" style="list-style:none;">
		@if(isset($memos))
			<?php $idx = 0; ?>
			@foreach($memos as $memo)
			<?php $idx++; ?>
			<li id="memo-{{ $memo->id }}" class="memoLi alert alert-default col-lg-2 col-md-2 col-sm-4 col-xs-6" style="cursor:pointer; text-align:center; height:75px; {{ (strlen($memo->memo) < 20) ?  'padding:0px; vertical-align:middle; line-height:75px;' : null }}">
				{{ $memo->memo }}
				<!-- small box -->
				<div class="hide">
					{{ Form::hidden('id',$memo->id,['class'=>'memosId']) }}
					{{ Form::hidden('memo',$memo->memo,['class'=>'memosMemo']) }}
					{{ Form::hidden('memos['.$memo->id.'][order]',$idx,['class'=>'memosOrdered'])}}
				</div>
			</li>
			@endforeach
		@endif
		</ul>
	</div>
</div>
{!! Form::close() !!}

@stop

@section('modals')
{!! View::make('partials.memos.add') 
	->render()
!!}
{!! View::make('partials.memos.edit') 
	->render()
!!}
{!! View::make('partials.memos.delete') 
	->render()
!!}
@stop