@extends($layout)
@section('stylesheets')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11,af-2.1.1,b-1.1.2,r-2.0.2/datatables.min.css"/>
@stop
@section('scripts')

<script type="text/javascript" src="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11,af-2.1.1,b-1.1.2,r-2.0.2/datatables.min.js"></script>
<script type="text/javascript" src="/js/admins/overview.js"></script>
@stop
@section('header')
	<h1> Customers <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li class="active">Customers</li>
	</ol>
@stop
@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Customer Search Results</h3>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table id="admin_search" class="table table-striped table-bordered" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			            	<th>Id</th>
			            	<th>Loc.</th>
			                <th>Last</th>
			                <th>First</th>
			                <th>Username</th>
			                <th>Email</th>
			                <th>Phone</th>
			                <th>Created</th>
			                <th>Action</th>
			            </tr>
			        </thead>
			        <tfoot>
			            <tr>
			            	<th>Id</th>
			            	<th>Loc.</th>
			                <th>Last</th>
			                <th>First</th>
			                <th>Username</th>
			                <th>Email</th>
			                <th>Phone</th>
			                <th>Created</th>
			                <th>Action</th>
			            </tr>
			        </tfoot>
			        <tbody>
			        	@if(isset($customers))
				        	@foreach($customers as $cust)
				        		<tr>
				        			<td>{{ $cust->id }}</td>
				        			<td>{{ $cust->company_id }}</td>
				        			<td>{{ $cust->last_name }}</td>
				        			<td>{{ $cust->first_name }}</td>
				        			<td>{{ $cust->username }}</td>
				        			<td>{{ $cust->email }}</td>
				        			<td>{{ $cust->phone }}</td>
				        			<td>{{ $cust->created_on }}</td>
				        			<td>
				        				<a class="btn btn-info" href="{{ route('customers_edit',$cust->id) }}">edit</a> 
				        				<a class="btn btn-danger" href="{{ route('customers_delete',$cust->id) }}">delete</a>
				        			</td>
				        		</tr>
				        	@endforeach
			        	@endif
			        </tbody>
			    </table>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<a class="btn btn-primary pull-right" href="{{ route('customers_add') }}">New Customer</a>
		</div>
	</div>
@stop