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
    	return [
    		''=>'select zipcode',
			'98101' => '98101',
			'98102' => '98102',
			'98103' => '98103',
 			'98104' => '98104',
			'98105' => '98105',
			'98107' => '98107',
			'98109' => '98109',
			'98112' => '98112',
			'98115' => '98115',
			'98117' => '98117',
			'98119' => '98119',
			'98121' => '98121',
			'98122' => '98122',
			'98195' => '98195',
			'98199' => '98199'
    	];
    }
}
