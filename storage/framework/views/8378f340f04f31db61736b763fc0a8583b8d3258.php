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
	<div class="panel panel-primary">
		<div class="panel-heading"><h4>Customer Search Form</h4></div>
		<div class="panel-body">
			<?php echo Form::open(['action' => 'AccountsController@postIndex','role'=>"form"]); ?>

			<div class="form-group <?php echo e($errors->has('search') ? ' has-error' : ''); ?>">
				<label class="control-label">Search</label>
				<?php echo e(Form::text('search',old('search'),['class'=>"form-control",'placeholder'=>'Last Name / Phone / ID'])); ?>

                <?php if($errors->has('search')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('search')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Search</button>
			</div>
			<?php echo Form::close(); ?>

		</div>
		<div class="table-responsive">
			<table class="table table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th>Id</th>
						<th>Username</th>
						<th>Last</th>
						<th>First</th>
						<th>Phone</th>
						<th>Due</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if(isset($customers)): ?>
					<?php foreach($customers as $customer): ?>
					<tr class="<?php echo e(($customer->status == 3) ? 'active' : ($customer->status== 2) ? 'info' : 'active'); ?>">
						<td><?php echo e($customer->account_transaction_id); ?></td>
						<td><?php echo e($customer->username); ?></td>
						<td><?php echo e($customer->last_name); ?></td>
						<td><?php echo e($customer->first_name); ?></td>
						<td><?php echo e($customer->phone); ?></td>
						<td><?php echo e($customer->account_total); ?></td>
						<td>
						<?php if($customer->account_total > 0): ?>
							<a href="<?php echo e(route('accounts_pay',$customer->id)); ?>" class="btn btn-info">Pay</a>
							<a href="<?php echo e(route('accounts_history',$customer->id)); ?>" class="btn btn-info">Payment History</a>
						<?php else: ?>
							<button type="button" class="btn btn-default" disabled="true">Pay</button>
							<a href="<?php echo e(route('accounts_history',$customer->id)); ?>" class="btn btn-info">Payment History</a>
						<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>

		<div class="panel-footer">
			<a class="btn btn-lg btn-primary" href="<?php echo e(route('accounts_send')); ?>">Send Monthly Bill</a>

		</div>

	</div>  

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
	<?php echo View::make('partials.accounts.bill')
		->with('month',$month)
		->render(); ?>	
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>