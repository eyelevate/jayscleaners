<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Credit extends Model
{
    use SoftDeletes;

    public static function prepareReason(){
    	return [
    		''=>'Select Reason',
    		'Customer Dissatisfaction'=>'Customer Dissatisfaction',
    		'Gift Certificate'=>'Gift Certificate',
    		'Human Error' => 'Human Error',
    		'Other'=>'Other'
    	];
    }
}
