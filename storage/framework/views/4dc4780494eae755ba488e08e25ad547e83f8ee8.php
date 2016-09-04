<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

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
<?php $__env->startSection('content'); ?>
  <div class="row">
    <a href="<?php echo e(route('customers_view','')); ?>" class="col-lg-3 col-xs-6">
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
    <a href="#" class="col-lg-3 col-xs-6">
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
    <a href="#" class="col-lg-3 col-xs-6">
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
  <!-- Sales -->
  <!-- Custom tabs (Charts with tabs)-->
  <div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
      <li><a href="#sales-other" data-toggle="tab">Other</a></li>
      <li><a href="#sales-household" data-toggle="tab">Household</a></li>
      <li><a href="#sales-alteration" data-toggle="tab">Alteration</a></li>
      <li><a href="#sales-laundry" data-toggle="tab">Laundry</a></li>
      <li><a href="#sales-dryclean" data-toggle="tab">Dry Clean</a></li>
      <li class="active"><a href="#revenue-chart" data-toggle="tab">Totals</a></li>
      <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
    </ul>
    <div class="tab-content no-padding">
      <!-- Morris chart - Sales -->
      <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
      <div class="chart tab-pane" id="sales-dryclean" style="position: relative; height: 300px;"></div>
      <div class="chart tab-pane" id="sales-laundry" style="position: relative; height: 300px;"></div>
      <div class="chart tab-pane" id="sales-alteration" style="position: relative; height: 300px;"></div>
      <div class="chart tab-pane" id="sales-household" style="position: relative; height: 300px;"></div>
      <div class="chart tab-pane" id="sales-other" style="position: relative; height: 300px;"></div>
    </div>
  </div><!-- /.nav-tabs-custom -->
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
          <a href="#">
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