@extends($layout)
@section('stylesheets')

@stop
@section('scripts')
<script type="text/javascript" src="/js/admins/index.js"></script>
@stop
@section('header')
  <h1>
    Admins Home Page
    <small>Control panel</small>
  </h1>
  <ol class="breadcrumb">
  	<li class="active">Admins</li>

  </ol>
@stop

@section('notifications')
  {!! View::make('partials.layouts.nav-bar')->render() !!}
@stop
@section('content')
  <div class="row">
    <a href="{{ route('customers_index','') }}" class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>Invoice</h3>
          <p>Drop / Pickup</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-paper-outline"></i>
        </div>
        <div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
      </div>
    </a><!-- ./col -->
    <a href="{{ route('reports_index') }}" class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>Reports</h3>
          <p>Sales reports</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
      </div>
    </a><!-- ./col -->
    <a href="{{ route('delivery_overview') }}" class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>Delivery</h3>
          <p>Delivery Schedule</p>
        </div>
        <div class="icon">
          <i class="ion ion-android-car"></i>
        </div>
        <div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
      </div>
    </a><!-- ./col -->
    <a href="{{ route('admins_settings') }}" class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>Settings</h3>
          <p>Company Settings</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-settings-strong"></i>
        </div>
        <div class="small-box-footer">Click Here <i class="fa fa-arrow-circle-right"></i></div>
      </div>
    </a><!-- ./col -->
  </div><!-- /.row -->
      
  <!-- TO DO List -->
  <div class="box box-primary">
    <div class="box-header">
      <i class="ion ion-clipboard"></i>
      <h3 class="box-title">Work List</h3>
      <div class="box-tools pull-right">

      </div>
    </div><!-- /.box-header -->
    <div class="box-body">
      <ul class="todo-list">
        <li>
          <a href="{{ route('invoices_report',2) }}">
            <!-- Emphasis label -->
            <span class="badge-default">{{ $today_totals['invoices_overdue'] }}</span>
            <!-- todo text -->
            <span class="ltext">Overdue Orders</span>
          </a>
        </li>
        <li>
          <a href="{{ route('invoices_report',1) }}">
          <!-- Emphasis label -->  
          <span class="badge-green">{{ $today_totals['invoices_today'] }}</span>
          <!-- todo text -->
          <span class="ltext">Due Today</span>
          </a>
        </li>
        <li>
          <a href="{{ route('delivery_overview') }}">
          <!-- Emphasis label -->
          <span class="badge-yellow">{{ $today_totals['deliveries'] }}</span>
          <!-- todo text -->
          <span class="ltext">Delivery Today</span>
          </a>
        </li>
        <li>
          <a href="{{ route('invoices_report',3) }}">
          <!-- Emphasis label -->
          <span class="badge-red">{{ $today_totals['invoices_voided'] }}</span>
          <!-- todo text -->
          <span class="ltext">Voided Today</span>
          </a>
        </li>
        <li>
          <a href="#">
          <!-- Emphasis label -->
          <span class="badge-aqua">{{ $today_totals['invoices_wayoverdue'] }}</span>
          <!-- todo text -->
          <span class="ltext">Aged (30 days+)</span>
          </a>
        </li>

      </ul>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix no-border">
      
    </div>
  </div><!-- /.box -->
  <!-- Zipcode Requests -->
  <div class="box box-info">
    <div class="box-header">
      <i class="ion ion-clipboard"></i>
      <h3 class="box-title">Today's Zipcode Request</h3>
      <div class="box-tools pull-right"></div>
    </div><!-- /.box-header -->
    <div class="box-body table-responsive">
      <table class="table table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th>Zipcode</th>
            <th>Name</th>
            <th>Email</th>
            <th>Comment</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        @if (count($zipcode_requests)> 0)
          @foreach($zipcode_requests as $zr)
          <tr>
            <td>{{ $zr->zipcode }}</td>
            <td>{{ $zr->name }}</td>
            <td>{{ $zr->email }}</td>
            <td>{{ $zr->comment }}</td>
            <td>{{ date('D n/d/Y', strtotime($zr->created_at)) }}</td>
            <td><a>Reply</a></td>
          </tr>
          @endforeach
        @endif
        </tbody>
      </table>
    </div>
    <div class="box-footer clearfix">
      <a href="" class="btn btn-info">Zipcode Requests<a/>
    </div>
  </div>
@stop