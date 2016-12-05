<?php

namespace App;
use Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Company;
use App\Invoice;
use App\Job;
use App\InvoiceItem;
use App\InventoryItem;
use App\Transaction;
use App\User;
use DNS1D;
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

    public static function getMonth(){
    	$this_month = date('n');
    	$months = [];
    	$year = date('Y');
    	for ($i=1; $i <= $this_month; $i++) { 
    		$check = date('F Y',strtotime($i.'/01/'.$year));
    		$months[$i] = $check;
    	}
    	return $months;
    }

    public static function getYear() {
    	$year = [];
    	for ($i=2016; $i <= date('Y'); $i++) { 
    		$year[$i] = $i;
    	}

    	return $year;
    }

    public static function makeBillHtml($ids = null, $items = false){
    	$html = Account::makePdfHead();
    	$current_date = date('F d, Y');
    	$html .= '<body>';
    	if (count($ids) > 0) {

    		$transactions = Transaction::whereIn('id',$ids)->get();
    		if (count($transactions)>0) {
    			foreach ($transactions as $transaction) {
	   
					$company_id = $transaction->company_id;
					$companies = Company::find($company_id);
					$users = User::find($transaction->customer_id);
			    	$html .= '<h1 style="text-align:center; margin:0px; padding:0px;">Account Billing Invoice</h1>';
			    	$html .= '<div style="width:50%; float:left;">';
			    	$html .= '<p style="padding-top:0px;margin-top:0px;"><span style="font-size:20px;"><b>'.ucFirst($companies->name).'</b></span><br/>';
			    	$html .= $companies->street.' <br/>'.$companies->city.', WA '.$companies->zipcode.'<br/>'.Job::formatPhoneString($companies->phone).'</p>';
			    	$html .= '<br/>';
			    	$html .= '<p style="margin-top:35px;"><span style="font-size:20px;"><b>'.ucFirst($users->first_name).' '.ucFirst($users->last_name).'</b></span><br/>';
			    	if ($users->suite) {
			    		$html .= $users->street.' '.$users->suite.' <br/> '.$users->city.', WA '.$users->zipcode.'<br/>'.Job::formatPhoneString($users->phone).'</p>';
			    	} else {
			    		$html .= $users->street.' <br/> '.$users->city.', WA '.$users->zipcode.'<br/>'.Job::formatPhoneString($users->phone).'</p>';
			    	}
			    	
			    	$html .= '</div>';
			    	$html .= '<div style="width:50%; float:left;">';
			    	$html .= '<table class="table table-condensed table-hover" style="font-size:20px; width:100%;">';
			    	$html .= '<tbody>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Invoice #</th>';
			    	$html .= '<td style="text-align:center;">'.$transaction->id.'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Date</th>';
			    	$html .= '<td style="text-align:center;">'.$current_date.'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Billing Period</th>';
			    	$html .= '<td style="text-align:center;">'.date('n/1/Y',strtotime($transaction->created_at)).' - '.date('n/t/Y',strtotime($transaction->created_at)).'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Customer #</th>';
			    	$html .= '<td style="text-align:center;">'.$transaction->customer_id.'</td>';
			    	$html .= '</tr>';
			    	// get last paid
			    	$last_trans = Transaction::where('status',1)
			    		->orderBy('id','desc')
			    		->limit(1)
			    		->get();
			    	if (count($last_trans) > 0) {
			    		foreach ($last_trans as $last) {
			    			$last_paid_date = date('F d, Y',strtotime($last->account_paid_on));
			    			$last_paid_amount = money_format('$%i',$last->account_paid);
			    		}
			    	} else {
			    		$last_paid_date = 'Not Paid';
			    		$last_paid_amount = 'Not Paid';
			    	}
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Last Pay Date</th>';
			    	$html .= '<td style="text-align:center;">'.$last_paid_date.'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Last Pay Amount</th>';
			    	$html .= '<td style="text-align:center;">'.$last_paid_amount.'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Due Date</th>';
			    	$due_on = date('F 15, Y',strtotime($transaction->created_at.' +1 month'));
			    	$html .= '<td style="text-align:center;">'.$due_on.'</td>';
			    	$html .= '</tr>';
			    	$html .= '</tbody>';
			    	$html .= '</table>';
			    	$html .= '</div>';
			    	$html .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
			    	$html .= '<hr style="border:1px dashed #5e5e5e"/>';
			    	$html .= '<ol>';
			    	$html .= '<li>You may pay your bill online by visiting <a style="text-decoration:underline; color: blue; font-style:italic;" href="'.route('accounts_payMyBill').'">www.jayscleaners.com/paymybill</a>. Sign in is required.</li>';
			    	$html .= '<li>You may pay your bill in person at one of our physical locations. (Montlake / Roosevelt)</li>';
			    	$html .= '<li>We accept check, credit card, cash payments only. Thank you.</li>';
			    	$html .= '</ol>';
			    	$html .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
			    	$html .= '<span style="font-size:12px;">Perforated line, detach here and send back with payment. Thank you.</span>';
			    	$html .= '<hr style="border:1px dashed #5e5e5e"/>';
			    	$html .= '<div style="width:50%; float:left;">';
			    	$html .= '<p style="margin-top:35px;"><span style="font-size:20px;"><b>'.ucFirst($users->first_name).' '.ucFirst($users->last_name).'</b></span><br/>';
			    	$html .= $users->street.' <br/> '.$users->city.', WA '.$users->zipcode.'<br/>'.Job::formatPhoneString($users->phone).'</p>';
			    	$html .= '<br/><br/>';
			    	$html .= '<p style="padding-top:0px;margin-top:0px;"><span style="font-size:20px;"><b>'.ucFirst($companies->name).'</b></span><br/>';
			    	$html .= $companies->street.' <br/>'.$companies->city.', WA '.$companies->zipcode.'<br/>'.Job::formatPhoneString($companies->phone).'</p>';		    	
			    	$html .= '</div>';
			    	$html .= '<br/>';
			    	$html .= '<div style="width:50%; float:left;">';
			    	$html .= '<table class="table table-condensed table-hover" style="font-size:20px; width:100%;">';
			    	$html .= '<tbody>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Due Date</th>';
			    	$due_on = date('F 15, Y',strtotime($transaction->created_at.' +1 month'));
			    	$html .= '<td style="text-align:center;">'.$due_on.'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Due Amount</th>';
			    	$html .= '<td style="text-align:center;">'.money_format('$%i',$users->account_total).'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Customer #</th>';
			    	$html .= '<td style="text-align:center;">'.$users->id.'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Invoice #</th>';
			    	$html .= '<td style="text-align:center;">'.$transaction->id.'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th style="background-color:#e5e5e5">Amount Paid</th>';
			    	$html .= '<td style="text-align:center;">$______________</td>';
			    	$html .= '</tr>';
			    	$html .= '</tbody>';
			    	$html .= '</table>';
			    	$html .= '<br/><br/>';
			    	$html .= '<table class="table table-condensed table-hover" style="font-size:20px; width:100%;">';
			    	$html .= '<tbody>';
			    	$html .= '<tr>';
			    	$html .= '<td colspan="2">'.DNS1D::getBarcodeHTML($transaction->id, "C39").'</td>';
			    	$html .= '</tr>';
			    	$html .= '</tbody>';
			    	$html .= '</table>';
			    	$html .= '</div>';
			    	$html .= '<p style="width:100%; clear:both; margin-top:0px; margin-bottom:0px; padding-top:0px; padding-bottom:0px; font-size:15px; text-align:center;">Make all checks payable to <span style="font-weight:bold;">Jays Cleaners</span></p>';
			    	$html .= '<div style="page-break-after: always;"></div>'; // Page Break
			    	$html .= '<div style="width:100%;">';
			    	$html .= '<h3 style="text-align:center;">Invoice Summary Totals</h3>';
			    	$html .= '<table style="width:100%;">';
			    	$html .= '<thead>';
			    	$html .= '<tr style="background-color:#e5e5e5;">';
			    	$html .= '<th>Invoice</th>';
			    	$html .= '<th>Drop Date</th>';
			    	$html .= '<th>Pickup Date</th>';
			    	$html .= '<th>Quantity</th>';
			    	$html .= '<th>Subtotal</th>';
			    	$html .= '</tr>';
			    	$html .= '</thead>';
			    	$html .= '<tbody>'; // invoice items
			    	$invoices = Invoice::where('transaction_id',$transaction->id)->get();
			    	if (count($invoices)>0) {
			    		$quantity = 0;
			    		foreach ($invoices as $invoice) {
			    			$quantity += $invoice->quantity;
			    			$html .= '<tr>';
			    			$html .= '<td>'.$invoice->id.'</td>';
			    			$html .= '<td>'.date('n/d/Y g:ia',strtotime($invoice->created_at)).'</td>';
			    			$html .= '<td>'.date('n/d/Y g:ia',strtotime($invoice->due_date)).'</td>';
			    			$html .= '<td>'.$invoice->quantity.'</td>';
			    			$html .= '<td>'.money_format('$%i',$invoice->pretax).'</td>';
			    			$html .= '</tr>';
			    		}
			    	}
		    		$html .= '</tbody>';
			    	$html .= '<tfoot style="border-top:1px solid #000000">';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>Quantity</th>'; 
			    	$html .= '<td>'.$quantity.'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>Subtotal</th>'; 
			    	$html .= '<td>'.money_format('$%i',$transaction->pretax).'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>Tax</th>'; 
			    	$html .= '<td>'.money_format('$%i',$transaction->tax).'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>After Tax</th>'; 
			    	$html .= '<td>'.money_format('$%i',$transaction->aftertax).'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>Credit</th>'; 
			    	$html .= '<td color="#ff0000">'.money_format('$%i',$transaction->credit).'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>Discount</th>'; 
			    	$html .= '<td color="#ff0000">'.money_format('$%i',$transaction->discount).'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>Due</th>'; 
			    	$html .= '<td>'.money_format('$%i',$transaction->total).'</td>';
			    	$html .= '</tr>';
			    	$html .= '</tfoot>';
			    	$html .= '</table>';
			    		
			    	if ($items) {
				    	$html .= '<h3 style="text-align:center;">Itemized Summary Table</h3>';
				    	$html .= '<table style="width:100%;">';
				    	$html .= '<thead>';
				    	$html .= '<tr style="background-color:#e5e5e5;">';
				    	// $html .= '<th>Item #</th>';
				    	$html .= '<th>Invoice #</th>';
				    	$html .= '<th>Name</th>';
				    	$html .= '<th>Color</th>';
				    	$html .= '<th>Quantity</th>';
				    	$html .= '<th>Subtotal</th>';
				    	$html .= '</tr>';
				    	$html .= '</thead>';
				    	$html .= '<tbody>';
				    	

				    	if (count($invoices)>0) {
				    		$quantity = 0;
				    		foreach ($invoices as $invoice) {
				    			$inv_items = InvoiceItem::prepareGroup(InvoiceItem::where('invoice_id',$invoice->id)->get());
				    			$quantity += $invoice->quantity;
				    			if (count($inv_items)) {
				    				foreach ($inv_items as $inv_item) {
						    			$html .= '<tr>';
						    			// $html .= '<td>'.$inv_item->id.'</td>';
						    			$html .= '<td>'.$inv_item['invoice_id'].'</td>';
						    			$html .= '<td>'.$inv_item['name'].'</td>';
						    			$html .= '<td>'.$inv_item['colors'].'</td>';
						    			$html .= '<td>'.$inv_item['qty'].'</td>';
						    			$html .= '<td>'.money_format('$%i',$inv_item['subtotal']).'</td>';
						    			$html .= '</tr>';			    					
				    				}
				    			}

				    		}
			    		}
			    		$html .= '</tbody>';
				    	$html .= '<tfoot style="border-top:1px solid #000000">';
				    	$html .= '<tr>';
				    	$html .= '<th colspan="3"></th>';
				    	$html .= '<th>Quantity</th>'; 
				    	$html .= '<td>'.$quantity.'</td>';
				    	$html .= '</tr>';
				    	$html .= '<tr>';
				    	$html .= '<th colspan="3"></th>';
				    	$html .= '<th>Subtotal</th>'; 
				    	$html .= '<td>'.money_format('$%i',$transaction->pretax).'</td>';
				    	$html .= '</tr>';
				    	$html .= '<tr>';
				    	$html .= '<th colspan="3"></th>';
				    	$html .= '<th>Tax</th>'; 
				    	$html .= '<td>'.money_format('$%i',$transaction->tax).'</td>';
				    	$html .= '</tr>';
				    	$html .= '<tr>';
				    	$html .= '<th colspan="3"></th>';
				    	$html .= '<th>After Tax</th>'; 
				    	$html .= '<td>'.money_format('$%i',$transaction->aftertax).'</td>';
				    	$html .= '</tr>';
			    		$html .= '<tfoot>';
			    		$html .= '</table>';
			    	}	
			    	$html .= '</div>';
			    	$html .= '<div style="page-break-after: always;"></div>'; // Page Break
		    	}

		    	
    		}
    	}

    	$html .= '</body>';
    	$html .= Account::makePdfFooter();

    	return $html;
    }

    public static function makeSingleBillHtml($id = null, $items = false){
    	$html = Account::makePdfHead();
    	$current_date = date('F d, Y');
    	$html .= '<body>';
    	if (isset($id)) {

    		$transactions = Transaction::find($id);
    		if (count($transactions)>0) {

	   
				$company_id = $transactions->company_id;
				$companies = Company::find($company_id);
				$users = User::find($transactions->customer_id);
		    	$html .= '<h1 style="text-align:center; margin:0px; padding:0px;">Account Billing Invoice</h1>';
		    	$html .= '<div style="width:50%; float:left;">';
		    	$html .= '<p style="padding-top:0px;margin-top:0px;"><span style="font-size:20px;"><b>'.ucFirst($companies->name).'</b></span><br/>';
		    	$html .= $companies->street.' <br/>'.$companies->city.', WA '.$companies->zipcode.'<br/>'.Job::formatPhoneString($companies->phone).'</p>';
		    	$html .= '<br/>';
		    	$html .= '<p style="margin-top:35px;"><span style="font-size:20px;"><b>'.ucFirst($users->first_name).' '.ucFirst($users->last_name).'</b></span><br/>';
	    		if ($users->suite) {
		    		$html .= $users->street.' '.$users->suite.' <br/> '.$users->city.', WA '.$users->zipcode.'<br/>'.Job::formatPhoneString($users->phone).'</p>';
		    	} else {
		    		$html .= $users->street.' <br/> '.$users->city.', WA '.$users->zipcode.'<br/>'.Job::formatPhoneString($users->phone).'</p>';
		    	}
		    	
		    	$html .= '</div>';
		    	$html .= '<div style="width:50%; float:left;">';
		    	$html .= '<table class="table table-condensed table-hover" style="font-size:20px; width:100%;">';
		    	$html .= '<tbody>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Invoice #</th>';
		    	$html .= '<td style="text-align:center;">'.$transactions->id.'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Date</th>';
		    	$html .= '<td style="text-align:center;">'.$current_date.'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Billing Period</th>';
		    	$html .= '<td style="text-align:center;">'.date('n/1/Y',strtotime($transactions->created_at)).' - '.date('n/t/Y',strtotime($transactions->created_at)).'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Customer #</th>';
		    	$html .= '<td style="text-align:center;">'.$transactions->customer_id.'</td>';
		    	$html .= '</tr>';
		    	// get last paid
		    	$last_trans = Transaction::where('status',1)
		    		->orderBy('id','desc')
		    		->limit(1)
		    		->get();
		    	if (count($last_trans) > 0) {
		    		foreach ($last_trans as $last) {
		    			$last_paid_date = date('F d, Y',strtotime($last->account_paid_on));
		    			$last_paid_amount = money_format('$%i',$last->account_paid);
		    		}
		    	} else {
		    		$last_paid_date = 'Not Paid';
		    		$last_paid_amount = 'Not Paid';
		    	}
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Last Pay Date</th>';
		    	$html .= '<td style="text-align:center;">'.$last_paid_date.'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Last Pay Amount</th>';
		    	$html .= '<td style="text-align:center;">'.$last_paid_amount.'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Due Date</th>';
		    	$due_on = date('F 15, Y',strtotime($transactions->created_at.' +1 month'));
		    	$html .= '<td style="text-align:center;">'.$due_on.'</td>';
		    	$html .= '</tr>';
		    	$html .= '</tbody>';
		    	$html .= '</table>';
		    	$html .= '</div>';
		    	$html .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
		    	$html .= '<hr style="border:1px dashed #5e5e5e"/>';
		    	$html .= '<ol>';
		    	$html .= '<li>You may pay your bill online by visiting <a style="text-decoration:underline; color: blue; font-style:italic;" href="'.route('accounts_payMyBill').'">www.jayscleaners.com/pay-my-bill</a>. Sign in is required.</li>';
		    	$html .= '<li>You may pay your bill in person at one of our physical locations. (Montlake / Roosevelt)</li>';
		    	$html .= '<li>We accept check, credit card, cash payments only. Thank you.</li>';
		    	$html .= '</ol>';
		    	$html .= '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
		    	$html .= '<span style="font-size:12px;">Perforated line, detach here and send back with payment. Thank you.</span>';
		    	$html .= '<hr style="border:1px dashed #5e5e5e"/>';
		    	$html .= '<div style="width:50%; float:left;">';
		    	$html .= '<p style="margin-top:35px;"><span style="font-size:20px;"><b>'.ucFirst($users->first_name).' '.ucFirst($users->last_name).'</b></span><br/>';
		    	$html .= $users->street.' <br/> '.$users->city.', WA '.$users->zipcode.'<br/>'.Job::formatPhoneString($users->phone).'</p>';
		    	$html .= '<br/><br/>';
		    	$html .= '<p style="padding-top:0px;margin-top:0px;"><span style="font-size:20px;"><b>'.ucFirst($companies->name).'</b></span><br/>';
		    	$html .= $companies->street.' <br/>'.$companies->city.', WA '.$companies->zipcode.'<br/>'.Job::formatPhoneString($companies->phone).'</p>';		    	
		    	$html .= '</div>';
		    	$html .= '<br/>';
		    	$html .= '<div style="width:50%; float:left;">';
		    	$html .= '<table class="table table-condensed table-hover" style="font-size:20px; width:100%;">';
		    	$html .= '<tbody>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Due Date</th>';
		    	$due_on = date('F 15, Y',strtotime($transactions->created_at.' +1 month'));
		    	$html .= '<td style="text-align:center;">'.$due_on.'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Due Amount</th>';
		    	$html .= '<td style="text-align:center;">'.money_format('$%i',$users->account_total).'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Customer #</th>';
		    	$html .= '<td style="text-align:center;">'.$users->id.'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Invoice #</th>';
		    	$html .= '<td style="text-align:center;">'.$transactions->id.'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th style="background-color:#e5e5e5">Amount Paid</th>';
		    	$html .= '<td style="text-align:center;">$______________</td>';
		    	$html .= '</tr>';
		    	$html .= '</tbody>';
		    	$html .= '</table>';
		    	$html .= '<br/><br/>';
		    	$html .= '<table class="table table-condensed table-hover" style="font-size:20px; width:100%;">';
		    	$html .= '<tbody>';
		    	$html .= '<tr>';
		    	$html .= '<td colspan="2">'.DNS1D::getBarcodeHTML($transactions->id, "C39").'</td>';
		    	$html .= '</tr>';
		    	$html .= '</tbody>';
		    	$html .= '</table>';
		    	$html .= '</div>';
		    	$html .= '<p style="width:100%; clear:both; margin-top:0px; margin-bottom:0px; padding-top:0px; padding-bottom:0px; font-size:15px; text-align:center;">Make all checks payable to <span style="font-weight:bold;">Jays Cleaners</span></p>';
		    	$html .= '<div style="page-break-after: always;"></div>'; // Page Break
		    	$html .= '<div style="width:100%;">';
		    	$html .= '<h3 style="text-align:center;">Invoice Summary Totals</h3>';
		    	$html .= '<table style="width:100%;">';
		    	$html .= '<thead>';
		    	$html .= '<tr style="background-color:#e5e5e5;">';
		    	$html .= '<th>Invoice</th>';
		    	$html .= '<th>Drop Date</th>';
		    	$html .= '<th>Pickup Date</th>';
		    	$html .= '<th>Quantity</th>';
		    	$html .= '<th>Subtotal</th>';
		    	$html .= '</tr>';
		    	$html .= '</thead>';
		    	$html .= '<tbody>'; // invoice items
		    	$invoices = Invoice::where('transaction_id',$transactions->id)->get();
		    	if (count($invoices)>0) {
		    		$quantity = 0;
		    		foreach ($invoices as $invoice) {
		    			$quantity += $invoice->quantity;
		    			$html .= '<tr>';
		    			$html .= '<td>'.$invoice->id.'</td>';
		    			$html .= '<td>'.date('n/d/Y g:ia',strtotime($invoice->created_at)).'</td>';
		    			$html .= '<td>'.date('n/d/Y g:ia',strtotime($invoice->due_date)).'</td>';
		    			$html .= '<td>'.$invoice->quantity.'</td>';
		    			$html .= '<td>'.money_format('$%i',$invoice->pretax).'</td>';
		    			$html .= '</tr>';
		    		}
		    	}
	    		$html .= '</tbody>';
		    	$html .= '<tfoot style="border-top:1px solid #000000">';
		    	$html .= '<tr>';
		    	$html .= '<th colspan="3"></th>';
		    	$html .= '<th>Quantity</th>'; 
		    	$html .= '<td>'.$quantity.'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th colspan="3"></th>';
		    	$html .= '<th>Subtotal</th>'; 
		    	$html .= '<td>'.money_format('$%i',$transactions->pretax).'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th colspan="3"></th>';
		    	$html .= '<th>Tax</th>'; 
		    	$html .= '<td>'.money_format('$%i',$transactions->tax).'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th colspan="3"></th>';
		    	$html .= '<th>After Tax</th>'; 
		    	$html .= '<td>'.money_format('$%i',$transactions->aftertax).'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th colspan="3"></th>';
		    	$html .= '<th>Credit</th>'; 
		    	$html .= '<td color="#ff0000">'.money_format('$%i',$transactions->credit).'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th colspan="3"></th>';
		    	$html .= '<th>Discount</th>'; 
		    	$html .= '<td color="#ff0000">'.money_format('$%i',$transactions->discount).'</td>';
		    	$html .= '</tr>';
		    	$html .= '<tr>';
		    	$html .= '<th colspan="3"></th>';
		    	$html .= '<th>Due</th>'; 
		    	$html .= '<td>'.money_format('$%i',$transactions->total).'</td>';
		    	$html .= '</tr>';
		    	$html .= '</tfoot>';
		    	$html .= '</table>';
		    		
		    	if ($items) {
			    	$html .= '<h3 style="text-align:center;">Itemized Summary Table</h3>';
			    	$html .= '<table style="width:100%;">';
			    	$html .= '<thead>';
			    	$html .= '<tr style="background-color:#e5e5e5;">';
			    	// $html .= '<th>Item #</th>';
			    	$html .= '<th>Invoice #</th>';
			    	$html .= '<th>Name</th>';
			    	$html .= '<th>Color</th>';
			    	$html .= '<th>Quantity</th>';
			    	$html .= '<th>Subtotal</th>';
			    	$html .= '</tr>';
			    	$html .= '</thead>';
			    	$html .= '<tbody>';
			    	

			    	if (count($invoices)>0) {
			    		$quantity = 0;
			    		foreach ($invoices as $invoice) {
			    			$inv_items = InvoiceItem::prepareGroup(InvoiceItem::where('invoice_id',$invoice->id)->get());
			    			$quantity += $invoice->quantity;
			    			if (count($inv_items)) {
			    				foreach ($inv_items as $inv_item) {

					    			$html .= '<tr>';
					    			// $html .= '<td>'.$inv_item->id.'</td>';
					    			$html .= '<td>'.$inv_item['invoice_id'].'</td>';
					    			$html .= '<td>'.$inv_item['name'].'</td>';
					    			$html .= '<td>'.$inv_item['colors'].'</td>';
					    			$html .= '<td>'.$inv_item['qty'].'</td>';
					    			$html .= '<td>'.money_format('$%i',$inv_item['subtotal']).'</td>';
					    			$html .= '</tr>';			    					
			    				}
			    			}

			    		}
		    		}
		    		$html .= '</tbody>';
			    	$html .= '<tfoot style="border-top:1px solid #000000">';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>Quantity</th>'; 
			    	$html .= '<td>'.$quantity.'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>Subtotal</th>'; 
			    	$html .= '<td>'.money_format('$%i',$transactions->pretax).'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>Tax</th>'; 
			    	$html .= '<td>'.money_format('$%i',$transactions->tax).'</td>';
			    	$html .= '</tr>';
			    	$html .= '<tr>';
			    	$html .= '<th colspan="3"></th>';
			    	$html .= '<th>After Tax</th>'; 
			    	$html .= '<td>'.money_format('$%i',$transactions->aftertax).'</td>';
			    	$html .= '</tr>';
		    		$html .= '<tfoot>';
		    		$html .= '</table>';
		    	}	
		    	$html .= '</div>';


		    	$html .= '<div style="page-break-after: always;"></div>'; // Page Break
    		}
    	}

    	$html .= '</body>';
    	$html .= Account::makePdfFooter();

    	return $html;
    }

    private static function makePdfHead() {
    	$html = '<!DOCTYPE HTML>';
        $html .= '<html>';
        $html .= '<head>';
        $html .= '<meta charset="utf-8">';
        $html .= '<title>Account Bill</title>';
        $html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1" />';
        $html .= '<link rel="stylesheet" href="/packages/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css">';
	    // $html .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">';
	    // $html .= '<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">';
	    $html .= '<!--[if lte IE 8]><script src="/packages/html5up-twenty/assets/js/ie/html5shiv.js"></script><![endif]-->';
		$html .= '<link rel="stylesheet" href="/packages/html5up-twenty/assets/css/main.css" />';
		$html .= '<!--[if lte IE 8]><link rel="stylesheet" href="/packages/html5up-twenty/assets/css/ie8.css" /><![endif]-->';
		$html .= '<!--[if lte IE 9]><link rel="stylesheet" href="/packages/html5up-twenty/assets/css/ie9.css" /><![endif]-->';
		// $html .= '<link rel="stylesheet" href="/css/pages/frontend.css" />';
		$html .= '</head>';
        return $html;
    }

    private static function makePdfFooter() {
    	$html ='';
    	// $html = '<footer>';
    	// $html .= '</footer>';
    	// $html .= '</html>';
    	return $html;
    }
}
