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
use App\Address;
use App\Company;
use App\Customer;
use App\Custid;
use App\Delivery;
use App\Layout;
use App\Zipcode;

class AddressesController extends Controller
{
    public function __construct() {
    	$this->layout = 'layouts.frontend_basic';
    }   

    public function getIndex(Request $request) {
    	$addresses = Address::prepareForView(Address::where('user_id',Auth::user()->id)->orderby('primary_address','desc')->get());
        $auth = (Auth::check()) ? Auth::user() : False;
        $form_previous = ($request->session()->has('form_previous')) ? $request->session()->get('form_previous') : 'delivery_pickup';
        return view('addresses.index')
        ->with('layout',$this->layout)
        ->with('addresses',$addresses)
        ->with('auth',$auth)
        ->with('form_previous',$form_previous);
    }

    public function getAdminIndex($id = null, Request $request) {

        $request->session()->put('address_user_id',$id);
        $request->session()->pull('new_form_back');
        $addresses = Address::where('user_id',$id)->orderby('primary_address','desc')->get();
        $form_previous = ($request->session()->has('form_previous')) ? $request->session()->get('form_previous') : 'delivery_pickup';
        $this->layout = 'layouts.dropoff';
        return view('addresses.admin_index')
        ->with('layout',$this->layout)
        ->with('addresses',$addresses)
        ->with('id',$id)
        ->with('form_previous',$form_previous);
    }


    public function getAdd() {

        $auth = (Auth::check()) ? Auth::user() : False;
        $states = Job::states();
        return view('addresses.add')
        ->with('layout',$this->layout)
        ->with('auth',$auth)
        ->with('states',$states);    	
    }
    public function getAdminAdd($id = null, Request $request) {

        $states = Job::states();
        $this->layout = 'layouts.dropoff';
        $back_redirect = ($request->session()->has('new_form_back')) ? $request->session()->get('new_form_back') : false;
        return view('addresses.admin-add')
        ->with('layout',$this->layout)
        ->with('customer_id',$id)
        ->with('states',$states)
        ->with('back_redirect',$back_redirect);       
    }
    public function postAdd(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'street'=>'required',
            'city'=>'required',
            'state' => 'required',
            'zipcode'=>'required',
            'concierge_name'=>'required',
            'concierge_number'=>'required'
        ]);

        $addresses = new Address();
        $addresses->user_id = Auth::user()->id;
        $addresses->status = 1;
        $addresses->name = $request->name;
        $addresses->street = $request->street;
        $addresses->suite = $request->suite;
        $addresses->city = $request->city;
        $addresses->state = $request->state;
        $addresses->zipcode = $request->zipcode;
        $addresses->concierge_name = $request->concierge_name;
        $addresses->concierge_number = $request->concierge_number;
        $primary_address = Address::where('user_id',Auth::user()->id)->where('primary_address',true)->pluck('id');
        $primary_address_id = (count($primary_address) > 0) ? $primary_address[0] : false;
        $addresses->primary_address = ($primary_address_id) ? false : true;

        if ($addresses->save()){
        	Flash::success('Successfully added a new address!');
            return Redirect::route('address_index');
            
        }
    }

    public function postAdminAdd(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'street'=>'required',
            'city'=>'required',
            'state' => 'required',
            'zipcode'=>'required',
            'concierge_name'=>'required',
            'concierge_number'=>'required'
        ]);

        $addresses = new Address();
        $addresses->user_id = $request->customer_id;
        $addresses->status = 1;
        $addresses->name = $request->name;
        $addresses->street = $request->street;
        $addresses->suite = $request->suite;
        $addresses->city = $request->city;
        $addresses->state = $request->state;
        $addresses->zipcode = $request->zipcode;
        $addresses->concierge_name = $request->concierge_name;
        $addresses->concierge_number = $request->concierge_number;
        $primary_address = Address::where('user_id',$request->customer_id)->where('primary_address',true)->pluck('id');
        $primary_address_id = (count($primary_address) > 0) ? $primary_address[0] : false;
        $addresses->primary_address = ($primary_address_id) ? false : true;

        if ($addresses->save()){
            Flash::success('Successfully added a new address!');
            if ($request->session()->has('new_form_back')) {
                $route_back = $request->session()->pull('new_form_back');
                return Redirect::route($route_back['route'],$route_back['param']);
            } else {
                return Redirect::route('address_admin_index',$request->customer_id);
            }
            
            
        }
    }

    public function getEdit($id = null) {
        $auth = (Auth::check()) ? Auth::user() : False;
        $states = Job::states();

        $addresses = Address::find($id);

        return view('addresses.edit')
        ->with('layout',$this->layout)
        ->with('auth',$auth)
        ->with('states',$states)
        ->with('addresses',$addresses);    
    }

    public function getAdminEdit($id = null, Request $request) {
        $auth = (Auth::check()) ? Auth::user() : False;
        $states = Job::states();

        $addresses = Address::find($id);
        $back_redirect = ($request->session()->has('new_form_back')) ? $request->session()->get('new_form_back') : false;
        $this->layout = 'layouts.dropoff';
        return view('addresses.admin-edit')
        ->with('layout',$this->layout)
        ->with('auth',$auth)
        ->with('states',$states)
        ->with('back_redirect',$back_redirect)
        ->with('addresses',$addresses);    
    }

    public function postEdit(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'street'=>'required',
            'city'=>'required',
            'state' => 'required',
            'zipcode'=>'required',
            'concierge_name'=>'required',
            'concierge_number'=>'required'
        ]);

        $addresses = Address::find($request->id);
        $addresses->name = $request->name;
        $addresses->street = $request->street;
        $addresses->suite = $request->suite;
        $addresses->city = $request->city;
        $addresses->state = $request->state;
        $addresses->zipcode = $request->zipcode;
        $addresses->concierge_name = $request->concierge_name;
        $addresses->concierge_number = $request->concierge_number;


        if ($addresses->save()){
        	Flash::success('Successfully edited address!');
            return Redirect::route('address_index');
        }    	
    }

    public function postAdminEdit(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'street'=>'required',
            'city'=>'required',
            'state' => 'required',
            'zipcode'=>'required',
            'concierge_name'=>'required',
            'concierge_number'=>'required'
        ]);

        $addresses = Address::find($request->id);
        $addresses->name = $request->name;
        $addresses->street = $request->street;
        $addresses->suite = $request->suite;
        $addresses->city = $request->city;
        $addresses->state = $request->state;
        $addresses->zipcode = $request->zipcode;
        $addresses->concierge_name = $request->concierge_name;
        $addresses->concierge_number = $request->concierge_number;


        if ($addresses->save()){
            Flash::success('Successfully edited address!');
            if ($request->session()->has('new_form_back')) {
                $route_back = $request->session()->pull('new_form_back');
                return Redirect::route($route_back['route'],$route_back['param']);
            } else {
                return Redirect::route('address_admin_index', $request->session()->get('address_user_id'));
            }
            
        }       
    }

    public function getDelete($id = NULL) {
    	$addresses = Address::find($id);

    	if ($addresses->delete()) {
        	Flash::success('Successfully deleted "'.$addresses->name.'"!');

            return Redirect::route('address_index');    		
    	}
    }

    public function getAdminDelete($id = null, Request $request) {
        $addresses = Address::find($id);

        if ($addresses->delete()) {
            Flash::success('Successfully deleted "'.$addresses->name.'"!');
            return Redirect::route('address_admin_index', $request->session()->get('address_user_id'));            
        }
    }    

    public function getPrimary($id = NULL) {
    	$addresses = Address::find($id);
    	$addresses->primary_address = true;
        $zipcode = $addresses->zipcode;
        $zipcodes = Zipcode::where('zipcode',$zipcode)->get();
        if (count($zipcodes) > 0) {
            $addrs = Address::where('user_id',Auth::user()->id)->get();
            if ($addrs) {
                foreach ($addrs as $address) {
                    $addr = Address::find($address->id);
                    $addr->primary_address = false;
                    $addr->save();
                }
            }
            if ($addresses->save()){
                Flash::success('Successfully set "<strong>'.$addresses->name.'</strong>" as primary address!');
                return Redirect::route('address_index');            
            }
        } else {
            Flash::error('This process cannot be completed. We do not currently deliver to this zipcode. Please try another address.');
            return Redirect::route('address_index');
        }

    }
    public function getAdminPrimary($id = NULL, Request $request) {
        
        $addresses = Address::where('user_id',Auth::user()->id)->get();
        if ($addresses) {
            foreach ($addresses as $address) {
                $addr = Address::find($address->id);
                $addr->primary_address = false;
                $addr->save();
            }
        }

        $addresses = Address::find($id);
        $addresses->primary_address = true;
        if ($addresses->save()){
            Flash::success('Successfully set "<strong>'.$addresses->name.'</strong>" as primary address!');
            return Redirect::route('address_admin_index', $request->session()->get('address_user_id'));            
        }
    }
}
