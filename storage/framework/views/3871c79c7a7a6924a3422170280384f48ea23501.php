<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/js/accounts/send.js"></script>
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
		<div class="panel-body well well-sm">
			<?php echo Form::open(['action' => 'AccountsController@postSend','role'=>"form"]); ?>

			<div class="form-group<?php echo e($errors->has('customer_id') ? ' has-error' : ''); ?>">
                <label class="control-label">Customer ID</label>

            	<?php echo Form::text('customer_id','', ['id'=>'customer_id','class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('customer_id')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('customer_id')); ?></strong>
                    </span>
                <?php endif; ?>

            </div>
			<div class="form-group<?php echo e($errors->has('transaction_id') ? ' has-error' : ''); ?>">
                <label class="control-label">Transaction ID</label>

            	<?php echo Form::text('transaction_id','', ['id'=>'transaction_id','class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('transaction_id')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('transaction_id')); ?></strong>
                    </span>
                <?php endif; ?>

            </div>
			<div class="form-group<?php echo e($errors->has('billing_month') ? ' has-error' : ''); ?>">
                <label class="control-label">Select Month</label>

            	<?php echo Form::select('billing_month', $month ,date('n'), ['id'=>'billing_month','class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('billing_month')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('billing_month')); ?></strong>
                    </span>
                <?php endif; ?>

            </div>
			<div class="form-group<?php echo e($errors->has('billing_month') ? ' has-error' : ''); ?>">
                <label class="control-label">Select Year</label>

            	<?php echo Form::select('billing_year', $year ,date('Y'), ['id'=>'billing_year','class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('billing_year')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('billing_year')); ?></strong>
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
						<th>Trans ID</th>
						<th>Customer</th>
						<th>Last</th>
						<th>First</th>
						<th>Email</th>
						<th>Period</th>
						<th>Due Date</th>
						<th>Bill Total</th>
						<th>Total Due</th>
						<th><input type="checkbox" id="checkAll"/></th>
					</tr>
				</thead>
				<tbody id="accountTbody">
				<?php if(count($transactions) > 0): ?>
					<?php $idx = 0; ?>
					<?php foreach($transactions as $transaction): ?>
					<tr id="accountTr-<?php echo e($transaction->id); ?>" class="accountTr">
						<td><?php echo e($transaction->id); ?></td>
						<td><?php echo e($transaction->customer_id); ?></td>
						<td><?php echo e($transaction->last_name); ?></td>
						<td><?php echo e($transaction->first_name); ?></td>
						<td><?php echo e($transaction->email); ?></td>
						<td><?php echo e($transaction->billing_period); ?></td>
						<td><?php echo e($transaction->due); ?></td>
						<td><?php echo e(money_format('$%i',$transaction->total)); ?></td>
						<td><?php echo e(money_format('$%i',$transaction->total_due)); ?></td>
						<td>
							<input class="transaction_ids" type="checkbox" value="<?php echo e($transaction->id); ?>" name="transaction_ids[<?php echo e($idx++); ?>]"/>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>

		<div class="panel-footer">
			<a class="btn btn-lg btn-info" href="<?php echo e(route('accounts_preview')); ?>" target="_blank">Preview & Print</a>
			<button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#send">Email Selected Bills</button>
		</div>

	</div>  

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
<?php echo View::make('partials.accounts.email_send')->render(); ?>	
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>