<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>

    <header id="header" class="reveal">
    <?php echo View::make('partials.layouts.navigation_logged_in')
        ->render(); ?>

    </header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo Form::open(['action' => 'UsersController@postUpdate', 'class'=>'form-horizontal','role'=>"form"]); ?>

        <?php echo csrf_field(); ?>

        <!-- Tabs within a box -->
        <div class="panel panel-default">
            <div class="panel-heading"><label>Customer Update Form</label></div>
            <div class="panel-body">
                <div class="form-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Phone <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::text('phone', old('phone') ? old('phone') : $customers->phone, ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('phone')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('phone')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('last_name') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Last Name <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::text('last_name', old('last_name') ? old('last_name') : $customers->last_name, ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('last_name')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('last_name')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div> 
                <div class="form-group<?php echo e($errors->has('first_name') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">First Name <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::text('first_name', old('first_name') ? old('first_name') : $customers->first_name, ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('first_name')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('first_name')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div> 
                <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Email</label>

                    <div class="col-md-6">
                        <?php echo Form::email('email', old('email') ? old('email') : $customers->email, ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('email')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('email')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>  

                <div class="form-group<?php echo e($errors->has('starch') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Shirt Starch Preferrence</label>

                    <div class="col-md-6">
                        <?php echo Form::select('starch', $starch , old('starch') ? old('starch') : $customers->starch, ['class'=>'form-control']); ?>

                        <?php if($errors->has('starch')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('starch')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>  
                <div class="form-group<?php echo e($errors->has('hanger') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Shirt Finish</label>

                    <div class="col-md-6">
                        <?php echo Form::select('hanger', $hanger, old('hanger') ? old('hanger') : $customers->shirt, ['class'=>'form-control']); ?>

                        <?php if($errors->has('hanger')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('hanger')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>  
                <div class="thumbnail">
                	<br/>
	                <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
	                    <label class="col-md-4 control-label">New Password </label>

	                    <div class="col-md-6">
	                        <?php echo Form::password('password', old('password'), ['class'=>'form-control', 'placeholder'=>'']); ?>

	                        <?php if($errors->has('password')): ?>
	                            <span class="help-block">
	                                <strong><?php echo e($errors->first('password')); ?></strong>
	                            </span>
	                        <?php endif; ?>
	                    </div>
	                </div>
	                <div class="form-group<?php echo e($errors->has('password_confirmation') ? ' has-error' : ''); ?>">
	                    <label class="col-md-4 control-label">Confirm Password</label>

	                    <div class="col-md-6">
	                        <?php echo Form::text('password_confirmation', old('password_confirmation'), ['class'=>'form-control', 'placeholder'=>'']); ?>

	                        <?php if($errors->has('password_confirmation')): ?>
	                            <span class="help-block">
	                                <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
	                            </span>
	                        <?php endif; ?>
	                    </div>
	                </div> 
	                <br/>
            	</div>



  

            </div>

	        <div class="panel-footer clearfix">
	            <input class="btn btn-lg btn-primary pull-right" type="submit" value="Save"/>
	        </div>
    	</div><!-- /.nav-tabs-custom -->
    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>