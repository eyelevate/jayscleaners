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
use App\Discount;
use App\Memo;
use App\Tax;
use App\Report;
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
            $itemsToInventory = Report::itemsToInventory($company_id);
            foreach ($items as $itms) { // iterate through the first index (inventory group)
                // create a new invoice id (this is different than invoices->id it is its own identification)
                $new_invoice_id++;

                // create new invoice object and prep for saving
                $invoice = new Invoice();
                // $invoice->invoice_id = $new_invoice_id;
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
                            $item->invoice_id = $invoice->id;
                            $item->company_id = $company_id;
                            $item->customer_id = $request->customer_id;
                            $item->item_id = $ivalue['item_id'];
                            $item->inventory_id = $itemsToInventory[$ivalue['item_id']];
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

        $invoices = Invoice::find($id);
        $company_id = $invoices->company_id;
        $customer_id = $invoices->customer_id;

        $customer = User::find($customer_id);
        $inventories = Inventory::where('company_id',$company_id)
        ->where('status',1)
        ->orderBy('ordered','asc')
        ->get();
        $items = InventoryItem::prepareItems($inventories);
        $colors = Color::where('company_id',$company_id)->orderBy('ordered','asc')->get();
        $memos = Memo::where('company_id',$company_id)->orderBy('ordered','asc')->get();
        $company = Company::where('id',$company_id)->get();
        $store_hours = Company::getStoreHours($company);

        $turnaround_date = Company::getTurnaroundDate($company);
        $turnaround = Company::getTurnaround($company);
        $hours = Company::prepareStoreHours();
        $minutes = Company::prepareMinutes();
        $ampm = Company::prepareAmpm();
        $tax_rate = Tax::where('company_id',$company_id)->where('status',1)->first();

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
            $itemsToInventory = Report::itemsToInventory($company_id);
            foreach ($items as $itms) { // iterate through the first index (inventory group)
                $invoices = Invoice::find($request->invoice_id);
                $invoices->due_date = date('Y-m-d H:i:s',strtotime($request->due_date));
                $invoices->status = 1;   
                $qty = 0;
                $subtotal = 0;
                $tax = 0;
                $total = 0;
                if($invoices->save()){ // save the invoice
                    // Get all the previously saved invoice items. remove any deleted ones by comparing existing items
                    $previous = InvoiceItem::where('invoice_id',$request->invoice_id)->where('status',1)->get();
                    if(isset($previous)) {

                        foreach ($previous as $pkey => $pvalue) {
                            $rcheck = true;
                            //compare to new items
                            foreach ($items as $ritms) {
                                foreach ($ritms as $i) {

                                    foreach ($i as $rikey => $rivalue) {
                                        if(isset($rivalue['id'])){

                                            if($rivalue['id'] == $pvalue->id){ 
                                                $rcheck = false;
                                            } 
                                        }
                                    }
                                }
                            }

                            if ($rcheck) {
                                $del = InvoiceItem::find($pvalue->id);
                                $del->delete();                                
                            }


                        }
                    }
                    // update and save the rest
                    if (count($itms) > 0) {
                        Job::dump($itms);
                        foreach ($itms as $i) {
                            foreach ($i as $ikey => $ivalue) {
                                $qty++;
                                if (isset($ivalue['color'])) {
                                    if (is_numeric($ivalue['color'])) {
                                        $colors = Color::find($ivalue['color']);
                                        $color_name = ($colors) ? $colors->name : $ivalue['color'];
                                    } else {
                                        $color_name = $ivalue['color'];
                                    }
                                } else {
                                    $color_name = NULL;
                                }
                                if (isset($ivalue['item_id'])) {
                                    $item = (isset($ivalue['id'])) ? InvoiceItem::find($ivalue['id']) : new InvoiceItem();
                                    $item->item_id = (isset($ivalue['item_id'])) ? $ivalue['item_id'] : NULL;
                                    $item->inventory_id = (isset($ivalue['item_id'])) ? $itemsToInventory[$ivalue['item_id']] : NULL;
                                    $item->invoice_id = $request->invoice_id;
                                    $item->company_id = Auth::user()->company_id;
                                    $item->customer_id = $invoices->customer_id;
                                    $item->pretax = (isset($ivalue['price'])) ? $ivalue['price'] : NULL;
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

                        }
                    }
                    // get totals here and update invoice
                    $edits = Invoice::find($request->invoice_id);
                    $edits->quantity = $qty;
                    $edits->pretax = $subtotal;
                    $edits->tax = number_format(round($subtotal * $tax_rate,2),2,'.','');
                    $edits->total = number_format(round($subtotal * (1+$tax_rate),2),2,'.','');
                    $edits->save();


                    // Do printer logic here
                }  
        
            }
            // all finished
            Flash::success('Successfully updated inventory!');
            return Redirect::route('customers_view',$invoices->customer_id);  

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
            $items = InvoiceItem::where('company_id',$company_id)->where('invoice_id',$id)->get();
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
        $invoices = Invoice::where('customer_id',$customer_id)
            ->where('transaction_id',NULL)
            ->where('status','<',5)
            ->get();
        $customer = User::find($customer_id);
        $credit_amount = $customer->credits;
        $cards = Card::where('user_id',$id)->where('company_id',Auth::user()->company_id)->get();
        $discounts = Discount::prepareApprovedSelect();
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
                $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
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
        ->with('customers',$customer)
        ->with('credits',$credit_amount)
        ->with('cards',$payment_ids)
        ->with('invoices',$invoices)
        ->with('discounts',$discounts)
        ->with('layout',$this->layout);

    }

    public function postPickup(Request $request) {
        $company_id = Auth::user()->company_id;
        $customer_id = $request->customer_id;
        $invoice_ids = $request->invoice_id;

        $invoices = Invoice::whereIn('id',$request->invoice_id)->get();
        $selected = Invoice::prepareSelected($invoices);
        $customers = User::find($customer_id);
        $credits = ($customers->credits) ? $customers->credits : 0;
        $type = $request->type;
        $transaction_status = 1;
        $account_check = false;
        switch($type) {
            case 'account':
                $account_transaction_id = false;
                $transaction_type = 5;
                // check transactions for existing open transaction ticket
                $account_transactions = Transaction::where('customer_id',$customer_id)->where('status',3)->get();
                if (count($account_transactions) > 0) {
                    foreach ($account_transactions as $at) {
                        $account_transaction_id = $at->id;
                    }
                } else {
                    $account_transaction_id = false;
                }
                $transaction_status = 3;
                $account_check = ($account_transaction_id) ? true : false;
            break;
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

        $discount = 0;
        if ($request->session()->has('discount_id')) {
            $discount_id = $request->session()->pull('discount_id');
            if ($discount_id > 0) {
                $discountable_total = 0;
                $discounts = Discount::find($discount_id);
                $inventory_id = $discounts->inventory_id;
                $inventory_item_id = $discounts->inventory_item_id;
                if (count($invoices) > 0) {
                    foreach ($invoices as $invoice) {
                        $invoice_id = $invoice->id;
                        if (isset($inventory_id)) {
                            $invoice_items = InvoiceItem::where('invoice_id',$invoice_id)
                                ->where('inventory_id',$inventory_id)
                                ->get();
                        
                        
                            if (count($invoice_items) > 0) {
                                foreach ($invoice_items as $invoice_item) {
                                    $item_id = $invoice_item->item_id;
                                    $item_price = $invoice_item->total;
                                    $discountable_total += $item_price;
                                }
                            }
                        } else {
                             $invoice_items = InvoiceItem::where('invoice_id',$invoice_id)
                                ->where('item_id',$inventory_item_id)
                                ->get();
                        
                        
                            if (count($invoice_items) > 0) {
                                foreach ($invoice_items as $invoice_item) {
                                    $item_id = $invoice_item->item_id;
                                    $item_price = $invoice_item->total;
                                    $discountable_total += $item_price; 
                                }
                            }                           
                        }
                        
                    }
                }

                $type = $discounts->type;
                if ($type == 1) {
                    $rate = $discounts->rate;
                    $discount = money_format('%i',($discountable_total * $rate));
                } else {
                    $price = $discounts->price;
                    $discount = money_format('%i',($discountable_total - $price));
                }

            }
        }


        if ($account_check){
            $transactions = Transaction::find($account_transaction_id);
            $transactions->company_id = $company_id;
            $transactions->customer_id = $customer_id;
            $old_pretax = $transactions->pretax;
            $old_tax = $transactions->tax;
            $old_discount = $transactions->discount;
            $old_credit = $transactions->credit;
            $old_aftertax = $transactions->aftertax;
            $old_due = $transactions->total;
            $new_pretax = $selected['totals']['subtotal'] + $old_pretax;
            $new_tax = $selected['totals']['tax'] + $old_tax;
            $new_aftertax = $selected['totals']['total'] + $old_aftertax;
            $new_discount = $old_discount + $discount;
            $discounted_total = $selected['totals']['total'] - $discount;
            $credit_spent = (($credits - $discounted_total) >= 0) ? $discounted_total : $credits;
            $total_due = $discounted_total - $credit_spent;
            $new_total_due = $old_due + $total_due;
            $new_credit = $old_credit + $credit_spent;

            $transactions->pretax = $new_pretax;
            $transactions->tax = $new_tax;
            $transactions->aftertax = $new_aftertax;
            $transactions->discount = $new_discount;
            $transactions->credit = $new_credit;
            $transactions->total = $new_total_due;
            $transactions->type = $transaction_type;
            $transactions->status = $transaction_status;
            $transactions->tendered = 0;
        } else {
            $transactions = new Transaction();
            $transactions->company_id = $company_id;
            $transactions->customer_id = $customer_id;
            $transactions->pretax = $selected['totals']['subtotal'];
            $transactions->tax = $selected['totals']['tax'];
            $transactions->aftertax = $selected['totals']['total'];
            $discounted_total = $transactions->aftertax - $discount;
            $credit_spent = (($credits - $discounted_total) >= 0) ? $discounted_total : $credits;
            $transactions->credit = $credit_spent;
            $transactions->discount = $discount;
            $total_due = $discounted_total - $credit_spent;
            $transactions->total = $total_due;
            $transactions->type = $transaction_type;
            $transactions->status = $transaction_status;
            $transactions->tendered = $request->tendered ? $request->tendered : 0;
            $transactions->last_four = $last_four;
        }

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
            if($customers){
                $save_check = false;
                // calculate the new credits
                if ($credit_spent > 0) {
                    $new_credit = $credits - $credit_spent;
                    $customers->credits = $new_credit;
                    $save_check = true;
                }
                // calculate new accounts
                if ($customers->account) {
                    $old_account_total = ($customers->account_total) ? $customers->account_total : 0;
                    $new_account_total = $old_account_total + $total_due;
                    $customers->account_total = $new_account_total;
                    $save_check = true;
                }
                if ($save_check) {
                    $customers->save();
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
            $discount_id = $request->discount_id;
            $request->session()->put('discount_id',$discount_id);
            $customer_id = $request->customer_id;
            $customers = User::find($customer_id);
            $credits = ($customers->credits) ? $customers->credits : 0;
            $invoices = Invoice::whereIn('id',$ids)->get();
            $discount = 0;
            if (count($invoices) > 0) {
                $selected = Invoice::prepareSelected($invoices);
                
                $total = $selected['totals']['total'];

                // discount rules
                if ($discount_id > 0) {
                    $discountable_total = 0;
                    $discounts = Discount::find($discount_id);
                    $inventory_id = $discounts->inventory_id;
                    $inventory_item_id = $discounts->inventory_item_id;

                    foreach ($invoices as $invoice) {
                        $invoice_id = $invoice->id;
                        if (isset($inventory_id)) {
                            $invoice_items = InvoiceItem::where('invoice_id',$invoice_id)
                                ->where('inventory_id',$inventory_id)
                                ->get();
                        
                        
                            if (count($invoice_items) > 0) {
                                foreach ($invoice_items as $invoice_item) {
                                    $item_id = $invoice_item->item_id;
                                    $item_price = $invoice_item->total;
                                    $discountable_total += $item_price;
                                }
                            }
                        } else {
                             $invoice_items = InvoiceItem::where('invoice_id',$invoice_id)
                                ->where('item_id',$inventory_item_id)
                                ->get();
                        
                        
                            if (count($invoice_items) > 0) {
                                foreach ($invoice_items as $invoice_item) {
                                    $item_id = $invoice_item->item_id;
                                    $item_price = $invoice_item->total;
                                    $discountable_total += $item_price; 
                                }
                            }                           
                        }
                        
                    }

                    $type = $discounts->type;
                    if ($type == 1) {
                        $rate = $discounts->rate;
                        $discount = money_format('%i',($discountable_total * $rate));
                    } else {
                        $price = $discounts->price;
                        $discount = money_format('%i',($discountable_total - $price));
                    }
                }

                $total_due = (($total - $credits - $discount) > 0) ? ($total - $credits - $discount) : 0; 
                return response()->json([
                    'status'=> true,
                    'invoice_data' => $selected,
                    'credits'=>$credits,
                    'discount'=>$discount,
                    'discount_html'=>money_format('($%i)',$discount),
                    'total_due' => $total_due,
                    'total_due_html'=>money_format('$%i',$total_due)
                ]);
            } else {
                return response()->json([
                    'status'=> false,
                    'invoice_data' => false,
                    'credits' => false
                ]);               
            }


        }
    }

    public function getView($id = null) {
        return view('invoices.view')
        ->with('layout',$this->layout);
    }

    public function getHistory($id = null) {
        $invoices = Invoice::prepareInvoice(Auth::user()->company_id,Invoice::where('customer_id',$id)->orderBy('id','desc')->paginate(20));
        $revert = Invoice::prepareRevert();

        $this->layout = 'layouts.dropoff';

        return view('invoices.history')
        ->with('invoices',$invoices)
        ->with('customer_id',$id)
        ->with('revert',$revert)
        ->with('layout',$this->layout);    

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
                $invoices = Invoice::find($key);
                $invoices->status = 2;
                $invoices->rack_date = date('Y-m-d H:i:s');
                $invoices->rack = $value;
                $invoices->save();
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

    public function postRevert(Request $request) {
        $new_status = $request->status;
        $invoices = Invoice::find($request->id);
        $old_status = $invoices->status;
        if ($old_status == 5 || $old_status == 3) { // revert payments 
            $transaction_id = $invoices->transaction_id;
            $customer_id = $invoices->customer_id;
            $company_id = $invoices->company_id;
            if (isset($transaction_id)) {
                $transactions = Transaction::find($transaction_id);

                $payment_transaction_id = ($transactions->transaction_id) ? $transactions->transaction_id : false;
                if ($payment_transaction_id) { // Online payment 
                    $void = Card::makeVoid($company_id, $payment_transaction_id);
                    if(!$void['status']) {
                        Flash::error('Error: '.$void['message']);
                        return Redirect::back();
                    }
                }
            }


            $invoices->transaction_id = NULL; 

        } 
        
        $invoices->status = $new_status;

        if ($invoices->save()) {
            Flash::success('Successfully updated status');
            return Redirect::back();
        }
    }

    public function getManage(Request $request) {
        $locations = InvoiceItem::prepareLocation();
        $companies = Company::getCompany();
        $invoices = ($request->session()->has('invoice')) ? Invoice::prepareInvoice(Auth::user()->company_id,$request->session()->pull('invoice')) : [];
        $invoice_id = ($request->session()->has('invoice_id')) ? $request->session()->pull('invoice_id') : NULL;
        $split = Invoice::splitInvoiceItems($invoices);
        $this->layout = 'layouts.dropoff';
        return view('invoices.manage')
        ->with('layout',$this->layout)
        ->with('invoices',$invoices)
        ->with('invoice_id',$invoice_id)
        ->with('split',$split)
        ->with('companies',$companies)
        ->with('locations', $locations);
    }

    public function postSearch(Request $request) {
        //Validate the request
        $this->validate($request, [
            'search' => 'required'
        ]); 

        $invoices = Invoice::where('id',$request->search)->get();
        $request->session()->put('invoice',$invoices);
        $request->session()->put('invoice_id',$request->search);
        if (count($invoices) > 0) {
            Flash::success('Invoice found. Please edit details below.');
        } else {
            Flash::error('No such invoice. Please try again');
        }

        return Redirect::back();

    }

    public function postManage(Request $request) {
        $invoice_id = $request->invoice_id;
        $items = $request->item;
        $tax_rates = Tax::where('company_id',$request->company_id)->orderBy('id','desc')->limit(1)->get();
        $tax = 0.096;
        if (count($tax_rates) > 0) {
            foreach ($tax_rates as $tax_rate) {
                $tax = $tax_rate->rate;
            }
        }
        $pretax = 0;
        $tax_total = 0;
        $total = 0;
        if (count($items) > 0) {
            foreach ($items as $key => $value) {
                // divide the subtotal based on count
                $invoice_items = InvoiceItem::where('invoice_id',$invoice_id)->where('item_id',$key)->get();
                $count_items = count($invoice_items);
                if ($count_items > 0) {
                    $divided_amount = money_format('%i',($value / $count_items));
                    $count_down = $count_items;
                    $count_down_subtotal = $value;
                    foreach ($invoice_items as $iitem) {
                        $count_down--;
                        $count_down_subtotal -= $divided_amount;
                        $invoice_item_id = $iitem->id;
                        $invs_items = InvoiceItem::find($invoice_item_id);

                        // add any extra amount to last item
                        $invs_pretax = ($count_down == 0) ? $divided_amount + $count_down_subtotal : $divided_amount;
                        $invs_tax = round($invs_pretax * $tax,2);
                        $invs_total = round($invs_pretax + $invs_tax,2);
                        $invs_items->pretax = $invs_pretax;
                        $invs_items->tax = money_format('%i',$invs_tax);
                        $invs_items->total = money_format('%i',$invs_total);
                        $pretax += $invs_pretax;
                        $tax_total += $invs_tax;
                        $total += $invs_total;
                        $invs_items->save();
                    }
                }
            }
        }

        // update invoice
        $invoices = Invoice::find($invoice_id);
        $invoices->pretax = $pretax;
        $invoices->tax = $tax_total;
        $invoices->company_id = $request->company_id;
        $invoices->total = $total;
        if ($invoices->save()) {
            Flash::success('Successfully updated prices');
            $invoices = Invoice::where('id',$invoice_id)->get();
            $request->session()->put('invoice',$invoices);
            $request->session()->put('invoice_id',$invoice_id);
            return Redirect::back();
        }

    }

    public function postManageItems(Request $request) {
        $invoice_id = $request->invoice_id;
        $items = $request->item;
        $tax_rates = Tax::where('company_id',Auth::user()->company_id)->orderBy('id','desc')->limit(1)->get();
        $tax = 0.096;

        if (count($tax_rates) > 0) {
            foreach ($tax_rates as $tax_rate) {
                $tax = $tax_rate->rate;
            }
        }
        if (count($items) > 0) {
            
            foreach ($items as $key => $value) {
                $tax_total = round($value * $tax,2);
                $aftertax = round($value + $tax_total,2);
                $invoice_items = InvoiceItem::find($key);
                $invoice_items->pretax = $value;
                $invoice_items->tax = $tax_total;
                $invoice_items->total = $aftertax;
                $invoice_items->save();
            }
        }

        // calculate new totals for invoice
        $pretax = InvoiceItem::where('invoice_id',$invoice_id)->sum('pretax');
        $tax = InvoiceItem::where('invoice_id',$invoice_id)->sum('tax');
        $total = InvoiceItem::where('invoice_id',$invoice_id)->sum('total');
        $invoices = Invoice::find($invoice_id);
        $invoices->pretax = $pretax;
        $invoices->tax = $tax;
        $invoices->total = $total;
        if ($invoices->save()) {
            Flash::success('You save successfully updated your invoice item price.');
            $invoices = Invoice::where('id',$invoice_id)->get();
            $request->session()->put('invoice',$invoices);
            $request->session()->put('invoice_id',$invoice_id);
            return Redirect::back();
        }
    }

    public function postManageUpdate(Request $request) {
        if ($request->ajax()) {
            $search = $request->search;

            $inv_items = InvoiceItem::find($search);
            if ($inv_items) {
                $item_id = $inv_items->item_id;
                $item_name = InventoryItem::getItemName($item_id);
                $memo = $inv_items->memo;
                $color = $inv_items->color;
                $status = $inv_items->status;
                $pretax = $inv_items->pretax;
                $tax = $inv_items->tax;
                $total = $inv_items->total;
                $company_id = $inv_items->company_id;

            } else {
                $item_name = NULL;
                $memo = NULL;
                $color = NULL;
                $status = 1;
                $pretax = 0;
                $tax = 0;
                $total = 0;
                $company_id = 1;
            }


            return response()->json([
                'status'=> true,
                'location_id'=>$status,
                'pretax'=>$pretax,
                'tax'=>$tax,
                'total'=>$total,
                'company_id'=>$company_id,
                'name'=>$item_name,
                'memo'=>$memo,
                'color'=>$color
            ]);            
        }
    }

    public function postManageTotals(Request $request) {
        if ($request->ajax()) {
            $amount = $request->amount;
            $direction = $request->direction;
            $company_id = $request->company_id;
            $taxes = Tax::where('company_id',$company_id)->orderBy('id','desc')->limit(1)->get();
            if (count($taxes) > 0) {
                foreach ($taxes as $tax) {
                    $tax_rate = $tax['rate'];
                }
            } else {
                $tax_rate = 0.096;
            }
            switch($direction) {
                case 1: // subtotal to total
                $pretax = money_format('%i', $amount);
                $tax = money_format('%i',round($pretax * $tax_rate,2));
                $total = money_format('%i',round($pretax * (1+$tax_rate),2));
                break;

                default: // total to subtotal
                $total = money_format('%i',$amount);
                $pretax = money_format('%i',round($total  / (1+$tax_rate),2));
                $tax = money_format('%i',$total - $pretax);
                break;
            }


            return response()->json([
                'status'=> true,
                'pretax'=>$pretax,
                'tax'=>$tax,
                'total'=>$total,
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
}

