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
    public static function getStorehours($data) {
        $store_hours = [];
        $today = date('Y-m-d');
        
        if(isset($data)) {
            foreach ($data as $key => $value) {
                if(isset($data[$key]['store_hours'])) {
                    $sh = json_decode($data[$key]['store_hours'],true);

                    if(isset($sh)){
                        foreach ($sh as $skey => $svalue) {
                            if($svalue['status'] == 2) {
                                $start_time = date('H:i',strtotime($today.' '.$svalue['open_hour'].':'.$svalue['open_minutes'].' '.$svalue['open_ampm']));
                                $end_time = date('H:i',strtotime($today.' '.$svalue['closed_hour'].':'.$svalue['closed_minutes'].' '.$svalue['closed_ampm']));
                                $store_hours[$skey] = [
                                    'start'=>$start_time,
                                    'end'=>$end_time,
                                    'dow'=>$skey
                                ];
                            } 
                        }
                    }

                    break;
                }
            }
        }
        $final_hours = [];
        $idx = -1;
        if(isset($store_hours)){
            // check to see what times are the same
            foreach ($store_hours as $key => $value) {
                $idx++;
                $final_hours['start'] = '00:00';
                $final_hours['end'] = '23:59';
                $final_hours['dow'][$idx] = $key;
            }
        }



        return json_encode($final_hours);
    }  
    public static function getTurnaround($data) {
        $turnaround = '';
        $dow = date('N',strtotime(date('Y-m-d H:i:s')));
        
        if(isset($data)) {
            foreach ($data as $key => $value) {
                if(isset($data[$key]['store_hours'])) {
                    $store_hours = json_decode($data[$key]['store_hours'],true);
                    $today_string = strtotime(date('n/d/Y').' '.$store_hours[$dow]['due_hour'].':'.$store_hours[$dow]['due_minutes'].' '.$store_hours[$dow]['due_ampm']);
                    $turnaround_days_string = ($store_hours[$dow]['turnaround']==1) ? ' day' : ' days';
                    $turnaround= $store_hours[$dow]['turnaround'];
                    break;
                }
            }
        }

        return $turnaround;
    }
    public static function getTurnaroundDate($data) {
        $turnaround = '';
        $dow = date('N',strtotime(date('Y-m-d H:i:s')));
        
        if(isset($data)) {
            foreach ($data as $key => $value) {
                if(isset($data[$key]['store_hours'])) {
                    $store_hours = json_decode($data[$key]['store_hours'],true);
                    $today_string = strtotime(date('n/d/Y').' '.$store_hours[$dow]['due_hour'].':'.$store_hours[$dow]['due_minutes'].' '.$store_hours[$dow]['due_ampm']);
                    $turnaround_days_string = ($store_hours[$dow]['turnaround']==1) ? ' day' : ' days';
                    $turnaround_string = '+'.$store_hours[$dow]['turnaround'].$turnaround_days_string;

                    $turnaround = date('Y-m-d H:i:s', strtotime($turnaround_string,$today_string));
                }
            }
        }

        return $turnaround;
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
