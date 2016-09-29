<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>
<header id="header" class="reveal">
<?php echo View::make('partials.layouts.navigation_logged_out')
    ->render(); ?>

</header>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php echo Form::open(['action' => 'ZipcodesController@postRequest', 'class'=>'form-horizontal','role'=>"form"]); ?>

<section class="wrapper style2 container special-alt no-background-image">
    <div class="">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <header>
                <h2>Request Form</h2>
            </header>
            <p>Please fill out this request form to get us out and deliver to your area. We take every request very seriously and appreciate your understanding of this matter. Thank you! </p>
            <section>
	            <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?>">
	                <label class="control-label">Zipcode</label>

                    <?php echo Form::text('zipcode', old('zipcode') ? old('zipcode') : $zipcode, ['placeholder'=>'','class'=>'form-control', 'style'=>'background-color:#ffffff; color: #000000;']); ?>

                    <?php if($errors->has('zipcode')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('zipcode')); ?></strong>
                        </span>
                    <?php endif; ?>

	            </div>
	            <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
	                <label class="control-label">Full Name</label>

                    <?php echo Form::text('name', old('name'), ['placeholder'=>'','class'=>'form-control', 'style'=>'background-color:#ffffff; color: #000000;']); ?>

                    <?php if($errors->has('name')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('name')); ?></strong>
                        </span>
                    <?php endif; ?>

	            </div>
	            <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
	                <label class="control-label">Email Address</label>

                    <?php echo Form::email('email', old('email'), ['placeholder'=>'','class'=>'form-control', 'style'=>'background-color:#ffffff; color: #000000;']); ?>

                    <?php if($errors->has('email')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('email')); ?></strong>
                        </span>
                    <?php endif; ?>

	            </div>
	            <div class="form-group<?php echo e($errors->has('comment') ? ' has-error' : ''); ?>">
	                <label class="control-label">Additional Comments</label>

                    <?php echo Form::textarea('comment', old('comment'), ['placeholder'=>'','class'=>'form-control', 'style'=>'background-color:#ffffff; color: #000000;']); ?>

                    <?php if($errors->has('comment')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('comment')); ?></strong>
                        </span>
                    <?php endif; ?>

	            </div>
            </section>	

            <footer>
                <ul class="buttons">
                    <li><button type="submit" class="button">Submit Request</button></li>
                </ul>
            </footer>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
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
</section>
<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>