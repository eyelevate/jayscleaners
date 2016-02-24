@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('header')
	<div class="jumbotron">
	</div>
@stop
@section('content')
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">Customer Info</div>
				<div class="box-body" style="">
					<div class="form-horizontal">

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="last_name" value="" disabled="true" style="font-size:20px; background-color:#ffffff;">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">First name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="" disabled="true" style="font-size:20px; background-color:#ffffff;">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="" disabled="true" style="font-size:20px; background-color:#ffffff;">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="" disabled="true" style="font-size:20px; background-color:#ffffff;">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Street</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="" disabled="true" style="font-size:20px; background-color:#ffffff;">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">City</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="" disabled="true" style="font-size:20px; background-color:#ffffff;">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Zip</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="" disabled="true" style="font-size:20px; background-color:#ffffff;">
                            </div>
                        </div>
					</div>
				</div> <!-- Close panel body -->
				<div class="panel-footer">
					<a class="btn btn-lg btn-danger" href="{{ route('customers_edit',$customer_id) }}">
						<div class="icon"><i class="ion-ios-compose-outline"></i> Edit</div>
					</a>
					<a class="btn btn-lg btn-info" href="#">
						<div class="icon"><i class="ion-ios-printer-outline"></i> Print Card</div>
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
								<th>Pickup</th>
								<th>Pcs</th>
								<th>Rack</th>
								<th>Total</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>

@stop