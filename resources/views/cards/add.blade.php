@extends($layout)

@section('stylesheets')

@stop

@section('scripts')
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>

@stop

@section('navigation')

    <header id="header" class="reveal">
    {!! View::make('partials.layouts.navigation_logged_in')
        ->render()
    !!}
    </header>
@stop


@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Credit Card Form</div>
                <div class="panel-body">
                    {!! Form::open(['action' => 'CardsController@postAdd', 'class'=>'form-horizontal','role'=>"form"]) !!}
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Billing First Name <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') ? old('first_name') : ($card_form_data) ? $card_form_data['first_name'] : '' }}" placeholder="e.g. John">

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
                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') ? old('last_name') : ($card_form_data) ? $card_form_data['last_name'] : '' }}" placeholder="e.g. Doe">

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
                                <input type="text" class="form-control" name="street" value="{{ old('street') ? old('street') : ($card_form_data) ? $card_form_data['street'] : '' }}" placeholder="e.g. 12345 1st Ave. N">

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
                                <input type="text" class="form-control"  name="suite" value="{{ old('suite') ? old('suite') : ($card_form_data) ? $card_form_data['suite'] : '' }}" placeholder="e.g. 201A">

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
                                <input type="text" class="form-control"  name="city" value="{{ old('city') ? old('city') : ($card_form_data) ? $card_form_data['city'] : '' }}" placeholder="e.g. Seattle">

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
                                
                                {{ Form::select('state',$states,(old('state')) ? old('state') : ($card_form_data) ? $card_form_data['state'] : '',['class'=>'form-control']) }}
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
                                <input type="text" class="form-control" name="zipcode" value="{{ old('zipcode') ? old('zipcode') : ($card_form_data) ? $card_form_data['zipcode'] : '' }}" placeholder="e.g. 98115">

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
                                <input type="text" class="form-control" name="card" value="{{ old('card') ? old('card') : ($card_form_data) ? $card_form_data['card_number'] : '' }}" placeholder="format. XXXX XXXX XXXX XXXX">

                                @if ($errors->has('card'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('card') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('month') || $errors->has('year') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Expiration <span style="color:#ff0000">*</span></label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="month" value="{{ old('month') ? old('month') : ($card_form_data) ? $card_form_data['exp_month'] : '' }}" placeholder="MM">

                                @if ($errors->has('month'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('month') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="year" value="{{ old('year') ? old('year') : ($card_form_data) ? $card_form_data['exp_year'] : '' }}" placeholder="YYYY">

                                @if ($errors->has('year'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('year') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4 clearfix">
                            	<a href="{{ route('cards_index') }}" class="btn btn-danger btn-lg">Cancel</a>
                                <button type="submit" data-toggle="modal" data-target="#loading" class="btn btn-lg btn-primary pull-right">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
@section('modals')
    {!! View::make('partials.frontend.modals')->render() !!}
@stop