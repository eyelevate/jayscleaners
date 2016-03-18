{!! Form::open(['action' => 'MemosController@postDelete', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
{{ Form::hidden('id',null,['class'=>'memoEditId']) }}
<div id="delete" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Are you sure?</h4>
			</div>
			<div class="modal-body">
				<p>By pressing "Confirm Delete" below you will be deleting a Memo. Are you sure you want to delete?.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-danger" value="Confirm Delete"/>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}