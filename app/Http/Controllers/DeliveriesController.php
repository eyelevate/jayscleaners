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
use App\Schedule;
use App\Zipcode;

class DeliveriesController extends Controller
{
    public function __construct() {
    	$this->layout = 'layouts.frontend_basic';
    }

    public function getPickupForm(Request $request) {
        // $request->session()->forget('schedule');
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

        $selected_date = false;
        $selected_delivery_id = false;
        $pickup_data = $request->session()->get('schedule');
        $check_session = (isset($pickup_data['pickup_address'])) ? true : false;
        if ($check_session) {
            
            $primary_address_id = (isset($pickup_data['pickup_address'])) ? $pickup_data['pickup_address'] : false;
            $primary_address = Address::find($primary_address_id);
            $primary_address_zipcode = (count($primary_address) > 0) ? $primary_address->zipcode : false;
            $selected_date = $pickup_data['pickup_date'];
            $selected_delivery_id = $pickup_data['pickup_delivery_id'];

       }


        $zipcodes = Zipcode::where('zipcode',$primary_address_zipcode)->get();
        $zip_list = [];
        if (count($zipcodes) > 0) {
            foreach ($zipcodes as $key => $zip) {
                $delivery_id = $zip['delivery_id'];
                $zip_list[$key] = $delivery_id;

            }
        }
        $time_options = Delivery::setTimeArray($selected_date,$zip_list);


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

        $breadcrumb_data = Delivery::setBreadCrumbs($pickup_data);

    	return view('deliveries.pickup')
        ->with('layout',$this->layout)
        ->with('auth',$auth)
        ->with('addresses',$addresses)
        ->with('primary_address_id',$primary_address_id)
        ->with('dropoff_method',$dropoff_method)
        ->with('zipcode_status',$zipcode_status)
        ->with('calendar_disabled',$calendar_setup)
        ->with('selected_date',$selected_date)
        ->with('selected_delivery_id',$selected_delivery_id)
        ->with('zip_list',$zip_list)
        ->with('time_options',$time_options)
        ->with('breadcrumb_data',$breadcrumb_data);
    } 

    public function getDropoffForm(Request $request) {
        $pickup_data = $request->session()->get('schedule');
        $check_session = (isset($pickup_data['pickup_address'])) ? true : false;
        if($check_session) {

            $auth = (Auth::check()) ? Auth::user() : false;
            $addresses = Address::addressSelect(Address::where('user_id',Auth::user()->id)->orderby('primary_address','desc')->get());
            $primary_address_id = (isset($pickup_data['pickup_address'])) ? $pickup_data['pickup_address'] :  false;
            $primary_address = Address::find($primary_address_id);

            $primary_address_zipcode = false;
            $primary_zipcode = false;
            if (count($primary_address) > 0) {
                $primary_address_id = $primary_address->id;
                $primary_address_zipcode = $primary_address->zipcode;            
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

            $breadcrumb_data = Delivery::setBreadCrumbs($pickup_data);
            return view('deliveries.dropoff')
            ->with('layout',$this->layout)
            ->with('auth',$auth)
            ->with('addresses',$addresses)
            ->with('primary_address_id',$primary_address_id)
            ->with('dropoff_method',$dropoff_method)
            ->with('zipcode_status',$zipcode_status)
            ->with('calendar_disabled',$calendar_setup)
            ->with('date_start',date('D m/d/Y',strtotime($pickup_data['pickup_date'].' +1 day')))
            ->with('breadcrumb_data',$breadcrumb_data);
        } else {
            Flash::error('Pickup form data has been timed out. Please fill out and try again.');
            return Redirect::route('delivery_pickup');
        }

    } 


    public function postPickupForm(Request $request) {
    	$this->validate($request, [
            'pickup_address' => 'required',
            'pickup_date'=>'required',
            'pickup_time'=>'required'
        ]);
        $request->session()->put('schedule', [
            'customer_id' => Auth::user()->id,
            'pickup_delivery_id' => $request->pickup_time,
            'pickup_address' => $request->pickup_address,
            'pickup_date'=> date('Y-m-d H:i:s',strtotime($request->pickup_date))
        ]);

        Flash::success('Please fill out dropoff form.');
        return Redirect::route('delivery_dropoff');
    }

    public function postDropoffForm(Request $request) {
        $this->validate($request, [
            'dropoff_date'=>'required',
            'dropoff_time'=>'required'
        ]);
        $schedule = $request->session()->get('schedule');
        $request->session()->put('schedule', [
            'pickup_delivery_id' => $schedule['pickup_delivery_id'],
            'pickup_address' => $schedule['pickup_address'],
            'pickup_date'=> $schedule['pickup_date'],            
            'dropoff_delivery_id' => $request->dropoff_time,
            'dropoff_address' => $request->dropoff_address,
            'dropoff_date'=> date('Y-m-d H:i:s',strtotime($request->dropoff_date))
        ]);

        return Redirect::route('delivery_confirmation');
    }

    public function getConfirmation(Request $request) {
        $schedule_data = $request->session()->get('schedule');
        $breadcrumb_data = Delivery::setBreadCrumbs($schedule_data);
        $profile_id = Auth::user()->profile_id;
        $payment_id = Auth::user()->payment_id;


        return view('deliveries.confirmation')
        ->with('layout',$this->layout)
        ->with('schedule',$schedule_data)
        ->with('breadcrumb_data',$breadcrumb_data);
    }

    public function postConfirmation(Request $request) {

    }

    public function getCancel(Request $request) {
        if ($request->session()->has('schedule')) {
            $request->session()->forget('schedule');
        }
        return Redirect::route('pages_index');

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
