
<div id="detail-{{ $schedule['id'] }}" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Delivery Detail #{{ $schedule['id'] }}</strong></h4>
			</div>
			<div class="modal-body clearfix">
				<div class="form-group">
					<label class="col-sm-4 control-label">Schedule #</label>
					<div class="col-sm-8">
						<p class="form-control">{{ $schedule['id'] }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Customer #</label>
					<div class="col-sm-8">
						<p class="form-control">{{ $schedule['customer_id'] }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Full Name</label>
					<div class="col-sm-8">
						<p class="form-control">{{ $schedule['last_name'] }}, {{ $schedule['first_name'] }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Pickup Date & Time</label>
					<div class="col-sm-8">
						<p class="form-control">{{ $schedule['pickup_date'] }} ({{ $schedule['pickup_time'] }})</p>
					</div>
				</div>					
				<div class="form-group">
					<label class="col-sm-4 control-label">Dropoff Date & Time</label>
					<div class="col-sm-8">
						<p class="form-control">{{ $schedule['dropoff_date'] }} ({{ $schedule['dropoff_time'] }})</p>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-4 control-label">Address</label>
					<div class="col-sm-8">
						<p class="form-control" style="height:75px;">{{ $schedule['pickup_address_1'] }} <br/>{{ $schedule['pickup_address_2'] }}</p>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-4 control-label">Contact Name</label>
					<div class="col-sm-8">
						<p class="form-control">{{ $schedule['contact_name'] }}</p>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-4 control-label">Contact Number</label>
					<div class="col-sm-8">
						<p class="form-control">{{ $schedule['contact_number'] }}</p>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-4 control-label">Special Instructions</label>
					<div class="col-sm-8">
						<p class="form-control" style="height:100px; overflow:auto;">{{ $schedule['special_instructions'] }} <br/>{{ $schedule['pickup_address_2'] }}</p>
					</div>
				</div>	
		
			</div>

			<div class="modal-footer clearfix">
				<button type="button" class="btn pull-left" data-dismiss="modal">Close</button>
				<a href="{{ route('delivery_admin_edit',$schedule['id']) }}" class="btn btn-primary pull-right">Edit</a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->