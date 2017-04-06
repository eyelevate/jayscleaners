<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<br/>
	<div class="panel panel-primary">
		<div class="panel-heading"><h4>Delivery Schedule</h4></div>
		<div class="panel-body">

		</div>
		<div class="table-responsive">
			<table class="table table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Route</th>
						<th>Day</th>
						<th>Limit</th>
						<th>Start</th>
						<th>End</th>
						<th>Zipcode</th>
						<th>Blackout</th>
						<th>Created</th>
						<th>A</th>
					</tr>
				</thead>
				<tbody>
				<?php if(count($deliveries)): ?>
					<?php foreach($deliveries as $delivery): ?>
					<tr>
						<td><?php echo e($delivery->id); ?></td>
						<td><?php echo e($delivery->route_name); ?></td>
						<td><?php echo e($delivery->day); ?></td>
						<td><?php echo e($delivery->limit); ?></td>
						<td><?php echo e($delivery->start_time); ?></td>
						<td><?php echo e($delivery->end_time); ?></td>
						<td>
							<ul>

							<?php if($delivery->zipcode): ?>
								<?php foreach($delivery->zipcode as $zipcode): ?>
								<li><?php echo e($zipcode); ?></li>
								<?php endforeach; ?>
							
							<?php endif; ?>
							</ul>
						</td>
						<td>
							<ul>
							<?php if(count($delivery->blackout) > 0): ?>
								<?php foreach($delivery->blackout as $blackout): ?>
								<li><?php echo e(date('D n/d/Y',strtotime($blackout))); ?></li>
								<?php endforeach; ?>
							<?php else: ?>
								<?php if(isset($delivery->blackout)): ?>

								<li><?php echo e(date('D n/d/Y',strtotime($delivery->blackout))); ?></li>
								<?php endif; ?>
							
							<?php endif; ?>
							</ul>
						</td>
						<td><?php echo e(date('D n/d/Y',strtotime($delivery->created_at))); ?></td>
						<td>
							<a class="btn btn-sm btn-info" href="<?php echo e(route('delivery_setup_edit',$delivery->id)); ?>">edit</a>&nbsp;
							<a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-<?php echo e($delivery->id); ?>">remove</a>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>

		<div class="panel-footer clearfix">
			<a href="<?php echo e(route('admins_index')); ?>" class="btn btn-danger">Back</a>
			<a href="<?php echo e(route('delivery_setup_add')); ?>" class="btn btn-primary pull-right">New Rule</a>
		</div>

	</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
	<?php if(count($deliveries)): ?>
		<?php foreach($deliveries as $delivery): ?>
		<?php echo View::make('partials.deliveries.remove') 
			->with('id',$delivery->id)
			->render(); ?>

		<?php endforeach; ?>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>