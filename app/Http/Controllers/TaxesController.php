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
use App\Company;
use App\Tax;
use App\Layout;

class TaxesController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }

    public function getIndex(){
    	$companies = Company::getCompany();
    	$tax = Tax::prepareTax(Tax::where('status',1)->first());
    	$history = Tax::where('id','>',0)->orderBy('id','desc')->get();
    	
    	return view('taxes.index')
    	->with('layout',$this->layout)
    	->with('companies',$companies)
    	->with('tax',$tax)
    	->with('history',$history);
    }


    public function postUpdate(Request $request){
    	// First set all tax statuses to 2
    	Tax::where('id','>',0)->update(['status' => 2]);
    	// insert new tax row with status 1 to show that this is the current tax rate.
         //Validate the request
        $this->validate($request, [
            'rate' => 'required|min:1',
            'company_id'=>'required'
        ]);   

        $tax = new Tax();
        $tax->company_id = $request->company_id;
        $tax->rate = $request->rate;
        $tax->status = 1;
        if($tax->save()){
			Flash::success('Successfully updated sales tax!');
			return Redirect::route('taxes_index');
        }   	
    }

}
