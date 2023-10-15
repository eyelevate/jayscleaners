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
use App\Custid;
use App\Company;

class UsersController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }  
    public function getIndex() {

        return view('users.index')
        ->with('layout',$this->layout);
    }

    public function getUpdate() {
        $customer_id = Auth::user()->id;
        $customers = User::find($customer_id);
        $companies = Company::getCompany();
        $starch = Company::getStarch();
        $hanger = Company::getShirt();

      
        $this->layout = 'layouts.frontend_basic';
        return view ('users.update')
        ->with('layout',$this->layout)
        ->with('customers',$customers)
        ->with('companies',$companies)
        ->with('starch',$starch)
        ->with('hanger',$hanger);
    }

    public function postUpdate(Request $request) {
        $this->validate($request, [
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'phone'=>'required|min:10',
            'email' => 'email|max:255'
        ]);
        // save
        $users = User::find(Auth::user()->id);
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->phone = $request->phone;
        $users->email = $request->email;
        $users->starch = $request->starch;
        $users->shirt = $request->hanger;
        // if password is entered in check for password
        $password = $request->password;
        if ($password) {
            if ($users->password != $users->password_confirmation) {
                Flash::error('Passwords do not match. Please try again.');
                return Redirect::back();
            }
            $users->password = bcrypt($password);
        }

        if ($users->save()) {
            Flash::success('Successfully updated your user information!');
            return Redirect::route('pages_index');
        }
        Flash::error('There were problems saving your form. Please try again.');
        return Redirect::back();

    }

    public function postIndex(Request $request) {


    }

    public function postApiNoDuplicateCreate(Request $request) {
        // make rules that check for duplicate phone_number
        $rules = [
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'phone'=>'required|min:7|max:10|unique:users'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // Check if a user with the same phone number already exists
        if (User::where('phone', $request->phone)->exists()) {
            return response()->json(['phone' => 'Phone number is already in use.'], 422);
        }
        // save
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->starch = $request->starch;
        $user->shirt = $request->shirt;
        $user->role_id = 5;
        $user->company_id = $request->company_id;
//        $users->account = $request->account;
        if($user->save()) {
            $mark = Custid::createOriginalMark($user);
            $custids = new Custid();
            $custids->company_id = $user->company_id;
            $custids->customer_id = $user->id;
            $custids->mark = $mark;
            $custids->status = 1;
            $custids->save();
            $user->load('custids');
            return response()->json($user);
        }

        return response()->error('could_not_create_user', 500);


    }

}

?>