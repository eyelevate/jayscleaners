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
use App\Layout;

class CustomersController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }

    public function getIndex($query){
    	return view('customers.index')
    	->with('layout',$this->layout);
    }

    public function postIndex(){

    }

    public function getAdd(){
    	return view('customers.add')
    	->with('layout',$this->layout);
    }

    public function postAdd(){

    }

    public function getEdit($id = null){
    	return view('customers.edit')
    	->with('layout',$this->layout);
    }

    public function postEdit(){

    }

    public function getView($id = null){
    	return view('customers.view')
    	->with('layout',$this->layout);
    }

}
