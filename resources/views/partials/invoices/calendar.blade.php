
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
				<br/>
				<div class="row">
					
			        <div class="form-group clearfix">
			        	<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Choose time</label>
			            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			                {!! Form::select('hours', $hours, '4', ['id'=>'due_temp_hours','class'=>'form-control']) !!}
			            </div>
			            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			                {!! Form::select('minutes', $minutes,'00',['id'=>'due_temp_minutes','class'=>'form-control']) !!}
			            </div>
			            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			                {!! Form::select('ampm', $ampm, 'pm', ['id'=>'due_temp_ampm','class'=>'form-control']) !!}
			            </div>
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