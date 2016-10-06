<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11,af-2.1.1,b-1.1.2,r-2.0.2/datatables.min.css"/>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script type="text/javascript" src="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11,af-2.1.1,b-1.1.2,r-2.0.2/datatables.min.js"></script>
<script type="text/javascript" src="/js/admins/overview.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
	<h1> Companies Home <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo e(route('admins_index')); ?>"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="<?php echo e(route('admins_settings')); ?>"> Settings</a></li>
		<li class="active">Company</li>
	</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Companies</h3>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table id="admin_search" class="table table-striped table-bordered" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		            	<th>Id</th>
		            	<th>Name</th>
		                <th>Phone</th>
		                <th>Email</th>
		                <th>Street</th>
		                <th>City</th>
		                <th>Zip</th>
		                <th>Created</th>
		                <th>Action</th>
		            </tr>
		        </thead>
		        <tfoot>
		            <tr>
		            	<th>Id</th>
		            	<th>Name</th>
		                <th>Phone</th>
		                <th>Email</th>
		                <th>Street</th>
		                <th>City</th>
		                <th>Zip</th>
		                <th>Created</th>
		                <th>Action</th>
		            </tr>
		        </tfoot>
		        <tbody>
		        	<?php if(isset($companies)): ?>
			        	<?php foreach($companies as $c): ?>
			        		<tr>
			        			<td><?php echo e($c->id); ?></td>
			        			<td><?php echo e($c->name); ?></td>
			        			<td><?php echo e($c->phone); ?></td>
			        			<td><?php echo e($c->email); ?></td>
			        			<td><?php echo e($c->street); ?></td>
			        			<td><?php echo e($c->city); ?></td>
			        			<td><?php echo e($c->zip); ?></td>
			        			<td><?php echo e($c->created_at); ?></td>
			        			<td>
			        				<a class="btn btn-info" href="<?php echo e(route('companies_edit',$c->id)); ?>">edit</a> 

			        			</td>
			        		</tr>
			        	<?php endforeach; ?>
		        	<?php endif; ?>
		        </tbody>
		    </table>
		</div>
	</div>
	<div class="panel-footer clearfix">
		<a class="btn btn-primary pull-right" href="<?php echo e(route('companies_add')); ?>">New Company</a>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>