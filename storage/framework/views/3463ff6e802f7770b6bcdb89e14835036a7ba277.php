<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="/packages/ddslick/ddslick.min.js"></script>
<script src="/js/inventories/index.js" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
<h1> Inventory <small>Control panel</small></h1>
<br/>
<ol class="breadcrumb">
	<li><a href="<?php echo e(route('admins_index')); ?>"><i class="fa fa-dashboard"></i> Admins</a></li>
	<li><a href="<?php echo e(route('admins_settings')); ?>"> Settings</a></li>
	<li class="active">Inventory</li>
</ol>
<div class="row clearfix">
	<!-- Inventory Group -->
	<a href="#" class="col-lg-3 col-md-3 col-xs-3">
		<!-- small box -->
		<div class="small-box bg-primary" style="padding-bottom:10px" data-toggle="modal" data-target="#group-add">
			<div class="inner">
				<h4>Add Group</h4>
				<p>Add inventory group</p>
			</div>
	        <div class="icon">
	          <i class="ion-ios-plus-outline"></i>
	        </div>

		</div>
	</a><!-- ./col -->
	<!-- Inventory Group Edit -->
	<a id="inventory-edit" href="#" class="col-lg-3 col-md-3 col-xs-3">
		<!-- small box -->
		<div class="small-box bg-yellow" style="padding-bottom:10px" data-toggle="modal" data-target="#group-edit">
			<div class="inner">
				<h4>Edit Group</h4>
				<p>Edit inventory group</p>
			</div>
	        <div class="icon">
	          <i class="ion-edit"></i>
	        </div>

		</div>
	</a><!-- ./col -->
	<!-- Inventory Item -->
	<a href="#" class="col-lg-3 col-md-3 col-xs-3">
		<!-- small box -->
		<div class="small-box bg-primary" style="padding-bottom:10px" data-toggle="modal" data-target="#item-add">
			<div class="inner">
				<h4>Add Item</h4>
				<p>Add inventory item</p>
			</div>
	        <div class="icon">
	          <i class="ion-plus"></i>
	        </div>

		</div>
	</a><!-- ./col -->


</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="nav-tabs-custom">
	<?php echo Form::open(['action' => 'InventoriesController@postOrder', 'class'=>'form-horizontal','role'=>"form",'id'=>'group-form']); ?>

		<?php echo csrf_field(); ?>

		<!-- Tabs within a box -->
		<ul id="inventory-group-ul" class="nav nav-tabs">
			<?php $idx = -1;?>
			<?php if(isset($inventories)): ?>
				<?php foreach($inventories as $inventory): ?>
				<?php $idx++;?>
				<li class="<?php echo e(($idx == 0) ? 'active' : ''); ?>" class="cursor:pointer">
					<a href="#sales-<?php echo e($inventory->id); ?>" data-toggle="tab"><?php echo e($inventory->name); ?></a>
					<div class="hide">
						<?php echo e(Form::hidden('inventory['.$idx.'][id]',$inventory->id,['class'=>'inventory-id'])); ?>

						<?php echo e(Form::hidden('inventory['.$idx.'][name]',$inventory->name,['class'=>'inventory-name'])); ?>

						<?php echo e(Form::hidden('inventory['.$idx.'][description]',$inventory->description,['class'=>'inventory-description'])); ?>

						<?php echo e(Form::hidden('inventory['.$idx.'][ordered]',$inventory->ordered,['class'=>'inventory-order'])); ?>

					</div>
				</li>
				<?php endforeach; ?>
			<?php endif; ?>

		</ul>
	<?php echo Form::close(); ?>


	<ul class="tab-content no-padding">
		<!-- Morris chart - Sales -->
		<?php $idx = -1;?>
		<?php if(isset($items)): ?>
			<?php foreach($items as $key => $value): ?>
			<?php $idx++;?>
			<li class="chart tab-pane <?php echo e(($idx == 0) ? 'active' : ''); ?>" id="sales-<?php echo e($key); ?>" style="position: relative; min-height: 300px; padding:5px;">
				<div class="table-responsive">			
					<table class="table table-condensed table-hover table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Order</th>
								<th>Name</th>
								<th>Qty</th>
								<th>Tags</th>
								<th>Desc</th>
								<th>Price</th>
								<th>Img</th>
								<th>A.</th>
							</tr>
						</thead>
						<tbody class="sortable-tbody">
						<?php if(isset($value['items'])): ?>
							<?php foreach($value['items'] as $item): ?>
							<tr style="cursor:pointer;" id="item_list-<?php echo e($item->id); ?>" order="<?php echo e($item->ordered); ?>">
								<td><?php echo e($item->id); ?></td>
								<td class="item_order"><?php echo e($item->ordered); ?></td>
								<td><?php echo e($item->name); ?></td>
								<td><?php echo e($item->quantity); ?></td>
								<td><?php echo e($item->tags); ?></td>
								<td><?php echo e($item->description); ?></td>
								<td><?php echo e($item->price); ?></td>
								<td><?php echo e($item->image); ?></td>
								<td><button class="button" data-toggle="modal" data-target="#item_edit-<?php echo e($item->id); ?>">Edit</button></td>
							</tr>
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</div><!-- /.nav-tabs-custom -->


<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>

	<?php echo View::make('partials.inventories.group-add') 
		->with('companies',$companies)
		->render(); ?>

	<?php echo View::make('partials.inventories.group-edit') 
		->with('companies',$companies)
		->render(); ?>

	<?php echo View::make('partials.inventories.item-add')
		->with('companies',$companies)
		->with('group_select',$group_select)
		->with('icon_select',$icon_select)
		->with('tags_select',$tags_select)
		->with('quantity_select',$quantity_select)
		->render(); ?>

	<?php if(isset($items)): ?>
		<?php foreach($items as $key => $value): ?>
			<?php if(isset($value['items'])): ?>
				<?php foreach($value['items'] as $item): ?>
				<?php echo View::make('partials.inventories.item-edit')
					->with('companies',$companies)
					->with('item',$item)
					->with('group_select',$group_select)
					->with('icon_select',$icon_select)
					->with('tags_select',$tags_select)
					->with('quantity_select',$quantity_select)
					->render(); ?>

				<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php echo View::make('partials.inventories.group-delete')
		->render(); ?>

	<?php echo View::make('partials.inventories.item-delete')
		->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>