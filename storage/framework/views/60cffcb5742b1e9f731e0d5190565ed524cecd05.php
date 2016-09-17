<div id="memo" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Memo</h4>
			</div>
			<div class="modal-body clearfix">
				<ul id="memosUl" item-id="" item-idx="" class="no-padding" style="list-style:none;">
				<?php if(isset($memos)): ?>
					<?php $idx = 0; ?>
					<?php foreach($memos as $memo): ?>
					<?php $idx++; ?>
					<li id="memo-<?php echo e($memo->id); ?>" class="memoLi alert alert-default col-lg-3 col-md-3 col-sm-4 col-xs-6" style="cursor:pointer; text-align:center; height:75px; <?php echo e((strlen($memo->memo) < 20) ?  'padding:0px; vertical-align:middle; line-height:75px;' : null); ?>">
						<?php echo e($memo->memo); ?>

						<!-- small box -->
						<div class="hide">
							<?php echo e(Form::hidden('id',$memo->id,['class'=>'memosId'])); ?>

							<?php echo e(Form::hidden('memo',$memo->memo,['class'=>'memosMemo'])); ?>

							<?php echo e(Form::hidden('memos['.$memo->id.'][order]',$idx,['class'=>'memosOrdered'])); ?>

						</div>
					</li>
					<?php endforeach; ?>
				<?php endif; ?>
				</ul>
	            <div class="form-group<?php echo e($errors->has('memo') ? ' has-error' : ''); ?>">
                    <label class="col-lg-12 col-md-12 col-sm-12 control-label">Memo <span class="text text-danger">*</span></label>

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <?php echo Form::text('memo', null, ['id'=>'memoInput','class'=>'form-control', 'placeholder'=>'Add memo here']); ?>

                    </div>
                </div> 		
			</div>
			<div class="modal-footer">
				<button id="clearMemo" class="btn btn-danger pull-left" type="button" >Clear Memo</button>
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button id="memoAdd" type="button" class="btn btn-success btn-lg" data-dismiss="modal">Update Memo</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->