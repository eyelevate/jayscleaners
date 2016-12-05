@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')
<header id="header" class="reveal">
@if (Auth::check())
{!! View::make('partials.layouts.navigation_logged_in')
    ->render()
!!}
@else
{!! View::make('partials.layouts.navigation_logged_out')
    ->render()
!!}
@endif
</header>
@stop


@section('content')
	{!! Form::open(['action' => 'AccountsController@postOneTimePayment', 'class'=>'form-horizontal','role'=>"form"]) !!}
	<section class="wrapper style3 container special">
		<div id="store_hours" class="row">
			<header class="clearfix col-xs-12 col-sm-12" style="">
				<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">One Time Payment Method</h3>
			</header>
			<section class="clearfix col-xs-12 col-sm-12">
				<div class="form-group{{ $errors->has('transaction_id') ? ' has-error' : '' }}">
		            <label class="control-label col-md-4 padding-top-none">Invoice # <span class="text text-danger">*</span></label>

		            <div class="col-md-6">
		                {!! Form::text('transaction_id', old('transaction_id'), ['class'=>'form-control', 'placeholder'=>'']) !!}
		                @if ($errors->has('transaction_id'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('transaction_id') }}</strong>
		                    </span>
		                @endif
		            </div>
		        </div>
		        <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
		            <label class="control-label col-md-4 padding-top-none">Customer # <span class="text text-danger">*</span></label>

		            <div class="col-md-6">
		                {!! Form::text('customer_id', old('customer_id'), ['class'=>'form-control', 'placeholder'=>'']) !!}
		                @if ($errors->has('customer_id'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('customer_id') }}</strong>
		                    </span>
		                @endif
		            </div>
		        </div>

		        <div class="form-group">
		        	<div class="col-md-offset-4" style="padding-left:15px;">
		        		<button class="btn btn-default pull-left" type="submit">Search Bill</button>
		        	</div>
	       	 	</div>
			</section>
		</div>


	</section>
	{!! Form::close() !!}
@stop
@section('modals')

@stop