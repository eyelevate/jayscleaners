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
use App\Schedule;
use App\Address;
use App\Zipcode;
use App\ZipcodeList;


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
        $companies = Company::prepareForView(Company::all());
        $schedules = ($auth) ? Schedule::prepareSchedule(Schedule::where('customer_id',Auth::user()->id)->orderBy('id','desc')->limit(1)->get()) : false;
        return view('pages.index')
        ->with('layout',$this->layout)
        ->with('schedules',$schedules)
        ->with('companies',$companies)
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

    public function getLogout() {
        Auth::logout();
        Flash::success('You have successfully been logged out');
        return Redirect::action('PagesController@getIndex');        
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
    
        $zipcodes = ZipcodeList::where('zipcode',$zipcode)->get();
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
            'email' => 'email|required|unique:users|max:255',
            'username'=>'required|unique:users|alpha_num',
            'password'=>'required|min:6|confirmed',
            'password_confirmation'=>'required|min:6'
        ]);

        // check users phone number if exists edit their account info with the new info
        $check = User::where('phone',Job::strip($request->phone))->get();
        if (count($check) > 0) {
            foreach ($check as $ch) {
                $user_id = $ch->id;
                $username = $ch->username;
                if (!isset($username)) {
                    $edit = User::find($user_id);
                    $edit->first_name = $request->first_name;
                    $edit->last_name = $request->last_name;
                    $edit->phone = Job::strip($request->phone);
                    $edit->email = $request->email;
                    $edit->username = $request->username;
                    $edit->password = bcrypt($request->password);
                    $edit->role_id = 5;
                    if ($edit->save()) {
                        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], true)) {
                            $request->session()->put('registered',true);
                            Flash::success(ucfirst($request->first_name).' '.ucfirst($request->last_name).', thank you for your registration! Please fill out the form below to start the delivery process.');
                            return Redirect::route('delivery_pickup');
                        }                         
                    }
                } else {
                    Flash::warning('A user with with the phone number of "'.$request->phone.'" already exist and has an active account. Please use another username and phone number and try again.');
                    return Redirect::back();
                }

            }
        } else {
            // otherwise save a new profile

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
                    $request->session()->put('registered',true);
                    Flash::success(ucfirst($request->first_name).' '.ucfirst($request->last_name).', thank you for your registration! Please fill out the form below to start the delivery process.');
                    return Redirect::route('delivery_pickup');
                } 


            }
        }
    }

    public function postOneTouch(Request $request) {
        $schedules = Schedule::prepareSchedule(Schedule::where('customer_id',Auth::user()->id)->orderBy('id','desc')->limit(1)->get());
        if (count($schedules) > 0) {
            foreach ($schedules as $schedule) {
                $today = strtotime(date('Y-m-d 00:00:00'));
                $company_id = $schedule['company_id'];
                $last_dropoff_date = strtotime($schedule['dropoff_date']);
                $start_date = ($today >= $last_dropoff_date) ? $today : $last_dropoff_date;
                $next_available_pickup = Delivery::prepareNextAvailableDate($schedule['pickup_delivery_id'],date('Y-m-d H:i:s',$start_date));
                $next_available_dropoff = Delivery::prepareNextAvailableDate($schedule['dropoff_delivery_id'],$next_available_pickup);
                
                // set session
                $request->session()->put('schedule', [
                    'pickup_delivery_id' => $schedule['pickup_delivery_id'],
                    'pickup_address' => $schedule['pickup_address'],
                    'pickup_date'=> $next_available_pickup,            
                    'dropoff_delivery_id' => $schedule['dropoff_delivery_id'],
                    'dropoff_address' => $schedule['pickup_address'],
                    'dropoff_date'=> $next_available_dropoff,
                    'company_id' => $company_id
                ]);

                Flash::success('Please review the information set below before confirming your delivery schedule.');
            }
            
        } else {
            Flash::error('Not enough available data to create a one touch delivery. Please complete form to set delivery.');
            
        }
        return Redirect::route('delivery_confirmation');
        
    }

    public function getServices() {
        $this->layout = 'layouts.frontend_basic';

        return view('pages.services')
        ->with('layout',$this->layout);
    }

    public function getBusinessHours() {
        $this->layout = 'layouts.frontend_basic';

        return view('pages.business-hours')
        ->with('layout',$this->layout);
    }

    public function getContactUs() {
        $this->layout = 'layouts.frontend_basic';

        return view('pages.contact-us')
        ->with('layout',$this->layout);        
    }

    public function getUpdateContact() {
        $this->layout = 'layouts.frontend_basic';

        $customer_id = Auth::user()->id;

        return view('pages.update-contact')
        ->with('layout',$this->layout);          
    }
}
