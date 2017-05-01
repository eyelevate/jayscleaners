@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')
<header id="header" class="reveal">
{!! View::make('partials.layouts.navigation-nodelivery')
    ->render()
!!}
</header>
@stop


@section('content')
	@if (count($transactions) > 0)
		@foreach($transactions as $transaction)
		{!! Form::open(['action' => 'AccountsController@postOneTimeFinish', 'class'=>'form-horizontal','role'=>"form"]) !!}
			{!! Form::hidden('company_id',$transaction->company_id) !!}
			<section class="wrapper style3 container special">
				<div id="store_hours" class="row">
					<header class="clearfix col-xs-12 col-sm-12" style="">
						<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">One Time Payment Method</h3>
					</header>
					<section class="clearfix col-xs-12 col-sm-12">
						
			       	 	
				        <div class="form-group{{ $errors->has('total') ? ' has-error' : '' }}">
				            <label class="control-label col-md-4 padding-top-none">Total Due</label>

				            <div class="col-md-6">
				            	<p style="text-align:left;"><strong>{{ money_format('$%i',$transaction->total) }}</strong></p>
				               
				                @if ($errors->has('total'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('total') }}</strong>
				                    </span>
				                @endif
				            </div>
				        </div>
				        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				            <label class="control-label col-md-4 padding-top-none">Email <span class="text text-danger">*</span></label>

				            <div class="col-md-6">
				                {!! Form::text('email', old('email'), ['class'=>'form-control','placeholder'=>'xxxx@xxxxx.com']) !!}
				                @if ($errors->has('email'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('email') }}</strong>
				                    </span>
				                @endif
				            </div>
				        </div>	
				        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				            <label class="control-label col-md-4 padding-top-none">Name On Card <span class="text text-danger">*</span></label>

				            <div class="col-md-6">
				                {!! Form::text('name', old('name'), ['class'=>'form-control','maxlength'=>'20','placeholder'=>'John Doe']) !!}
				                @if ($errors->has('name'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('name') }}</strong>
				                    </span>
				                @endif
				            </div>
				        </div>	
				        <div class="form-group{{ $errors->has('card_number') ? ' has-error' : '' }}">
				            <label class="control-label col-md-4 padding-top-none">Credit Card # <span class="text text-danger">*</span></label>

				            <div class="col-md-6">
				                {!! Form::text('card_number', old('card_number'), ['class'=>'form-control','maxlength'=>'20','placeholder'=>'XXXXXXXXXXXXXXXX']) !!}
				                @if ($errors->has('card_number'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('card_number') }}</strong>
				                    </span>
				                @endif
				            </div>
				        </div>	
				        <div class="form-group{{ $errors->has('exp_month') ? ' has-error' : '' }}">
				            <label class="control-label col-md-4 padding-top-none">Expiration Month <span class="text text-danger">*</span></label>

				            <div class="col-md-6">
				                {!! Form::text('exp_month', old('exp_month'), ['class'=>'form-control','placeholder'=>'XX','maxlength'=>'2']) !!}
				                @if ($errors->has('exp_month'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('exp_month') }}</strong>
				                    </span>
				                @endif
				            </div>
				        </div>	
				        <div class="form-group{{ $errors->has('exp_year') ? ' has-error' : '' }}">
				            <label class="control-label col-md-4 padding-top-none">Expiration Year <span class="text text-danger">*</span></label>

				            <div class="col-md-6">
				                {!! Form::text('exp_year', old('exp_year'), ['class'=>'form-control','placeholder'=>'XXXX','maxlength'=>'4']) !!}
				                @if ($errors->has('exp_year'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('exp_year') }}</strong>
				                    </span>
				                @endif
				            </div>
				        </div>	
				        <div class="form-group{{ $errors->has('cvv') ? ' has-error' : '' }}">
				            <label class="control-label col-md-4 padding-top-none">CVV <span class="text text-danger">*</span></label>

				            <div class="col-md-6">
				                {!! Form::text('cvv', old('cvv'), ['class'=>'form-control','placeholder'=>'XXX','maxlength'=>'4']) !!}
				                @if ($errors->has('cvv'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('cvv') }}</strong>
				                    </span>
				                @endif
				            </div>
				        </div>	
				        <div class="form-group">
				        	<div class="col-md-offset-4" style="padding-left:15px;">
				        		<button class="btn btn-primary pull-left" type="submit" data-toggle="modal" data-target="#loading">Make Payment</button>
				        	</div>
			       	 	</div>
					</section>
				</div>
			</section>
		{!! Form::close() !!}
		@endforeach
	@endif
@stop
@section('modals')
	{!! View::make('partials.frontend.modals')->render() !!}	
@stop