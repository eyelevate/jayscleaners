<?php

namespace App;
use App\Zipcode;
use App\ZipcodeList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GuzzleHttp\Client;
use Geocoder;

class Address extends Model
{
	use SoftDeletes;
	
    static public function addressSelect($addresses) {
    	$addr = [''=>'Select Address'];
    	if ($addresses) {
    		foreach ($addresses as $address) {
    			$addr[$address->id] = $address['name'].' - '.$address->zipcode;
    		}
    	}


    	return $addr;
    }

    static public function prepareForView($data) {
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $zipcodes = ZipcodeList::where('zipcode',$value->zipcode)->get();
                if (count($zipcodes) > 0) {
                    $data[$key]['zipcode_status'] = true;
                } else {
                    $data[$key]['zipcode_status'] = false;
                }
            }
        }

        return $data;
    }
}
