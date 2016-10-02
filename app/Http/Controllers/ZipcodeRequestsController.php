<?php

namespace App\Http\Controllers;

use App\ZipcodeList;
use Mail;
use App\ZipcodeRequest;
use Input;
use Validator;
use Redirect;
use Hash;
use Route;
use Response;
use Auth;
use App\Job;
use URL;
use Session;
use Laracasts\Flash\Flash;
use View;
use Illuminate\Http\Request;
use App\Http\Requests;

class ZipcodeRequestsController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.dropoff';

    }  

    public function getIndex() {
    	$zipcodes = ZipcodeRequest::where('status',1)->orderBy('id','desc')->get();
    	$request_dataset = ZipcodeRequest::getRequestDataset();
        return view('zipcode_requests.index')
        ->with('zipcodes',$zipcodes)
       	->with('requests',$request_dataset)
        ->with('layout',$this->layout);   	
    }
	public function getRequestData() {
		$request_dataset = ZipcodeRequest::getRequestDataset();

        return response()->json(['labels'=>$request_dataset['labels'],
                                 'datasets'=>$request_dataset['datasets']]);

    }

    /**
    * status check
    * 1. Active
    * 2. Deny
    * 3. Accept
    **/

    public function postAccept(Request $request) {
    	$status = 3;

    	$zipcode_requests = ZipcodeRequest::where('zipcode',$request->zipcode)->where('status',1)->get();
    	$emails = [];
    	$zip = $request->zipcode;
    	// add zipcode to list
    	$zipcodes = new ZipcodeList();
    	$zipcodes->zipcode = $request->zipcode;
    	$zipcodes->status = 1;
    	if ($zipcodes->save()) {
	    	// update zipcodes requests and send email
	    	if (count($zipcode_requests) > 0) {
	    		foreach ($zipcode_requests as $zr) {
	    			$zip_req = ZipcodeRequest::find($zr->id);
	    			$zip_req->status = $status;
	    			if ($zip_req->save()) {
	    				$emails[$zr->email] = $zr->email;
	    			}
	    			
	    		}
	    	}

	    	$send_emails = [];
	    	if (count($emails)) {
	    		foreach ($emails as $key => $value) {
	    			array_push($send_emails,$key);
	    		}
	    	}

	    	// send emails 
	    	$message = $request->message != '' ? $request->message : false;
	    	$zip = $request->zipcode;
			$send_to = $send_emails;
	        $from = 'noreply@jayscleaners.com';

	        // Email customer
	        if (Mail::send('emails.accept_zipcode', [
	        	'zipcode' => $zip
	        ], function($message) use ($send_to, $zip)
	        {
	            $message->to('onedough83@gmail.com');
	            $message->subject('We are now delivering to your zipcode - '.$zip.'!');
	        })) {
	            // redirect with flash
	            Flash::success('Successfully accepted a new zipcode to delivery routes');
	        } else {
	            Flash::success('Successfully accepted a new zipcode to delivery routes, however, we could not successfully send email confirmations.');
	        }
    	} 
    	return Redirect::back();
    }

    public function postDeny(Request $request) {
    	$status = 2;
    	$zipcode_requests = ZipcodeRequest::where('zipcode',$request->zipcode)->where('status',1)->get();
    	$emails = [];
    	$zip = $request->zipcode;
    	// add zipcode to list
    	// update zipcodes requests and send email
    	if (count($zipcode_requests) > 0) {
    		foreach ($zipcode_requests as $zr) {
    			$zip_req = ZipcodeRequest::find($zr->id);
    			$zip_req->status = $status;
    			if ($zip_req->save()) {
    				$emails[$zr->email] = $zr->email;
    			}
    		}
    	}
    	Flash::success('Successfully removed zipcode from request list.');
    	return Redirect::back();
    }

}
