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
use App\Credit;
use App\Job;
use App\User;
use App\Company;
use App\Customer;
use App\Custid;
use App\Layout;
use App\Invoice;
use App\InvoiceItem;
use App\Schedule;

class CreditsController extends Controller
{

    public function postAdd(Request $request){
        //Validate the request
        $this->validate($request, [
            'amount' => 'required',
            'reason' => 'required',
            'customer_id'=>'required'
        ]);    

        $credits = new Credit();
        $credits->customer_id = $request->customer_id;
        $credits->employee_id = Auth::user()->id;
        $credits->reason = $request->reason;
        $credits->amount = $request->amount;
        $credits->status = 1;
        if ($credits->save()) {
        	$customers = User::find($request->customer_id);
        	$credit_amount = (isset($customers->credits)) ? $customers->credits : 0;
        	$new_credit_amount = $credit_amount + $credits->amount;
        	$customers->credits = $new_credit_amount;
        	if ($customers->save()){
        		Flash::success('Successfully added store credit!');
        	} else {
        		Flash::error("Could not locate customer. Please try again");
        	}
        	return Redirect::back();

        	
        }

    }
}
