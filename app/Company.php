<?php

namespace App;
use App\Job;
use App\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Company extends Model
{
    use SoftDeletes;
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
                                $open_time = date('H:i',strtotime($today.' '.$svalue['open_hour'].':'.$svalue['open_minutes'].' '.$svalue['open_ampm']));
                                $end_time = date('H:i',strtotime($today.' '.$svalue['closed_hour'].':'.$svalue['closed_minutes'].' '.$svalue['closed_ampm']));
                                $store_hours[$skey] = [
                                    'open'=>$open_time,
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
                $final_hours['open'] = '00:00';
                $final_hours['end'] = '23:59';
                $final_hours['dow'][$idx] = $key;
            }
        }



        return json_encode($final_hours);
    }  
    public static function getTurnaround($data) {
        $turnaround = '';
        $dow = date('w',strtotime(date('Y-m-d H:i:s')));
        
        if(isset($data)) {
            foreach ($data as $key => $value) {
                if(isset($data[$key]['store_hours'])) {
                    $store_hours = json_decode($data[$key]['store_hours'],true);
                    if ($store_hours[$dow]['status'] != 1){
                        $today_string = strtotime(date('n/d/Y').' '.$store_hours[$dow]['due_hour'].':'.$store_hours[$dow]['due_minutes'].' '.$store_hours[$dow]['due_ampm']);
                        $turnaround_days_string = ($store_hours[$dow]['turnaround']==1) ? ' day' : ' days';
                        $turnaround= $store_hours[$dow]['turnaround'];
                        break;
                    } else {
                        $turnaround = date('Y-m-d H:i:s',strtotime('NOW + 2 days'));
                    }
                }
            }
        }

        return $turnaround;
    }
    public static function getTurnaroundDate($data) {
        $turnaround = '';
        $dow = date('w',strtotime(date('Y-m-d H:i:s')));
        
        if(isset($data)) {
            foreach ($data as $key => $value) {
                if(isset($data[$key]['store_hours'])) {
                    $store_hours = json_decode($data[$key]['store_hours'],true);
                    if ($store_hours[$dow]['status'] != 1){
                        $today_string = strtotime(date('n/d/Y').' '.$store_hours[$dow]['due_hour'].':'.$store_hours[$dow]['due_minutes'].' '.$store_hours[$dow]['due_ampm']);
                        $turnaround_days_string = ($store_hours[$dow]['turnaround']==1) ? ' day' : ' days';
                        $turnaround_string = '+'.$store_hours[$dow]['turnaround'].$turnaround_days_string;
                        $turnaround = date('Y-m-d H:i:s', strtotime($turnaround_string,$today_string));                    
                    } else {
                        $turnaround = date('Y-m-d H:i:s',strtotime('NOW + 2 days'));
                    }
                    

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

    public static function getFillColor($company_id){
        switch ($company_id) {
            case 1:
                $fill_color = "rgb(210, 214, 222)";
                break;
            
            default:
                $fill_color = "rgba(60,141,188,0.9)";
                break;
        }

        return $fill_color;
    }

    public static function getStrokeColor($company_id){
        switch ($company_id) {
            case 1:
                $stroke_color = "rgb(210, 214, 222)";
                break;
            
            default:
                $stroke_color = "rgba(60,141,188,0.8)";
                break;
        }

        return $stroke_color;
    }

    public static function getPointColor($company_id){
        switch ($company_id) {
            case 1:
                $point_color = "rgb(210, 214, 222)";
                break;
            
            default:
                $point_color = "#3b8bba";
                break;
        }

        return $point_color;
    }

    public static function getPointStrokeColor($company_id){
        switch ($company_id) {
            case 1:
                $point_stroke_color = "#c1c7d1";
                break;
            
            default:
                $point_stroke_color = "rgba(60,141,188,1)";
                break;
        }

        return $point_stroke_color;
    }

    public static function getPointHighlightFill($company_id){
        switch ($company_id) {
            case 1:
                $color = "#fff";
                break;
            
            default:
                $color = "#fff";
                break;
        }

        return $color;
    }

    public static function getPointHighlightStroke($company_id){
        switch ($company_id) {
            case 1:
                $color = "rgb(220,220,220)";
                break;
            
            default:
                $color = "rgba(60,141,188,1)";
                break;
        }

        return $color;
    }

    public static function prepareSelect($data) {
        $companies = [''=>'select company'];
        if (count($data) >0) {
            foreach ($data as $company) {
                $companies[$company->id] = $company->name;
            }
        }

        return $companies;
    }

    public static function prepareForView($data) {
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                if (isset($data[$key]['phone'])) {
                    $data[$key]['phone'] = Job::formatPhoneString($data[$key]['phone']);
                }

                // map button
                $address = $value->street.' '.$value->city.', '.$value->state.' '.$value->zipcode;
                $latlong = Schedule::getLatLong($address);
                $data[$key]['map'] = 'http://maps.apple.com/?q='.$latlong['latitude'].','.$latlong['longitude'];
                
                if (isset($data[$key]['store_hours'])) {
                    $view_hours = [];
                    $store_hours = json_decode($data[$key]['store_hours'], true);
                    if (count($store_hours) > 0) {
                        foreach ($store_hours as $shkey => $shvalue) {

                            $dow = Company::prepareDayOfWeek($shkey);
                            

                            $view_hours[$dow[$shkey]] = ($shvalue['status'] == 2) ? $shvalue['open_hour'].':'.$shvalue['open_minutes'].' '.$shvalue['open_ampm'].' - '.$shvalue['closed_hour'].':'.$shvalue['closed_minutes'].' '.$shvalue['closed_ampm'] : 'CLOSED';
                        }
                    }

                    $data[$key]['store_hours'] = $view_hours;
                    $now = strtotime(date('Y-m-d H:i:s'));
                    if ($store_hours[date('w')]['status'] > 1) {
                        $open_string = date('n/d/Y ').$store_hours[date('w')]['open_hour'].':'.$store_hours[date('w')]['open_minutes'].' '.$store_hours[date('w')]['open_ampm'];
                        $open_time = strtotime(date('Y-m-d H:i:s',strtotime($open_string)));
                        $closed_string = date('n/d/Y ').$store_hours[date('w')]['closed_hour'].':'.$store_hours[date('w')]['closed_minutes'].' '.$store_hours[date('w')]['closed_ampm'];
                        $closed_time = strtotime(date('Y-m-d H:i:s',strtotime($closed_string)));
                    } else {
                        $open_time = 0;
                        $closed_time = 0;
                    }


                    $data[$key]['open_status'] = ($now >= $open_time && $now <= $closed_time) ? true : false;
                }

            }

        }

        return $data;
    }

}
