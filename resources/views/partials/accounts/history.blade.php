<div id="invoices-{{ $transaction->id }}" class="modal fade" tabindex="-1" role="dialog">
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
					@if (count($invoices) > 0)
						@foreach($invoices as $invoice)
							@if (isset($invoice->invoice_items))
								@foreach ($invoice->invoice_items as $invoice_item)
								<tr>
									<td>{{ $invoice_item->id }}</td>
									<td>{{ $invoice_item->invoice_id }}</td>
									<td>{{ $invoice_item->item_name }}</td>
									<td>{{ $invoice_item->quantity }}</td>
									<td>{{ $invoice_item->color }}</td>
									<td>{{ $invoice_item->memo }}</td>
									<td>{{ money_format('$%i',$invoice_item->pretax) }}</td>
								</tr>
								@endforeach
							@endif
						@endforeach
					@endif
					</tbody>
					<tfoot>
					@if (count($transaction) >0)
						<tr>
							<td colspan="5"></td>
							<th>Quantity</th>
							<td>{{ $transaction->quantity }}</td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Subtotal</th>
							<td>{{ money_format('$%i',$transaction->pretax) }}</td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Tax</th>
							<td>{{ money_format('$%i',$transaction->tax) }}</td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Total</th>
							<td>{{ money_format('$%i',$transaction->aftertax) }}</td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Credit</th>
							<td>{{ money_format('$%i',$transaction->credit) }}</td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Discount</th>
							<td>{{ money_format('$%i',$transaction->discount) }}</td>
						</tr>
						<tr>
							<td colspan="5"></td>
							<th>Due</th>
							<td>{{ money_format('$%i',$transaction->total) }}</td>
						</tr>
					@endif
					</tfoot>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->