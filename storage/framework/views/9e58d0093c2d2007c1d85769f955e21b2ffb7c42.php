<?php echo Form::open(['action' => 'InventoriesController@postEdit', 'class'=>'form-horizontal','role'=>"form"]); ?>

	<?php echo csrf_field(); ?>

	<?php echo e(Form::hidden('id',null,['class'=>'groupEdit-id'])); ?>

	<div id="group-edit" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Edit Inventory Group</h4>
				</div>
				<div class="modal-body">
	                <div class="form-group<?php echo e($errors->has('company_id') ? ' has-error' : ''); ?>">
	                    <label class="col-md-4 control-label">Location <span class="text text-danger">*</span></label>

	                    <div class="col-md-6">
	                        <?php echo Form::select('company_id',$companies , '', ['id'=>'groupEdit-company_id','class'=>'form-control']); ?>

	                        <?php if($errors->has('company_id')): ?>
	                            <span class="help-block">
	                                <strong><?php echo e($errors->first('company_id')); ?></strong>
	                            </span>
	                        <?php endif; ?>
	                    </div>
	                </div>		
	                <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
	                    <label class="col-md-4 control-label">Name <span class="text text-danger">*</span></label>

	                    <div class="col-md-6">
	                        <?php echo Form::text('name', old('name'), ['id'=>'groupEdit-name','class'=>'form-control', 'placeholder'=>'']); ?>

	                        <?php if($errors->has('name')): ?>
	                            <span class="help-block">
	                                <strong><?php echo e($errors->first('name')); ?></strong>
	                            </span>
	                        <?php endif; ?>
	                    </div>
	                </div> 			        
	                <div class="form-group<?php echo e($errors->has('description') ? ' has-error' : ''); ?>">
	                    <label class="col-md-4 control-label">Description</label>

	                    <div class="col-md-6">
	                        <?php echo Form::textarea('description', old('description'), ['id'=>'groupEdit-description','class'=>'form-control', 'placeholder'=>'']); ?>

	                        <?php if($errors->has('description')): ?>
	                            <span class="help-block">
	                                <strong><?php echo e($errors->first('description')); ?></strong>
	                            </span>
	                        <?php endif; ?>
	                    </div>
	                </div>
				
				</div>
				<div class="modal-footer">
					<a class="btn btn-danger" href="#" data-toggle="modal" data-target="#group-delete">Delete</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<input class="btn btn-primary" type="submit" value="Edit Group"/>
				</div>
				
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php echo Form::close(); ?>