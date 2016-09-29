<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
	<h1> Add A Customer <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo e(route('admins_index')); ?>"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="<?php echo e(route('customers_index')); ?>"> Customers</a></li>
		<li class="active">Add</li>
	</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo Form::open(['action' => 'CustomersController@postAdd', 'class'=>'form-horizontal','role'=>"form"]); ?>

        <?php echo csrf_field(); ?>

    <!-- Custom tabs (Charts with tabs)-->
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs pull-right">
        <li><a href="#customer-account" data-toggle="tab">Account Setup</a></li>
        <li><a href="#customer-delivery" data-toggle="tab">Delivery Setup</a></li>
        <li class="active"><a href="#customer-instore" data-toggle="tab">In-Store Only</a></li>
        <li class="pull-left header"><i class="fa fa-inbox"></i> Customer Form</li>
        </ul>
        <div class="tab-content">
            <!-- Morris chart - Sales -->
            <div class="chart tab-pane active" id="customer-instore" style="position: relative;">
                <div class="form-group<?php echo e($errors->has('company_id') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Location <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::select('company_id',$companies , '', ['class'=>'form-control']); ?>

                        <?php if($errors->has('company_id')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('company_id')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Phone <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::text('phone', old('phone'), ['class'=>'form-control', 'placeholder'=>'']); ?>

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
                        <?php echo Form::text('last_name', old('last_name'), ['class'=>'form-control', 'placeholder'=>'']); ?>

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
                        <?php echo Form::text('first_name', old('first_name'), ['class'=>'form-control', 'placeholder'=>'']); ?>

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
                        <?php echo Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=>'']); ?>

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
                        <?php echo Form::select('starch',$starch , '1', ['class'=>'form-control']); ?>

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
                        <?php echo Form::select('hanger',$hanger , '1', ['class'=>'form-control']); ?>

                        <?php if($errors->has('hanger')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('hanger')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>  

                <div class="form-group<?php echo e($errors->has('important_memo') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Important Memo</label>

                    <div class="col-md-6">
                        <?php echo Form::text('important_memo', old('important_memo'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('important_memo')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('important_memo')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>  
                <div class="form-group<?php echo e($errors->has('invoice_memo') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Invoice Memo</label>

                    <div class="col-md-6">
                        <?php echo Form::text('invoice_memo', old('invoice_memo'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('invoice_memo')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('invoice_memo')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>  
  

            </div>
            <div class="chart tab-pane" id="customer-delivery" style="position: relative;">
                <div class="form-group<?php echo e($errors->has('delivery') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Delivery Customer? <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::select('delivery',$delivery , '0', ['class'=>'form-control']); ?>

                        <?php if($errors->has('delivery')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('delivery')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>  
                <div class="form-group<?php echo e($errors->has('mobile') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Mobile</label>

                    <div class="col-md-6">
                        <?php echo Form::text('mobile', old('mobile'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('mobile')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('mobile')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('street') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Street</label>

                    <div class="col-md-6">
                        <?php echo Form::text('street', old('street'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('street')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('street')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('suite') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Suite</label>

                    <div class="col-md-6">
                        <?php echo Form::text('suite', old('suite'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('suite')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('suite')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('city') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">City</label>

                    <div class="col-md-6">
                        <?php echo Form::text('city', old('city'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('city')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('city')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Zipcode</label>

                    <div class="col-md-6">
                        <?php echo Form::text('zipcode', old('zipcode'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('zipcode')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('zipcode')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('concierge_contact') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Concierge Contact</label>

                    <div class="col-md-6">
                        <?php echo Form::text('concierge_contact', old('concierge_contact'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('concierge_contact')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('concierge_contact')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('concierge_number') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Concierge Number</label>

                    <div class="col-md-6">
                        <?php echo Form::text('concierge_number', old('concierge_number'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('concierge_number')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('concierge_number')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('special_instructions') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Special Instructions</label>

                    <div class="col-md-6">
                        <?php echo Form::textarea('special_instructions', old('special_instructions'), ['class'=>'form-control', 'placeholder'=>'']); ?>

                        <?php if($errors->has('special_instructions')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('special_instructions')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                
            </div>
            <div class="chart tab-pane" id="customer-account" style="position: relative;">
                <div class="form-group<?php echo e($errors->has('account') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Account Customer? <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::select('account',$account , '0', ['class'=>'form-control']); ?>

                        <?php if($errors->has('account')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('account')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <input class="btn btn-lg btn-primary pull-right" type="submit" value="Save"/>
        </div>
    </div><!-- /.nav-tabs-custom -->
    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>