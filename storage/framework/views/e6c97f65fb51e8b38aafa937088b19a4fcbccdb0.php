<?php echo Form::open(['action' => 'InvoicesController@postManageItems','role'=>"form"]); ?>

<?php echo Form::hidden('invoice_id',$invoice_id); ?>

<div id="expand-<?php echo e($item_id); ?>" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Item Update</h4>
			</div>
			<div class="modal-body clearfix">
	
			</div>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Color</th>
                            <th>Memo</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(count($items) > 0): ?>
                        <?php foreach($items as $item): ?>
                            <tr>
                                <td><?php echo e($item['id']); ?></td>
                                <td><?php echo e($item['item']); ?></td>
                                <td><?php echo e($item['color']); ?></td>
                                <td><?php echo e($item['memo']); ?></td>
                                <td><input name="item[<?php echo e($item['id']); ?>]" class="item_value" type="text" value="<?php echo e($item['subtotal']); ?>"/></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
<!--                         <tr>
                            <td  colspan="4" style="text-align:right;">Total Subtotal </td>
                            <th><input id="subtotal-<?php echo e($item_id); ?>" class="subtotals" type="text" value="<?php echo e(money_format('%i',$subtotal)); ?>"/></th>
                        </tr> -->
                    </tfoot>
                </table>

            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success btn-lg" >Update</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo Form::close(); ?>