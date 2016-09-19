<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="/css/deliveries/delivery.css" type="text/css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>
<script type="text/javascript" src="/js/deliveries/dropoff.js"></script>
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
    $('#dropoffdate').Zebra_DatePicker({
        container:$("#dropoff_container"),
        format:'D m/d/Y',
        disabled_dates: disabled_dates,
        direction: ['<?php echo e($date_start); ?>', false],
        show_select_today: false,
        onSelect: function(a, b) {
            var dropoff_address_id = $("#dropoff_address").val();
            request.set_time_dropoff(b, dropoff_address_id);
        }
    });

</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>
    <header id="header" class="reveal">
        <h1 id="logo"><a href="<?php echo e(route('pages_index')); ?>">Jays Cleaners</a></h1>
        <nav id="nav">
            <ul>
                <li class="submenu">
                    <a href="#"><small>Hello </small><strong><?php echo e($auth->username); ?></strong></a>
                    <ul>
                        <li><a href="<?php echo e(route('delivery_index')); ?>">Your Deliveries</a></li>
                        <li><a href="left-sidebar.html">Services</a></li>
                        <li><a href="right-sidebar.html">Business Hours</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                        <li class="submenu">
                            <a href="#"><?php echo e($auth->username); ?> menu</a>
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

            <a href="<?php echo e(route('delivery_pickup')); ?>" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px; overflow:hidden;">
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
            <a href="<?php echo e(route('delivery_dropoff')); ?>" class="btn btn-default active col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px; overflow:hidden;">
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
                <?php echo Form::open(['action' => 'DeliveriesController@postDropoffForm', 'class'=>'form-horizontal','role'=>"form"]); ?>

                    <?php echo csrf_field(); ?> 
                    <?php echo Form::hidden('dropoff_address',$primary_address_id,['id'=>'dropoff_address']); ?>

                    <div class="panel-heading"><strong>Dropoff Form </strong>- we deliver to you!</div>

                    <div id="dropoff_body" class="panel-body">

<!--   
                        <div class="form-group<?php echo e($errors->has('dropoff_address') ? ' has-error' : ''); ?> dropoff_address_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Address</label>

                            <div class="col-md-6">
                                
                                <?php echo e(Form::select('dropoff_address',$addresses,$primary_address_id,['id'=>'dropoff_address','class'=>'form-control','readonly'=>'true'])); ?>

                                <?php if($errors->has('dropoff_address')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('dropoff_address')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                        </div> -->
                    

                        <div class="form-group<?php echo e($errors->has('dropoff_date') ? ' has-error' : ''); ?> dropoff_date_div">
                            <label class="col-md-4 control-label padding-top-none">Dropoff Date</label>

                            <div id="dropoff_container" class="col-md-6">
                                <input id="dropoffdate" type="text" class="form-control" name="dropoff_date" value="<?php echo e(old('dropoff_date')); ?>" style="background-color:#ffffff">

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
                                
                                <?php echo e(Form::select('dropoff_time',[''=>'select time'],null,['id'=>'dropofftime','class'=>'form-control','disabled'=>'true'])); ?>

                                <?php if($errors->has('dropoff_time')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('dropoff_time')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                    	<a href="<?php echo e(route('delivery_pickup')); ?>" class='btn btn-link btn-lg'><i class="ion-arrow-left-c"></i> Back</a>
                        <button id="dropoff_submit" type="submit" data-toggle="modal" data-target="#loading" class="btn btn-lg btn-primary pull-right" disabled="true">Next</button>
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