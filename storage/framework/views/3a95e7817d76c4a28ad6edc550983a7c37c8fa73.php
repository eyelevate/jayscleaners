<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11,af-2.1.1,b-1.1.2,r-2.0.2/datatables.min.css"/>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script type="text/javascript" src="https://cdn.datatables.net/t/bs/jq-2.2.0,dt-1.10.11,af-2.1.1,b-1.1.2,r-2.0.2/datatables.min.js"></script>
<script type="text/javascript" src="/js/admins/overview.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
	<h1> Customers <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo e(route('admins_index')); ?>"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li class="active">Customers</li>
	</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Customer Search Results</h3>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table id="admin_search" class="table table-striped table-bordered" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			            	<th>Id</th>
			            	<th>Loc.</th>
			                <th>Last</th>
			                <th>First</th>
			                <th>Username</th>
			                <th>Email</th>
			                <th>Phone</th>
			                <th>Created</th>
			                <th>Action</th>
			            </tr>
			        </thead>
			        <tfoot>
			            <tr>
			            	<th>Id</th>
			            	<th>Loc.</th>
			                <th>Last</th>
			                <th>First</th>
			                <th>Username</th>
			                <th>Email</th>
			                <th>Phone</th>
			                <th>Created</th>
			                <th>Action</th>
			            </tr>
			        </tfoot>
			        <tbody>
			        	<?php if(isset($customers)): ?>
				        	<?php foreach($customers as $cust): ?>
				        		<tr>
				        			<td><?php echo e($cust->id); ?></td>
				        			<td><?php echo e($cust->company_id); ?></td>
				        			<td><?php echo e($cust->last_name); ?></td>
				        			<td><?php echo e($cust->first_name); ?></td>
				        			<td><?php echo e($cust->username); ?></td>
				        			<td><?php echo e($cust->email); ?></td>
				        			<td><?php echo e($cust->phone); ?></td>
				        			<td><?php echo e($cust->created_on); ?></td>
				        			<td>
				        				<a class="btn btn-info" href="<?php echo e(route('customers_view',$cust->id)); ?>">View</a> 
				        			</td>
				        		</tr>
				        	<?php endforeach; ?>
			        	<?php endif; ?>
			        </tbody>
			    </table>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<a class="btn btn-primary pull-right" href="<?php echo e(route('customers_add')); ?>">New Customer</a>
		</div>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>