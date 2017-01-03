{!! Form::open(['action' => 'SchedulesController@postAdminCancel', 'class'=>'form-horizontal','role'=>"form",'id'=>'cancel_form']) !!}
{!! Form::hidden('id',$schedule_id) !!}
<div id="cancel" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Cancel Delivery Request</strong></h4>
			</div>
			<div class="modal-body clearfix">
				<p>Are you sure you want to delete this schedule? This cannot be undone.</p>
				<div class="form-group">
					<label class="col-sm-4 control-label">Schedule #</label>
					<div class="col-sm-8">
						<p class="form-control">{{ $schedule_id }}</p>
					</div>
				</div>
			</div>

			<div class="modal-footer clearfix">
				<button type="button" class="btn pull-left" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-danger pull-right">Cancel</a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}