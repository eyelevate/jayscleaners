@extends($layout)

@section('stylesheets')

@stop

@section('scripts')
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop

@section('content')
<br/>
{!! Form::open(['action' => 'CardsController@postAdminsAdd', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
<div class="panel panel-default">
    <div class="panel-heading">Credit Card Form</div>
    <div class="panel-body">  
        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label padding-top-none">Billing First Name <span style="color:#ff0000">*</span></label>

            <div class="col-md-6">
                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. John">

                @if ($errors->has('first_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('first_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label padding-top-none">Billing Last Name <span style="color:#ff0000">*</span></label>

            <div class="col-md-6">
                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Doe">

                @if ($errors->has('last_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label padding-top-none">Billing Street <span style="color:#ff0000">*</span></label>

            <div class="col-md-6">
                <input type="text" class="form-control" name="street" value="{{ old('street') }}" placeholder="e.g. 12345 1st Ave. N">

                @if ($errors->has('street'))
                    <span class="help-block">
                        <strong>{{ $errors->first('street') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('suite') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label padding-top-none">Billing Suite</label>

            <div class="col-md-6">
                <input type="text" class="form-control"  name="suite" value="{{ old('suite') }}" placeholder="e.g. 201A">

                @if ($errors->has('suite'))
                    <span class="help-block">
                        <strong>{{ $errors->first('suite') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label padding-top-none">Billing City <span style="color:#ff0000">*</span></label>

            <div class="col-md-6">
                <input type="text" class="form-control"  name="city" value="{{ old('city') }}" placeholder="e.g. Seattle">

                @if ($errors->has('city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label padding-top-none">Billing State <span style="color:#ff0000">*</span></label>

            <div class="col-md-6">
                
                {{ Form::select('state',$states,old('state'),['class'=>'form-control']) }}
                @if ($errors->has('state'))
                    <span class="help-block">
                        <strong>{{ $errors->first('state') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label padding-top-none">Billing Zipcode <span style="color:#ff0000">*</span></label>

            <div class="col-md-6">
                <input type="text" class="form-control" name="zipcode" value="{{ old('zipcode') }}" placeholder="e.g. 98115">

                @if ($errors->has('zipcode'))
                    <span class="help-block">
                        <strong>{{ $errors->first('zipcode') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('card') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label padding-top-none">Credit Card Number <span style="color:#ff0000">*</span></label>

            <div class="col-md-6">
                <input type="text" class="form-control" name="card" value="{{ old('card') }}" placeholder="format. XXXX XXXX XXXX XXXX">

                @if ($errors->has('card'))
                    <span class="help-block">
                        <strong>{{ $errors->first('card') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('year') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label padding-top-none">Expiration <span style="color:#ff0000">*</span></label>

            <div class="col-md-4">
                <input type="text" class="form-control" name="year" value="{{ old('year') }}" placeholder="format. YYYY">

                @if ($errors->has('year'))
                    <span class="help-block">
                        <strong>{{ $errors->first('year') }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="month" value="{{ old('month') }}" placeholder="format. MM">

                @if ($errors->has('month'))
                    <span class="help-block">
                        <strong>{{ $errors->first('month') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="panel-footer">
    	<a href="{{ route('cards_admins_index',$customer_id) }}" class="btn btn-danger btn-lg">Cancel</a>
        <button type="submit" data-toggle="modal" data-target="#loading" class="btn btn-lg btn-primary pull-right">Add</button>
    </div>
</div>
{!! Form::close() !!}
@stop
@section('modals')
    {!! View::make('partials.frontend.modals')->render() !!}
@stop