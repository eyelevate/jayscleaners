<?php

namespace App;
use App\Job;
use App\User;
use App\Customer;
use App\Custid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Custid extends Model
{
	use SoftDeletes;

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public static function createOriginalMark($users){
    	$mark = '';

    	$first_mark = strtoupper(substr($users->last_name, 0,1));
    	$second_mark = $users->id;
		switch($users->starch) {
			case '1':
				$last_mark = 'N';
			break;

			case '2':
				$last_mark = 'L';
			break;

			case '3':
				$last_mark = 'M';
			break;

			case '4':
				$last_mark = 'H';
			break;

			default:
				$last_mark = 'N';
			break;
    	}

    	$mark = $first_mark.$second_mark.$last_mark;

    	return $mark;
    }
}
