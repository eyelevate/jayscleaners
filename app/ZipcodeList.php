<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ZipcodeList extends Model
{
    use SoftDeletes;


    static public function getAllZipcodes() {
        $zipcodes = ZipcodeList::all();
        $zips = [];
        if (count($zipcodes) > 0) {
            foreach ($zipcodes as $z) {
                $zips[$z->zipcode] = $z->zipcode;
            }
        }
    	return $zips;
    }
}
