<div id="memos" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Memo</h4>
			</div>
			<div class="modal-body clearfix">
	            <div class="form-group{{ $errors->has('memo') ? ' has-error' : '' }}">
                    <label class="col-lg-12 col-md-12 col-sm-12 control-label">Memo <span class="text text-danger">*</span></label>

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        {!! Form::text('memo', null, ['id'=>'memo',class'=>'form-control', 'placeholder'=>'Add memo here']) !!}
                    </div>
                </div> 		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="memoAdd" type="button" class="btn btn-success">Add Memo</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->