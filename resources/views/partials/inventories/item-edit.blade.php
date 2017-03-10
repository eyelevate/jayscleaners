
<div id="item_edit-{{ $item->id }}" class="modal fade" tabindex="-1" role="dialog">
{!! Form::open(['action' => 'InventoryItemsController@postEdit', 'class'=>'form-horizontal','role'=>"form"]) !!}
{!! csrf_field() !!}
{{ Form::hidden('id',$item->id,['class'=>'itemEdit-id']) }}
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
                        {!! Form::select('company_id',$companies , $item->company_id, ['class'=>'form-control']) !!}
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
                        {!! Form::select('inventory_id',$group_select , $item->inventory_id, ['class'=>'form-control','id'=>'itemEdit-inventory_id']) !!}
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
                        {!! Form::select('quantity',$quantity_select , $item->quantity, ['class'=>'form-control']) !!}
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
                        {!! Form::select('tags',$tags_select ,$item->tags, ['class'=>'form-control','id'=>'itemEdit-tags']) !!}
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
                        {!! Form::text('price', $item->price, ['class'=>'form-control', 'placeholder'=>'','id'=>'itemEdit-price']) !!}
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
                        {!! Form::text('name', $item->name, ['class'=>'form-control', 'placeholder'=>'','id'=>'itemEdit-name']) !!}
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
                        {!! Form::textarea('description', $item->description, ['class'=>'form-control', 'placeholder'=>'','id'=>'itemEdit-description']) !!}
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Image</label>

                    <div class="col-md-6">
                        {{ Form::select('image',$icon_select,$item->image,['class'=>'form-control'])}}
                    </div>

                </div>	

			
			</div>
			<div class="modal-footer">
                <a class="btn btn-danger" href="#" data-toggle="modal" data-target="#item-delete">Delete</a>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Update Item"/>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
{!! Form::close() !!}
</div><!-- /.modal -->