<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Job;
use App\InvoiceItem;
use App\Color;
use App\Tax;

class Invoice extends Model
{
    use SoftDeletes;

    /**
    * Statuses
    * 1 - New Order 
    * 2 - 
    * 3 - 
    * 4 - 
    * 5 - Paid and complete 
    **/


    public static function prepareInvoice($company_id,$data){
        $item_detail = [];
    	if(isset($data)){
            $taxes = Tax::where('company_id',$company_id)->where('status',1)->first();
            $tax_rate = $taxes['rate'];
    		foreach ($data as $key => $value) {
	    		$qty = 0;
	    		$total_pcs = 0;
                $total = 0;
                $tax_total = 0;
    			if(isset($data[$key]['id'])){
    				$data[$key]['items'] = InvoiceItem::where('invoice_id',$data[$key]['invoice_id'])->where('status',1)->get();
    			}
                if (isset($data[$key]['status'])) {
                    
                }

                $item_detail[$data[$key]['invoice_id']] = [];
    			if(isset($data[$key]['items'])){
                    
                    $color_count = [];
    				$item_count = [];
                    foreach ($data[$key]['items'] as $ikey => $ivalue) {
                        if(isset($item_count[$ivalue->item_id])) {
                            $item_count[$ivalue->item_id] += 1;
                        } else {
                            $item_count[$ivalue->item_id] = 1;
                        }
                        
    					if(isset($data[$key]['items'][$ikey]['color']) && $data[$key]['items'][$ikey]['color']){
    						$data[$key]['items'][$ikey]['color'] = $ivalue['color'];

                            $color_name = ($ivalue['color']) ? $ivalue['color'] : false;
                            if ($color_name) {
                                if (is_numeric($ivalue['color'])) {
                                    $colors = Color::find($ivalue['color']);
                                    $color_name = $colors->name;
                                }

                                if(isset($color_count[$color_name])) {
                                    $color_count[$ivalue->item_id][$color_name] += 1;
                                } else {
                                    $color_count[$ivalue->item_id][$color_name] = 1;
                                }
                            } 
                            $data[$key]['items'][$ikey]['color_name'] = $color_name;
                                
    					}


    					if(isset($data[$key]['items'][$ikey]['item_id'])) {
    						$inventoryItem = InventoryItem::find($ivalue['item_id']);
    						$data[$key]['items'][$ikey]['item_name'] = $inventoryItem->name;
    						$data[$key]['items'][$ikey]['tags'] = $inventoryItem->tags;
                            $total += $inventoryItem->price;
    						$qty++;

                            

    					}  
                        $item_detail[$data[$key]['invoice_id']][$ivalue['item_id']] = [
                            'qty' => $item_count[$ivalue['item_id']],
                            'item' => $inventoryItem->name,
                            'color' => (isset($color_count[$ivalue->item_id])) ? $color_count[$ivalue->item_id] : []
                        ];



    				}
                    $data[$key]['item_details'] = $item_detail[$data[$key]['invoice_id']];
                    
    			}
                
    			$data[$key]['quantity'] = $qty;
    			$data[$key]['tags'] = $total_pcs;
                $data[$key]['pretax_html'] = money_format('$%i',$value->pretax);
                $data[$key]['total'] = money_format('$%i', $total * (1+$tax_rate));
    		}
    	}
    	return $data;
    }

    public static function prepareTotals($data) {
        $totals = ['quantity'=>0,
                   'subtotal'=>0,
                   'tax'=>0,
                   'total'=>0];
        if (count($data) > 0 ) {
            foreach ($data as $key => $value) {
                $totals['quantity'] += $value->quantity;
                $totals['subtotal'] += $value->pretax;
                $totals['tax'] += $value->tax;
                $totals['total'] += $value->total;
            }
        }
        $totals['quantity'] = $totals['quantity'];
        $totals['subtotal_html'] = money_format('$%i',$totals['subtotal']);
        $totals['tax_html'] =  money_format('$%i',$totals['tax']);
        $totals['total_html'] =  money_format('$%i',$totals['total']);

        return $totals;
    }

    public static function newInvoiceId(){
        $invoices = Invoice::where('id','>',0)->orderBy('id','desc')->limit('1')->get();
        if(count($invoices) > 0) {
            foreach ($invoices as $invoice) {
                $last_invoice_id = $invoice['invoice_id'];
                $new_invoice_id = $last_invoice_id + 1;
            }
        } else {
            $new_invoice_id = 1;
        }

        return $new_invoice_id;
    }

    public static function prepareSelected($data) {
        $selected = [];
        $totals = Invoice::prepareTotals($data);
        $invoice_html = '';
        if (count($data) > 0) {
            foreach ($data as $invoice) {
                $invoice_html .= '<tr id="selected_tr-'.$invoice->id.'" class="info">';
                $invoice_html .= '<td>'.str_pad($invoice->invoice_id, 6, '0', STR_PAD_LEFT).'</td>';
                $invoice_html .= '<td>'.date('D n/d',strtotime($invoice->created_at)).'</td>';
                $invoice_html .= '<td>'.date('D n/d',strtotime($invoice->due_date)).'</td>';
                $invoice_html .= '<td>'.$invoice->quantity.'</td>';
                $invoice_html .= '<td>'.money_format('$%i',$invoice->pretax).'</td>';
                $invoice_html .= '</tr>';
            }
        }

        $selected['invoices'] = $invoice_html;
        $selected['totals'] = $totals;

        return $selected;
    }
}
