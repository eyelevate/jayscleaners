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
<section class="wrapper style3 container special">
	<header class="major">
		<h2>Our <strong>Prices</strong></h2>
	</header>
	<?php if(count($price_list) > 0): ?>
		<?php foreach($price_list as $key => $value): ?>
		<div class="thumbnail">
			<div class="caption">
				<h3><strong><?php echo e($key); ?></strong></h3>
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<thead>
							<tr>
								<th style="text-align:left">Name</th>
								<th style="text-align:left">Base Price</th>
							</tr>
						</thead>
						<tbody>
						<?php if(count($value) > 0): ?>
							<?php foreach($value as $item => $price): ?>
							<tr>
								<td style="text-align:left"><?php echo e($item); ?></td>
								<td style="text-align:left"><?php echo e($price); ?></td>
							</tr>
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>	
		<?php endforeach; ?>
	<?php endif; ?>
	<p>Cant find the item you are looking for? Do not fret! We are only displaying prices of our most popular items and of items that have the most inquiry. Please give us a call if you want a specific price of your garment that is not listed above!</p>
</section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>