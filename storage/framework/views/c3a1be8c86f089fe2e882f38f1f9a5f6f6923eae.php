<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="/css/deliveries/delivery.css" type="text/css">
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
				<h2><strong>Manage your deliveries here..</strong></h2>
            </header>
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                <section class="row clearfix">
                	<ul class="12u">
                	<?php if(count($schedules) > 0): ?> 
                		<?php foreach($schedules as $schedule): ?>
	    				<li>
							<div class="thumbnail">
								<div class="caption clearfix">
									<h3><strong>Delivery #<?php echo e($schedule['id']); ?></strong><br/><small>created: <?php echo e($schedule['created_at']); ?></small></h3>
									<ul>
										<li class="clearfix col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<p class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
												<a href="<?php echo e(route('delivery_index')); ?>">Contact Info</a>
												<br/>
												<i><?php echo e($schedule['contact_name']); ?></i>
												<br/>
												<i><?php echo e($schedule['contact_number']); ?></i>
											</p>
											<p class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
												<a href="<?php echo e(route('address_index')); ?>"><?php echo e($schedule['address_name']); ?></a>
												<br/>
												<i><?php echo e($schedule['pickup_address_1']); ?>

												</br><?php echo e($schedule['pickup_address_2']); ?>

												</i>
											</p>
											<p class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
												<a href="<?php echo e(route('delivery_index')); ?>">Pickup Date</a>
												<br/>
												<i><?php echo e($schedule['pickup_date']); ?></i>
												<br/>
												<i><?php echo e($schedule['pickup_time']); ?></i>
											</p>
										</li>
										<li class="clearfix col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<p class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
												<a href="<?php echo e(route('delivery_index')); ?>">Dropoff Date</a>
												<br/>
												<i><?php echo e($schedule['dropoff_date']); ?></i>
												<br/>
												<i><?php echo e($schedule['dropoff_time']); ?></i>
											</p>
											<p class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
												<a href="<?php echo e(route('delivery_index')); ?>">Special Instructions</a>
												<br/>
												<i><?php echo e($schedule['special_instructions']); ?></i>
											</p>
										</li>
										<li class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label class="control-label"><?php echo e($schedule['status_message']); ?></label>
											<div class="progress">
												<?php if($schedule['status'] == 1): ?>
												<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
													<span class="sr-only">20% Complete (success)</span>
												</div>

												<?php elseif($schedule['status'] == 2): ?>
												<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
													<span class="sr-only">30% Complete (success)</span>
												</div>
												<?php elseif($schedule['status'] == 3): ?>
												<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
													<span class="sr-only">40% Complete (success)</span>
												</div>
												<?php elseif($schedule['status'] == 4): ?>
												<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
													<span class="sr-only">50% Complete (success)</span>
												</div>
												<?php elseif($schedule['status'] == 5): ?>
												<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
													<span class="sr-only">75% Complete (success)</span>
												</div>
												<?php elseif($schedule['status'] == 6): ?>
												<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
													<span class="sr-only">100% Complete (cancelled by user)</span>
												</div>
												<?php elseif($schedule['status'] == 7): ?>
												<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
													<span class="sr-only">50% Complete (success)</span>
												</div>
												<?php elseif($schedule['status'] == 8): ?>
												<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
													<span class="sr-only">30% Complete (Delayed)</span>
												</div>
												<?php elseif($schedule['status'] == 9): ?>
												<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
													<span class="sr-only">75% Complete (success)</span>
												</div>
												<?php elseif($schedule['status'] == 10): ?>
												<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
													<span class="sr-only">80% Complete (success)</span>
												</div>
												<?php elseif($schedule['status'] == 11): ?>
												<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
													<span class="sr-only">90% Complete (success)</span>
												</div>
												<?php elseif($schedule['status'] == 12): ?>
												<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
													<span class="sr-only">100% Complete (success)</span>
												</div>
												<?php endif; ?>
											</div>
										<li>
									</ul>


									<ul class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<li class="pull-left"><a href="<?php echo e(route('delivery_delete',$schedule['id'])); ?>" class="btn btn-danger" role="button">Cancel</a>&nbsp</li>
										<li class="pull-left"><a href="<?php echo e(route('delivery_update',$schedule['id'])); ?>" class="btn btn-default" role="button">Edit</a>&nbsp</li>
									</ul>
								</div>
							</div>
	            		</li>
	            		<?php endforeach; ?>
                	<?php endif; ?>
                	</ul>
				</section>

            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			    <ul>
		            <li><a href="<?php echo e(route('delivery_start')); ?>" class="button">New Delivery</a></li>
		        </ul>
            </div>
        </div>
    </section>


<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>