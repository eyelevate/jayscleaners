@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')

<header id="header" class="reveal">
{!! View::make('partials.layouts.navigation_logged_out')
    ->render()
!!} 
</header>
@stop


@section('content')
	{!! Form::open(['action' => 'PagesController@postResetPassword','role'=>"form"]) !!}
	@if (count($users) > 0)
		@foreach($users as $user)
		{!! Form::hidden('id',$user->id) !!}
		@endforeach
	@endif
	
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="control-label padding-top-none">New Password</label>
                        {{ Form::password('password','',['class'=>'form-control']) }}
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                    </div>
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label class="control-label padding-top-none">Confirm Password</label>
                        {{ Form::password('password_confirmation','',['class'=>'form-control']) }}

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif

                    </div>
                    
                </div>
                <div class="panel-footer">
                	<button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
@section('modals')
    {!! View::make('partials.frontend.modals')->render() !!}
@stop