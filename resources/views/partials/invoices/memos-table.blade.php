<div id="memo-table" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Memo Update </h4>
			</div>
			<div class="modal-body">
				<table id="memoTable" class="no-padding clearfix table table-striped" style="list-style:none;">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Color</th>
							<th>Memo</th>
							<th>Price</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button id="deleteMemo-all" class="btn btn-danger pull-left" type="button">Delete Memo(s)</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="memo-accept" type="button" class="btn btn-success" data-dismiss="modal">Accept Memo(s)</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->