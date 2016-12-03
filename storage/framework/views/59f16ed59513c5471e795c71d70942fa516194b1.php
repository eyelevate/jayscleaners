<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

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
	<?php if(count($transactions) > 0): ?>
		<?php foreach($transactions as $transaction): ?>
		<?php echo Form::open(['action' => 'AccountsController@postOneTimeFinish', 'class'=>'form-horizontal','role'=>"form"]); ?>

		<?php echo Form::hidden('company_id',$transaction->company_id); ?>

		<section class="wrapper style3 container special">
			<div id="store_hours" class="row">
				<header class="clearfix col-xs-12 col-sm-12" style="">
					<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">One Time Payment Method</h3>
				</header>
				<section class="clearfix col-xs-12 col-sm-12">
					
		       	 	
			        <div class="form-group<?php echo e($errors->has('total') ? ' has-error' : ''); ?>">
			            <label class="control-label col-md-4 padding-top-none">Total Due</label>

			            <div class="col-md-6">
			            	<p style="text-align:left;"><strong><?php echo e(money_format('$%i',$transaction->total)); ?></strong></p>
			               
			                <?php if($errors->has('total')): ?>
			                    <span class="help-block">
			                        <strong><?php echo e($errors->first('total')); ?></strong>
			                    </span>
			                <?php endif; ?>
			            </div>
			        </div>
			        <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
			            <label class="control-label col-md-4 padding-top-none">Email <span class="text text-danger">*</span></label>

			            <div class="col-md-6">
			                <?php echo Form::text('email', old('email'), ['class'=>'form-control','placeholder'=>'xxxx@xxxxx.com']); ?>

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
			                <?php echo Form::text('name', old('name'), ['class'=>'form-control','maxlength'=>'20','placeholder'=>'John Doe']); ?>

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
			        <div class="form-group">
			        	<div class="col-md-offset-4" style="padding-left:15px;">
			        		<button class="btn btn-primary pull-left" type="submit">Make Payment</button>
			        	</div>
		       	 	</div>
				</section>
			</div>


		</section>
		<?php echo Form::close(); ?>

		<?php endforeach; ?>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>