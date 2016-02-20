@extends($layout)
@section('stylesheets')

@stop
@section('scripts')

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
@section('content')
  <div class="row">
    <a href="{{ route('invoices_add') }}" class="col-lg-3 col-xs-6">
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
    <a href="#" class="col-lg-3 col-xs-6">
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
    <a href="#" class="col-lg-3 col-xs-6">
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
    <a href="" class="col-lg-3 col-xs-6">
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
                      <a href="#">
                        <!-- Emphasis label -->
                        <span class="badge-default">10</span>
                        <!-- todo text -->
                        <span class="ltext">Overdue Orders</span>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                      <!-- Emphasis label -->
                      <span class="badge-green">10</span>
                      <!-- todo text -->
                      <span class="ltext">Due Today</span>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                      <!-- Emphasis label -->
                      <span class="badge-yellow">10</span>
                      <!-- todo text -->
                      <span class="ltext">Delivery Today</span>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                      <!-- Emphasis label -->
                      <span class="badge-red">10</span>
                      <!-- todo text -->
                      <span class="ltext">Voided Today</span>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                      <!-- Emphasis label -->
                      <span class="badge-aqua">10</span>
                      <!-- todo text -->
                      <span class="ltext">Aged (30 days+)</span>
                      </a>
                    </li>

                  </ul>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix no-border">
                  
                </div>
              </div><!-- /.box -->
@stop