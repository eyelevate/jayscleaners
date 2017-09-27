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
use Mail;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Account;
use App\InventoryItem;
use App\Card;
use App\Job;
use App\User;
use App\Company;
use App\Customer;
use App\Custid;
use App\Invoice;
use App\InvoiceItem;
use App\Transaction;
use App\Layout;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AccountsController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.dropoff';

    }

    public function getIndex(){
        $month = Account::getMonth();
        return view('accounts.index')
        ->with('month',$month)
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
        $month = Account::getMonth();
        return view('accounts.index')
        ->with('layout',$this->layout)
        ->with('month',$month)
        ->with('customers',$users);
    }

    public function getPay($id = null, Transaction $transaction) {
        $customers = User::find($id);
        $customers->phone = Job::formatPhoneString($customers->phone);
        // $transactions = Account::prepareAccountTransactionPay($id);

        $sum = $transaction->where('customer_id',8259)->orderBy('id','desc')->limit(5)->sum('total');
        dump('sum - '.$sum);
        $tendered = 3000;
        $difference = $sum - $tendered;
        dump($difference);
        $transactions = $transaction->where('customer_id',8259)->orderBy('id','desc')->limit(5)->get();
        $t_count = count($transactions);
        dump($t_count);
        $transactions->each(function($value,$key) use (&$t_count, $tendered){
            $tendered = $tendered - $value->total;
            $account_tendered = ($t_count == 1) ? $sum : $value->total;
            dump($t_count.' - '.$account_tendered);
            $t_count--;
            
        });

        dump($t_count);

        return view('accounts.pay')
        ->with('layout',$this->layout)
        ->with('customers',$customers)
        ->with('transactions',$transactions);
    }

    public function postPay(Request $request, Transaction $transaction) {

        if ($request->session()->has('transaction_ids') && count($request->session()->get('transaction_ids')) > 0){
            $transaction_ids = $request->session()->get('transaction_ids');
            // $transactions = $transaction->whereIn('id',$transaction_ids);

            // check totals and make payment
            $check = $transaction->makePayment($transaction_ids, $request->tendered);
            if ($transactions->update(
                [
                    'status'=>1,
                    'type'=>$request->type,
                    'account_paid'=>$request->tendered,
                    'account_paid_on'=>date('Y-m-d H:i:s')
                ]
            )) { // now update the user total
                $customers = User::find($request->customer_id);
                $total_balance = ($customers->account_total >= 0) ? $customers->account_total : 0;
                $new_balance = $total_balance - $request->tendered;
                $customers->account_total = $new_balance;
                if ($customers->save()) {
                    Flash::success('Successfully paid account invoices.');
                    return Redirect::route('accounts_index');
                }
            }

            
        } else {
            Flash::error('You must first select an account invoice.');
            return Redirect::back();
        }
    }

    public function getHistory($id = null) {
        $customers = User::find($id);
        $customers->phone = Job::formatPhoneString($customers->phone);
        $transactions = Account::prepareAccountTransaction($id);

        return view('accounts.history')
        ->with('layout',$this->layout)
        ->with('customers',$customers)
        ->with('transactions',$transactions);
    }

    public function postUpdateTotal(Request $request)  {
        if ($request->ajax()){
            $request->session()->put('transaction_ids',$request->transaction_ids);
            $pretax = Transaction::whereIn('id',$request->transaction_ids)->sum('pretax');
            $tax = Transaction::whereIn('id',$request->transaction_ids)->sum('tax');
            $aftertax = Transaction::whereIn('id',$request->transaction_ids)->sum('aftertax');
            $credit = Transaction::whereIn('id',$request->transaction_ids)->sum('credit');
            $discount = Transaction::whereIn('id',$request->transaction_ids)->sum('discount');
            $total = Transaction::whereIn('id',$request->transaction_ids)->sum('total');
            $pretax_format = money_format('$%i', $pretax);
            $tax_format = money_format('$%i', $tax);
            $aftertax_format = money_format('$%i', $aftertax);
            $credit_format = money_format('$%i', $credit);
            $discount_format = money_format('$%i', $discount);
            $total_format = money_format('$%i', $total);


            return response()->json([
                'status'=> true,
                'pretax'=>$pretax,
                'tax'=>$tax,
                'aftertax'=>$aftertax,
                'credit'=>$credit,
                'discount'=>$discount,
                'total'=>$total,
                'pretax_format'=>$pretax_format,
                'tax_format'=>$tax_format,
                'aftertax_format'=>$aftertax_format,
                'credit_format'=>$credit_format,
                'discount_format'=>$discount_format,
                'total_format'=>$total_format
            ]);  
        }
    }

    public function postRevert(Request $request) {
        if ($request->status > 1) {
            $transactions = Transaction::find($request->id);
            $customer_id = $transactions->customer_id;
            $users= User::find($customer_id);
            $transactions->account_paid = NULL;
            $transactions->account_paid_on = NULL;
            $transactions->status = $request->status;
            if ($transactions->save()) {
                $old_account_total = $users->account_total;
                $new_account_total = $old_account_total + $transactions->total;
                $users->account_total = $new_account_total;
                if ($users->save()) {
                    Flash::success('Successfully reverted transaction.');
                    return Redirect::back();
                }
            }
        } else {
            Flash::error('You must first select a new status in order to revert this transaction.');
            return Redirect::back();
        }
    }

    public function postBill(Request $request) {

    }

    public function postEmailSend(Request $request) {
        
        $transaction_ids = $request->session()->has('account_transaction_ids') ? $request->session()->get('account_transaction_ids') : [];
        $reports = [];
        if (count($transaction_ids) >0){
            $email_count = 0;
            foreach ($transaction_ids as $transaction_id) {
                $pdf = App::make('dompdf.wrapper');
                $html = Account::makeSingleBillHtml($transaction_id, true);   
                $pdf->loadHTML($html);
                $pdf_title = 'pdf/account-'.$transaction_id.'-'.strtotime(date('Y-m-d H:i:s')).'.pdf';
                if ($pdf->save($pdf_title)){
                    // prepare email
                    $transactions = Transaction::find($transaction_id);
                    $customer_id = $transactions->customer_id;
                    $customers = User::find($customer_id);
                    $first_name = ucFirst($customers->first_name);
                    $last_name = ucFirst($customers->last_name);
                    $send_to = $customers->email;
                    $bill_month = date('F Y',strtotime($transactions->created_at));
                    $bill_period = date('n/01/Y',strtotime($transactions->created_at)).' - '.date('n/t/Y',strtotime($transactions->created_at));
                    $due_date = date('n/15/Y',strtotime($transactions->created_at.' +1 month'));
                    $title = 'Jays Cleaners Account Billing Statement - '.$bill_month;

                    // send email
                    $from = 'noreply@jayscleaners.com';
                    // Email customer
                    if (Mail::send('emails.account_bill-nodelivery', [
                    // if (Mail::send('emails.account_bill', [
                        'transactions' => $transactions,
                        'customers' => $customers
                    ], function($message) use ($send_to, $title, $pdf_title)
                    {
                        $message->to($send_to);
                        $message->subject($title);
                        $message->attach($pdf_title);
                    }));

                    if (unlink($pdf_title)) {
                        $email_count += 1;
                    }
                    

                } 
            }

            Flash::success('Successfully send account billing emails to '.$email_count.' customers. Check no-reply email failues onlinen to view which emails were not successfully sent. Thank you.');
            
            
        } else {
            Flash::error('No account bills to send. Error try another month or look at ids.');
        }
        return Redirect::back();

    }

    public function getPreview(Request $request) {

        $pdf = App::make('dompdf.wrapper');
        $transaction_ids = $request->session()->has('account_transaction_ids') ? $request->session()->get('account_transaction_ids') : [];
        $html = Account::makeBillHtml($transaction_ids, true);
        $pdf->loadHTML($html);
        
        return $pdf->stream();
    }

    public function getPrintCurrent(){
        $pdf = App::make('dompdf.wrapper');
        $html = Account::makeBillHtml('current',false);
        $pdf->loadHTML($html);
        
        return $pdf->stream();
    }

    public function getSend() {
        $month = Account::getMonth();
        $year = Account::getYear();
        return view('accounts.send')
        ->with('month',$month)
        ->with('year',$year)
        ->with('transactions',[])
        ->with('layout',$this->layout);
    }

    public function postSend(Request $request) {
        $month = $request->billing_month;
        $year = $request->billing_year;
        $transaction_id = $request->transaction_id;
        $customer_id = $request->customer_id;
        $date_range_start = ($month != '' && $year != '') ? date('Y-m-01 00:00:00',strtotime($year.'-'.$month.'-01 00:00:00')) : false;
        $date_range_end = ($date_range_start) ? date('Y-m-t 23:59:59',strtotime($date_range_start)) : false;

        if ($date_range_start && $date_range_end) {
            if ($transaction_id != '' && $customer_id != '') {
                $transactions = Transaction::whereBetween('created_at',[$date_range_start,$date_range_end])
                    ->where('status',2)
                    ->where('id',$transaction_id)
                    ->where('customer_id',$customer_id)
                    ->get();
            } elseif($transaction_id != '' && $customer_id == '') {
                $transactions = Transaction::whereBetween('created_at',[$date_range_start,$date_range_end])
                    ->where('status',2)
                    ->where('id',$transaction_id)
                    ->get();

            } elseif($transaction_id == '' && $customer_id != '') {
                $transactions = Transaction::whereBetween('created_at',[$date_range_start,$date_range_end])
                    ->where('status',2)
                    ->where('customer_id',$customer_id)
                    ->get();
            } else {
                $transactions = Transaction::whereBetween('created_at',[$date_range_start,$date_range_end])
                    ->where('status',2)
                    ->get();
            }
            
        } else {
            if ($transaction_id != '' && $customer_id != '') {
                $transactions = Transaction::where('status',2)
                    ->where('id',$transaction_id)
                    ->where('customer_id',$customer_id)
                    ->get();
            } elseif($transaction_id != '' && $customer_id == '') {
                $transactions = Transaction::where('status',2)
                    ->where('id',$transaction_id)
                    ->get();

            } elseif($transaction_id == '' && $customer_id != '') {
                $transactions = Transaction::where('status',2)
                    ->where('customer_id',$customer_id)
                    ->get();
            } else {
                $transactions = Transaction::where('status',2)
                    ->get();
            }            
        }
        $month = Account::getMonth();
        $year = Account::getYear();
        $transactions = Transaction::prepareTransaction($transactions);
        // Job::dump($transactions);
        return view('accounts.send')
        ->with('month',$month)
        ->with('year',$year)
        ->with('transactions',$transactions)
        ->with('layout',$this->layout); 
    }

    public function postSendList(Request $request) {
        if ($request->ajax()) {
            $request->session()->put('account_transaction_ids',$request->transaction_ids);
            return response()->json([
                'status'=> true,
                'transaction_ids'=>$request->session()->get('account_transaction_ids')
            ]);
        }

    } 

    public function getPayMyBill(){
        // $this->layout = 'layouts.bill';
        // return view('accounts.pay_my_bill')
        // ->with('layout',$this->layout); 
        $this->layout = 'layouts.bill-nodelivery';
        return view('accounts.pay_my_bill-nodelivery')
        ->with('layout',$this->layout); 
    }

    public function getOneTimePayment(){
        // $this->layout = 'layouts.bill';
        $this->layout = 'layouts.bill-nodelivery';
        $transactions = [];
        return view('accounts.one_time_payment-nodelivery')
        ->with('transactions',$transactions)
        ->with('layout',$this->layout); 
        // return view('accounts.one_time_payment')
        // ->with('transactions',$transactions)
        // ->with('layout',$this->layout); 
    }

    public function postOneTimePayment(Request $request) {
        // $this->layout = 'layouts.bill';
        $this->layout = 'layouts.bill-nodelivery';
        $transaction_id = $request->transaction_id;
        $customer_id = $request->customer_id;
        $transactions = Transaction::where('customer_id',$customer_id)
            ->where('id',$transaction_id)
            ->where('status',2)
            ->get();
        $transaction_status = (count($transactions) > 0) ? true : false;
        $request->session()->put('transaction_status',$transaction_status);
        $request->session()->put('transactions',$transactions);
        return Redirect::route('accounts_oneTimeFinish');

    }

    public function getOneTimeFinish(Request $request) {
        // $this->layout = 'layouts.bill';
        $this->layout = 'layouts.bill-nodelivery';
        $transaction_status = $request->session()->has('transaction_status') ? $request->session()->get('transaction_status') : false;
        if ($transaction_status) {

            $transactions = $request->session()->get('transactions');

            return view('accounts.one_time_finish-nodelivery')
            ->with('transactions',$transactions)
            ->with('layout',$this->layout);   
            // return view('accounts.one_time_finish')
            // ->with('transactions',$transactions)
            // ->with('layout',$this->layout);             
        } else {
            Flash::error('Your session has expired. Please enter in the invoice # and the customer # located on your most current account billing statement. Thank you.');
            return Redirect::route('accounts_oneTimePayment');
        }
    }

    public function postOneTimeFinish(Request $r){
        // validate form
        $this->validate($r, [
            'name'=>'required',
            'card_number'=>'required',
            'exp_month' => 'required',
            'exp_year'=>'required',
            'cvv'=>'required',
            'email' => 'email|max:255|required'
        ]);
        $trans = $r->session()->get('transactions');
        $transaction_id = false;
        $customer_id = false;
        $total = 0;
        if (count($trans) > 0) {
            foreach ($trans as $tran) {
                $total = $tran->total;
                $transaction_id = $tran->id;
                $customer_id = $tran->customer_id;
            }
        }
        $exp_date = date('my',strtotime($r->exp_year.'-01-'.$r->exp_month));
        $company_id = 1; // Hard code Young's Api for payment
        $companies = Company::find($company_id);
        $api_login = $companies->payment_api_login;
        $api_pass = $companies->payment_gateway_id;


        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($api_login);
        $merchantAuthentication->setTransactionKey($api_pass);
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($r->card_number);
        $creditCard->setExpirationDate($exp_date);
        $creditCard->setCardCode($r->cvv);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        $order = new AnetAPI\OrderType();
        $order->setDescription("Account Payment - ".$r->transaction_id);

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType( "authCaptureTransaction"); 
        $transactionRequestType->setAmount($total);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);


        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId( $refId);
        $request->setTransactionRequest( $transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);


        if ($response != null) {
            if($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();
              
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $new_transaction_id = $tresponse->getTransId();
                    $auth_code = $tresponse->getAuthCode();
                    $save = Transaction::find($transaction_id);
                    $save->transaction_id = $new_transaction_id;
                    $save->status = 1;
                    $save->account_paid = $total;
                    $save->account_paid_on = date('Y-m-d H:i:S');
                    $save->tendered = $total;
                    if ($save->save()) {
                        $customers = User::find($save->customer_id);
                        $account_total = $customers->account_total;
                        $new_account_total = $account_total - $total;
                        $customers->account_total = $new_account_total;
                        if ($customers->save()) {
                            // make email
                            $send_to = $r->email;
                            $from = 'noreply@jayscleaners.com';
                            // Email customer
                            // if (Mail::send('emails.account_receipt', [
                            if (Mail::send('emails.account_receipt-nodelivery', [
                                'transactions' => $save,
                                'customers'=>$customers
                            ], function($message) use ($send_to)
                            {
                                $message->to($send_to);
                                $message->subject('Account Bill Payment Receipt');
                            }));



                        }

                        // redirect to home page
                        $r->session()->pull('transactions');
                        $r->session()->pull('transaction_status');
                        Flash::success('Successfully made account billing payment. An email has been sent to you as a copy of this transaction. Thank you for your business!');
                        
                        return Redirect::route('pages_index');

                    }

                    // echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                    // echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                    // echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
                    // echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n"; 
                    // echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
                } else {
                    
                    if($tresponse->getErrors() != null) {
                        Flash::error('Error ('.$tresponse->getErrors()[0]->getErrorCode().'): '.$tresponse->getErrors()[0]->getErrorText());
                        return Redirect::back();        
                    }
                }
            } else {
                // echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
                if($tresponse != null && $tresponse->getErrors() != null) {
                    Flash::error('Error ('.$tresponse->getErrors()[0]->getErrorCode().'): '.$tresponse->getErrors()[0]->getErrorText());                   
                } else {
                    Flash::error('Error ('.$response->getMessages()->getMessage()[0]->getCode().'): '.$response->getMessages()->getMessage()[0]->getText());

                }
                return Redirect::back(); 
            }      
        } else {
            Flash::error('No response returned from Authorize.net, server may be experiencing down time. Please try again later.');
            return Redirect::route('accounts_oneTimePayment');
        }
        
    }
    public function getMemberPayment(Request $request){
        $request->session()->put('form_previous','accounts_memberPayment');
        $transactions = Transaction::prepareTransaction(Transaction::where('status',2)
            ->where('customer_id',Auth::user()->id)
            ->get());
        $quantity = 0;
        $subtotal = 0;
        $tax = 0;
        $aftertax = 0;
        $credit = 0;
        $discount = 0;
        $total = 0;
        $company_id = Auth::user()->company_id;
        $card = [];
        if (count($transactions) > 0) {
            foreach ($transactions as $transaction) {
                $quantity += $transaction['quantity'];
                $subtotal += $transaction['pretax'];
                $tax += $transaction['tax'];
                $aftertax += $transaction['aftertax'];
                $credit += $transaction['credit'];
                $discount += $transaction['discount'];
                $total += $transaction['total'];
                $company_id = $transaction['company_id'];
            }

        }

        // look up card on file
        $card_on_file = Card::where('user_id',Auth::user()->id)
            ->where('company_id',$company_id)
            ->get();
    
        if (count($card_on_file)>0) {
            foreach ($card_on_file as $key => $value){
                $card_id = $value->id;
                $profile_id = $value->profile_id;
                $payment_id = $value->payment_id;
                $card_on_file = Card::getCardInfo($company_id, $profile_id, $payment_id);
                $card[$card_id] = ($card_on_file['status']) ? $card_on_file['last_four'] : 'No Card';
            }
        }

        $email = Auth::user()->email;
        $full_name = ucFirst(Auth::user()->first_name).' '.Auth::user()->last_name;

        $this->layout = 'layouts.account_basic';
        return view('accounts.member_payment')
        ->with('transactions',$transactions)
        ->with('quantity',$quantity)
        ->with('subtotal',$subtotal)
        ->with('tax',$tax)
        ->with('aftertax',$aftertax)
        ->with('credit',$credit)
        ->with('discount',$discount)
        ->with('total',$total)
        ->with('card',$card)
        ->with('email',$email)
        ->with('full_name',$full_name)
        ->with('layout',$this->layout); 
    }
    public function postMemberPayment(Request $r){
        Flash::error('There was errors with your form. Please review your form below and try again. Thank you.');
        $this->validate($r, [
            'name'=>'required',
            'card_number'=>'required',
            'exp_month' => 'required',
            'exp_year'=>'required',
            'cvv'=>'required',
            'email' => 'email|max:255|required'
        ]);
        $trans = Transaction::where('status',2)
            ->where('customer_id',Auth::user()->id)
            ->get();
        $transaction_ids = [];
        $customer_id = Auth::user()->id;
        $total = 0;
        $company_id = false;
        if (count($trans) > 0) {
            foreach ($trans as $tran) {
                $total += $tran->total;
                array_push($transaction_ids,$tran->id);
                $company_id = $tran->company_id;
            }
        }

        if (count($transaction_ids) > 0) {
            $exp_date = date('my',strtotime($r->exp_year.'-01-'.$r->exp_month));
            $companies = Company::find($company_id);
            $api_login = $companies->payment_api_login;
            $api_pass = $companies->payment_gateway_id;


            // Common setup for API credentials
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($api_login);
            $merchantAuthentication->setTransactionKey($api_pass);
            $refId = 'ref' . time();

            // Create the payment data for a credit card
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($r->card_number);
            $creditCard->setExpirationDate($exp_date);
            $creditCard->setCardCode($r->cvv);
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);

            $order = new AnetAPI\OrderType();
            $order->setDescription("Account Payment - ".$r->transaction_id);

            //create a transaction
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType( "authCaptureTransaction"); 
            $transactionRequestType->setAmount($total);
            $transactionRequestType->setOrder($order);
            $transactionRequestType->setPayment($paymentOne);


            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId( $refId);
            $request->setTransactionRequest( $transactionRequestType);
            $controller = new AnetController\CreateTransactionController($request);
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);


            if ($response != null) {
                if($response->getMessages()->getResultCode() == "Ok") {
                    $tresponse = $response->getTransactionResponse();
                  
                    if ($tresponse != null && $tresponse->getMessages() != null) {
                        $new_transaction_id = $tresponse->getTransId();
                        $auth_code = $tresponse->getAuthCode();

                        $check_save = false;
                        $total = 0;
                        if (count($transaction_ids) > 0) {
                            foreach ($transaction_ids as $transaction_id) {

                                $update_tran = Transaction::find($transaction_id);
                                $update_tran->transaction_id = $new_transaction_id;
                                $update_tran->status = 1;
                                $update_tran->account_paid = $update_tran->total;
                                $update_tran->account_paid_on = date('Y-m-d H:i:S');
                                $update_tran->tendered = $update_tran->total;
                                if ($update_tran->save()) {
                                    $total += $update_tran->total;
                                    $check_save = true;
                                }
                            }
                        }

                        if ($check_save) {
                            $customers = User::find($customer_id);
                            $account_total = $customers->account_total;
                            $new_account_total = $account_total - $total;
                            $customers->account_total = $new_account_total;
                            if ($customers->save()) {
                                // make email
                                $send_to = $r->email;
                                $from = 'noreply@jayscleaners.com';
                                $transactions = Transaction::whereIn('id',$transaction_ids)->get();
                                // Email customer
                                if (Mail::send('emails.account_receipts-nodelivery', [
                                // if (Mail::send('emails.account_receipts', [
                                    'transactions' => $transactions,
                                    'amount_paid' => $total,
                                    'customers'=>$customers
                                ], function($message) use ($send_to)
                                {
                                    $message->to($send_to);
                                    $message->subject('Account Bill Payment Receipt');
                                }));

                            }

                            // redirect to home page
                            Flash::success('Successfully made account billing payment. An email has been sent to you as a copy of this transaction. Thank you for your business!');
                            
                            return Redirect::back();

                        }

                        // echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                        // echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                        // echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
                        // echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n"; 
                        // echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
                    } else {
                        
                        if($tresponse->getErrors() != null) {
                            Flash::error('Error ('.$tresponse->getErrors()[0]->getErrorCode().'): '.$tresponse->getErrors()[0]->getErrorText());
                            return Redirect::back();        
                        }
                    }
                } else {
                    // echo "Transaction Failed \n";
                    $tresponse = $response->getTransactionResponse();
                    if($tresponse != null && $tresponse->getErrors() != null) {
                        Flash::error('Error ('.$tresponse->getErrors()[0]->getErrorCode().'): '.$tresponse->getErrors()[0]->getErrorText());                   
                    } else {
                        Flash::error('Error ('.$response->getMessages()->getMessage()[0]->getCode().'): '.$response->getMessages()->getMessage()[0]->getText());

                    }
                    return Redirect::back(); 
                }      
            } else {
                Flash::error('No response returned from Authorize.net, server may be experiencing down time. Please try again later.');
                return Redirect::route('accounts_oneTimePayment');
            }
        } else {
            Flash::error('You are not authorized to view this form. There are no invoices with this associated customer id. Please try again.');
            return Redirect::back();
        }
    }

    public function postMemberFile(Request $r){
        if ($r->card_id != '') {
            $card_id = $r->card_id;
            $cards = Card::find($card_id);
            $profile_id = $cards->profile_id;
            $payment_id = $cards->payment_id;
            $customer_id = Auth::user()->id;
            $company_id = $cards->company_id;
            $companies = Company::find($company_id);
            $api_login = $companies->payment_api_login;
            $api_gateway_id = $companies->payment_gateway_id;

            $transactions = Transaction::where('status',2)
                ->where('customer_id',$customer_id)
                ->get();
            $total = 0;
            if (count($transactions)>0) {
                foreach ($transactions as $transaction) {
                    $total += $transaction->total;
                }
            } else {
                Flash::error('No Account invoices on file. ');
                return Redirect::back();
            }
            // Common setup for API credentials
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($api_login);
            $merchantAuthentication->setTransactionKey($api_gateway_id);
            $refId = 'ref' . time();

            $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
            $profileToCharge->setCustomerProfileId($profile_id);
            $paymentProfile = new AnetAPI\PaymentProfileType();
            $paymentProfile->setPaymentProfileId($payment_id);
            $profileToCharge->setPaymentProfile($paymentProfile);

            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType( "authCaptureTransaction"); 
            $transactionRequestType->setAmount($total);
            $transactionRequestType->setProfile($profileToCharge);

            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId( $refId);
            $request->setTransactionRequest( $transactionRequestType);
            $controller = new AnetController\CreateTransactionController($request);
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

            if ($response != null) {
                if($response->getMessages()->getResultCode() == 'Ok'){
                    $tresponse = $response->getTransactionResponse();

                    if ($tresponse != null && $tresponse->getMessages() != null) {
                        Flash::success('Successfully paid account billing! An email of your receipt has been sent to you. Thank you for your business.');
                        // update transactions
                        $saved = false;
                        if (count($transactions) > 0) {
                            
                            foreach ($transactions as $tran) {
                                $transaction_id = $tran->id;
                                $trans = Transaction::find($transaction_id);
                                $trans->status = 1;
                                $trans->account_paid = $tran->total;
                                $trans->account_paid_on = date('Y-m-d H:i:s');
                                $trans->transaction_id = $tresponse->getTransId();
                                $trans->tendered = $tran->total;
                                if ($trans->save()) {
                                    $saved = true;
                                }
                            }

                        }
                        // update users
                        if ($saved) {
                            $customers = User::find($customer_id);
                            $old_account_total = $customers->account_total;
                            $new_account_total = $old_account_total - $total;
                            $customers->account_total = $new_account_total;
                            if ($customers->save()) {
                                // make email
                                $send_to = $r->email;
                                $from = 'noreply@jayscleaners.com';
                                // Email customer
                                if (Mail::send('emails.account_receipts-nodelivery', [
                                // if (Mail::send('emails.account_receipts', [
                                    'transactions' => $transactions,
                                    'amount_paid' => $total,
                                    'customers'=>$customers
                                ], function($message) use ($send_to)
                                {
                                    $message->to($send_to);
                                    $message->subject('Account Bill Payment Receipt');
                                }));
                                Flash::success('Successfully accpeted payment and updated account totals. Thank you for your business.');       
                                return Redirect::back();
                            }
                        } else {
                            Flash::error('Accepted payment, however, could not find the correct transaction. Please contact administrator for refund. We apologize for the inconvenience.');

                        }

                        

                        // echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                        // echo  "Charge Customer Profile APPROVED  :" . "\n";
                        // echo " Charge Customer Profile AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                        // echo " Charge Customer Profile TRANS ID  : " . $tresponse->getTransId() . "\n";
                        // echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n"; 
                        // echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
                    } else {
                        if($tresponse->getErrors() != null) {
                            Flash::error('Error ('.$tresponse->getErrors()[0]->getErrorCode().'): '.$tresponse->getErrors()[0]->getErrorText());          
                        }
                    }
                } else {
                    $tresponse = $response->getTransactionResponse();
                    if($tresponse != null && $tresponse->getErrors() != null) {
                        Flash::error('Error ('.$tresponse->getErrors()[0]->getErrorCode().'): '.$tresponse->getErrors()[0]->getErrorText());                     
                    } else {
                        Flash::error('Error ('.$response->getMessages()->getMessage()[0]->getCode().'): '.$response->getMessages()->getMessage()[0]->getText());
                    }
                }
            } else {
                Flash::error('No response from authorize.net servers. Server may be down. Please try again at another time. Thank you');
                
            }

        } else {
            Flash::error('You must first select a card on file before processing.');

        }
        return Redirect::back();
    }




}
