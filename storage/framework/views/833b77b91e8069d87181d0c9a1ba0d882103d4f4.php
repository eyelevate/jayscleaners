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
    <?php if($status): ?>
    <section class="wrapper style2 container special-alt no-background-image">
        <div class="">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <header>
                    <h2>Outstanding! <strong>"<?php echo e($zipcode); ?>"</strong> is covered by our delivery routes!</h2>
                </header>
                <p>Click Here To learn more about our delivery system and how we can provide our quality and price guarantee. </p>
                <footer>
                    <ul class="buttons">
                        <li><a href="<?php echo e(route('pages_registration')); ?>" class="button">Get Started</a></li>
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
    <?php else: ?>
    <section class="wrapper style3 container special-alt no-background-image">
        <div class="">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <header>
                    <h2>Bummer! <strong><?php echo e($zipcode); ?></strong> is not covered by our delivery routes!</h2>
                </header>
                <p>However, we may still offer service in your area. Request a service route, and we will contact you on availability!</p>
                <footer>
                    <ul class="buttons">
                        <li><a href="<?php echo e(route('zipcodes_request',$zipcode)); ?>" class="button">Make Request</a></li>
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

    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>