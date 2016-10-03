{!! Form::open(['action' => 'ZipcodeRequestsController@postAccept', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
{!! Form::hidden('zipcode',$zipcode) !!}
<div id="accept-{{ $zipcode }}" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Accept Zipcode</h4>
			</div>
			<div class="modal-body">
				<p>Will accept zipcode, send all requests an email letting them know that zipcode has been approved and will allow users to start using zipcode in delivery schedule. Must be followed by a delivery schedule creation.</p> 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Accept"/>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}