<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    public static function prepareCompany($data){
        if(isset($data['store_hours'])){
            $data['store_hours'] = json_decode($data['store_hours'],true);
        }

        return $data;
    }
    public static function getCompany(){

    	return [1=>'Montlake',2=>'Roosevelt'];
    }

    public static function getStarch(){

    	return [1=>'None',2=>'Light',3=>'Medium',4=>'Heavy'];
    }

    public static function getShirt(){
    	return [1=>'Hanger',2=>'Box/Folded'];
    }

    public static function getDelivery(){
    	return [false=>'No', true=>'Yes'];
    }

    public static function getAccount(){
    	return [false=>'No', true=>'Yes'];

    }

    public static function prepareStoreHours() {
        $hours = [];
        for ($i=1; $i<=12 ; $i++) { 
            $hours[$i] = $i;
        }

        return $hours;
    }

    public static function prepareStoreHourStatus(){
        return ['1'=>'Closed','2'=>'Open'];
    }

    public static function prepareMinutes(){
        $minutes = [];
        for ($i=0; $i<60 ; $i++) { 
            $minutes[str_pad($i, 2, '0', STR_PAD_LEFT)] = ':'.str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        return $minutes;
    }

    public static function prepareAmpm(){
        return ['am'=>'AM','pm'=>'PM'];
    }
    public static function prepareTurnaround(){
        $turnaround = [];
        for ($i=0; $i <= 14 ; $i++) { 
            $turnaround[$i] = ($i == 0) ? 'Same Day' : $i.' days';
        }
        return $turnaround;
    }
    public static function prepareDayOfWeek(){
        return [
            0=>'Sunday',
            1=>'Monday',
            2=>'Tuesday',
            3=>'Wednesday',
            4=>'Thursday',
            5=>'Friday',
            6=>'Saturday'
        ];
    }
}
