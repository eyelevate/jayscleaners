<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<br/>
<?php echo Form::open(['action' => 'ZipcodesController@postAdd','role'=>"form"]); ?>

	<div class="panel panel-default">
		<div class="panel-heading"><h4 class="panel-title">Add Zipcode</h4></div>
		<div class="panel-body" >
            <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?>">
                <label class="control-label padding-top-none">Zipcode <span style="color:#ff0000">*</span></label>

                <?php echo e(Form::text('zipcode',old('zipcode'),['class'=>'form-control'])); ?>

                <?php if($errors->has('zipcode')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('zipcode')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>			
		</div>
		<div class="panel-footer">
			<a href="<?php echo e(route('zipcodes_index')); ?>" class="btn btn-danger">Back</a>
			<button class="btn btn-primary pull-right" type="submit">Add</button>
		</div>
	</div>
<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>