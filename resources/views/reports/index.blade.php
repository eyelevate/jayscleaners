@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/admins/index.js"></script>
<script type="text/javascript" src="/js/reports/index.js"></script>
@stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Monthly Recap Report</h3>

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
							<strong>Sales: {{ date('d M, Y', strtotime(date('Y-01-01 00:00:00'))) }} - {{ date('d M, Y',strtotime(date('Y-m-d H:i:s'))) }}</strong>
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
				<div class="row">
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">TODAY</p>
						<div class="description-block border-right">
							
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Jays Cleaners Roosevelt<label>
								<h5 class="description-header">&nbsp;$1,210.43</h5>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Jays Cleaners Montlake<label>
								<h5 class="description-header">&nbsp;$5,210.43</h5>
							</div>
							
						</div>
						<!-- /.description-block -->
					</div>
					<!-- /.col -->
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">THIS WEEK</p>
						<div class="description-block border-right">
							
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Jays Cleaners Roosevelt<label>
								<h5 class="description-header">&nbsp;$5,210.43</h5>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Jays Cleaners Montlake<label>
								<h5 class="description-header">&nbsp;$7,210.43</h5>
							</div>
							
						</div>
					<!-- /.description-block -->
					</div>
					<!-- /.col -->
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">THIS MONTH</p>
						<div class="description-block border-right">
							
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Jays Cleaners Roosevelt<label>
								<h5 class="description-header">&nbsp;$27,210.43</h5>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Jays Cleaners Montlake<label>
								<h5 class="description-header">&nbsp;$35,210.43</h5>
							</div>
							
						</div>
						<!-- /.description-block -->
					</div>
					<!-- /.col -->
					<div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
						<p class="description-text" style="text-align:center;">THIS YEAR</p>
						<div class="description-block">
						
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Jays Cleaners Roosevelt<label>
								<h5 class="description-header">&nbsp;$331,210.43</h5>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label>Jays Cleaners Montlake<label>
								<h5 class="description-header">&nbsp;$350,210.43</h5>
							</div>
							
						</div>
						<!-- /.description-block -->
					</div>
				</div>
				<!-- /.row -->
			</div>
		<!-- /.box-footer -->
			<div class="box-footer">

				{!! Form::open(['action' => 'ReportsController@postIndex','role'=>"form",'id'=>'start_form']) !!}
		        
		        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
		            <label class="control-label">Company</label>

	            	{{ Form::select('company_id',$companies,Auth::user()->company_id,['class'=>'form-control']) }}

	                @if ($errors->has('start'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('start') }}</strong>
	                    </span>
	                @endif
		        </div>
		        <div class="form-group{{ $errors->has('start') ? ' has-error' : '' }}">
		            <label class="control-label">Start</label>
		            <div id="start_container">
	                	<input id="start" type="text" class="form-control" name="start" value="{{ old('start') }}" readonly="true" style="background-color:#ffffff">
	            	</div>
	                @if ($errors->has('start'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('start') }}</strong>
	                    </span>
	                @endif
		        </div>
		        <div class="form-group{{ $errors->has('end') ? ' has-error' : '' }}">
		            <label class="control-label">End</label>
		            <div id="end_container">
	                	<input id="end" type="text" class="form-control" name="end" value="{{ old('end') }}" readonly="true" style="background-color:#ffffff">
	            	</div>
	                @if ($errors->has('end'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('end') }}</strong>
	                    </span>
	                @endif

		        </div>
		        <div class="form-group">
		        	<button type="submit" class="btn btn-lg btn-primary col-xs-12 col-md-12 cold-lg-12 col-sm-12">Search By Dates</button>
		        </div>
		        <div class="form-group">
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="{{ $dates['today']['start'] }}" end="{{ $dates['today']['end'] }}">
						<p class="description-text" style="text-align:center;">TODAY</p>
						<div class="description-block">
							<label>{{ $dates['today']['start'] }} - {{ $dates['today']['end'] }}<label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="{{ $dates['this_week']['start'] }}" end="{{ $dates['this_week']['end'] }}">
						<p class="description-text" style="text-align:center;">THIS WEEK</p>
						<div class="description-block">
							<label>{{ $dates['this_week']['start'] }} - {{ $dates['this_week']['end'] }}<label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="{{ $dates['this_month']['start'] }}" end="{{ $dates['this_month']['end'] }}">
						<p class="description-text" style="text-align:center;">THIS MONTH</p>
						<div class="description-block">
							<label>{{ $dates['this_month']['start'] }} - {{ $dates['this_month']['end'] }}<label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="{{ $dates['this_year']['start'] }}" end="{{ $dates['this_year']['end'] }}">
						<p class="description-text" style="text-align:center;">THIS YEAR</p>
						<div class="description-block">
							<label>{{ $dates['this_year']['start'] }} - {{ $dates['this_year']['end'] }}<label>
						</div>
					</button>

					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="{{ $dates['yesterday']['start'] }}" end="{{ $dates['yesterday']['end'] }}">
						<p class="description-text" style="text-align:center;">YESTERDAY</p>
						<div class="description-block">
							<label>{{ $dates['yesterday']['start'] }} - {{ $dates['yesterday']['end'] }}<label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="{{ $dates['last_week']['start'] }}" end="{{ $dates['last_week']['end'] }}">
						<p class="description-text" style="text-align:center;">LAST WEEK</p>
						<div class="description-block">
							<label>{{ $dates['last_week']['start'] }} - {{ $dates['last_week']['end'] }}<label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="{{ $dates['last_month']['start'] }}" end="{{ $dates['last_month']['end'] }}">
						<p class="description-text" style="text-align:center;">LAST MONTH</p>
						<div class="description-block">
							<label>{{ $dates['last_month']['start'] }} - {{ $dates['last_month']['end'] }}<label>
						</div>
					</button>
					<button class="select_dates btn btn-info col-xs-12 col-sm-6 col-md-3 col-lg-3" type="button" start="{{ $dates['last_year']['start'] }}" end="{{ $dates['last_year']['end'] }}">
						<p class="description-text" style="text-align:center;">LAST YEAR</p>
						<div class="description-block">
							<label>{{ $dates['last_year']['start'] }} - {{ $dates['last_year']['end'] }}<label>
						</div>
					</button>
		        </div>
		        {!! Form::close() !!}
			</div>
		</div>
	<!-- /.box -->
	</div>
	<!-- /.col -->
</div>
@stop