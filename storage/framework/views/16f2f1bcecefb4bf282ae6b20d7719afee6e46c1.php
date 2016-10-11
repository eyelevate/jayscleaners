<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/css/colors/index.css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script src="/packages/ddslick/ddslick.min.js"></script>

<script src="/js/colors/index.js" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
	<h1> Colors <small>Control panel</small></h1>
	<br/>
	<ol class="breadcrumb">
		<li><a href="<?php echo e(route('admins_index')); ?>"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="<?php echo e(route('admins_settings')); ?>"> Settings</a></li>
		<li class="active">Colors</li>
	</ol>
	<div class="row clearfix">
		<!-- Inventory Group -->
		<a href="#" class="col-lg-6 col-md-6 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-primary" style="padding-bottom:10px" data-toggle="modal" data-target="#add">
				<div class="inner">
					<h4>Add Color</h4>
					<p>Add new color</p>
				</div>
		        <div class="icon">
		          <i class="ion-ios-plus-outline"></i>
		        </div>

			</div>
		</a><!-- ./col -->
		<!-- Inventory Group Edit -->
		<a id="inventory-edit" href="#" class="col-lg-6 col-md-6 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow" style="padding-bottom:10px" data-toggle="modal" data-target="#edit">
				<div class="inner">
					<h4>Edit Color</h4>
					<p>Edit existing color</p>
				</div>
		        <div class="icon">
		          <i class="ion-edit"></i>
		        </div>

			</div>
		</a><!-- ./col -->


	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo Form::open(['action' => 'ColorsController@postOrder','id'=>'color-form', 'class'=>'form-horizontal','role'=>"form"]); ?>

<?php echo csrf_field(); ?>


<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Color Selection</h3>
	</div>
	<div class="box-body clearfix">
		<ul id="colorsUl" class="no-padding" style="list-style:none;">
		<?php if(isset($colors)): ?>
			<?php $idx = 0; ?>
			<?php foreach($colors as $color): ?>
				<?php $idx++; ?>
			<li id="color-<?php echo e($color->id); ?>" class=" col-lg-1 col-md-1 col-xs-1" style="cursor:pointer;">
				<!-- small box -->
				<a href="#" class="small-box "style="background-color:<?php echo e($color->color); ?>;">
					<?php echo e(Form::hidden('id',$color->id,['class'=>'colorsId'])); ?>

					<?php echo e(Form::hidden('color',$color->color,['class'=>'colorsColor'])); ?>

					<?php echo e(Form::hidden('name', $color->name,['class'=>'colorsName'])); ?>

					<?php echo e(Form::hidden('color['.$color->id.'][order]',$idx)); ?>

				</a>
			</li>
			<?php endforeach; ?>
		<?php endif; ?>
		</ul>
	</div>
</div>
<?php echo Form::close(); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
<?php echo View::make('partials.colors.add') 
	->render(); ?>

<?php echo View::make('partials.colors.edit') 
	->render(); ?>

<?php echo View::make('partials.colors.delete') 
	->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>