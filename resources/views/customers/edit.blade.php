@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('header')
	<h1> Edit Customer <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admins_index') }}"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="{{ route('customers_index') }}"> Customers</a></li>
		<li class="active">Edit</li>
	</ol>
@stop
@section('content')
    {!! Form::open(['action' => 'CustomersController@postEdit', 'class'=>'form-horizontal','role'=>"form"]) !!}
        {!! csrf_field() !!}
        {!! Form::hidden('customer_id',$customers->id) !!}
    <!-- Custom tabs (Charts with tabs)-->
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs pull-right">
        <li><a href="#customer-account" data-toggle="tab">Account Setup</a></li>
        <li><a href="#customer-delivery" data-toggle="tab">Delivery Setup</a></li>
        <li class="active"><a href="#customer-instore" data-toggle="tab">In-Store Only</a></li>
        <li class="pull-left header"><i class="fa fa-inbox"></i> Customer Form</li>
        </ul>
        <div class="tab-content">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="customer-instore" style="position: relative;">
                <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Location <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::select('company_id',$companies , $customers->company_id, ['class'=>'form-control']) !!}
                        @if ($errors->has('company_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('company_id') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Phone <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::text('phone', $customers->phone, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Last Name <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::text('last_name', $customers->last_name, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('last_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> 
                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">First Name <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::text('first_name', $customers->first_name, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('first_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> 
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Email</label>

                    <div class="col-md-6">
                        {!! Form::email('email', $customers->email, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> 
                <div class="form-group{{ $errors->has('marks') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Shirt Marks <span class="text text-danger">*</span></label>

                    <div class="col-md-2">
                        {!! Form::text('marks[0][mark]', (isset($marks[0])) ? $marks[0]['mark'] : null, ['class'=>'form-control', 'placeholder'=>'','disabled'=>'true']) !!}

                    </div>
                    <div class="col-md-2">
                    	{!! Form::hidden('marks[1][id]', (isset($marks[1])) ? $marks[1]['id'] : null) !!}
                        {!! Form::text('marks[1][mark]', (isset($marks[1])) ? $marks[1]['mark'] : null, ['class'=>'form-control', 'placeholder'=>'']) !!}

                    </div>
                    <div class="col-md-2">
                    	{!! Form::hidden('marks[2][id]', (isset($marks[2])) ? $marks[2]['id'] : null) !!}
                        {!! Form::text('marks[2][mark]', (isset($marks[2])) ? $marks[2]['mark'] : null, ['class'=>'form-control', 'placeholder'=>'']) !!}
    
                    </div>
                </div> 
 

                <div class="form-group{{ $errors->has('starch') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Shirt Starch Preferrence</label>

                    <div class="col-md-6">
                        {!! Form::select('starch', $starch , $customers->starch, ['class'=>'form-control']) !!}
                        @if ($errors->has('starch'))
                            <span class="help-block">
                                <strong>{{ $errors->first('starch') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  
                <div class="form-group{{ $errors->has('hanger') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Shirt Finish</label>

                    <div class="col-md-6">
                        {!! Form::select('hanger', $hanger, $customers->shirt, ['class'=>'form-control']) !!}
                        @if ($errors->has('hanger'))
                            <span class="help-block">
                                <strong>{{ $errors->first('hanger') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  

                <div class="form-group{{ $errors->has('important_memo') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Important Memo</label>

                    <div class="col-md-6">
                        {!! Form::text('important_memo', (old('important_memo')) ? old('important_memo') : $customers->important_memo, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('important_memo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('important_memo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  
                <div class="form-group{{ $errors->has('invoice_memo') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Invoice Memo</label>

                    <div class="col-md-6">
                        {!! Form::text('invoice_memo', (old('invoice_memo')) ? old('invoice_memo') : $customers->invoice_memo, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('invoice_memo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('invoice_memo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  
  

            </div>
            <div class="chart tab-pane" id="customer-delivery" style="position: relative;">
                <div class="form-group{{ $errors->has('delivery') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Delivery Customer? <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::select('delivery',$delivery , (old('delivery')) ? old('delivery') : $customers->delivery, ['class'=>'form-control']) !!}
                        @if ($errors->has('delivery'))
                            <span class="help-block">
                                <strong>{{ $errors->first('delivery') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  
                <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Mobile</label>

                    <div class="col-md-6">
                        {!! Form::text('mobile', (old('mobile')) ? old('mobile') : $customers->mobile, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('mobile'))
                            <span class="help-block">
                                <strong>{{ $errors->first('mobile') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Street</label>

                    <div class="col-md-6">
                        {!! Form::text('street', (old('street')) ? old('street') : $customers->street, ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('street'))
                            <span class="help-block">
                                <strong>{{ $errors->first('street') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('suite') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Suite</label>

                    <div class="col-md-6">
                        {!! Form::text('suite', old('suite'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('suite'))
                            <span class="help-block">
                                <strong>{{ $errors->first('suite') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">City</label>

                    <div class="col-md-6">
                        {!! Form::text('city', old('city'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('city'))
                            <span class="help-block">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Zipcode</label>

                    <div class="col-md-6">
                        {!! Form::text('zipcode', old('zipcode'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('zipcode'))
                            <span class="help-block">
                                <strong>{{ $errors->first('zipcode') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('concierge_contact') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Concierge Contact</label>

                    <div class="col-md-6">
                        {!! Form::text('concierge_contact', old('concierge_contact'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('concierge_contact'))
                            <span class="help-block">
                                <strong>{{ $errors->first('concierge_contact') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('concierge_number') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Concierge Number</label>

                    <div class="col-md-6">
                        {!! Form::text('concierge_number', old('concierge_number'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('concierge_number'))
                            <span class="help-block">
                                <strong>{{ $errors->first('concierge_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('special_instructions') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Special Instructions</label>

                    <div class="col-md-6">
                        {!! Form::textarea('special_instructions', old('special_instructions'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('special_instructions'))
                            <span class="help-block">
                                <strong>{{ $errors->first('special_instructions') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                
            </div>
            <div class="chart tab-pane" id="customer-account" style="position: relative;">
                <div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Account Customer? <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::select('account',$account , '1', ['class'=>'form-control']) !!}
                        @if ($errors->has('account'))
                            <span class="help-block">
                                <strong>{{ $errors->first('account') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <input class="btn btn-lg btn-primary pull-right" type="submit" value="Save"/>
        </div>
    </div><!-- /.nav-tabs-custom -->
    {!! Form::close() !!}
@stop