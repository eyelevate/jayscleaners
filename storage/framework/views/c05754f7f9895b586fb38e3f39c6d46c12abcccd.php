<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<br/>
<div class="well">
	<h3>Report date(s): <strong><?php echo e($start_date); ?></strong> - <strong><?php echo e($end_date); ?></strong></h3>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Dropoff Totals</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>Inventory</th>
					<th>Quantity</th>
					<th>Subtotal</th>
					<th>Tax</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
			<?php if(count($reports['dropoff_summary'] > 0)): ?>
				<?php foreach($reports['dropoff_summary'] as $ds): ?>
				<tr>
					<td><?php echo e($ds['name']); ?></td>
					<td><?php echo e($ds['totals']['quantity']); ?></td>
					<td><?php echo e($ds['totals']['subtotal']); ?></td>
					<td><?php echo e($ds['totals']['tax']); ?></td>
					<td><?php echo e($ds['totals']['total']); ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4" style="text-align:right">Quantity</th>
					<td><?php echo e($reports['dropoff_summary_totals']['quantity']); ?></td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Subtotal</th>
					<td><?php echo e($reports['dropoff_summary_totals']['subtotal']); ?></td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Tax</th>
					<td><?php echo e($reports['dropoff_summary_totals']['tax']); ?></td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Total</th>
					<td><?php echo e($reports['dropoff_summary_totals']['total']); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="panel-footer"></div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Pickup Totals</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>Inventory</th>
					<th>Quantity</th>
					<th>Subtotal</th>
					<th>Tax</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
			<?php if(count($reports['pickup_summary'] > 0)): ?>
				<?php foreach($reports['pickup_summary'] as $ps): ?>
				<tr>
					<td><?php echo e($ps['name']); ?></td>
					<td><?php echo e($ps['totals']['quantity']); ?></td>
					<td><?php echo e($ps['totals']['subtotal']); ?></td>
					<td><?php echo e($ps['totals']['tax']); ?></td>
					<td><?php echo e($ps['totals']['total']); ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4" style="text-align:right">Quantity</th>
					<td><?php echo e($reports['pickup_summary_totals']['quantity']); ?></td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Subtotal</th>
					<td><?php echo e($reports['pickup_summary_totals']['subtotal']); ?></td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Tax</th>
					<td><?php echo e($reports['pickup_summary_totals']['tax']); ?></td>
				</tr>
				<tr>
					<th colspan="4" style="text-align:right">Total</th>
					<td><?php echo e($reports['pickup_summary_totals']['total']); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="panel-footer"></div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Payment Summary</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped">
			<thead>
				<tr>
					<th>Type</th>
					<th>Subtotal</th>
					<th>Tax</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Cash</td>
					<td><?php echo e($reports['total_splits']['cash']['subtotal']); ?></td>
					<td><?php echo e($reports['total_splits']['cash']['tax']); ?></td>
					<td><?php echo e($reports['total_splits']['cash']['total']); ?></td>
				</tr>
				<tr>
					<td>Credit Card</td>
					<td><?php echo e($reports['total_splits']['credit']['subtotal']); ?></td>
					<td><?php echo e($reports['total_splits']['credit']['tax']); ?></td>
					<td><?php echo e($reports['total_splits']['credit']['total']); ?></td>
				</tr>
				<tr>
					<td>Online</td>
					<td><?php echo e($reports['total_splits']['online']['subtotal']); ?></td>
					<td><?php echo e($reports['total_splits']['online']['tax']); ?></td>
					<td><?php echo e($reports['total_splits']['online']['total']); ?></td>
				</tr>
				<tr>
					<td>Check</td>
					<td><?php echo e($reports['total_splits']['check']['subtotal']); ?></td>
					<td><?php echo e($reports['total_splits']['check']['tax']); ?></td>
					<td><?php echo e($reports['total_splits']['check']['total']); ?></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="3" style="text-align:right">Subtotal</th>
					<td><?php echo e($reports['totals']['subtotal']); ?></td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:right">Tax</th>
					<td><?php echo e($reports['totals']['tax']); ?></td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:right">Discount</th>
					<td><?php echo e($reports['totals']['discount']); ?></td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:right">Total</th>
					<td><?php echo e($reports['totals']['total']); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="panel-footer"></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>