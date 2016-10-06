<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
<h1> Update Taxes <small>Control panel</small></h1>
<ol class="breadcrumb">
	<li><a href="<?php echo e(route('admins_index')); ?>"><i class="fa fa-dashboard"></i> Admins</a></li>
	<li><a href="<?php echo e(route('admins_settings')); ?>"> Settings</a></li>
	<li class="active">Taxes</li>
</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- quick email widget -->
<div class="box box-info">
	<div class="box-header">

		<h3 class="box-title">Sales Tax Rate</h3>

	</div>
	<div class="box-body">
		<div class="form-horizontal">
            <div class="form-group">
                <label class="col-md-4 control-label">Current Sales Tax</label>

                <div class="col-md-6">
                    <?php echo Form::text('tax', $tax['rate'], ['class'=>'form-control', 'placeholder'=>'','disabled'=>'true','style'=>'font-size:20px;']); ?>


                </div>
            </div> 				
		</div>
	</div>
	<div class="box-footer clearfix">
		<button class="pull-left btn btn-info" data-toggle="modal" data-target="#history">History</button>
		<button class="pull-right btn btn-primary" data-toggle="modal" data-target="#update">Update Tax</button>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
	<?php echo View::make('partials.taxes.update')
		->with('companies',$companies)
		->render(); ?>

	<?php echo View::make('partials.taxes.history')
		->with('history',$history)
		->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>