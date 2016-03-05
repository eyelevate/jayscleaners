edit.blade.php@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')
	<h1> Companies Edit <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="{{ route('companies_index') }}"> Companies</a></li>
		<li class="active">Edit</li>
	</ol>
@stop
@section('content')

@stop