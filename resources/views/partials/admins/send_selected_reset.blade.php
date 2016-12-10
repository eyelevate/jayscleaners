<div id="send-selected" class="modal fade" tabindex="-1" role="dialog" style="z-index:9999 !important; margin-top:10px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Reset User Passwords - Selected Users</h4>
			</div>
			<div class="modal-body">
				<p class="sending hide" style="text-align:center;">
					<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate glpyh-large" style="font-size: 2.2em;"></span>
				</p>
				<p id="step-0" class="steps"> Click Send to start sending reset emails to selected users </p>
				
				<p id="step-1" class="steps hide">(step 1) Preparing selected users</p>
				<p id='step-2' class="steps hide">(step 2) Collected <strong class="totalSelected">0</strong> users</p>
				<p id='step-3' class="steps hide">(step 3) Sending reset password email <strong>1</strong> of <strong class="totalSelected">1</strong></p>
				<p id="step-4" class="steps hide">(step 4) Process complete.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="send_selected" class="btn btn-primary" type="button">Send</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->