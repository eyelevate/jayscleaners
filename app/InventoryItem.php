<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InventoryItem extends Model
{
	use SoftDeletes;
	public static function prepareItems($inventories){
		$items = [];
		if(count($inventories) > 0) {
			foreach ($inventories as $inv) {
				$items[$inv->id] = [
					'id'	=> $inv->id,
					'name' => $inv->name,
					'items' => InventoryItem::prepareForView(InventoryItem::where('inventory_id',$inv->id)->orderBy('ordered','asc')->get())
				];
			}
		}

		return $items;
	}

	public static function prepareForView($data) {
		if (isset($data)) {
			foreach ($data as $key => $value) {
				if (isset($data[$key]['image'])) {
					$list = explode('/', $data[$key]['image']);
					unset($list[0]);
					unset($list[1]);
					unset($list[2]);
					$image_name = (isset($list[3])) ? $list[3] : '';
					if (isset($list[3])) {
						$data[$key]['image'] = '/imgs/inventories/'.$image_name; 
					} else {
						$data[$key]['image'] = 'http://placehold.it/350x150'; 
					}
					
					
				}
			}
		}

		return $data;
	}

	public static function preparePricingList() {
		$list = [
			'Dry Clean' => [
				'Pants' => '$6.50',
				'Blouse' => '$6.50',
				'Shirt (Dry Clean)' => '$6.50',
				'2pc Suit' => '$14.95',
				'Tux Suit' => '$15.95',
				'Sweater' => '$6.50',
				'Tie' => '$4.75',
				'Jacket' => '$8.95',
				'Vest' => '$5.75',
				'Shorts' => '$5.95',
				'Jumper' => '$15.95'
			],
			'Laundry' => [
				'Shirts (Hanger)' => '$1.70',
				'Shirts (Folded)' => '$3.40'
			]
		];

		return $list;
	}
}
