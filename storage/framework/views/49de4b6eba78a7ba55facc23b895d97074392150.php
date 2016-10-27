<?php $__env->startSection('stylesheets'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/number/jquery.number.min.js"></script>
<script type="text/javascript" src="/packages/numeric/jquery.numeric.js"></script>
<script type="text/javascript" src="/packages/priceformat/priceformat.min.js"></script>
<script type="text/javascript" src="/js/invoices/manage.js"></script>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<br/>

<div class="box box-primary clearfix">
	<div class="box-header">
		<h3 class="box-title">Invoice Item ID</h3>
	</div>
	
	<div class="box-body">	
        <div class="form-group<?php echo e($errors->has('search') ? ' has-error' : ''); ?>">
            <label class="control-label">Search <span class="text text-danger">*</span></label>

            <div class="">
                <?php echo Form::text('search', old('search'), ['id'=>'search_query','class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('search')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('search')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>
	</div>
	<div class="box-footer clearfix">
		<button type="button" id="search_item" class="btn btn-lg btn-success pull-right" data-toggle="modal" data-target="#update">Search</button>
	</div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
	<?php echo View::make('partials.invoices.manage')
		->with('locations',$locations)
		->with('companies',$companies)
		->with('company_id',1)
		->render(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>