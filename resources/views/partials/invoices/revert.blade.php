{!! Form::open(['action' => 'InvoicesController@postRevert','role'=>"form"]) !!}
{!! Form::hidden('id',$invoice->id) !!}
<div id="revert-{{ $invoice->id }}" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Revert Invoice Status #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</h4>
			</div>
			<div class="modal-body clearfix">
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">Current Status</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	<label class="form-control">{{ $invoice->status_title }}</label>
                	</div>
                </div>
	            <div class="form-group">
                    <label class="col-md-4 col-sm-4 col-lg-4 col-xs-12 control-label padding-top-none">New Status</label>
                    <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12 ">
                    	{{ Form::select('status',$revert,$invoice->status,['class'=>'form-control']) }}
                	</div>
                </div>
			</div>
			<div class="modal-footer clearfix">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success btn-lg finish_button pull-right" >Revert</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}