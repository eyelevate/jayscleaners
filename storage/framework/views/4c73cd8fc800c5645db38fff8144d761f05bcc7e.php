<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
	<h1> Companies Edit <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo e(route('admins_index')); ?>"><i class="fa fa-dashboard"></i> Admins</a></li>
		<li><a href="<?php echo e(route('admins_settings')); ?>"> Settings</a></li>
		<li><a href="<?php echo e(route('companies_index')); ?>"> Companies</a></li>
		<li class="active">Edit</li>
	</ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- Add Company Form -->
<?php echo Form::open(['action' => 'CompaniesController@postEdit', 'class'=>'form-horizontal','role'=>"form"]); ?>

<?php echo csrf_field(); ?>

<?php echo e(Form::hidden('id',$company->id)); ?>

<div class="box box-primary">
	<div class="box-header">
		<i class="ion ion-clipboard"></i>
		<h3 class="box-title">Add A Company</h3>
		<div class="box-tools pull-right">

		</div>
	</div><!-- /.box-header -->
	<div class="box-body">
        <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
            <label class="col-md-4 control-label">Company Name <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                <?php echo Form::text('name', $company->name, ['class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('name')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('name')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>	
        <div class="form-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
            <label class="col-md-4 control-label">Phone <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                <?php echo Form::text('phone', $company->phone, ['class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('phone')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('phone')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
            <label class="col-md-4 control-label">Email <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                <?php echo Form::text('email', $company->email, ['class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('email')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('email')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>		
        <div class="form-group<?php echo e($errors->has('street') ? ' has-error' : ''); ?>">
            <label class="col-md-4 control-label">Street <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                <?php echo Form::text('street', $company->street, ['class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('street')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('phone')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>	
        <div class="form-group<?php echo e($errors->has('city') ? ' has-error' : ''); ?>">
            <label class="col-md-4 control-label">City <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                <?php echo Form::text('city', $company->city, ['class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('city')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('city')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group<?php echo e($errors->has('state') ? ' has-error' : ''); ?>">
            <label class="col-md-4 control-label">State <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                <?php echo Form::text('state', $company->state, ['class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('state')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('state')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>		
        <div class="form-group<?php echo e($errors->has('zip') ? ' has-error' : ''); ?>">
            <label class="col-md-4 control-label">Zipcode <span class="text text-danger">*</span></label>

            <div class="col-md-6">
                <?php echo Form::text('zip', $company->zip, ['class'=>'form-control', 'placeholder'=>'']); ?>

                <?php if($errors->has('zip')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('zip')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        </div>
	</div><!-- /.box-body -->
	<div class="box-footer clearfix no-border ">
		<input type="submit" value="Edit Company" class="btn btn-primary btn-large pull-right"/>
	</div>
</div><!-- /.box -->
<?php echo e(Form::close()); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>