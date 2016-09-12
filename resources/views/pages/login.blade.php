@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')

<header id="header" class="reveal">
    <h1 id="logo"><a href="{{ route('pages_index') }}" class="nav-colors">Jays Cleaners</a></h1>
    <nav id="nav">
        <ul>
            <li class="submenu nav-colors">
                <a href="#">About Us</a>
                <ul>
                    <li><a href="{{ route('delivery_pickup') }}" >Schedule Delivery</a></li>
                    <li><a href="left-sidebar.html">Services</a></li>
                    <li><a href="right-sidebar.html">Business Hours</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                </ul>
            </li>
            <li><a href="{{ route('pages_registration') }}" class="button special">Sign Up</a></li>
        </ul>
    </nav>
</header>
@stop


@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{  route('pages_login_post') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Username</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label padding-top-none">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" style="position:relative"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop