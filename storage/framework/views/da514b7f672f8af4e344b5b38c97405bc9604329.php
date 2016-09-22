<?php $__env->startSection('stylesheets'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Delivery Overview</h3>
		</div>
        <?php echo Form::open(['action' => 'DeliveriesController@postOverview','role'=>"form"]); ?>

            <?php echo csrf_field(); ?> 
		<div class="panel-body">

            <div class="form-group<?php echo e($errors->has('search') ? ' has-error' : ''); ?>">
                <label class="control-label padding-top-none">Search Delivery</label>
				<input id="search" type="text" class="form-control" name="search" value="<?php echo e(old('search')); ?>" placeholder="phone / id / last name">
				
	            <?php if($errors->has('search')): ?>
	                <span class="help-block">
	                    <strong><?php echo e($errors->first('search')); ?></strong>
	                </span>
	            <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-default ">Search</button>
		</div>
		<?php echo Form::close(); ?>

		<div class="panel-footer clearfix">
			<a href="#" class="btn btn-lg btn-info btn-flat pull-left col-md-2 col-sm-6 col-xs-6">New Delivery</a>
			<a href="<?php echo e(route('schedules_checklist')); ?>" class="btn btn-lg btn-default btn-flat pull-right col-md-2 col-sm-6 col-xs-6">Checklist</a>
		</div>
	</div>

	<div class="box box-info">
		<div class="box-header with-border clearfix">
			<h3 class="box-title">Active Delivery List &nbsp;<span class="label label-default pull-right"><?php echo e(count($schedules)); ?></span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="table-responsive">
				<table class="table no-margin">
					<thead>
						<tr>
							<th>Schedule #</th>
							<th>Customer</th>
							<th>Pickup</th>
							<th>Dropoff</th>
							<th>Status</th>
							<th>Created On</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($schedules) > 0): ?> 
						<?php foreach($schedules as $schedule): ?>
						<tr data-toggle="modal" data-target="#detail-<?php echo e($schedule['id']); ?>">
							<td><?php echo e($schedule['id']); ?></td>
							<td>[<?php echo e($schedule['customer_id']); ?>] <?php echo e($schedule['last_name']); ?>, <?php echo e($schedule['first_name']); ?></td>
							<td><?php echo e($schedule['pickup_date']); ?></td>
							<td><?php echo e($schedule['dropoff_date']); ?></td>
							<td><label class="label <?php echo e($schedule['status_html']); ?>"><?php echo e($schedule['status_message']); ?></label></td>
							<td><?php echo e($schedule['created_at']); ?></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div><!-- /.table-responsive -->
		</div><!-- /.box-body -->
		<div class="box-footer clearfix">

		</div><!-- /.box-footer -->
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
	<?php if(count($schedules) > 0): ?>
		<?php foreach($schedules as $schedule): ?>
		<?php echo View::make('partials.deliveries.details')
			->with('schedule',$schedule)
			->render(); ?>

		<?php endforeach; ?>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>