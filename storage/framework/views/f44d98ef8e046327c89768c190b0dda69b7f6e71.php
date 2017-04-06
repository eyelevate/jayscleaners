<?php $__env->startSection('stylesheets'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<br/>
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
			<a class="btn btn-info" href="<?php echo e(route('customers_view',$customer_id)); ?>">Customer View</a>
			<a class="btn btn-info" href="<?php echo e(route('delivery_overview')); ?>">Delivery Overview</a>
			<a class="btn btn-primary" href="<?php echo e(route('delivery_new',$customer_id)); ?>">New Delivery</a>
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