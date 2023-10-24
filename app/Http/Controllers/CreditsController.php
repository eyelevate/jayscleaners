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
use App\Credit;
use App\User;
use App\Layout;
use App\Transaction;

class CreditsController extends Controller
{

    public function postAdd(Request $request)
    {
        //Validate the request
        $this->validate($request, [
            'amount' => 'required',
            'reason' => 'required',
            'customer_id' => 'required'
        ]);

        $credits = new Credit();
        $credits->customer_id = $request->customer_id;
        $credits->employee_id = Auth::user()->id;
        $credits->reason = $request->reason;
        $credits->amount = $request->amount;
        $credits->status = 1;
        if ($credits->save()) {
            $customers = User::find($request->customer_id);
            $credit_amount = (isset($customers->credits)) ? $customers->credits : 0;
            $new_credit_amount = $credit_amount + $credits->amount;
            $customers->credits = $new_credit_amount;
            if ($customers->save()) {
                Flash::success('Successfully added store credit!');
            } else {
                Flash::error("Could not locate customer. Please try again");
            }
            return Redirect::back();
        }
    }

    public function create(Request $request)
    {
        $amount = $request->amount;
        $reason = $request->reason;
        $status = 1;
        $employee_id = $request->employeeId;
        $customer_id = $request->customerId;
        $customer = User::find($customer_id);
        $credit = new Credit();
        $credit->amount = $amount;
        $credit->reason = $reason;
        $credit->status = $status;
        $credit->employee_id = $employee_id;
        $credit->customer_id = $customer_id;
        if ($credit->save()) {
            $oldCredit = ($customer->credits !== null) ? $customer->credits : 0;
            $newCredit = $oldCredit + $amount;
            $customer->credits = $newCredit;
            $customer->save();
        }

        return response()->json($customer);
    }

    public function edit(Request $request)
    {
        $amount = $request->amount;
        $reason = $request->reason;
        $status = 1;
        $employee_id = $request->employeeId;
        $customer_id = $request->customerId;
        $credit_id = $request->creditId;
        $old_amount = $request->oldAmount;
        $customer = User::find($customer_id);
        $credit = Credit::find($credit_id);
        $credit->amount = $amount;
        $credit->reason = $reason;
        $credit->status = $status;
        $credit->employee_id = $employee_id;
        $credit->customer_id = $customer_id;
        if ($credit->save()) {
            $oldCredit = ($customer->credits !== null) ? $customer->credits : 0;
            $creditRemoved = $oldCredit - $old_amount;
            $newCredit = $creditRemoved + $amount;
            $customer->credits = $newCredit;
            $customer->save();
        }

        return response()->json($customer);
    }

    public function history($id = null)
    {
        $credits = Credit::where('customer_id', $id)->orderBy('created_at', 'desc')->get();

        $credits->each(function ($credit) use ($credits, $id) {
            $key = $credits->search($credit);

            if ($key < $credits->count() - 1) {
                $nextCredit = $credits[$key + 1];
                $endDate = $nextCredit->created_at;
            } else {
                $endDate = date('Y-m-d H:i:s'); // Use today's date for the last credit record
            }

            $startDate = $credit->created_at;

            // Query the Transaction model for records within the date range with credit > 0
            $transactions = Transaction::where('customer_id', $id)
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->where('credit', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            // Set the transactions as a property of the credit record
            $credit->transactions = $transactions;
        });

        return response()->json($credits);
    }


}
