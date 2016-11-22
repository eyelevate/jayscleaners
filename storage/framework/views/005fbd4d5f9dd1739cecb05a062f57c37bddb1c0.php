
<?php echo Form::open(['action' => 'SchedulesController@postEmailStatus','role'=>"form",'class'=>'pull-right']); ?>

<?php echo Form::hidden('id',$schedule_id); ?>	
<div id="email-<?php echo e($schedule_id); ?>" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Send Email to Customer</strong></h4>
			</div>
			<div class="modal-body clearfix">
                <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?> clearfix">
                    <label class="col-md-12 control-label padding-top-none">Add Comment (<small>optional</small>)</label>

                    <div class="col-md-12 clearfix">
                        
                        <?php echo e(Form::textarea('content',old('content'),['class'=>'form-control'])); ?>

                        <?php if($errors->has('content')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('content')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
			</div>

			<div class="modal-footer clearfix">
				<button type="button" class="btn pull-left" data-dismiss="modal">Close</button>

				<input type="submit" class="btn btn-primary" value="Send" />
				
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	
</div><!-- /.modal -->
<?php echo Form::close(); ?>