
<div id="calendar" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Select Due Date & Time</strong></h4>
			</div>
			<div class="modal-body">
				<div id="calendarDiv">

				</div>

				<div class="row">
					
					<div>

					</div>

					<div>

					</div>

					<div>

					</div>
				</div>
			</div>

			<div class="modal-footer">
				{{ Form::hidden('temp_date',$turnaround_date,['id'=>'temp_date']) }}
				<button id="calendar-selected" type="button" class="btn btn-success" data-dismiss="modal">OK</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->