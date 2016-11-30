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

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Account;
use App\InventoryItem;
use App\Job;
use App\User;
use App\Company;
use App\Customer;
use App\Custid;
use App\Invoice;
use App\InvoiceItem;
use App\Transaction;
use App\Layout;
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

    public function getPay($id = null) {
        $customers = User::find($id);
        $customers->phone = Job::formatPhoneString($customers->phone);
        $transactions = Account::prepareAccountTransactionPay($id);

        return view('accounts.pay')
        ->with('layout',$this->layout)
        ->with('customers',$customers)
        ->with('transactions',$transactions);
    }

    public function postPay(Request $request) {
        if ($request->session()->has('transaction_ids') && count($request->session()->get('transaction_ids')) > 0){
            $transaction_ids = $request->session()->get('transaction_ids');
            $transactions = Transaction::whereIn('id',$transaction_ids);

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

    public function postConvertSend() {

    }

    public function getPreview($id = null) {

        $pdf = App::make('dompdf.wrapper');
        $html = Account::makeBillHtml($id, true);
        $pdf->loadHTML($html);
        
        return $pdf->stream();
    }

    public function getPrintCurrent(){
        $pdf = App::make('dompdf.wrapper');
        $html = Account::makeBillHtml('current',false);
        $pdf->loadHTML($html);
        
        return $pdf->stream();
    }


}
