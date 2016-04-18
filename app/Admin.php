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

    	if ($created_at > 0) {
    		// get all data from models
    		$colors = Color::where('updated_at','>=',$created_at)->get();
    		$companies = Company::where('updated_at','>=',$created_at)->get();
    		$custids = Custid::where('updated_at','>=',$created_at)->get();
    		// $customers = Customer::where('updated_at',' >= ',$created_at)->get();
    		$deliveries = Delivery::where('updated_at','>=',$created_at)->get();
    		$discounts = Discount::where('updated_at','>=',$created_at)->get();
    		$inventories = Inventory::where('updated_at','>=',$created_at)->get();
    		$inventory_items = InventoryItem::where('updated_at','>=',$created_at)->get();
    		$invoices = Invoice::where('updated_at','>=',$created_at)->get();
    		$invoice_items = InvoiceItem::where('updated_at','>=',$created_at)->get();
    		$memos = Memo::where('updated_at','>=',$created_at)->get();
    		$printers = Printer::where('updated_at','>=',$created_at)->get();
    		$rewards = Reward::where('updated_at','>=',$created_at)->get();
    		$reward_transactions = RewardTransaction::where('updated_at','>=',$created_at)->get();
    		$schedules = Schedule::where('updated_at','>=',$created_at)->get();
    		$taxes = Tax::where('updated_at','>=',$created_at)->get();
    		$transactions = Transaction::where('updated_at','>=',$created_at)->get();
    		$users = User::where('updated_at','>=',$created_at)->get();
    		// 
    		if(count($colors) > 0){
    			$update['colors'] = $colors;
    		}
    		if(count($companies) > 0){
    			$update['companies'] = $companies;
    		}
    		if(count($custids) > 0){
    			$update['custids'] = $custids;
    		}
    		// if(count($customers) > 0){
    		// 	$update['customers'] = $customers;
    		// }
    		if(count($deliveries) > 0){
    			$update['deliveries'] = $deliveries;
    		}
    		if(count($discounts) > 0){
    			$update['discounts'] = $discounts;
    		}
    		if(count($inventories) > 0){
    			$update['inventories'] = $inventories;
    		}
    		if(count($inventory_items) > 0){
    			$update['inventory_items'] = $inventory_items;
    		}
    		if(count($invoices) > 0){
    			$update['invoices'] = $invoices;
    		}
    		if(count($invoice_items) > 0){
    			$update['invoice_items'] = $invoice_items;
    		}
    		if(count($memos) > 0){
    			$update['memos'] = $memos;
    		}
    		if(count($printers) > 0){
    			$update['printers'] = $printers;
    		}
    		if(count($rewards) > 0){
    			$update['rewards'] = $rewards;
    		}
			if(count($reward_transactions) > 0){
    			$update['reward_transactions'] = $reward_transactions;
    		}
    		if(count($schedules) > 0){
    			$update['schedules'] = $schedules;
    		}
    		if(count($taxes) > 0){
    			$update['taxes'] = $taxes;
    		}
    		if(count($transactions) > 0){
    			$update['transactions'] = $transactions;
    		}
    		if(count($users) > 0){
    			$update['users'] = $users;
    		}
    	}

    	return $update;
    }
}
