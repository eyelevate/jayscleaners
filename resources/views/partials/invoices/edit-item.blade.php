<div id="item-update" class="modal fade" tabindex="-1" role="dialog">

	<div class="row">
		<div class="box box-primary col-lg-12 col-md-12 col-sm-12" style="margin-bottom:0px; border-radius:0px;">
			<div class="box-body">	
				<ul class=" no-padding" >
					<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none; height:65px;"><button type="button" id="number-c" class="number-clear btn btn-primary" style="font-size:30px; height:60px; width:100%">C</button></li>
					@for($i=1;$i<=9;$i++)
					<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none; height:65px;"><button type="button" id="number-{{ $i }}" class="number btn btn-primary" style="font-size:30px; height:60px; width:100%">{{ $i }}</button></li>
					@endfor
					<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none; height:65px;"><button type="button" id="number-0" class="number btn btn-primary" style="font-size:30px; height:60px; width:100%">0</button></li>
					<li class="col-md-1 col-lg-1 col-sm-2 col-xs-4" style="list-style:none; height:65px;"><button type="button" id="actual_number" class="btn btn-default" style="font-size:30px; height:60px; width:100%"/>--</button></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title"> <span id="invoiceModal-item_name"></span> <small>[<span id="invoiceModal-item_qty">0</span>/<span id="invoiceModal-total_qty">0</span>]</small></h2>
			</div>
			<div class="modal-body" style="padding-top:0px;">
				<div class="row">
					<div  class="col-md-8">
						<h3>Color Selection</h3>
						<ul id="colorsUl" class="no-padding clearfix" style="list-style:none;">
						@if(isset($colors))
							<?php $idx = 0; ?>
							@foreach($colors as $color)
								<?php $idx++; ?>
								<li id="color-{{ $color->id }}" class=" col-lg-3 col-md-4 col-xs-2 clearfix" style="cursor:pointer;  height:75px;">
									<!-- small box -->
									<button href="#" class="btn btn-sm colorBtn" style="background-color:{{ $color->color }}; min-width:60px; height:60px; border:3px solid #e5e5e5;">
										
										{{ Form::hidden('id',$color->id,['class'=>'colorsId']) }}
										{{ Form::hidden('color',$color->color,['class'=>'colorsColor']) }}
										{{ Form::hidden('name', $color->name,['class'=>'colorsName']) }}
										{{ Form::hidden('color['.$color->id.'][order]',$idx)}}
									</button>
								</li>
							@endforeach
						@endif
						</ul>
					</div>
					<div class="col-md-4">
						<h3>Selected</h3>
						<ul class="no-padding clearfix" style="list-style:none;">
							<li class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
								<div class="alert alert-default alert-dismissible colorRow" role="alert" color="" quantity="">
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								  <span class="badge" style="font-size:18px">4</span> <strong style="font-size:15px;">Black</strong>
								</div>
							</li>
						</ul>
					</div>
				</div>

			</div>
			<div class="modal-footer clearfix">

				<button type="button" class="btn btn-lg btn-info pull-left">Add Memo</button>
				<button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-lg btn-primary" data-dismiss="modal">Update</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
