<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Invoice;
use App\User;
class Transaction extends Model
{
    use SoftDeletes;

    public static function prepareTransaction($data) {
    	if (count($data) > 0) {
    		foreach ($data as $key => $value) {
    			if (isset($data[$key]['customer_id'])) {
    				$customer_id = $value['customer_id'];
    				$customers = User::find($customer_id);
    				$data[$key]['last_name'] = ucFirst($customers->last_name);
    				$data[$key]['first_name'] = ucFirst($customers->first_name);
    				$data[$key]['total_due'] = $customers->account_total;

    			}
    			if (isset($data[$key]['created_at'])) {
    				$billing_period_start = date('n/01/Y',strtotime($value['created_at']));
    				$billing_period_end = date('n/t/Y',strtotime($value['created_at']));
    				$billing_period = $billing_period_start.' - '.$billing_period_end;
    				$data[$key]['billing_period'] = $billing_period;
    				$due = date('n/15/Y',strtotime($billing_period_start.' +1 month'));
    				$data[$key]['due'] = $due;
    			}
                if (isset($data[$key]['id'])) {
                    $invoices = Invoice::where('transaction_id',$value['id'])->get();
                    $quantity = 0;
                    if (count($invoices) > 0) {
                        foreach ($invoices as $invoice) {
                            $quantity += $invoice->quantity;
                        }
                    }
                    $data[$key]['quantity'] = $quantity;
                }

    		}
    	}

    	return $data;
    }
}
