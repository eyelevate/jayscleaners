@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')
	<h1> Settings <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li class="active">Settings</li>
	</ol>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<div class="row">
	<a href="{{ route('companies_index') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Company</h3>
				<p>Company Information</p>
			</div>
			<div class="icon">
				<i class="ion-android-globe"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->
	<a href="{{ route('inventories_index') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Inventory</h3>
				<p>Setup Inventory</p>
			</div>
			<div class="icon">
				<i class="ion-ios-cog"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->
	<a href="{{ route('taxes_index') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Tax</h3>
				<p>Setup Taxes</p>
			</div>
			<div class="icon">
				<i class="ion-social-usd-outline"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->

	<a href="{{ route('companies_operation') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Operation</h3>
				<p>Hours & Turnaround</p>
			</div>
			<div class="icon">
				<i class="ion-ios-loop-strong"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->
</div><!-- /.row -->
<div class="row">
	<a href="{{ route('colors_index') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Colors</h3>
				<p>Setup Colors</p>
			</div>
			<div class="icon">
				<i class="ion-paintbrush"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->
	<a href="{{ route('customers_view','') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Printers</h3>
				<p>Setup Printers</p>
			</div>
			<div class="icon">
				<i class="ion-printer"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->
	<a href="#" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Users</h3>
				<p>Setup Users</p>
			</div>
			<div class="icon">
				<i class="ion-ios-personadd"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->

	<a href="{{ route('memos_index') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Memo</h3>
				<p>Setup Memo</p>
			</div>
			<div class="icon">
				<i class="ion-ios-compose"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->
	<a href="{{ route('admins_reset_passwords') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Reset Pswds</h3>
				<p>Manage Reset Password</p>
			</div>
			<div class="icon">
				<i class="ion-ios-compose"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->
	<a href="{{ route('accounts_index') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Accounts</h3>
				<p>Manage Accounts</p>
			</div>
			<div class="icon">
				<i class="ion-ios-compose"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->
	<a href="{{ route('discounts_index') }}" class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>Discounts</h3>
				<p>Manage Discounts</p>
			</div>
			<div class="icon">
				<i class="ion-ios-compose"></i>
			</div>
			<div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
		</div>
	</a><!-- ./col -->
</div><!-- /.row -->
@stop