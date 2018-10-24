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
use Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\User;
use App\Admin;
use App\Address;
use App\Card;
use App\Credit;
use App\Layout;
use App\Company;
use App\Custid;

use App\Passmanage;
use App\Invoice;
use App\InvoiceItem;
use App\Inventory;
use App\InventoryItem;
use App\Color;
use App\Delivery;
use App\Discount;
use App\Memo;
use App\Printer;
use App\Profile;
use App\Reward;
use App\RewardTransaction;
use App\Report;
use App\Schedule;
use App\Tag;
use App\Tax;
use App\Transaction;
use App\Zipcode;
use App\ZipcodeRequest;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
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
    
    public function getIndex(Request $request) {
        $this->layout = 'layouts.admin';
        $role = User::role(Auth::user()->role_id);

        $today_totals = Admin::getTodaysTotals();

        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $zipcode_requests = ZipcodeRequest::where('status',1)->whereBetween('created_at',[$start,$end])->get();
        return view('admins.index')
        ->with('layout',$this->layout)
        ->with('role',$role)
        ->with('today_totals',$today_totals)
        ->with('zipcode_requests',$zipcode_requests)
        ->with('role_id',Session::get('role_id'));
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
            } else { //LOGIN FAILED
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

    public static function getRackHistory(Request $request) {
        $layout = 'layouts.dropoff';

        return view('admins.rack_history')
            ->with('layout',$layout); 
    }

    public static function postRackHistory(Request $request) {
        $layout = 'layouts.dropoff';
        $company_id = $request->company_id;

        $search_dates = $request->search;

        $search_start = date('Y-m-d 00:00:00',strtotime($search_dates));
        $search_end = date('Y-m-d 23:59:59',strtotime($search_dates));

        $history = Invoice::prepareInvoice($company_id,Invoice::whereBetween('rack_date',[$search_start,$search_end])->orderBy('rack_date','asc')->get());
        return view('admins.rack_history')
            ->with('history',$history)
            ->with('company_id',$company_id)
            ->with('search',$search_dates)
            ->with('layout',$layout);
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
        $users->phone = Job::strip($request->phone);
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
        $users->phone = $request->phone;
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

    public function getView(){
        $this->layout = 'admins.login';
        # update invoice to set users table id as customer_id instead of user_id
        // $invoices = Invoice::whereBetween('id',[65001,70000])->get();
        // if ($invoices) {
        //     foreach ($invoices as $invoice) {
        //         $customer_id = $invoice->customer_id;
        //         $customers = User::where('user_id',$customer_id)->get();
        //         if ($customers){
        //             foreach ($customers as $customer) {                   
        //                 $invs = Invoice::find($invoice->id);
        //                 $invs->customer_id = $customer->id;
        //                 if($invs->save()){
        //                     Job::dump('saved invoice - '.$invs->id.' - with customer #'.$customer->id);
        //                     if($invs->transaction_id){
        //                         $transaction = Transaction::find($invs->transaction_id);
        //                         if($transaction){
        //                             $transaction->customer_id = $customer->id;
        //                             if($transaction->save()){
        //                                 Job::dump('Saved Transaction - '.$transaction->id.' - with #'.$customer->id);
        //                             }
        //                         }

        //                     }                            
        //                 }

        //             }
        //         }

        //     }
        // }

        # update the invoice to make invoice items

        // $invoices = Invoice::whereBetween('id', [70001, 72500])->get();
        // if($invoices){
        //     $itemsToInventory = Report::itemsToInventory(1);
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
        //                         $invoice_item->inventory_id = $itemsToInventory[$item_id];
        //                         $invoice_item->pretax = money_format('%i',round($inventory_items->price,2));
        //                         $invoice_item->tax = money_format('%i',round($inventory_items->price * $tax_rate,2));
        //                         $invoice_item->total = money_format('%i',round($inventory_items->price * (1+$tax_rate),2));
        //                         $invoice_item->status = 1; 
        //                         if($invoice_item->save()){
        //                             Job::dump($invoice->id.' - '.$invoice_id.' - '.$invoice_item->id);
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        #update transactions
        // $transactions = Transaction::whereBetween('id', [30001,35000])->get();
        // if ($transactions) {
        //     foreach ($transactions as $transaction) {
        //         $transaction_id = $transaction->id;

        //         $trans_invoices = json_decode($transaction->invoices);
        //         // Job::dump($trans_invoices);
        //         if ($trans_invoices){
        //             foreach ($trans_invoices as $key => $value) {
        //                 $invoice_id = ($value->invoice_id) ? $value->invoice_id : false;
                   
        //                 if ($invoice_id) {
        //                     $invoices = Invoice::where('invoice_id',$invoice_id)->get();
        //                     if($invoices){
        //                         foreach ($invoices as $invoice) {
        //                             $invs = Invoice::find($invoice->id);
        //                             $invs->transaction_id = $transaction_id;
        //                             $invs->status = 5;

        //                             if($invs->save()){
        //                                 Job::dump('saved '.$transaction_id.' = '.$invoice_id);
        //                             } else {
        //                                 Job::dump('could not save '.$transaction_id.' no such invoice - '.$invoice_id);
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        #make custids
        // $users = User::whereBetween('id',[15000,17500])->get();
        // if($users){
        //     foreach ($users as $user) {
        //         $user_id = $user->id;
        //         $mark_base = $user->user_id;
        //         $last_name = $user->last_name;
        //         $hanger_old = $user->shirt_old;
        //         $starch = $user->starch_old;
        //         // switch($hanger_old){
        //         //     case 'hanger':
        //         //         $hanger = 1;
        //         //     break;

        //         //     case 'box':
        //         //         $hanger = 2;
        //         //     break;

        //         //     case 'fold':
        //         //         $hanger = 2;
        //         //     break;

        //         //     default:
        //         //         $hanger = 1;
        //         //     break;
        //         // }
        //         // $us = User::find($user_id);
        //         // $us->shirt = $hanger;
        //         // if($us->save()){
        //         //     Job::dump($hanger);
        //         // }

        //         $mark = Custid::createOriginalMark($user); 
        //         // strtoupper(substr($last_name, 0,1)).$user_id.strtoupper(substr($starch,0,1));
        //         $custids = new Custid();
        //         $custids->customer_id = $user_id;
        //         $custids->company_id = $user->company_id;
        //         $custids->mark = $mark;
        //         $custids->status = 1;
        //         if($custids->save()){
        //             Job::dump($mark);
        //         }

        //     }
        // }

        // SCHEDULE update customer id 
        // $schedules = Schedule::whereBetween('id', [0,5000])->get();
        // if (count($schedules) > 0) {
        //     foreach ($schedules as $schedule) {
        //         $customer_id = $schedule->customer_id;
        //         $users = User::where('user_id',$customer_id)->pluck('id');
        //         if(count($users) > 0) {
        //             foreach ($users as $uid) {
        //                 $scheds = Schedule::find($schedule->id);
        //                 $scheds->customer_id = $uid;
        //                 if ($scheds->save()) {
        //                     Job::dump('updated #'.$schedule->id.' with customer_id #'.$uid);
        //                 }
        //             }
        //         }
        //     }
        // }

        // Transaction only update OPTIONAL
        // $transactions = Transaction::whereBetween('id', [25001,30000])->get();
        // if ($transactions) {
        //     foreach ($transactions as $transaction) {
        //         $user_id = $transaction->customer_id;
        //         # swap out the user id with the id
        //         $users = User::where('user_id',$user_id)->pluck('id');
        //         if (count($users)>0) {
        //             foreach ($users as $uid) {
        //                 $trans = Transaction::find($transaction->id);
        //                 $trans->customer_id = $uid;
        //                 if ($trans->save()) {
        //                     Job::dump('saved id #'.$uid.' to transaction #'.$transaction->id);
        //                 }
        //             }
        //         } 
        //     }
        // }

        # payment profiles and ids
        // $users = User::where('profile_id','>',0)->get();
        // if (count($users)) {
        //     foreach ($users as $user) {
        //         $profile_id = $user->profile_id;
        //         $payment_id = $user->payment_id;
        //         $user_id = $user->id;
        //         $profile = new Profile();
        //         $profile->user_id = $user_id;
        //         $profile->company_id = 1;
        //         $profile->profile_id = $profile_id;
        //         $profile->status = 1;
        //         if ($profile->save()) {
        //             Job::dump('saved profile #'.$profile->id);
        //         }
        //     }
        // }

        # card ids
        // $users = User::where('profile_id','>',0)->get();
        // if (count($users)) {
        //     foreach ($users as $user) {
        //         $profile_id = $user->profile_id;
        //         $payment_id = $user->payment_id;
        //         $user_id = $user->id;
        //         $street = $user->street;
        //         $suite = $user->suite;
        //         $city = $user->city;
        //         $state = $user->state;
        //         $zipcode = $user->zipcode;

        //         $address = new Card();
        //         $address->user_id = $user_id;
        //         $address->company_id = 1;
        //         $address->profile_id = $profile_id;
        //         $address->root_payment_id = $payment_id;
        //         $address->payment_id = $payment_id;
        //         $address->status = 1;
        //         $address->street = $street;
        //         $address->suite = $suite;
        //         $address->city = $city;
        //         $address->state = $state;
        //         $address->zipcode = $zipcode;
        //         if ($payment_id != '') {
        //             if ($address->save()) {
        //                 Job::dump('saved card #'.$address->id);
        //             }
        //         }   
        //     }
        // }

        # remove deleted card_ids from server
        // $cards = Card::all();
        // if (count($cards) > 0) {
        //     $companies = Company::find(1);

        //     foreach ($cards as $card) {
        //         $card_id = $card->id;
        //         $profile_id = $card->profile_id;
        //         $payment_id = $card->payment_id;

        //         // Common setup for API credentials (merchant)
        //         $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        //         $merchantAuthentication->setName($companies->payment_api_login);
        //         $merchantAuthentication->setTransactionKey($companies->payment_gateway_id);
        //         $refId = 'ref' . time();

        //         //request requires customerProfileId and customerPaymentProfileId
        //         $request = new AnetAPI\GetCustomerPaymentProfileRequest();
        //         $request->setMerchantAuthentication($merchantAuthentication);
        //         $request->setRefId( $refId);
        //         $request->setCustomerProfileId($profile_id);
        //         $request->setCustomerPaymentProfileId($payment_id);
        //         $request->setUnmaskExpirationDate(true);

        //         $controller = new AnetController\GetCustomerPaymentProfileController($request);
        //         // $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        //         $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        //         if(($response != null)){
        //             if ($response->getMessages()->getResultCode() != "Ok") {
        //                 $deleting = Card::find($card->id);
        //                 if ($deleting->delete()) {
        //                     Job::dump('deleting #'.$card->id);
        //                 }
                        
        //             } else {
        //                 // expiration_date
        //                 $expiration_date = $response->getPaymentProfile()->getPayment()->getCreditCard()->getExpirationDate();
        //                 $exp_month = date('m',strtotime($expiration_date.'-01 00:00:00'));
        //                 $exp_year = date('Y',strtotime($expiration_date.'-01 00:00:00'));
        //                 $update = Card::find($card->id);
        //                 $update->exp_month = $exp_month;
        //                 $update->exp_year = $exp_year;
        //                 if ($update->save()) {
        //                     Job::dump('Saved exp_month='.$exp_month.' exp_year='.$exp_year.' id='.$update->id);
        //                 }
        //             } 
        //         }
        //     }
        // }


        # address ids
        // $users = User::where('profile_id','>',0)->get();
        // if (count($users)) {
        //     foreach ($users as $user) {
        //         $profile_id = $user->profile_id;
        //         $payment_id = $user->payment_id;
        //         $user_id = $user->id;
        //         $street = $user->street;
        //         $suite = $user->suite;
        //         $city = $user->city;
        //         $state = $user->state;
        //         $zipcode = $user->zipcode;

        //         $address = new Address();
        //         $address->user_id = $user_id;
        //         $address->name = 'Primary Address';
        //         $address->primary_address = 1;
        //         $address->concierge_name = ucFirst($user->first_name).' '.ucFirst($user->last_name);
        //         $address->concierge_number = Job::formatPhoneString($user->phone);
        //         $address->status = 1;
        //         $address->street = $street;
        //         $address->suite = $suite;
        //         $address->city = $city;
        //         $address->state = $state;
        //         $address->zipcode = $zipcode;
        //         if ($street != '') {
        //             if ($address->save()) {
        //                 Job::dump('saved address #'.$address->id);
        //             }
        //         }   
        //     }
        // }

        #schedules fix add in address_id
        // $schedules = Schedule::all();
        // if (count($schedules) > 0) {
        //     foreach ($schedules as $schedule) {
        //         $sch_id = $schedule->id;
        //         $customer_id = $schedule->customer_id;
        //         $addresses = Address::where('user_id',$customer_id)
        //             ->where('primary_address',1)
        //             ->get();
        //         $sch = Schedule::find($sch_id);
        //         $address_id = NULL;
        //         if (count($addresses) > 0) {
        //             foreach ($addresses as $address) {
        //                 $address_id = $address->id;

        //             }
        //         }
        //         $sch->pickup_address = ($schedule->pickup_delivery_id > 0) ? $address_id : NULL;
        //         $sch->dropoff_address = ($schedule->dropoff_delivery_id > 0) ? $address_id : NULL;
                
        //         $cards = Card::where('user_id',$customer_id)->get();
        //         $card_id = NULL;
        //         if (count($cards) > 0) {
        //             foreach ($cards as $card) {
        //                 $card_id = $card->id;
        //             }
        //         }

        //         $sch->card_id = $card_id;

        //         // change status
        //         $dropoff_date = strtotime(date('Y-m-d 23:59:59',strtotime($schedule->dropoff_date)));
        //         $today_date = strtotime(date('Y-m-d H:i:s'));
        //         if ($today_date >= $dropoff_date) {
        //             $sch->status = 12;
        //         }

        //         if ($sch->save()) {
        //             Job::dump('updated schedule #'.$sch->id);
        //         }
        //     }
        // }

        # go through todays transactions, check to see if exists in invoices if not then delete
        // $start = date('Y-m-d 00:00:00');
        // $end = date('Y-m-d 23:59:59');
        // $found = [];
        // $not_found = [];
        // $transactions = Transaction::whereBetween('created_at',[$start,$end])->where('status',1)->get();
        // if (count($transactions) > 0) {
        //     foreach ($transactions as $transaction) {
        //         $transaction_id = $transaction->id;
        //         $invoices = Invoice::where('transaction_id',$transaction_id)->get();
        //         if (count($invoices) > 0) {
        //             array_push($found,$transaction_id);
        //         } else {
        //             array_push($not_found,$transaction_id);
        //         }
        //     }
        // }
        
        // Job::dump($found);
        // Job::dump($not_found);

        // if (count($not_found) > 0) {
        //     foreach ($not_found as $trans_id) {
        //         $trans = Transaction::find($trans_id);
        //         if ($trans->delete()) {
        //             Job::dump('Removed #'.$trans_id);
        //         }
        //     }
        // }

        #check invoices
        // $invoices = Invoice::where('customer_id',NULL)->get();
        // Job::dump($invoices);
        // if (count($invoices) > 0) {
        //     foreach ($invoices as $inv) {
        //         $invs = Invoice::find($inv->id);
        //         if ($invs->delete()) {
        //             Job::dump('deleted invoice #'.$inv->id);
        //         }
        //     }
        // }

        #check invoice items to compare
        // $invoices = Invoice::whereBetween('created_at',['2017-01-03 00:00:00','2017-01-03 23:59:59'])->get();
        // $yes_invoice = [];
        // $no_invoice = [];
        // if (count($invoices) > 0) {
        //     foreach ($invoices as $invoice) {
        //         $invoice_id = $invoice->id;
        //         $iitems = InvoiceItem::where('invoice_id',$invoice_id)->get();
        //         if (count($iitems) > 0) {
        //             array_push($yes_invoice,$invoice_id);
        //         } else {
        //             array_push($no_invoice,$invoice_id);
        //         }
        //     }
        // }
        // if (count($no_invoice) > 0) {
        //     foreach ($no_invoice as $invoice_id) {
        //         $delete = Invoice::find($invoice_id);
        //         if ($delete->delete()) {
        //             Job::dump('deleted empty invoice #'.$invoice_id);
        //         }
        //     }
        // }

        // remove duplicates
        Job::dump('starting data deletion');
        $start = 1;
        $end = 8619;
        $search = User::where('id','<',8260)->get();
        Job::dump(count($search));
        if (count($search) > 0) {
            Job::dump('count search started');
            foreach ($search as $s) {
                $base_phone = $s->phone;
                $haystack = User::where('phone',$base_phone)->where('id','>=',8620)->get();
                if (count($haystack)>0) {
                    Job::dump(count($haystack));
        //             foreach ($haystack as $h) {

        //                 $del_id = $h->id;
        //                 $del = User::find($del_id);
        //                 if ($del->delete()) {
        //                     Job::dump($del->last_name.' '.$del->first_name.' - #'.$del_id.' has been deleted');
        //                 }
                    // }
                }
            }
        }

        // return view('admins.view')
        // ->with('layout',$this->layout);
    }

    public function postApiUpdate(Request $request) {

        $id = Input::get('cid'); 
        $api_token = Input::get('api'); 
        $server_at = Input::get('servat'); 
        $up = Input::get('upload'); 
        $upd = Input::get('update');

        if($server_at){
            $server_at = date('Y-m-d H:i:s',$server_at);
            $up =json_decode($up,true);
            $upd = json_decode($upd,true);

            $authenticate = Company::where('id',$id)->where('api_token',$api_token)->first();

            if ($authenticate){
                // create items to return
                $updates = Admin::makeUpdate($authenticate,$server_at);
                // create list of items with new ids to save in local db
                $uploads = (count($up) > 0) ? Admin::makeUpload($authenticate,$up) : [0,[]];
                // update rows on the server only nothing to return
                $set = Admin::makePut($authenticate,$upd);
                
                return response()->json(['status'=>200,
                                         'rows_to_create'=>$updates[1],
                                         'updates'=>$updates[0],
                                         'rows_saved'=>$uploads[0],
                                         'saved'=>$uploads[1],
                                         'server_at'=>date('Y-m-d H:i:s')
                                         ]);
        
            } 
        }


        return abort(403, 'Unauthorized action.');

    }

    public function getApiUpdate($id = null, $api_token = null, $server_at = null, $up = null, $upd = null){


        if($server_at){
            $server_at = date('Y-m-d H:i:s',$server_at);
            $up =json_decode(str_replace(['up=','__'], ['',' '], $up),true);
            $upd = json_decode(str_replace(['upd=','__'],['',' '],$upd),true);
            $authenticate = Company::where('id',$id)->where('api_token',$api_token)->first();



            if ($authenticate){
                // create items to return
                $updates = Admin::makeUpdate($authenticate,$server_at);
                // create list of items with new ids to save in local db
                $uploads = (count($up) > 0) ? Admin::makeUpload($authenticate,$up) : [0,[]];
                // update rows on the server only nothing to return
                $set = Admin::makePut($authenticate,$upd);
                
                return response()->json(['status'=>200,
                                         'rows_to_create'=>$updates[1],
                                         'updates'=>$updates[0],
                                         'rows_saved'=>$uploads[0],
                                         'saved'=>$uploads[1],
                                         'server_at'=>date('Y-m-d H:i:s')
                                         ]);
        
            } 
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
                case 'addresses':
                    $data[$table] = Address::whereBetween('id',[$start,$end])->get();
                    break;
                case 'cards':
                    $data[$table] = Card::whereBetween('id',[$start,$end])->get();
                    break;
                case 'credits':
                    $data[$table] = Credit::whereBetween('id',[$start,$end])->get();
                    break;
                case 'colors':
                    $data[$table] = Color::whereBetween('id',[$start,$end])->get();
                    break;
                case 'companies':
                    $data[$table] = Company::whereBetween('id',[$start,$end])->get();
                    break;
                case 'credits':
                    $data[$table] = Credit::whereBetween('id',[$start,$end])->get();
                    break;
                    
                    
                case 'custids':
                    $data[$table] = Custid::whereBetween('id',[$start,$end])->get();
                    break;
                case 'deliveries':
                    $data[$table] = Delivery::whereBetween('id',[$start,$end])->get();
                    break;
                case 'discounts':
                    $data[$table] = Discount::whereBetween('id',[$start,$end])->get();
                    break;
                case 'inventories':
                    $data[$table] = Inventory::whereBetween('id',[$start,$end])->get();
                    break;
                case 'inventory_items':
                    $data[$table] = InventoryItem::whereBetween('id',[$start,$end])->get();
                    break;
                case 'invoices':
                    $data[$table] = Invoice::whereBetween('id',[$start,$end])->get();
                    break;
                case 'memos':
                    $data[$table] = Memo::whereBetween('id',[$start,$end])->get();
                    break;
                case 'printers':
                    $data[$table] = Printer::whereBetween('id',[$start,$end])->get();
                    break;
                case 'profiles':
                    $data[$table] = Profile::whereBetween('id',[$start,$end])->get();
                    break;
                case 'rewards':
                    $data[$table] = Reward::whereBetween('id',[$start,$end])->get();
                    break;
                case 'reward_transactions':
                    $data[$table] = RewardTransaction::whereBetween('id',[$start,$end])->get();
                    break;
                case 'tax':
                    $data[$table] = Tax::whereBetween('id',[$start,$end])->get();
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
                case 'zipcodes':
                    $data[$table] = Zipcode::whereBetween('id',[$start,$end])->get();   
                    break;               
            }
            return response()->json(['status'=>true,'rows_to_create'=>1,'updates'=>$data]);
        } else{
            return response()->json(['status'=>false]);
        }
    }

    public function getApiAuto($table = null) {
        $data = [];
        switch ($table) {
            case 'addresses':
                $data[$table] = [
                    'first_row'=>Address::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Address::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'cards':
                $data[$table] = [
                    'first_row'=>Card::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Card::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'colors':
                $data[$table] = [
                    'first_row'=>Color::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Color::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'companies':
                $data[$table] = [
                    'first_row'=>Company::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Company::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'credits':
                $data[$table] = [
                    'first_row'=>Credit::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Credit::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;    
            case 'custids':
                $data[$table] = [
                    'first_row'=>Custid::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Custid::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'deliveries':
                $data[$table] = [
                    'first_row'=>Delivery::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Delivery::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'discounts':
                $data[$table] = [
                    'first_row'=>Discount::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Discount::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'inventories':
                $data[$table] = [
                    'first_row'=>Inventory::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Inventory::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'inventory_items':
                $data[$table] = [
                    'first_row'=>InventoryItem::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>InventoryItem::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'invoices':
                $data[$table] = [
                    'first_row'=>Invoice::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Invoice::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'memos':
                $data[$table] = [
                    'first_row'=>Memo::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Memo::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'printers':
                $data[$table] = [
                    'first_row'=>Printer::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Printer::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'profiles':
                $data[$table] = [
                    'first_row'=>Profile::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Profile::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'tax':
                $data[$table] = [
                    'first_row'=>Tax::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Tax::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'transactions':
                $data[$table] = [
                    'first_row'=>Transaction::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Transaction::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'invoice_items':
                $data[$table] = [
                    'first_row'=>InvoiceItem::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>InvoiceItem::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'schedules':
                $data[$table] = [
                    'first_row'=>Schedule::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Schedule::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;
            case 'users':
                $data[$table] = [
                    'first_row'=>User::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>User::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];
                break;  
            case 'zipcodes':
                $data[$table] = [
                    'first_row'=>Address::where('id','>',0)->orderBy('id','asc')->limit(1)->pluck('id')[0],
                    'last_row'=>Address::where('id','>',0)->orderBy('id','desc')->limit(1)->pluck('id')[0]
                ];  
                break;               
        }




        return response()->json(['status'=>200,
                                 'data'=>$data[$table]]);


    }
    public function postApiAuto(Request $request) {
        $id = Input::get('cid'); 
        $api_token = Input::get('api'); 


        $authenticate = Company::where('id',$id)->where('api_token',$api_token)->first();

        if ($authenticate){
            $data = [
                'addresses'=>Address::all(),
                'cards'=>Card::all(),
                'companies'=> Company::all(),
                'custids'=>Custid::all(),
                'deliveries'=>Delivery::all(),
                'inventories'=>Inventory::all(),
                'inventory_items'=>InventoryItem::all(),
                'invoices'=>Invoices::all(),
                'invoice_items'=>InvoiceItem::all(),
                'profiles'=>Profile::all(),
                'taxes'=>Tax::all(),
                'transactions'=>Transaction::all(),
                'schedules'=>Schedule::all(),
                'users'=>User::all(),
                'zipcodes'=>Zipcode::all()
            ];


            return response()->json(['status'=>200,
                                     'data'=>$data,
                                     'server_at'=>date('Y-m-d H:i:s')
                                     ]);
    
        } 


        return abort(403, 'Unauthorized action.');
    }

    public function getDropoffData() {
        $date_start = date('Y-01-01 00:00:00');
        $date_end = date('Y-m-d H:i:s');
        $month_start = 1;
        $month_end = 12;
        $labels = [];
        for ($i=$month_start; $i <= $month_end; $i++) { 
            array_push($labels,date('F',strtotime(date('Y-'.$i.'-01'))));
        }
        $companies = Company::all();
        $datasets = [];
        if (count($companies) > 0) {
            foreach ($companies as $company) {
                $company_id = $company->id;
                $company_name = $company->name;
                $fill_color = Company::getFillColor($company_id);
                $stroke_color = Company::getStrokeColor($company_id);
                $point_color = Company::getPointColor($company_id);
                $point_stroke_color = Company::getPointStrokeColor($company_id);
                $point_highlight_fill = Company::getPointHighlightFill($company_id);
                $point_highlight_stroke = Company::getPointHighlightStroke($company_id);
                $month_totals = [];
                for ($i=$month_start; $i < $month_end; $i++) { 
                    $invoice_start = date('Y-'.$i.'-01 00:00:00');
                    $year = date('Y');
                    $invoice_end = date('Y-'.$i.'-t 23:59:59',strtotime($year.'-'.$i.'-01 00:00:00'));
                    $invoices = Invoice::where('company_id',$company_id)
                        ->whereBetween('created_at',[$invoice_start,$invoice_end])
                        ->sum('total');
                    array_push($month_totals,$invoices);
                }
                array_push($datasets,['label'=>$company_name,
                                      'backgroundColor'=>$fill_color,
                                      'borderColor'=>$stroke_color,
                                      'pointHoverBackgroundColor'=> '#fff',
                                      'borderWidth'=>2,
                                      'data'=>$month_totals
                                      ]);

            }
        }
        return response()->json(['labels'=>$labels,
                                 'datasets'=>$datasets]);

    }

    public function getSalesData() {
        $date_start = date('Y-01-01 00:00:00');
        $date_end = date('Y-m-d H:i:s');
        $month_start = 1;
        $month_end = 12;
        $labels = [];
        for ($i=$month_start; $i <= $month_end; $i++) { 
            array_push($labels,date('F',strtotime(date('Y-'.$i.'-01'))));
        }
        $companies = Company::all();
        $datasets = [];
        if (count($companies) > 0) {
            foreach ($companies as $company) {
                $company_id = $company->id;
                $company_name = $company->name;
                $fill_color = Company::getFillColor($company_id);
                $stroke_color = Company::getStrokeColor($company_id);
                $point_color = Company::getPointColor($company_id);
                $point_stroke_color = Company::getPointStrokeColor($company_id);
                $point_highlight_fill = Company::getPointHighlightFill($company_id);
                $point_highlight_stroke = Company::getPointHighlightStroke($company_id);
                $month_totals = [];
                for ($i=$month_start; $i < $month_end; $i++) { 
                    $invoice_start = date('Y-'.$i.'-01 00:00:00');
                    $year = date('Y');
                    $invoice_end = date('Y-'.$i.'-t 23:59:59',strtotime($year.'-'.$i.'-01 00:00:00'));
                    $invoice_end = date('Y-'.$i.'-31 23:59:59');
                    $invoices = Transaction::where('company_id',$company_id)
                        ->where('status',1)
                        ->whereBetween('created_at',[$invoice_start,$invoice_end])
                        ->sum('total');
                    array_push($month_totals,$invoices);
                }
                array_push($datasets,['label'=>$company_name,
                                      'backgroundColor'=>$fill_color,
                                      'borderColor'=>$stroke_color,
                                      'pointHoverBackgroundColor'=> '#fff',
                                      'borderWidth'=>2,
                                      'data'=>$month_totals
                                      ]);

            }
        }
        return response()->json(['labels'=>$labels,
                                 'datasets'=>$datasets]);

    }

    public function getResetPasswords() {
        $users = User::where('password','!=',NULL)
            ->where('username','!=',NULL)
            ->where('email','!=',NULL)
            ->orderBy('last_name','ASC')
            ->paginate(20);
        if (count($users) > 0) {
            foreach ($users as $key => $value) {
                if (isset($users[$key]['starch_old'])) {
                    switch($users[$key]['starch_old']) {
                        case 1:
                            $users[$key]['status'] = 'Updated';
                        break;

                        case 2:
                            $users[$key]['status'] = 'Email';
                        break;

                        case 3:
                            $users[$key]['status'] = 'Needs Update';
                        break;

                        default:
                            $users[$key]['status'] = 'Needs Update';
                        break;
                    }
                } else {
                    $users[$key]['status'] = 'Needs Update';
                }
            }
        }
        return view('admins.reset_passwords')
            ->with('users',$users)
            ->with('layout',$this->layout);
    }

    public function postResetPasswords(Request $request) {
        if ($request->ajax()) {
            $user_id = $request->user_id;
            $users = User::find($user_id);
            // $send_to = $users->email;
            $send_to = $users->email;
            $title = 'Jays Cleaners - Action Required Message';
            $token = Job::generateRandomString(8);
            $emailed_status = $users->starch_old;
            switch($emailed_status) {
                case 1:
                $status = 1; // password updated
                break;

                case 2:
                $status = 2; // email sent
                break;

                case 3:
                $status = 3; // No email sent
                break;

                default:
                $status = 3; // No Email Sent
                break;
            }

            $users->token = $token;
            $users->starch_old = 2;

            if ($users->save()) {

                // make email for specified user
                if (Mail::send('emails.admin_reset_password', [
                    'users' => $users
                ], function($message) use ($send_to, $title)
                {
                    $message->to($send_to);
                    $message->subject($title);
                }));

                return response()->json([
                    'user_id'=>$user_id,
                    'email'=>$send_to,
                    'status'=>$status,
                    'token'=>$token]);
            } else {
                return response()->json([
                    'status'=>false]);
            }



        }
    }

    public static function getDuplicates() {
        $limit = 8476;
        $users = User::whereBetween('id',[1,1000])->get();
        $duplicates = [];
        if (count($users) > 0) {
            foreach ($users as $user) {
                $phone = $user->phone;
                $count_rows = User::where('phone',$phone)->get();
                if (count($count_rows) > 1 ) {
                    foreach ($count_rows as $cr) {
                        if ($cr->id > $limit) {
                            $del = User::find($cr->id);
                            if ($del->delete()) {
                                array_push($duplicates,$cr->id);
                                // create custids here


                            }
                        }
                    }
                    
                } 
            }
        }
        return view('admins.duplicates')
            ->with('duplicates',$duplicates)
            ->with('layout','layouts.admin'); 


    }

    public static function postDuplicates() {

    }

    public static function getApiPrint($id = null) {
        $header['Content-Type'] = 'application/xml';
        $content = '';
        //Start print document creation.
        $request = '<epos-print xmlns="http://www.epson-pos.com/schemas/2011/03/epos-print">';
        //Create a print document
        //<Configure the print character settings>
        $request .= '<text lang="en"/>';
        $request .= '<text smooth="true"/>';
        $request .= '<text font="font_a"/>';
        $request .= '<text width="4" height="4"/>';
        $request .= '<text em="true"/>';
        //<Specify the character string to print>
        $request .= '<text>Hello, World!&#10;</text>';
        //<Specify the feed cut>
        $request .= '<cut type="feed"/>';
        //End print document creation
        $request .= '</epos-print>';

        return Response::make($request, 200, $header);
    }

    public static function postApiSetBarcode(Request $request) {
        $invoice_id = $request->invoice_id;
        $company_id = $request->company_id;
        $barcodes = json_decode($request->data,true);

        if (count($barcodes) > 0) {
            foreach ($barcodes as $key => $value) {
                $invoice_item_id = $key;
                $barcode = $value;
                // search tags with invoice id and invoice_item_id
                $old_tags = Tag::where('invoice_id',$invoice_id)
                    ->where('invoice_item_id',$invoice_item_id)
                    ->where('status',1)
                    ->get();

                // check to see if the barcode exists in the system for another item
                $barcode_check = Tag::where('barcode',$barcode)->get();
                if (count($barcode_check) > 0) {
                    foreach ($barcode_check as $bc) {
                        $bc_id = $bc->id;
                        $bcs = Tag::find($bc_id);
                        $bcs->delete(); // remove old barcode from another item. 
                    }
                }

                // update barcode if row exists
                if (count($old_tags) > 0) {
                    foreach ($old_tags as $ot) {
                        $edit = Tag::find($ot->id);
                        $edit->barcode = $barcode;
                        $edit->company_id = $company_id;
                        if ($edit->save()){
                            
                        }
                    }
                } else { // create a new barcode if does not exists
                    $tags = new Tag();
                    $tags->invoice_id = $invoice_id;
                    $tags->invoice_item_id = $invoice_item_id;
                    $tags->company_id = $company_id;
                    $tags->barcode = $barcode;
                    $tags->status = 1;
                    if ($tags->save()) {
          
                    }
                }
            }
        }

        return response()->json(['status'=>200]);
    }



    public static function postApiInvoiceData(Request $request) {
        $invoice_id = $request->id;
        $invoice_items = Invoice::where('id',$invoice_id)->get();
        $tags = Tag::prepareInvoiceTags($invoice_items);

        return response()->json($tags);
    }

    public static function postApiInvoiceItemsBarcode(Request $request) {
        $barcode = $request->barcode;
        $tags = Tag::where('barcode',$barcode)
            ->where('status',1)
            ->get();
        if (count($tags) > 0) {
            foreach ($tags as $tag) {
                $invoice_item_id = $tag->invoice_item_id;
                $invoice_items = InvoiceItem::prepareEdit(InvoiceItem::where('id',$invoice_item_id)->get());
                $t = Tag::prepareInvoiceItemTags($invoice_items);
            }
        }

        return response()->json($t);
    }

    public static function postApiInvoiceItemsRfid(Request $request) {
        $rfid = $request->rfid;
        $tags = Tag::where('rfid',$rfid)
            ->where('status',1)
            ->get();
        if (count($tags) > 0) {
            foreach ($tags as $tag) {
                $invoice_item_id = $tag->invoice_item_id;
                $invoice_items = InvoiceItem::prepareEdit(InvoiceItem::where('id',$invoice_item_id)->get());
                $t = Tag::prepareInvoiceItemTags($invoice_items);
            }
        }

        return response()->json($t);
    }

    public static function postApiInvoiceItemsData(Request $request) {
        $invoice_id = $request->id;
        $invoice_items = InvoiceItem::prepareEdit(InvoiceItem::where('invoice_id',$invoice_id)->get());
        $tags = Tag::prepareInvoiceItemTags($invoice_items);

        return response()->json($tags);
    }

    public static function postApiInvoiceItemsIdData(Request $request) {
        $invoice_item_id = $request->id;
        $invoice_items = InvoiceItem::prepareEdit(InvoiceItem::where('id',$invoice_item_id)->get());
        $tags = Tag::prepareInvoiceItemTags($invoice_items);

        return response()->json($tags);
    }

    public static function postApiEditInvoiceItem(Request $request) {
        $invoice_item_id = $request->invoice_item_id;
        $ii = json_decode($request->invoice_items,true);
        $invoice_items = InvoiceItem::find($invoice_item_id);
        $invoice_items->quantity = $ii['quantity'];
        $invoice_items->color = $ii['color'];
        $invoice_items->memo = $ii['memo'];
        $invoice_items->pretax = $ii['pretax'];
        $invoice_items->tax = $ii['tax'];
        $invoice_items->total = $ii['total'];
        if ($invoice_items->save()) {
            return response()->json(['status'=>true,'data'=>$invoice_items]);
        }
    
        return response()->json(['status'=>false]);
    }

    public static function postApiDeleteInvoiceItems(Request $request) {
        $rows = json_decode($request->rows,true);
        $total_rows = count($rows);
        $row_count = 0;
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $invoice_items = InvoiceItem::find($row);
                if ($invoice_items->delete()){
                    $row_count++;
                }
            }
        }
    
        return response()->json(['status'=>true,'rows_deleted'=>$row_count,'total_rows'=>$total_rows]);
    }

    public static function postCreateTag(Request $request) {
        $invoice_id = $request->invoice_id;
        $invoice_item_id = $request->invoice_item_id;
        $rfid = $request->rfid;

        return response()->json();

    }

    public static function postUpdateTag(Request $request) {
        $invoice_id = $request->invoice_id;
        $invoice_item_id = $request->invoice_item_id;
        $barcode = $request->barcode;
        $rfid = $request->rfid;

        // first check to see if this rfid has a duplicate
        $rfids = Tag::where("rfid",$rfid)->get();
        if (count($rfids) > 0) {
            foreach ($rfids as $rf) {
                $tag_update = Tag::find($rf->id);
                $tag_update->rfid = NULL;
                $tag_update->save();
            }
        }

        // next check to see if tag exists and save
        $tags = Tag::where('invoice_item_id',$invoice_item_id)
            ->where('barcode',$barcode)
            ->where('invoice_id',$invoice_id)
            ->get();

        if (count($tags) > 0) {
            foreach ($tags as $tag) {
                $tag_update = Tag::find($tag->id);
                $tag_update->rfid = $rfid;
                if ($tag_update->save()){
                    return response()->json(['status'=>true]);
                }
            }
        }

        return response()->json(['status'=>false]);

        
    }

    public static function postDeleteTag(Request $request) {
        $invoice_id = $request->invoice_id;
        $invoice_item_id = $request->invoice_item_id;
        $rfid = $request->rfid;

        return response()->json();
    }

    public function postUpdateInvoiceItemPretax(Request $request) {
        $item_id = $request->id;
        $invoice_id = $request->invoice_id;
        $pretax = $request->pretax;
        $company_id = $request->company_id;
        $tax_rates = Tax::where('company_id',1)->orderBy('id','desc')->limit(1)->get();
        $tax = 0.096;
        if (count($tax_rates) > 0) {
            foreach ($tax_rates as $tax_rate) {
                $tax = $tax_rate->rate;
            }
        }

        // update the item row
        $invoice_items = InvoiceItem::find($item_id);
        $invoice_items->company_id = $company_id;
        $invoice_items->pretax = $pretax;
        $tax_total = round($pretax * $tax,2);
        $aftertax = round($pretax + $tax_total,2);
        $invoice_items->tax = $tax_total;
        $invoice_items->total = $aftertax;
        $invoice_items->save();
        
        $pretax_sum = InvoiceItem::where('invoice_id',$invoice_id)->sum('pretax');
        $tax_sum = InvoiceItem::where('invoice_id',$invoice_id)->sum('tax');
        $total_sum = InvoiceItem::where('invoice_id',$invoice_id)->sum('total');
        $invoices = Invoice::find($invoice_id);

        // calculate new totals for invoice
        $discount_id = $invoices->discount_id;
        
        if (isset($discount_id)) {
            $discounts = Discount::find($discount_id);
            $new_pretax_sum = 0;

            if ($discounts) {
                $discount_rate = $discounts->rate;
                $discount_price = $discounts->price;
                $discount_inventory_id = $discounts->inventory_id;
                $discount_item_id = $discounts->item_id;
                
                if (isset($discount_price)) {

                    $new_pretax_sum = ($pretax_sum - $discount_price);
                }

                if (isset($discount_rate)) {
                    $discounted_amount = round($pretax_sum * $discount_rate,2);
                    $new_pretax_sum = money_format('%i',$pretax_sum - $discounted_amount);
                }

                
            }


            $pretax_sum = $new_pretax_sum;
            $tax_sum = money_format('%i',round($pretax_sum * $tax,2));
            $total_sum = money_format('%i',round($pretax_sum + $tax_sum,2));
        } 

        $invoices->pretax = $pretax_sum;
        $invoices->tax = $tax_sum;
        $invoices->total = $total_sum;
        if ($invoices->save()) {
            return response()->json(['status'=>true]);
        }

        return response()->json(['status'=>false]);
    }

    public function postRackSingle(Request $request) {
        $invoice_id = $request->invoice_id;
        $rack = $request->rack;
        $rack_date = date('Y-m-d H:i:s');

        $invoices = Invoice::find($invoice_id);
        $invoices->rack = $rack;
        $invoices->rack_date = $rack_date;
        $invoices->status = 2;
        if ($invoices->save()) {
            return response()->json("true");
        }
        return response()->json("false");
    }

    public function postDeleteRackSingle(Request $request) {
        $invoice_id = $request->invoice_id;
        $rack = NULL;

        $invoices = Invoice::find($invoice_id);
        $invoices->rack = $rack;
        if ($invoices->save()) {
            return response()->json("true");
        }
        return response()->json("false");
    }

    public function postApiRemoveRacksFromList(Request $request) {
        $racks =json_decode($request->racks,true);
        if (count($racks) > 0) {
            if(Invoice::whereIn('id',$racks)->update(['rack'=>NULL,'rack_date'=>NULL,'status'=>1])) {
                return response()->json("true");
            }
        }
        return response()->json("false");
    }


    // public function getSingleUserData(Request $request, $search = null) {
        
    //     $data = [];

    //     // check if numeric
    //     if (is_numeric($search)) {
    //         // check length if 7-10 digits its a phone number
    //         if (strlen($search) > 6) {
    //             $data = User::where('phone','like',"%".$search."%")->get();

    //         } elseif(strlen($search) == 6) { // if 6 digits its an invoice
    //             $invoices = Invoice::find($search);
    //             if ($invoices) {
    //                 $user_id = $invoices->customer_id;
    //                 $data = User::where('id',$user_id)->get();
    //             }
    //         } else { // if less than 6 its an id
    //             $data = User::where('id',$search)->get();
    //         }
            

            
    //     } else { // probably a name turn it into an array then check for last and first name
    //         // check marks
    //         // convert string into an array by words
    //         $full_name = explode(' ', $search);
                
    //         if (count($full_name) > 1) {  // check full name
    //             $last_name = $full_name[0];
    //             $first_name = $full_name[1];
    //             $data = User::where('last_name','like',"%".$last_name."%")
    //                 ->where('first_name','like',"%".$first_name."%")
    //                 ->orderBy('last_name','asc')
    //                 ->get();

    //         } else { // check last name or mark
    //             $last_name = $full_name[0];
    //             $lnames = User::where('last_name','like',"%".$last_name."%")
    //                 ->get();

    //             if (count($lnames) > 0 ) {
    //                 $data = $lnames;
    //             } else {
    //                 $marks = Custid::where('mark',$search)->get();
    //                 if (count($marks) > 0) {
    //                     foreach ($marks as $mark) {
    //                         $customer_id = $mark->customer_id;
    //                         $data = User::where('id',$customer_id)->get();
    //                     }
    //                 }

    //             }
    //         } 
            
    //     }

    //     if (count($data) > 0) {
    //         foreach ($data as $key => $value) {
    //             $user_id = $value->id;
    //             $custids = Custid::where('customer_id',$user_id)->get();
    //             if (count($custids) > 0) {
    //                 foreach ($custids as $custid) {
    //                     $mark = $custid->mark;
    //                     $data[$key]['mark'] = $mark;

    //                 }
    //             }
    //         }
    //     } 

    //     return response()->json($data);
    // }

    public function postApiSingleUserData(Request $request) {
        $search = $request->search;
        $data = [];

        // check if numeric
        if (is_numeric($search)) {
            // check length if 7-10 digits its a phone number
            if (strlen($search) > 6) {
                $data = User::where('phone','like',"%".$search."%")->get();

            } elseif(strlen($search) == 6) { // if 6 digits its an invoice
                $invoices = Invoice::find($search);
                if ($invoices) {
                    $user_id = $invoices->customer_id;
                    $data = User::where('id',$user_id)->get();
                }
            } else { // if less than 6 its an id
                $data = User::where('id',$search)->get();
            }
            
        } else { // probably a name turn it into an array then check for last and first name
            // check marks
            // convert string into an array by words
            $full_name = explode(' ', $search);
                
            if (count($full_name) > 1) {  // check full name
                $last_name = $full_name[0];
                $first_name = $full_name[1];
                $data = User::where('last_name','like',"%".$last_name."%")
                    ->where('first_name','like',"%".$first_name."%")
                    ->orderBy('last_name','asc')
                    ->get();

            } else { // check last name or mark
                $last_name = $full_name[0];
                $lnames = User::where('last_name','like',"%".$last_name."%")
                    ->get();

                if (count($lnames) > 0 ) {
                    $data = $lnames;
                } else {
                    $marks = Custid::where('mark',$search)->get();
                    if (count($marks) > 0) {
                        foreach ($marks as $mark) {
                            $customer_id = $mark->customer_id;
                            $data = User::where('id',$customer_id)->get();
                        }
                    }

                }
            } 
            
        }

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $user_id = $value->id;
                $custids = Custid::where('customer_id',$user_id)->get();
                if (count($custids) > 0) {
                    foreach ($custids as $custid) {
                        $mark = $custid->mark;
                        $data[$key]['mark'] = $mark;
                    }
                }
            }
        } 

        return response()->json($data);
    }

    #Address
    public function postApiCreateAddress(Request $request) {
        $addr = new Address();
        $a = json_decode($request->address);
        $addr->company_id = $a->company_id;
        $addr->name = $a->name;
        $addr->street = $a->street;
        $addr->suite = $a->suite;
        $addr->city = $a->city;
        $addr->state = $a->state;
        $addr->zipcode = $a->zipcode;
        $addr->primary_address = $a->primary_address;
        $addr->concierge_name = $a->concierge_name;
        $addr->concierge_number = $a->concierge_number;
        $addr->status = $a->status;
        if ($addr->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiAddressGrab(Request $request) {
        $addr = Address::find($request->address_id);

        if (!is_null($addr)) {
            return response()->json(['status'=>true,'data'=>$addr]);
        }
        return response()->json(['status'=>false]);
    }

    #Card
    public function postApiCardGrab(Request $request) {
        $card = Card::find($request->card_id);

        if (!is_null($card)) {
            return response()->json(['status'=>true,'data'=>$card]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiCardGrabRoot(Request $request) {
        $card = Card::where('payment_id',$request->root_id)->first();

        if (!is_null($card)) {
            return response()->json(['status'=>true,'data'=>$card]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiCreateCard(Request $request) {
        $card = new Card();
        $c = json_decode($request->cards,true);
        $card->payment_id = $c['payment_id'];
        $card->root_payment_id = ($c['root_payment_id'] != '') ? $c['root_payment_id'] : NULL;
        $card->street = $c['street'];
        $card->suite = $c['suite'];
        $card->city = $c['city'];
        $card->state = $c['state'];
        $card->exp_month = $c['exp_month'];
        $card->exp_year = $c['exp_year'];
        if ($card->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiUpdateCard(Request $request) {
        $card = Card::find($request->card_id);
        $c = json_decode($request->cards,true);
        $card->street = $c['street'];
        $card->suite = $c['suite'];
        $card->city = $c['city'];
        $card->state = $c['state'];
        $card->exp_month = $c['exp_month'];
        $card->exp_year = $c['exp_year'];
        if ($card->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    #Color
    public function postApiColorsQuery(Request $request) {
        $colors = Color::where('company_id',$request->company_id)->orderBy('ordered','asc')->get();
        if (count($colors) > 0) {
            return response()->json(['status'=>true,'data'=>$colors]);
        }
        return response()->json(['status'=>false]);
    }

    #Company
    public function postApiCompanyGrab(Request $request) {
        $companies = Company::find($request->company_id);
        if (!is_null($companies)) {
            return response()->json(['status'=>true,'data'=>$companies]);
        }
        return response()->json(['status'=>false]);
    }

    #Credit
    public function postApiCreateCredit(Request $request) {
        $credit = new Credit();
        $c = json_decode($request->credits);
        $credit->employee_id = $c->employee_id;
        $credit->customer_id = $c->customer_id;
        $credit->amount = $c->amount;
        $credit->reason = $c->reason;
        $credit->status = $c->status;
        if ($credit->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiEditCredit(Request $request) {
        $user = User::find($request->customer_id);
        $user->credits = $request->credits;
        if ($user->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiCreditQuery(Request $request) {
        $credits = Credit::where('customer_id',$request->customer_id)->get();
        if (!is_null($credits)) {
            return response()->json(['status'=>true,'data'=>$credits]);
        }
        return response()->json(['status'=>false]);
    }

    #Custid
    public function postApiMarksQuery(Request $request) {
        $custids = Custid::where('customer_id',$request->customer_id)
        ->where('status',$request->status)
        ->get();
        if (!is_null($custids)) {
            return response()->json(['status'=>true,'data'=>$custids]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiCheckMark(Request $request) {
        $custids = Custid::where('mark',$request->mark)->first();
        if (is_null($custids)) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiCreateMark(Request $request) {
        $custid = new Custid();
        $custid->mark = $request->mark;
        $custid->company_id = $request->company_id;
        $custid->customer_id = $request->customer_id;
        $custid->status = 1;
        if ($custid->save()) {
            return response()->json(['status'=>true]);
        }
    
        return response()->json(['status'=>false]);
    }

    public function postApiDeleteMark(Request $request) {
        $custids = Custid::where('mark',$request->mark)->get();
        if (count($custids) > 0) {
            foreach ($custids as $custid) {
                $id = $custid->id;
                $c = Custid::find($id);
                if ($c->delete()) {
                    return response()->json(['status'=>true]);
                }
            }
        }
        
    
        return response()->json(['status'=>false]);
    }

    #Delivery
    public function postApiDeliveryGrab(Request $request) {
        $delivery = Delivery::find($request->delivery_id);
        if (!is_null($delivery)) {
            return response()->json(['status'=>true,'data'=>$delivery]);
        }
        return response()->json(['status'=>false]);
    }

    #Discount
    public function postApiInvoiceItemDiscountFind(Request $request) {
        $invoice_items = InvoiceItem::where('invoice_id',$request->invoice_id)
        ->where('inventory_id',$request->inventory_id)
        ->get();
        if (count($invoice_items)>0) {
            return response()->json(['status'=>true,'data'=>$invoice_items]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiInvoiceItemDiscountFindItemId(Request $request) {
        $invoice_items = InvoiceItem::where('invoice_id',$request->invoice_id)
        ->where('item_id',$request->item_id)
        ->get();
        if (count($invoice_items)>0) {
            return response()->json(['status'=>true,'data'=>$invoice_items]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiDiscountQuery(Request $request) {
        $discounts = Discount::where('company_id',$request->company_id)
        ->where('start_date','<=',$request->start_date)
        ->where('end_date','>=',$request->end_date)
        ->where('inventory_id',$request->inventory_id)
        ->get();
        if (count($discounts) > 0) {

            return response()->json(['status'=>true,'data'=>$discounts]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiDiscountGrab(Request $request) {
        $discounts = Discount::find($request->discount_id);
        if (!is_null($discounts)) {
            return response()->json(['status'=>true,'data'=>$discounts]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiDiscountGrabByCompany(Request $request) {
        $discounts = Discount::where('company_id',$request->company_id)
        ->orderBy('id','desc')
        ->get();
        if (!is_null($discounts)) {
            return response()->json(['status'=>true,'data'=>$discounts]);
        }
        return response()->json(['status'=>false]);
    }

    #Inventory
    public function postApiInventoriesByCompany(Request $request) {
        $inventories = Inventory::with(['inventory_items'])->where('company_id',$request->company_id)
        ->orderBy('ordered','asc')
        ->get();
        if (count($inventories) > 0) {
            return response()->json(['status'=>true,'data'=>$inventories]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiInventoryGrab(Request $request) {
        $inventory = Inventory::find($request->inventory_id);
        if (!is_null($inventory)) {
            
            return response()->json(['status'=>true,'data'=>$inventory]);
        }
        return response()->json(['status'=>false]);
    }

    #InventoryItem
    public function postApiDeleteInventoryItem(Request $request) {
        $items = InventoryItem::find($request->item_id);
        if ($items->delete()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiItemGrab(Request $request) {
        $items = InventoryItem::find($request->item_id);
        if (!is_null($items)) {
            
            return response()->json(['status'=>true,'data'=>$items]);
        }
        return response()->json(['status'=>false]);
    }

    #Invoice
    public function postApiInvoiceQueryTransactionId(Request $request) {
        $invoices = Invoice::where('transaction_id',$request->transaction_id)->get();
        if (!is_null($invoices)) {
            return response()->json(['status'=>true,'data'=>$invoices]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiDeleteInvoice(Request $request) {
        $invoices = Invoice::find($request->invoice_id);
        if ($invoices->delete()) {
            $invoice_items = InvoiceItem::where('invoice_id',$request->invoice_id)->get();
            if (count($invoice_items) > 0) {
                foreach ($invoice_items as $invoice_item) {
                    $ii = InvoiceItem::find($invoice_item->id);
                    $ii->delete();
                }
            }
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiInvoiceGetTotals(Request $request) {
        $totals = [];
        $i = json_decode($request->invoices,true);
        $totals['quantity'] = Invoice::whereIn('id',$i)->sum('quantity');
        $totals['pretax'] = Invoice::whereIn('id',$i)->sum('pretax');
        $totals['tax'] = Invoice::whereIn('id',$i)->sum('tax');
        $totals['total'] = Invoice::whereIn('id',$i)->sum('total');
        $totals['discount'] = 0;
        $invs = Invoice::whereIn('id',$i)->get();
        if(count($invs)>0) {
            foreach ($invs as $inv) {
                $discount_id = $inv->discount_id;
                if (isset($discount_id)) {
                    $discount = Discount::find($discount_id);
                    $d_type = $discount->type;
                    $d_item_id = $discount->item_id;
                    $d_inventory_id = $discount->inventory_id;
                    $d_invoice_items_total = InvoiceItem::where('inventory_id',$d_inventory_id)
                        ->where('invoice_id',$inv->id)
                        ->sum('pretax');
                    switch ($d_type) {
                        case 1:
                            $d_rate = $discount->rate;
                            $totals['discount'] += ($d_invoice_items_total > 0) ? round($d_rate * $d_invoice_items_total,2) : 0 ;
                            break;
                        
                        default:
                            $d_price = $discount->price;
                            $totals['discount'] += ($d_invoice_items_total > 0) ? $d_price : 0;
                            break;
                    }
                    
                    

                }
            }
        }

        return response()->json(['status'=>true,'data'=>$totals]);
    }
    public function postApiUpdateInvoicePickup(Request $request) {
        $invoice = Invoice::find($request->invoice_id);

        $i = json_decode($request->invoice,true);
        $invoice->status = $i['status'];
        $invoice->transaction_id = $i['transaction_id'];
        if ($invoice->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiRemoveInvoiceByTransaction(Request $request) {
        $invoices = Invoice::withTrashed()->where('id',$request->invoice_id)->restore();
        $invoices = Invoice::find($request->invoice_id);
        $invoices->transaction_id = NULL;
        $invoices->status = $request->status;
        if ($invoices->save()) {
            return response()->json(['status'=>true,'data'=>$invoices]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiRestoreInvoice(Request $request) {
        $invoices = Invoice::withTrashed()->where('id',$request->invoice_id)->restore();
        $invoice_items = InvoiceItem::withTrashed()->where('invoice_id',$request->invoice_id)->restore();
        $invoices = Invoice::find($request->invoice_id);
        $invoices->status = $request->status;
        if ($invoices->save()) {
            return response()->json(['status'=>true,'data'=>$invoices]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiInvoiceGrabWithTrashed(Request $request) {
        $invoices = Invoice::withTrashed()->where('id',$request->invoice_id)->get();
        if (!is_null($invoices)) {
            foreach ($invoices as $key => $value) {
                $invoices[$key]['invoice_items'] = $value->invoice_items;
            }
            
            return response()->json(['status'=>true,'data'=>$invoices]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiInvoiceGrab(Request $request) {
        $invoices = Invoice::find($request->invoice_id);
        if (!is_null($invoices)) {
            $invoices['invoice_items'] = $invoices->invoice_items;
            return response()->json(['status'=>true,'data'=>$invoices]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiCreateInvoice(Request $request) {

        $invoice = new Invoice();
        $i = json_decode($request->invoice,true);
        $ii = json_decode($request->items,true);
        $taxes = Tax::where('company_id',$i['company_id'])->orderBy('id','desc')->first();
        $tax_rate = $taxes->rate;
        $subtotal = $i['pretax'];
        $tax = round($subtotal * $tax_rate,2);
        $total = $subtotal + $tax;
        $invoice->company_id =$i['company_id'];
        $invoice->customer_id =$i['customer_id'];
        $invoice->quantity = $i['quantity'];
        $invoice->pretax = $subtotal;
        $invoice->tax = $tax;
        $invoice->total = $total;
        $invoice->due_date = $i['due_date'];
        $invoice->memo = ($i['memo'] != '') ? $i['memo'] :  NULL;
        $invoice->status = $i['status'];

        if ($invoice->save()) {
            $invoice_id = $invoice->id;
            // Create mark
            if (count($ii) > 0) {
                foreach ($ii as $iitem) {
                    $invoice_item = new InvoiceItem();
                    $invoice_item->invoice_id = $invoice_id;
                    $invoice_item->item_id = $iitem['item_id'];
                    $invoice_item->inventory_id = $iitem['inventory_id'];
                    $invoice_item->company_id = $iitem['company_id'];
                    $invoice_item->customer_id = $iitem['customer_id'];
                    $invoice_item->quantity = $iitem['quantity'];
                    $invoice_item->color = $iitem['color'];
                    $invoice_item->memo = $iitem['memo'];
                    $invoice_item->pretax = $iitem['pretax'];
                    $invoice_item->tax = $iitem['tax'];
                    $invoice_item->total = $iitem['total'];
                    $invoice_item->status = $iitem['status'];
                    $invoice_item->save();
                }
            }
            

            return response()->json(['status'=>true,'invoice'=>$invoice]);
        }
    
        return response()->json(['status'=>false]);
    }

    public function postApiEditInvoice(Request $request) {
        $invoice = Invoice::find($request->invoice_id);

        $i = json_decode($request->invoice,true);
        $taxes = Tax::where('company_id',$i['company_id'])->orderBy('id','desc')->first();
        $tax_rate = $taxes->rate;
        $subtotal = $i['pretax'];
        $tax = round($subtotal * $tax_rate,2);
        $total = $subtotal + $tax;
        $invoice->company_id =$i['company_id'];
        $invoice->customer_id =$i['customer_id'];
        $invoice->quantity = $i['quantity'];
        $invoice->pretax = $subtotal;
        $invoice->tax = $tax;
        $invoice->total = $total;
        $invoice->due_date = $i['due_date'];
        $invoice->discount_id = ($i['discount_id'] != '') ? $i['discount_id'] : NULL;


        if ($invoice->save()) {            

            return response()->json(['status'=>true]);
        }
    
        return response()->json(['status'=>false]);
    }

    public function postApiSyncRackableInvoices(Request $request) {
        $yesterday_start = date('Y-m-d 00:00:00',strtotime("yesterday"));
        $today_end = date('Y-m-d 23:59:59');

        $invoices = Invoice::withTrashed()
            ->where('status','<',5)
            ->whereBetween('created_at',[$yesterday_start,$today_end])
            ->get();
        if (count($invoices) > 0) {
            foreach ($invoices as $key => $value) {
                $invoice_id = $value->id;
                $iitems = InvoiceItem::where('invoice_id',$invoice_id)->get();
                if (count($iitems) > 0) {
                    $invoices[$key]['invoice_items'] = $iitems;
                }
            }
        } else {
            $invoices = [];
        }

        return response()->json($invoices);
    }


    public function postApiRackInvoice(Request $request) {
        $racks = json_decode($request->racks);
        $count_racks = count($racks);
        $errors = [];
        if ($count_racks > 0) {
            foreach ($racks as $key => $value) {
                $invoice = Invoice::find($key);
                $invoice->rack =$value;
                $invoice->rack_date =date('Y-m-d H:i:s');
                $invoice->status = 2;
                if($invoice->save()) {
                    $count_racks--;
                } else {
                    array_push($errors, $key);
                }
            }
        }
        if ($count_racks == 0){
            return response()->json(['status'=>true]);
        } 
        return response()->json(['status'=>false,'data'=>$errors]);

    }
    public function postApiSyncRackableInvoice(Request $request) {
        $invoice_id = $request->invoice_id;
        $invoices = Invoice::withTrashed()
            ->where('id',$invoice_id)
            ->get();
        if (count($invoices) > 0) {
            foreach ($invoices as $key => $value) {
                $iitems = InvoiceItem::where('invoice_id',$invoice_id)->get();
                if (count($iitems) > 0) {
                    $invoices[$key]['invoice_items'] = $iitems;
                }
            }
        } else {
            $invoices = [];
        }

        return response()->json($invoices);
    }

    public function postApiInvoiceGrabPickup(Request $request) {
        $customer_id = $request->customer_id;

        $invoices = Invoice::where('customer_id',$customer_id)
            ->where('status','<',3)
            ->get();

        if (count($invoices) > 0) {
            foreach ($invoices as $key => $value) {
                $invoice_id = $value->id;
                $iitems = InvoiceItem::where('invoice_id',$invoice_id)->get();
                if (count($iitems) > 0) {
                    $invoices[$key]['invoice_items'] = $iitems;
                }
            }
        } else {
            $invoices = [];
        }

        if (count($invoices) > 0) {
            return response()->json(['status'=>true,'data'=>$invoices]);
        } 

        return response()->json(['status'=>false]);
    }

    public function postApiInvoiceGrabCount(Request $request) {
        $customer_id = $request->customer_id;

        $count = Invoice::where('customer_id',$customer_id)->count();

        return response()->json($count);
    }

    public function postApiInvoiceSearchHistory(Request $request) {
        $customer_id = $request->customer_id;
        $start = $request->start;
        $end = $request->end;
        if ($end == "END") {
            $invoices = Invoice::with(['invoice_items','invoice_items.inventory','invoice_items.inventoryItem'])
                ->where('customer_id',$customer_id)
                ->orderBy('id','desc')
                ->get();

        } else {
            $invoices = Invoice::where('customer_id',$customer_id)
                ->orderBy('id','desc')
                ->skip($start)
                ->take(10)
                ->get(); 
        }
        
        if (count($invoices) >0){
            return response()->json(['status'=>true,'data'=>$invoices]);
        } 
        return response()->json(['status'=>false]);
    }

    public function postApiSyncCustomer(Request $request) {
        $customer_id = $request->customer_id;

        $invoices = Invoice::where('customer_id',$customer_id)
            ->where('status','<',5)
            ->get();

        if (count($invoices) > 0) {
            foreach ($invoices as $key => $value) {
                $invoice_id = $value->id;
                $iitems = InvoiceItem::where('invoice_id',$invoice_id)->get();
                if (count($iitems) > 0) {
                    $invoices[$key]['invoice_items'] = $iitems;
                }
            }
        } else {
            $invoices = [];
        }
        if (count($invoices) > 0) {
            return response()->json(['status'=>true,'data'=>$invoices]);
        } 

        return response()->json(['status'=>false]);
        
    }

    #InvoiceItem
    public function postApiInvoiceItemGrab(Request $request) {
        $invoice_item = InvoiceItem::find($request->item_id);
        if (!is_null($invoice_item)) {
            
            return response()->json(['status'=>true,'data'=>$invoice_item]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiCreateInvoiceItem(Request $request) {
        $ii = json_decode($request->items,true);
        $taxes = Tax::where('company_id',$ii['company_id'])->orderBy('id','desc')->first();
        $tax_rate = $taxes->rate;
        $subtotal = $ii['pretax'];
        $tax = round($subtotal * $tax_rate,2);
        $total = $subtotal + $tax;
        $invoice_item = new InvoiceItem();
        $invoice_item->invoice_id = $ii['invoice_id'];
        $invoice_item->item_id = $ii['item_id'];
        $invoice_item->inventory_id = $ii['inventory_id'];
        $invoice_item->company_id = $ii['company_id'];
        $invoice_item->customer_id = $ii['customer_id'];
        $invoice_item->quantity = $ii['quantity'];
        $invoice_item->color = $ii['color'];
        $invoice_item->memo = $ii['memo'];
        $invoice_item->pretax = $subtotal;
        $invoice_item->tax = $tax;
        $invoice_item->total = $total;
        $invoice_item->status = $ii['status'];
        
        if ($invoice_item->save()) {
            

            return response()->json(['status'=>true,'data'=>$invoice_item]);
        }
    
        return response()->json(['status'=>false]);
    }

    #Memo
    public function postApiMemosQuery(Request $request) {
        $memos = Memo::where('company_id',$request->company_id)->get();
        if (!is_null($memos)) {
            return response()->json(['status'=>true,'data'=>$memos]);
        }
        return response()->json(['status'=>false]);
    }

    #Profile
    public function postApiCreateProfile(Request $request) {
        $profile = new Profile();
        $p = json_decode($request->profile,true);
        $profile->company_id = $p['company_id'];
        $profile->payment_id = $p['payment_id'];
        $profile->profile_id = $p['profile_id'];
        $profile->status = $c['status'];
        if ($profile->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiProfilesQuery(Request $request) {
        $profiles = Profile::where('company_id',$request->company_id)
        ->where('user_id',$request->customer_id)
        ->get();
        if (count($profiles)>0) {
            return response()->json(['status'=>true,'data'=>$profiles]);
        }
        return response()->json(['status'=>false]);
    }

    #Transaction
    public function postApiTransactionGrab(Request $request) {
        $transactions = Transaction::find($request->transaction_id);
        if (!is_null($transactions)) {
            return response()->json(['status'=>true,'data'=>$transactions]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiTransactionQuery(Request $request) {
        $transactions = Transaction::where('customer_id',$request->customer_id)
        ->orderBy('id','desc')
        ->get();
        if (count($transactions)>0) {
            return response()->json(['status'=>true,'data'=>$transactions]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiTransactionPaymentQuery(Request $request) {
        $transactions = Transaction::where('customer_id',$request->customer_id)
        ->where('status',2)
        ->orderBy('id','desc')
        ->get();
        if (count($transactions)>0) {
            return response()->json(['status'=>true,'data'=>$transactions]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiUpdateTransaction(Request $request) {
        $transactions = Transaction::where('status',3)
        ->where('customer_id',$request->customer_id)
        ->get();
        $t = json_decode($request->transaction,true);
        

        
        if (count($transactions) > 0) {
            foreach ($transactions as $tran) {
                $trans = Transaction::find($tran->id);
                $trans->pretax += $t['pretax'];
                $trans->tax += $t['tax'];
                $trans->aftertax += $t['aftertax'];
                $trans->credit -= $t['credit'];
                $trans->discount += $t['discount'];
                $trans->total += $t['total'];
                $trans->status = 3;
                if ($trans->save()) {
                    $customer = User::find($request->customer_id);
                    $old_total = $customer->account_total;
                    $new_total = $old_total + $t['total'];
                    $customer->account_total = $new_total;
                    if ($customer->save()) {
                        return response()->json(['status'=>true,'data'=>$trans]);
                    }
                    
                }
            }
        }


        return response()->json(['status'=>false]);
    }

    public function postApiPayAccount(Request $request, Transaction $transaction) {
        $transaction_ids = json_decode($request->transaction_ids);
        $check = $transaction->makePayment($transaction_ids, $request->tendered, $request->customer_id);
        if ($check) { // now update the user total
            return response()->json(['status'=>true,'data'=>$check]);
        }

        return response()->json(['status'=>false]);
    }

    public function postApiPayAccountCustomer(Request $request) {
        $customer = User::find($request->customer_id);
        $customer->account_total = $request->balance;
        if ($customer->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }


    public function postApiCreateTransaction(Request $request) {
        $trans = new Transaction;
        $t = json_decode($request->transaction,true);
        $trans->customer_id = $request->customer_id;
        $trans->company_id = $t['company_id'];
        $trans->pretax = $t['pretax'];
        $trans->tax = $t['tax'];
        $trans->aftertax = $t['aftertax'];
        $trans->credit = $t['credit'];
        $trans->discount = $t['discount'];
        $trans->total = $t['total'];
        $trans->account_paid = $t['account_paid'];
        $trans->account_paid_on = $t['account_paid_on'];
        $trans->type = $t['type'];
        $trans->last_four = $t['last_four'];
        $trans->tendered = $t['tendered'];
        $trans->status = $t['status'];
        if ($trans->save()) {
            return response()->json(['status'=>true,'data'=>$trans]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiLastTransactionGrab(Request $request) {
        $trans = Transaction::where('id','>',0)
        ->where('customer_id',$request->customer_id)
        ->orderBy('id','desc')
        ->limit(1)
        ->get();

        if (count($trans)>0) {
            return response()->json(['status'=>true,'data'=>$trans]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiCheckAccount(Request $request) {
        $trans = Transaction::where('customer_id',$request->customer_id)
        ->where('status',3)
        ->get();
        if (count($trans)>0) {
            return response()->json(['status'=>true,'data'=>$trans]);
        }
        return response()->json(['status'=>false]);
    }

    #Schedule
    public function postApiCreateSchedule(Request $request) {
        $s = new Schedule();
        $c = json_decode($request->schedule);
        $s->company_id = $c->company_id;
        $s->customer_id = $c->customer_id;
        $s->card_id = $c->card_id;
        $s->pickup_date = $c->pickup_date;
        $s->pickup_address = $c->pickup_address;
        $s->pickup_delivery_id = $c->pickup_delivery_id;
        $s->dropoff_date = $c->dropoff_date;
        $s->dropoff_address = $c->dropoff_address;
        $s->dropoff_delivery_id = $c->dropoff_delivery_id;
        $s->special_instructions = $c->special_instructions;
        $s->type = $c->type;
        $s->status = $c->status;
        if ($s->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiScheduleQuery(Request $request) {
        $sch = Schedule::where('customer_id',$request->customer_id)
        ->where('status','<',12)
        ->get();
        if (count($sch) > 0) {
            return response()->json(['status'=>true,'data'=>$sch]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiScheduleGrab(Request $request) {
        $sch = Schedule::find($request->id);
        if (!is_null($sch)) {
            return response()->json(['status'=>true,'data'=>$sch]);
        }
        return response()->json(['status'=>false]);
    }

    #Tax
    public function postApiTaxesQuery(Request $request) {
        $taxes = Tax::where('company_id',$request->company_id)
        ->where('status',$request->status)
        ->get();
        if (!is_null($taxes)) {
            return response()->json(['status'=>true,'data'=>$taxes]);
        }
        return response()->json(['status'=>false]);
    }


    #User
    public function postApiUpdateCustomerAccountTotal(Request $request) {
        $user = User::find($request->customer_id);
        $user->account_total = $request->account_total;

        if ($user->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function postApiUpdateCustomerCredits(Request $request) {
        $user = User::find($request->customer_id);
        $user->credits = $request->credits;
        if ($user->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }
    public function postApiUpdateCustomerPickup(Request $request) {
        $user = User::find($request->customer_id);

        $u = json_decode($request->customer,true);
        $old_credits = $user->credits;
        $new_credits = (is_numeric($old_credits)) ? $old_credits - $u['credits'] : 0;
        $user->credits = $new_credits;
        // $user->account_total = $u['account_total'];
        if ($user->save()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    
    public function postApiEditCustomer(Request $request) {
        $customer = User::find($request->customer_id);
        $u = json_decode($request->users,true);
        $customer->company_id =$u['company_id'];
        $customer->phone =$u['phone'];
        $customer->last_name =$u['last_name'];
        $customer->first_name =$u['first_name'];
        $customer->email =$u['email'];
        $customer->invoice_memo =$u['invoice_memo'];
        $customer->important_memo =$u['important_memo'];
        $customer->shirt =$u['shirt'];
        $customer->starch =$u['starch'];
        $customer->street =$u['street'];
        $customer->suite =$u['suite'];
        $customer->city =$u['city'];
        $customer->zipcode =$u['zipcode'];
        $customer->concierge_name =$u['concierge_name'];
        $customer->concierge_number =$u['concierge_number'];
        $customer->special_instructions =$u['special_instructions'];
        $customer->account =$u['account'];

        if ($customer->save()) {
            return response()->json(['status'=>true]);
        }
    
        return response()->json(['status'=>false]);
    }

    public function postApiAddCustomer(Request $request) {
        $customer = new User();
        $u = json_decode($request->users,true);
        $customer->company_id =$u['company_id'];
        $customer->phone =$u['phone'];
        $customer->last_name =$u['last_name'];
        $customer->first_name =$u['first_name'];
        $customer->email =($u['email'] != '') ? $u['email'] : NULL;
        $customer->invoice_memo =($u['invoice_memo'] != '') ? $u['invoice_memo'] : NULL;
        $customer->important_memo =($u['important_memo'] != '') ? $u['important_memo'] : NULL;
        $customer->shirt =$u['shirt'];
        $customer->starch =$u['starch'];
        $customer->street = ($u['street'] != '') ? $u['street'] :  NULL;
        $customer->suite =($u['suite'] != '') ? $u['suite'] :  NULL;
        $customer->city =($u['city'] != '') ? $u['city'] :  NULL;
        $customer->zipcode =($u['zipcode'] != '') ? $u['zipcode'] :  NULL;
        $customer->concierge_name =($u['concierge_name'] != '') ? $u['concierge_name'] :  NULL;
        $customer->concierge_number =($u['concierge_number'] != '') ? $u['concierge_number'] :  NULL;
        $customer->special_instructions =($u['special_instructions'] != '') ? $u['special_instructions'] :  NULL;
        $customer->account =($u['account'] == 1) ? $u['account'] : NULL;
        $customer->role_id = $u['role_id'];


        if ($customer->save()) {
            // Create mark
            $mark = Custid::createOriginalMark($customer);
            $custids = new Custid();
            $custids->company_id = $customer->company_id;
            $custids->customer_id = $customer->id;
            $custids->mark = $mark;
            $custids->status = 1;
            $custids->save();

            return response()->json(['status'=>true,'data'=>$customer]);
        }
    
        return response()->json(['status'=>false]);
    }
    public function postApiCustomerDelete(Request $request) {
        $user = User::find($request->customer_id);
        if ($user->delete()) {
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function getApiSearchCustomer($query = null) {
        $users = new User();
        $query_word_count = explode(" ",$query);
        $results = [];
        if (count($query_word_count) > 1) {
            //check if string
            if (is_string($query)) {
                
                $last_name = $query_word_count[0];
                $first_name = $query_word_count[1];
                // look by last_name and first name
                $results = $users->where('last_name','like',"%".$last_name."%")
                ->where('first_name','like','%'.$first_name.'%')
                ->get();
            }  
        } elseif (count($query_word_count) == 1) {
            //check if string
            if (is_numeric($query)) {
                if (strlen($query) >= 7) { // Phone
                    // first check to see if there is an exact search
                    $results = $users->where('phone',$query)->get();

                    if (count($results) == 0) {
                        $results = $users->where('phone','like','%'.$query.'%')->get();
                    }

                } elseif(strlen($query) == 6) {
                    $invoice = Invoice::find($query);
                    $customer_id = $invoice->customer_id;
                    $results = $users->where('id',$customer_id)->get();
                } else {
                    $results = $users->where('id',$query)->get();
                }
            } else { // check marks table
                $marks = Custid::where('mark',$query)->get();
                if (count($marks) > 0) {
                    foreach ($marks as $mark) {
                        $customer_id = $mark->customer_id;
                        $results = $users->where('id',$customer_id)->get();
                        break;
                    }
                } else {
                    $last_name = $query_word_count[0];
                    // look by last_name and first name
                    $results = $users->where('last_name','like',"%".$last_name."%")
                    ->get();
                }
            }
        } elseif (count($query_word_count) == 6) {

        }
        if (count($results) > 0) {
            foreach ($results as $key => $value) {
                if (isset($value->custids)) {
                    $results[$key]['custids'] = $value->custids;
                }
                
            }
        }

        return response()->json($results);
    }


    public function postApiSearchCustomer(Request $request) {
        $users = new User();
        $query = $request->query;
        $query_word_count = explode(" ",$query);
        $results = [];

        return response()->json($results);
    }

    public function postApiCustomersSearchResults(Request $request) {
        $query = json_decode($request->list,true);
        $query_word_count = count(json_decode($request->list,true));
        $start = $request->start;
        $customers = [];
        if (isset($query[1])) {
            //check if string
                
            $last_name = $query[0];
            $first_name = $query[1];
            // look by last_name and first name
            $customers = User::where('last_name','like',$last_name.'%')
            ->where('first_name','like',$first_name.'%')
            ->orderBy('last_name','asc')
            ->orderBy('first_name','asc')
            ->skip($start)
            ->take(10)
            ->get();
        } else {
            $last_name = $query[0];
            //check if string
            $customers = User::where('last_name','like',$last_name.'%')
                ->orderBy('last_name','asc')
                ->orderBy('first_name','asc')
                ->skip($start)
                ->take(10)
                ->get();
        } 

        if (count($customers) >0){
            foreach ($customers as $key => $value) {
                $customers[$key]['custids'] = $value->custids;
            }
            return response()->json(['status'=>true,'data'=>$customers]);
        } 
        return response()->json(['status'=>false]);
    }

    public function postApiCustomersIn(Request $request) {
        $ids = json_decode($request->ids,true);
        
        $customers = User::with('custids')->whereIn('id',$ids)->get();

        if (count($customers) >0){
            return response()->json(['status'=>true,'data'=>$customers]);
        } 
        return response()->json(['status'=>false]);
    }


    public function postApiCustResults(Request $request) {
        $query = json_decode($request->list,true);
        $query_word_count = count(json_decode($request->list,true));
        $start = $request->start;
        $customers = [];
        if (isset($query[1])) {
            //check if string
                
            $last_name = $query[0];
            $first_name = $query[1];
            // look by last_name and first name
            $customers = User::where('last_name','like',$last_name.'%')
            ->where('first_name','like',$first_name.'%')
            ->orderBy('last_name','asc')
            ->orderBy('first_name','asc')
            ->get();
        } else {
            $last_name = $query[0];
            //check if string
            $customers = User::where('last_name','like',$last_name.'%')
                ->orderBy('last_name','asc')
                ->orderBy('first_name','asc')
                ->get();
        } 

        if (count($customers) >0){
            foreach ($customers as $key => $value) {
                $customers[$key]['custids'] = $value->custids;
            }
            return response()->json(['status'=>true,'data'=>$customers]);
        } 
        return response()->json(['status'=>false]);
    }

    public function postApiCustomersRowCap(Request $request) {
        $query = json_decode($request->list,true);
        $query_word_count = count(json_decode($request->list));
        $customers = 0;
        if (isset($query[1])) {
            //check if string
                
            $last_name = $query[0];
            $first_name = $query[1];
            // look by last_name and first name
            $customers = User::where('last_name','like',$last_name.'%')
            ->where('first_name','like',$first_name.'%')
            ->count();
        } else {
            $last_name = $query[0];
            //check if string
            $customers = User::where('last_name','like',$last_name.'%')->count();
        } 
        return response()->json(['status'=>true,'data'=>$customers]); 
    }

    #zipcode
    public function postApiZipcodeQuery(Request $request) {
        $zips = Zipcode::where('zipcode',$request->zipcode)->get();

        if (count($zips) > 0) {
            return response()->json(['status'=>true,'data'=>$zips]);
        }
        return response()->json(['status'=>false]);
    }
    #Last

    #test
    public function getApiTestWondo() {
        $ids = [1, 1188, 9000, 8787];
        
        $customers = User::with('custids')
        ->whereIn('id',$ids)
        ->orderByRaw(User::raw("FIELD(id, $ids)"))
        ->get();

        if (count($customers) >0){
            return response()->json(['status'=>true,'data'=>$customers]);
        } 
        return response()->json(['status'=>false]);
    }


}
