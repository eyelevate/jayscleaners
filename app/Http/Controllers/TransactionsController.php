<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Transaction;
use App\Invoice;
use App\User;

class TransactionsController extends Controller
{
    //
    public function pickupNonAccount(Request $request)
    {
        try {
            $transaction = new Transaction;

            DB::transaction(function () use ($request, $transaction) {
                $invoices = (array) $request->get('invoices');
                $transaction->company_id = $request->company_id;
                $transaction->customer_id = $request->customer_id;
                $transaction->pretax = $request->pretax;
                $transaction->tax = $request->tax;
                $transaction->aftertax = $request->aftertax;
                $transaction->discount = $request->discount;
                $transaction->credit = $request->credit;
                $transaction->total = $request->total;
                $transaction->type = $request->type;
                $transaction->tendered = $request->tendered;
                $transaction->status = $request->status;
                $transaction->saveOrFail();
                foreach ($invoices as $invoice) {
                    $invoiceId = $invoice['id'];
                    $invoice = Invoice::findOrFail($invoiceId);
                    $invoice->transaction_id = $transaction->id;
                    $invoice->status = 5;
                    $invoice->saveOrFail();
                }
                if ($request->credit > 0) {
                    $customer = User::findOrFail($request->customer_id);
                    $newCredits = (($customer->credits - $request->credit) > 0) ? $customer->credits - $request->credit : 0;
                    $customer->credits = $newCredits;
                    $customer->saveOrFail();
                }
            });
            return response()->json($transaction);
        } catch (ModelNotFoundException $e) {
            return response()->error('Transaction not created, rolling back', 400);

        } catch (QueryException $e) {
            return response()->error('Error creating transaction, saving an invoice failed, rolling back.', 500);
        }
    }

    public function pickupAccount(Request $request)
    {
        try {
            $transaction = new Transaction;
            $customer = User::findOrFail($request->customer_id);
            DB::transaction(function () use ($request, $transaction, $customer) {
                $invoices = (array) $request->get('invoices');
                $transaction->company_id = $request->company_id;
                $transaction->customer_id = $request->customer_id;
                $transaction->pretax = $request->pretax;
                $transaction->tax = $request->tax;
                $transaction->aftertax = $request->aftertax;
                $transaction->discount = $request->discount;
                $transaction->credit = $request->credit;
                $transaction->total = $request->total;
                $transaction->type = $request->type;
                $transaction->tendered = $request->tendered;
                $transaction->status = $request->status;
                $transaction->saveOrFail();
                foreach ($invoices as $invoice) {
                    $invoiceId = $invoice['id'];
                    $invoice = Invoice::findOrFail($invoiceId);
                    $invoice->transaction_id = $transaction->id;
                    $invoice->status = 5;
                    $invoice->saveOrFail();
                }

                if ($customer->account_total === null) {
                    $customer->account_total = $request->total;
                } else {
                    $customer->account_total += $request->total;
                }
                if ($request->credit > 0) {
                    $newCredits = (($customer->credits - $request->credit) > 0) ? $customer->credits - $request->credit : 0;
                    $customer->credits = $newCredits;
                }

                $customer->saveOrFail();

            });
            return response()->json($customer);
        } catch (ModelNotFoundException $e) {
            return response()->error('Transaction not created, rolling back', 400);

        } catch (QueryException $e) {
            return response()->error('Error creating transaction, saving an invoice failed, rolling back.', 500);
        }
    }

    public function accountTransactionsByCustomerId($id = null) {
        $transactions = Transaction::where('customer_id',$id)->orderBy('updated_at', 'desc')->get();

        return response()->json($transactions);

    }

    public function payAccount(Request $request) {
        try {
            $customer = User::findOrFail($request->customerId);
            DB::transaction(function () use ($request, $customer) {
                $transactions = Transaction::where('customer_id', $request->customerId)
                    ->whereIn('status', [2,3])
                    ->orderBy('status', 'asc')
                    ->get();
                $rollingTendered = $request->tendered;
                foreach ($transactions as $transaction) {
                    if($rollingTendered <= 0) {
                        continue;
                    }

                    if ($transaction->status === 2) {
                        $transaction->status = 1;
                        $transaction->account_paid = $request->tendered;
                        $transaction->account_paid_on = date('Y-m-d H:i:s');
                        $rollingTendered -= $transaction->account_paid;
                    } elseif($transaction->status === 3 && $rollingTendered >= $transaction->total) {
                        $transaction->status = 1;
                        $transaction->account_paid = $request->tendered;
                        $transaction->account_paid_on = date('Y-m-d H:i:s');
                        $rollingTendered -= $transaction->total;
                    } elseif($transaction->status === 3 && $rollingTendered < $transaction->total) {
                        $transaction->status = 2;
                        $difference = $transaction->total - $rollingTendered;
                        $transaction->account_paid = $difference;
                        $rollingTendered = 0;
                    }
                    $transaction->saveOrFail();
                }
                $customer->account_total -= $request->tendered;
                $customer->saveOrFail();
            });
            return response()->json($customer);
        } catch (ModelNotFoundException $e) {
            return response()->error('Account not paid, rolling back', 400);

        } catch (QueryException $e) {
            return response()->error('Error creating account, saving an invoice failed, rolling back.', 500);
        }
    }
}
