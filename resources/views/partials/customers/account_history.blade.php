<div id="account_history" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Account History</h4>
			</div>
			<div class="table-responsive">
				<table class="table table-condensed table-hover table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Date</th>
							<th>Balance</th>
							<th>Paid</th>
							<th>Paid On</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if (isset($history))
							@foreach($history['transactions'] as $transaction)
							<tr class="{{ $transaction['background_color'] }}">
								<td>{{ $transaction['id'] }}</td>
								<td>{{ $transaction['customer_id'] }}</td>
								<td>{{ date('m/Y',strtotime($transaction['created_at'])) }}</td>
								<td>{{ $transaction['total'] }}</td>
								<td>{{ $transaction['account_paid'] }}</td>
								<td>{{ $transaction['account_paid_on'] }}</td>
								<td>{{ $transaction['status_html'] }}</td>
								<td><button type="button">view</button></td>
							</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
			<div class="modal-footer clearfix">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->