<?php

namespace App\Http\Controllers;


use App\ZipcodeRequest;

use Illuminate\Http\Request;
use App\Http\Requests;

class ZipcodeRequestsController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.dropoff';

    }  

    public function getIndex() {
    	$zipcodes = ZipcodeRequest::where('status','<',3)->orderBy('id','desc')->get();
        return view('zipcode_requests.index')
        ->with('zipcodes',$zipcodes)
        ->with('layout',$this->layout);   	
    }
	public function getRequestData() {
		$zipcodes = ZipcodeRequest::where('status','<',3)->get();

		$l = [];
		if (count($zipcodes) > 0) {
			foreach ($zipcodes as $zipcode) {
				$l[$zipcode->zipcode] = $zipcode->zipcode;
			}
		}
		ksort($l);
		$label = [];
		if (count($l) > 0) {
			foreach ($l as $key => $value) {
				array_push($label,$value);
			}
		}
		$datasets = [];
		$data = [];
		$background_colors = [];
		if (count($label) > 0) {

			$idx = -1;
			foreach ($label as $key => $value) {
				$idx++;
				$search = ZipcodeRequest::where('zipcode',$value)->get();
				array_push($data,count($search));
				array_push($background_colors, ZipcodeRequest::getBackgroundColor($idx));
			}
			$datasets =['data'=>$data,'backgroundColor'=>$background_colors];
		}

        return response()->json(['labels'=>$label,
                                 'datasets'=>$datasets]);

    }

}
