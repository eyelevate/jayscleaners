<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<br/>
<?php echo Form::open(['action' => 'ZipcodesController@postEdit','role'=>"form"]); ?>

	<?php echo Form::hidden('id',$zipcodes->id); ?>

	<?php echo Form::hidden('list_id',$list_id); ?>

	<div class="panel panel-default">
		<div class="panel-heading"><h4 class="panel-title">Edit Zipcode</h4></div>
		<div class="panel-body" >
            <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?>">
                <label class="control-label padding-top-none">Zipcode <span style="color:#ff0000">*</span></label>

                <?php echo e(Form::text('zipcode',old('zipcode') ? old('zipcode') : $zipcodes->zipcode,['class'=>'form-control'])); ?>

                <?php if($errors->has('zipcode')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('zipcode')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
            <div class="form-group<?php echo e($errors->has('routes') ? ' has-error' : ''); ?>">
                <label class="control-label padding-top-none">Add Route(s) <span style="color:#ff0000">*</span></label>

                <?php echo e(Form::select('routes',$deliveries,'',['class'=>'form-control'])); ?>

                <?php if($errors->has('routes')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('routes')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>			
		</div>

		<div class="panel-footer">
			<a href="<?php echo e(route('zipcodes_index')); ?>" class="btn btn-danger">Back</a>
			<button class="btn btn-primary pull-right" type="submit">Update</button>
		</div>
	</div>
<?php echo Form::close(); ?>

<?php echo Form::open(['action'=>'ZipcodesController@postDelete','role'=>'form']); ?>

	
	<div class="panel panel-default">
		<div class="panel-heading"><h4 class="panel-title">Zipcode Accepted Routes</h4></div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Day</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if(count($edits) > 0): ?>
					<?php foreach($edits as $edit): ?>
					<tr>
						<td><?php echo e($edit->id); ?></td>
						<td><?php echo e($edit['delivery']->route_name); ?></td>
						<td><?php echo e($edit['delivery']->day); ?></td>
						<td>
							<a type="button" class="btn btn-sm btn-danger" href="<?php echo e(route('zipcodes_delete',$edit->id)); ?>">Delete</a>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>