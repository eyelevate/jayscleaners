<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.search').Zebra_DatePicker();
});

</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo Form::open(array('action' => 'AdminsController@postRackHistory','role'=>"form")); ?>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Rack History Search</h3>
		</div>
		<div class="panel-body">
			<div class="form-group<?php echo e($errors->has('company_id') ? ' has-error' : ''); ?>">
                <label class="control-label">Company <span class="text text-danger">*</span></label>

                <?php echo Form::select('company_id',[''=>'Select Company','1'=>'Roosevelt','2'=>'Montlake'], (isset($company_id)) ? $company_id : Auth::user()->company_id , ['class'=>'company_id form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('company_id')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('company_id')); ?></strong>
                    </span>
                <?php endif; ?>

            </div>
			<div class="form-group<?php echo e($errors->has('search') ? ' has-error' : ''); ?>">
                <label class="control-label">Search Date <span class="text text-danger">*</span></label>

                <?php echo Form::text('search', (isset($search)) ? $search : NULL , ['class'=>'search form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('search')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('search')); ?></strong>
                    </span>
                <?php endif; ?>

            </div>

		</div>
		<div class="panel-footer">
			<input type="submit" class="btn btn-info btn-lg" value="Search"/>
		</div>
	</div>

<?php echo Form::close(); ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Rack History Search</h3>
		</div>
		<div class="panel-body">


		</div>
		<div class="table-responsive">
			<table class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th>Inv #</th>
						<th>Cust #</th>
						<th>Name</th>
						<th>Date</th>
						<th>Rack</th>
					</tr>
				</thead>
				<tbody>
				<?php if(isset($history)): ?>
					<?php if(count($history) > 0): ?>
						<?php foreach($history as $h): ?>
						<tr>
							<td><?php echo e($h->id); ?></td>
							<td><?php echo e($h->customer_id); ?></td>
							<td><?php echo e(ucFirst($h['customer']->last_name).', '.ucFirst($h['customer']->first_name)); ?></td>
							<td><?php echo e(date('n/d/Y g:i:s a',strtotime($h->rack_date))); ?></td>
							<td><?php echo e($h->rack); ?></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endif; ?>
				</tbody>
			
			</table>
		</div>
		<div>
		</div>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>