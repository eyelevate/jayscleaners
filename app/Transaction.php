<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Invoice;
use App\User;
class Transaction extends Model
{
    use SoftDeletes;

    public function makePayment($ids, $tendered, $customer_id)
    {
        $sum = $this->whereIn('id',$ids)->sum('total');
        $transactions = $this->whereIn('id',$ids)->get();
        $t_count = count($transactions);
        $difference = $tendered - $sum;
        $status = 1;
        if ($difference = 0) {
            $transactions->each(function($value, $key) use(&$t_count, $status){
                $t = $this->find($value->id);
                $t->status = $status;
                $t->account_paid = $tendered;
                $t->account_paid_on = date('Y-m-d H:i:s');
                if ($t->save()) {
                    $t_count--;
                }
            });
        } elseif($difference > 0) {
            $status = 2;
            $transactions->each(function($value, $key) use(&$t_count, &$tendered, $status){
                $tendered = $tendered - $value->total;
                $account_tendered = ($t_count == 1) ? $value->total - $tendered : $value->total;
                $t = $this->find($value->id);
                $t->status = $status;
                $t->account_paid = $account_tendered;
                $t->account_paid_on = date('Y-m-d H:i:s');
                if ($t->save()) {
                    $t_count--;
                }
            });
        } else {

            $transactions->each(function($value, $key) use(&$t_count, &$tendered, $status){
                $tendered = $tendered - $value->total;
                $account_tendered = ($t_count == 1) ? $tendered : $value->total;
                $t = $this->find($value->id);
                $t->status = $status;
                $t->account_paid = $account_tendered;
                $t->account_paid_on = date('Y-m-d H:i:s');
                if ($t->save()) {
                    $t_count--;
                }
            });
        }

        $customers = User::find($customer_id);
        $new_balance = $customers->account_total - $tendered;
        $customers->account_total = $new_balance;
        if ($customers->save()) {
            return ($t_count == 0) ? true : false;    
        }

        return false;
        
    }

    public static function prepareTransaction($data) {
    	if (count($data) > 0) {
    		foreach ($data as $key => $value) {
    			if (isset($data[$key]['customer_id'])) {
    				$customer_id = $value['customer_id'];
    				$customers = User::find($customer_id);
    				$data[$key]['last_name'] = ucFirst($customers->last_name);
    				$data[$key]['first_name'] = ucFirst($customers->first_name);
    				$data[$key]['total_due'] = $customers->account_total;
                    $data[$key]['email'] = $customers->email;

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
