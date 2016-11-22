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
	<!-- Inventory Item Edit -->
	<a id="inventory-item-edit" href="#item-edit" class="col-lg-3 col-md-3 col-xs-3">
		<!-- small box -->
		<div class="small-box bg-yellow" style="padding-bottom:10px" data-toggle="modal" data-target="#item-edit">
			<div class="inner">
				<h4>Edit Item</h4>
				<p>Edit inventory item</p>
			</div>
	        <div class="icon">
	          <i class="ion-edit"></i>
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

	<?php echo Form::open(['action' => 'InventoryItemsController@postOrder', 'class'=>'form-horizontal','role'=>"form",'id'=>'item-form']); ?>

	<?php echo csrf_field(); ?>

	<ul class="tab-content no-padding">
		<!-- Morris chart - Sales -->
		<?php $idx = -1;?>
		<?php if(isset($items)): ?>
			<?php foreach($items as $key => $value): ?>
			<?php $idx++;?>
			<li class="chart tab-pane <?php echo e(($idx == 0) ? 'active' : ''); ?>" id="sales-<?php echo e($key); ?>" style="position: relative; min-height: 300px; padding:5px;">
			<?php if(isset($value['items'])): ?>
				<?php foreach($value['items'] as $item): ?>
				<a id="item-<?php echo e($item->id); ?>" href="#" class="items col-lg-3 col-md-3 col-xs-3">
					<!-- small box -->
					<div class="small-box bg-gray clearfix" style="max-height:125px; overflow:hidden">
						<div class="inner" style="padding-bottom:50px;">
							<h4><strong><?php echo e($item->name); ?></strong></h4>
							<small><strong><?php echo e($item->description); ?></strong></small>
					        <div class="icon" style="z-index:0">
					          <img src="<?php echo e($item->image); ?>" style="max-width:64px; opacity:0.8"/>
					        </div>
						</div>
				        <div class="small-box-footer" style="position:absolute; width:100%; bottom:0px; background:rgba(0,0,0,0.6); font-size:15px;"><strong><?php echo e($item->price); ?></strong></i></div>
					</div>
					<div class="hide">
						<?php echo e(Form::hidden('item['.$item->id.'][id]',$item->id,['class'=>'item-id'])); ?>

						<?php echo e(Form::hidden('item['.$item->id.'][name]',$item->name,['class'=>'item-name'])); ?>

						<?php echo e(Form::hidden('item['.$item->id.'][description]',$item->description,['class'=>'item-description'])); ?>

						<?php echo e(Form::hidden('item['.$item->id.'][ordered]',$item->ordered,['class'=>'item-order'])); ?>

						<?php echo e(Form::hidden('item['.$item->id.'][price]',$item->price,['class'=>'item-price'])); ?>

						<?php echo e(Form::hidden('item['.$item->id.'][image]',$item->image,['class'=>'item-image'])); ?>

						<?php echo e(Form::hidden('item['.$item->id.'][tags]',$item->tags,['class'=>'item-tags'])); ?>

						<?php echo e(Form::hidden('item['.$item->id.'][quantity]',$item->quantity,['class'=>'item-quantity'])); ?>

						<?php echo e(Form::hidden('item['.$item->id.'][inventory_id]',$item->ordered,['class'=>'item-inventory_id'])); ?>


					</div>
				</a><!-- ./col -->					
				<?php endforeach; ?>
			<?php endif; ?>
			</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
	<?php echo Form::close(); ?>

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

	<?php echo View::make('partials.inventories.item-edit')
		->with('companies',$companies)
		->with('group_select',$group_select)
		->with('icon_select',$icon_select)
		->with('tags_select',$tags_select)
		->with('quantity_select',$quantity_select)
		->render(); ?>

	<?php echo View::make('partials.inventories.group-delete')
		->render(); ?>

	<?php echo View::make('partials.inventories.item-delete')
		->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>