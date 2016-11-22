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
use App\Account;
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

    public function postIndex(Request $request) {
        $this->validate($request, [
            'search' => 'required',
        ]);

        $search = $request->search;
        // check number or else
        if (is_numeric($search)) { # phone, customer_id, transaction_id
            $phones = User::where('phone','like','%'.$search.'%')
                ->where('account',1);
            $users = User::where('id',$search)
                ->where('account',1)
                ->union($phones)
                ->orderBy('last_name','asc')
                ->get();
        } else {
            $users = User::where('last_name','like','%'.$search.'%')
                ->where('account',1)
                ->orderBy('last_name','asc')
                ->get();
        }

        $formatted = Account::prepareAccount($users);

        return view('accounts.index')
        ->with('layout',$this->layout)
        ->with('customers',$users);
    }

    public function getPay($id = null) {

    }

    public function postPay(Request $request) {

    }

    public function getHistory($id = null) {

    }
}
