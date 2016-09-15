
<div id="status_change" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Status Change</strong></h4>
			</div>
			<div class="modal-body clearfix">
				<p>Are you sure you want to send a status change email to these email customers?</p>
				<ul>
				<?php if(count($schedules) > 0): ?> 
					<?php foreach($schedules as $schedule): ?>
					<li>[<?php echo e($schedule['id']); ?>] <?php echo e($schedule['first_name']); ?>, <?php echo e($schedule['first_name']); ?> => <?php echo e($schedule['email']); ?></li>
					<?php endforeach; ?>
				<?php endif; ?>
				</ul>
			</div>

			<div class="modal-footer clearfix">
				<button type="button" class="btn pull-left" data-dismiss="modal">Close</button>
				<?php echo Form::open(['action' => 'SchedulesController@postEmailEnroute','role'=>"form",'class'=>'pull-right']); ?>

				<?php echo Form::hidden('delivery_date',$delivery_date); ?>

				<input type="submit" class="btn btn-primary" value="Send Status Change Email" />
				<?php echo Form::close(); ?>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->