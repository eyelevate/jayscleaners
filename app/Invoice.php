<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Job;
use App\InvoiceItem;
use App\Color;

class Invoice extends Model
{
    public static function prepareInvoice($data){
    	if(isset($data)){

    		foreach ($data as $key => $value) {
	    		$qty = 0;
	    		$total_pcs = 0;
    			if(isset($data[$key]['id'])){
    				$data[$key]['items'] = InvoiceItem::where('invoice_id',$data[$key]['id'])->where('status',1)->get();
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
    						$qty++;
    						$total_pcs += ($inventoryItem->tags > 0) ? $inventoryItem->tags : 1;
    					}

    				}
    			}

    			$data[$key]['quantity'] = $qty;
    			$data[$key]['tags'] = $total_pcs;
    		}
    	}



    	return $data;
    }
}
