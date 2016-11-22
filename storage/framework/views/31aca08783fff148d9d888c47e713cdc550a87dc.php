<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="/css/deliveries/delivery.css" type="text/css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/js/deliveries/confirmation.js"></script>
<script type="text/javascript">

</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>
    <header id="header" class="reveal">
    <?php echo View::make('partials.layouts.navigation_logged_in')
        ->render(); ?>

    </header>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div id="bc1" class="btn-group btn-breadcrumb col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <a href="<?php echo e(route('delivery_pickup')); ?>" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px; overflow:hidden">
            	<h2><span class="badge">1</span> Pickup</h2>
        		<table class="table table-condensed ">
        			<tbody>
        				<tr>
        					<td><p style="margin:0;"><?php echo e($breadcrumb_data['pickup_address']); ?></p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0"><?php echo e($breadcrumb_data['pickup_date']); ?></p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0"><?php echo e($breadcrumb_data['pickup_time']); ?></p></td>
        				</tr>
        			</tbody>
        		</table>
            </a>
            <a href="<?php echo e(route('delivery_dropoff')); ?>" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px; overflow:hidden">
            	<h2><span class="badge">2</span> Dropoff</h2>
            	<table class="table table-condensed ">
        			<tbody>
        				<tr>
        					<td><p style="margin:0;"><?php echo e($breadcrumb_data['dropoff_address']); ?></p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0"><?php echo e($breadcrumb_data['dropoff_date']); ?></p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0"><?php echo e($breadcrumb_data['dropoff_time']); ?></p></td>
        				</tr>
        			</tbody>
        		</table>
            </a>
            <a href="<?php echo e(route('delivery_confirmation')); ?>" class="btn btn-default active col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px">
            	<h2><span class="badge">3</span> Confirm</h2>
            	<p>Not yet confirmed.</p>
            </a>

        </div>
	</div>
	<div class="row"><p></p></div>
	<?php if(count($cards) > 0): ?>
    <div class="wrapper style2 special-alt no-background-image">
    	<div class="row 50%">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <header>
                    <h2>Confirmation Of Delivery</h2>
                </header>
                <p>Please review your delivery confirmation form data below, then select your desired card on file to finalize the delivery.</p>
                <section>
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-4 control-label padding-top-none">First Name:</label>

                            <div class="col-md-6">
                                <strong>    
                                    <label class="control-label padding-top-none disabled"><?php echo e(ucFirst(Auth::user()->first_name)); ?></label>
                                </strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label padding-top-none">Last Name:</label>

                            <div class="col-md-6">
                                <strong>
                                    <label class="control-label padding-top-none disabled"><?php echo e(ucFirst(Auth::user()->last_name)); ?></label>
                                </strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label padding-top-none">Delivery Address:</label>

                            <div class="col-md-6">
                                <strong>
                                    <label class="control-label padding-top-none disabled col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left; padding-left:0px;"><?php echo e($delivery_address[0]); ?></label>
                                    <label class="control-label passing-top-none disabled"><?php echo e($delivery_address[1]); ?></label>
                                </strong>
                                <small>
                                    <a href="<?php echo e(route('delivery_pickup')); ?>" class="btn btn-primary btn-sm">edit</a>
                                </small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label padding-top-none">Pickup Date:</label>

                            <div class="col-md-6">
                                <strong>
                                    <label class="control-label padding-top-none disabled"><?php echo e($pickup_date); ?></label>
                                </strong>
                                <a href="<?php echo e(route('delivery_pickup')); ?>" class="btn btn-primary btn-sm">edit</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label padding-top-none">Pickup Time:</label>

                            <div class="col-md-6">
                                <strong>
                                    <label class="control-label padding-top-none disabled"><?php echo e($pickup_time); ?></label>   
                                </strong>
                                <small>
                                    <a href="<?php echo e(route('delivery_pickup')); ?>" class="btn btn-primary btn-sm">edit</a>
                                </small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Date:</label>

                            <div class="col-md-6">
                                <strong>
                                    <label class="control-label padding-top-none disabled"><?php echo e($dropoff_date); ?></label>
                                </strong>
                                <small>
                                    <a href="<?php echo e(route('delivery_dropoff')); ?>" class="btn btn-primary btn-sm">edit</a>
                                </small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Time:</label>

                            <div class="col-md-6">
                                <strong>
                                    <label class="control-label padding-top-none disabled"><?php echo e($dropoff_time); ?></label>
                                </strong>
                                <small>
                                    <a href="<?php echo e(route('delivery_dropoff')); ?>" class="btn btn-primary btn-sm">edit</a>
                                </small>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?php echo Form::open(['action' => 'DeliveriesController@postConfirmation', 'class'=>'','role'=>"form"]); ?>

                    <?php echo csrf_field(); ?>

                    <div class="form-group<?php echo e($errors->has('payment_id') ? ' has-error' : ''); ?>">
                        <label class="col-md-12 control-label padding-top-none">Card on file <span style="color:#ff0000">*</span></label>

                        <div class="col-md-12">
                            
                            <?php echo e(Form::select('payment_id',$payment_ids,old('payment_id'),['class'=>'form-control'])); ?>

                            <?php if($errors->has('payment_id')): ?>
                                <span class="help-block">
                                    <strong><?php echo e($errors->first('payment_id')); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e(route('cards_index')); ?>" class="btn btn-link" style="color:#ffffff">Manage my cards on file</a>
                    </div>
                    <div class="form-group<?php echo e($errors->has('payment_id') ? ' has-error' : ''); ?> clearfix">
                        <label class="col-md-12 control-label padding-top-none">Special Instructions (<small>optional</small>)</label>

                        <div class="col-md-12 clearfix">
                            
                            <?php echo e(Form::textarea('special_instructions',old('special_instructions'),['class'=>'form-control'])); ?>

                            <?php if($errors->has('special_instructions')): ?>
                                <span class="help-block">
                                    <strong><?php echo e($errors->first('special_instructions')); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group" >
                        <ul class="buttons col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="margin:0px;">
                            <li><input type="submit" data-toggle="modal" data-target="#loading" class="button" value="Confirm"/></li>
                        </ul>
                    </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="wrapper style3 special-alt no-background-image">
        <div class="row 50%">
            <div class="8u">
                <header>
                    <h2>No credit card on file!</h2>
                </header>
                <p>In order for us to confirm your delivery schedule we must have at least one qualified credit card on file. Please use the link below to setup your stored credit card information.</p>
                <footer>
                    <ul class="buttons">
                        <li><a href="<?php echo e(route('cards_index')); ?>" class="button">Manage Credit Cards</a></li>
                    </ul>
                </footer>
            </div>
            <div class="4u">
                <ul class="featured-icons">
                    <li><span class="icon fa-clock-o"><span class="label">Feature 1</span></span></li>
                    <li><span class="icon fa-volume-up"><span class="label">Feature 2</span></span></li>
                    <li><span class="icon fa-laptop"><span class="label">Feature 3</span></span></li>
                    <li><span class="icon fa-inbox"><span class="label">Feature 4</span></span></li>
                    <li><span class="icon fa-lock"><span class="label">Feature 5</span></span></li>
                    <li><span class="icon fa-cog"><span class="label">Feature 6</span></span></li>
                </ul>
            </div>
        </div>
    </div>
	<?php endif; ?>		


<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>