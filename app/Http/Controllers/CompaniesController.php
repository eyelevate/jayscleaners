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

    }

    public function getEdit($id = null){
    	return view('companies.edit')
    	->with('layout',$this->layout);
    }

    public function postEdit(Request $request){

    }

    public function postDelete(Request $request){

    }
}
