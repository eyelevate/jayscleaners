<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/deliveries/setup-add.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<br/>
	<?php echo Form::open(['action' => 'DeliveriesController@postSetupEdit','role'=>"form"]); ?>

	<?php echo Form::hidden('id',$delivery_id); ?>

	<div class="panel panel-primary">
		<div class="panel-heading"><h4>New Delivery Schedule</h4></div>
		<div class="panel-body">
            <div class="form-group<?php echo e($errors->has('company_id') ? ' has-error' : ''); ?>">
                <label class="control-label">Select who will run the route <span class="text text-danger">*</span></label>
                <?php echo Form::select('company_id',$companies , old('company_id') ? old('company_id') : $deliveries->company_id, ['class'=>'form-control']); ?>

                <?php if($errors->has('company_id')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('company_id')); ?></strong>
                    </span>
                <?php endif; ?>

            </div> 
            <div class="form-group<?php echo e($errors->has('route_name') ? ' has-error' : ''); ?>">
                <label class="control-label">Route Name <span class="text text-danger">*</span></label>
                <?php echo Form::text('route_name', old('route_name') ? old('route_name') : $deliveries->route_name, ['class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('route_name')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('route_name')); ?></strong>
                    </span>
                <?php endif; ?>

            </div> 			
            <div class="form-group<?php echo e($errors->has('day') ? ' has-error' : ''); ?>">
                <label class="control-label">Day Of Week <span class="text text-danger">*</span></label>

                <?php echo Form::select('day',$day , old('day') ? old('day') : $deliveries->day, ['class'=>'form-control']); ?>

                <?php if($errors->has('day')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('day')); ?></strong>
                    </span>
                <?php endif; ?>

            </div>
            <div class="form-group<?php echo e($errors->has('limit') ? ' has-error' : ''); ?>">
                <label class="control-label">Trip limit per day <span class="text text-danger">*</span></label>

                <?php echo Form::select('limit',$limits , old('limit') ? old('limit') : $deliveries->limit, ['class'=>'form-control']); ?>

                <?php if($errors->has('limit')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('limit')); ?></strong>
                    </span>
                <?php endif; ?>

            </div>	
  
         	<div class="form-group<?php echo e($errors->has('start_time') ? ' has-error' : ''); ?>">
                <label class="control-label">Start Time <span class="text text-danger">*</span></label>
                <div class="clearfix" style="margin-bottom:10px;">
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                <?php echo Form::select('start_hours',$hours , old('start_hours') ? old('start_hours') : $start_hour, ['class'=>'form-control']); ?>

	           	 	</div>
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                <?php echo Form::select('start_minutes',$minutes , old('start_minutes') ? old('start_minutes') : $start_minutes , ['class'=>'form-control']); ?>

	           	 	</div>
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                <?php echo Form::select('start_ampm',$ampm , old('start_ampm') ? old('start_ampm') :  $start_ampm, ['class'=>'form-control']); ?>

	           	 	</div>
           	 	</div>
            </div>
         	<div class="form-group<?php echo e($errors->has('end_time') ? ' has-error' : ''); ?>">
                <label class="control-label">End Time <span class="text text-danger">*</span></label>
                <div class="clearfix" style="margin-bottom:10px;">
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                <?php echo Form::select('end_hours',$hours , old('end_hours') ? old('end_hours') : $end_hour, ['class'=>'form-control']); ?>

	           	 	</div>
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                <?php echo Form::select('end_minutes',$minutes , old('end_minutes') ? old('end_minutes') : $end_minutes, ['class'=>'form-control']); ?>

	           	 	</div>
	                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
	                <?php echo Form::select('end_ampm',$ampm , old('end_ampm') ? old('end_ampm') : $end_ampm, ['class'=>'form-control']); ?>

	           	 	</div>
           	 	</div>
            </div>   
            <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?>">
                <label class="control-label">Zipcode(s) <span class="text text-danger">*</span></label>

                <?php echo Form::select('zipcode_select',$zipcodes ,'', ['id'=>'zipcode_select','class'=>'form-control']); ?>


            </div>	
            <div class="form-group">
            	<label class="control-label">Zipcode(s) Selected</label>
	            <div class="table-responsive">
	            	<table id="zipcode_table" class="table table-condensed table-striped table-hover">
	            		<thead>
	            			<tr>
	            				<th>Zipcode</th>
	            				<th>Action</th>
	            			</tr>
	            		</thead>
	            		<tbody>
	            		<?php if(count($zipcodes_selected) > 0): ?>
	            			<?php foreach($zipcodes_selected as $key => $z): ?>
	            			<tr id="zipcode_tr-<?php echo e($z->zipcode); ?>" class="zipcode_tr">
	            				<td><?php echo e($z->zipcode); ?></td>
	            				<td>
	            					<button type="button" class="btn btn-sm btn-danger zipcode_remove">remove</button>
	            					<input type="hidden" value="<?php echo e($z->zipcode); ?>" name="zipcode[<?php echo e($key); ?>]"/>
	            				</td>
	            			</tr>
	            			<?php endforeach; ?>
	            		<?php endif; ?>
	            		</tbody>
	            	</table>
	           	</div>   
           	</div> 
            <div class="form-group<?php echo e($errors->has('blackout') ? ' has-error' : ''); ?>">
                <label class="control-label">Blackout Date(s) <span class="text text-danger">*</span></label>

                <input id="blackout" type="text" class="form-control" value="" style="background-color:#ffffff" readonly="true">

            </div>	
            <div class="form-group">
            	<label class="control-label">Blackout Date(s) Chosen</label>
	            <div class="table-responsive">
	            	<table id="blackout_table" class="table table-condensed table-striped table-hover">
	            		<thead>
	            			<tr>
	            				<th>Date</th>
	            				<th>Action</th>
	            			</tr>
	            		</thead>
	            		<tbody>
	            		<?php if($blackout_dates): ?>
	            			<?php foreach($blackout_dates as $key => $blackout): ?>

	            			<tr class="blackout_tr" date="<?php echo e(date('Y-m-d',strtotime($blackout))); ?>">
	            				<td><?php echo e(date('D n/d/Y',strtotime($blackout))); ?></td>
	            				<td>
	            					<button type="button" class="btn btn-sm btn-danger blackout_remove">remove</button>
	            					<input type="hidden" name="blackout[<?php echo e($key); ?>]" value="<?php echo e($blackout); ?>"/>
	            				</td>
	            			</tr>

	            			<?php endforeach; ?>
	            		<?php endif; ?>
	            		</tbody>
	            	</table>
	           	</div>   
           	</div>     		
		</div>

		<div class="panel-footer clearfix">
			<a href="<?php echo e(route('delivery_setup')); ?>" class="btn btn-danger">Cancel</a>
			<button type="submit" class="btn btn-primary pull-right">Edit</button>
		</div>

	</div>
	<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>