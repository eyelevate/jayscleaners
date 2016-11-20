<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/number/jquery.number.min.js"></script>
<script type="text/javascript" src="/packages/numeric/jquery.numeric.js"></script>
<script type="text/javascript" src="/packages/priceformat/priceformat.min.js"></script>

<script type="text/javascript" src="/js/invoices/pickup.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<br/>
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
		<article class="col-xs-12 col-sm-6 col-md-6 col-lg-8">
			<div class="box box-primary" >
				<div class="box-header cleafix">
					<h4 class="clearfix">Select Invoice 
					<?php if($customers->account): ?>
						<span class="label label-danger pull-right">Account Customer</span>
					<?php endif; ?>
					</h4>
				</div>
				<div class="table-responsive">
					<table class="table table-hover table-condensed">
						<thead>
							<tr>
								<th>ID</th>
								<th>Rack</th>
								<th>Drop</th>
								<th>Due</th>
								<th>Qty</th>
								<th>Subtotal</th>
							</tr>
						</thead>
						<tbody id="invoice_tbody">
						<?php if(count($invoices) > 0): ?>
							<?php foreach($invoices as $invoice): ?>
							<tr id="invoice_tr-<?php echo e($invoice->id); ?>" class="invoice_tr" style="cursor:pointer">
								<td><?php echo e(str_pad($invoice->id, 6, '0', STR_PAD_LEFT)); ?></td>
								<td><?php echo e($invoice->rack); ?></td>
								<td><?php echo e(date('D n/d',strtotime($invoice->created_at))); ?></td>
								<td><?php echo e(date('D n/d',strtotime($invoice->due_date))); ?></td>
								<td><?php echo e($invoice->quantity); ?></td>
								<td><?php echo e(money_format('$%i',$invoice->pretax)); ?></td>
							</tr>
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="box-footer clearfix">
					<a href="<?php echo e(route('customers_view',$customer_id)); ?>" class="btn btn-lg btn-danger">Back</a>

				</div>
			</div>
		</article>
		<article class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
			<div class="box box-info">
				<div class="box-header">
					<h4 class="clearfix">Invoice Selected 
					<?php if($customers->account): ?>
						<span class="label label-danger pull-right">Account Customer</span>
					<?php endif; ?>
					</h4>
				</div>
				<div class="table-responsive">
					<table class="table table-condensed table-striped table-hover">
						<thead>
							<th>ID</th>
							<th>Drop</th>
							<th>Due</th>
							<th>Qty</th>
							<th>Subtotal</th>
						</thead>
						<tbody id="selected_tbody">
						</tbody>
						<tfoot>
							<tr>
								<th colspan="4" style="text-align:right;">Quantity</th>
								<td id="quantity_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Subtotal</th>
								<td id="subtotal_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Tax</th>
								<td id="tax_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Total</th>
								<td id="total_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Store Credit</th>
								<td id="credit_td" credit="<?php echo e($credits); ?>"><?php echo e(money_format('$%i',$credits)); ?></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Discount</th>
								<td id="discount_td"></td>
							</tr>
							<tr>
								<th colspan="4" style="text-align:right;">Total Due</th>
								<td id="due_td"></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="box-footer clearfix">
					<?php if($customers->account): ?>
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#account">Account Finish</button>
					<?php else: ?>
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#credit"><i class="ion ion-card"></i>&nbsp;Credit</button>
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#cash"><i class="ion ion-cash"></i>&nbsp;Cash</button>
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#cof"><i class="ion ion-folder"></i>&nbsp;CoF</button>
						<button type="button" class="btn btn-success" data-toggle="modal" data-target="#check">Check</button>
					<?php endif; ?>
				</div>
			</div>
		</article>
	</section>
	<section class="hide">
		<?php echo Form::open(['action' => 'InvoicesController@postPickup','role'=>"form",'id'=>'invoiceForm']); ?>

		<?php echo e(Form::hidden('customer_id',$customer_id,['id'=>"customer_id"])); ?>

			<div id="invoice_form" class="hide">

			</div>
		<?php echo Form::close(); ?>

	</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
	<?php echo View::make('partials.invoices.account')
		->render(); ?>

	<?php echo View::make('partials.invoices.credit')
		->render(); ?>

	<?php echo View::make('partials.invoices.cash')
		->render(); ?>

	<?php echo View::make('partials.invoices.cof')
		->with('cards',$cards)
		->render(); ?>

	<?php echo View::make('partials.invoices.check')
		->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>