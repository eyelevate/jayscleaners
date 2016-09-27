<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/js/admins/index.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
  <h1>
    Admins Home Page
    <small>Control panel</small>
  </h1>
  <ol class="breadcrumb">
  	<li class="active">Admins</li>

  </ol>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('notifications'); ?>
<div class="navbar-custom-menu">
  <ul class="nav navbar-nav">
    <!-- Messages: style can be found in dropdown.less-->

    <!-- Notifications: style can be found in dropdown.less -->

    <!-- Tasks: style can be found in dropdown.less -->

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
            <?php echo e(Auth::user()->username); ?> - <?php echo e($role); ?>

            <small>Member since <?php echo e(date('M., Y',strtotime(Auth::user()->created_at))); ?></small>
          </p>
        </li>
        <!-- Menu Body -->
        <li class="user-body">

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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
  <div class="row">
    <a href="<?php echo e(route('customers_index','')); ?>" class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>Invoice</h3>
          <p>Drop / Pickup</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-paper-outline"></i>
        </div>
        <div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
      </div>
    </a><!-- ./col -->
    <a href="<?php echo e(route('reports_index')); ?>" class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>Reports</h3>
          <p>Sales reports</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
      </div>
    </a><!-- ./col -->
    <a href="<?php echo e(route('delivery_overview')); ?>" class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>Delivery</h3>
          <p>Delivery Schedule</p>
        </div>
        <div class="icon">
          <i class="ion ion-android-car"></i>
        </div>
        <div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
      </div>
    </a><!-- ./col -->
    <a href="<?php echo e(route('admins_settings')); ?>" class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>Settings</h3>
          <p>Company Settings</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-settings-strong"></i>
        </div>
        <div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
      </div>
    </a><!-- ./col -->
  </div><!-- /.row -->
      
  <!-- TO DO List -->
  <div class="box box-primary">
    <div class="box-header">
      <i class="ion ion-clipboard"></i>
      <h3 class="box-title">Work List</h3>
      <div class="box-tools pull-right">

      </div>
    </div><!-- /.box-header -->
    <div class="box-body">
      <ul class="todo-list">
        <li>
          <a href="#">
            <!-- Emphasis label -->
            <span class="badge-default">10</span>
            <!-- todo text -->
            <span class="ltext">Overdue Orders</span>
          </a>
        </li>
        <li>
          <a href="#">
          <!-- Emphasis label -->
          <span class="badge-green">10</span>
          <!-- todo text -->
          <span class="ltext">Due Today</span>
          </a>
        </li>
        <li>
          <a href="<?php echo e(route('delivery_overview')); ?>">
          <!-- Emphasis label -->
          <span class="badge-yellow">10</span>
          <!-- todo text -->
          <span class="ltext">Delivery Today</span>
          </a>
        </li>
        <li>
          <a href="#">
          <!-- Emphasis label -->
          <span class="badge-red">10</span>
          <!-- todo text -->
          <span class="ltext">Voided Today</span>
          </a>
        </li>
        <li>
          <a href="#">
          <!-- Emphasis label -->
          <span class="badge-aqua">10</span>
          <!-- todo text -->
          <span class="ltext">Aged (30 days+)</span>
          </a>
        </li>

      </ul>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix no-border">
      
    </div>
  </div><!-- /.box -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>