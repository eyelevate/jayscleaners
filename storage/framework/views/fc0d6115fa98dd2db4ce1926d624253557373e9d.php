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
						<?php if(count($last10) >0): ?> 

							<?php foreach($last10 as $last): ?>
								<?php foreach($last as $l): ?>
								<tr>
									<td><?php echo e($l->id); ?></td>
									<td><?php echo e($l->last_name); ?></td>
									<td><?php echo e($l->first_name); ?></td>
									<td><?php echo e($l->phone); ?></td>
									<td><a class="btn btn-link" href="<?php echo e(route('customers_view', $l->id)); ?>">View</a></td>
								</tr>
								<?php endforeach; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->