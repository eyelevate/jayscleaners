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
    			if(isset($data[$key]['id'])){
    				$data[$key]['items'] = InvoiceItem::where('invoice_id',$data[$key]['invoice_id'])->where('status',1)->get();
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
}
