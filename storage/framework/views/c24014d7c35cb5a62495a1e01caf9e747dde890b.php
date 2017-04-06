<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/admins/index.js"></script>
<script type="text/javascript" src="/js/reports/index.js"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notifications'); ?>
  <?php echo View::make('partials.layouts.nav-bar')->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Monthly Pickup/Dropoff Summary</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse">
						<i class="fa fa-minus"></i>
					</button>
					<div class="btn-group">
						<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-wrench"></i>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li><a href="#">Separated link</a></li>
						</ul>
					</div>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			
			
			<!-- /.box-header -->
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<p class="text-center">
							<strong>Pickup: <?php echo e(date('d M, Y', strtotime(date('Y-01-01 00:00:00')))); ?> - <?php echo e(date('d M, Y',strtotime(date('Y-m-d H:i:s')))); ?></strong>
						</p>

						<div class="chart">
							<!-- Sales Chart Canvas -->
							<canvas id="salesChart" style="height: 350px;"></canvas>
						</div>
					<!-- /.chart-responsive -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</div>
<!-- ./box-body -->
			<div class="box-footer">
				<div class="row clearfix">
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">TODAY</p>
						<div class="description-block border-right">
							<?php if(count($summaries)): ?>
								<?php foreach($summaries as $summary): ?>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php echo e($summary['name']); ?></label>
									<h5 class="description-header">&nbsp;<?php echo e($summary['today']); ?></h5>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<!-- /.description-block -->
					</div>
					<!-- /.col -->
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">THIS WEEK</p>
						<div class="description-block border-right">
							
							<?php if(count($summaries)): ?>
								<?php foreach($summaries as $summary): ?>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php echo e($summary['name']); ?></label>
									<h5 class="description-header">&nbsp;<?php echo e($summary['this_week']); ?></h5>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
							
						</div>
					<!-- /.description-block -->
					</div>
					<!-- /.col -->
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">THIS MONTH</p>
						<div class="description-block border-right">
							
							<?php if(count($summaries)): ?>
								<?php foreach($summaries as $summary): ?>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php echo e($summary['name']); ?></label>
									<h5 class="description-header">&nbsp;<?php echo e($summary['this_month']); ?></h5>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
							
						</div>
						<!-- /.description-block -->
					</div>
					<!-- /.col -->
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">THIS YEAR</p>
						<div class="description-block">
						
							<?php if(count($summaries)): ?>
								<?php foreach($summaries as $summary): ?>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php echo e($summary['name']); ?></label>
									<h5 class="description-header">&nbsp;<?php echo e($summary['this_year']); ?></h5>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
							
						</div>
						<!-- /.description-block -->
					</div>
				</div>
				<!-- /.row -->
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<p class="text-center">
							<strong>Dropoff: <?php echo e(date('d M, Y', strtotime(date('Y-01-01 00:00:00')))); ?> - <?php echo e(date('d M, Y',strtotime(date('Y-m-d H:i:s')))); ?></strong>
						</p>

						<div class="chart">
							<!-- Sales Chart Canvas -->
							<canvas id="dropoffChart" style="height: 350px;"></canvas>
						</div>
					<!-- /.chart-responsive -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</div>
			<!-- ./box-body -->
			<div class="box-footer">
				<div class="row clearfix">
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">TODAY</p>
						<div class="description-block border-right">
							<?php if(count($drops)): ?>
								<?php foreach($drops as $drop): ?>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php echo e($drop['name']); ?></label>
									<h5 class="description-header">&nbsp;<?php echo e($drop['today']); ?></h5>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<!-- /.description-block -->
					</div>
					<!-- /.col -->
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">THIS WEEK</p>
						<div class="description-block border-right">
							
							<?php if(count($drops)): ?>
								<?php foreach($drops as $drop): ?>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php echo e($drop['name']); ?></label>
									<h5 class="description-header">&nbsp;<?php echo e($drop['this_week']); ?></h5>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
							
						</div>
					<!-- /.description-block -->
					</div>
					<!-- /.col -->
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">THIS MONTH</p>
						<div class="description-block border-right">
							
							<?php if(count($drops)): ?>
								<?php foreach($drops as $drop): ?>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php echo e($drop['name']); ?></label>
									<h5 class="description-header">&nbsp;<?php echo e($drop['this_month']); ?></h5>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
							
						</div>
						<!-- /.description-block -->
					</div>
					<!-- /.col -->
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">THIS YEAR</p>
						<div class="description-block">
						
							<?php if(count($drops)): ?>
								<?php foreach($drops as $drop): ?>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php echo e($drop['name']); ?></label>
									<h5 class="description-header">&nbsp;<?php echo e($drop['this_year']); ?></h5>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
							
						</div>
						<!-- /.description-block -->
					</div>
				</div>
				<!-- /.row -->
			</div> 
		<!-- /.box-footer -->
			<div class="box-footer">

				<?php echo Form::open(['action' => 'ReportsController@postIndex','role'=>"form",'id'=>'start_form']); ?>

		        
		        <div class="form-group<?php echo e($errors->has('company_id') ? ' has-error' : ''); ?>">
		            <label class="control-label">Company</label>

	            	<?php echo e(Form::select('company_id',$companies,Auth::user()->company_id,['class'=>'form-control'])); ?>


	                <?php if($errors->has('start')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('start')); ?></strong>
	                    </span>
	                <?php endif; ?>
		        </div>
		        <div class="form-group<?php echo e($errors->has('start') ? ' has-error' : ''); ?>">
		            <label class="control-label">Start</label>
		            <div id="start_container">
	                	<input id="start" type="text" class="form-control" name="start" value="<?php echo e(old('start')); ?>" readonly="true" style="background-color:#ffffff">
	            	</div>
	                <?php if($errors->has('start')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('start')); ?></strong>
	                    </span>
	                <?php endif; ?>
		        </div>
		        <div class="form-group<?php echo e($errors->has('end') ? ' has-error' : ''); ?>">
		            <label class="control-label">End</label>
		            <div id="end_container">
	                	<input id="end" type="text" class="form-control" name="end" value="<?php echo e(old('end')); ?>" readonly="true" style="background-color:#ffffff">
	            	</div>
	                <?php if($errors->has('end')): ?>
	                    <span class="help-block">
	                        <strong><?php echo e($errors->first('end')); ?></strong>
	                    </span>
	                <?php endif; ?>

		        </div>
		        <div class="form-group">
		        	<button type="submit" class="btn btn-lg btn-primary col-xs-12 col-md-12 cold-lg-12 col-sm-12">Search By Dates</button>
		        </div>
		        <div class="form-group">
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="<?php echo e($dates['today']['start']); ?>" end="<?php echo e($dates['today']['end']); ?>">
						<p class="description-text" style="text-align:center;">TODAY</p>
						<div class="description-block">
							<label><?php echo e($dates['today']['start']); ?> - <?php echo e($dates['today']['end']); ?><label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="<?php echo e($dates['this_week']['start']); ?>" end="<?php echo e($dates['this_week']['end']); ?>">
						<p class="description-text" style="text-align:center;">THIS WEEK</p>
						<div class="description-block">
							<label><?php echo e($dates['this_week']['start']); ?> - <?php echo e($dates['this_week']['end']); ?><label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="<?php echo e($dates['this_month']['start']); ?>" end="<?php echo e($dates['this_month']['end']); ?>">
						<p class="description-text" style="text-align:center;">THIS MONTH</p>
						<div class="description-block">
							<label><?php echo e($dates['this_month']['start']); ?> - <?php echo e($dates['this_month']['end']); ?><label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="<?php echo e($dates['this_year']['start']); ?>" end="<?php echo e($dates['this_year']['end']); ?>">
						<p class="description-text" style="text-align:center;">THIS YEAR</p>
						<div class="description-block">
							<label><?php echo e($dates['this_year']['start']); ?> - <?php echo e($dates['this_year']['end']); ?><label>
						</div>
					</button>

					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="<?php echo e($dates['yesterday']['start']); ?>" end="<?php echo e($dates['yesterday']['end']); ?>">
						<p class="description-text" style="text-align:center;">YESTERDAY</p>
						<div class="description-block">
							<label><?php echo e($dates['yesterday']['start']); ?> - <?php echo e($dates['yesterday']['end']); ?><label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="<?php echo e($dates['last_week']['start']); ?>" end="<?php echo e($dates['last_week']['end']); ?>">
						<p class="description-text" style="text-align:center;">LAST WEEK</p>
						<div class="description-block">
							<label><?php echo e($dates['last_week']['start']); ?> - <?php echo e($dates['last_week']['end']); ?><label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="<?php echo e($dates['last_month']['start']); ?>" end="<?php echo e($dates['last_month']['end']); ?>">
						<p class="description-text" style="text-align:center;">LAST MONTH</p>
						<div class="description-block">
							<label><?php echo e($dates['last_month']['start']); ?> - <?php echo e($dates['last_month']['end']); ?><label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="<?php echo e($dates['last_year']['start']); ?>" end="<?php echo e($dates['last_year']['end']); ?>">
						<p class="description-text" style="text-align:center;">LAST YEAR</p>
						<div class="description-block">
							<label><?php echo e($dates['last_year']['start']); ?> - <?php echo e($dates['last_year']['end']); ?><label>
						</div>
					</button>
		        </div>
		        <?php echo Form::close(); ?>

			</div>
		</div>
	<!-- /.box -->
	</div>
	<!-- /.col -->
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>