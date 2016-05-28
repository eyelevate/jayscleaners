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
use App\Admin;
use App\Layout;
use App\Company;
use App\Passmanage;
use App\Invoice;
use App\InvoiceItem;
use App\Inventory;
use App\InventoryItem;
use App\Color;
use App\Delivery;
use App\Schedule;
use App\Tax;
use App\Transaction;
// use App\Role;
// use App\RoleUser;
// use App\Permission;
// use App\PermissionRole;

class AdminsController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }
    
    public function getIndex() {

        return view('admins.index')
        ->with('layout',$this->layout);
    }

    public function getLogin() {
        $this->layout = 'layouts.admin_login';
    	return view('admins.login')
    	->with('layout',$this->layout);
    }

    public function postLogin() {
        $this->layout = 'layouts.admin_login';
        $username = Input::get('username');
        $password = Input::get('password');
        $remember = Input::get('remember');

        if($remember) { // If user requests to be remembered create session
            if (Auth::attempt(['username' => $username, 'password' => $password], $remember)) {
                Flash::success('Welcome back '.$username.'!');

                //redirect to intended page
                return (Session::has('intended_url')) ? Redirect::to(Session::get('intended_url')) : redirect()->intended('/admins');
            } else { //LOGING FAILED
                Flash::error('Wrong Username or Password!');
                return view('admins.login')
                ->with('layout',$this->layout);
            }   
        } else {
            if (Auth::attempt(['username'=>$username, 'password'=>$password])) {
                Flash::success('Welcome back '.$username.'!');

                return (Session::has('intended_url')) ? Redirect::to(Session::get('intended_url')) : redirect()->intended('/admins');
            } else { //LOGING FAILED
                Flash::error('Wrong Username or Password!');
                return view('admins.login')
                ->with('layout',$this->layout);
            }   
        }
     
    }

    public function postLogout() {
        Auth::logout();
        Flash::success('You have successfully been logged out');
        return Redirect::action('AdminsController@getLogin');

    }

    public function getOverview(){

        $admins = Admin::prepareAdmin(User::where('role_id',1)->orderBy('last_name', 'asc')->get());

        return view('admins.overview')
        ->with('layout',$this->layout)
        ->with('admins',$admins);

    }

    public function getAdd(){
        $this->layout = 'layouts.admin_login';
        $companies = [''=>'Select A Location',1=>'Montlake',2=>'Roosevelt'];

        return view('admins.add')
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
        $users->role_id = 1; //Admin status
        $users->email = $request->email;
        $users->phone = $request->phone;
        $users->password = bcrypt($request->password);


        if ($users->save()) {
             Flash::success('Successfully added!');
             return Redirect::route('admins_login');
        }
    }

    public function getEdit($id = null){
        $user = User::find($id);
        $companies = [''=>'Select A Location',1=>'Montlake',2=>'Roosevelt'];

        return view('admins.edit')
        ->with('layout',$this->layout)
        ->with('companies',$companies)
        ->with('user',$user);
    }

    public function postEdit(Request $request){
        //Validate the request
        $this->validate($request, [
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'phone'=>'required|min:10',
            'password' => 'required|confirmed|min:6',
            'company_id'=>'required'
        ]);

        // Validation has passed save data
        $users = User::find($request->id);
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->role_id = 1; //Admin status
        $users->contact_phone = $request->phone;
        $users->company_id = $request->company_id;
        $users->password = bcrypt($request->password);

        if ($users->save()) {
             Flash::success('Successfully updated admin!');
             return Redirect::route('admins_overview');
        }
    }

    public function getSettings(){
         return view('admins.settings')
        ->with('layout',$this->layout);       
    }

    public function getView($id = null){
        $this->layout = 'admins.login';
        // update the invoice to make invoice items
        // $invoices = Invoice::whereBetween('id', [58482, 60000])->get();
        // if($invoices){
        //     foreach ($invoices as $invoice) {
        //         $invoice_id = $invoice['id'];
        //         $customer_id = $invoice['customer_id'];
        //         $company_id = $invoice['company_id'];
        //         $taxes = Tax::where('company_id',$company_id)->where('status',1)->first();
        //         $tax_rate = $taxes['rate'];
        //         $items = json_decode($invoice['items']);
        //         if($items){
        //             foreach ($items as $key => $value) {
        //                 $item_id = $key;
        //                 $item_qty = $value->quantity;
        //                 for ($i=0; $i < $item_qty; $i++) { 
        //                     $invoice_item = new InvoiceItem;
        //                     $invoice_item->invoice_id = $invoice_id;
        //                     $invoice_item->item_id = $item_id;
        //                     $invoice_item->customer_id = $customer_id;
        //                     $invoice_item->company_id = $company_id;
        //                     $invoice_item->quantity = 1; 
        //                     $inventory_items = InventoryItem::find($item_id);
        //                     if ($inventory_items) {
        //                         $invoice_item->pretax = money_format('%i',round($inventory_items->price,2));
        //                         $invoice_item->tax = money_format('%i',round($inventory_items->price * $tax_rate,2));
        //                         $invoice_item->total = money_format('%i',round($inventory_items->price * (1+$tax_rate),2));
        //                         $invoice_item->status = 1; 
        //                         if($invoice_item->save()){
        //                             Job::dump($invoice_id.' - '.$invoice_item->id);
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        #update transactions
        // $transactions = Transaction::whereBetween('id', [23582,25000])->get();
        // if ($transactions) {
        //     foreach ($transactions as $transaction) {
        //         $transaction_id = $transaction->id;
        //         $trans_invoices = json_decode($transaction->invoices);
        //         if ($trans_invoices){
        //             foreach ($trans_invoices as $key => $value) {
        //                 $invoice_id = ($value->invoice_id) ? $value->invoice_id : false;
        //                 if ($invoice_id) {
        //                     $invoices = Invoice::find($invoice_id);
        //                         if($invoices){
        //                         $invoices->transaction_id = $transaction_id;
        //                         if($invoices->save()){
        //                             Job::dump('saved '.$transaction_id.' = '.$invoice_id);
        //                         } else {
        //                             Job::dump('could not save '.$transaction_id.' no such invoice - '.$invoice_id);
        //                         }
        //                     }

        //                 }

        //             }
        //         }
        //     }
        // }


        return view('admins.view')
        ->with('layout',$this->layout);
    }

    public function getApiUpdate($id = null, $api_token = null, $server_at = null, $up = null){
        $server_at = date('Y-m-d H:i:s', $server_at);

        $up = json_decode(str_replace('up=', '', $up),true);
        $authenticate = Company::where('id',$id)->where('api_token',$api_token)->first();

        if ($authenticate){
            // create items to return
            $updates = Admin::makeUpdate($authenticate,$server_at);

            // create list of items with new ids to save in local db
            $uploads = Admin::makeUpload($authenticate,$up);
    

            return response()->json(['status'=>200,
                                     'rows_to_create'=>$updates[1],
                                     'updates'=>$updates[0],
                                     'rows_saved'=>$uploads[0],
                                     'saved'=>$uploads[1],
                                     'server_at'=>$server_at
                                     ]);
    
        } 
        return abort(403, 'Unauthorized action.');
    }

    public function getAuthentication($username = null, $pw = null) {
        
        if (Auth::attempt(['username'=>$username, 'password'=>$pw])) {
            
            // Next check to see if the role is an admin or an employee
            $role = Auth::user()->role_id;
            if ($role < 3){
                $status = true;

                return response()->json(['status'=>true,
                                         'company_id'=>Auth::user()->company_id,
                                         'user_id'=>Auth::user()->id
                                         ]);
            } 


        } 

        // If not authorized then return false
        return response()->json(['status'=>false]);

    }

    public function getApiPassmanage($id = null, $api_token = null, $up = null){
        $authenticate = Company::where('id',$id)->where('api_token',$api_token)->first();
        if ($authenticate){

        }
    }

    public function getApiChunk($id = null, $api_token = null, $table = null, $start = null, $end = null) {
        $data = [];
        $authenticate = Company::where('id',$id)->where('api_token',$api_token)->first();
        if ($authenticate){
            switch ($table) {
                case 'colors':
                    $data[$table] = Color::whereBetween('id',[$start,$end])->get();
                    break;
                case 'companies':
                    $data[$table] = Company::whereBetween('id',[$start,$end])->get();
                    break;
                case 'deliveries':
                    $data[$table] = Delivery::whereBetween('id',[$start,$end])->get();
                    break;
                case 'invoices':
                    $data[$table] = Invoice::whereBetween('id',[$start,$end])->get();
                    break;
                case 'transactions':
                    $data[$table] = Transaction::whereBetween('id',[$start,$end])->get();
                    break;
                case 'invoice_items':
                    $data[$table] = InvoiceItem::whereBetween('id',[$start,$end])->get();
                    break;
                case 'schedules':
                    $data[$table] = Schedule::whereBetween('id',[$start,$end])->get();
                    break;
                case 'users':
                    $data[$table] = User::whereBetween('id',[$start,$end])->get();
                    break;                    
            }
            return response()->json(['status'=>true,'rows_to_create'=>1,'updates'=>$data]);
        } else{
            return response()->json(['status'=>false]);
        }
    }



}
