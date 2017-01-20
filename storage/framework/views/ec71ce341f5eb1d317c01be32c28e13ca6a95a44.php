<?php echo Form::open(['action' => 'SchedulesController@postSetupRoute','role'=>"form",'class'=>'pull-right']); ?>

<?php echo Form::hidden('id',$schedule['id']); ?>	
<div id="edit-<?php echo e($schedule['id']); ?>" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Edit Route Information</strong></h4>
			</div>
			<div class="modal-body clearfix">
                <div class="form-group<?php echo e($errors->has('first_name') ? ' has-error' : ''); ?> clearfix">
                    <label class="col-md-12 control-label padding-top-none">First Name </label>

                    <div class="col-md-12 clearfix">
                        
                        <?php echo e(Form::text('first_name',old('first_name') ? old('first_name') : $schedule['first_name'],['class'=>'form-control'])); ?>

                        <?php if($errors->has('content')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('first_name')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('last_name') ? ' has-error' : ''); ?> clearfix">
                    <label class="col-md-12 control-label padding-top-none">Last Name </label>

                    <div class="col-md-12 clearfix">
                        
                        <?php echo e(Form::text('last_name',old('last_name') ? old('last_name') : $schedule['last_name'],['class'=>'form-control'])); ?>

                        <?php if($errors->has('content')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('last_name')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('street') ? ' has-error' : ''); ?> clearfix">
                    <label class="col-md-12 control-label padding-top-none">Street </label>

                    <div class="col-md-12 clearfix">
                        
                        <?php echo e(Form::text('street',old('street') ? old('street') : $schedule['street'],['class'=>'form-control'])); ?>

                        <?php if($errors->has('content')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('street')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('city') ? ' has-error' : ''); ?> clearfix">
                    <label class="col-md-12 control-label padding-top-none">City </label>

                    <div class="col-md-12 clearfix">
                        
                        <?php echo e(Form::text('city',old('city') ? old('city') : $schedule['city'],['class'=>'form-control'])); ?>

                        <?php if($errors->has('content')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('city')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?> clearfix">
                    <label class="col-md-12 control-label padding-top-none">Zipcode </label>

                    <div class="col-md-12 clearfix">
                        
                        <?php echo e(Form::text('zipcode',old('zipcode') ? old('zipcode') : $schedule['zipcode'],['class'=>'form-control'])); ?>

                        <?php if($errors->has('content')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('zipcode')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('employee_id') ? ' has-error' : ''); ?> clearfix">
                    <label class="col-md-12 control-label padding-top-none">Driver </label>

                    <div class="col-md-12 clearfix">
                        
                        <?php echo e(Form::select('employee_id',$drivers,old('employee_id'),['class'=>'form-control'])); ?>

                        <?php if($errors->has('employee_id')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('employee_id')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
			</div>

			<div class="modal-footer clearfix">
				<button type="button" class="btn pull-left" data-dismiss="modal">Close</button>

				<input type="submit" class="btn btn-primary" value="Finish Setup" />
				
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	
</div><!-- /.modal -->
<?php echo Form::close(); ?>