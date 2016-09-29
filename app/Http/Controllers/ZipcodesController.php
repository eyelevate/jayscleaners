<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Job;
use App\Req;
use App\Zipcode;

class ZipcodesController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.frontend_basic';

    }  

    public function getIndex() {

    }

    public function getRequest($id = null){
    	$zipcodes = Zipcode::getAllZipcodes();

        return view('zipcodes.request')
        ->with('zipcode',$id)
        ->with('layout',$this->layout);
    }

    public function postRequest(Request $request) {
    	$user_ip = Job::getUserIP();
    	$zipcode = $request->zipcode;
    	$full_name = $request->full_name;
    	$comment = $request->comment;
    	$email = $request->email;

    	$zipcode_requests = new Req();
    	$zipcode_requests->ip = $user_ip;
    	$zipcode_requests->zipcode = $zipcode;
    	$zipcode_requests->full_name = $full_name;
    	$zipcode_requests->comment = $request->comment;
    	$zipcode_requests->email = $request->email; 
    }
}
