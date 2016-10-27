@extends($layout)
@section('stylesheets')
@stop
@section('scripts')
<script type="text/javascript" src="/packages/number/jquery.number.min.js"></script>
<script type="text/javascript" src="/packages/numeric/jquery.numeric.js"></script>
<script type="text/javascript" src="/packages/priceformat/priceformat.min.js"></script>
<script type="text/javascript" src="/js/invoices/manage.js"></script>

@stop
@section('header')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<br/>

<div class="box box-primary clearfix">
	<div class="box-header">
		<h3 class="box-title">Invoice Item ID</h3>
	</div>
	
	<div class="box-body">	
        <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
            <label class="control-label">Search <span class="text text-danger">*</span></label>

            <div class="">
                {!! Form::text('search', old('search'), ['id'=>'search_query','class'=>'form-control', 'placeholder'=>'']) !!}
                @if ($errors->has('search'))
                    <span class="help-block">
                        <strong>{{ $errors->first('search') }}</strong>
                    </span>
                @endif
            </div>
        </div>
	</div>
	<div class="box-footer clearfix">
		<button type="button" id="search_item" class="btn btn-lg btn-success pull-right" data-toggle="modal" data-target="#update">Search</button>
	</div>
</div>


@stop

@section('modals')
	{!! View::make('partials.invoices.manage')
		->with('locations',$locations)
		->with('companies',$companies)
		->with('company_id',1)
		->render()
	!!}
@stop

