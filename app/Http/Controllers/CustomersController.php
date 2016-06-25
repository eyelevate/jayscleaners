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
use App\Invoice;
use App\InvoiceItem;

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
    	$companies = Company::getCompany();
    	$starch = Company::getStarch();
    	$hanger = Company::getShirt();
    	$delivery = Company::getDelivery();
    	$account = Company::getAccount();

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
            'phone'=>'required|min:10|unique:users',
            'email' => 'email|max:255|unique:users',
            'company_id'=>'required',
            'delivery'=>'required',
            'account'=>'required'
        ]);

        // Validation has passed save data
        $users = new User;
        $users->company_id = $request->company_id;
        $last_user_id = User::where('company_id',$request->company_id)->orderBy('id','desc')->limit('1')->pluck('id');
        $users->user_id = ($last_user_id[0]) ? $last_user_id[0]+1 : 1;
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
        $users->role_id = 5; //Customer status
          

        if ($users->save()) {
        	// Create Shirt Mark
        	$custid = new Custid;
        	$custid->customer_id = $users->id;
        	$custid->mark = Custid::createOriginalMark($users);
        	$custid->status = 1; // approved

        	if($custid->save()) {
				Flash::success('Successfully added a new customer!');
				return Redirect::route('customers_view',$users->id);
        	}
        }
    }

    public function getEdit($id = null){
    	$customers = User::find($id);
    	$marks = Custid::where('customer_id',$id)->get();
    	$companies = Company::getCompany();
    	$starch = Company::getStarch();
    	$hanger = Company::getShirt();
    	$delivery = Company::getDelivery();
    	$account = Company::getAccount();

    	return view('customers.edit')
    	->with('layout',$this->layout)
    	->with('customers',$customers)
    	->with('marks',$marks)
    	->with('companies',$companies)
    	->with('starch',$starch)
    	->with('hanger',$hanger)
    	->with('delivery',$delivery)
    	->with('account',$account);
    }

    public function postEdit(Request $request){
        //Validate the request
        $this->validate($request, [
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'phone'=>'required|min:10',
            'email' => 'email|max:255',
            'company_id'=>'required',
            'delivery'=>'required',
            'account'=>'required'
        ]);

        // Validation has passed save data
        $users = User::find($request->customer_id);
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
            #@TODO double check to see if this is how we want to do this
        	// Update Shirt Marks
        	$marks = Input::get('marks');
			$mark1_id = preg_replace('/\s+/', '', $marks[1]['id']);
			$mark1_mark = preg_replace('/\s+/', '', $marks[1]['mark']);
			$mark2_id = preg_replace('/\s+/', '', $marks[2]['id']);
			$mark2_mark = preg_replace('/\s+/', '', $marks[2]['mark']);
        	// If there is an id
			if( $mark1_id !== '' && $mark1_mark == '') { // softdelete the row
        		$custid1 = Custid::find($mark1_id);
        		$custid1->delete(); // Soft delete
        	} else { // save 
        		$custid1 = ($mark1_id !== '' && $mark1_mark !== '') ? Custid::find($mark1_id) : new Custid;
	        	$custid1->customer_id = $users->id;
	        	$custid1->mark = $mark1_mark;
	        	$custid1->status = 2; // approved
	        	$custid1->save();
				
        	}
        	// If there is an id
			if( $mark2_id !== '' && $mark2_mark == '') { // softdelete the row
        		$custid2 = Custid::find($mark2_id);
        		$custid2->delete(); // Soft delete
        	} else { // save 
        		$custid2 = ($mark2_id !== '' && $mark2_mark !== '') ? Custid::find($mark2_id) : new Custid;
	        	$custid2->customer_id = $users->id;
	        	$custid2->mark = $mark2_mark;
	        	$custid2->status = 2; // approved
	        	$custid2->save();
				
        	}

			Flash::success('Successfully updated customer!');
			return Redirect::route('customers_view',$users->id);

        }
    }

    public function getDelete($id = null){

    }

    public function getHistory($id = null) {

    }

    public function getView(Request $request, $id = null){
    	$user = User::find($id);
        if ($user){
            $customers = Customer::prepareView($user);
            $last10 = Customer::prepareLast10($user, $request->session()->get('last10'));
            $request->session()->put('last10',$last10);
            $invoices = Invoice::prepareInvoice(Auth::user()->company_id,Invoice::where('customer_id',$id)->where('status',1)->orderBy('id','desc')->get());

            return view('customers.view')
            ->with('layout',$this->layout)
            ->with('customers',$customers)
            ->with('last10',$last10)
            ->with('invoices',$invoices);
        } else {
            return view('customers.view')
            ->with('layout',$this->layout)
            ->with('customers',[])
            ->with('last10',$request->session()->get('last10'))
            ->with('invoices',[]);            
        }



    }

}
