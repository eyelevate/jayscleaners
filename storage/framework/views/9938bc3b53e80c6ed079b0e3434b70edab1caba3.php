<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="/css/deliveries/delivery.css" type="text/css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>
<script type="text/javascript" src="/js/deliveries/admin-edit.js"></script>
<script type="text/javascript">
	disabled_dates = [];
    <?php
    if (count($calendar_disabled) > 0) {
        foreach ($calendar_disabled as $cd) {
        ?>
        var item_string = '<?php echo e($cd); ?>';
        disabled_dates.push(item_string);
        <?php
        }
    }
    ?>
    $('#pickupdate').Zebra_DatePicker({
        container:$("#pickup_container"),
        format:'D m/d/Y',
        disabled_dates: disabled_dates,
        direction: [true, true],
        show_select_today: true,
        <?php if($selected_date): ?>
        start_date :'<?php echo e(date("D m/d/Y",strtotime($selected_date))); ?>',
        <?php endif; ?>
        onSelect: function(a, b) {
            var pickup_address_id = $("#pickup_address option:selected").val();
            var pickup_delivery_id = $("#pickuptime option:selected").val();
            request.set_time_pickup(b, pickup_address_id, pickup_delivery_id);
        }
    });
    $('#dropoffdate').Zebra_DatePicker({
        container:$("#dropoff_container"),
        format:'D m/d/Y',
        disabled_dates: disabled_dates,
        direction: ['<?php echo e(date("D m/d/Y",strtotime($dropoff_date))); ?>', false],
        show_select_today: true,
        onSelect: function(a, b) {
            var dropoff_address_id = $("#pickup_address option:selected").val();
            request.set_time_dropoff(b, dropoff_address_id);
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <br/>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                <?php echo Form::open(['action' => 'DeliveriesController@postAdminEdit', 'class'=>'form-horizontal','role'=>"form"]); ?>

                    <?php echo csrf_field(); ?> 
                    <?php echo Form::hidden('id',$update_id); ?>

                    <div class="panel-heading"><strong>Delivery Update Form</strong></div>
                    <div id="pickup_body" class="panel-body">   
                        <div class="form-group<?php echo e($errors->has('pickup_address') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Pickup Card</label>

                            <div class="col-md-6">
                                
                                <?php echo e(Form::select('card_id',$cards,$card_id,['class'=>'form-control'])); ?>

                                <?php if($errors->has('card_id')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('card_id')); ?></strong>
                                    </span>
                                <?php endif; ?>

                                <a href="<?php echo e(route('cards_admins_index',$customer_id)); ?>" class="btn btn-link">Manage your credit card(s)</a>
                            </div>
                        </div>


                        <div class="form-group<?php echo e($errors->has('pickup_address') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Pickup Address</label>

                            <div class="col-md-6">
                                
                                <?php echo e(Form::select('pickup_address',$addresses,$schedule->dropoff_address,['class'=>'form-control','id'=>'pickup_address'])); ?>

                                <?php if($errors->has('pickup_address')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('pickup_address')); ?></strong>
                                    </span>
                                <?php endif; ?>

                                <a href="<?php echo e(route('address_admin_index',$customer_id)); ?>" class="btn btn-link">Manage your address(es)</a>
                            </div>
                        </div>

                        <div class="form-group<?php echo e($errors->has('pickup_date') ? ' has-error' : ''); ?> pickup_date_div ">
                            <label class="col-md-4 control-label padding-top-none">Pickup Date</label>

                            <div id="pickup_container" class="col-md-6">
                                <?php if(strtotime($schedule->pickup_date) > 0): ?> 
                                <input id="pickupdate" type="text" class="form-control" name="pickup_date" value="<?php echo e((old('pickup_date')) ? old('pickup_date') : ($schedule->pickup_date) ? date('D m/d/Y',strtotime($schedule->pickup_date)) : ''); ?>" style="background-color:#ffffff;">
                                <?php else: ?>
                                <input id="pickupdate" type="text" class="datepicker form-control" name="pickup_date" value="<?php echo e(old('pickup_date')); ?>" >
                                <?php endif; ?>
                                <?php if($errors->has('pickup_date')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('pickup_date')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                        <div class="form-group<?php echo e($errors->has('pickup_time') ? ' has-error' : ''); ?> pickup_time_div">
                            <label class="col-md-4 control-label padding-top-none">Pickup Time</label>

                            <div class="col-md-6">
                                <?php if($schedule->pickup_delivery_id > 0): ?>
                                <?php echo e(Form::select('pickup_time',$time_options,$schedule->pickup_delivery_id,['id'=>'pickuptime','class'=>'form-control'])); ?>

                                <?php else: ?>
                                <?php echo e(Form::select('pickup_time',[''=>'select time'],null,['id'=>'pickuptime','class'=>'form-control'])); ?>

                                <?php endif; ?>
                                
                                <?php if($errors->has('pickup_time')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('pickup_time')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
					<div id="dropoff_body" class="panel-body">  
                        <div class="form-group<?php echo e($errors->has('dropoff_date') ? ' has-error' : ''); ?> dropoff_date_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Date</label>

                            <div id="dropoff_container" class="col-md-6">
                                <?php if($zipcode_status): ?> 
                                <input id="dropoffdate" type="text" class="form-control" name="dropoff_date" value="<?php echo e((old('dropoff_date')) ? old('dropoff_date') : ($schedule->dropoff_date) ? date('D m/d/Y',strtotime($schedule->dropoff_date)) : ''); ?>" style="background-color:#ffffff;">
                                <?php else: ?>
                                <input id="dropoffdate" type="text" class="form-control" name="dropoff_date" value="<?php echo e(old('dropoff_date')); ?>" disabled="true">
                                <?php endif; ?>
                                

                                <?php if($errors->has('dropoff_date')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('dropoff_date')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('dropoff_time') ? ' has-error' : ''); ?> dropoff_time_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Time</label>

                            <div class="col-md-6">
                                
								<?php if($dropoff_delivery_id): ?>
                                <?php echo e(Form::select('dropoff_time',$time_options_dropoff,$dropoff_delivery_id,['id'=>'dropofftime','class'=>'form-control'])); ?>

                                <?php else: ?>
                                <?php echo e(Form::select('dropoff_time',[''=>'select time'],null,['id'=>'dropofftime','class'=>'form-control', 'disabled'=>"true"])); ?>

                                <?php endif; ?>

                                <?php if($errors->has('dropoff_time')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('dropoff_time')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('special_instructions') ? ' has-error' : ''); ?> dropoff_time_div">
                            <label class="col-md-4 control-label padding-top-none">Special Instructions</label>

                            <div class="col-md-6">
                                <?php echo e(Form::textarea('special_instructions',old('special_instructions') ? old('special_instructions') : ($special_instructions) ? $special_instructions : null), ['class'=>'form-control']); ?>


                                <?php if($errors->has('dropoff_time')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('dropoff_time')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('status') ? ' has-error' : ''); ?> ">
                            <label class="col-md-4 control-label padding-top-none">Status</label>

                            <div class="col-md-6">
                                <?php echo e(Form::select('status',$status_list,old('status') ? old('status') : ($status) ? $status : null, ['class'=>'form-control'])); ?>


                                <?php if($errors->has('status')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('status')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer clearfix">
                        <a href="<?php echo e(route('schedules_view',$customer_id)); ?>" class="btn btn-lg">Back</a>
                        <button type="button" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#cancel">Cancel Delivery</button>
                        <button id="pickup_submit" type="submit" class="btn btn-lg btn-primary pull-right" >Update</button>
                    </div>
                <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.deliveries.cancel')
        ->with('schedule_id',$update_id)
        ->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>