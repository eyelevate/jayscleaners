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

<?php echo Form::hidden('invoice_id',$invoice_id); ?>

<div class="box box-success clearfix">
	<div class="box-header">
		<h3 class="box-title"><?php echo e(($invoice_id) ? 'Invoice Detail #'.$invoice_id : 'No Invoice Selected'); ?></h3>
	</div>
	<div class="table-responsive">
		<table class="table-bordered table-striped table-hover table-condensed">
			<thead>
				<tr>
					<th class="col-sm-2 col-md-1 col-lg-1">Qty</th>
					<th class="col-sm-7 col-md-9 col-lg-9">Item</th>
					<th class="col-sm-3 col-md-2 col-lg-2">Subtotal</th>
				</tr>
			</thead>
			<tbody>
			<?php if(count($invoices) > 0): ?>
				<?php foreach($invoices as $invoice): ?>
					<?php if(count($invoice->item_details) > 0): ?>
						<?php foreach($invoice->item_details as $ikey => $item): ?>
						<tr style="cursor:pointer" >
							<td style="text-align:center;" data-toggle="modal" data-target="#expand-<?php echo e($ikey); ?>"><?php echo e($item['qty']); ?></td>
							<td data-toggle="modal" data-target="#expand-<?php echo e($ikey); ?>">
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
							<td ><input class="item_value" old="<?php echo e(money_format('%i',$item['subtotal'])); ?>" name="item[<?php echo e($ikey); ?>]" class="col-sm-12 col-xs-12 col-md-12 col-lg-12" type="text" value="<?php echo e(money_format('%i',$item['subtotal'])); ?>"/></td>
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
					<th><?php echo e((count($invoices) > 0) ? $invoices[0]['total_html'] : NULL); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="box-footer clearfix">
		<button class="btn btn-lg btn-success" type="submit">Edit Prices</button>
	</div>
</div>
<?php echo Form::close(); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
	<?php if(count($split) > 0): ?>
		<?php foreach($split as $spl_key =>$spl_value): ?>
			<?php echo View::make('partials.invoices.manage')
				->with('items',$split[$spl_key]['items'])
				->with('invoice_id',$invoice_id)
				->with('item_id',$spl_key)
				->with('subtotal',$split[$spl_key]['total_subtotal'])
				->render(); ?>

		<?php endforeach; ?>

	<?php endif; ?>
	
<?php $__env->stopSection(); ?>


<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>