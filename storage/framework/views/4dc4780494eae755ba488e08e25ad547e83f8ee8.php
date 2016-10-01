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
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

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
          <a href="<?php echo e(route('invoices_report',2)); ?>">
            <!-- Emphasis label -->
            <span class="badge-default"><?php echo e($today_totals['invoices_overdue']); ?></span>
            <!-- todo text -->
            <span class="ltext">Overdue Orders</span>
          </a>
        </li>
        <li>
          <a href="<?php echo e(route('invoices_report',1)); ?>">
          <!-- Emphasis label -->  
          <span class="badge-green"><?php echo e($today_totals['invoices_today']); ?></span>
          <!-- todo text -->
          <span class="ltext">Due Today</span>
          </a>
        </li>
        <li>
          <a href="<?php echo e(route('delivery_overview')); ?>">
          <!-- Emphasis label -->
          <span class="badge-yellow"><?php echo e($today_totals['deliveries']); ?></span>
          <!-- todo text -->
          <span class="ltext">Delivery Today</span>
          </a>
        </li>
        <li>
          <a href="<?php echo e(route('invoices_report',3)); ?>">
          <!-- Emphasis label -->
          <span class="badge-red"><?php echo e($today_totals['invoices_voided']); ?></span>
          <!-- todo text -->
          <span class="ltext">Voided Today</span>
          </a>
        </li>
        <li>
          <a href="#">
          <!-- Emphasis label -->
          <span class="badge-aqua"><?php echo e($today_totals['invoices_wayoverdue']); ?></span>
          <!-- todo text -->
          <span class="ltext">Aged (30 days+)</span>
          </a>
        </li>

      </ul>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix no-border">
      
    </div>
  </div><!-- /.box -->
  <!-- Zipcode Requests -->
  <div class="box box-info">
    <div class="box-header">
      <i class="ion ion-clipboard"></i>
      <h3 class="box-title">Today's Zipcode Request</h3>
      <div class="box-tools pull-right"></div>
    </div><!-- /.box-header -->
    <div class="box-body table-responsive">
      <table class="table table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th>Zipcode</th>
            <th>Name</th>
            <th>Email</th>
            <th>Comment</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php if(count($zipcode_requests)> 0): ?>
          <?php foreach($zipcode_requests as $zr): ?>
          <tr>
            <td><?php echo e($zr->zipcode); ?></td>
            <td><?php echo e($zr->name); ?></td>
            <td><?php echo e($zr->email); ?></td>
            <td><?php echo e($zr->comment); ?></td>
            <td><?php echo e(date('D n/d/Y', strtotime($zr->created_at))); ?></td>
            <td><a>Reply</a></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="box-footer clearfix">
      <a href="" class="btn btn-info">Zipcode Requests<a/>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>