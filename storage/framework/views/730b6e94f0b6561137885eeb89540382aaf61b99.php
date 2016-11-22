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
    <section class="wrapper style3 container special-alt no-background-image">
        <div class="row 50%">
        	<header>
				<h2><strong>Manage your address(es) here..</strong></h2>
            </header>
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                <section class="row clearfix">
                	<ul class="12u">
                		<?php if($addresses): ?>
                			<?php foreach($addresses as $address): ?>
                				<?php if($address->primary_address): ?>
		                		<li>
									<div class="thumbnail clearfix" style="<?php echo e(($address->zipcode_status) ? '' : 'background-color:#F2DEDE'); ?>">
										<div class="caption clearfix">
                                            <h3 style="text-align:left;"><strong><?php echo e($address->name); ?> </strong> <small><?php echo e(($address->zipcode_status) ? '' : '- zipcode not deliverable'); ?></small> - <a href="#" class="btn btn-sm btn-link">Primary</a></h3>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">       
                                                <strong style="text-align:left;">Address</strong>
                                                <p><i><?php echo e($address->street); ?> <br/> <?php echo e(ucfirst($address->city)); ?> , <?php echo e(strtoupper($address->state)); ?> <?php echo e($address->zipcode); ?></i></p>
											</div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <strong style="text-align:left;">Contact Info</strong>
                                                <p><?php echo e(ucFirst($address->concierge_name)); ?> <br/><?php echo e($address->concierge_number); ?></p>
                                            </div>
                                            <ul class="clearfix col-xs-12 col-md-12 col-sm-12 col-lg-12">
												<li class="pull-left"><a href="<?php echo e(route('address_delete',$address->id)); ?>" class="btn btn-danger" role="button">Delete</a>&nbsp</li>
												<li class="pull-left"><a href="<?php echo e(route('address_edit',$address->id)); ?>" class="btn btn-default" role="button">Edit</a>&nbsp</li>

                                            </ul>
										</div>
									</div>
		                		</li>
                				<?php else: ?>
		                		<li>
									<div class="thumbnail clearfix" style="<?php echo e(($address->zipcode_status) ? '' : 'background-color:#F2DEDE'); ?>">
										<div class="caption clearfix">
                                            <h3 style="text-align:left;"><strong><?php echo e($address->name); ?> </strong> <small><?php echo e(($address->zipcode_status) ? '' : '- zipcode not deliverable'); ?></small> - <a href="#" class="btn btn-sm btn-link">Primary</a></h3>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <strong style="text-align:left;">Address</strong>
                                                <p><i><?php echo e($address->street); ?> <br/> <?php echo e(ucfirst($address->city)); ?> , <?php echo e(strtoupper($address->state)); ?> <?php echo e($address->zipcode); ?></i></p>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <strong style="text-align:left;">Contact Info</strong>
                                                <p><?php echo e(ucFirst($address->concierge_name)); ?> <br/><?php echo e($address->concierge_number); ?></p>
                                            </div>
											<ul class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<li class="pull-left"><a class="btn btn-danger" role="button" data-toggle="modal" data-target="#address_delete-<?php echo e($address['id']); ?>">Delete</a>&nbsp</li>
												<li class="pull-left"><a href="<?php echo e(route('address_edit',$address->id)); ?>" class="btn btn-default" role="button">Edit</a>&nbsp</li>
												<?php if($address->zipcode_status): ?>
                                                <li class="pull-left"><a href="<?php echo e(route('address_primary',$address->id)); ?>" class="btn btn-primary" role="button">Set Primary</a>&nbsp</li>
											    <?php endif; ?>
                                            </ul>
										</div>
									</div>
		                		</li>
                				<?php endif; ?>
                			<?php endforeach; ?>
                		<?php endif; ?>

                	</ul>
				</section>

            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			    <ul>
			    	<li style="margin-bottom:10px;"><a href="<?php echo e((is_array($form_previous)) ? route($form_previous[0],$form_previous[1]) : route($form_previous)); ?>" class="button special-red">Back</a></li>
		            <li><a href="<?php echo e(route('address_add')); ?>" class="button">Add Address</a></li>
		        </ul>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.frontend.modals')->render(); ?>

    <?php if(count($addresses) > 0): ?>
        <?php foreach($addresses as $address): ?>
        <?php echo View::make('partials.frontend.address-confirm-delete')
            ->with('address_id',$address->id)
            ->render(); ?>

        <?php endforeach; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>