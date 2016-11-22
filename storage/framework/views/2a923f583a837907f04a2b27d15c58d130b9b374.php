<div id="view-<?php echo e($invoice->id); ?>" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Invoice View #<?php echo e(str_pad($invoice->id, 6, '0', STR_PAD_LEFT)); ?></h4>
			</div>
			<div class="modal-body clearfix form-horizontal">
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Invoice Number</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control"><?php echo e(str_pad($invoice->id, 6, '0', STR_PAD_LEFT)); ?></label>
                	</div>
                </div>	
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Status</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control"><?php echo e($invoice->status); ?></label>
                	</div>
                </div>	
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Rack Number</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control"><?php echo e($invoice->rack); ?></label>
                	</div>
                </div>	
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Rack Date</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control"><?php echo e(($invoice->rack_date) ? date('D n/d/Y',strtotime($invoice->rack_date)) : ''); ?></label>
                	</div>
                </div>	
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Invoice Memo</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control"><?php echo e($invoice->memo); ?></label>
                	</div>
                </div>	
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Dropoff Date</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control"><?php echo e(date('D n/d/Y',strtotime($invoice->created_at))); ?></label>
                	</div>
                </div>	
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Due Date</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control"><?php echo e(date('D n/d/Y',strtotime($invoice->due_date))); ?></label>
                	</div>
                </div>	
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Transaction #</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control"><?php echo e($invoice->transaction_id); ?></label>
                	</div>
                </div>		
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Schedule #</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control"><?php echo e($invoice->schedule_id); ?></label>
                	</div>
                </div>                	
			</div>
			<div class="table table-responive">
				<table class="table table-condensed table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Color</th>
							<th>Memo</th>
							<th>Subtotal</th>
							<th>Tax</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($invoice->items) > 0): ?>
						<?php foreach($invoice->items as $item): ?>
						<tr>
							<td><?php echo e($item->id); ?></td>
							<td><?php echo e($item->item_name); ?></td>
							<td><?php echo e($item->color); ?></td>
							<td><?php echo e($item->memo); ?></td>
							<td><?php echo e(money_format('$%i',$item->pretax)); ?></td>
							<td><?php echo e(money_format('$%i',$item->tax)); ?></td>
							<td><?php echo e(money_format('$%i',$item->total)); ?></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					<tbody>
					<tfoot>
						<tr>
							<th colspan="6" style="text-align:right">Qty</th>
							<td><?php echo e($invoice->quantity); ?></td>
						</tr>
						<tr>
							<th colspan="6" style="text-align:right">Subtotal</th>
							<td><?php echo e(money_format('$%i',$invoice->pretax)); ?></td>
						</tr>
						<tr>
							<th colspan="6" style="text-align:right">Tax</th>
							<td><?php echo e(money_format('$%i',$invoice->tax)); ?></td>
						</tr>
						<tr>
							<th colspan="6" style="text-align:right">Discount</th>
							<th></th>
						</tr>
						<tr>
							<th colspan="6" style="text-align:right">Total</th>
							<td><?php echo e($invoice->total); ?></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer clearfix">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
				<a class="btn btn-lg btn-danger" data-toggle="modal" data-target="#remove-<?php echo e($invoice->id); ?>" >Delete</a>
				<a href="<?php echo e(route('invoices_edit',$invoice->id)); ?>" class="btn btn-lg btn-info">Edit</a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->