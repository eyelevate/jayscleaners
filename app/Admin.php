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

    public static function makeUpdate($company, $server_at) {
    	$company_id = $company->id;
    	$last_created_at = $company->created_at;
    	$update = [];
    	$update_rows = 0;

    	if (is_numeric(strtotime($server_at))) {
    		// get all data from models
    		$colors = Color::where('updated_at','>=',$server_at)->get();
    		$companies = Company::where('updated_at','>=',$server_at)->get();
    		$custids = Custid::where('updated_at','>=',$server_at)->get();
    		// $customers = Customer::where('updated_at',' >= ',$server_at)->get();
    		$deliveries = Delivery::where('updated_at','>=',$server_at)->get();
    		$discounts = Discount::where('updated_at','>=',$server_at)->get();
    		$inventories = Inventory::where('updated_at','>=',$server_at)->get();
    		$inventory_items = InventoryItem::where('updated_at','>=',$server_at)->get();
    		$invoices = Invoice::where('updated_at','>=',$server_at)->get();
    		$invoice_items = InvoiceItem::where('updated_at','>=',$server_at)->get();
    		$memos = Memo::where('updated_at','>=',$server_at)->get();
    		$printers = Printer::where('updated_at','>=',$server_at)->get();
    		$rewards = Reward::where('updated_at','>=',$server_at)->where('company_id',$company_id)->get();
    		$reward_transactions = RewardTransaction::where('updated_at','>=',$server_at)->get();
    		$schedules = Schedule::where('updated_at','>=',$server_at)->get();
    		$taxes = Tax::where('updated_at','>=',$server_at)->get();
    		$transactions = Transaction::where('updated_at','>=',$server_at)->get();
    		$users = User::where('updated_at','>=',$server_at)->get();

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

    static function makeUpload($c, $up){
    	$company_id = $c->id;
    	$last_created_at = $c->created_at;
    	$uploaded_rows = 0;
    	if(isset($up['colors'])){
    		foreach ($up['colors'] as $key => $value) {
    			$color = new Color();
    			$color->company_id = $company_id;
    			$color->color = $value['color'];
    			$color->name = $value['name'];
    			$color->ordered = $value['ordered'];
    			$color->status = $value['status'];
    			if($color->save()){
    				$up['colors'][$key]['color_id'] = $color->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['companies'])) {
    		foreach ($up['companies'] as $key => $value) {
    			$company = new Company();
    			$company->name = $value['name'];
    			$company->street = $value['street'];
    			$company->city = $value['city'];
    			$company->state = $value['state'];
    			$company->zip = $value['zip'];
    			$company->phone = $value['phone'];
    			$company->email = $value['email'];
    			$company->store_hours = $value['store_hours'];
    			$company->turn_around = $value['turn_around'];
    			$company->api_token = $value['api_token'];
    			if($company->save()){
    				$up['companies'][$key]['company_id'] = $company->id;
    				$uploaded_rows++;
    			}
    		}
    	} 

    	if (isset($up['custids'])){
    		foreach ($up['custids'] as $key => $value) {
    			$custid = new Custid();
    			$custid->customer_id = $value['customer_id'];
                $custid->company_id = $value['company_id'];
    			$custid->mark = $value['mark'];
    			$custid->status = $value['status'];
    			if($custid->save()){
    				$up['custids'][$key]['cust_id'] = $custid->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['deliveries'])){
    		foreach ($up['deliveries'] as $key => $value) {
    			$delivery = new Delivery();
    			$delivery->customer_id = $value['customer_id'];
    			$delivery->mark = $value['mark'];
    			$delivery->status = $value['status'];
    			if($delivery->save()){
    				$up['deliveries'][$key]['delivery_id'] = $delivery->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['discounts'])){
    		foreach ($up['discounts'] as $key => $value) {
    			$discount = new Discount();
    			$discount->company_id = $company_id;
    			$discount->inventory_id = $value['inventory_id'];
    			$discount->inventory_item_id = $value['inventory_item_id'];
    			$discount->name = $value['name'];
    			$discount->type = $value['type'];
    			$discount->discount = $value['discount'];
    			$discount->rate = $value['rate'];
    			$discount->end_time = $value['end_time'];
    			$discount->start_date = $value['start_date'];
    			$discount->end_date = $value['end_date'];
    			$discount->status = $value['status'];
    			if($discount->save()){
    				$up['discounts'][$key]['discount_id'] = $discount->id;
    				$uploaded_rows++;
    			}
    		}
    	} 

    	if (isset($up['inventories'])){
    		foreach ($up['inventories'] as $key => $value) {
    			$inventory = new Inventory();
    			$inventory->company_id = $company_id;
    			$inventory->name = $value['name'];
    			$inventory->description = $value['description'];
    			$inventory->ordered = $value['ordered'];
    			$inventory->status = $value['status'];
    			if($inventory->save()){
    				$up['inventories'][$key]['inventory_id'] = $inventory->id;
    				$uploaded_rows++;
    			}
    		}
    	} 

    	if (isset($up['inventory_items'])){
    		foreach ($up['inventory_items'] as $key => $value) {
    			$inventory_item = new InventoryItem();
    			$inventory_item->company_id = $company_id;
    			$inventory_item->inventory_id = $value['inventory_id'];
    			$inventory_item->name = $value['name'];
    			$inventory_item->description = $value['description'];
    			$inventory_item->tags = $value['tags'];
    			$inventory_item->quantity = $value['quantity'];
    			$inventory_item->ordered = $value['ordered'];
    			$inventory_item->price = $value['price'];
    			$inventory_item->image = $value['image'];
    			$inventory_item->status = $value['status'];
    			if($inventory_item->save()){
    				$up['inventory_items'][$key]['item_id'] = $inventory_item->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['invoices'])){
    		foreach ($up['invoices'] as $key => $value) {
    			$invoice = new Invoice();
    			$invoice->company_id = $company_id;
    			$invoice->customer_id = $value['customer_id'];
    			$invoice->quantity = $value['quantity'];
    			$invoice->pretax = $value['pretax'];
    			$invoice->tax = $value['tax'];
    			$invoice->reward_id = $value['reward_id'];
    			$invoice->discount_id = $value['discount_id'];
    			$invoice->rack = $value['rack'];
    			$invoice->rack_date = $value['rack_date'];
    			$invoice->due_date = $value['due_date'];
    			$invoice->memo = $value['memo'];
    			$invoice->status = $value['status'];
    			if($invoice->save()){
    				$up['invoices'][$key]['invoice_id'] = $invoice->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['invoice_items'])){
    		foreach ($up['invoice_items'] as $key => $value) {
    			$invoice_item = new InvoiceItem();
    			$invoice_item->invoice_id = $value['invoice_id'];
    			$invoice_item->item_id = $value['item_id'];
    			$invoice_item->company_id = $company_id;
    			$invoice_item->customer_id = $value['customer_id'];
    			$invoice_item->quantity = $value['quantity'];
    			$invoice_item->color = $value['color'];
    			$invoice_item->memo = $value['memo'];
    			$invoice_item->pretax = $value['pretax'];
    			$invoice_item->tax = $value['tax'];
    			$invoice_item->total = $value['total'];
    			$invoice_item->status = $value['status'];
    			if($invoice_item->save()){
    				$up['invoice_items'][$key]['invoice_item_id'] = $invoice_item->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['memos'])){
    		foreach ($up['memos'] as $key => $value) {
    			$memo = new Memo();
    			$memo->company_id = $company_id;
    			$memo->memo = $value['memo'];
    			$memo->ordered = $value['ordered'];
    			$memo->status = $value['status'];
    			if($memo->save()){
    				$up['memos'][$key]['memo_id'] = $memo->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['printers'])){
    		foreach ($up['printers'] as $key => $value) {
    			$printer = new Printer();
    			$printer->company_id = $company_id;
    			$printer->name = $value['name'];
    			$printer->model = $value['model'];
    			$printer->nick_name = $value['nick_name'];
    			$printer->type = $value['type'];
    			$printer->status = $value['status'];
    			if($printer->save()){
    				$up['printers'][$key]['printer_id'] = $printer->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['rewards'])){
    		foreach ($up['rewards'] as $key => $value) {
    			$reward = new Reward();
    			$reward->company_id = $company_id;
    			$reward->name = $value['name'];
    			$reward->points = $value['points'];
    			$reward->discount = $value['discount'];
    			$reward->status = $value['status'];
    			if($reward->save()){
    				$up['rewards'][$key]['reward_id'] = $reward->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['reward_transactions'])){
    		foreach ($up['reward_transactions'] as $key => $value) {
    			$reward_transaction = new RewardTransaction();
    			$reward_transaction->reward_id = $value['reward_id'];
    			$reward_transaction->transaction_id = $value['transaction_id'];
    			$reward_transaction->customer_id = $value['customer_id'];
    			$reward_transaction->employee_id = $value['employee_id'];
    			$reward_transaction->company_id = $company_id;
    			$reward_transaction->type = $value['type'];
    			$reward_transaction->points = $value['points'];
    			$reward_transaction->credited = $value['credited'];
    			$reward_transaction->reduced = $value['reduced'];
    			$reward_transaction->running_total = $value['running_total'];
    			$reward_transaction->reason = $value['reason'];
    			$reward_transaction->name = $value['name'];
    			$reward_transaction->status = $value['status'];
    			if($reward_transaction->save()){
    				$up['reward_transactions'][$key]['reward_id'] = $reward_transaction->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['schedules'])){
    		foreach ($up['schedules'] as $key => $value) {
    			$schedule = new Schedule();
    			$schedule->company_id = $company_id;
    			$schedule->customer_id = $value['customer_id'];
    			$schedule->pickup_delivery_id = $value['pickup_delivery_id'];
    			$schedule->pickup_date = $value['pickup_date'];
    			$schedule->dropoff_delivery_id = $value['dropoff_delivery_id'];
    			$schedule->dropoff_date = $value['dropoff_date'];
    			$schedule->special_instructions = $value['special_instructions'];
    			$schedule->type = $value['type'];
    			$schedule->token = $value['token'];
    			$schedule->status = $value['status'];
    			if($schedule->save()){
    				$up['schedules'][$key]['schedule_id'] = $schedule->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['taxes'])){
    		foreach ($up['taxes'] as $key => $value) {
    			$tax = new Tax();
    			$tax->company_id = $company_id;
    			$tax->rate = $value['rate'];
    			$tax->status = $value['status'];
    			if($tax->save()){
    				$up['taxes'][$key]['tax_id'] = $tax->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['transactions'])){
    		foreach ($up['transactions'] as $key => $value) {
    			$transaction = new Schedule();
    			$transaction->company_id = $company_id;
    			$transaction->customer_id = $value['customer_id'];
    			$transaction->schedule_id = $value['schedule_id'];
    			$transaction->pretax = $value['pretax'];
    			$transaction->tax = $value['tax'];
    			$transaction->aftertax = $value['aftertax'];
    			$transaction->discount = $value['discount'];
    			$transaction->invoices = $value['invoices'];
    			$transaction->type = $value['type'];
    			$transaction->last_four = $value['last_four'];
    			$transaction->tendered = $value['tendered'];
    			$transaction->transaction_id = $value['transaction_id'];
    			$transaction->status = $value['status'];
    			if($transaction->save()){
    				$up['transactions'][$key]['transaction_id'] = $transaction->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	if (isset($up['users'])){
    		foreach ($up['users'] as $key => $value) {
    			$user = new User();
                $last_user_id = User::where('company_id',$company_id)->orderBy('id','desc')->limit('1')->pluck('id');
                $user->user_id = ($last_user_id[0]) ? $last_user_id[0]+1 : 1;
    			$user->company_id = $company_id;
    			$user->username = $value['username'];
    			$user->first_name = $value['first_name'];
    			$user->last_name = $value['last_name'];
    			$user->street = $value['street'];
    			$user->suite = $value['suite'];
    			$user->city = $value['city'];
    			$user->state = $value['state'];
    			$user->zipcode = $value['zipcode'];
    			$user->email = $value['email'];
    			$user->phone = $value['phone'];
    			$user->intercom = $value['intercom'];
    			$user->concierge_name = $value['concierge_name'];
    			$user->concierge_number = $value['concierge_number'];
    			$user->special_instructions = $value['special_instructions'];
    			$user->shirt_old = $value['shirt_old'];
    			$user->shirt = $value['shirt'];
    			$user->delivery = $value['delivery'];
    			$user->profile_id = $value['profile_id'];
    			$user->payment_status = $value['payment_status'];
    			$user->payment_id = $value['payment_id'];
    			$user->token = $value['token'];
    			$user->api_token = $value['api_token'];
    			$user->reward_status = $value['reward_status'];
    			$user->reward_points = $value['reward_points'];
    			$user->account = $value['account'];
    			$user->starch = $value['starch'];
    			$user->important_memo = $value['important_memo'];
    			$user->invoice_memo = $value['invoice_memo'];
    			$user->role_id = $value['role_id'];
    			if($user->save()){
    				$up['users'][$key]['user_id'] = $user->id;
    				$uploaded_rows++;
    			}
    		}
    	} 
    	return [$uploaded_rows,$up];
    }
    static function makePut($c,$up){
        $company_id = $c->id;
        $uploaded_rows = 0;
        if(isset($up['colors'])){
            foreach ($up['colors'] as $key => $value) {
                $color = Color::withTrashed()->find($value['color_id']);
                $color->company_id = $company_id;
                $color->color = $value['color'];
                $color->name = $value['name'];
                $color->ordered = $value['ordered'];
                $color->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $color->delete();
                } elseif($color->trashed() && !isset($value['deleted_at'])) {
                    $color->restore();
                } else {
                    $color->save(); 
                }
            }
        } 
        if (isset($up['companies'])) {
            foreach ($up['companies'] as $key => $value) {
                $company = Company::withTrashed()->find($value['company_id']);
                $company->name = $value['name'];
                $company->street = $value['street'];
                $company->city = $value['city'];
                $company->state = $value['state'];
                $company->zip = $value['zip'];
                $company->phone = $value['phone'];
                $company->email = $value['email'];
                $company->store_hours = $value['store_hours'];
                $company->turn_around = $value['turn_around'];
                $company->api_token = $value['api_token'];
                if(isset($value['deleted_at'])) {
                    $company->delete();
                } elseif($company->trashed() && !isset($value['deleted_at'])) {
                    $company->restore();
                } else {
                    $company->save(); 
                }
            }
        } 

        if (isset($up['custids'])){
            foreach ($up['custids'] as $key => $value) {
                $custid = Custid::withTrashed()->find($value['cust_id']);
                $custid->customer_id = $value['customer_id'];
                $custid->company_id = $value['company_id'];
                $custid->mark = $value['mark'];
                $custid->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $custid->delete();
                } elseif($custid->trashed() && !isset($value['deleted_at'])) {
                    $custid->restore();
                } else {
                    $custid->save(); 
                }
            }
        } 
        if (isset($up['deliveries'])){
            foreach ($up['deliveries'] as $key => $value) {
                $delivery = Delivery::withTrashed()->find($value['delivery_id']);
                $delivery->customer_id = $value['customer_id'];
                $delivery->mark = $value['mark'];
                $delivery->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $delivery->delete();
                } elseif($delivery->trashed() && !isset($value['deleted_at'])) {
                    $delivery->restore();
                } else {
                    $delivery->save(); 
                }
            }
        }  
        if (isset($up['discounts'])){
            foreach ($up['discounts'] as $key => $value) {
                $discount = Discount::withTrashed()->find($value['discount_id']);
                $discount->company_id = $company_id;
                $discount->inventory_id = $value['inventory_id'];
                $discount->inventory_item_id = $value['inventory_item_id'];
                $discount->name = $value['name'];
                $discount->type = $value['type'];
                $discount->discount = $value['discount'];
                $discount->rate = $value['rate'];
                $discount->end_time = $value['end_time'];
                $discount->start_date = $value['start_date'];
                $discount->end_date = $value['end_date'];
                $discount->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $discount->delete();
                } elseif($discount->trashed() && !isset($value['deleted_at'])) {
                    $discount->restore();
                } else {
                    $discount->save(); 
                }
            }
        } 

        if (isset($up['inventories'])){
            foreach ($up['inventories'] as $key => $value) {
                $inventory = Inventory::withTrashed()->find($value['inventory_id']);
                $inventory->company_id = $company_id;
                $inventory->name = $value['name'];
                $inventory->description = $value['description'];
                $inventory->ordered = $value['ordered'];
                $inventory->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $inventory->delete();
                } elseif($inventory->trashed() && !isset($value['deleted_at'])) {
                    $inventory->restore();
                } else {
                    $inventory->save(); 
                }
            }
        } 

        if (isset($up['inventory_items'])){
            foreach ($up['inventory_items'] as $key => $value) {
                $inventory_item = InventoryItem::withTrashed()->find($value['item_id']);
                $inventory_item->company_id = $company_id;
                $inventory_item->inventory_id = $value['inventory_id'];
                $inventory_item->name = $value['name'];
                $inventory_item->description = $value['description'];
                $inventory_item->tags = $value['tags'];
                $inventory_item->quantity = $value['quantity'];
                $inventory_item->ordered = $value['ordered'];
                $inventory_item->price = $value['price'];
                $inventory_item->image = $value['image'];
                $inventory_item->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $inventory_item->delete();
                } elseif($inventory_item->trashed() && !isset($value['deleted_at'])) {
                    $inventory_item->restore();
                } else {
                    $inventory_item->save(); 
                }
            }
        } 
        if (isset($up['invoices'])){
            foreach ($up['invoices'] as $key => $value) {
                $invoices = Invoice::withTrashed()->where('invoice_id',$value['invoice_id'])->get();
                if ($invoices){
                    foreach ($invoices as $data) {
                        $invoice = Invoice::withTrashed()->find($data->id);
                        $invoice->company_id = $company_id;
                        $invoice->customer_id = $value['customer_id'];
                        $invoice->quantity = $value['quantity'];
                        $invoice->pretax = $value['pretax'];
                        $invoice->tax = $value['tax'];
                        $invoice->reward_id = $value['reward_id'];
                        $invoice->discount_id = $value['discount_id'];
                        $invoice->rack = $value['rack'];
                        $invoice->rack_date = $value['rack_date'];
                        $invoice->due_date = $value['due_date'];
                        $invoice->memo = $value['memo'];
                        $invoice->status = $value['status'];
                        if(isset($value['deleted_at'])) {
                            $invoice->delete();
                        } elseif($invoice->trashed() && !isset($value['deleted_at'])) {
                            $invoice->restore();
                        } else {
                            $invoice->save(); 
                        }
                        
                    }
                }
            }
        } 
        if (isset($up['invoice_items'])){
            foreach ($up['invoice_items'] as $key => $value) {
                $invoice_item = InvoiceItem::withTrashed()->find($value['invoice_items_id']);
                $invoice_item->invoice_id = $value['invoice_id'];
                $invoice_item->item_id = $value['item_id'];
                $invoice_item->company_id = $company_id;
                $invoice_item->customer_id = $value['customer_id'];
                $invoice_item->quantity = $value['quantity'];
                $invoice_item->color = $value['color'];
                $invoice_item->memo = $value['memo'];
                $invoice_item->pretax = $value['pretax'];
                $invoice_item->tax = $value['tax'];
                $invoice_item->total = $value['total'];
                $invoice_item->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $invoice_item->delete();
                } elseif($invoice_item->trashed() && !isset($value['deleted_at'])) {
                    $invoice_item->restore();
                } else {
                    $invoice_item->save(); 
                }
            }
        } 
        if (isset($up['memos'])){
            foreach ($up['memos'] as $key => $value) {
                $memo = Memo::withTrashed()->find($value['memo_id']);
                $memo->company_id = $company_id;
                $memo->memo = $value['memo'];
                $memo->ordered = $value['ordered'];
                $memo->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $memo->delete();
                } elseif($memo->trashed() && !isset($value['deleted_at'])) {
                    $memo->restore();
                } else {
                    $memo->save(); 
                }
            }
        } 
        if (isset($up['printers'])){
            foreach ($up['printers'] as $key => $value) {
                $printer = Printer::withTrashed()->find($value['printer_id']);
                $printer->company_id = $company_id;
                $printer->name = $value['name'];
                $printer->model = $value['model'];
                $printer->nick_name = $value['nick_name'];
                $printer->type = $value['type'];
                $printer->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $printer->delete();
                } elseif($printer->trashed() && !isset($value['deleted_at'])) {
                    $printer->restore();
                } else {
                    $printer->save(); 
                }
            }
        } 
        if (isset($up['rewards'])){
            foreach ($up['rewards'] as $key => $value) {
                $reward = Reward::withTrashed()->find($value['reward_id']);
                $reward->company_id = $company_id;
                $reward->name = $value['name'];
                $reward->points = $value['points'];
                $reward->discount = $value['discount'];
                $reward->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $reward->delete();
                } elseif($reward->trashed() && !isset($value['deleted_at'])) {
                    $reward->restore();
                } else {
                    $reward->save(); 
                }
            }
        } 
        if (isset($up['reward_transactions'])){
            foreach ($up['reward_transactions'] as $key => $value) {
                $reward_transaction = RewardTransaction::withTrashed()->find($value['reward_id']);
                $reward_transaction->reward_id = $value['reward_id'];
                $reward_transaction->transaction_id = $value['transaction_id'];
                $reward_transaction->customer_id = $value['customer_id'];
                $reward_transaction->employee_id = $value['employee_id'];
                $reward_transaction->company_id = $company_id;
                $reward_transaction->type = $value['type'];
                $reward_transaction->points = $value['points'];
                $reward_transaction->credited = $value['credited'];
                $reward_transaction->reduced = $value['reduced'];
                $reward_transaction->running_total = $value['running_total'];
                $reward_transaction->reason = $value['reason'];
                $reward_transaction->name = $value['name'];
                $reward_transaction->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $reward_transaction->delete();
                } elseif($reward_transaction->trashed() && !isset($value['deleted_at'])) {
                    $reward_transaction->restore();
                } else {
                    $reward_transaction->save(); 
                }
            }
        }
        if (isset($up['schedules'])){
            foreach ($up['schedules'] as $key => $value) {
                $schedule = Schedule::withTrashed()->find($value['schedule_id']);
                $schedule->company_id = $company_id;
                $schedule->customer_id = $value['customer_id'];
                $schedule->pickup_delivery_id = $value['pickup_delivery_id'];
                $schedule->pickup_date = $value['pickup_date'];
                $schedule->dropoff_delivery_id = $value['dropoff_delivery_id'];
                $schedule->dropoff_date = $value['dropoff_date'];
                $schedule->special_instructions = $value['special_instructions'];
                $schedule->type = $value['type'];
                $schedule->token = $value['token'];
                $schedule->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $schedule->delete();
                } elseif($schedule->trashed() && !isset($value['deleted_at'])) {
                    $schedule->restore();
                } else {
                    $schedule->save(); 
                }
            }
        } 
        if (isset($up['taxes'])){
            foreach ($up['taxes'] as $key => $value) {
                $tax = Tax::withTrashed()->find($value['tax_id']);
                $tax->company_id = $company_id;
                $tax->rate = $value['rate'];
                $tax->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $tax->delete();
                } elseif($tax->trashed() && !isset($value['deleted_at'])) {
                    $tax->restore();
                } else {
                    $tax->save(); 
                }

            }
        } 
        if (isset($up['transactions'])){
            foreach ($up['transactions'] as $key => $value) {
                $transaction = Transaction::withTrashed()->find($value['transaction_id']);
                $transaction->company_id = $company_id;
                $transaction->customer_id = $value['customer_id'];
                $transaction->schedule_id = $value['schedule_id'];
                $transaction->pretax = $value['pretax'];
                $transaction->tax = $value['tax'];
                $transaction->aftertax = $value['aftertax'];
                $transaction->discount = $value['discount'];
                $transaction->invoices = $value['invoices'];
                $transaction->type = $value['type'];
                $transaction->last_four = $value['last_four'];
                $transaction->tendered = $value['tendered'];
                $transaction->transaction_id = $value['transaction_id'];
                $transaction->status = $value['status'];
                if(isset($value['deleted_at'])) {
                    $transaction->delete();
                } elseif($transaction->trashed() && !isset($value['deleted_at'])) {
                    $transaction->restore();
                } else {
                    $transaction->save(); 
                }
            }
        } 
        if (isset($up['users'])){
            foreach ($up['users'] as $key => $value) {
                $users = User::withTrashed()->where('user_id',$value['user_id'])->get();
                if($users){
                    foreach ($users as $data) {
                        $user = User::withTrashed()->find($data->id);
                        $user->username = $value['username'];
                        $user->first_name = $value['first_name'];
                        $user->last_name = $value['last_name'];
                        $user->street = $value['street'];
                        $user->suite = $value['suite'];
                        $user->city = $value['city'];
                        $user->state = $value['state'];
                        $user->zipcode = $value['zipcode'];
                        $user->email = $value['email'];
                        $user->phone = $value['phone'];
                        $user->intercom = $value['intercom'];
                        $user->concierge_name = $value['concierge_name'];
                        $user->concierge_number = $value['concierge_number'];
                        $user->special_instructions = $value['special_instructions'];
                        $user->shirt_old = $value['shirt_old'];
                        $user->shirt = $value['shirt'];
                        $user->delivery = $value['delivery'];
                        $user->profile_id = $value['profile_id'];
                        $user->payment_status = $value['payment_status'];
                        $user->payment_id = $value['payment_id'];
                        $user->token = $value['token'];
                        $user->api_token = $value['api_token'];
                        $user->reward_status = $value['reward_status'];
                        $user->reward_points = $value['reward_points'];
                        $user->account = $value['account'];
                        $user->starch = $value['starch'];
                        $user->important_memo = $value['important_memo'];
                        $user->invoice_memo = $value['invoice_memo'];
                        $user->role_id = $value['role_id'];
                        if(isset($value['deleted_at'])) {
                            $user->delete();
                        } elseif($user->trashed() && !isset($value['deleted_at'])) {
                            $user->restore();
                        } else {
                            $user->save(); 
                        }                     
                    }
                }

            }
        } 
    }
}
