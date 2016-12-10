<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
	<script type="text/javascript" src="/packages/ajaxqueue/ajaxQueue.min.js"></script>
	<script type="text/javascript" src="/js/admins/reset_passwords.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
	<h1>Admins Reset Passwords Page <small>Control panel</small></h1>
	<ol class="breadcrumb">
	<li class="active">Admins</li>

	</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<br/>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Old Password List</h3>
		</div>
		<div class="panel-body">
			
		</div>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Username</th>
						<th>Last</th>
						<th>First</th>
						<th>Email</th>
						<th>Token</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if(count($users) > 0): ?>
					<?php foreach($users as $user): ?>
					<tr class="reset_tr">
						<td><?php echo e($user->id); ?></td>
						<td><?php echo e($user->username); ?></td>
						<td><?php echo e($user->last_name); ?></td>
						<td><?php echo e($user->first_name); ?></td>
						<td><?php echo e($user->email); ?></td>
						<td><?php echo e($user->token); ?></td>
						<td><?php echo e($user->status); ?></td>
						<td><input type="checkbox" name="user_id" value="<?php echo e($user->id); ?>" class="user_id"/></td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>

		</div>
		<div class="panel-body">
			<?php echo e($users->links()); ?>

		</div>
		<div class="panel-footer">
			<button class="btn btn-info" type="button" data-toggle="modal" data-target="#send-all">Reset All Users</button>
			<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#send-selected">Reset Selected Users</button>
		</div>
	</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>


	<?php echo View::make('partials.admins.send_all_reset')->render(); ?>	
	<?php echo View::make('partials.admins.send_selected_reset')->render(); ?>				
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>