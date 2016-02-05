<?php

namespace App\Http\Controllers;

use Input;
use Validator;
use Redirect;
use Hash;
use Route;
use Response;
use Auth;
use URL;
use Session;
use Laracasts\Flash\Flash;
use View;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Job;
use App\User;
use App\Admin;
use App\Layout;
// use App\Role;
// use App\RoleUser;
// use App\Permission;
// use App\PermissionRole;

class AdminsController extends Controller
{
    public function __construct() {
    	$this->layout = 'layouts.admin';

    }
    
    public function getIndex() {


        return view('admins.index')
        ->with('layout',$this->layout);
    }

    public function getLogin() {

    	return view('admins.index')
    	->with('layout',$this->layout);
    }

}
