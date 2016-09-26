<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/deliveries/new.js"></script>

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
		<?php if(!$customer_id): ?>
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
		<?php else: ?>
		<div class="panel-body">
			<h5>Customer Selected</h5>
			<div class="table-responsive">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>Id</th>
							<th>Username</th>
							<th>Last</th>
							<th>First</th>
							<th>Phone</th>
						</tr>
					</thead>
					<tbody>
						<tr class="success">
							<td><?php echo e($customers->id); ?></td>
							<td><?php echo e($customers->username); ?></td>
							<td><?php echo e($customers->last_name); ?></td>
							<td><?php echo e($customers->first_name); ?></td>
							<td><?php echo e(\App\Job::formatPhoneString($customers->phone)); ?></td>
						</tr>
					</tbody>
				</table>	
			</div>	
		</div>
		<div class="panel-heading" style="border-radius:0px;"><h4>Card Selection</h4></div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>Id</th>
							<th>Card</th>
							<th>Exp</th>
							<th>Remaining</th>
							<th>Type</th>
							<th>A</th>
							<th>-</th>
						</tr>
					</thead>

					<tbody>
					<?php if(count($cards)): ?>
						<?php foreach($cards as $card): ?>
						<tr style="cursor:pointer;">
							<td><?php echo e($card['id']); ?></td>
							<td><?php echo e($card['card_number']); ?></td>
							<td><?php echo e($card['exp_month']); ?>/<?php echo e($card['exp_year']); ?></td>
							<td><?php echo e($card['days_remaining']); ?></td>
							<td><?php echo e($card['card_type']); ?></td>
							<td><input class="card_id" type="checkbox" value="<?php echo e($card['id']); ?>"/></td>
							<td><a href="<?php echo e(route('cards_admins_edit',$card['id'])); ?>">update</a></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div >
				<a href="<?php echo e(route('cards_admins_index',$customer_id)); ?>" class="btn btn-primary">Add Card</a>
			</div>
		</div>
		<div class="panel-heading" style="border-radius:0px;"><h4>Address Selection</h4></div>
		<div class="panel-body">
			<h4>Address(es) On File</h4>
			<div class="table-responsive">
				<table class="table table-condensed table-">
					<thead>
						<tr>
							<th>Name</th>
							<th>Street</th>
							<th>Suite</th>
							<th>City</th>
							<th>State</th>
							<th>Zip</th>
							<th>Primary</th>
							<th>A.</th>
							<th>-</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($addresses)): ?>
						<?php foreach($addresses as $address): ?>
						<tr class="<?php echo e(($address->zipcode_status ? 'address_tr' : 'danger')); ?>" style="cursor:pointer;">
							<td><?php echo e($address->name); ?></td>
							<td><?php echo e($address->street); ?></td>
							<td><?php echo e($address->suite); ?></td>
							<td><?php echo e($address->city); ?></td>
							<td><?php echo e($address->state); ?></td>
							<td><?php echo e($address->zipcode); ?></td>
							<td><?php echo e($address->primary ? 'Yes' : 'No'); ?></td>
							<td>
								<?php if($address->zipcode_status): ?>
								<input class="address_id" type='checkbox' name="address_id" value="<?php echo e($address->id); ?>" /></td>
								<?php else: ?>
								<input type="checkbox" disabled="true" />
								<?php endif; ?>
							</td>
							<td><a href="<?php echo e(route('address_admin_edit',$address->id)); ?>">update</a></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
			<a href="<?php echo e(route('address_admin_add',$customer_id)); ?>" class="btn btn-primary">Add New Address</a>
		</div>		
		<div class="panel-heading" style="border-radius:0px;"><h4>Pickup Form</h4></div>
		<div class="panel-body">
			<div class="form-group <?php echo e($errors->has('pickingup') ? ' has-error' : ''); ?>">
				<label class="control-label">Are you picking up?</label>
				<?php echo e(Form::select('pickingup',['1'=>'Yes','0'=>'No'],0,['id'=>'pickingup','class'=>"form-control"])); ?>

                <?php if($errors->has('search')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('pickingup')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>		
			<div >

			</div>	
		</div>
		<div class="panel-heading" style="border-radius:0px;"><h4>Dropoff Form</h4></div>
		<div class="panel-body">
			<div class="form-group <?php echo e($errors->has('droppingoff') ? ' has-error' : ''); ?>">
				<label class="control-label">Are you dropping off?</label>
				<?php echo e(Form::select('droppingoff',['1'=>'Yes','0'=>'No'],0,['id'=>'droppingoff','class'=>"form-control"])); ?>

                <?php if($errors->has('search')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('droppingoff')); ?></strong>
                    </span>
                <?php endif; ?>
			</div>		
			<div >

			</div>	
		</div>
		<?php endif; ?>
		<div class="panel-footer">
			<button>Set Delivery</button>
		</div>

	</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
	<?php echo View::make('partials.deliveries.cards_form')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>