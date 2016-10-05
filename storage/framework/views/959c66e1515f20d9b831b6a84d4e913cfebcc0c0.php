<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/Readmore/readmore.min.js"></script>
<script type="text/javascript" src="/js/pages/index.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>
<header id="header" class="reveal">
<?php if(Auth::check()): ?>
<?php echo View::make('partials.layouts.navigation_logged_in')
    ->render(); ?>

<?php else: ?>
<?php echo View::make('partials.layouts.navigation_logged_out')
    ->render(); ?>

<?php endif; ?>
</header>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php echo View::make('partials.pages.services')->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>