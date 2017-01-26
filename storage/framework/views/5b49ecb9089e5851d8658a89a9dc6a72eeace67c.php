<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/schedules/checklist.js"></script>
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
        <?php echo Form::open(['action' => 'SchedulesController@postChecklist','role'=>"form",'id'=>'search_form']); ?>

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
			<a href="<?php echo e(route('delivery_overview')); ?>" class="btn btn-lg btn-danger pull-left col-md-2 col-sm-6 col-xs-6"><i class="ion ion-chevron-left"></i>&nbsp; Back</a>
			<a href="<?php echo e(route('schedules_prepare_route')); ?>" class="btn btn-lg btn-primary pull-right col-md-2 col-sm-6 col-xs-6">Prepare Route(s) &nbsp;<i class="ion ion-chevron-right"></i></a>
		</div>
	</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
	<?php if(count($schedules) > 0): ?>
		<?php foreach($schedules as $schedule): ?>
		<?php echo View::make('partials.schedules.email')
			->with('schedule_id',$schedule['id'])
			->render(); ?>

		<?php endforeach; ?>
	<?php endif; ?>
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
	<?php echo View::make('partials.frontend.modals')->render(); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>