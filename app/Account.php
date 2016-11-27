<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Invoice;
use App\Job;
use App\InvoicedItem;
use App\InventoryItem;
use App\Transaction;
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
    			if ($data[$key]['phone']) {
    				$data[$key]['phone'] = Job::formatPhoneString($data[$key]['phone']);
    			}
    		}
    	}

    	return $data;
    }

    static public function prepareAccountTransaction($id) {
    	$transactions = Transaction::where('customer_id',$id)->orderBy('id','desc')->paginate(25);
        if (count($transactions) > 0) {
            foreach ($transactions as $key => $value) {
                $invoices = Invoice::where('transaction_id',$value->id)->get();
                $quantity = 0;
                if (count($invoices) > 0) {
                    foreach ($invoices as $ikey => $ivalue) {
                        $invoice_items = InvoiceItem::where('invoice_id',$ivalue->id)->get();
                        $invoices[$ikey]['invoice_items'] = $invoice_items;
                        if (count($invoice_items)) {
                            foreach ($invoice_items as $inv_item_key => $inv_item_value) {
                                $invs = InventoryItem::find($inv_item_value->item_id);
                                $item_name = $invs->name;
                                $invoice_items[$inv_item_key]['item_name'] = $item_name;

                            }
                        }


                    }
                }
                $transactions[$key]['invoices'] = $invoices;  
                $transactions[$key]['quantity'] = $quantity;   
                // status
                if ($transactions[$key]['status']){
                	switch($transactions[$key]['status']) {
                		case 1:
                			$transactions[$key]['status_html'] = 'Paid';
                			$transactions[$key]['status_class'] = 'active';
                		break;

                		case 2:
                			$transactions[$key]['status_html'] = 'Due';
                			$transactions[$key]['status_class'] = 'info';
                		default:
                			$transactions[$key]['status_html'] = 'Current';
                			$transactions[$key]['status_class'] = 'success';
                		break;
                	}
                }

            }
        }

        return $transactions;
    }
    static public function prepareAccountTransactionPay($id) {
    	$transactions = Transaction::where('customer_id',$id)
    		->where('status','>',1)
    		->orderBy('id','desc')->paginate(25);
        if (count($transactions) > 0) {
            foreach ($transactions as $key => $value) {
                $invoices = Invoice::where('transaction_id',$value->id)->get();
                $quantity = 0;
                if (count($invoices) > 0) {
                    foreach ($invoices as $ikey => $ivalue) {
                        $invoice_items = InvoiceItem::where('invoice_id',$ivalue->id)->get();
                        $invoices[$ikey]['invoice_items'] = $invoice_items;
                        if (count($invoice_items)) {
                            foreach ($invoice_items as $inv_item_key => $inv_item_value) {
                                $invs = InventoryItem::find($inv_item_value->item_id);
                                $item_name = $invs->name;
                                $invoice_items[$inv_item_key]['item_name'] = $item_name;

                            }
                        }


                    }
                }
                $transactions[$key]['invoices'] = $invoices;  
                $transactions[$key]['quantity'] = $quantity;   
                // status
                if ($transactions[$key]['status']){
                	switch($transactions[$key]['status']) {
                		case 1:
                			$transactions[$key]['status_html'] = 'Paid';
                			$transactions[$key]['status_class'] = 'active';
                		break;

                		case 2:
                			$transactions[$key]['status_html'] = 'Due';
                			$transactions[$key]['status_class'] = 'info';
                		default:
                			$transactions[$key]['status_html'] = 'Current';
                			$transactions[$key]['status_class'] = 'success';
                		break;
                	}
                }

            }
        }

        return $transactions;
    }
}
