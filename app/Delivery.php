<?php

namespace App;
use App\Delivery;
use App\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Delivery extends Model
{
    use SoftDeletes;

    static public function makeCalendar($data) {

    	$disabled_weekdates = '* * * 0,1,2,3,4,5,6,';

    	$calendar = ['0'=>['status'=>false],
    				 '1'=>['status'=>false],
    				 '2'=>['status'=>false],
    				 '3'=>['status'=>false],
    				 '4'=>['status'=>false],
    				 '5'=>['status'=>false],
    				 '6'=>['status'=>false]];
    	if (count($data) > 0) {
    		foreach ($data as $key => $value) {
    			$deliveries = Delivery::find($key);

    			$dow = $deliveries['day'];
    			switch($dow) {
    				case 'Monday':
    					$disabled_weekdates = str_replace('1,', '',$disabled_weekdates);
    					$calendar[1] = ['status'=>true,'blackout_dates'=>json_decode($deliveries['blackout'])];
    				break;

    				case 'Tuesday':
    					$disabled_weekdates = str_replace('2,', '',$disabled_weekdates);
    					$calendar[2] = ['status'=>true,'blackout_dates'=>json_decode($deliveries['blackout'])];
    				break;

    				case 'Wednesday':
    					$disabled_weekdates = str_replace('3,', '',$disabled_weekdates);
						$calendar[3] = ['status'=>true,'blackout_dates'=>json_decode($deliveries['blackout'])];
    				break;

    				case 'Thursday':
    					$disabled_weekdates = str_replace('4,', '',$disabled_weekdates);
    					$calendar[4] = ['status'=>true,'blackout_dates'=>json_decode($deliveries['blackout'])];
    				break;

    				case 'Friday':
    					$disabled_weekdates = str_replace('5,', '',$disabled_weekdates);
    					$calendar[5] = ['status'=>true,'blackout_dates'=>json_decode($deliveries['blackout'])];
    				break;

    				case 'Saturday':
    					$disabled_weekdates = str_replace('6,', '',$disabled_weekdates);
    					$calendar[6] = ['status'=>true,'blackout_dates'=>json_decode($deliveries['blackout'])];
    				break;

    				case 'Sunday':
    					$disabled_weekdates = str_replace('0,', '',$disabled_weekdates);
    					$calendar[0] = ['status'=>true,'blackout_dates'=>json_decode($deliveries['blackout'])];
    				break;

    				default:
    					$disabled_weekdates = str_replace($dow.',', '',$disabled_weekdates);
    					
    					$calendar[$dow] = ['status'=>true,'blackout_dates'=>json_decode($deliveries['blackout'])];
    				break;
    			}



    			rtrim($disabled_weekdates, ",");

    		}
    	}

    	return $disabled_weekdates;
    }

    static public function setTimeOptions($date,$zips) {
    	$options = [''=>'<option>select time</option>'];

    	$dow = date('w',strtotime($date));
    	if (count($zips) > 0) {
    		foreach ($zips as $key => $value) {
    			$delivery_id = $value;
    			$deliveries = Delivery::find($delivery_id);
    			$day_of_week = $deliveries->day;
    			$start_time = $deliveries->start_time;
    			$end_time = $deliveries->end_time;
    			switch($day_of_week){
    				case 'Sunday':
    					$ddow = 0;
    				break;
    				case 'Monday':
    					$ddow = 1;
    				break;
    				case 'Tuesday':
    					$ddow = 2;
    				break;
    				case 'Wednesday':
    					$ddow = 3;
    				break;
    				case 'Thursday':
    					$ddow = 4;
    				break;
    				case 'Friday':
    					$ddow = 5;
    				break;
    				case 'Saturday':
    					$ddow = 6;
    				break;
    				default:
    					$ddow = $day_of_week;
    				break;
    			}
    			if ($dow == $ddow) {
    				$options[$delivery_id] = '<option value="'.$delivery_id.'">'.$start_time.' - '.$end_time.'</option>';
    			}
    		}
    	}

    	return $options;
    }

    static public function setTimeArray($date,$zips) {
    	$options = [''=>'select time'];

    	$dow = date('w',strtotime($date));
    	if (count($zips) > 0) {
    		foreach ($zips as $key => $value) {
    			$delivery_id = $value;
    			$deliveries = Delivery::find($delivery_id);
    			$day_of_week = $deliveries->day;
    			$start_time = $deliveries->start_time;
    			$end_time = $deliveries->end_time;
    			switch($day_of_week){
    				case 'Sunday':
    					$ddow = 0;
    				break;
    				case 'Monday':
    					$ddow = 1;
    				break;
    				case 'Tuesday':
    					$ddow = 2;
    				break;
    				case 'Wednesday':
    					$ddow = 3;
    				break;
    				case 'Thursday':
    					$ddow = 4;
    				break;
    				case 'Friday':
    					$ddow = 5;
    				break;
    				case 'Saturday':
    					$ddow = 6;
    				break;
    				default:
    					$ddow = $day_of_week;
    				break;
    			}
    			if ($dow == $ddow) {
    				$options[$delivery_id] = $start_time.' - '.$end_time;
    			}
    		}
    	}

    	return $options;    	
    }

    static public function setBreadcrumbs($data) {
    	$set = ['pickup_address' =>'Not Set',
    			'pickup_date' => 'Not Set',
    			'pickup_time' => 'Not Set',
    			'dropoff_address' => 'Not Set',
    			'dropoff_time' => 'Not Set',
    			'dropoff_date'=> 'Not Set'];

   		if(isset($data)) {
   			$pickup_address_id = (isset($data['pickup_address'])) ? $data['pickup_address'] : false;
   			$dropoff_address_id = (isset($data['dropoff_address'])) ? $data['dropoff_address'] : $data['pickup_address'];
   			$pickup_delivery_id = (isset($data['pickup_delivery_id'])) ? $data['pickup_delivery_id'] : false;
   			$dropoff_delivery_id = (isset($data['dropoff_delivery_id'])) ? $data['dropoff_delivery_id'] : false;
   			$pickup_address = Address::find($pickup_address_id);
   			$dropoff_address = Address::find($dropoff_address_id);
   			$pickup_delivery = Delivery::find($pickup_delivery_id);
   			$dropoff_delivery = Delivery::find($dropoff_delivery_id);

   			if (isset($data['pickup_date'])) {
   				$set['pickup_date'] = date('D m/d/Y',strtotime($data['pickup_date']));
   			}
			if (isset($data['dropoff_date'])) {
   				$set['dropoff_date'] = date('D m/d/Y',strtotime($data['dropoff_date']));
   			}
   			if(count($pickup_address)) {
   				$set['pickup_address'] = $pickup_address['name'].' - '.$pickup_address['zipcode'];
   			} 

   			if (count($dropoff_address)) {
   				$set['dropoff_address'] = $dropoff_address['name'].' - '.$dropoff_address['zipcode'];	
   			}

   			if (count($pickup_delivery)) {
   				$set['pickup_time'] = $pickup_delivery['start_time'].' - '.$pickup_delivery['end_time'];
   			}

   			if (count($dropoff_delivery)) {
   				$set['dropoff_time'] = $dropoff_delivery['start_time'].' - '.$dropoff_delivery['end_time'];
   			}



   		} 

   		return $set;
    }
}
