<?php

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

class DiscountsController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.dropoff';

    }

    public function getIndex(){

    }

    public function getAdd() {

    }

    public function postAdd(Request $request) {

    }

    public function getEdit($id = null) {

    }

    public function postEdit(Request $request) {

    }
}
