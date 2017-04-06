<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/js/accounts/member_payment.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>
<header id="header" class="reveal">
<?php if(Auth::check()): ?>
<?php echo View::make('partials.layouts.navigation_logged_in')
    ->render(); ?>

<?php else: ?>
<?php echo View::make('partials.layouts.navigation_logged_out')
    ->render(); ?>

<?php endif; ?>
</header>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

	<section class="wrapper style3 container special">
		<div id="store_hours" class="row">
			<header class="clearfix col-xs-12 col-sm-12" style="">
				<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Account Billing Membership Page</h3>
			</header>
			<section class="clearfix col-xs-12 col-sm-12 table-responsive">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th>Invoice #</th>
							<th>Customer #</th>
							<th>Billing Period</th>
							<th>Due Date</th>
							<th>Subtotal</th>
							<th>Tax</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
					<?php if($transactions): ?>
						<?php foreach($transactions as $transaction): ?>
						<tr style="text-align:left;">
							<td><?php echo e($transaction->id); ?></td>
							<td><?php echo e($transaction->customer_id); ?></td>
							<td><?php echo e($transaction->billing_period); ?></td>
							<td><?php echo e($transaction->due); ?></td>
							<td><?php echo e(money_format('$%i',$transaction->pretax)); ?></td>
							<td><?php echo e(money_format('$%i',$transaction->tax)); ?></td>
							<td><?php echo e(money_format('$%i',$transaction->aftertax)); ?></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
					<tfoot style="text-align:left;">
						<tr>
							<td colspan="5" style=""></td>
							<th>Quantity</th>
							<td><?php echo e($quantity); ?></td>
						</tr>
						<tr>
							<td colspan="5" style="border:none;"></td>
							<th>Subtotal</th>
							<td><?php echo e(money_format('$%i',$subtotal)); ?></td>
						</tr>
						<tr>
							<td colspan="5" style="border:none;"></td>
							<th>Tax</th>
							<td><?php echo e(money_format('$%i',$tax)); ?></td>
						</tr>
						<tr>
							<td colspan="5" style="border:none;"></td>
							<th>After Tax</th>
							<td><?php echo e(money_format('$%i',$aftertax)); ?></td>
						</tr>
						<tr>
							<td colspan="5" style="border:none;"></td>
							<th>Credit</th>
							<td>(<?php echo e(money_format('$%i',$credit)); ?>)</td>
						</tr>
						<tr>
							<td colspan="5" style="border:none;"></td>
							<th>Discount</th>
							<td>(<?php echo e(money_format('$%i',$discount)); ?>)</td>
						</tr>
						<tr>
							<td colspan="5" style="border:none;"></td>
							<th>Total Due</th>
							<td><?php echo e(money_format('$%i',$total)); ?></td>
						</tr>
					</tfoot>
				</table>
			</section>
		</div>
	</section>
	<?php if(count($transactions) > 0): ?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Credit Card Payment Method</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal">
				<div class="form-group<?php echo e($errors->has('total') ? ' has-error' : ''); ?>">
		            <label class="control-label col-md-4 padding-top-none">Total Due</label>

		            <div class="col-md-6">
		            	<p style="text-align:left;"><strong><?php echo e(money_format('$%i',$total)); ?></strong></p>
		               
		                <?php if($errors->has('total')): ?>
		                    <span class="help-block">
		                        <strong><?php echo e($errors->first('total')); ?></strong>
		                    </span>
		                <?php endif; ?>
		            </div>
		        </div>
		        <div class="form-group<?php echo e($errors->has('total') ? ' has-error' : ''); ?>">
		            <label class="control-label col-md-4 padding-top-none">Select Payment Method</label>

		            <div class="col-md-6">
		            	<?php echo Form::select('type',[''=>'Select Payment Method','1'=>'Pay using online credit card form','2'=>'Pay using card on file'],'1',['class'=>'form-control','id'=>'pay_type']); ?>

		            </div>
		        </div>					
			</form>
		</div>
	</div>
	<?php echo Form::open(['action' => 'AccountsController@postMemberPayment', 'class'=>'form-horizontal','role'=>"form"]); ?>

	<div id="card_selection-form" class="card_selection panel panel-default">
		
		<div class="panel-heading">
			<h3 class="panel-title">Credit Card Payment Form</h3>
		</div>
		<div class="panel-body">
			
			<?php echo Form::hidden('total',$total); ?>

	        <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
	            <label class="control-label col-md-4 padding-top-none">Email <span class="text text-danger">*</span></label>

	            <div class="col-md-6">
	                <?php echo Form::text('email', old('email') ? old('email') : $email, ['class'=>'form-control','placeholder'=>'xxxx@xxxxx.com']); ?>

	                <?php if($errors->has('email')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('email')); ?></strong>
	                    </span>
	                <?php endif; ?>
	            </div>
	        </div>	
	        <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
	            <label class="control-label col-md-4 padding-top-none">Name On Card <span class="text text-danger">*</span></label>

	            <div class="col-md-6">
	                <?php echo Form::text('name', old('name') ? old('name') : $full_name, ['class'=>'form-control','maxlength'=>'20','placeholder'=>'John Doe']); ?>

	                <?php if($errors->has('name')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('name')); ?></strong>
	                    </span>
	                <?php endif; ?>
	            </div>
	        </div>	
	        <div class="form-group<?php echo e($errors->has('card_number') ? ' has-error' : ''); ?>">
	            <label class="control-label col-md-4 padding-top-none">Credit Card # <span class="text text-danger">*</span></label>

	            <div class="col-md-6">
	                <?php echo Form::text('card_number', old('card_number'), ['class'=>'form-control','maxlength'=>'20','placeholder'=>'XXXXXXXXXXXXXXXX']); ?>

	                <?php if($errors->has('card_number')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('card_number')); ?></strong>
	                    </span>
	                <?php endif; ?>
	            </div>
	        </div>	
	        <div class="form-group<?php echo e($errors->has('exp_month') ? ' has-error' : ''); ?>">
	            <label class="control-label col-md-4 padding-top-none">Expiration Month <span class="text text-danger">*</span></label>

	            <div class="col-md-6">
	                <?php echo Form::text('exp_month', old('exp_month'), ['class'=>'form-control','placeholder'=>'XX','maxlength'=>'2']); ?>

	                <?php if($errors->has('exp_month')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('exp_month')); ?></strong>
	                    </span>
	                <?php endif; ?>
	            </div>
	        </div>	
	        <div class="form-group<?php echo e($errors->has('exp_year') ? ' has-error' : ''); ?>">
	            <label class="control-label col-md-4 padding-top-none">Expiration Year <span class="text text-danger">*</span></label>

	            <div class="col-md-6">
	                <?php echo Form::text('exp_year', old('exp_year'), ['class'=>'form-control','placeholder'=>'XXXX','maxlength'=>'4']); ?>

	                <?php if($errors->has('exp_year')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('exp_year')); ?></strong>
	                    </span>
	                <?php endif; ?>
	            </div>
	        </div>	
	        <div class="form-group<?php echo e($errors->has('cvv') ? ' has-error' : ''); ?>">
	            <label class="control-label col-md-4 padding-top-none">CVV <span class="text text-danger">*</span></label>

	            <div class="col-md-6">
	                <?php echo Form::text('cvv', old('cvv'), ['class'=>'form-control','placeholder'=>'XXX','maxlength'=>'4']); ?>

	                <?php if($errors->has('cvv')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('cvv')); ?></strong>
	                    </span>
	                <?php endif; ?>
	            </div>
	        </div>	
		</div>
		<div class="panel-footer clearfix">
			<button class="btn btn-primary pull-left" type="submit" data-toggle="modal" data-target="#loading">Make Payment</button>
		</div>
		
	</div>
	<?php echo Form::close(); ?>

	<?php echo Form::open(['action' => 'AccountsController@postMemberFile', 'class'=>'form-horizontal','role'=>"form"]); ?>


	<div id="card_selection-cof" class="card_selection panel panel-default hide">
		<div class="panel-heading">
			<h3 class="panel-title">Card On File Form</h3>
		</div>
		<div class="panel-body">

			<?php if(count($card) > 0): ?>
			<div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
	            <label class="control-label col-md-4 padding-top-none">Email <span class="text text-danger">*</span></label>

	            <div class="col-md-6">
	                <?php echo Form::text('email', old('email') ? old('email') : $email, ['class'=>'form-control','placeholder'=>'xxxx@xxxxx.com']); ?>

	                <?php if($errors->has('email')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('email')); ?></strong>
	                    </span>
	                <?php endif; ?>
	            </div>
	        </div>	
	        <div class="form-group<?php echo e($errors->has('card_id') ? ' has-error' : ''); ?>">
	            <label class="control-label col-md-4 padding-top-none">Card On File <span class="text text-danger">*</span></label>

	            <div class="col-md-6">
	                <?php echo Form::select('card_id', $card,'',['class'=>'form-control' ]); ?>

	                <?php if($errors->has('card_id')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('card_id')); ?></strong>
	                    </span>
	                <?php endif; ?>
	            </div>
	        </div>				
			<?php else: ?>
			<p>You do not have any card on file.</p>
			<a class="btn btn-info" href="<?php echo e(route('cards_index')); ?>">Add A Card</a>
			<?php endif; ?>
		</div>
		<div class="panel-footer clearfix">  
			<a class="btn btn-default" href="<?php echo e(route('cards_index')); ?>">Manage Card(s) On File</a>
			<button class="btn btn-primary" type="submit" data-toggle="modal" data-target="#loading">Make Payment</button>
		</div>
	</div>
	<?php echo Form::close(); ?>

	<?php else: ?>
	<div class="panel panel-default">
		<div class="panel-heading"><h3>No Invoices To Pay</h3></div>
		<div class="panel-body">

		</div>

		<div class="panel-footer">
			<a href="<?php echo e(route('pages_index')); ?>" class="btn btn-default">Home</a>
		</div>
	</div>
	<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
<?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>