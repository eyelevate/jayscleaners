@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')
<h1> Update Taxes <small>Control panel</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
	<li><a href="{{ route('admins_settings') }}"> Settings</a></li>
	<li class="active">Taxes</li>
</ol>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<!-- quick email widget -->
<div class="box box-info">
	<div class="box-header">

		<h3 class="box-title">Sales Tax Rate</h3>

	</div>
	<div class="box-body">
		<div class="form-horizontal">
            <div class="form-group">
                <label class="col-md-4 control-label">Current Sales Tax</label>

                <div class="col-md-6">
                    {!! Form::text('tax', $tax['rate'], ['class'=>'form-control', 'placeholder'=>'','disabled'=>'true','style'=>'font-size:20px;']) !!}

                </div>
            </div> 				
		</div>
	</div>
	<div class="box-footer clearfix">
		<button class="pull-left btn btn-info" data-toggle="modal" data-target="#history">History</button>
		<button class="pull-right btn btn-primary" data-toggle="modal" data-target="#update">Update Tax</button>
	</div>
</div>
@stop
@section('modals')
	{!! View::make('partials.taxes.update')
		->with('companies',$companies)
		->render()
	 !!}
	{!! View::make('partials.taxes.history')
		->with('history',$history)
		->render()
	 !!}
@stop