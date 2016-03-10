<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    public static function prepareTax($data){
    	if(isset($data->rate)){
    		$data->rate = ($data->rate * 100).'%';
    	} else {
    		$data = [];
    		$data['rate'] = 'N/A';
    	}

    	return $data;
    }

    public static function prepareHistory($data){

    	return $data;
    }
}
