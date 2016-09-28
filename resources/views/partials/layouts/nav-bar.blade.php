<?php
switch (Auth::user()->role_id) {
  case '1':
    $role = 'Superadmin';
    break;
  case '2':
    $role = 'Manager';
    break;

  case '3':
    $role = 'Employee';
    break;
  case '4':
    $role = 'Guest';
    break;
  default:
    $role = 'Member';
    break;
}
?>

<div class="navbar-custom-menu">
  <ul class="nav navbar-nav">
    <!-- Messages: style can be found in dropdown.less-->

    <!-- Notifications: style can be found in dropdown.less -->

    <!-- Tasks: style can be found in dropdown.less -->

    <!-- User Account: style can be found in dropdown.less -->
    <li class="dropdown user user-menu">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="user-image" alt="User Image">
        <span class="hidden-xs">{{ Auth::user()->username }}</span>
      </a>
      <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
          <img src="http://api.adorable.io/avatars/285/abott@adorable.png" class="img-circle" alt="User Image">
          <p>
            {{ Auth::user()->username }} - {{ $role }}
            <small>Member since {{ date('M., Y',strtotime(Auth::user()->created_at)) }}</small>
          </p>
        </li>
        <!-- Menu Body -->
        <li class="user-body">

        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
          <div class="pull-left">
            <a href="{{ route('admins_edit',Auth::user()->id) }}" class="btn btn-default btn-flat">Edit</a>
          </div>
          <div class="pull-right">
            <form role="form" method="POST" action="{{  route('admins_logout_post') }}">
              {!! csrf_field() !!}
              <input type="submit" class="btn btn-default btn-flat" value="Sign Out"/>
            </form>
          </div>
        </li>
      </ul>
    </li>

  </ul>
</div>