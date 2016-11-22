<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
 	<br/>
    <section class="panel panel-default">
        <div class="panel-heading">
        	<header>
				<h2><strong>Credit Cards</strong></h2>
            </header>
        </div>
        <div class="panel-body">
    	<?php if(count($cards_data) > 0): ?> 
    		<?php foreach($cards_data as $card): ?>

				<div class="thumbnail" style="background-color:<?php echo e($card['background_color']); ?>; font-size:17px;">
					<div class="caption">
						<h3><strong><?php echo e($card['card_type']); ?> <?php echo e($card['card_number']); ?></strong> <span class="pull-right"><img src="<?php echo e($card['card_image']); ?>" /></span></h3>
						<p style="margin-bottom:5px;"><?php echo e($card['first_name']); ?> <?php echo e($card['last_name']); ?></p>
						<p class="clearfix"> <strong>Expiration Date:</strong>&nbsp;<?php echo e($card['exp_month']); ?>/<?php echo e($card['exp_year']); ?> <span class="pull-right"><?php echo e($card['days_remaining']); ?></span></p>
						<div class="clearfix">
							<a href="<?php echo e(route('cards_admins_delete',$card['id'])); ?>" class="btn btn-danger" role="button">Delete</a>
							<a href="<?php echo route('cards_admins_edit',$card['id']); ?>" class="btn btn-default" role="button">Edit</a>
						</div>
					</div>
				</div>
    		<?php endforeach; ?>
    	<?php endif; ?>
	    </div>
        <div class="panel-footer">
			<a href="<?php echo e(route('delivery_new',$customer_id)); ?>" class="btn btn-danger">Back</a>
            <a href="<?php echo e(route('cards_admins_add',$customer_id)); ?>" class="btn btn-primary">Add Card</a>
        </div>
    </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>