<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/deliveries/new.js"></script>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<br/>
	<div class="panel panel-primary">
		<div class="panel-heading"><h4>Customer Search Form</h4></div>
		<div class="panel-body">
			<?php echo Form::open(['action' => 'DeliveriesController@postFindCustomer','role'=>"form"]); ?>

			<div class="form-group <?php echo e($errors->has('search') ? ' has-error' : ''); ?>">
				<label class="control-label">Search</label>
				<?php echo e(Form::text('search',old('search'),['class'=>"form-control",'placeholder'=>'Last Name / Phone / ID'])); ?>

                <?php if($errors->has('search')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('search')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Search</button>
			</div>
			<?php echo Form::close(); ?>

		</div>
		<div class="table-responsive">
			<table class="table table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th>Id</th>
						<th>Username</th>
						<th>Last</th>
						<th>First</th>
						<th>Phone</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if(count($customers) > 0): ?>
					<?php foreach($customers as $customer): ?>
					<tr>
						<td><?php echo e($customer['id']); ?></td>
						<td><?php echo e($customer['username'] ? $customer['username'] : ''); ?></td>
						<td><?php echo e($customer['last_name']); ?></td>
						<td><?php echo e($customer['first_name']); ?></td>
						<td><?php echo e(\App\Job::formatPhoneString($customer['phone'])); ?></td>
						<td><a href="<?php echo e(route('delivery_new',$customer['id'])); ?>">Select</a></td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>

		<div class="panel-footer"></div>

	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>