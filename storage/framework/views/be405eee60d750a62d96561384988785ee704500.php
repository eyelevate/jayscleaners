<div id="credit_history" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Store Credit</h4>
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
						<?php if(isset($credits)): ?>
							<?php foreach($credits as $credit): ?>
							<tr>
								<td><?php echo e($credit->id); ?></td>
								<td><?php echo e($credit->employee_name); ?></td>
								<td><?php echo e($credit->customer_name); ?></td>
								<td><?php echo e(money_format('$%i',$credit->amount)); ?></td>
								<td><?php echo e($credit->reason); ?></td>
								<td><?php echo e($credit->created); ?></td>
							</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer clearfix">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

				<button type="submit" class="btn btn-success pull-right">Add Credit</button>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->