<div id="invoices-<?php echo e($transaction->id); ?>" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Account Invoices?</h4>
			</div>
			<div class="table-responsive">
				<table class="table table-condensed table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Invoice</th>
							<th>Item</th>
							<th>Qty</th>
							<th>Color</th>
							<th>Memo</th>
							<th>Subtotal</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($invoices) > 0): ?>
						<?php foreach($invoices as $invoice): ?>
							<?php if(isset($invoice->invoice_items)): ?>
								<?php foreach($invoice->invoice_items as $invoice_item): ?>
								<tr>
									<td><?php echo e($invoice_item->id); ?></td>
									<td><?php echo e($invoice_item->invoice_id); ?></td>
									<td><?php echo e($invoice_item->item_name); ?></td>
									<td><?php echo e($invoice_item->quantity); ?></td>
									<td><?php echo e($invoice_item->color); ?></td>
									<td><?php echo e($invoice_item->memo); ?></td>
									<td><?php echo e(money_format('$%i',$invoice_item->pretax)); ?></td>
								</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
					<tfoot>
					<?php if(count($transaction) >0): ?>
						<tr>
							<td colspan="5"></td>
							<th>Quantity</th>
							<td><?php echo e($transaction->quantity); ?></td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Subtotal</th>
							<td><?php echo e(money_format('$%i',$transaction->pretax)); ?></td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Tax</th>
							<td><?php echo e(money_format('$%i',$transaction->tax)); ?></td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Total</th>
							<td><?php echo e(money_format('$%i',$transaction->aftertax)); ?></td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Credit</th>
							<td><?php echo e(money_format('$%i',$transaction->credit)); ?></td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Discount</th>
							<td><?php echo e(money_format('$%i',$transaction->discount)); ?></td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Due</th>
							<td><?php echo e(money_format('$%i',$transaction->total)); ?></td>
						</tr>
					<?php endif; ?>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->