{!! Form::open(['action' => 'AccountsController@postEmailSend','role'=>"form"]) !!}

<div id="send" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Send Emails?</h4>
			</div>
			<div class="modal-body">
			<p>Are you sure you want to send selected account billing email(s)?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success" data-toggle="modal" data-target="#loading">Send</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}