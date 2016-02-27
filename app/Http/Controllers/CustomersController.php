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
    	$companies = [1=>'Montlake',2=>'Roosevelt'];
    	$starch = [1=>'None',2=>'Light',3=>'Medium',4=>'Heavy'];
    	$hanger = [1=>'Hanger',2=>'Box/Folded'];
    	$delivery = [false=>'No', true=>'Yes'];
    	$account = [false=>'No', true=>'Yes'];

    	return view('customers.add')
    	->with('layout',$this->layout)
    	->with('companies',$companies)
    	->with('starch',$starch)
    	->with('hanger',$hanger)
    	->with('delivery',$delivery)
    	->with('account',$account);
    }

    public function postAdd(Request $request){
        //Validate the request
        $this->validate($request, [
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'phone'=>'required|min:10',
            'email' => 'email|max:255|unique:users',
            'company_id'=>'required',
            'delivery'=>'required',
            'account'=>'required'
        ]);

        // Validation has passed save data
        $users = new User;
        $users->company_id = $request->company_id;
        $users->phone = $request->phone;
        $users->last_name = $request->last_name;
        $users->first_name = $request->first_name;
        $users->starch = $request->starch;
        $users->shirt = $request->hanger;
        $users->email = $request->email;
        $users->important_memo = $request->important_memo;
        $users->invoice_memo = $request->invoice_memo;
        $users->delivery = $request->delivery;
        if($request->delivery == '1') {
	        $users->username = $request->username;
	        $users->mobile = $request->mobile;
	        $users->street = $request->street;
	        $users->suite = $request->suite;
	        $users->city = $request->city;
	        $users->zipcode = $request->zipcode;
	        $users->concierge_name = $request->concierge_contact;
	        $users->concierge_number = $request->concierge_number;
	        $users->special_instructions = $request->special_instructions;
        }

        $users->account = $request->account;

        if($request->account == '1') {
			
        }
        $users->role_id = 3; //Customer status
          

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
