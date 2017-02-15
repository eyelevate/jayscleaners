<?php

namespace App;
use App\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zipcode extends Model
{
    use SoftDeletes;

    static public function prepareForSetup($data) {
    	$select = [''=>'select a zipcode'];
    	if (count($data) > 0) {
    		foreach ($data as $zipcode) {
    			$company_id = $zipcode->company_id;
    			$companies = Company::find($company_id);

    			$select[$zipcode->id] = $zipcode->zipcode.' - '.$companies->name;
    		}
    	}
    	return $select;
    }

    static public function getAllZipcodes() {
        $zipcodes = Zipcode::all();
        $zips = [];
        if (count($zipcodes) > 0) {
            foreach ($zipcodes as $z) {
                array_push($z->zipcode,$z->zipcode);
            }
        }
    	return $zips;
    }
}
