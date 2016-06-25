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
    public static function prepareInvoice($company_id,$data){
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
    			if(isset($data[$key]['items'])){
    				foreach ($data[$key]['items'] as $ikey => $ivalue) {
    					if(isset($data[$key]['items'][$ikey]['color']) && $data[$key]['items'][$ikey]['color']){
    						$data[$key]['items'][$ikey]['color'] = Color::find($ivalue['color'])->color;
 
    					}
    					if(isset($data[$key]['items'][$ikey]['item_id'])) {
    						$inventoryItem = InventoryItem::find($ivalue['item_id']);
    						$data[$key]['items'][$ikey]['item_name'] = $inventoryItem->name;
    						$data[$key]['items'][$ikey]['tags'] = $inventoryItem->tags;
                            $total += $inventoryItem->price;
    						$qty++;
    						$total_pcs += ($inventoryItem->tags > 0) ? $inventoryItem->tags : 1;
    					}

    				}
    			}

    			$data[$key]['quantity'] = $qty;
    			$data[$key]['tags'] = $total_pcs;
                $data[$key]['total'] = money_format('$%i', $total * (1+$tax_rate));
    		}
    	}



    	return $data;
    }
}
