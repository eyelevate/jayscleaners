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
use App\Inventory;
use App\InventoryItem;
use App\Card;
use App\Color;
use App\Company;
use App\Memo;
use App\Tax;
use App\Schedule;
use App\Transaction;
use App\Invoice;
use App\InvoiceItem;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\CapabilityProfiles\StarCapabilityProfile;
use Mike42\Escpos\Printer;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class InvoicesController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }

    public function getIndex(){

    }

    public function getReport($id = null) {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $due_time = date('Y-m-d 16:00:00');
        $now = date('Y-m-d H:i:s');
        $company_id = Auth::user()->company_id;
        switch($id) {
            case 1:
            $invoices = Invoice::whereBetween('due_date',[$start,$end])
                                        ->where('status','<',5)
                                        ->get();
            break;

            case 2:
            $invoices = Invoice::where('due_date','>=',$start)
                                ->where('due_date','<',$now)
                                ->where('status','<',5)
                                ->get();
            break;

            case 3:
            $invoices = Invoice::whereBetween('due_date',[date('Y-01-01 00:00:00'),date('Y-m-d 00:00:00',strtotime($start.' -1 days'))])
                                            ->where('status','!=',5)
                                            ->where('transaction_id',NULL)
                                            ->get();
            break;

            default:
            $invoices = [];
            break;
        }
        $invoices = Invoice::prepareInvoice($company_id, $invoices);
        $this->layout = 'layouts.dropoff';
        return view('invoices.report')
        ->with('invoices',$invoices)
        ->with('layout',$this->layout);
    }

    public function getAdd($id = null){
        $this->layout = 'layouts.dropoff';
        $customer = User::find($id);
        $inventories = Inventory::where('company_id',Auth::user()->company_id)
        ->where('status',1)
        ->orderBy('ordered','asc')
        ->get();
        $items = InventoryItem::prepareItems($inventories);
        $colors = Color::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();
        $memos = Memo::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();
        $company = Company::where('id',Auth::user()->company_id)->get();
        $store_hours = Company::getStoreHours($company);

        $turnaround_date = Company::getTurnaroundDate($company);
        $turnaround = Company::getTurnaround($company);
        $hours = Company::prepareStoreHours();
        $minutes = Company::prepareMinutes();
        $ampm = Company::prepareAmpm();
        $tax_rate = Tax::where('company_id',Auth::user()->company_id)->where('status',1)->first();

        return view('invoices.add')
        ->with('layout',$this->layout)
        ->with('customer',$customer)
        ->with('inventories',$inventories)
        ->with('items',$items)
        ->with('colors',$colors)
        ->with('memos',$memos)
        ->with('turnaround',$turnaround)
        ->with('turnaround_date',$turnaround_date)
        ->with('store_hours',$store_hours)
        ->with('hours',$hours)
        ->with('minutes',$minutes)
        ->with('ampm',$ampm)
        ->with('tax_rate',$tax_rate->rate);
    }

    public function postAdd(Request $request){
        $items = Input::get('item');
        $company_id = Auth::user()->company_id;
        $tax_rate = $tax_rate = Tax::where('company_id',$company_id)->where('status',1)->first()->rate;
        
        $last_saved_id = Invoice::where('company_id',$company_id)->orderBy('id','desc')->limit(1)->pluck('invoice_id');

        if(count($items) > 0) { //Check if any items were selected
            // what type of print out?
            $print_type = $request->store_copy;
            // create a new invoice id (this is different than invoices->id it is its own identification)
            $new_invoice_id = (count($last_saved_id) > 0) ? $last_saved_id[0] : 0;

            foreach ($items as $itms) { // iterate through the first index (inventory group)
                // create a new invoice id (this is different than invoices->id it is its own identification)
                $new_invoice_id++;

                // create new invoice object and prep for saving
                $invoice = new Invoice();
                $invoice->invoice_id = $new_invoice_id;
                $invoice->company_id = $company_id;
                $invoice->customer_id = $request->customer_id;
                $invoice->due_date = date('Y-m-d H:i:s',strtotime($request->due_date));
                $invoice->status = 1;   
                $qty = 0;
                $subtotal = 0;
                $tax = 0;
                $total = 0;   
                if($invoice->save()){ // save the invoice
                    
                    foreach ($itms as $i) {
                        foreach ($i as $ikey => $ivalue) {
                            #get color name
                            $colors = Color::find($ivalue['color']);
                            $color_name = ($colors) ? $colors->name : $ivalue['color'];
                            $qty++;
                            $item = new InvoiceItem();
                            $item->invoice_id = $invoice->invoice_id;
                            $item->company_id = $company_id;
                            $item->customer_id = $request->customer_id;
                            $item->item_id = $ivalue['item_id'];
                            $item->pretax = $ivalue['price'];
                            $item->tax = number_format(round($ivalue['price'] * $tax_rate,2),2,'.','');
                            $item->total = number_format(round($ivalue['price'] * (1+$tax_rate),2),2,'.','');
                            $subtotal += $ivalue['price'];
                            $item->quantity = 1;
                            $item->color = $color_name;
                            $item->memo = $ivalue['memo'];
                            $item->status = 1;
                            $item->save();
                        }

                    }
                    // get totals here and update invoice
                    $edit = Invoice::find($invoice->id);
                    $edit->quantity = $qty;
                    $edit->pretax = $subtotal;
                    $edit->tax = number_format(round($subtotal * $tax_rate,2),2,'.','');
                    $edit->total = number_format(round($subtotal * (1+$tax_rate),2),2,'.','');
                    $edit->save();

                    // Do printer logic here


             
                }          
            }
            // all finished
            Flash::success('Successfully added a new inventory!');
            return Redirect::route('customers_view',$invoice->customer_id);  

        } else {
            Flash::warning('Could not save your invoice! Please select an invoice item');
            return Redirect::route('invoices_dropoff',$request->customer_id); 
        }   
    }

    public function getEdit($id = null){
        $this->layout = 'layouts.dropoff';
        $customer = User::find($id);
        $inventories = Inventory::where('company_id',Auth::user()->company_id)
        ->where('status',1)
        ->orderBy('ordered','asc')
        ->get();
        $items = InventoryItem::prepareItems($inventories);
        $colors = Color::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();
        $memos = Memo::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();
        $company = Company::where('id',Auth::user()->company_id)->get();
        $store_hours = Company::getStoreHours($company);

        $turnaround_date = Company::getTurnaroundDate($company);
        $turnaround = Company::getTurnaround($company);
        $hours = Company::prepareStoreHours();
        $minutes = Company::prepareMinutes();
        $ampm = Company::prepareAmpm();
        $tax_rate = Tax::where('company_id',Auth::user()->company_id)->where('status',1)->first();

        $invoice_items = InvoiceItem::prepareEdit(InvoiceItem::where('invoice_id',$id)->where('status',1)->get());

        $invoice_grouped = InvoiceItem::prepareGroup($invoice_items);

        return view('invoices.edit')
        ->with('layout',$this->layout)
        ->with('customer',$customer)
        ->with('inventories',$inventories)
        ->with('items',$items)
        ->with('colors',$colors)
        ->with('memos',$memos)
        ->with('turnaround',$turnaround)
        ->with('turnaround_date',$turnaround_date)
        ->with('store_hours',$store_hours)
        ->with('hours',$hours)
        ->with('minutes',$minutes)
        ->with('ampm',$ampm)
        ->with('tax_rate',$tax_rate->rate)
        ->with('invoice_id',$id)
        ->with('invoice_items',$invoice_items)
        ->with('invoice_grouped',$invoice_grouped);
    }

    public function postEdit(Request $request) {
        $items = Input::get('item');
        $tax_rate = $tax_rate = Tax::where('company_id',Auth::user()->company_id)->where('status',1)->first()->rate;
        if(count($items) > 0) { //Check if any items were selected


            // what type of print out?
            $print_type = $request->store_copy;

            foreach ($items as $itms) { // iterate through the first index (inventory group)
                $invoices = Invoice::where('invoice_id',$request->invoice_id)->get();
                if (count($invoices) > 0) {
                    foreach ($invoices as $inv) {
                        $invoice = Invoice::find($inv->id);
                        $invoice->due_date = date('Y-m-d H:i:s',strtotime($request->due_date));
                        $invoice->status = 1;   
                        $qty = 0;
                        $subtotal = 0;
                        $tax = 0;
                        $total = 0;
                        if($invoice->save()){ // save the invoice
                            // Get all the previously saved invoice items. remove any deleted ones by comparing existing items
                            $previous = InvoiceItem::where('invoice_id',$request->invoice_id)->where('status',1)->get();
                            if(isset($previous)) {
                                foreach ($previous as $pkey => $pvalue) {
                                    $rmv = true;
                                    //compare to new items
                                    foreach ($items as $itms) {
                                        foreach ($itms as $i) {

                                            foreach ($i as $ikey => $ivalue) {
                                                if(isset($ivalue['id'])){

                                                    if($ivalue['id'] == $pvalue->id){ 
                                                        $rmv = false;
                                                        break;
            
                                                    } 
                                                }
                                            }
                                        }
                                    }
                                    if($rmv) {
                                        $del = InvoiceItem::find($pvalue->id);
                                        $del->delete();
                                    }

                                }
                            }
                            // update and save the rest
                            foreach ($itms as $i) {
                                foreach ($i as $ikey => $ivalue) {
                                    $qty++;
                                    if (is_numeric($ivalue['color'])) {
                                        $colors = Color::find($ivalue['color']);
                                        $color_name = ($colors) ? $colors->name : $ivalue['color'];
                                    } else {
                                        $color_name = $ivalue['color'];
                                    }

                                    $item = (isset($ivalue['id'])) ? InvoiceItem::find($ivalue['id']) : new InvoiceItem();
                                    $item->item_id = $ivalue['item_id'];
                                    $item->invoice_id = $request->invoice_id;
                                    $item->pretax = $ivalue['price'];
                                    $item->tax = number_format(round($ivalue['price'] * $tax_rate,2),2,'.','');
                                    $item->total = number_format(round($ivalue['price'] * (1+$tax_rate),2),2,'.','');
                                    $subtotal += $ivalue['price'];
                                    $item->quantity = 1;
                                    $item->color = $color_name;
                                    $item->memo = $ivalue['memo'];
                                    $item->status = 1;
                                    $item->save();
                                }

                            }
                            // get totals here and update invoice
                            $edits = Invoice::where('invoice_id',$request->invoice_id)->get();
                            if (count($edits)> 0) {
                                foreach ($edits as $e) {
                                    $edit = Invoice::find($e->id);
                                    $edit->quantity = $qty;
                                    $edit->pretax = $subtotal;
                                    $edit->tax = number_format(round($subtotal * $tax_rate,2),2,'.','');
                                    $edit->total = number_format(round($subtotal * (1+$tax_rate),2),2,'.','');
                                    $edit->save();
                                }
                            }

                            // Do printer logic here
                        }  
                    }
                }   
        
            }
            // all finished
            Flash::success('Successfully added a new inventory!');
            return Redirect::route('customers_view',$invoice->customer_id);  

        } else {
            Flash::warning('Could not save your invoice! Please select an invoice item');
            return Redirect::route('invoices_dropoff',$request->customer_id); 
        }   
    }

    public function getDelete($id = null) {
        $invoices = Invoice::find($id);
        if ($invoices->delete()) {
            $invoice_id = $invoices->invoice_id;
            $company_id = $invoices->company_id;
            $items = InvoiceItem::where('company_id',$company_id)->where('invoice_id',$invoice_id)->get();
            if (count($items)>0) {
                foreach ($items as $item) {
                    $item_id = $item->id;
                    $itms = InvoiceItem::find($item_id);
                    $itms->delete();
                }
            }
            Flash::success('Successfully delete invoice #'.$invoice_id);
        }
        return Redirect::back();
    }

    public function getPickup($id = null) {
        $customer_id = $id;
        $invoices = Invoice::where('customer_id',$customer_id)->where('transaction_id',NULL)->get();
        $cards = Card::where('user_id',$id)->where('company_id',Auth::user()->company_id)->get();

        $companies = Company::find(Auth::user()->company_id);
        $cards_data = [];
        $payment_ids = [];
        if (count($cards) > 0) {
            foreach ($cards as $key => $card) {
                $profile_id = $card->profile_id;
                $payment_id = $card->payment_id;
                $exp_month = $card->exp_month;
                $exp_year = $card->exp_year;
                $street = $card->street;
                $suite = $card->suite;
                $city = $card->city;
                $state = $card->state;
                $status = $card->status;

                $exp_full_time = strtotime($exp_year.'-'.$exp_month.'-01 00:00:00');
                $today = strtotime(date('Y-m-d H:i:s'));
                $difference = $exp_full_time - $today;
                $days_remaining = floor($difference/60/60/24);
                $days_comment = ($days_remaining > 0) ? $days_remaining.' day(s) remaining.' : 'Expired!';
                if ($difference < 0) {
                    $exp_status = 3; // expired
                } elseif(($exp_full_time < strtotime('+1 month'))) {
                    $exp_status = 2; // Within a month
                } else {
                    $exp_status = 1; // good
                }

                switch($exp_status) {
                    case 2:
                    $background_color = '#FCF8E3';
                    break;

                    case 3:
                    $background_color = '#F2DEDE';
                    break;

                    default:
                    $background_color = '';
                    break;
                }
                $cards_data[$key] = [
                    'id' => $card->id,
                    'profile_id' => $profile_id,
                    'payment_id' => $payment_id,
                    'exp_month' => $exp_month,
                    'exp_year' => $exp_year,
                    'exp_status' => $exp_status,
                    'status' => $status,
                    'days_remaining' => $days_comment,
                    'background_color'=>$background_color
                ];

                // Common setup for API credentials (merchant)
                $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
                $merchantAuthentication->setName($companies->payment_api_login);
                $merchantAuthentication->setTransactionKey($companies->payment_gateway_id);
                $refId = 'ref' . time();

                //request requires customerProfileId and customerPaymentProfileId
                $request = new AnetAPI\GetCustomerPaymentProfileRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setRefId( $refId);
                $request->setCustomerProfileId($profile_id);
                $request->setCustomerPaymentProfileId($payment_id);

                $controller = new AnetController\GetCustomerPaymentProfileController($request);
                $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
                if(($response != null)){
                    if ($response->getMessages()->getResultCode() == "Ok")
                    {
                        $card_number = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber();
                        $card_type = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardType();
                        $cards_data[$key]['card_number'] = $card_number;
                        $cards_data[$key]['card_type'] = $card_type;
                        $card_first_name = $response->getPaymentProfile()->getBillTo()->getFirstName();
                        $card_last_name = $response->getPaymentProfile()->getBillTo()->getLastName();
                        $cards_data[$key]['first_name'] = $card_first_name;
                        $cards_data[$key]['last_name'] = $card_last_name;
                        switch($card_type) {
                            case 'Visa':
                                $cards_data[$key]['card_image'] = '/imgs/icons/visa.jpg';
                            break;
                            case 'MasterCard':
                                $cards_data[$key]['card_image'] = '/imgs/icons/master.jpg';
                            break;
                            case 'Amex':
                                $cards_data[$key]['card_image'] = '/imgs/icons/amex.jpg';
                            break;

                            case 'Discover':
                                $cards_data[$key]['card_image'] = '/imgs/icons/discover.jpg';
                            break;

                            default:
                                $cards_data[$key]['card_image'] = '';
                            break;
                        }
                        if ($difference < 0) { // expired
                            $payment_ids[$payment_id] = $card_type.' '.$card_number.' ***'.$days_comment.'***';  
                        } elseif(($exp_full_time < strtotime('+1 month'))) { // Within a month
                            $payment_ids[$payment_id] = $card_type.' '.$card_number.' '.$days_comment;
                        } else {  // good
                            $payment_ids[$payment_id] = $card_type.' '.$card_number.' '.$days_comment;
                        }
                        // Job::dump($response->getPaymentProfile());
                    }
                }               
            }
        }


        $this->layout = 'layouts.dropoff';
        return view('invoices.pickup')
        ->with('customer_id',$customer_id)
        ->with('cards',$payment_ids)
        ->with('invoices',$invoices)
        ->with('layout',$this->layout);

    }

    public function postPickup(Request $request) {
        $company_id = Auth::user()->company_id;
        $customer_id = $request->customer_id;
        $invoice_ids = $request->invoice_id;
        Job::dump($invoice_ids);
        $invoices = Invoice::whereIn('id',$request->invoice_id)->get();
        $selected = Invoice::prepareSelected($invoices);

        $type = $request->type;
        switch($type) {
            case 'credit':
                $transaction_type = 1;
            break;
            case 'cash':
                $transaction_type = 2;
            break;
            case 'check':
                $transaction_type = 3;
            break;
            default: // card on file
                $transaction_type = 4;
            break;
        }
        $last_four =($request->last_four) ? $request->last_four : NULL;

        if ($type == 'cof') {
            $profile_id = false;
            $payment_id = false;
            $cards = Card::where('payment_id',$request->payment_id)->get();
            if (count($cards)> 0) {
                foreach ($cards as $card) {
                    $profile_id = $card->profile_id;
                    $payment_id = $card->payment_id;                    
                }
                $valid_card_check = Card::checkValid($company_id, $profile_id, $payment_id);
                // Card is not valid error
                if (!$valid_card_check) {
                    Flash::error('Credit card on file did not validate. Please contact customer for new card and reschedule delivery.');
                    return Redirect::back();
                }

                $attempt_payment = Schedule::makePayment($company_id, $profile_id, $payment_id, $selected['totals']['total']);

                if (!$attempt_payment) {
                    Flash::error('Error: '.$attempt_payment['error_message']);
                    return Redirect::back();
                }
            } else {
                Flash::error('Error: no such card.');
                return Redirect::back();                
            }
        } 

        $transactions = new Transaction();
        $transactions->company_id = $company_id;
        $transactions->customer_id = $customer_id;
        $transactions->pretax = $selected['totals']['subtotal'];
        $transactions->tax = $selected['totals']['tax'];
        
        $transactions->aftertax = $selected['totals']['total'];
        $transactions->discount = NULL;
        $transactions->total = $selected['totals']['total'];
        $transactions->type = $transaction_type;
        $transactions->status = 1;
        $transactions->tendered = $request->tendered ? $request->tendered : NULL;
        $transactions->last_four = $last_four;
        if ($transactions->save()) {
            $transaction_id = $transactions->id;
            if (count($invoices) > 0) {
                foreach ($invoices as $invoice) {
                    $invoice_id = $invoice->id;
                    $invs = Invoice::find($invoice_id);
                    $invs->transaction_id = $transaction_id;
                    $invs->status = 5;
                    $invs->save();
                }
            }

            Flash::success('Transaction finished. Invoices are complete');
            return Redirect::route('customers_view',$customer_id);
        }

        Flash::error('There was an error with the transaction.');
        return Redirect::back();

    }

    public function postSelect(Request $request) {
        if ($request->ajax()) {
            $ids = $request->invoice_ids;
            $invoices = Invoice::whereIn('id',$ids)->get();
            if (count($invoices) > 0) {
                $selected = Invoice::prepareSelected($invoices);
                return response()->json([
                    'status'=> true,
                    'invoice_data' => $selected
                ]);
            } else {
                return response()->json([
                    'status'=> false,
                    'invoice_data' => false
                ]);               
            }


        }
    }

    public function getView($id = null) {
        return view('invoices.view')
        ->with('layout',$this->layout);
    }

    public function getHistory($id = null) {
        $invoices = Invoice::prepareInvoice(Auth::user()->company_id,Invoice::where('customer_id',$id)->orderBy('id','desc')->get());
    }

    public function getRack(Request $request, $id = null){
        $racks = ($request->session()->has('racks')) ? $request->session()->get('racks')  : false;
        $this->layout = 'layouts.dropoff';
        return view('invoices.rack')
        ->with('racks',$racks)
        ->with('id',$id)
        ->with('layout',$this->layout);
    }

    public function postRack(Request $request) {
        $racks = $request->rack;
        $id = $request->id;

        if (count($racks) > 0) {
            foreach ($racks as $key => $value) {
                $invoices = Invoice::where('invoice_id',$key)->get();
                if (count($invoices) > 0) {
                    foreach ($invoices as $invoice) {
                        $invoice_id = $invoice->id;
                        $invs = Invoice::find($invoice_id);
                        $invs->status = 2;
                        $invs->rack_date = date('Y-m-d H:i:s');
                        $invs->rack = $value;
                        $invs->save();
                    }
                }
            }
            $request->session()->pull('racks');
            Flash::success('Successfully racked invoices.');
            if ($id) {
                return Redirect::route('customers_view',$id);
            } else {
                return Redirect::route('admin_index');
            }
            

        } else {
            Flash::error('No racks were entered in to be saved. Please try again.');
            return Redirect::back();
        }
    }

    public function postRackUpdate(Request $request) {
        if ($request->ajax()) {
            $invoice_id = $request->invoice_id;
            $rack_number = $request->rack_number;
            if ($request->session()->has('racks')) {
                $racks = $request->session()->get('racks');
                $racks[$invoice_id] = $rack_number;
                $request->session()->put('racks',$racks);
            } else {
                $request->session()->put('racks',[$invoice_id=>$rack_number]);
            }

            return response()->json([
                'status'=> true,
                'racks' => $request->session()->get('racks')
            ]);
        }
    }
    public function postRackRemove(Request $request) {
        if ($request->ajax()) {
            $invoice_id = $request->invoice_id;
            if ($request->session()->has('racks')) {
                $racks = $request->session()->get('racks');
                unset($racks[$invoice_id]);
                $request->session()->put('racks',$racks);
            } 
            
            return response()->json([
                'status'=> true,
                'racks' => $request->session()->get('racks')
            ]);
        }
    }

    public function postFeed(Request $request) {
        if($request->ajax()){
            $test = $request->test;
            return response()->json([
                'title'=> $test,
                'start' => '2016-03-13T11:00:00',
                'constraint'=> 'availableForMeeting', // defined below
                'color'=> '#257e4a'
            ]);
        }
    }

    public function getTest(){


try {
    $connector = new CupsPrintConnector("TSP100LAN");
    // $connector = new NetworkPrintConnector("10.1.10.10", 9100); 
    /* Print a "Hello world" receipt" */
    $printer = new Printer($connector);
    $printer -> text("Hello World!\n");
    $printer -> cut();
    
    /* Close printer */
    $printer -> close();

} catch(Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}
// $connector = new FilePrintConnector("php://stdout");
// $printer = new Printer($connector);
// $printer -> text("Hello World!\n");
// $printer -> cut();
// $printer -> close();

        return view('invoices.test')
        ->with('layout',$this->layout);        
    }
}