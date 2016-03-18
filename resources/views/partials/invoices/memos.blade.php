<div id="memos" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Memo</h4>
			</div>
			<div class="modal-body clearfix">
				<ul id="memosUl" class="no-padding" style="list-style:none;">
				@if(isset($memos))
					<?php $idx = 0; ?>
					@foreach($memos as $memo)
					<?php $idx++; ?>
					<li id="memo-{{ $memo->id }}" class="memoLi alert alert-default col-lg-3 col-md-3 col-sm-4 col-xs-6" style="cursor:pointer; text-align:center; height:75px; {{ (strlen($memo->memo) < 20) ?  'padding:0px; vertical-align:middle; line-height:75px;' : null }}">
						{{ $memo->memo }}
						<!-- small box -->
						<div class="hide">
							{{ Form::hidden('id',$memo->id,['class'=>'memosId']) }}
							{{ Form::hidden('memo',$memo->memo,['class'=>'memosMemo']) }}
							{{ Form::hidden('memos['.$memo->id.'][order]',$idx,['class'=>'memosOrdered'])}}
						</div>
					</li>
					@endforeach
				@endif
				</ul>
	            <div class="form-group{{ $errors->has('memo') ? ' has-error' : '' }}">
                    <label class="col-lg-12 col-md-12 col-sm-12 control-label">Memo <span class="text text-danger">*</span></label>

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        {!! Form::text('memo', null, ['id'=>'memo','class'=>'form-control', 'placeholder'=>'Add memo here']) !!}
                    </div>
                </div> 		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button id="memoAdd" type="button" class="btn btn-success btn-lg">Add Memo</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->