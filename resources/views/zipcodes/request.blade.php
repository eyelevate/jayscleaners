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
{!! Form::open(['action' => 'ZipcodesController@postRequest', 'class'=>'form-horizontal','role'=>"form"]) !!}
<section class="wrapper style2 container special-alt no-background-image">
    <div class="">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <header>
                <h2>Request Form</h2>
            </header>
            <p>Please fill out this request form to get us out and deliver to your area. We take every request very seriously and appreciate your understanding of this matter. Thank you! </p>
            <section>
	            <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
	                <label class="control-label">Zipcode</label>

                    {!! Form::text('zipcode', old('zipcode') ? old('zipcode') : $zipcode, ['placeholder'=>'','class'=>'form-control', 'style'=>'background-color:#ffffff; color: #000000;']) !!}
                    @if ($errors->has('zipcode'))
                        <span class="help-block">
                            <strong>{{ $errors->first('zipcode') }}</strong>
                        </span>
                    @endif

	            </div>
	            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
	                <label class="control-label">Full Name</label>

                    {!! Form::text('name', old('name'), ['placeholder'=>'','class'=>'form-control', 'style'=>'background-color:#ffffff; color: #000000;']) !!}
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif

	            </div>
	            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
	                <label class="control-label">Email Address</label>

                    {!! Form::email('email', old('email'), ['placeholder'=>'','class'=>'form-control', 'style'=>'background-color:#ffffff; color: #000000;']) !!}
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif

	            </div>
	            <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
	                <label class="control-label">Additional Comments</label>

                    {!! Form::textarea('comment', old('comment'), ['placeholder'=>'','class'=>'form-control', 'style'=>'background-color:#ffffff; color: #000000;']) !!}
                    @if ($errors->has('comment'))
                        <span class="help-block">
                            <strong>{{ $errors->first('comment') }}</strong>
                        </span>
                    @endif

	            </div>
            </section>	

            <footer>
                <ul class="buttons">
                    <li><button type="submit" class="button">Submit Request</button></li>
                </ul>
            </footer>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
            <ul class="featured-icons">
                <li><span class="icon fa-clock-o"><span class="label">Feature 1</span></span></li>
                <li><span class="icon fa-volume-up"><span class="label">Feature 2</span></span></li>
                <li><span class="icon fa-laptop"><span class="label">Feature 3</span></span></li>
                <li><span class="icon fa-inbox"><span class="label">Feature 4</span></span></li>
                <li><span class="icon fa-lock"><span class="label">Feature 5</span></span></li>
                <li><span class="icon fa-cog"><span class="label">Feature 6</span></span></li>
            </ul>
        </div>
    </div>
</section>
{!! Form::close() !!}
@stop
@section('modals')
    {!! View::make('partials.frontend.modals')->render() !!}
@stop