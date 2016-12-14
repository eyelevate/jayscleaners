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
    			<tbody>
    			<?php if(count($discounts) > 0): ?>
    				<?php foreach($discounts as $discount): ?>
    				<tr class="<?php echo e(($discount->status == 1 ? 'success' : 'error')); ?>">
    					<td><?php echo e($discount->id); ?></td>
    					<td><?php echo e($discount->name); ?></td>
    					<td><?php echo e($discount->type); ?></td>
    					<td><?php echo e($discount->group); ?></td>
    					<td><?php echo e($discount->item); ?></td>
    					<td><?php echo e($discount->rate); ?></td>
    					<td><?php echo e($discount->discount); ?></td>
    					<td><?php echo e(date('n/d/Y g:ia',strtotime($discount->start_date))); ?></td>
    					<td><?php echo e(date('n/d/Y g:ia',strtotime($discount->end_date))); ?></td>
    					<td><?php echo e($discount->status_label); ?></td>
    					<td>
    						<a href="<?php echo e(route('discounts_edit',$discount->id)); ?>">Edit</a> 
    						<a style="color:#ff0000" href="#" data-toggle="modal" data-target="#delete-<?php echo e($discount->id); ?>">Delete</a>
    					</td>
    				</tr>
    				<?php endforeach; ?>
    			<?php endif; ?>
    			</tbody>	
    		</table>
    	</div>
    	<div class="panel-footer">
    		<a class="btn btn-primary" href="<?php echo e(route('discounts_add')); ?>">Create Discount</a>
    	</div>
    </div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
	<?php if(count($discounts) > 0): ?>
		<?php foreach($discounts as $discount): ?>
		<?php echo View::make('partials.discounts.delete')
			->with('id',$discount->id)
			->render(); ?>

		<?php endforeach; ?>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>