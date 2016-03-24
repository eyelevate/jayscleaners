<div id="priceCalculator" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Price Calculator</h4>
			</div>
			<div class="modal-body">
				<div class="priceCalculatorDiv" style="padding-left:25px; padding-right:25px;">
					<div class="row">
						<input id="priceCalculatorInput" class="numeric col-lg-12 col-md-12 col-sm-12 col-xs-12" type="text" value="0.00" first="0.00" style="font-size:30px; text-align:center; border:3px solid #5e5e5e;" status="2"/>
						
					</div>
					<div class="row">
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="7">7</button>
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="8">8</button>
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="9">9</button>
						<button type="button" class="priceNum btn btn-warning btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="+">+</button>
					</div>
					<div class="row">
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="4">4</button>
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="5">5</button>
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="6">6</button>
						<button type="button" class="priceNum btn btn-warning btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="-">-</button>
					</div>
					<div class="row">
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="1">1</button>
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="2">2</button>
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="3">3</button>
						<button type="button" class="priceNum btn btn-warning btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="*">*</button>
					</div>
					<div class="row">
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="0">0</button>
						<button type="button" class="priceNum btn btn-info btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="00">00</button>
						<button type="button" class="priceNum btn btn-danger btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="C">C</button>
						<button type="button" class="priceNum btn btn-warning btn-lg col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="/">/</button>
					</div>	
					<div class="row">

						<button type="button" class="priceNum btn btn-lg col-lg-9 col-md-9 col-sm-9 col-xs-9" style="font-size:30px" num="=">=</button>
						<button type="button" class="priceNum btn btn-lg btn-danger col-lg-3 col-md-3 col-sm-3 col-xs-3" style="font-size:30px" num="B">Back</button>
					</div>
				</div>
			
			</div>
			<div class="modal-footer">
				<button id="cancelPrice" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="updatePrice" type="button" class="btn btn-success" data-dismiss="modal">Update Price</button>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->