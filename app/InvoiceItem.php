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
    		$idx = -1;
            $color = [];
            $memo = '';
            $color_string = [];

            foreach($data as $key => $value) {
                if (isset($value->color)) {
                    if (isset($color[$value->item_id][$value->color]))
                        $color[$value->item_id][$value->color] += 1;
                    else {
                        $color[$value->item_id][$value->color] = 1;
                    }                    
                }

            } 

            if (count($color)) {
                foreach ($color as $key => $value) {
                    $color_string[$key] = '';
                    foreach ($value as $ckey => $cvalue) {
                        $color_string[$key] .= $cvalue.'-'.$ckey.', ';
                    }
                }
            }


    		foreach ($data as $key => $value) {
    			$idx++;
                $name = $value->item_name;
                if (isset($group[$data[$key]['item_id']])) {
                    $qty += 1;
                    $memo .= (isset($value->memo)) ? ', '.$value->memo : '';
                } else {
                    $qty = 1;
                    $memo = (isset($value->memo)) ? $value->memo : '';
                }

                $group[$data[$key]['item_id']] = [
                    'name' => $name,
                    'colors'=>(isset($color_string[$value->item_id])) ? $color_string[$value->item_id] : '',
                    'memos'=>($memo) ? $memo : '',
                    'qty'=>$qty
                ];
    		}
    	}


    	return $group;
    }
}
