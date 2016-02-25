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
use App\Customer;
use App\Layout;

class CustomersController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }

    public function getIndex($query = null){
    	return view('customers.index')
    	->with('layout',$this->layout)
    	->with('query',$query);
    }

    public function postIndex(Request $request){
        //Validate the request
        $this->validate($request, [
            'search_query' => 'required'
        ]);    

        // Search has validated start searching
        $results = Customer::prepareResults($request->search_query);
        if($results['status'] == true) {
	        switch($results['redirect']) {
	        	case 'customers_index_post':
			    	return view('customers.index')
			    	->with('customers',$results['data'])
			    	->with('layout',$this->layout);

	        	break;

	        	case 'customers_view': // found customer
	        		return Redirect::route('customers_view',$results['param']);
	        	break;

	        	case 'invoices_index_post':

	        	break;

	        	case 'invoices_view':

	        	break;
	        }
        } else {
        	Flash::error('Could not find customer or invoice. Please try again!');
	        return Redirect::back();
        }



    }

    public function getAdd(){
    	$companies = [''=>'Select A Location',1=>'Montlake',2=>'Roosevelt'];


    	return view('customers.add')
    	->with('layout',$this->layout)
    	->with('companies',$companies);
    }

    public function postAdd(Request $request){
        //Validate the request
        $this->validate($request, [
            'username' => 'required|unique:users|max:255',
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'phone'=>'required|min:10',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'company_id'=>'required'
        ]);

        // Validation has passed save data
        $users = new User;
        $users->username = $request->username;
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->role_id = 3; //Customer status
        $users->email = $request->email;
        $users->contact_phone = $request->phone;
        $users->password = bcrypt($request->password);
        $users->company_id = Auth::user()->company_id;

        if ($users->save()) {
             Flash::success('Successfully added a new customer!');
             return Redirect::route('customers_view',$users->id);
        }
    }

    public function getEdit($id = null){
    	return view('customers.edit')
    	->with('layout',$this->layout);
    }

    public function postEdit(){

    }

    public function getDelete($id = null){

    }

    public function getHistory($id = null) {

    }

    public function getView($id = null){

    	$customers = User::find($id);
    	return view('customers.view')
    	->with('layout',$this->layout)
    	->with('customers',$customers);


    }

}
