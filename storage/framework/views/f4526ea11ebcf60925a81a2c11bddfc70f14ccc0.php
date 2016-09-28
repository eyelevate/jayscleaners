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
	<div class="panel panel-default">
		<div class="panel-heading"><h4>Invoices</h4></div>
		<div ckass="panel-body">
			<div class="table-responsive">
				<table class="table table-condensed table-striped table-hover">	
					<thead>
						<tr>
							<th>Id</th>
							<th>Qty</th>
							<th>Items</th>
							<th>Drop</th>
							<th>Due</th>
							<th>Total</th>
							<th>A.</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($invoices) > 0): ?>
						<?php foreach($invoices as $invoice): ?>
						<tr>
							<td><?php echo e($invoice->invoice_id); ?></td>
							<td><?php echo e($invoice->quantity); ?></td>
							<td>
								<ul style="list-style:none;">
								<?php if(count($invoice['item_details'])): ?>
									<?php foreach($invoice['item_details'] as $ids): ?>
									<li><?php echo e($ids['qty']); ?>-<?php echo e($ids['item']); ?></li>
										<?php if(count($ids['color']) > 0): ?>
										<li>
											<ul>
											<?php foreach($ids['color'] as $colors_name => $colors_count): ?>
												<li><?php echo e($colors_count); ?>-<?php echo e($colors_name); ?></li>
											<?php endforeach; ?>
											</ul>
										</li>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								</ul>
							</td>
							<td><?php echo e(date('D n/d/Y',strtotime($invoice->created_at))); ?></td>
							<td><?php echo e(date('D n/d/Y', strtotime($invoice->due_date))); ?></td>
							<td><?php echo e($invoice->total); ?></td>
							<td><a href="<?php echo e(route('invoices_edit',$invoice->invoice_id)); ?>">Edit</a></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer"></div>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>