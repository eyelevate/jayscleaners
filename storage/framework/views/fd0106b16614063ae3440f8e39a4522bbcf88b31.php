<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<br/>
	<div class="panel panel-info">
		<div class="panel-heading"><h4>Zipcodes</h4></div>
		<div class="table-responsive">
			<table class="table table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Zipcode</th>
						<th>Status</th>
						<th>Created</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if(count($zipcodes) > 0): ?>
					<?php foreach($zipcodes as $zipcode): ?>
					<tr>
						<td><?php echo e($zipcode->id); ?></td>
						<td><?php echo e($zipcode->zipcode); ?></td>
						<td><?php echo e($zipcode->status); ?></td>
						<td><?php echo e(date('D n/d/Y',strtotime($zipcode->created_at))); ?></td>
						<td>
							<a href="<?php echo e(route('zipcodes_edit',$zipcode->id)); ?>" class="btn btn-info btn-sm">edit</a>&nbsp;
							<a href="<?php echo e(route('zipcodes_delete',$zipcode->id)); ?>" class="btn btn-sm btn-danger">delete</a>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="panel-footer clearfix">
			<a href="<?php echo e(route('admins_index')); ?>" class="btn btn-danger btn-lg">Home</a>
			<a href="<?php echo e(route('zipcodes_add')); ?>" class="btn btn-primary btn-lg pull-right">Add</a>
		</div>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>