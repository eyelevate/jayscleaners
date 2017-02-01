<?php $__env->startSection('stylesheets'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/number/jquery.number.min.js"></script>
<script type="text/javascript" src="/packages/numeric/jquery.numeric.js"></script>
<script type="text/javascript" src="/packages/priceformat/priceformat.min.js"></script>
<script type="text/javascript" src="/js/invoices/manage.js"></script>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<br/>
<?php echo Form::open(['action' => 'InvoicesController@postSearch','role'=>"form"]); ?>

<div class="box box-primary clearfix">
	<div class="box-header">
		<h3 class="box-title">Search Invoice</h3>
	</div>
	
	<div class="box-body">	
        <div class="form-group<?php echo e($errors->has('search') ? ' has-error' : ''); ?>">
            <label class="control-label">Search <span class="text text-danger">*</span></label>

            <div class="">
                <?php echo Form::text('search', old('search') ? old('search') : (isset($invoice_id)) ? $invoice_id : NULL, ['id'=>'search_query','class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('search')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('search')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>
	</div>
	<div class="box-footer clearfix">
		<input class="btn btn-success btn-lg" type="submit" value="Search" />
	</div>
</div>
<?php echo Form::close(); ?>


<?php echo Form::open(['action'=>'InvoicesController@postManage','role'=>'form']); ?>


<div class="box box-success clearfix">
	<div class="box-header">
		<h3 class="box-title"><?php echo e(($invoice_id) ? 'Invoice Detail #'.$invoice_id : 'No Invoice Selected'); ?></h3>
	</div>
	<div class="table-responsive">
		<table class="col-md-12 col-lg-12 col-sm-12 table-bordered table-striped table-hover table-condensed">
			<thead>
				<tr>
					<th style="width:50px;">Qty</th>
					<th>Item</th>
					<th style="width:125px;">Subtotal</th>
				</tr>
			</thead>
			<tbody>
			<?php if(count($invoices) > 0): ?>
				<?php foreach($invoices as $invoice): ?>
					<?php if(count($invoice->item_details) > 0): ?>
						<?php foreach($invoice->item_details as $ikey => $item): ?>
						<tr style="cursor:pointer" data-toggle="modal" data-target="#expand-<?php echo e($ikey); ?>">
							<td style="text-align:center;" ><?php echo e($item['qty']); ?></td>
							<td>
								<?php echo e($item['item']); ?>


								<?php if(count($item['color']) > 0): ?>
								<br/>
									<?php $color_string = ''; ?>
									<?php foreach($item['color'] as $color_name => $color_count): ?>
										<?php $color_string .= $color_name.' - '.$color_count.', ';?>
									<?php endforeach; ?>
									<?php echo e(rtrim($color_string,', ')); ?>

								<?php endif; ?>
							</td>
							<td ><input class="col-sm-12 col-xs-12 col-md-12 col-lg-12" type="text" value="<?php echo e(money_format('%i',$item['subtotal'])); ?>"/></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" style="text-align:right;">Total Qty </th>
					<th><?php echo e((count($invoices) > 0) ? $invoices[0]['quantity'] : NULL); ?></th>
				</tr>
				<tr>
					<th colspan="2" style="text-align:right;">Total Subtotal </th>
					<th><?php echo e((count($invoices) > 0) ? $invoices[0]['pretax_html'] : NULL); ?></th>
				</tr>
				<tr>
					<th colspan="2" style="text-align:right;">Total Tax </th>
					<th><?php echo e((count($invoices) > 0) ? money_format('$%i',$invoices[0]['tax']) : NULL); ?></th>
				</tr>
				<tr>
					<th colspan="2" style="text-align:right;">Total Aftertax </th>
					<th><?php echo e((count($invoices) > 0) ? $invoices[0]['total'] : NULL); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="box-footer clearfix">
		<button>Edit Prices</button>
	</div>
</div>
<?php echo Form::close(); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
	<?php if(count($split) > 0): ?>
		<?php foreach($split as $spl_key =>$spl_value): ?>
		<?php echo View::make('partials.invoices.manage')
			->with('items',$spl_value)
			->with('item_id',$spl_key)
			->render(); ?>

		<?php endforeach; ?>

	<?php endif; ?>
	
<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>