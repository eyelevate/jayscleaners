<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

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

	<section class="wrapper style3 container special">
		<div id="store_hours" class="row">
			<header class="clearfix col-xs-12 col-sm-12" style="">
				<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Select Account Payment Method</h3>
			</header>
			<section class="clearfix col-xs-12 col-sm-12">
				<a class="btn btn-lg btn-default" href="<?php echo e(route('accounts_oneTimePayment')); ?>">One Time Payment</a>
				<a class="btn btn-lg btn-primary" href="<?php echo e(route('accounts_memberPayment')); ?>">Membership Payment <b><i>(login required)</i></b></a>
			</section>
		</div>


	</section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>