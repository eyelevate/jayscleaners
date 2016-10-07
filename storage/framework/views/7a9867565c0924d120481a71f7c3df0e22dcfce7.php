<div class="row">
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">Customer Info 
                <label class="pull-right">
                <?php if(isset($customers->marks)): ?>
                    <?php foreach($customers->marks as $mark): ?>
                    <span class="label label-danger" style="font-size:20px;"><?php echo e($mark->mark); ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
                </label>
            </div>
			<div class="box-body" style="">
				<div class="form-horizontal">

                    <div class="form-group<?php echo e($errors->has('company_name') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Branch</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="company_name" value="<?php echo e($customers->company_name); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>

                    <div class="form-group<?php echo e($errors->has('last_name') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Last Name</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="last_name" value="<?php echo e($customers->last_name); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>

                    <div class="form-group<?php echo e($errors->has('first_name') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">First name</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="first_name" value="<?php echo e($customers->first_name); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Phone</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="phone" value="<?php echo e($customers->phone); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Email</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="email" value="<?php echo e($customers->email); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('street') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Street</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="street" value="<?php echo e($customers->street); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('city') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">City</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="city" value="<?php echo e($customers->city); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Zip</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="zipcode" value="<?php echo e($customers->zipcode); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('shirt') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Shirts</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="shirt" value="<?php echo e($customers->shirt); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('starch') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Starch</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="starch" value="<?php echo e($customers->starch); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('account') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label"><a href="#" style="cursor:pointer;">Account</a></label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="account" value="<?php echo e($customers->account); ?>" disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('delivery') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label"><a href="<?php echo e(route('schedules_view',$customers->id)); ?>" style="cursor:pointer;">Delivery</a></label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="delivery" value="<?php echo e(count($schedules)); ?> active schedule(s) " disabled="true" style="font-size:20px; background-color:#ffffff;">
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('important_memo') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Important Memo</label>

                        <div class="col-md-6">
                            <textarea class="form-control" name="important_memo" disabled="true" style="font-size:20px; background-color:#ffffff;"><?php echo e($customers->important_memo); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group<?php echo e($errors->has('invoice_memo') ? ' has-error' : ''); ?>">
                        <label class="col-md-4 control-label">Invoice Memo</label>

                        <div class="col-md-6">
                            <textarea class="form-control" name="important_invoice" disabled="true" style="font-size:20px;background-color:#ffffff;"><?php echo e($customers->invoice_memo); ?></textarea>
                        </div>
                    </div>
				</div>
			</div> <!-- Close panel body -->
			<div class="panel-footer">
				<a class="btn btn-lg btn-primary" href="<?php echo e(route('customers_edit',$customers->id)); ?>">
					<div class="icon"><i class="ion-ios-compose-outline"></i> Edit</div>
				</a>
				<a class="btn btn-lg btn-info" href="#" data-toggle="modal" data-target="#print-card">
					<div class="icon"><i class="ion-ios-printer-outline"></i> Print Card</div>
				</a>
                <a class="btn btn-lg btn-danger" href="<?php echo e(route('customers_add')); ?>">
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
                    <?php if(isset($invoices)): ?>
                        <?php foreach($invoices as $invoice): ?>
                        <tr id="invoiceTr-<?php echo e($invoice->invoice_id); ?>" class="invoiceTr " invoice-id="<?php echo e($invoice->invoice_id); ?>" data-toggle="modal" data-target="#invoiceModal-<?php echo e($invoice->invoice_id); ?>" style="<?php echo e($invoice->status_color ? 'color:'.$invoice->status_color : ''); ?>">
                            <td><?php echo e(str_pad($invoice->invoice_id, 6, '0', STR_PAD_LEFT)); ?></td>
                            <td><?php echo e(date('D, n/d',strtotime($invoice->created_at))); ?></td>
                            <td><?php echo e(date('D, n/d',strtotime($invoice->due_date))); ?></td>
                            <td><?php echo e($invoice->quantity); ?></td>
                            <td><?php echo e($invoice->rack); ?></td>
                            <td><?php echo e($invoice->total); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
				</table>
			</div>
            <div class="panel-footer">
                <a class="btn btn-lg btn-info" href="<?php echo e(route('invoices_history',$customers->id)); ?>">
                    <div class="icon"><i class="ion-filing"></i> History</div>
                </a>
                <a class="btn btn-lg btn-info" href="<?php echo e(route('delivery_new',$customers->id)); ?>">
                    <div class="icon"><i class="ion-android-car"></i> Set Delivery</div>
                </a>
                <a class="btn btn-lg btn-info" href="<?php echo e(route('cards_admins_index',$customers->id)); ?>">
                    <div class="icon"><i class="ion ion-card"></i> Card on File</div>
                </a>
            </div>  
		</div>
	</div>
</div>