@extends($layout)
@section('stylesheets')

@stop
@section('scripts')
	<script type="text/javascript" src="/packages/ajaxqueue/ajaxQueue.min.js"></script>
	<script type="text/javascript" src="/js/admins/reset_passwords.js"></script>
@stop
@section('header')
	<h1>Admins Reset Passwords Page <small>Control panel</small></h1>
	<ol class="breadcrumb">
	<li class="active">Admins</li>

	</ol>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<br/>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Old Password List</h3>
		</div>
		<div class="panel-body">
			
		</div>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Username</th>
						<th>Last</th>
						<th>First</th>
						<th>Email</th>
						<th>Token</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@if (count($users) > 0)
					@foreach ($users as $user)
					<tr class="reset_tr">
						<td>{{ $user->id }}</td>
						<td>{{ $user->username }}</td>
						<td>{{ $user->last_name }}</td>
						<td>{{ $user->first_name }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ $user->token }}</td>
						<td>{{ $user->status }}</td>
						<td><input type="checkbox" name="user_id" value="{{ $user->id }}" class="user_id"/></td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>

		</div>
		<div class="panel-body">
			{{ $users->links() }}
		</div>
		<div class="panel-footer">
			<button class="btn btn-info" type="button" data-toggle="modal" data-target="#send-all">Reset All Users</button>
			<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#send-selected">Reset Selected Users</button>
		</div>
	</div>
@stop
@section('modals')


	{!! View::make('partials.admins.send_all_reset')->render() !!}	
	{!! View::make('partials.admins.send_selected_reset')->render() !!}				
@stop