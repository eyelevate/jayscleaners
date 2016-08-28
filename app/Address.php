<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
