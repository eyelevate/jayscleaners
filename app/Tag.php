<?php

namespace App;

use App\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tag extends Model
{
    use SoftDeletes;

    public static function prepareInvoiceTags($data) {

    	
    	if (count($data) > 0){
    		foreach ($data as $key => $value) {
    			$invoice_id = $value['invoice_id'];
    			$customer_id = $value['customer_id'];
    			$customers = User::find($customer_id);
    			$first_name = $customers->first_name;
    			$last_name = $customers->last_name;
    			$data[$key]['first_name'] = $first_name;
    			$data[$key]['last_name'] = $last_name;
    			$tags = Tag::where('invoice_id',$invoice_id)
    				->where('status',1)
    				->get();
    			if (count($tags)) {
    				foreach ($tags as $tag) {
    					$rfid = $tag->rfid;
    					$location_id = $tag->location_id;
    					$data[$key]['rfid'] = $rfid;
    					$data[$key]['location_id'] = $location_id;

    				}
    			} else {
    				$data[$key]['rfid'] = '';
    				$data[$key]['location_id'] = 0;
    			}
    		}
    	}


    	return $data;
    }

    public static function prepareInvoiceItemTags($data) {

    	
    	if (count($data) > 0){
    		foreach ($data as $key => $value) {
    			$invoice_id = $value['invoice_id'];
    			

    			$invoice_item_id = $value['id'];
    			$tags = Tag::where('invoice_item_id',$invoice_item_id)
    				->where('status',1)
    				->get();
    			if (count($tags)) {
    				foreach ($tags as $tag) {
    					$rfid = $tag->rfid;
    					$location_id = $tag->location_id;
    					$data[$key]['rfid'] = $rfid;
    					$data[$key]['location_id'] = $location_id;
    					

    				}
    			} else {
    				$data[$key]['rfid'] = '';
    				$data[$key]['location_id'] = 0;

    			}
    		}
    	}


    	return $data;
    }
}
