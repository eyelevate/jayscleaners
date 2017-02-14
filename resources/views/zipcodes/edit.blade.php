@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

@stop
@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
<br/>
{!! Form::open(['action' => 'ZipcodesController@postEdit','role'=>"form"]) !!}
	{!! Form::hidden('id',$zipcodes->id) !!}
	<div class="panel panel-default">
		<div class="panel-heading"><h4 class="panel-title">Edit Zipcode</h4></div>
		<div class="panel-body" >
            <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                <label class="control-label padding-top-none">Zipcode <span style="color:#ff0000">*</span></label>

                {{ Form::text('zipcode',old('zipcode') ? old('zipcode') : $zipcodes->zipcode,['class'=>'form-control']) }}
                @if ($errors->has('zipcode'))
                    <span class="help-block">
                        <strong>{{ $errors->first('zipcode') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ $errors->has('routes') ? ' has-error' : '' }}">
                <label class="control-label padding-top-none">Route(s) <span style="color:#ff0000">*</span></label>

                {{ Form::select('routes',$deliveries,'',['class'=>'form-control']) }}
                @if ($errors->has('routes'))
                    <span class="help-block">
                        <strong>{{ $errors->first('routes') }}</strong>
                    </span>
                @endif
            </div>			
		</div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Day</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@if (count($edits) > 0)
					@foreach($edits as $edit)
					<tr>
						<td>{{ $edit->id }}</td>
						<td>{{ $edit['delivery']->name }}</td>
						<td>{{ $edit['delivery']->day }}</td>
						<td><button type="button" class="delete_btn btn btn-sm btn-danger">Delete</button></td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
		<div class="panel-footer">
			<a href="{{ route('zipcodes_index') }}" class="btn btn-danger">Back</a>
			<button class="btn btn-primary pull-right" type="submit">Edit</button>
		</div>
	</div>
{!! Form::close() !!}
@stop