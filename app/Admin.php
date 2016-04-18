<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Job;

use App\Color;
use App\Company;
use App\Custid;
use App\Customer;
use App\Delivery;
use App\Discount;
use App\Inventory;
use App\InventoryItem;
use App\Invoice;
use App\InvoiceItem;
use App\Memo;
use App\Printer;
use App\Reward;
use App\RewardTransaction;
use App\Schedule;
use App\Tax;
use App\Transaction;
use App\User;

class Admin extends Model
{
    //
    public static function prepareAdmin($data) {

    	foreach ($data as $key => $value) {
    		if(isset($data[$key]['company_id'])){
	    		switch($data[$key]['company_id']){
	    			case '1': //Montlake
	    				$data[$key]['location'] = 'Montlake';
	    			break;

	    			case '2': //Roosevelt
	    				$data[$key]['location'] = 'Roosevelt';
	    			break;

	    			default: // N/A
	    				$data[$key]['location'] = 'N/A';
	    			break;	
	    		}
    		}
	    	if(isset($data[$key]['created_at'])){
	    		$data[$key]['created_on'] = date('n/d/Y g:ia',strtotime($data[$key]['created_at']));
	    	}
    	}

    	return $data;
    }

    public static function makeUpdate($company, $created_at) {
    	$company_id = $company->id;
    	$last_created_at = $company->created_at;
    	$update = [];
    	$update_rows = 0;

    	if ($created_at > 0) {
    		// get all data from models
    		$colors = Color::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$companies = Company::where('updated_at','>=',$created_at)->where('id',$company_id)->get();
    		$custids = Custid::where('updated_at','>=',$created_at)->get();
    		// $customers = Customer::where('updated_at',' >= ',$created_at)->get();
    		$deliveries = Delivery::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$discounts = Discount::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$inventories = Inventory::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$inventory_items = InventoryItem::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$invoices = Invoice::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$invoice_items = InvoiceItem::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$memos = Memo::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$printers = Printer::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$rewards = Reward::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$reward_transactions = RewardTransaction::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$schedules = Schedule::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$taxes = Tax::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$transactions = Transaction::where('updated_at','>=',$created_at)->where('company_id',$company_id)->get();
    		$users = User::where('updated_at','>=',$created_at)->get();
    		// 
    		if(count($colors) > 0){
    			$update['colors'] = $colors;
    			$update_rows += count($colors);
    		}
    		if(count($companies) > 0){
    			$update['companies'] = $companies;
    			$update_rows += count($companies);
    		}
    		if(count($custids) > 0){
    			$update['custids'] = $custids;
    			$update_rows += count($custids);
    		}
    		// if(count($customers) > 0){
    		// 	$update['customers'] = $customers;
    		// }
    		if(count($deliveries) > 0){
    			$update['deliveries'] = $deliveries;
    			$update_rows += count($deliveries);
    		}
    		if(count($discounts) > 0){
    			$update['discounts'] = $discounts;
    			$update_rows += count($discounts);
    		}
    		if(count($inventories) > 0){
    			$update['inventories'] = $inventories;
    			$update_rows += count($inventories);
    		}
    		if(count($inventory_items) > 0){
    			$update['inventory_items'] = $inventory_items;
    			$update_rows += count($inventory_items);
    		}
    		if(count($invoices) > 0){
    			$update['invoices'] = $invoices;
    			$update_rows += count($invoices);
    		}
    		if(count($invoice_items) > 0){
    			$update['invoice_items'] = $invoice_items;
    			$update_rows += count($invoice_items);
    		}
    		if(count($memos) > 0){
    			$update['memos'] = $memos;
    			$update_rows += count($memos);
    		}
    		if(count($printers) > 0){
    			$update['printers'] = $printers;
    			$update_rows += count($printers);
    		}
    		if(count($rewards) > 0){
    			$update['rewards'] = $rewards;
    			$update_rows += count($rewards);
    		}
			if(count($reward_transactions) > 0){
    			$update['reward_transactions'] = $reward_transactions;
    			$update_rows += count($reward_transactions);
    		}
    		if(count($schedules) > 0){
    			$update['schedules'] = $schedules;
    			$update_rows += count($schedules);
    		}
    		if(count($taxes) > 0){
    			$update['taxes'] = $taxes;
    			$update_rows += count($taxes);
    		}
    		if(count($transactions) > 0){
    			$update['transactions'] = $transactions;
    			$update_rows += count($transactions);
    		}
    		if(count($users) > 0){
    			$update['users'] = $users;
    			$update_rows += count($users);
    		}
    	}

    	return [$update, $update_rows];
    }
}
