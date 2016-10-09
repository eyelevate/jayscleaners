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
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="box box-primary" >
				<div class="box-header"><h4>Select Invoice</h4></div>
				<div class="table-responsive">
					<table class="table table-hover table-condensed">
						<thead>
							<tr>
								<th>ID</th>
								<th>Rack</th>
								<th>Drop</th>
								<th>Due</th>
								<th>Qty</th>
								<th>Status</th>
								<th>Subtotal</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody id="invoice_tbody">
						<?php if(count($invoices) > 0): ?>
							<?php foreach($invoices as $invoice): ?>
							<tr id="invoice_tr-<?php echo e($invoice->id); ?>" class="invoice_tr" style="cursor:pointer; color:<?php echo e($invoice->status_color); ?>; background-color:<?php echo e($invoice->status_bg); ?>;">
								<td><?php echo e(str_pad($invoice->id, 6, '0', STR_PAD_LEFT)); ?></td>
								<td><?php echo e($invoice->rack); ?></td>
								<td><?php echo e(date('D n/d',strtotime($invoice->created_at))); ?></td>
								<td><?php echo e(date('D n/d',strtotime($invoice->due_date))); ?></td>
								<td><?php echo e($invoice->quantity); ?></td>
								<td><?php echo e($invoice->status_title); ?></td>
								<td><?php echo e(money_format('$%i',$invoice->pretax)); ?></td>
								<td>
									<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#view-<?php echo e($invoice->id); ?>">View</a>
									<a href="<?php echo e(route('invoices_edit',$invoice->id)); ?>" class="btn btn-sm btn-info">Edit</a>&nbsp;
									<a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#revert-<?php echo e($invoice->id); ?>">Revert</a>
								</td>
							</tr>
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="box-footer clearfix">
					<a class="btn btn-lg btn-danger" href="<?php echo e(route('customers_view',$customer_id)); ?>">Back</a>
					<?php echo e($invoices->links()); ?>


				</div>
			</div>
		</article>
	</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
	<?php if(count($invoices) > 0): ?>
		<?php foreach($invoices as $invoice): ?>
		<?php echo View::make('partials.invoices.revert')
			->with('invoice',$invoice)
			->render(); ?>

		<?php echo View::make('partials.invoices.view')
			->with('invoice',$invoice)
			->render(); ?>

		<?php echo View::make('partials.invoices.remove')
			->with('invoice',$invoice)
			->render(); ?>

		<?php endforeach; ?>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>