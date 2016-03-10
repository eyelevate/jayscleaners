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
use App\Customer;
use App\Custid;
use App\Layout;


class CompaniesController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }

    public function getIndex(){
    	$companies = Company::all();

    	return view('companies.index')
    	->with('layout',$this->layout)
    	->with('companies',$companies);
    }

    public function getAdd(){
    	return view('companies.add')
    	->with('layout',$this->layout);
    }

    public function postAdd(Request $request){
        //Validate the request
        $this->validate($request, [
            'name' => 'required|min:1',
            'phone'=>'required|min:10',
            'email' => 'email|max:255',
            'street'=>'required|min:1',
            'city'=>'required|min:1',
            'state'=>'required|min:1',
            'zip'=>'required|min:1',
        ]);

        $company = new Company;
        $company->name = $request->name;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->street = $request->street;
        $company->city = $request->city;
        $company->state = $request->state;
        $company->zip = $request->zip;
        if($company->save()){
			Flash::success('Successfully added a new company!');
			return Redirect::route('companies_index');        	
        }
    }

    public function getEdit($id = null){
    	$company = Company::find($id);
    	return view('companies.edit')
    	->with('layout',$this->layout)
    	->with('company',$company);
    }

    public function postEdit(Request $request){
        //Validate the request
        $this->validate($request, [
            'name' => 'required|min:1',
            'phone'=>'required|min:10',
            'email' => 'email|max:255',
            'street'=>'required|min:1',
            'city'=>'required|min:1',
            'state'=>'required|min:1',
            'zip'=>'required|min:1',
        ]);

        $company = Company::find($request->id);
        $company->name = $request->name;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->street = $request->street;
        $company->city = $request->city;
        $company->state = $request->state;
        $company->zip = $request->zip;
        if($company->save()){
			Flash::success('Successfully updated company!');
			return Redirect::route('companies_index');        	
        }
    }

    public function postDelete(Request $request){

    }
    public function getOperation(){
    	$status = Company::prepareStoreHourStatus();
    	$hours = Company::prepareStoreHours();
    	$minutes = Company::prepareMinutes();
    	$ampm = Company::prepareAmpm();
        $turnaround = Company::prepareTurnaround();
        $company = Company::prepareCompany(Company::find(Auth::user()->company_id));
        $dow = Company::prepareDayOfWeek();

    	return view('companies.operation')
    	->with('layout',$this->layout)
    	->with('status',$status)
    	->with('hours',$hours)
    	->with('minutes',$minutes)
    	->with('ampm',$ampm)
        ->with('turnaround',$turnaround)
        ->with('company',$company)
        ->with('dow',$dow);

    }

    public function postOperation(Request $request){

        $company = Company::find($request->id);
        $company->store_hours = json_encode(Input::get('operation'));
        if($company->save()){
			Flash::success('Successfully updated store hours!');
			return Redirect::route('companies_operation');        	
        }
    }
}
