
<div id="history" class="modal fade" tabindex="-1" role="dialog">

	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Inventory Item</h4>
			</div>
			<div class="modal-body">
                <div class="table-responsive">   
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Rate</th>
                                <th>Created</th>
                            </tr>
                        </thead>    

                        <tbody>
                        <?php if(isset($history)): ?>
                            <?php foreach($history as $h): ?>
                            <tr>
                                <td><?php echo e($h->rate); ?></td>
                                <td><?php echo e(date('n/d/Y g:ia',strtotime($h->created_at))); ?>

                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
			    </div> 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->