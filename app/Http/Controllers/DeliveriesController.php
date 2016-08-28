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
        $primary_address = Address::where('user_id',Auth::user()->id)->where('primary_address',true)->get();
        $primary_address_id = false;
        $primary_zipcode = false;
        if (count($primary_address) > 0) {
            foreach ($primary_address as $pa) {
                $primary_address_id = $pa['id'];
                $primary_address_zipcode = $pa['zipcode'];
            }
        }

        $zipcodes = Zipcode::where('zipcode',$primary_address_zipcode)->get();
        $zipcode_status = (count($zipcodes) > 0) ? true : false;
        $calendar_dates = [];
        if ($zipcodes) {
            foreach ($zipcodes as $zipcode) {
                $calendar_dates[$zipcode['delivery_id']] = $zipcode['zipcode'];
            }
        }

        $calendar_setup = Delivery::makeCalendar($calendar_dates);

        if ($zipcode_status == false) {
            Flash::error('Your primary address is not set or zipcode is not valid. Please select a new address. ');
        }

        $dropoff_method = [''=>'Select Dropoff Method',
                           '1'=>'Delivered to the address chosen below.',
                           '2'=>'I wish to pick up my order myself.'];


    	return view('deliveries.form')
        ->with('layout',$this->layout)
        ->with('auth',$auth)
        ->with('addresses',$addresses)
        ->with('primary_address_id',$primary_address_id)
        ->with('dropoff_method',$dropoff_method)
        ->with('zipcode_status',$zipcode_status)
        ->with('calendar_disabled',$calendar_setup);
    } 

    public function postForm() {
    	
    }

    public function postCheckAddress(Request $request) {
        if($request->ajax()){

            $addr_id = $request->id;
            $addresses = Address::find($addr_id);
            $zip = (count($addresses) > 0) ? $addresses['zipcode'] : false;
            $zipcodes = Zipcode::where('zipcode',$zip)->get();
            $check = false;
            if (count($zipcodes) > 0) {
                foreach ($zipcodes as $zipcode) {
                    $check = true;
                }
            }

            return Response::json(['status'=>$check]);

        }
    }

    public function postSetTime(Request $request) {
        if ($request->ajax()) {
            $date = $request->date;
            $address_id = $request->address_id;

            $addresses = Address::find($address_id);
            $zipcode = false;
            if ($addresses) {
                $zipcode = $addresses->zipcode;
            }


            $zipcodes = Zipcode::where('zipcode',$zipcode)->get();
            $zip_list = [];
            if (count($zipcodes) > 0) {
                foreach ($zipcodes as $key => $zip) {
                    $delivery_id = $zip['delivery_id'];
                    $zip_list[$key] = $delivery_id;

                }
            }

            $time_options = Delivery::setTimeOptions($date,$zip_list);
            if (count($time_options) > 0){
                return Response::json(['status'=>true,
                                       'options'=>$time_options]);
            } else {
                return Response::json(['status'=>false]);
            }
        }
    }
}
