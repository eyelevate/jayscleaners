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

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id', 'id');
    }

    public function inventoryItem_trashed() {
        return $this->hasMany('App\InventoryItem')->withTrashed();
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }

    public function inventory_trashed() {
        return $this->hasMany('App\Inventory')->withTrashed();
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

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
                        if ($value->color) {
                            $color[$value->item_id][$value->color] += 1;
                        }
                        
                    else {
                        if ($value->color) {
                            $color[$value->item_id][$value->color] = 1;
                        }
                        
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
                $item_id = $value->item_id;
                $inventory_item = InventoryItem::find($item_id);
                $item_name = $inventory_item->name;
                if (isset($group[$data[$key]['item_id']])) {
                    $qty += 1;
                    $memo .= (isset($value->memo)) ? ', '.$value->memo : '';
                    $subtotal += $value->pretax;
                } else {
                    $qty = 1;
                    $memo = (isset($value->memo)) ? $value->memo : '';
                    $subtotal = $value->pretax;
                }

                $group[$data[$key]['item_id']] = [
                    'invoice_id'=>$value->invoice_id,
                    'name' => $item_name,
                    'colors'=>(isset($color_string[$value->item_id])) ? $color_string[$value->item_id] : '',
                    'memos'=>($memo) ? $memo : '',
                    'qty'=>$qty,
                    'subtotal' => $subtotal
                ];
    		}
    	}


    	return $group;
    }

    public static function prepareLocation() {
        return [
            1 => 'Accepted',
            2 => 'In Dry Clean',
            3 => 'In Wash',
            4 => 'In Press - Shirts',
            5 => 'In Press - Pants',
            6 => 'In Press - Blouse',
            7 => 'In Press - Touch Up',
            8 => 'In Spotting',
            9 => 'In Assembly',
            10 => 'In Bagging',
            11 => 'Racked',
            12 => 'In Delivery',
            13 => 'Complete'
        ];
    }


}
