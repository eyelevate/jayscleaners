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
use App\Invoice;
use App\Layout;
use App\Report;
use App\Transaction;
use App\Zipcode;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Geocoder;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class ReportsController extends Controller
{
    public function __construct() {
        //Set controller variables
    	$this->layout = 'layouts.dropoff';

    }

    public function getIndex() {
    	$dates = Report::prepareDates();
    	$companies = Report::prepareCompanies(Company::all());

        return view('reports.index')
        ->with('layout',$this->layout)
        ->with('dates',$dates)
        ->with('companies',$companies);
    }

    public function postIndex(Request $request) {
    	$this->validate($request, [
            'company_id' => 'required',
            'start'=>'required',
            'end'=>'required'
        ]);

        $start = strtotime(date('Y-m-d 00:00:00',strtotime($request->start)));
        $end = strtotime(date('Y-m-d 23:59:59',strtotime($request->end)));
        $company_id = $request->company_id;

        return Redirect::route('reports_make',[$start,$end,$company_id]);
    }

    public function getMake($start = null, $end = null, $company_id = null) {
    	
    	Job::dump($start);
    	Job::dump($end);
    	Job::dump($company_id);

    }
}
