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
        $summaries = Report::prepareGlimpse();
        $drops = Report::prepareDropoffGlimpse();


        $start_date = date('Y-m-d 00:00:00');
        $end_date = date('Y-m-d 23:59:59');
        $invoices = Invoice::whereBetween('created_at',[$start_date,$end_date])->where('company_id',2)->get();
        if (count($invoices) > 0) {
            foreach ($invoices as $inv) {
                Job::dump($inv->id.' -- '.$inv->customer_id.' -- '.$inv->created_at.' -- '.$inv->updated_at.' -- ');
                // $ii = Invoice::find($inv->id);
                // if ($ii->delete()) {
                //     Job::dump($inv->id.' -- '.$inv->customer_id.' -- '.$inv->created_at.' -- '.$inv->updated_at.' -- Deleted');
                // }
                
            }
        }

        return view('reports.index')
        ->with('layout',$this->layout)
        ->with('dates',$dates)
        ->with('summaries',$summaries)
        ->with('drops',$drops)
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

        $start_date = date('D n/d/Y',$start);
        $end_date = date('D n/d/Y',$end);

        $reports = Report::prepareQueryReport($start, $end, $company_id);
        return view('reports.make')
        ->with('layout',$this->layout)
        ->with('reports',$reports)
        ->with('start_date',$start_date)
        ->with('end_date',$end_date)
        ->with('reports',$reports);

    }

    public function getView($start = null, $end = null, $company_id = null) {

        $start_date = date('D n/d/Y',$start);
        $end_date = date('D n/d/Y',$end);

        $reports = Report::prepareInvoiceReport($start, $end, $company_id);
        return view('reports.make')
        ->with('layout',$this->layout)
        ->with('reports',$reports)
        ->with('start_date',$start_date)
        ->with('end_date',$end_date)
        ->with('reports',$reports);

    }
}
