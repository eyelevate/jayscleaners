<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/js/customers/view.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
	<div class="row clearfix">
		<!-- last 10 -->
		<a href="#" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box bg-aqua" style="padding-bottom:10px" data-toggle="modal" data-target="#last10Customers">
				<div class="inner">
					<h4>Last 10</h4>
					<p>Customers</p>
				</div>
		        <div class="icon">
		          <i class="ion-ios-list"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Rack -->
		<a href="<?php echo e(route('invoices_rack',$customer_id)); ?>" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box bg-aqua" style="padding-bottom:10px">
				<div class="inner">
					<h4>Rack</h4>
					<p>Rack Invoices</p>
				</div>
		        <div class="icon">
		          <i class="ion-ios-barcode"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Pickup -->
		<a href="<?php echo e((isset($customers)) ? route('invoices_pickup',$customers->id) : '#'); ?>" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box <?php echo e((isset($customers)) ? 'bg-green' : 'bg-default'); ?>" style="padding-bottom:10px">
				<div class="inner">
					<h4>Pickup</h4>
					<p>Finish Invoice</p>
				</div>
		        <div class="icon">
		          <i class="ion-cash"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Drop Off -->
		<a href="<?php echo e((isset($customers)) ? route('invoices_dropoff',$customers->id) : '#'); ?>" class="col-lg-3 col-md-3 col-xs-3">
			<!-- small box -->
			<div class="small-box <?php echo e((isset($customers)) ? 'bg-primary' : 'bg-default'); ?>" style="padding-bottom:10px">
				<div class="inner">
					<h4>Drop</h4>
					<p>New Invoice</p>
				</div>
		        <div class="icon">
		          <i class="ion-ios-paper"></i>
		        </div>

			</div>
		</a><!-- ./col -->


	</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<?php if(isset($customers)): ?>
		<?php echo View::make('partials.customers.view-id')
			->with('customers',$customers)
			->with('schedules',$schedules)
			->with('invoices',$invoices)
			->render(); ?>		
	<?php else: ?>
		<?php echo View::make('partials.customers.view'); ?>

	<?php endif; ?>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
	<!-- Modals -->
	<?php echo View::make('partials.customers.last10')
		->with('last10',$last10)
		->render(); ?>

	<?php echo View::make('partials.customers.reprint-card'); ?>

	<?php echo View::make('partials.customers.reprint-invoice'); ?>



	<!-- Invoice data -->
	<?php if(isset($invoices)): ?>
		<?php foreach($invoices as $invoice): ?>
		<?php echo View::make('partials.customers.invoice_items')
			->with('id',$invoice->id)
			->with('invoice_id',$invoice->invoice_id)
			->with('items',$invoice->items)
			->render(); ?>	
		<?php echo View::make('partials.customers.remove-invoice')
			->with('id',$invoice->id)
			->with('invoice_id', $invoice->invoice_id)
			->render(); ?>

		<?php endforeach; ?>
	<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>