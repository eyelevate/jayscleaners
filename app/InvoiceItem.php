<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\InvoiceItem;
use App\Invoice;
use App\InventoryItem;
use App\Inventory;
class InvoiceItem extends Model
{
    use SoftDeletes;
    public static function prepareEdit($data){
    	if(isset($data)){
    		foreach ($data as $key => $value) {
    			if(isset($data[$key]['item_id'])){
    				$data[$key]['inventory_id'] = InventoryItem::find($data[$key]['item_id'])->inventory_id;
    				$data[$key]['item_name'] = InventoryItem::find($data[$key]['item_id'])->name;
    			}
    		}
    	}

    	return $data;
    }

    public static function prepareGroup($data){
    	$group = [];
    	if(isset($data)){
    		$idx = 0;

    		foreach ($data as $key => $value) {
    			$idx++;
    			$group[$data[$key]['item_id']] = $value->item_name;
    		}
    	}

    	return $group;
    }
}
