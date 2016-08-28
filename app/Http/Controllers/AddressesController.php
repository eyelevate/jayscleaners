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

    public function getIndex() {
    	$addresses = Address::where('user_id',Auth::user()->id)->orderby('primary_address','desc')->get();
        $auth = (Auth::check()) ? Auth::user() : False;
        return view('addresses.index')
        ->with('layout',$this->layout)
        ->with('addresses',$addresses)
        ->with('auth',$auth);
    }

    public function getAdd() {

        $auth = (Auth::check()) ? Auth::user() : False;
        $states = Job::states();
        return view('addresses.add')
        ->with('layout',$this->layout)
        ->with('auth',$auth)
        ->with('states',$states);    	
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
        if ($primary_address_id) {
        	$addresses->primary_address = false;
        } else {
        	$addresses->primary_address = true;
        }

        if ($addresses->save()){
        	Flash::success('Successfully added a new address!');
            return Redirect::route('address_index');
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

    public function getDelete($id = NULL) {
    	$addresses = Address::find($id);

    	if ($addresses->delete()) {
        	Flash::success('Successfully deleted "'.$addresses->name.'"!');
            return Redirect::route('address_index');    		
    	}
    }

    public function getPrimary($id = NULL) {
    	
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
            return Redirect::route('address_index');    		
    	}
    }
}
