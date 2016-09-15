<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="panel panel-default">
    	<div class="panel-heading">
    		<strong>Address Update</strong>
    	</div>
    	<div class="panel-body">
		<?php if($addresses): ?>
			<?php foreach($addresses as $address): ?>
				<?php if($address->primary_address): ?>
				<div class="thumbnail">
					<div class="caption">
						<h3><strong><?php echo e($address->name); ?></strong> - <a href="#" class="btn btn-sm btn-link">Primary</a></h3>
						<p><i><?php echo e($address->street); ?> <br/> <?php echo e(ucfirst($address->city)); ?> , <?php echo e(strtoupper($address->state)); ?> <?php echo e($address->zipcode); ?></i></p>
						<p><strong><?php echo e($address->concierge_name); ?></strong><br/><i><?php echo e($address->concierge_number); ?></i></p>
						<div class="clearfix" >
							<div class="pull-left"><a href="<?php echo e(route('address_admin_delete',$address->id)); ?>" class="btn btn-danger" role="button">Delete</a>&nbsp;</div>
							<div class="pull-left"><a href="<?php echo e(route('address_admin_edit',$address->id)); ?>" class="btn btn-default" role="button">Edit</a>&nbsp;</div>
						</div>
					</div>
				</div>
				<?php else: ?>
				<div class="thumbnail">
					<div class="caption">
						<h3><strong><?php echo e($address->name); ?></strong></h3>
						<p><i><?php echo e($address->street); ?> <br/> <?php echo e(ucfirst($address->city)); ?> , <?php echo e(strtoupper($address->state)); ?> <?php echo e($address->zipcode); ?></i></p>
						<p><strong><?php echo e($address->concierge_name); ?></strong><br/><i><?php echo e($address->concierge_number); ?></i></p>
						<div class="clearfix">
							<div class="pull-left"><a href="<?php echo e(route('address_admin_delete',$address->id)); ?>" class="btn btn-danger" role="button">Delete</a>&nbsp;</div>
							<div class="pull-left"><a href="<?php echo e(route('address_admin_edit',$address->id)); ?>" class="btn btn-default" role="button">Edit</a>&nbsp;</div>
							<div class="pull-left"><a href="<?php echo e(route('address_admin_primary',$address->id)); ?>" class="btn btn-primary" role="button">Set Primary</a>&nbsp;</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
    	</div>
	    <div class="panel-footer clearfix">
	    	<a href="<?php echo e((is_array($form_previous)) ? route($form_previous[0],$form_previous[1]) : route($form_previous)); ?>" class="btn btn-danger btn-lg pull-left">Back</a>
			<a href="<?php echo e(route('address_admin_add',$id)); ?>" class="btn btn-lg btn-primary pull-right">Add Address</a>
	    </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>