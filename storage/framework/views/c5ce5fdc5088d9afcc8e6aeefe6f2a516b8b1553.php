<div id="qty" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Quantity Update - <strong class="badge" style="font-size:20px;"><span id="qtyModalTotal">0</span></strong></h4>
			</div>
			<div class="modal-body">
				<table id="qtyTable" class="no-padding clearfix table table-striped" style="list-style:none;">
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
				<button id="deleteQty-all" class="btn btn-danger pull-left" type="button" data-dismiss="modal">Delete All</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="deleteQty-selected" type="button" class="btn btn-danger" data-dismiss="modal">Delete Selected</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->