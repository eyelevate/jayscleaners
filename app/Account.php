<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Account extends Model
{
    use SoftDeletes;

    static public function prepareAccount($data){
    	if (isset($data)) {
    		foreach ($data as $key => $value) {
    			$customer_id = $data[$key]['id'];
    			$transactions = Transaction::where('customer_id',$customer_id)->get();

    			if (count($transactions) > 0) {
    				foreach ($transactions as $transaction) {
    					$data[$key]['account_status'] = $transaction->status;
    					$data[$key]['account_transaction_id'] = $transaction->id;
    					$invoices = Invoice::where('transaction_id',$transaction->id)->get();
    					$data[$key]['account_invoices'] = $invoices;
    				}
    			}
    		}
    	}

    	return $data;
    }
}
