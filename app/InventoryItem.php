<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
	public static function prepareItems($inventories){
		$items = [];
		if(count($inventories) > 0) {
			foreach ($inventories as $inv) {
				$items[$inv->id] = [
					'id'	=> $inv->id,
					'name' => $inv->name,
					'items' => InventoryItem::where('inventory_id',$inv->id)->orderBy('ordered','asc')->get()
				];
			}
		}

		return $items;
	}
}
