<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Job;
class Admin extends Model
{
    //
    public static function prepareAdmin($data) {

    	foreach ($data as $key => $value) {
    		if(isset($data[$key]['company_id'])){
	    		switch($data[$key]['company_id']){
	    			case '1': //Montlake
	    				$data[$key]['location'] = 'Montlake';
	    			break;

	    			case '2': //Roosevelt
	    				$data[$key]['location'] = 'Roosevelt';
	    			break;

	    			default: // N/A
	    				$data[$key]['location'] = 'N/A';
	    			break;	
	    		}
    		}
	    	if(isset($data[$key]['created_at'])){
	    		$data[$key]['created_on'] = date('n/d/Y g:ia',strtotime($data[$key]['created_at']));
	    	}
    	}

    	return $data;
    }
}
