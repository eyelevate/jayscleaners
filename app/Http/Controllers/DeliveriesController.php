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
use App\Address;
use App\User;
use App\Company;
use App\Customer;
use App\Custid;
use App\Delivery;
use App\Layout;
use App\Zipcode;

class DeliveriesController extends Controller
{
    public function __construct() {
    	$this->layout = 'layouts.frontend_basic';
    }

    public function getForm() {
    	$auth = (Auth::check()) ? Auth::user() : false;
        $addresses = Address::addressSelect(Address::where('user_id',Auth::user()->id)->orderby('primary_address','desc')->get());
        $primary_address = Address::where('user_id',Auth::user()->id)->where('primary_address',true)->pluck('id');

        $primary_address_id = (count($primary_address) > 0) ? $primary_address[0] : '';
        
    	return view('deliveries.form')
        ->with('layout',$this->layout)
        ->with('auth',$auth)
        ->with('addresses',$addresses)
        ->with('primary_address_id',$primary_address_id);
    } 

    public function postForm() {
    	
    }
}
