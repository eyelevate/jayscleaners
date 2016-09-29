<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Jays Cleaners</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <!-- layout stylesheets -->
    <link rel="stylesheet" href="/css/layouts/admins.css">
    @yield('stylesheets')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="{{ route('admins_index') }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>Jays</b> <small>Admin</small></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Jays Cleaners</b> <small>Admin</small></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          @yield('notifications')
          
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p>{{ Auth::user()->username }}</p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          {!! Form::open(['action' => 'CustomersController@postIndex', 'class'=>'sidebar-form','role'=>"form", 'method'=>'post']) !!}
            {!! csrf_field() !!}
            <div class="input-group {{ $errors->has('search') ? ' has-error' : '' }}">
              {!! Form::text('search_query', old('search'), ['id'=>'search','class'=>'form-control', 'style'=>'font-size:20px', 'placeholder'=>'Search...']) !!}
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
            <div>
              @if ($errors->has('search'))
                  <span class="help-block">
                      <strong>{{ $errors->first('search') }}</strong>
                  </span>
              @endif
            </div>
          {{ Form::close() }}
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="{{ (Request::is('invoices') || Request::is('invoices/add') || Request::is('invoices/rack')) ? 'active' : '' }} treeview">
              <a href="#">
                <i class="fa fa-files-o"></i> <span>Invoice</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="{{ Request::is('invoices/dropoff') ? 'active' : '' }}"><a href="{{ route('invoices_dropoff',NULL) }}"><i class="fa fa-circle-o"></i> New Invoice</a></li>
                <li class="{{ Request::is('invoices/rack') ? 'active' : '' }}"><a href="{{ route('invoices_rack') }}"><i class="fa fa-circle-o"></i> Rack</a></li>
                <li class="{{ Request::is('invoices') ? 'active' : '' }}"><a href="{{ route('invoices_index') }}"><i class="fa fa-circle-o"></i> History</a></li>
              </ul>
            </li>
            <li class="{{ (Request::is('admins/overview') || Request::is('admins/add')) ? 'active' : '' }} treeview">
              <a href="#">
                <i class="fa fa-user"></i> <span>Admins</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="{{ Request::is('admins/overview') ? 'active' : '' }}"><a href="{{ route('admins_overview') }}"><i class="fa fa-circle-o"></i> Overview</a></li>
                <li class="{{ Request::is('admins/add') ? 'active' : '' }}"><a href="{{ route('admins_add') }}"><i class="fa fa-circle-o"></i> Add</a></li>
              </ul>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-users"></i> <span>Customers</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="{{ Request::is('customers') ? 'active' : '' }}"><a href="{{ route('customers_index') }}"><i class="fa fa-circle-o"></i> View</a></li>
                <li class="{{ Request::is('customers/add') ? 'active' : '' }}"><a href="{{ route('customers_add') }}"><i class="fa fa-circle-o"></i> Add</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-truck"></i>
                <span>Delivery</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('delivery_overview') }}"><i class="fa fa-circle-o"></i> Overview</a></li>
                <li><a href="{{ route('delivery_new',0)}}"><i class="fa fa-circle-o"></i> New Delivery</a></li>
                <li><a href="{{ route('zipcodes_index') }}"><i class="fa fa-circle-o"></i> Zipcodes</a></li>
                <li><a href="{{ route('delivery_setup') }}"><i class="fa fa-circle-o"></i> Setup</a></li>
              </ul>
            </li>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          @yield('header')

        </section>

        <!-- Main content -->
        <section class="content">
          @include('flash::message')
          @yield('content')
          @yield('modals')
         

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="http://eyelevate.com">Eyelevate</a>.</strong> All rights reserved.
      </footer>

      <!-- Control Sidebar -->
      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="/packages/AdminLTE-2.3.0/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="/packages/AdminLTE-2.3.0/bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="/packages/AdminLTE-2.3.0/plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="/packages/AdminLTE-2.3.0/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="/packages/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/packages/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="/packages/AdminLTE-2.3.0/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="/packages/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="/packages/AdminLTE-2.3.0/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="/packages/AdminLTE-2.3.0/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="/packages/AdminLTE-2.3.0/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="/packages/AdminLTE-2.3.0/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/packages/AdminLTE-2.3.0/dist/js/app.min.js"></script>

    <!-- ChartJS 1.0.1 -->
    <script src="/packages/AdminLTE-2.3.0/plugins/chartjs/Chart.min.js"></script>

    <script src="/packages/AdminLTE-2.3.0/dist/js/demo.js"></script>
    <script src="/js/layouts/admins.js" type="text/javascript"></script>
    @yield('scripts')
  </body>
</html>
