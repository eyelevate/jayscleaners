<div id="cash" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Cash Payment</h4>
			</div>
			<div class="modal-body clearfix">
	            <div class="form-group<?php echo e($errors->has('amount_due') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Amount Due <span style="color:#ff0000">*</span></label>  
                    <?php echo e(Form::text('amount_due','0.00',['id'=>'amount_due_cash','class'=>'amount_due form-control','readonly'=>'true','style'=>'font-size:20px;'])); ?>

                </div>	
                <div id="calculator" class="form-group" >
                	<label class="control-label padding-top-none">Amount Tendered <span style="color:#ff0000">*</span></label>
                	<p id="amount_tendered_display" style="text-align:center; form-control; border:1px solid #5e5e5e; font-size:20px;">$0.00</p>
                	<input id="amount_tendered" type="hidden" value="0"/>
                	<input id="amount_tendered_exact" type="hidden" value="" />

                	<div class="calc_buttons">
                		<div>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="7" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">7</button>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="8" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">8</button>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="9" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">9</button>
                		</div>
                		<div>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="4" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">4</button>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="5" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">5</button>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="6" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">6</button>
                		</div>
                		<div>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="1" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">1</button>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="2" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">2</button>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="3" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">3</button>
                		</div>
                		<div>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="0" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">0</button>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="00" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">00</button>
	                		<button class="btn btn-lg col-xs-4 col-sm-4 col-md-4 col-lg-4 calc_button" type="button" value="C" style="background-color:#e5e5e5; border:1px solid #5e5e5e;">C</button>
                		</div>
                	</div>
               	</div>	
	            <div class="form-group<?php echo e($errors->has('change') ? ' has-error' : ''); ?>">
                    <label class="control-label padding-top-none">Change <span style="color:#ff0000">*</span></label>  
                    <?php echo e(Form::text('change','0.00',['id'=>'change','class'=>'form-control','readonly'=>'true','style'=>'font-size:20px;'])); ?>

                </div>	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button id="finish-cash" type="button" class="btn btn-success btn-lg finish_button" >Finish</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->