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
					@if (count($companies) > 0)
						@foreach($companies as $company)
							@if(count($company->store_hours) > 0 && $company->id == 1)
								@foreach($company->store_hours as $key => $value)
									@if (date('l') == $key)
									<tr class="warning" style="color:#5e5e5e; font-weight:bold;">
										<th style="text-align:right;"><strong>{{ $key }}</strong></th>
										<td style="text-align:center;"><strong>{{ $value }}</strong></td>
										<td style="text-align:left;"><strong style="color:{{ $company['open_status'] ? 'green' : 'red' }};">{{ $company['open_status'] ? 'Open' : 'Closed' }}</strong></td>
									</tr>
									@else
									<tr>
										<th style="text-align:right;">{{ $key }}</th>
										<td style="text-align:center;">{{ $value }}</td>
										<td style="text-align:left;"></td>
									</tr>
									@endif
								
								@endforeach
							@endif
		
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
		</section>


	</div>


</section>