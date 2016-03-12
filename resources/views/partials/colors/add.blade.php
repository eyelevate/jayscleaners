{!! Form::open(['action' => 'ColorsController@postAdd', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
<div id="add" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Color</h4>
			</div>
			<div class="modal-body">
                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Color</label>

                    <div class="col-md-6">
                        {!! Form::color('color', old('color'), ['id'=>'colorInput', 'placeholder'=>'']) !!}
                        @if ($errors->has('color'))
                            <span class="help-block">
                                <strong>{{ $errors->first('color') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>	
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Name <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> 			        
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Add Color"/>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}