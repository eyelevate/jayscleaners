
<div id="item-add" class="modal fade" tabindex="-1" role="dialog">
{!! Form::open(['action' => 'InventoryItemsController@postAdd', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Inventory Item</h4>
			</div>
			<div class="modal-body">
                <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Location <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::select('company_id',$companies , Auth::user()->company_id, ['class'=>'form-control']) !!}
                        @if ($errors->has('company_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('company_id') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>	
                <div class="form-group{{ $errors->has('inventory_id') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Inventory Group <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::select('inventory_id',$group_select , '', ['class'=>'form-control']) !!}
                        @if ($errors->has('inventory_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('inventory_id') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>		
                <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Quantity <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::select('quantity',$quantity_select , '1', ['class'=>'form-control']) !!}
                        @if ($errors->has('quantity'))
                            <span class="help-block">
                                <strong>{{ $errors->first('quantity') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  
                <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Tags <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::select('tags',$tags_select , '1', ['class'=>'form-control']) !!}
                        @if ($errors->has('tags'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tags') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> 	

                <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Price <span class="text text-danger">*</span></label>

                    <div class="col-md-6">
                        {!! Form::text('price', old('price'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('price'))
                            <span class="help-block">
                                <strong>{{ $errors->first('price') }}</strong>
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
                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Description</label>

                    <div class="col-md-6">
                        {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'placeholder'=>'']) !!}
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Image</label>

                    <div class="col-md-6">
					    <select id="image_select" name="image">
					    @if(isset($icon_select))
					    	@foreach($icon_select as $key => $value)
					        <option value="{{ $key }}" data-imagesrc="{{ $key }}"
					            data-description="{{ $key }}">{{ $value }}</option>
					    	@endforeach
					    @endif
					    </select>	
                        @if ($errors->has('image'))
                            <span class="help-block">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div id="imageSelected" class="hide">
                    	{{ Form::hidden('image','',['id'=>'image_selected']) }}
                	</div>
                </div>	

			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Add Item"/>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
{!! Form::close() !!}
</div><!-- /.modal -->
