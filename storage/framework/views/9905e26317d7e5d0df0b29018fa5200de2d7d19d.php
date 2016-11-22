<section class="wrapper style3 container special">
	<div id="store_hours" class="row">
		<header class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
			<span class="icon featured fa-clock-o"></span>
			<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Store Hours</h3>
		</header>
		<section class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="table-responsive">
				<table class="table table-condensed">	
					<thead>
						<tr>
							<th style="text-align:right;"><strong>Day</strong></th>
							<th style="text-align:center;"><strong>Hours</strong></th>
							<th style="text-align:left;"><strong>Currently</strong></th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($companies) > 0): ?>
						<?php foreach($companies as $company): ?>
							<?php if(count($company->store_hours) > 0 && $company->id == 1): ?>
								<?php foreach($company->store_hours as $key => $value): ?>
									<?php if(date('l') == $key): ?>
									<tr class="warning" style="color:#5e5e5e; font-weight:bold;">
										<th style="text-align:right;"><strong><?php echo e($key); ?></strong></th>
										<td style="text-align:center;"><strong><?php echo e($value); ?></strong></td>
										<td style="text-align:left;"><strong style="color:<?php echo e($company['open_status'] ? 'green' : 'red'); ?>;"><?php echo e($company['open_status'] ? 'Open' : 'Closed'); ?></strong></td>
									</tr>
									<?php else: ?>
									<tr>
										<th style="text-align:right;"><?php echo e($key); ?></th>
										<td style="text-align:center;"><?php echo e($value); ?></td>
										<td style="text-align:left;"></td>
									</tr>
									<?php endif; ?>
								
								<?php endforeach; ?>
							<?php endif; ?>
		
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</section>


	</div>


</section>