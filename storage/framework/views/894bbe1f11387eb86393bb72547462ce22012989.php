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
        <?php echo Form::open(['action' => 'SchedulesController@postProcessing','role'=>"form",'id'=>'search_form']); ?>

            <?php echo csrf_field(); ?> 
		<div class="panel-body">

	        <div class="form-group<?php echo e($errors->has('search') ? ' has-error' : ''); ?> search_div">
	            <label class="col-md-12 col-lg-12 col-sm-12 col-xs-12 control-label padding-top-none">Processing Date</label>

	            <div id="search_container" class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
	                <input id="search_data" type="text" class="form-control" name="search" value="<?php echo e(old('search') ? old('search') : $processing_date); ?>" readonly="true" style="background-color:#ffffff">

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
			<a href="<?php echo e(route('delivery_overview')); ?>" class="btn btn-lg btn-primary pull-right <?php echo e((count($schedules) == 0) ? '' : 'disabled'); ?> col-md-2 col-sm-6 col-xs-6">Finished</a>
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
					<h4><strong>#<?php echo e($schedule['id']); ?></strong> - [<?php echo e($schedule['customer_id']); ?>] <?php echo e($schedule['last_name']); ?>, <?php echo e($schedule['first_name']); ?></h4>
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
				</div>
				<div class="clearfix panel-footer" >
					<a class="btn btn-info" href="<?php echo e(route('delivery_admin_edit',$schedule['id'])); ?>">Edit Delivery</a>
					<?php echo Form::open(['action' => 'SchedulesController@postApproveProcessing','role'=>"form",'class'=>'pull-right']); ?>

					<?php echo Form::hidden('id',$schedule['id']); ?>

					<input type="submit" class="btn btn-success" value="At Processing" />
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
			<h3 class="box-title">Actions Approved &nbsp;<span class="label label-default pull-right"><?php echo e(count($processing_list)); ?></span></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
		<?php if(count($processing_list) > 0): ?>
			<?php foreach($processing_list as $pl): ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4><strong>#<?php echo e($pl['id']); ?></strong> - [<?php echo e($pl['customer_id']); ?>] <?php echo e($pl['last_name']); ?>, <?php echo e($pl['first_name']); ?></h4>
				</div>
				<div class="panel-body" style="font-size:17px;">
					<div class="form-group">
						<label class="control-label"><?php echo e($pl['status_message']); ?></label>
						<div class="progress">
							<?php if($pl['status'] == 1): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
								<span class="sr-only">20% Complete (success)</span>
							</div>

							<?php elseif($pl['status'] == 2): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (success)</span>
							</div>
							<?php elseif($pl['status'] == 3): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
								<span class="sr-only">40% Complete (success)</span>
							</div>
							<?php elseif($pl['status'] == 4): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							<?php elseif($pl['status'] == 5): ?>
							<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							<?php elseif($pl['status'] == 6): ?>
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (cancelled by user)</span>
							</div>
							<?php elseif($pl['status'] == 7): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
								<span class="sr-only">50% Complete (success)</span>
							</div>
							<?php elseif($pl['status'] == 8): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
								<span class="sr-only">30% Complete (Delayed)</span>
							</div>
							<?php elseif($pl['status'] == 9): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
								<span class="sr-only">75% Complete (success)</span>
							</div>
							<?php elseif($pl['status'] == 10): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
								<span class="sr-only">80% Complete (success)</span>
							</div>
							<?php elseif($pl['status'] == 11): ?>
							<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
								<span class="sr-only">90% Complete (success)</span>
							</div>
							<?php elseif($pl['status'] == 12): ?>
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (success)</span>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Customer</label>
						<div>
							<p class="form-control">[<?php echo e($pl['customer_id']); ?>] <?php echo e($pl['last_name']); ?>, <?php echo e($pl['first_name']); ?></p>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Pickup Date & Time</label>
						<div>
							<p class="form-control"><?php echo e($pl['pickup_date']); ?> (<?php echo e($pl['pickup_time']); ?>)</p>
						</div>
					</div>					
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Dropoff Date & Time</label>
						<div>
							<p class="form-control"><?php echo e($pl['dropoff_date']); ?> (<?php echo e($pl['dropoff_time']); ?>)</p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Delivery Address</label>
						<div>
							<p class="form-control" style="height:75px;"><?php echo e($pl['pickup_address_1']); ?> <br/><?php echo e($pl['pickup_address_2']); ?></p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Contact Info</label>
						<div>
							<p class="form-control"><?php echo e($pl['contact_name']); ?> - <?php echo e($pl['contact_number']); ?></p>
						</div>
					</div>	
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label class="control-label" style="text-align:right">Special Instructions</label>
						<div>
							<p class="form-control" style="height:100px; overflow:auto;"><?php echo e($pl['special_instructions']); ?></p>
						</div>
					</div>	
				</div>
				<div class="clearfix panel-footer" >
					<a class="btn btn-info" href="<?php echo e(route('delivery_admin_edit',$pl['id'])); ?>">Edit Delivery</a>
					<?php
					switch($pl['status']) {
						case 2:
						?>
						<?php echo Form::open(['action' => 'SchedulesController@postRevertPickup','role'=>"form",'class'=>'pull-right']); ?>

						<?php echo Form::hidden('id',$pl['id']); ?>

						<input type="submit" class="btn btn-danger" value="Revert Back" />
						<?php echo Form::close(); ?>

						<?php
						break;

						case 5:
						?>
						<?php echo Form::open(['action' => 'SchedulesController@postRevertDropoff','role'=>"form",'class'=>'pull-right']); ?>

						<?php echo Form::hidden('id',$pl['id']); ?>

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
			<a href="#" class="btn btn-lg btn-primary pull-right" data-toggle="modal" data-target="#status_change">Email Status Change</a>
		</div><!-- /.box-footer -->
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>