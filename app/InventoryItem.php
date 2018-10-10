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
						$data[$key]['image'] = '/img/inventory/'.$image_name; 
					} else {
						$data[$key]['image'] = ''; 
					}
					
					
				}
			}
		}

		return $data;
	}

	public static function getItemName($item_id) {
		$items = InventoryItem::find($item_id);
		if ($items){
			return $items->name;
		} else {
			return NULL;
		}
		return $items->name;
	}

	public static function preparePricingList() {
		$company_id = 1;
		$pants_id = 7;
		$blouse_id = 1;
		$shirt_dc_id = 5;
		$suit_id = 2;
		$tux_shirt_id = 148;
		$sweater_id = 15;
		$tie_id = 10;
		$jacket_id = 11;
		$vest_id = 9;
		$shorts_id = 139;
		$jumper_id = 194;
		$shirts_hanger_id = 17;
		$shirts_folded_id = 18;

		$pant = money_format('$%n',InventoryItem::find($pants_id)->price);
		$blouse = money_format('$%n',InventoryItem::find($blouse_id)->price);
		$shirts_dc = money_format('$%n',InventoryItem::find($shirt_dc_id)->price);
		$suit = money_format('$%n',InventoryItem::find($suit_id)->price);
		$tux_shirt = money_format('$%n',InventoryItem::find($tux_shirt_id)->price);
		$sweater = money_format('$%n',InventoryItem::find($sweater_id)->price);
		$tie = money_format('$%n',InventoryItem::find($tie_id)->price);
		$jacket = money_format('$%n',InventoryItem::find($jacket_id)->price);
		$vest = money_format('$%n',InventoryItem::find($vest_id)->price);
		$shorts = money_format('$%n',InventoryItem::find($shorts_id)->price);
		$jumper = money_format('$%n',InventoryItem::find($jumper_id)->price);
		$shirts_hanger = money_format('$%n',InventoryItem::find($shirts_hanger_id)->price);
		$shirts_folded = money_format('$%n',InventoryItem::find($shirts_folded_id)->price);

		$list = [
			'Dry Clean' => [
				'Pants' => $pant,
				'Blouse' => $blouse,
				'Shirt (Dry Clean)' => $shirts_dc,
				'2pc Suit' => $suit,
				'Tux Shirt' => $tux_shirt,
				'Sweater' => $sweater,
				'Tie' => $tie,
				'Jacket' => $jacket,
				'Vest' => $vest,
				'Shorts' => $shorts,
				'Jumper' => $jumper
			],
			'Laundry' => [
				'Shirts (Hanger)' => $shirts_hanger,
				'Shirts (Folded)' => $shirts_folded
			]
		];

		return $list;
	}

	public static function prepareSelect($data) {
        $select = [];

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                if (isset($data[$key]['id'])) {
                    $select[$data[$key]['id']] = $data[$key]['name'];
                }
            }
        }

        return $select;
    }
}
