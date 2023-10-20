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
}
