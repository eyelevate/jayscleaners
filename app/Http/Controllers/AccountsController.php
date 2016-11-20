<?php

namespace App\Http\Controllers;

use App\Http\Requests;
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
class AccountsController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.dropoff';

    }

    public function getIndex(){
        return view('accounts.index')
        ->with('layout',$this->layout);
    }

    public function getPay($id = null) {

    }

    public function postPay(Request $request) {

    }

    public function getHistory($id = null) {

    }
}
