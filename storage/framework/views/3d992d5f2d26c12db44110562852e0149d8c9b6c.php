<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>
<header id="header" class="reveal">
<?php echo View::make('partials.layouts.navigation-nodelivery')
    ->render(); ?>

</header>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<!-- <?php echo View::make('partials.pages.contact-us')
	->with('companies',$companies)
    ->render(); ?> -->
<?php echo View::make('partials.pages.contact-us-nodelivery')
	->with('companies',$companies)
    ->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>