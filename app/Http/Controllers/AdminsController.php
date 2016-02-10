<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


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
        $this->layout = 'layouts.admin_login';
    	return view('admins.login')
    	->with('layout',$this->layout);
    }

    public function postLogin() {
        $this->layout = 'layouts.admin_login';
        $username = Input::get('username');
        $password = Input::get('password');
        $remember = Input::get('remember');

        if($remember) { // If user requests to be remembered create session
            if (Auth::attempt(['username' => $username, 'password' => $password], $remember)) {
                Flash::success('Welcome back '.$username.'!');

                //redirect to intended page
                return (Session::has('intended_url')) ? Redirect::to(Session::get('intended_url')) : redirect()->intended('/admins');
            } else { //LOGING FAILED
                Flash::error('Wrong Username or Password!');
                return view('admins.login')
                ->with('layout',$this->layout);
            }   
        } else {
            if (Auth::attempt(['username'=>$username, 'password'=>$password])) {
                Flash::success('Welcome back '.$username.'!');

                return (Session::has('intended_url')) ? Redirect::to(Session::get('intended_url')) : redirect()->intended('/admins');
            } else { //LOGING FAILED
                Flash::error('Wrong Username or Password!');
                return view('admins.login')
                ->with('layout',$this->layout);
            }   
        }
     
    }

    public function postLogout() {
        Auth::logout();

    }

}
