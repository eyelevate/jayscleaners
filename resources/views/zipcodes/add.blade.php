@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<br/>
{!! Form::open(['action' => 'ZipcodesController@postAdd','role'=>"form"]) !!}
	<div class="panel panel-default">
		<div class="panel-heading"><h4 class="panel-title">Add Zipcode</h4></div>
		<div class="panel-body" >
            <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                <label class="control-label padding-top-none">Zipcode <span style="color:#ff0000">*</span></label>

                {{ Form::text('zipcode',old('zipcode'),['class'=>'form-control']) }}
                @if ($errors->has('zipcode'))
                    <span class="help-block">
                        <strong>{{ $errors->first('zipcode') }}</strong>
                    </span>
                @endif
            </div>			
		</div>
		<div class="panel-footer">
			<a href="{{ route('zipcodes_index') }}" class="btn btn-danger">Back</a>
			<button class="btn btn-primary pull-right" type="submit">Add</button>
		</div>
	</div>
{!! Form::close() !!}
@stop