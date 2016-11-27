<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
	<script type="text/javascript" src="/js/accounts/pay.js"></script>
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
			<h3 class="box-title">Active Account Transactions</h3>
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
					<tr id="transaction_tr-<?php echo e($transaction->id); ?>" customer="<?php echo e($customers->id); ?>" class="transaction_tr" style="cursor:pointer;">
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
						<td><input type="checkbox" class="transaction_id" value="<?php echo e($transaction->id); ?>"/></td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5"></td>
						<th>Quantity</th>
						<td id='quantity'>0</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Subtotal</th>
						<td id="subtotal">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Tax</th>
						<td id="tax">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>After Tax</th>
						<td id="aftertax">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Credits</th>
						<td id="credits">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Discount</th>
						<td id="discount">$0.00</td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<th>Total Due</th>
						<td id="due">$0.00</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="box-body">
			<?php echo e($transactions->links()); ?>

		</div>
		<div class="box-footer clearfix">
			<button class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#account_pay">Make Payment</button>
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
			<?php endif; ?>
		<?php endforeach; ?>
		<?php echo View::make('partials.accounts.pay')
			->with('status',1)
			->with('customer_id',$customers->id)
			->render(); ?>

	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>