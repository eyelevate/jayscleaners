<div class="row">
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">Customer Info</div>
			<div class="box-body" style="">
				<div class="form-horizontal">

                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Last Name</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="last_name" value="{{ $customers->last_name }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">First name</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="first_name" value="{{ $customers->first_name }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Phone</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="phone" value="{{ $customers->phone }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Email</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="email" value="{{ $customers->email }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Street</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="street" value="{{ $customers->street }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">City</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="city" value="{{ $customers->city }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Zip</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="zipcode" value="{{ $customers->zipcode }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
				</div>
			</div> <!-- Close panel body -->
			<div class="panel-footer">
				<a class="btn btn-lg btn-primary" href="{{ route('customers_edit',$customers->id) }}">
					<div class="icon"><i class="ion-ios-compose-outline"></i> Edit</div>
				</a>
				<a class="btn btn-lg btn-info" href="#" data-toggle="modal" data-target="#print-card">
					<div class="icon"><i class="ion-ios-printer-outline"></i> Print Card</div>
				</a>
                <a class="btn btn-lg btn-danger" href="{{ route('customers_add') }}">
                    <div class="icon"><i class="ion-person-add"></i> New</div>
                </a>
			</div>			
		</div>
	</div>
	<div class="col-md-6">
		<div class="box box-success">
			<div class="box-body table-responsive">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Drop</th>
							<th>Due</th>
							<th>Pcs</th>
							<th>Rack</th>
							<th>Total</th>
						</tr>
					</thead>
				</table>
			</div>
            <div class="panel-footer">
                <a class="btn btn-lg btn-primary" href="{{ route('customers_edit',$customers->id) }}">
                    <div class="icon"><i class="ion-ios-compose-outline"></i> Edit</div>
                </a>
                <a class="btn btn-lg btn-info" href="#" data-toggle="modal" data-target="#print-invoice">
                    <div class="icon"><i class="ion-ios-printer-outline"></i> Reprint</div>
                </a>
                <a class="btn btn-lg btn-info" href="#">
                    <div class="icon"><i class="ion-filing"></i> History</div>
                </a>
            </div>  
		</div>
	</div>
</div>