<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>
<!-- <script type="text/javascript" src="/js/deliveries/pickup.js"></script> -->
<?php if(isset($primary_address_id)): ?>
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
        direction: [true, false],
        show_select_today: false,
        <?php if($selected_date): ?>
        start_date :'<?php echo e(date("D m/d/Y",strtotime($selected_date))); ?>',
        <?php endif; ?>
        onSelect: function(a, b) {
            var pickup_address_id = $("#pickup_address option:selected").val();
            request.set_time_pickup(b, pickup_address_id);
        }
    });

</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>
    <header id="header" class="reveal">
        <h1 id="logo"><a href="<?php echo e(route('pages_index')); ?>">Jays Cleaners</a></h1>
        <nav id="nav">
            <ul>
                <li class="submenu">
                    <a href="#"><small>Hello </small><strong><?php echo e(Auth::user()->username); ?></strong></a>
                    <ul>
                        <li><a href="no-sidebar.html">Your Deliveries</a></li>
                        <li><a href="left-sidebar.html">Services</a></li>
                        <li><a href="right-sidebar.html">Business Hours</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                        <li class="submenu">
                            <a href="#"><?php echo e(Auth::user()->username); ?> menu</a>
                            <ul>
                                <li><a href="#">Dolore Sed</a></li>
                                <li><a href="#">Consequat</a></li>
                                <li><a href="#">Lorem Magna</a></li>
                                <li><a href="#">Sed Magna</a></li>
                                <li><a href="#">Ipsum Nisl</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a id="logout_button" href="#" class="button special">Logout</a>
                    <?php echo Form::open(['action' => 'PagesController@postLogout', 'id'=>'logout_form', 'class'=>'form-horizontal','role'=>"form"]); ?>

                    <?php echo Form::close(); ?>

                </li>
            </ul>
        </nav>
    </header>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div id="bc1" class="btn-group btn-breadcrumb col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <a href="<?php echo e(route('delivery_pickup')); ?>" class="btn btn-default active col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px; overflow:hidden;">
                <h2><span class="badge">1</span> Pickup</h2>
                <table class="table table-condensed ">
                    <tbody>
                        <tr>
                            <td><p style="margin:0;"><?php echo e($breadcrumb_data['pickup_address']); ?></p></td>
                        </tr>
                        <tr>
                            <td><p style="margin:0"><?php echo e($breadcrumb_data['pickup_date']); ?></p></td>
                        </tr>
                        <tr>
                            <td><p style="margin:0"><?php echo e($breadcrumb_data['pickup_time']); ?></p></td>
                        </tr>
                    </tbody>
                </table>
            </a>
            <a href="<?php echo e(route('delivery_dropoff')); ?>" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12 disabled" disabled="true" style="height:160px; overflow:hidden;">
                <h2><span class="badge">2</span> Dropoff</h2>
                <table class="table table-condensed ">
                    <tbody>
                        <tr>
                            <td><p style="margin:0;"><?php echo e($breadcrumb_data['dropoff_address']); ?></p></td>
                        </tr>
                        <tr>
                            <td><p style="margin:0"><?php echo e($breadcrumb_data['dropoff_date']); ?></p></td>
                        </tr>
                        <tr>
                            <td><p style="margin:0"><?php echo e($breadcrumb_data['dropoff_time']); ?></p></td>
                        </tr>
                    </tbody>
                </table>
            </a>
            <a href="<?php echo e(route('delivery_confirmation')); ?>" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12 disabled" disabled="true" style="height:160px; overflow:hidden;">
                <h2><span class="badge">3</span> Confirm</h2>
            </a>

        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                <?php echo Form::open(['action' => 'DeliveriesController@postPickupForm', 'class'=>'form-horizontal','role'=>"form"]); ?>

                    <?php echo csrf_field(); ?> 
                    <div class="panel-heading"><strong>Pickup Form</strong> - we pick up from you.</div>
                    <div id="pickup_body" class="panel-body">                   
                        <div class="form-group<?php echo e($errors->has('pickup_address') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Pickup Address</label>

                            <div class="col-md-6">
                                
                                <?php echo e(Form::select('pickup_address',$addresses,$primary_address_id,['class'=>'form-control','id'=>'pickup_address'])); ?>

                                <?php if($errors->has('pickup_address')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('pickup_address')); ?></strong>
                                    </span>
                                <?php endif; ?>

                                <a href="<?php echo e(route('address_index')); ?>" class="btn btn-link">Manage your address(es)</a>
                            </div>
                        </div>

                        <div class="form-group<?php echo e($errors->has('pickup_date') ? ' has-error' : ''); ?> pickup_date_div ">
                            <label class="col-md-4 control-label padding-top-none">Pickup Date</label>

                            <div id="pickup_container" class="col-md-6">
                                <?php if($zipcode_status): ?> 
                                <input id="pickupdate" type="text" class="form-control" name="pickup_date" value="<?php echo e((old('pickup_date')) ? old('pickup_date') : ($selected_date) ? date('D m/d/Y',strtotime($selected_date)) : ''); ?>" style="background-color:#ffffff;">
                                <?php else: ?>
                                <input id="pickupdate" type="text" class="datepicker form-control" name="pickup_date" value="<?php echo e(old('pickup_date')); ?>" disabled="true">
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
                                <?php if($selected_delivery_id): ?>
                                <?php echo e(Form::select('pickup_time',$time_options,$selected_delivery_id,['id'=>'pickuptime','class'=>'form-control'])); ?>

                                <?php else: ?>
                                <?php echo e(Form::select('pickup_time',[''=>'select time'],null,['id'=>'pickuptime','class'=>'form-control', 'disabled'=>"true"])); ?>

                                <?php endif; ?>
                                
                                <?php if($errors->has('pickup_time')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('pickup_time')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer clearfix">
                        <a href="<?php echo e(route('delivery_cancel')); ?>" class="btn btn-danger btn-lg">Cancel</a>
                        <button id="pickup_submit" type="submit" class="btn btn-lg btn-primary pull-right" >Next</button>
                    </div>
                <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>