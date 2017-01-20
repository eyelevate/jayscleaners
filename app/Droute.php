<?php

namespace App;

use App\User;
use App\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Droute extends Model
{
    use SoftDeletes;
    public static function prepareRoutes($data) {
    	$dr = [];
    	if (count($data) > 0) {
    		foreach ($data as $droute) {
    			$schedules = Schedule::prepareSchedule(Schedule::where('id',$droute->schedule_id)->get());
    			$dr[$droute->employee_id]['driver'] = User::find($droute->employee_id);
    			$dr[$droute->employee_id]['schedule'][$droute->ordered] =$schedules;
    			
    		}
    	}

    	return $dr;
    }
}
