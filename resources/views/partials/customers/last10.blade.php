<div id="last10Customers" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Last 10 Customers</h4>
			</div>
			<div class="modal-body table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Last</th>
							<th>First</th>
							<th>Phone</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(count($last10) >0) 

							@foreach($last10 as $last)
								@foreach($last as $l)
								<tr>
									<td>{{ $l->id }}</td>
									<td>{{ $l->last_name }}</td>
									<td>{{ $l->first_name }}</td>
									<td>{{ $l->phone }}</td>
									<td><a class="btn btn-link" href="{{ route('customers_view', $l->id) }}">View</a></td>
								</tr>
								@endforeach
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->