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
use App\Http\Requests;
use App\Job;
use App\Req;
use App\Zipcode;
use App\ZipcodeRequest;
use App\ZipcodeList;
use App\Delivery;

class ZipcodesController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.frontend_basic';

    }  

    public function getIndex() {
        $zipcodes = ZipcodeList::where('id','>',0)->orderBy('zipcode','ASC')->get();
        $this->layout = 'layouts.dropoff';
        return view('zipcodes.index')
        ->with('zipcodes',$zipcodes)
        ->with('layout',$this->layout);
    }

    public function getAdd() {
        $this->layout = 'layouts.dropoff';
        return view('zipcodes.add')
        ->with('layout',$this->layout);
    }

    public function postAdd(Request $request) {
        $this->validate($request, [
            'zipcode' => 'required|unique:zipcode_lists',
        ]);       

        $zl = new ZipcodeList(); 
        $zl->zipcode = $request->zipcode;
        $zl->status = 1;
        if ($zl->save()) {
            Flash::success('Added a new zipcode to the delivery route!');
            return Redirect::route('zipcodes_index');
        }
    }

    public function getEdit($id = null) {
        $this->layout = 'layouts.dropoff';
        $zipcodes = ZipcodeList::find($id);
        $deliveries = Delivery::prepareSelect(Delivery::all());
        $edits = Zipcode::where('zipcode',$zipcodes->zipcode)->get();


        return view('zipcodes.edit')
        ->with('layout',$this->layout)
        ->with('deliveries',$deliveries)
        ->with('zipcodes',$zipcodes);
    }

    public function postEdit(Request $request) {
        $this->validate($request, [
            'zipcode' => 'required',
        ]);       

        $zl = ZipcodeList::find($request->id); 
        $zl->zipcode = $request->zipcode;
        if ($zl->save()) {
            Flash::success('Edited zipcode.');
            return Redirect::route('zipcodes_index');
        }
    }

    public function getDelete($id = null) {

    }

    public function getRequest($id = null){
    	$zipcodes = Zipcode::getAllZipcodes();
        return view('zipcodes.request')
        ->with('zipcode',$id)
        ->with('layout',$this->layout);
    }

    public function postRequest(Request $request) {
        $this->validate($request, [
            'zipcode' => 'required',
            'name'=>'required',
            'email'=>'email'
        ]);
    	$user_ip = Job::getUserIP();
    	$zipcode = $request->zipcode;
    	$full_name = $request->name;
    	$comment = $request->comment;
    	$email = $request->email;

        $check_list = ZipcodeList::where('zipcode',$zipcode)->get();
        if (count($check_list) == 0) {
            $zipcode_requests = new ZipcodeRequest();
            $zipcode_requests->ip = $user_ip;
            $zipcode_requests->zipcode = $zipcode;
            $zipcode_requests->name = $full_name;
            $zipcode_requests->comment = $request->comment;
            $zipcode_requests->email = $request->email; 
            $zipcode_requests->status = 1;
            if ($zipcode_requests->save()) {
                Flash::success('Thank you. We will review this request and email you a response. In the meanwhile feel free to give us a call or come stop by for a visit!');
                return Redirect::route('pages_index');
            }
        } else {
            Flash::error('The zipcode ('.$zipcode.') already exists. Please request another zipcode and try again.');
            return Redirect::back();
        }

    }
}
