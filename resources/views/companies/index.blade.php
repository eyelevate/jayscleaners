@extends($layout)
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11,af-2.1.1,b-1.1.2,r-2.0.2/datatables.min.css"/>
@stop
@section('scripts')

<script type="text/javascript" src="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11,af-2.1.1,b-1.1.2,r-2.0.2/datatables.min.js"></script>
<script type="text/javascript" src="/js/admins/overview.js"></script>
@stop
@section('header')
	<h1> Companies Home <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="{{ route('admins_settings') }}"> Settings</a></li>
		<li class="active">Company</li>
	</ol>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Companies</h3>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table id="admin_search" class="table table-striped table-bordered" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		            	<th>Id</th>
		            	<th>Name</th>
		                <th>Phone</th>
		                <th>Email</th>
		                <th>Street</th>
		                <th>City</th>
		                <th>Zip</th>
		                <th>Created</th>
		                <th>Action</th>
		            </tr>
		        </thead>
		        <tfoot>
		            <tr>
		            	<th>Id</th>
		            	<th>Name</th>
		                <th>Phone</th>
		                <th>Email</th>
		                <th>Street</th>
		                <th>City</th>
		                <th>Zip</th>
		                <th>Created</th>
		                <th>Action</th>
		            </tr>
		        </tfoot>
		        <tbody>
		        	@if(isset($companies))
			        	@foreach($companies as $c)
			        		<tr>
			        			<td>{{ $c->id }}</td>
			        			<td>{{ $c->name }}</td>
			        			<td>{{ $c->phone }}</td>
			        			<td>{{ $c->email }}</td>
			        			<td>{{ $c->street }}</td>
			        			<td>{{ $c->city }}</td>
			        			<td>{{ $c->zip }}</td>
			        			<td>{{ $c->created_at }}</td>
			        			<td>
			        				<a class="btn btn-info" href="{{ route('companies_edit',$c->id) }}">edit</a> 

			        			</td>
			        		</tr>
			        	@endforeach
		        	@endif
		        </tbody>
		    </table>
		</div>
	</div>
	<div class="panel-footer clearfix">
		<a class="btn btn-primary pull-right" href="{{ route('companies_add') }}">New Company</a>
	</div>
</div>
@stop