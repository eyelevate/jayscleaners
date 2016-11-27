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
	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">Account Transaction History</h3>
		</div>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Customer ID</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Phone</th>
						<th>Account Due</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo e($customers->id); ?></td>
						<td><?php echo e(ucFirst($customers->last_name)); ?></td>
						<td><?php echo e(ucFirst($customers->first_name)); ?></td>
						<td><?php echo e($customers->phone); ?></td>
						<td><?php echo e(money_format('$%i',$customers->account_total)); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Date</th>
						<th>Due</th>
						<th>Paid</th>
						<th>Paid On</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if(count($transactions) > 0): ?>
					<?php foreach($transactions as $transaction): ?>
					<tr class="<?php echo e($transaction->status_class); ?>">
						<td><?php echo e($transaction->id); ?></td>
						<td><?php echo e(date('m/Y',strtotime($transaction->created_at))); ?></td>
						<td><?php echo e(money_format('$%i',$transaction->total)); ?></td>
						<?php if(isset($transaction->account_paid)): ?>
						<td><?php echo e(money_format('$%i', $transaction->account_paid)); ?></td>
						<?php else: ?>
						<td>--</td>
						<?php endif; ?>
						<?php if(isset($transaction->account_paid_on)): ?>
						<td><?php echo e(date('n/d/Y g:ia',strtotime($transaction->account_paid_on))); ?></td>
						<?php else: ?>
						<td>Not Paid</td>
						<?php endif; ?>
						
						<td><?php echo e($transaction->status_html); ?></td>
						<td>
							<button class="btn btn-info btn-sm" type="button" data-toggle="modal" data-target="#invoices-<?php echo e($transaction->id); ?>">invoices</button>
							<?php if(isset($transaction->account_paid_on)): ?>
							<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#revert-<?php echo e($transaction->id); ?>">Revert Paid</button>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="box-body">
			<?php echo e($transactions->links()); ?>

		</div>
		<div class="box-footer clearfix">
			<a class="btn btn-primary btn-lg pull-right" href="<?php echo e(route('accounts_pay',$customers->id)); ?>">Make Payment</a>
		</div>
	</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
	<?php if(count($transactions) > 0): ?>
		<?php foreach($transactions as $transaction): ?>
			<?php if(count($transaction->invoices) > 0): ?>
			<?php echo View::make('partials.accounts.history')
				->with('transaction',$transaction)
				->with('invoices',$transaction->invoices)
				->render(); ?>	
			<?php echo View::make('partials.accounts.revert')
				->with('transaction',$transaction)
				->render(); ?>				
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>