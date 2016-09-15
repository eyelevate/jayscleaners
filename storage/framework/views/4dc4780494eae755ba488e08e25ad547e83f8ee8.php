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
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Monthly Recap Report</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <p class="text-center">
                    <strong>Sales: <?php echo e(date('d M, Y', strtotime(date('Y-01-01 00:00:00')))); ?> - <?php echo e(date('d M, Y',strtotime(date('Y-m-d H:i:s')))); ?></strong>
                  </p>

                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="salesChart" style="height: 350px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
                  <div class="description-block border-right">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Jays Cleaners Roosevelt<label>
                      <h5 class="description-header">&nbsp;$1,210.43</h5>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Jays Cleaners Montlake<label>
                      <h5 class="description-header">&nbsp;$5,210.43</h5>
                    </div>
                    <span class="description-text">TODAY</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
                  <div class="description-block border-right">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Jays Cleaners Roosevelt<label>
                      <h5 class="description-header">&nbsp;$5,210.43</h5>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Jays Cleaners Montlake<label>
                      <h5 class="description-header">&nbsp;$7,210.43</h5>
                    </div>
                    <span class="description-text">THIS WEEK</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
                  <div class="description-block border-right">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Jays Cleaners Roosevelt<label>
                      <h5 class="description-header">&nbsp;$27,210.43</h5>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Jays Cleaners Montlake<label>
                      <h5 class="description-header">&nbsp;$35,210.43</h5>
                    </div>
                    <span class="description-text">THIS MONTH</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
                  <div class="description-block">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Jays Cleaners Roosevelt<label>
                      <h5 class="description-header">&nbsp;$331,210.43</h5>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <label>Jays Cleaners Montlake<label>
                      <h5 class="description-header">&nbsp;$350,210.43</h5>
                    </div>
                    <span class="description-text">THIS YEAR</span>
                  </div>
                  <!-- /.description-block -->
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
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