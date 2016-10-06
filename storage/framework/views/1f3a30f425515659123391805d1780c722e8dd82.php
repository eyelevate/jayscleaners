
<div id="item-edit" class="modal fade" tabindex="-1" role="dialog">
<?php echo Form::open(['action' => 'InventoryItemsController@postEdit', 'class'=>'form-horizontal','role'=>"form"]); ?>

<?php echo csrf_field(); ?>

<?php echo e(Form::hidden('id',NULL,['class'=>'itemEdit-id'])); ?>

	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Inventory Item</h4>
			</div>
			<div class="modal-body">
                <div class="form-group<?php echo e($errors->has('company_id') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Location <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::select('company_id',$companies , '', ['class'=>'form-control']); ?>

                        <?php if($errors->has('company_id')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('company_id')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>	
                <div class="form-group<?php echo e($errors->has('inventory_id') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Inventory Group <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::select('inventory_id',$group_select , '', ['class'=>'form-control','id'=>'itemEdit-inventory_id']); ?>

                        <?php if($errors->has('inventory_id')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('inventory_id')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>	
                <div class="form-group<?php echo e($errors->has('quantity') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Quantity <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::select('quantity',$quantity_select , '1', ['class'=>'form-control']); ?>

                        <?php if($errors->has('quantity')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('quantity')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div> 	
                <div class="form-group<?php echo e($errors->has('tags') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Tags <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::select('tags',$tags_select , '1', ['class'=>'form-control','id'=>'itemEdit-tags']); ?>

                        <?php if($errors->has('tags')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('tags')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div> 	
                <div class="form-group<?php echo e($errors->has('price') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Price <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::text('price', old('price'), ['class'=>'form-control', 'placeholder'=>'','id'=>'itemEdit-price']); ?>

                        <?php if($errors->has('price')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('price')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Name <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        <?php echo Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>'','id'=>'itemEdit-name']); ?>

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
                        <?php echo Form::textarea('description', old('description'), ['class'=>'form-control', 'placeholder'=>'','id'=>'itemEdit-description']); ?>

                        <?php if($errors->has('description')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('description')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group<?php echo e($errors->has('image') ? ' has-error' : ''); ?>">
                    <label class="col-md-4 control-label">Image</label>

                    <div class="col-md-6">
					    <select id="image_select-edit" name="image">
					    <?php if(isset($icon_select)): ?>
					    	<?php foreach($icon_select as $key => $value): ?>
					        <option value="<?php echo e($key); ?>" data-imagesrc="<?php echo e($key); ?>"
					            data-description="<?php echo e($key); ?>"><?php echo e($value); ?></option>
					    	<?php endforeach; ?>
					    <?php endif; ?>
					    </select>	
                        <?php if($errors->has('image')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('image')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div id="imageSelected" class="hide">
                    	<?php echo e(Form::hidden('image','',['id'=>'itemEdit-image'])); ?>

                	</div>
                </div>	

			
			</div>
			<div class="modal-footer">
                <a class="btn btn-danger" href="#" data-toggle="modal" data-target="#item-delete">Delete</a>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Update Item"/>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
<?php echo Form::close(); ?>

</div><!-- /.modal -->