<div class="row">
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">Customer Info 
                <label class="pull-right">
                @if(isset($customers->marks))
                    @foreach($customers->marks as $mark)
                    <span class="label label-danger" style="font-size:20px;">{{ $mark->mark }}</span>
                    @endforeach
                @endif
                </label>
            </div>
			<div class="box-body" style="">
				<div class="form-horizontal">

                    <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Branch</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="company_name" value="{{ $customers->company_name }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>

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
                    <div class="form-group{{ $errors->has('shirt') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Shirts</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="shirt" value="{{ $customers->shirt }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('starch') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Starch</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="starch" value="{{ $customers->starch }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label"><a href="#" style="cursor:pointer;">Account</a></label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="account" value="{{ $customers->account }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('credits') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Store Credit</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="account" value="{{ money_format('$%i',$customers->credits) }}" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('credits') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label"></label>

                        <div class="col-md-6">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#credit">Add Credit</button>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('delivery') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label"><a href="{{ route('schedules_view',$customers->id) }}" style="cursor:pointer;">Delivery</a></label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="delivery" value="{{ count($schedules) }} active schedule(s) " disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('important_memo') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Important Memo</label>

                        <div class="col-md-6">
                            <textarea class="form-control" name="important_memo" disabled="true" style="font-size:20px; background-color:#ffffff;">{{ $customers->important_memo }}</textarea>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('invoice_memo') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Invoice Memo</label>

                        <div class="col-md-6">
                            <textarea class="form-control" name="important_invoice" disabled="true" style="font-size:20px;background-color:#ffffff;">{{ $customers->invoice_memo }}</textarea>
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
				<table id="invoiceTable" class="table table-hover table-striped">
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
                    <tbody>
                    @if(isset($invoices))
                        @foreach($invoices as $invoice)
                        <tr id="invoiceTr-{{ $invoice->id }}" class="invoiceTr " invoice-id="{{ $invoice->id }}" data-toggle="modal" data-target="#invoiceModal-{{ $invoice->id }}" style="{{ $invoice->status_color ? 'color:'.$invoice->status_color : '' }}">
                            <td>{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ date('D, n/d',strtotime($invoice->created_at)) }}</td>
                            <td>{{ date('D, n/d',strtotime($invoice->due_date)) }}</td>
                            <td>{{ $invoice->quantity }}</td>
                            <td>{{ $invoice->rack }}</td>
                            <td>{{ $invoice->total }}</td>
                        </tr>
                        @endforeach
                    @endif
                    </tbody>
				</table>
			</div>
            <div class="panel-footer">
                <a class="btn btn-lg btn-info" href="{{ route('invoices_history',$customers->id) }}">
                    <div class="icon"><i class="ion-filing"></i> History</div>
                </a>
                <a class="btn btn-lg btn-info" href="{{ route('delivery_new',$customers->id) }}">
                    <div class="icon"><i class="ion-android-car"></i> Set Delivery</div>
                </a>
                <a class="btn btn-lg btn-info" href="{{ route('cards_admins_index',$customers->id) }}">
                    <div class="icon"><i class="ion ion-card"></i> Card on File</div>
                </a>
            </div>  
		</div>
	</div>
</div>