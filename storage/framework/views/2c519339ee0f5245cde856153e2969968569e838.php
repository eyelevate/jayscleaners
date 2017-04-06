<div id="color" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title"> <span id="invoiceModal-item_name"></span> <small>[<span id="invoiceModal-item_qty">0</span>/<span id="invoiceModal-total_qty">0</span>]</small></h2>
			</div>
			<div class="modal-body" style="padding-top:0px;">
				<div class="row">
					<div  class="col-md-7">
						<h3>Color Selection</h3>
						<ul id="colorsUl" class="no-padding clearfix" style="list-style:none;">
						<?php if(isset($colors)): ?>
							<?php $idx = 0; ?>
							<?php foreach($colors as $color): ?>
								<?php $idx++; ?>
								<li id="color-<?php echo e($color->id); ?>" class=" col-lg-3 col-md-3 col-sm-3 col-xs-4 clearfix" style="cursor:pointer;  height:75px;">
									<!-- small box -->
									<button href="#" class="btn btn-sm colorBtn" style="background-color:<?php echo e($color->color); ?>; min-width:60px; height:60px; border:3px solid #e5e5e5;">
										
										<?php echo e(Form::hidden('id',$color->id,['class'=>'colorsId'])); ?>

										<?php echo e(Form::hidden('color',$color->color,['class'=>'colorsColor'])); ?>

										<?php echo e(Form::hidden('name', $color->name,['class'=>'colorsName'])); ?>

										<?php echo e(Form::hidden('color['.$color->id.'][order]',$idx)); ?>

									</button>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
						</ul>
					</div>
					<div class="col-md-5">
						<h3>Selected</h3>
						<table id="selectedColorsTable" class="no-padding clearfix table table-striped" style="list-style:none;">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Color</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>

			</div>
			<div class="modal-footer clearfix">
				<button id="colorCancel" type="button" class="btn btn-lg btn-default" data-dismiss="modal">Cancel</button>
				<button id="colorUpdate" type="button" class="btn btn-lg btn-primary" data-dismiss="modal">Update</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	<div id="colorsFormData" class="hide">
		<?php echo e(Form::hidden('total',null,['id'=>'colorForm-total'])); ?>

		<?php echo e(Form::hidden('qty',null,['id'=>'colorForm-qty'])); ?>

		<?php echo e(Form::hidden('id', null,['id'=>'colorForm-id'])); ?>	
	</div>
</div><!-- /.modal -->
