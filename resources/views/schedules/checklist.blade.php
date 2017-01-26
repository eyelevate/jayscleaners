@extends($layout)
@section('stylesheets')
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
@stop
@section('scripts')
<script type="text/javascript" src="/packages/zebra_datepicker/public/javascript/zebra_datepicker.js"></script>
<script type="text/javascript" src="/js/schedules/checklist.js"></script>
<script type="text/javascript">
    $('#search_data').Zebra_DatePicker({
        container:$("#search_container"),
        format:'D m/d/Y',
        onSelect: function(a, b) {
        	$("#search_form").submit();
        }
    });

</script>
@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Delivery Overview</h3>
		</div>
        {!! Form::open(['action' => 'SchedulesController@postChecklist','role'=>"form",'id'=>'search_form']) !!}
            {!! csrf_field() !!} 
		<div class="panel-body">
	        <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }} search_div">
	            <label class="col-md-12 col-lg-12 col-sm-12 col-xs-12 control-label padding-top-none">Delivery Date</label>

	            <div id="search_container" class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
	                <input id="search_data" type="text" class="form-control" name="search" value="{{ old('search') ? old('search') : $delivery_date }}" readonly="true" style="background-color:#ffffff">

	                @if ($errors->has('search'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('search') }}</strong>
	                    </span>
	                @endif
	            </div>
	        </div>
		</div>

		{!! Form::close() !!}
		<div class="panel-footer clearfix">
			<a href="{{ route('delivery_overview') }}" class="btn btn-lg btn-danger pull-left col-md-2 col-sm-6 col-xs-6"><i class="ion ion-chevron-left"></i>&nbsp; Back</a>
			<a href="{{ route('schedules_prepare_route') }}" class="btn btn-lg btn-primary pull-right col-md-2 col-sm-6 col-xs-6">Prepare Route(s) &nbsp;<i class="ion ion-chevron-right"></i></a>
		</div>
	</div>

@stop

@section('modals')
	@if (count($schedules) > 0)
		@foreach($schedules as $schedule)
		{!! View::make('partials.schedules.email')
			->with('schedule_id',$schedule['id'])
			->render()
		!!}
		@endforeach
	@endif
	@if(count($delayed_list) > 0)
		@foreach($delayed_list as $dl)
		{!! View::make('partials.schedules.email')
			->with('schedule_id',$dl['id'])
			->render()
		!!}
		@endforeach
	@endif
	@if(count($approved_list) > 0)
		@foreach($approved_list as $al)
		{!! View::make('partials.schedules.email')
			->with('schedule_id',$al['id'])
			->render()
		!!}
		@endforeach
	@endif
	{!! View::make('partials.frontend.modals')->render() !!}

@stop