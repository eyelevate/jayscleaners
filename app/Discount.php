<?php

namespace App;
use Auth;
use App\Inventory;
use App\InventoryItem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Discount extends Model
{
    use SoftDeletes;

    public static function prepareView($data) {
    	if (count($data) > 0) {
    		foreach ($data as $key => $value) {
    			if (isset($data[$key]['inventory_id'])) {
    				$inventories = Inventory::find($data[$key]['inventory_id']);
    				$data[$key]['group'] = $inventories->name;
    			} else {
    				$data[$key]['group'] = NULL;
    			}

    			if (isset($data[$key]['inventory_item_id'])) {
    				$inventory_items = InventoryItem::find($data[$key]['inventory_item_id']);
    				if ($inventory_items) {
    					$data[$key]['item'] = $inventory_items->name;
    				} else {
    					$data[$key]['item'] = NULL;
    				}
    				
    			} else {
    				$data[$key]['item'] = NULL;
    			}

    			if (isset($data[$key]['status'])) {
    				switch($data[$key]['status']) {
    					case 1:
    					$status = 'Active';
    					break;

    					case 2:
    					$status = 'Not Active';
    					break;
    				}
    				$data[$key]['status_label'] = $status;
    			}
    		}
    	}

    	return $data;
    }

    public static function prepareApproved() {
        $discounts = Discount::where('company_id',Auth::user()->company_id)
            ->where('status',1)
            ->get();
        $today = strtotime(date('Y-m-d H:i:s'));
        if (count($discounts) > 0) {
            foreach ($discounts as $key => $value) {
                if (isset($discounts[$key]['start_date'])) {
                    
                    $start_date = strtotime($value->start_date);
                    if ($today < $start_date) {
                        unset($discounts[$key]);
                    }

                }

                if (isset($discounts[$key]['end_date'])) {
                    $end_date = strtotime($value->end_date);
                    if ($today > $end_date) {
                        unset($discounts[$key]);
                    }
                }
                $items = [];
                if (isset($discounts[$key]['inventory_id'])) {
                    $inventory_items = InventoryItem::where('inventory_id',$value->inventory_id)->get();
                    
                    if (count($inventory_items) > 0) {
                        foreach ($inventory_items as $inventory_item) {
                            array_push($items, $inventory_item->id);
                        }
                    } 
                    
                } 
                $discounts[$key]['inventory_items'] = $items;
            }
        }

        return $discounts;
    }

    public static function prepareApprovedSelect() {
        $discounts = Discount::where('company_id',Auth::user()->company_id)
            ->where('status',1)
            ->get();
        $today = strtotime(date('Y-m-d H:i:s'));
        if (count($discounts) > 0) {
            foreach ($discounts as $key => $value) {
                if (isset($discounts[$key]['start_date'])) {
                    
                    $start_date = strtotime($value->start_date);
                    if ($today < $start_date) {
                        unset($discounts[$key]);
                    }

                }

                if (isset($discounts[$key]['end_date'])) {
                    $end_date = strtotime($value->end_date);
                    if ($today > $end_date) {
                        unset($discounts[$key]);
                    }
                }
            }
        }

        $select = ['0'=>'No Discount'];
        if (count($discounts) > 0) {
            foreach ($discounts as $discount) {
                $select[$discount->id] = $discount->name;
            }
        }

        return $select;
    }
}
