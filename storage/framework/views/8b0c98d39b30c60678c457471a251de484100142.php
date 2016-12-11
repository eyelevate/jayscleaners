<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>

<header id="header" class="reveal">
<?php echo View::make('partials.layouts.navigation_logged_out')
    ->render(); ?> 
</header>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
	<?php echo Form::open(['action' => 'PagesController@postResetPassword','role'=>"form"]); ?>

    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">

                    <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                        <label class="control-label padding-top-none">New Password</label>
                        <?php echo e(Form::password('password','',['class'=>'form-control'])); ?>

                        <?php if($errors->has('password')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('password')); ?></strong>
                            </span>
                        <?php endif; ?>

                    </div>
                    <div class="form-group<?php echo e($errors->has('password_confirmation') ? ' has-error' : ''); ?>">
                        <label class="control-label padding-top-none">Confirm Password</label>
                        <?php echo e(Form::password('password_confirmation','',['class'=>'form-control'])); ?>


                        <?php if($errors->has('password_confirmation')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
                            </span>
                        <?php endif; ?>

                    </div>
                    
                </div>
                <div class="panel-footer">
                	<button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>