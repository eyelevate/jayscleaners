<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">Address Form</div>
        <div class="panel-body">
            <?php echo Form::open(['action' => 'AddressesController@postAdminAdd', 'class'=>'form-horizontal','role'=>"form"]); ?>

                <?php echo e(Form::hidden('customer_id',$customer_id)); ?>

                <?php echo csrf_field(); ?>

                <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label padding-top-none">Address Name <span style="color:#ff0000">*</span></label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" value="<?php echo e(old('name')); ?>" placeholder="e.g. My Home">

                        <?php if($errors->has('name')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('name')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('street') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label padding-top-none">Street Address <span style="color:#ff0000">*</span></label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="street" value="<?php echo e(old('street')); ?>" placeholder="e.g. 12345 1st Ave NE">

                        <?php if($errors->has('street')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('street')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('suite') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label padding-top-none">Suite / Apt #</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="suite" value="<?php echo e(old('suite')); ?>" placeholder="e.g. 201b">

                        <?php if($errors->has('suite')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('suite')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('city') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label padding-top-none">City <span style="color:#ff0000">*</span></label>

                    <div class="col-md-6">
                        <input type="text" class="form-control"  name="city" value="<?php echo e(old('city')); ?>" placeholder="e.g. Seattle">

                        <?php if($errors->has('city')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('city')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('state') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label padding-top-none">State <span style="color:#ff0000">*</span></label>

                    <div class="col-md-6">
                        
                        <?php echo e(Form::select('state',$states,old('state'),['class'=>'form-control'])); ?>

                        <?php if($errors->has('state')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('state')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label padding-top-none">Zipcode <span style="color:#ff0000">*</span></label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="zipcode" value="<?php echo e(old('zipcode')); ?>" placeholder="e.g. 98115">

                        <?php if($errors->has('zipcode')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('zipcode')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('concierge_name') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label padding-top-none">Contact Name <span style="color:#ff0000">*</span></label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="concierge_name" value="<?php echo e(old('concierge_name')); ?>" placeholder="Name of: caretaker, concierge">

                        <?php if($errors->has('concierge_name')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('concierge_name')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('concierge_number') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label padding-top-none">Contact Phone # <span style="color:#ff0000">*</span></label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="concierge_number" data-mask="(000) 000-0000" value="<?php echo e(old('concierge_number')); ?>" placeholder="format (XXX) XXX-XXXX">

                        <?php if($errors->has('concierge_number')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('concierge_number')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4 clearfix">
                    	<a href="<?php echo e(($back_redirect) ? route($back_redirect['route'],$back_redirect['param']) : route('address_admin_index',$customer_id)); ?>" class="btn btn-danger btn-lg">Cancel</a>
                        <button type="submit" class="btn btn-lg btn-primary pull-right">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>