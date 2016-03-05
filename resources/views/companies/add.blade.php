@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')
	<h1> Companies Add <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="{{ route('companies_index') }}"> Companies</a></li>
		<li class="active">Add</li>
	</ol>
@stop
@section('content')
<!-- Add Company Form -->
{!! Form::open(['action' => 'CompaniesController@postAdd', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
<div class="box box-primary">
	<div class="box-header">
		<i class="ion ion-clipboard"></i>
		<h3 class="box-title">Add A Company</h3>
		<div class="box-tools pull-right">

		</div>
	</div><!-- /.box-header -->
	<div class="box-body">

	</div><!-- /.box-body -->
	<div class="box-footer clearfix no-border">

	</div>
</div><!-- /.box -->
{{ Form::close() }}
@stop