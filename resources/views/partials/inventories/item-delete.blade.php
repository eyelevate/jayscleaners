{!! Form::open(['action' => 'InventoryItemsController@postDelete', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
{{ Form::hidden('id',null,['class'=>'itemEdit-id']) }}
<div id="item-delete" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Are you sure?</h4>
			</div>
			<div class="modal-body">
				<p>By pressing "Confirm Delete" below you will be deleting an inventory item. By doing so will cause all inventory items associated with this group to be deleted as well.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-danger" value="Confirm Delete"/>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}