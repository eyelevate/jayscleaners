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

use Illuminate\Http\Request;
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

        //Set controller variables
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
        Flash::success('You have successfully been logged out');
        return Redirect::action('AdminsController@getLogin');

    }

    public function getOverview(){

        $admins = Admin::prepareAdmin(User::where('role_id',1)->orderBy('last_name', 'asc')->get());

        return view('admins.overview')
        ->with('layout',$this->layout)
        ->with('admins',$admins);

    }

    public function getAdd(){
        $this->layout = 'layouts.admin_login';
        $companies = [''=>'Select A Location',1=>'Montlake',2=>'Roosevelt'];

        return view('admins.add')
        ->with('layout',$this->layout)
        ->with('companies',$companies);
    }

    public function postAdd(Request $request){

        //Validate the request
        $this->validate($request, [
            'username' => 'required|unique:users|max:255',
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'phone'=>'required|min:10',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'company_id'=>'required'
        ]);


        // Validation has passed save data
        $users = new User;
        $users->username = $request->username;
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->role_id = 1; //Admin status
        $users->email = $request->email;
        $users->phone = $request->phone;
        $users->password = bcrypt($request->password);


        if ($users->save()) {
             Flash::success('Successfully added!');
             return Redirect::route('admins_login');
        }
    }

    public function getEdit($id = null){
        $user = User::find($id);
        $companies = [''=>'Select A Location',1=>'Montlake',2=>'Roosevelt'];

        return view('admins.edit')
        ->with('layout',$this->layout)
        ->with('companies',$companies)
        ->with('user',$user);
    }

    public function postEdit(Request $request){
        //Validate the request
        $this->validate($request, [
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'phone'=>'required|min:10',
            'password' => 'required|confirmed|min:6',
            'company_id'=>'required'
        ]);

        // Validation has passed save data
        $users = User::find($request->id);
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->role_id = 1; //Admin status
        $users->contact_phone = $request->phone;
        $users->company_id = $request->company_id;
        $users->password = bcrypt($request->password);

        if ($users->save()) {
             Flash::success('Successfully updated admin!');
             return Redirect::route('admins_overview');
        }
    }

    public function getView($id = null){
        return view('admins.view')
        ->with('layout',$this->layout);
    }

}
