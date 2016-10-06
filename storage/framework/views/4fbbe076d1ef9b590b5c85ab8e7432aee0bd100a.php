<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/ion.sound-3.0.7/ion.sound.min.js"></script>
<script type="text/javascript" src="/js/invoices/rack.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<br/>

	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="box box-primary" >
				<div class="box-header"><h4>Rack Form</h4></div>
				<div class="box-body">
					<div class="form-group">
						<label>Invoice #</label>
						<input id="invoice_id" class="rack_input form-control" type="text" value="" placeholder="invoice #"/>
					</div>
					<div class="form-group">
						<label>Rack #</label>
						<input id="rack_number" class="rack_input form-control" type="text" value="" placeholder="rack #"/>
					</div>
				</div>
				<div class="box-footer clearfix">
					

				</div>
			</div>
		</article>
		<?php echo Form::open(['action' => 'InvoicesController@postRack','role'=>"form"]); ?>

		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="box box-info">
				<div class="box-header"><h4>Invoice Selected</h4></div>
				<div class="table-responsive">
					<table class="table table-condensed table-striped table-hover">
						<thead>
							<th>ID</th>
							<th>Rack</th>
							<th>Action</th>
						</thead>
						<tbody id="rack_tbody">
						<?php if($racks): ?> 
							<?php foreach($racks as $key => $value): ?>
							<tr>
								<td><?php echo e($key); ?></td>
								<td><?php echo e($value); ?></td>
								<td><a invoice="<?php echo e($key); ?>" class="remove btn btn-sm btn-danger">Remove</a><input type="hidden" name="rack[<?php echo e($key); ?>]" value="<?php echo e($value); ?>"/></td>
							</tr>
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>

					</table>
				</div>
				<div class="box-footer clearfix">
					<a class="btn btn-lg btn-danger" href="<?php echo e(URL::previous()); ?>">Back</a>
					<button type="submit" class="btn btn-lg btn-success" >Finish</button>

				</div>
			</div>
		</article>
		<?php echo Form::close(); ?>

	</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
=
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>