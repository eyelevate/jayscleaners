<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<div class="panel panel-default">
		<div class="panel-header">

		</div>
		<div class="panel-body">

		</div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th>ID</th>
						
					</tr>
				</thead>
				<tbody>
				<?php if(count($duplicates) > 0): ?>
					<?php foreach($duplicates as $key => $value): ?>
					<tr>
						<td><?php echo e($key); ?></td>
						<td><?php echo e($value); ?></td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>