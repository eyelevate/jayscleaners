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
use App\Company;
use App\Customer;
use App\Custid;
use App\Delivery;
use App\Layout;
use App\Zipcode;


class PagesController extends Controller
{
    public function __construct() {
    	$this->layout = 'layouts.home';
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $auth = (Auth::check()) ? Auth::user() : False;
        return view('pages.index')
        ->with('layout',$this->layout)
        ->with('auth',$auth);
    }

    public function getLogin() {
        $this->layout = 'layouts.frontend_basic';
        return view('pages.login')
        ->with('layout',$this->layout);
    }

    public function postLogin() {
        $this->layout = 'layouts.frontend_basic';
        $username = Input::get('username');
        $password = Input::get('password');
        $remember = Input::get('remember');

        if($remember) { // If user requests to be remembered create session
            if (Auth::attempt(['username' => $username, 'password' => $password], $remember)) {
                Flash::success('Welcome back '.$username.'!');

                //redirect to intended page
                return (Session::has('intended_url')) ? Redirect::to(Session::get('intended_url')) : redirect()->intended('/');
            } else { //LOGING FAILED
                Flash::error('Wrong Username or Password!');
                return view('pages.login')
                ->with('layout',$this->layout);
            }   
        } else {
            if (Auth::attempt(['username'=>$username, 'password'=>$password])) {
                Flash::success('Welcome back '.$username.'!');

                return (Session::has('intended_url')) ? Redirect::to(Session::get('intended_url')) : redirect()->intended('/');
            } else { //LOGIN FAILED
                Flash::error('Wrong Username or Password!');
                return view('pages.login')
                ->with('layout',$this->layout);
            }   
        }
     
    }        

    public function postLogout() {
        Auth::logout();
        Flash::success('You have successfully been logged out');
        return Redirect::action('PagesController@getIndex');
    }

    public function postZipcodes(Request $request) {
        $this->layout = 'layouts.frontend_basic';

        //Validate the request
        $this->validate($request, [
            'zipcode' => 'required|min:5'
        ]);

        $zipcode = $request->zipcode;
    
        $zipcodes = Zipcode::where('zipcode',$zipcode)->get();
        $status = (count($zipcodes) > 0) ? true : false;

        return view('pages.zipcodes')
        ->with('layout',$this->layout)
        ->with('zipcode',$zipcode)
        ->with('status',$status);        
    }

    public function getRegistration() {
        $this->layout = 'layouts.frontend_basic';

        return view('pages.register')
        ->with('layout',$this->layout);
    }

    public function postRegistration(Request $request) {
        //Validate the request
        $this->validate($request, [
            'first_name' => 'required',
            'last_name'=>'required',
            'phone'=>'required|min:10',
            'email' => 'email|required|max:255',
            'username'=>'required|unique:users|alpha_num',
            'password'=>'required|min:6|confirmed',
            'password_confirmation'=>'required|min:6'
        ]);

        $users = new User();
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->phone = Job::strip($request->phone);
        $users->email = $request->email;
        $users->username = $request->username;
        $users->password = bcrypt($request->password);
        $users->role_id = 5; //Customer status

        if ($users->save()) {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password], true)) {
                Flash::success(ucfirst($request->first_name).' '.ucfirst($request->last_name).', thank you for your registration! Please fill out the form below to start the delivery process.');
                return Redirect::route('delivery_form');
            } 


        }
    }
}
