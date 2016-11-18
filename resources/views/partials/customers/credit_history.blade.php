<div id="credit_history" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Credit History</h4>
			</div>
			<div class="table-responsive">
				<table class="table table-condensed table-hover table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Employee</th>
							<th>Customer</th>
							<th>Amount</th>
							<th>Reason</th>
							<th>Created</th>
						</tr>
					</thead>
					<tbody>
						@if (isset($credits))
							@foreach($credits as $credit)
							<tr>
								<td>{{ $credit->id }}</td>
								<td>{{ $credit->employee_name }}</td>
								<td>{{ $credit->customer_name }}</td>
								<td>{{ money_format('$%i',$credit->amount) }}</td>
								<td>{{ $credit->reason }}</td>
								<td>{{ $credit->created }}</td>
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