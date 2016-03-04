<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    public static function prepareTax($data){
    	if(isset($data->rate)){
    		$data->rate = ($data->rate * 100).'%';
    	}

    	return $data;
    }
}
