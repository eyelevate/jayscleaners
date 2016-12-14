<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/discounts/add.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <br/>
    <?php echo Form::open(['action' => 'DiscountsController@postEdit','role'=>"form"]); ?>

    <?php echo Form::hidden('id',$discounts->id); ?>

    <div class="panel panel-default">
    	<div class="panel-heading">
    		<h3 class="panel-title">Discount List</h3>
    	</div>
    	<div class="panel-body">
    		<div class="form-group <?php echo e($errors->has('company_id') ? ' has-error' : ''); ?>">
				<label class="control-label">Company</label>
				<?php echo e(Form::select('company_id',$companies,old('company_id') ? old('company_id') : $discounts->company_id,['class'=>"form-control"])); ?>

                <?php if($errors->has('company_id')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('company_id')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
    		<div class="form-group <?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
				<label class="control-label">Name</label>
				<?php echo e(Form::text('name',old('name') ? old('name') : $discounts->name,['class'=>"form-control",'placeholder'=>'Name'])); ?>

                <?php if($errors->has('name')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('name')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div class="form-group <?php echo e($errors->has('type') ? ' has-error' : ''); ?>">
				<label class="control-label">Type</label>
				<?php echo e(Form::select('type',['1'=>'Rate Discount','2'=>'Price Discount'],old('type') ? old('type') : $discounts->type,['class'=>"form-control",'placeholder'=>'type'])); ?>

                <?php if($errors->has('type')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('type')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div id="rate" class="form-group <?php echo e($errors->has('rate') ? ' has-error' : ''); ?>">
				<label class="control-label">Rate</label>
				<?php echo e(Form::text('rate',old('rate') ? old('rate') : $discounts->rate,['class'=>"form-control",'placeholder'=>'rate'])); ?>

                <?php if($errors->has('rate')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('rate')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div id="price" class="form-group <?php echo e($errors->has('price') ? ' has-error' : ''); ?>">
				<label class="control-label">Price</label>
				<?php echo e(Form::text('price',old('price') ? old('price') : $discounts->price,['class'=>"form-control",'placeholder'=>'price'])); ?>

                <?php if($errors->has('price')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('price')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div class="form-group <?php echo e($errors->has('inventory_id') ? ' has-error' : ''); ?>">
				<label class="control-label">Inventory Group</label>
				<?php echo e(Form::select('inventory_id',$inventories,old('inventory_id') ? old('inventory_id') : $discounts->inventory_id,['class'=>"form-control",'placeholder'=>'Select Inventory Group'])); ?>

                <?php if($errors->has('inventory_id')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('inventory_id')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div class="form-group <?php echo e($errors->has('inventory_item_id') ? ' has-error' : ''); ?>">
				<label class="control-label">Inventory Item</label>
				<?php echo e(Form::select('inventory_item_id',$items,old('inventory_item_id') ? old('inventory_item_id') : $discounts->inventory_item_id,['class'=>"form-control",'placeholder'=>'Select Item'])); ?>

                <?php if($errors->has('inventory_item_id')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('inventory_item_id')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div class="form-group <?php echo e($errors->has('start_date') ? ' has-error' : ''); ?>">
				<label class="control-label">Start Date</label>
				<?php echo e(Form::text('start_date',old('start_date') ? old('start_date') : date('Y-m-d',strtotime($discounts->start_date)),['class'=>"datePicker form-control",'placeholder'=>'start_date'])); ?>

                <?php if($errors->has('start_date')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('start_date')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div class="form-group <?php echo e($errors->has('end_date') ? ' has-error' : ''); ?>">
				<label class="control-label">End Date</label>
				<?php echo e(Form::text('end_date',old('end_date') ? old('end_date') : date('Y-m-d',strtotime($discounts->end_date)),['class'=>"datePicker form-control",'placeholder'=>'end_date'])); ?>

                <?php if($errors->has('end_date')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('end_date')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div class="form-group <?php echo e($errors->has('status') ? ' has-error' : ''); ?>">
				<label class="control-label">Status</label>
				<?php echo e(Form::select('status',['1'=>'Active','2'=>'Not Active'],old('status') ? old('status') : $discounts->status,['class'=>"form-control",'placeholder'=>'Select Inventory Group'])); ?>

                <?php if($errors->has('status')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('status')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
    	</div>
    	<div class="panel-footer">
    		<a class="btn btn-danger" href="<?php echo e(route('discounts_index')); ?>">Back</a>
    		<button class="btn btn-primary" type="submit">Edit Discount</button>
    	</div>
    </div>
    <?php echo Form::close(); ?>



<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>