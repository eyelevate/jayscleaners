<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript">
    $('#search_data').Zebra_DatePicker({
        container:$("#search_container"),
        format:'D m/d/Y',
        onSelect: function(a, b) {
        	$("#search_form").submit();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Delivery Overview</h3>
		</div>
        <?php echo Form::open(['action' => 'SchedulesController@postDeliveryRoute','role'=>"form",'id'=>'search_form']); ?>

            <?php echo csrf_field(); ?> 
		<div class="panel-body">

	        <div class="form-group<?php echo e($errors->has('search') ? ' has-error' : ''); ?> search_div">
	            <label class="col-md-12 col-lg-12 col-sm-12 col-xs-12 control-label padding-top-none">Delivery Date</label>

	            <div id="search_container" class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
	                <input id="search_data" type="text" class="form-control" name="search" value="<?php echo e(old('search') ? old('search') : $delivery_date); ?>" readonly="true" style="background-color:#ffffff">

	                <?php if($errors->has('search')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('search')); ?></strong>
	                    </span>
	                <?php endif; ?>
	            </div>
	        </div>
		</div>

		<?php echo Form::close(); ?>

		<div class="panel-footer clearfix">
			<a href="<?php echo e(route('schedules_checklist')); ?>" class="btn btn-lg btn-danger pull-left col-md-2 col-sm-6 col-xs-6"><i class="ion ion-chevron-left"></i>&nbsp;Back</a>
			<a href="<?php echo e(route('schedules_processing')); ?>" class="btn btn-lg btn-primary pull-right col-md-2 col-sm-6 col-xs-6">Process&nbsp;<i class="ion ion-chevron-right"></i></a>
		</div>
	</div>

	<div class="box box-warning">
		<div class="box-header with-border clearfix">
			<h3 class="box-title">Actions Required &nbsp;<span class="label label-default pull-right"><?php echo e(($schedules) ? count($schedules) : 0); ?></span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
		<?php if($schedules): ?>
			<?php $idx = 0 ?>
			<?php foreach($schedules as $schedule): ?>
				<?php $idx += 1 ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4><label class="label label-default">Stop #<?php echo e($idx); ?></label>&nbsp;<strong>#<?php echo e($schedule['id']); ?></strong> - [<?php echo e($schedule['customer_id']); ?>] <?php echo e($schedule['last_name']); ?>, <?php echo e($schedule['first_name']); ?></h4>
				</div>
				<div class="panel-body" style="font-size:17px;">
					<div class="form-group">
						<label class="control-label"><?php echo e($schedule['status_message']); ?></label>
						<div class="progress">
							<?php if($schedule['status'] == 1): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
								<span class="sr-only">20% Complete (success)</span>
							</div>

							<?php elseif($schedule['status'] == 2): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (success)</span>
							</div>
							<?php elseif($schedule['status'] == 3): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
								<span class="sr-only">40% Complete (success)</span>
							</div>
							<?php elseif($schedule['status'] == 4): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							<?php elseif($schedule['status'] == 5): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							<?php elseif($schedule['status'] == 6): ?>
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (cancelled by user)</span>
							</div>
							<?php elseif($schedule['status'] == 7): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							<?php elseif($schedule['status'] == 8): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (Delayed)</span>
							</div>
							<?php elseif($schedule['status'] == 9): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							<?php elseif($schedule['status'] == 10): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
								<span class="sr-only">80% Complete (success)</span>
							</div>
							<?php elseif($schedule['status'] == 11): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
								<span class="sr-only">90% Complete (success)</span>
							</div>
							<?php elseif($schedule['status'] == 12): ?>
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (success)</span>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Customer</label>
						<div>
							<p class="form-control">[<?php echo e($schedule['customer_id']); ?>] <?php echo e($schedule['last_name']); ?>, <?php echo e($schedule['first_name']); ?></p>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Pickup Date & Time</label>
						<div>
							<p class="form-control"><?php echo e($schedule['pickup_date']); ?> (<?php echo e($schedule['pickup_time']); ?>)</p>
						</div>
					</div>					
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Dropoff Date & Time</label>
						<div>
							<p class="form-control"><?php echo e($schedule['dropoff_date']); ?> (<?php echo e($schedule['dropoff_time']); ?>)</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Delivery Address</label>
						<div>
							<p class="form-control" style="height:75px;"><?php echo e($schedule['pickup_address_1']); ?> <br/><?php echo e($schedule['pickup_address_2']); ?></p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Contact Info</label>
						<div>
							<p class="form-control"><?php echo e($schedule['contact_name']); ?> - <?php echo e($schedule['contact_number']); ?></p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Special Instructions</label>
						<div>
							<p class="form-control" style="height:100px; overflow:auto;"><?php echo e($schedule['special_instructions']); ?></p>
						</div>
					</div>	
					<?php if($schedule['status'] == 11): ?>
					<div class="table-responsive form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="schedule_table table table-striped table-condensed table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Qty</th>
									<th>Items</th>
									<th>Subtotal</th>
									<th>A.</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$invoices = $schedule['invoices'];
							if (count($invoices) > 0) {
								foreach ($invoices as $invoice) {
								?>
								<tr class="disabled <?php echo e(($invoice->schedule_id) ? 'warning' : ''); ?>" >
									<td><?php echo e($invoice->id); ?></td>
									<td><?php echo e($invoice->quantity); ?></td>
									<td>
										<ul style="list-style:none;">
										<?php if(count($invoice['item_details'])): ?>
											<?php foreach($invoice['item_details'] as $ids): ?>
											<li><?php echo e($ids['qty']); ?>-<?php echo e($ids['item']); ?></li>
												<?php if(count($ids['color']) > 0): ?>
												<li>
													<ul>
													<?php foreach($ids['color'] as $colors_name => $colors_count): ?>
														<li><?php echo e($colors_count); ?>-<?php echo e($colors_name); ?></li>
													<?php endforeach; ?>
													</ul>
												</li>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
										</ul>
									</td>
									<td><?php echo e($invoice->pretax_html); ?></td>
									<td>
										<input class="invoice_ids" readonly="true" disabled="true" type="checkbox" value="<?php echo e($invoice->id); ?>" <?php echo e(($invoice->schedule_id) ? 'checked="true"' : ''); ?> />
									</td>
								</tr>
								<?php
								}
							}
							
							?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4" style="text-align:right">Qty&nbsp;</th>
									<td id="total_qty-<?php echo e($schedule['id']); ?>" class="disabled"><?php echo e(($schedule['invoice_totals']) ? $schedule['invoice_totals']['quantity'] : '0'); ?></td>
								</tr>
								<tr>
									<th colspan="4" style="text-align:right">Subtotal&nbsp;</th>
									<td id="total_subtotal-<?php echo e($schedule['id']); ?>" class="disabled"><?php echo e(($schedule['invoice_totals']) ? $schedule['invoice_totals']['subtotal_html'] : '$0.00'); ?></td>
								</tr>
								<tr>
									<th colspan="4" style="text-align:right">Tax&nbsp;</th>
									<td id="total_tax-<?php echo e($schedule['id']); ?>" class="disabled"><?php echo e(($schedule['invoice_totals']) ? $schedule['invoice_totals']['tax_html'] : '$0.00'); ?></td>
								</tr>
								<tr>
									<th colspan="4" style="text-align:right">Total&nbsp;</th>
									<td id="total_total-<?php echo e($schedule['id']); ?>" class="disabled"><?php echo e(($schedule['invoice_totals']) ? $schedule['invoice_totals']['total_html'] : '$0.00'); ?></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 clearfix">					
						<label class="label label-success col-xs-12 col-sm-12 col-md-12 col-lg-12">Paid</label>
						<?php echo Form::open(['action' => 'SchedulesController@postRevertPayment','role'=>"form"]); ?>

						<?php echo Form::hidden('id',$schedule['id']); ?>

						<input type="submit" data-toggle="modal" data-target="#loading" class="btn btn-danger col-lg-12 col-md-12 col-sm-12 col-xs-12" value="Revert Payment" />
						<?php echo Form::close(); ?>						
					</div>

					<?php endif; ?>
					<hr/>
					<?php echo Form::open(['action'=>'SchedulesController@postDelayDelivery','role'=>'form']); ?>

					<?php echo Form::hidden('id',$schedule['id']); ?>

					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label class="control-label" style="text-align:right">Delay Delivery</label>
						<p>
						<?php echo e(Form::select('reason',$schedule['delay_list'],'',['class'=>'form-control'])); ?>

						</p>
						<div>
							<input type="submit" class="btn btn-danger" value="Submit Delay"/>
						</div>
					</div>						
					<?php echo Form::close(); ?>

				</div>
				<div class="clearfix panel-footer" >
					<a class="btn btn-info" href="<?php echo e(route('delivery_admin_edit',$schedule['id'])); ?>">Edit Delivery</a>
					<a class="btn btn-warning" href="<?php echo e($schedule['gmap_address']); ?>">View Map</a>
					<?php
					switch($schedule['status']) {
						case 2:
						?>
						<?php echo Form::open(['action' => 'SchedulesController@postApprovePickedup','role'=>"form",'class'=>'pull-right']); ?>

						<?php echo Form::hidden('id',$schedule['id']); ?>

						<input type="submit" class="btn btn-success" value="Picked Up" />
						<?php echo Form::close(); ?>

						<?php
						break;


						case 11:
						?>
						<?php echo Form::open(['action' => 'SchedulesController@postApproveDroppedOff','role'=>"form",'class'=>'pull-right']); ?>

						<?php echo Form::hidden('id',$schedule['id']); ?>						
						<button type="submit" class="btn btn-success " href="#">Dropped Off</button>
						<?php echo Form::close(); ?>

						<?php
						break;
					}

					?>
				</div>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
		</div><!-- /.box-body -->
		<div class="box-footer clearfix">

		</div><!-- /.box-footer -->
	</div>
	<div class="box box-danger collapsed-box">
		<div class="box-header with-border clearfix">
			<h3 class="box-title">Actions Delayed &nbsp;<span class="label label-default pull-right"><?php echo e(count($delayed_list)); ?></span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
		<?php if(count($delayed_list) > 0): ?>
			<?php foreach($delayed_list as $dl): ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4><strong>#<?php echo e($dl['id']); ?></strong> - [<?php echo e($dl['customer_id']); ?>] <?php echo e($dl['last_name']); ?>, <?php echo e($dl['first_name']); ?></h4>
				</div>
				<div class="panel-body" style="font-size:17px;">
					<div class="form-group">
						<label class="control-label"><?php echo e($dl['status_message']); ?></label>
						<div class="progress">
							<?php if($dl['status'] == 1): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
								<span class="sr-only">20% Complete (success)</span>
							</div>

							<?php elseif($dl['status'] == 2): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (success)</span>
							</div>
							<?php elseif($dl['status'] == 3): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
								<span class="sr-only">40% Complete (success)</span>
							</div>
							<?php elseif($dl['status'] == 4): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							<?php elseif($dl['status'] == 5): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							<?php elseif($dl['status'] == 6): ?>
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (cancelled by user)</span>
							</div>
							<?php elseif($dl['status'] == 7): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							<?php elseif($dl['status'] == 8): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (Delayed)</span>
							</div>
							<?php elseif($dl['status'] == 9): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							<?php elseif($dl['status'] == 10): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
								<span class="sr-only">80% Complete (success)</span>
							</div>
							<?php elseif($dl['status'] == 11): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
								<span class="sr-only">90% Complete (success)</span>
							</div>
							<?php elseif($dl['status'] == 12): ?>
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (success)</span>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Customer</label>
						<div>
							<p class="form-control">[<?php echo e($dl['customer_id']); ?>] <?php echo e($dl['last_name']); ?>, <?php echo e($dl['first_name']); ?></p>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Pickup Date & Time</label>
						<div>
							<p class="form-control"><?php echo e($dl['pickup_date']); ?> (<?php echo e($dl['pickup_time']); ?>)</p>
						</div>
					</div>					
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Dropoff Date & Time</label>
						<div>
							<p class="form-control"><?php echo e($dl['dropoff_date']); ?> (<?php echo e($dl['dropoff_time']); ?>)</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Delivery Address</label>
						<div>
							<p class="form-control" style="height:75px;"><?php echo e($dl['pickup_address_1']); ?> <br/><?php echo e($dl['pickup_address_2']); ?></p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Contact Info</label>
						<div>
							<p class="form-control"><?php echo e($dl['contact_name']); ?> - <?php echo e($dl['contact_number']); ?></p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Special Instructions</label>
						<div>
							<p class="form-control" style="height:100px; overflow:auto;"><?php echo e($dl['special_instructions']); ?></p>
						</div>
					</div>	
				</div>
				<div class="clearfix panel-footer" >
					<a class="btn btn-info" href="<?php echo e(route('delivery_admin_edit',$dl['id'])); ?>">Edit Delivery</a>
					<a class="btn btn-warning" data-toggle="modal" data-target="#email-<?php echo e($dl['id']); ?>">Email</a>
					<?php echo Form::open(['action' => 'SchedulesController@postRevertDelay','role'=>"form",'class'=>'pull-right']); ?>

					<?php echo Form::hidden('id',$dl['id']); ?>

					<?php echo Form::hidden('status',$dl['status']); ?>

					<input type="submit" class="btn btn-danger" value="Revert Back" />
					<?php echo Form::close(); ?>

				</div>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
		</div><!-- /.box-body -->
		<div class="box-footer clearfix">
			
		</div><!-- /.box-footer -->
	</div>

	<div class="box box-success collapsed-box">
		<div class="box-header with-border clearfix">
			<h3 class="box-title">Actions Approved &nbsp;<span class="label label-default pull-right"><?php echo e(count($approved_list)); ?></span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
		<?php if(count($approved_list) > 0): ?>
			<?php foreach($approved_list as $al): ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4><strong>#<?php echo e($al['id']); ?></strong> - [<?php echo e($al['customer_id']); ?>] <?php echo e($al['last_name']); ?>, <?php echo e($al['first_name']); ?></h4>
				</div>
				<div class="panel-body" style="font-size:17px;">
					<div class="form-group">
						<label class="control-label"><?php echo e($al['status_message']); ?></label>
						<div class="progress">
							<?php if($al['status'] == 1): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
								<span class="sr-only">20% Complete (success)</span>
							</div>

							<?php elseif($al['status'] == 2): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (success)</span>
							</div>
							<?php elseif($al['status'] == 3): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
								<span class="sr-only">40% Complete (success)</span>
							</div>
							<?php elseif($al['status'] == 4): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							<?php elseif($al['status'] == 5): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							<?php elseif($al['status'] == 6): ?>
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (cancelled by user)</span>
							</div>
							<?php elseif($al['status'] == 7): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							<?php elseif($al['status'] == 8): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (Delayed)</span>
							</div>
							<?php elseif($al['status'] == 9): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							<?php elseif($al['status'] == 10): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
								<span class="sr-only">80% Complete (success)</span>
							</div>
							<?php elseif($al['status'] == 11): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
								<span class="sr-only">90% Complete (success)</span>
							</div>
							<?php elseif($al['status'] == 12): ?>
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (success)</span>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Customer</label>
						<div>
							<p class="form-control">[<?php echo e($al['customer_id']); ?>] <?php echo e($al['last_name']); ?>, <?php echo e($al['first_name']); ?></p>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Pickup Date & Time</label>
						<div>
							<p class="form-control"><?php echo e($al['pickup_date']); ?> (<?php echo e($al['pickup_time']); ?>)</p>
						</div>
					</div>					
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Dropoff Date & Time</label>
						<div>
							<p class="form-control"><?php echo e($al['dropoff_date']); ?> (<?php echo e($al['dropoff_time']); ?>)</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Delivery Address</label>
						<div>
							<p class="form-control" style="height:75px;"><?php echo e($al['pickup_address_1']); ?> <br/><?php echo e($al['pickup_address_2']); ?></p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Contact Info</label>
						<div>
							<p class="form-control"><?php echo e($al['contact_name']); ?> - <?php echo e($al['contact_number']); ?></p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Special Instructions</label>
						<div>
							<p class="form-control" style="height:100px; overflow:auto;"><?php echo e($al['special_instructions']); ?></p>
						</div>
					</div>	
				</div>
				<div class="clearfix panel-footer" >
					<a class="btn btn-info" href="<?php echo e(route('delivery_admin_edit',$al['id'])); ?>">Edit Delivery</a>
					<a class="btn btn-warning" data-toggle="modal" data-target="#email-<?php echo e($al['id']); ?>">Email</a>
					<?php
					switch($al['status']) {
						case 3:
						?>
						<?php echo Form::open(['action' => 'SchedulesController@postRevertPickup','role'=>"form",'class'=>'pull-right']); ?>

						<?php echo Form::hidden('id',$al['id']); ?>

						<input type="submit" class="btn btn-danger" value="Revert Back" />
						<?php echo Form::close(); ?>

						<?php
						break;

						case 5:
						?>
						<?php echo Form::open(['action' => 'SchedulesController@postRevertDropoff','role'=>"form",'class'=>'pull-right']); ?>

						<?php echo Form::hidden('id',$al['id']); ?>

						<input type="submit" class="btn btn-danger" value="Revert Back" />
						<?php echo Form::close(); ?>

						<?php
						break;
					}

					?>
				</div>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
		</div><!-- /.box-body -->
		<div class="box-footer clearfix">

		</div><!-- /.box-footer -->
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>

	<?php if(count($delayed_list) > 0): ?>
		<?php foreach($delayed_list as $dl): ?>
		<?php echo View::make('partials.schedules.email')
			->with('schedule_id',$dl['id'])
			->render(); ?>

		<?php endforeach; ?>
	<?php endif; ?>
	<?php if(count($approved_list) > 0): ?>
		<?php foreach($approved_list as $al): ?>
		<?php echo View::make('partials.schedules.email')
			->with('schedule_id',$al['id'])
			->render(); ?>

		<?php endforeach; ?>
	<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>