<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Transaction;
use App\Invoice;

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
                    $invoice = Invoice::findOrFail(invoiceId);
                    $invoice->transaction_id = $transaction->id;
                    $invoice->status = 5;
                    $invoice->saveOrFail();
                }
            });
            return response()->json($transaction);
        } catch (ModelNotFoundException $e) {
            return response()->error('Transaction not found, rolling back', 400);

        } catch (QueryException $e) {
            return response()->error('Error creating transaction, saving an invoice failed, rolling back.', 500);
        }
    }
}
