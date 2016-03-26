<div id="invoiceModal-{{ $invoice_id }}" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">View Invoice Items [<strong>{{ str_pad($invoice_id, 6, '0', STR_PAD_LEFT) }}</strong>]</h4>
			</div>
			<div class="modal-body">
				<table id="itemTable-{{ $invoice_id }}" class="itemTable no-padding clearfix table table-striped" style="list-style:none;">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Color</th>
							<th>Memo</th>
							<th>Price</th>
						</tr>
					</thead>
					<tbody>
					@if(isset($items))
						@foreach($items as $item)
						<tr>
							<td>{{ $item->id }}</td>
							<td>{{ $item->item_name }}</td>
							<td><?php echo ($item->color) ? '<input type="color" value="'.$item->color.'"/>' : '';?></td>
							<td>{{ $item->memo }}</td>
							<td>{{ $item->pretax }}</td>
						</tr>

						@endforeach
					@endif	
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-info" >Print Invoice</button>
				<button type="button" class="btn btn-info" >Print Tags</button>
				<button type="button" class="btn btn-info" >Rfid</button>
                <a class="btn btn-primary" href="{{ route('invoices_edit',$invoice_id) }}">
                    <div class="icon"><i class="ion-ios-compose-outline"></i> Edit</div>
                </a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->