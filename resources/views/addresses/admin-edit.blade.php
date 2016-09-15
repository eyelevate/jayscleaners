@extends($layout)

@section('stylesheets')

@stop

@section('scripts')
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>

@stop

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Address Form</div>
                <div class="panel-body">
                    {!! Form::open(['action' => 'AddressesController@postAdminEdit', 'class'=>'form-horizontal','role'=>"form"]) !!}
                        {!! csrf_field() !!}
                        {!! Form::hidden('id',$addresses->id) !!}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Address Name <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ (old('name')) ? old('name') : $addresses->name }}" placeholder="e.g. My Home">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Street Address <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="street" value="{{ (old('street')) ? old('street') : $addresses->street }}" placeholder="e.g. 12345 1st Ave NE">

                                @if ($errors->has('street'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('street') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('suite') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Suite / Apt #</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="suite" value="{{ (old('suite')) ? old('suite') : $addresses->suite }}" placeholder="e.g. 201b">

                                @if ($errors->has('suite'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('suite') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">City <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control"  name="city" value="{{ (old('city')) ? old('city') : $addresses->city }}" placeholder="e.g. Seattle">

                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">State <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <?php
                                $state = old('state') ? old('state') : $addresses->state;

                                ?>
                                {{ Form::select('state',$states,$state,['class'=>'form-control']) }}
                                @if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Zipcode <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="zipcode" value="{{ (old('zipcode')) ? old('zipcode') : $addresses->zipcode }}" placeholder="e.g. 98115">

                                @if ($errors->has('zipcode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('concierge_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Contact Name <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="concierge_name" value="{{ (old('concierge_name')) ? old('concierge_name') : $addresses->concierge_name }}" placeholder="Name of: caretaker, concierge">

                                @if ($errors->has('concierge_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('concierge_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('concierge_number') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Contact Phone # <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="concierge_number" data-mask="(000) 000-0000" value="{{ (old('concierge_number')) ? old('concierge_number') : $addresses->concierge_number }}" placeholder="format (XXX) XXX-XXXX">

                                @if ($errors->has('concierge_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('concierge_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4 clearfix">
                            	<a href="{{ route('address_index') }}" class="btn btn-danger btn-lg">Cancel</a>
                                <button type="submit" class="btn btn-lg btn-primary pull-right">Edit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop