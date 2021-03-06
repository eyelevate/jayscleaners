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
use App\Inventory;
use App\InventoryItem;
use App\Layout;
use App\Schedule;
use App\Address;
use App\Zipcode;
use App\ZipcodeList;


class PagesController extends Controller
{
    public function __construct() {
    	$this->layout = 'layouts.home';
        // $this->layout = 'layouts.home-nodelivery';
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
        // return view('pages.index-nodelivery')
        // ->with('layout',$this->layout)
        // ->with('schedules',$schedules)
        // ->with('companies',$companies)
        // ->with('auth',$auth);

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
        $users = User::where('username',$username)->get();
        $password_expired = 3;
        if($remember) { // If user requests to be remembered create session
            if (Auth::attempt(['username' => $username, 'password' => $password], $remember)) {
                Flash::success('Welcome back '.$username.'!');

                $user_id = Auth::user()->id;
                $password_token = Auth::user()->starch_old;
                if ($password_token != 1) {
                    $user = User::find($user_id);
                    $user->starch_old = 1;
                    $user->save();
                }

                //redirect to intended page
                return (Session::has('intended_url')) ? Redirect::to(Session::get('intended_url')) : redirect()->intended('/');
            } else { //LOGING FAILED
                
                if (count($users) > 0) {
                    foreach ($users as $user) {
                        $password_expired = ($user->starch_old != '') ? $user->starch_old : 3;
                    }
                    if ($password_expired != 1) {
                        Flash::error('Password has expired. Please reset your password using the form below. Thank you.');
                        return redirect('/password/reset');
                    } else {
                        Flash::error('Wrong Username or Password!');

                    }
                } else {
                    Flash::error('Wrong Username or Password!');
                }

                
                return view('pages.login')
                ->with('layout',$this->layout);
            }   
        } else {
            if (Auth::attempt(['username'=>$username, 'password'=>$password])) {
                Flash::success('Welcome back '.$username.'!');
                $user_id = Auth::user()->id;
                $password_token = Auth::user()->starch_old;
                if ($password_token != 1) {
                    $user = User::find($user_id);
                    $user->starch_old = 1;
                    $user->save();
                }

                return (Session::has('intended_url')) ? Redirect::to(Session::get('intended_url')) : redirect()->intended('/');
            } else { //LOGIN FAILED
                if (count($users) > 0) {
                    foreach ($users as $user) {
                        $password_expired = ($user->starch_old != '') ? $user->starch_old : 3;
                    }
                    if ($password_expired != 1) {
                        Flash::error('Password has expired. Please reset your password using the form below. Thank you.');
                        return redirect('/password/reset');
                    } else {
                        Flash::error('Wrong Username or Password!');
                    }
                } else {
                    Flash::error('Wrong Username or Password!');
                }
                
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
                    $edit->starch_old = 1; # override the expiration password
                    $edit->password = bcrypt($request->password);
                    $edit->role_id = 5;
                    if ($edit->save()) {

                        // check if the user has a mark if so then pass if not then create one
                        $custids = Custid::where('customer_id',$edit->id)->get();
                        if (count($custids) == 0) {
                            $marks = new Custid();
                            $marks->company_id = 1;
                            $marks->customer_id = $edit->id;
                            $marks->mark = Custid::createOriginalMark($edit);
                            $marks->status = 1;
                            $marks->save();
                        }
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
            $users->company_id = 1;
            $users->first_name = $request->first_name;
            $users->last_name = $request->last_name;
            $users->phone = Job::strip($request->phone);
            $users->email = $request->email;
            $users->username = $request->username;
            $users->starch_old = 1; #override the password expiration
            $users->password = bcrypt($request->password);
            $users->role_id = 5; //Customer status

            if ($users->save()) {
                // check to see if user has a custid
                $custids = Custid::where('customer_id',$users->id)->get();
                if (count($custids) == 0) {
                    $marks = new Custid();
                    $marks->company_id = 1;
                    $marks->customer_id = $users->id;
                    $marks->mark = Custid::createOriginalMark($users);
                    $marks->status = 1;
                    $marks->save();
                }


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
        // DELIVERY
        $this->layout = 'layouts.frontend_basic';
        return view('pages.services')
        ->with('layout',$this->layout);

        // NO DELIVERY
        // $this->layout = 'layouts.frontend-nodelivery';
        // return view('pages.services-nodelivery')
        // ->with('layout',$this->layout);

    }

    public function getBusinessHours() {
        // DELIVERY
        $this->layout = 'layouts.frontend_basic';
        $companies = Company::prepareForView(Company::all());
        return view('pages.business-hours')
        ->with('companies',$companies)
        ->with('layout',$this->layout);

        // NO DELIVERY
        // $this->layout = 'layouts.frontend-nodelivery';
        //$companies = Company::prepareForView(Company::all());
        // return view('pages.business-hours-nodelivery')
        // ->with('companies',$companies)
        // ->with('layout',$this->layout);
    }

    public function getContactUs() {
        // DELIVERY
        $this->layout = 'layouts.frontend_basic';
        $companies = Company::prepareForView(Company::all());
        return view('pages.contact-us')
        ->with('companies',$companies)
        ->with('layout',$this->layout);   

        // NO DELIVERY
        // $this->layout = 'layouts.frontend-nodelivery';
        // $companies = Company::prepareForView(Company::all());
        // return view('pages.contact-us-nodelivery')
        // ->with('companies',$companies)
        // ->with('layout',$this->layout);     
    }

    public function getUpdateContact() {
        $this->layout = 'layouts.frontend_basic';
        $customer_id = Auth::user()->id;

        return view('pages.update-contact')
        ->with('layout',$this->layout);          
    }

    public function getPricing() {
        $price_list = InventoryItem::preparePricingList();
        // DELIVERY
        $this->layout = 'layouts.frontend_basic';
        return view('pages.pricing')
        ->with('price_list',$price_list)
        ->with('layout',$this->layout);

        // NO DELIVERY
        // $this->layout = 'layouts.frontend-nodelivery';
        // return view('pages.pricing-nodelivery')
        // ->with('price_list',$price_list)
        // ->with('layout',$this->layout);
    }

    public function getResetPassword($token = null) {
        
        if (isset($token)){
            $users = User::where('token',$token)->get();
            if (count($users) > 0) {
                foreach ($users as $user) {
                    $status = $user->starch_old;
                    if ($status == 1) {
                        Flash::error('You have already changed the password. Please use the "forgot password" form on the login page to request a new form. Thank you.');
                        return Redirect::route('pages_index');
                    }
                }

            } else {
                Flash::error('You do not have access to this form. Please try again.');
                return Redirect::route('pages_index');
            }

            $this->layout = 'layouts.bill';
            return view('pages.reset_password')
            ->with('users',$users)
            ->with('layout',$this->layout);
        } else {
            Flash::error('You are not able to view this page. Please contact administrator for assistance');
            return Redirect::route('pages_index');
        }

    }

    public function postResetPassword(Request $request) {
        $this->validate($request, [
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'required'
        ]);

        // save password
        $user_id = $request->id;
        $users = User::find($user_id);
        $users->password = bcrypt($request->password);
        $users->token = NULL;
        $users->starch_old = 1;
        if ($users->save()) {
            Flash::success('Successfully updated password. Please log in with your new password.');
            return Redirect::route('pages_login');
        }


        // remove 
    }

    public function getTerms() {
        // DELIVERY
        $this->layout = 'layouts.bill';
        return view('pages.terms')
            ->with('layout',$this->layout);

        // NO DELIVERY
        // $this->layout = 'layouts.bill-nodelivery';
        // return view('pages.terms-nodelivery')
        //     ->with('layout',$this->layout);
    }
}
