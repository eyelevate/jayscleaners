<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <br/>
    <div class="panel panel-default">
    	<div class="panel-heading">
    		<h3 class="panel-title">Discount List</h3>
    	</div>
    	<div class="table-responsive">
    		<table class="table table-hover table-stripped">
    			<thead>
    				<tr>
    					<th>ID</th>
    					<th>Name</th>
    					<th>Type</th>
    					<th>Group</th>
    					<th>Item</th>
    					<th>Rate</th>
    					<th>Price</th>
    					<th>Start</th>
    					<th>End</th>
    					<th>Status</th>
    					<th>Action</th>
    				</tr>
    			</thead>	
    		</table>
    	</div>
    	<div class="panel-footer">
    		<a class="btn btn-primary" href="<?php echo e(route('discounts_add')); ?>">Create Discount</a>
    	</div>
    </div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>