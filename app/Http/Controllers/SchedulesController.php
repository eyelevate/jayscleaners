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
use Mail;
use URL;
use Session;
use Laracasts\Flash\Flash;
use View;

use App\Schedule;
use App\Job;
use App\Address;
use App\User;
use App\Card;
use App\Company;
use App\Customer;
use App\Custid;
use App\Delivery;
use App\Layout;
use App\Zipcode;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Geocoder;

class SchedulesController extends Controller
{
	public function __construct() {
    	$this->layout = 'layouts.dropoff';
    }

    public function getView($id = null, Request $request) {
        $schedules = Schedule::where('customer_id',$id)->where('status','<',12)->where('status','!=',6)->get();
        $request->session()->put('form_previous',['schedule_view',$id]);
        $active_list = Schedule::prepareSchedule($schedules);

        return view('schedules.view')
        ->with('layout',$this->layout)
        ->with('schedules',$active_list);
    }

    public function getChecklist(Request $request) {
        $this->layout = 'layouts.dropoff';
        $company_id = Auth::user()->company_id;

        $today = ($request->session()->has('delivery_date')) ? $request->session()->get('delivery_date') : date('Y-m-d 00:00:00');
        $schedules = Schedule::where('dropoff_date',$today)
        					   ->orWhere('pickup_date',$today)
        					   ->whereIn('status',[1,4])
        					   ->orderBy('id','desc')->get();
        $active_list = Schedule::prepareSchedule($schedules);

        $approved = Schedule::where('dropoff_date',$today)
        					   ->orWhere('pickup_date',$today)
        					   ->whereIn('status',[2,5])
        					   ->orderBy('id','desc')->get();
       	$approved_list = Schedule::prepareSchedule($approved);

        return view('schedules.checklist')
        ->with('layout',$this->layout)
        ->with('delivery_date',date('D m/d/Y',strtotime($today)))
        ->with('schedules',$active_list)
        ->with('approved_list',$approved_list);
    }

    public function postCheckList(Request $request) {
    	$this->validate($request, [
            'search' => 'required'
        ]);

        $request->session()->put('delivery_date',date('Y-m-d 00:00:00',strtotime($request->search)));

        return Redirect::route('schedules_checklist');
    }

    public function getDeliveryRoute(Request $request) {
        $this->layout = 'layouts.dropoff';
        $today = ($request->session()->has('delivery_date')) ? $request->session()->get('delivery_date') : date('Y-m-d 00:00:00');
        $schedules = Schedule::where('dropoff_date',$today)
        					   ->orWhere('pickup_date',$today)
        					   ->whereIn('status',[2,5,11])
        					   ->orderBy('id','desc')->get();

        $active_list = Schedule::prepareSchedule($schedules);
        $options = $request->session()->has('route_options') ? $request->session()->get('route_options') : false;
        $trip = Schedule::prepareTrip($schedules, $options);
        if (count($schedules) > 0) {

			$client = new Client();
	        $res = $client->request('POST', 'https://api.routific.com/v1/vrp', [
	        	'headers' => [
	        		'Content-Type' => 'application/json',
	        		'Authorization' => 'bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1N2Q4ODdjZTJjOGE5MGZhNmMyNDY2YTAiLCJpYXQiOjE0NzM4MDgzMzR9.G-wRJ7Prih7MXp15vUv6T_mqDSd-nvzPnR4OA9PzjbY'
	        	],
	            'json' => $trip 
	        ]);
	        $body = json_decode($res->getBody()->read(1024));
	        $delivery_route = Schedule::prepareRouteForView($body,$active_list);
    	} else {
    		$body = false;
    		$delivery_route = false;
    	}
        $approved = Schedule::where('dropoff_date',$today)
        					   ->orWhere('pickup_date',$today)
        					   ->whereIn('status',[3,12])
        					   ->orderBy('id','desc')->get();
       	$approved_list = Schedule::prepareSchedule($approved);
        $delayed = Schedule::where('dropoff_date',$today)
        					   ->orWhere('pickup_date',$today)
        					   ->whereIn('status',[7,8,9,10])
        					   ->orderBy('id','desc')->get();
       	$delayed_list = Schedule::prepareSchedule($delayed);

       	$traffic = [
       		'very slow' => 'Very Slow',
       		'slow' => 'Slow',
       		'normal' => 'Normal',
       		'fast' => 'Fast',
       		'faster' => 'Faster'
       	];

       	$shortest_distance = [
       		'false' => 'Shortest Time',
       		'true' => 'Shortest Distance'
       	];

       	$traffic_selected = ($options) ? $options['traffic'] : 'slow';
       	$shortest_distance_selected = ($options) ? $options['shortest_distance'] : 'false';
       	$route_options_header = ($options) ? ($options['shortest_distance'] == 'false') ? 'Route Optimized For Time' : 'Route Optimized For Distance' : 'Route Optimized For Time';


        return view('schedules.delivery_route')
        ->with('layout',$this->layout)
        ->with('schedules',$delivery_route)
        ->with('approved_list',$approved_list)
        ->with('delayed_list',$delayed_list)
        ->with('delivery_date',date('D m/d/Y',strtotime($today)))
        ->with('traffic',$traffic)
        ->with('shortest_distance',$shortest_distance)
        ->with('traffic_selected',$traffic_selected)
        ->with('shortest_distance_selected',$shortest_distance_selected)
        ->with('route_options_header',$route_options_header)
        ->with('travel_data',$body);    	
    }

    public function postDeliveryRoute(Request $request) {
    	$this->validate($request, [
            'search' => 'required'
        ]);

        $request->session()->put('delivery_date',date('Y-m-d 00:00:00',strtotime($request->search)));

        return Redirect::route('schedules_delivery_route');
    }

    public function postApprovePickup(Request $request){
    	$next_status = 2;

    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;

    	if ($schedules->save()) {
    		Flash::success('Updated #'.$request->id.' to "En-route to pickup"');
    		return Redirect::route('schedules_checklist');
    	}

    }
    public function postApproveDropoff(Request $request){
    	$next_status = 5;
    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;

    	if ($schedules->save()) {
    		Flash::success('Updated #'.$request->id.' to "En-route to dropoff"');
    		return Redirect::route('schedules_checklist');
    	}
    }
    public function postRevertPickup(Request $request){
    	$next_status = 1;

    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;

    	if ($schedules->save()) {
    		Flash::success('Reverted #'.$request->id.' to "Delivery Scheduled"');
    		return Redirect::route('schedules_checklist');
    	}

    }
    public function postRevertDropoff(Request $request){
    	$next_status = 4;
    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;

    	if ($schedules->save()) {
    		Flash::success('Reverted #'.$request->id.' to "Processing"');
    		return Redirect::route('schedules_checklist');
    	}
    }

    public function postEmailEnroute(Request $request) {
    	$delivery_date = date('Y-m-d 00:00:00', strtotime($request->delivery_date));

    }
    public function postApprovePickedup(Request $request){
    	$next_status = 3;
    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;

    	if ($schedules->save()) {
    		Flash::success('Updated #'.$request->id.' to "Picked Up"');
    		return Redirect::route('schedules_delivery_route');
    	}
    }
    public function postApproveDelivered(Request $request){
    	$next_status = 5;
    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;
    	// email customer


    	// finish status change
    	if ($schedules->save()) {
    		Flash::success('Updated #'.$request->id.' to "Delivered"');
    		return Redirect::route('schedules_delivery_route');
    	}
    }

    public function postRouteOptions(Request $request) {
    	$request->session()->put('route_options',[
			"traffic" => $request->traffic,
			"min_visits_per_vehicle"=> 1,
			"balance"=> 'true',
			"min_vehicles"=> 'true',
			"shortest_distance"=> $request->shortest_distance
    	]);

    	Flash::success('Updated route settings!');
    	return Redirect::route('schedules_delivery_route');
    }

    public function getProcessing(Request $request) {

    }
}
