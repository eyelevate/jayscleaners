<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Jays Cleaners</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/packages/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
    <?php echo $__env->yieldContent('stylesheets'); ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo e(route('admins_index')); ?>" class="logo">
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
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 4 messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li><!-- end message -->
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            AdminLTE Design Team
                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Developers
                            <small><i class="fa fa-clock-o"></i> Today</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Sales Department
                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Reviewers
                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">10</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 10 notifications</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-red"></i> 5 new members joined
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-user text-red"></i> You changed your username
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">View all</a></li>
                </ul>
              </li>
              <!-- Tasks: style can be found in dropdown.less -->
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                  <span class="label label-danger">9</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 9 tasks</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Design some buttons
                            <small class="pull-right">20%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">20% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Create a nice theme
                            <small class="pull-right">40%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">40% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Some task I need to do
                            <small class="pull-right">60%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">60% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Make beautiful transitions
                            <small class="pull-right">80%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">80% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all tasks</a>
                  </li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo e(Auth::user()->username); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="img-circle" alt="User Image">
                    <p>
                      <?php echo e(Auth::user()->username); ?> - Web Developer
                      <small>Member since Nov. 2012</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <form role="form" method="POST" action="<?php echo e(route('admins_logout_post')); ?>">
                        <?php echo csrf_field(); ?>

                        <input type="submit" class="btn btn-default btn-flat" value="Sign Out"/>
                      </form>
                    </div>
                  </li>
                </ul>
              </li>

            </ul>
          </div>
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
					<p><?php echo e(Auth::user()->username); ?></p>
					<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
				</div>
			</div>
			<!-- search form -->
			<?php echo Form::open(['action' => 'CustomersController@postIndex', 'class'=>'sidebar-form','role'=>"form", 'method'=>'post']); ?>

				<?php echo csrf_field(); ?>

				<div class="input-group <?php echo e($errors->has('search') ? ' has-error' : ''); ?>">
					<?php echo Form::text('search_query', old('search'), ['id'=>'search','class'=>'form-control', 'style'=>'font-size:20px', 'placeholder'=>'Search...']); ?>

					<span class="input-group-btn">
						<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
					</span>
				</div>
				<div>
					<?php if($errors->has('search')): ?>
					<span class="help-block">
						<strong><?php echo e($errors->first('search')); ?></strong>
					</span>
					<?php endif; ?>
				</div>
			<?php echo e(Form::close()); ?>

			<!-- /.search form -->
			<!-- sidebar menu: : style can be found in sidebar.less -->
			<ul class="sidebar-menu">
				<li class="header">MAIN NAVIGATION</li>
				<li class="<?php echo e((Request::is('invoices') || Request::is('invoices/add') || Request::is('invoices/rack')) ? 'active' : ''); ?> treeview">
					<a href="#">
						<i class="fa fa-files-o"></i> <span>Invoice</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo e(Request::is('invoices/dropoff') ? 'active' : ''); ?>"><a href="<?php echo e(route('invoices_dropoff', NULL)); ?>"><i class="fa fa-circle-o"></i> New Invoice</a></li>
						<li class="<?php echo e(Request::is('invoices/rack') ? 'active' : ''); ?>"><a href="<?php echo e(route('invoices_rack')); ?>"><i class="fa fa-circle-o"></i> Rack</a></li>
						<li class="<?php echo e(Request::is('invoices') ? 'active' : ''); ?>"><a href="<?php echo e(route('invoices_index')); ?>"><i class="fa fa-circle-o"></i> History</a></li>
					</ul>
				</li>
				<li class="<?php echo e((Request::is('admins/overview') || Request::is('admins/add')) ? 'active' : ''); ?> treeview">
					<a href="#">
						<i class="fa fa-dashboard"></i> <span>Admins</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo e(Request::is('admins/overview') ? 'active' : ''); ?>"><a href="<?php echo e(route('admins_overview')); ?>"><i class="fa fa-circle-o"></i> Overview</a></li>
						<li class="<?php echo e(Request::is('admins/add') ? 'active' : ''); ?>"><a href="<?php echo e(route('admins_add')); ?>"><i class="fa fa-circle-o"></i> Add</a></li>
					</ul>
				</li>
			</ul>
		</section>
		<!-- /.sidebar -->
	</aside>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header" style="margin-top:0px; padding:0px;">
	<?php echo $__env->yieldContent('header'); ?>

	</section>

		<!-- Main content -->
		<section class="content" style="margin-top:0px; padding-top:0px;">
			<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			<?php echo $__env->yieldContent('content'); ?>

			<?php echo $__env->yieldContent('modals'); ?>
		</section><!-- /.content -->
	</div><!-- /.content-wrapper -->
	<footer class="main-footer">
		<div class="pull-right hidden-xs">
	  		<b>Version</b> 1.0.0
		</div>
		<strong>Copyright &copy; <?php echo e(date('Y')); ?> <a href="http://eyelevate.com">Eyelevate</a>.</strong> All rights reserved.
	</footer>


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
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="/packages/AdminLTE-2.3.0//packages/AdminLTE-2.3.0/dist/js/pages/dashboard.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/packages/AdminLTE-2.3.0/dist/js/demo.js"></script>
    <script src="/js/layouts/admins.js" type="text/javascript"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
  </body>
</html>
