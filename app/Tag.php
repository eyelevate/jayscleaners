<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tag extends Model
{
    use SoftDeletes;

    public static function prepareTag($data, $invoice_id) {
    	$tag_update = [0=>[],1=>[]];
    	$tags = Tag::where('invoice_id',$invoice_id)
    		->where('status',1)
    		->get();
    	if (count($tags) > 0) {
    		foreach ($tags as $tag) {
    			$tag_update['invoice'] = $tag;
    		}
    	}
    	
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
    				$data[$key]['rfid'] = null;
    				$data[$key]['location_id'] = null;
    			}
    		}
    	}

    	$tag_update['items'] = $data;

    	return $tag_update;
    }
}
