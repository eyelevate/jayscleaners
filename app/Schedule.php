<?php

namespace App;
use App\Address;
use App\Delivery;
use App\Company;
use App\Job;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Schedule extends Model
{
    use SoftDeletes;

    static public function prepareSchedule($data) {
    	$schedules = [];
    	if (count($data) > 0) {
    		foreach ($data as $key => $value) {
    			if(isset($data[$key]['pickup_address'])) {
    				$pickup_address_id = $data[$key]['pickup_address'];
    				$addresses = Address::find($pickup_address_id);
    				$street = $addresses->street;
    				$suite = $addresses->suite;
    				$city = $addresses->city;
    				$state = $addresses->state;
    				$zipcode = $addresses->zipcode;
    				$contact_name = $addresses->concierge_name;
    				$contact_number = $addresses->concierge_number;
    				$pickup_address_1 = (isset($suite)) ? $street.' #'.$suite : $street;
    				$pickup_address_2 = ucFirst($city).', '.strtoupper($state).' '.$zipcode;
    				$pickup_delivery_id = $value->pickup_delivery_id;
    				$dropoff_delivery_id = $value->dropoff_delivery_id;
    				$pickup_deliveries = Delivery::find($pickup_delivery_id);
    				$pickup_time = $pickup_deliveries->start_time.' - '.$pickup_deliveries->end_time;
    				$dropoff_deliveries = Delivery::find($dropoff_delivery_id);
    				$dropoff_time = $dropoff_deliveries->start_time.' - '.$dropoff_deliveries->end_time;
    				$schedules[$key]['id'] = $value->id;
    				$schedules[$key]['company_id'] = $value->company_id;
    				$schedules[$key]['pickup_address'] = $value->pickup_address;
    				$schedules[$key]['pickup_delivery_id'] = $value->pickup_delivery_id;
    				$schedules[$key]['dropoff_delivery_id'] = $value->dropoff_delivery_id;
    				$schedules[$key]['address_name'] = $addresses->name;
    				$schedules[$key]['pickup_address_1'] = $pickup_address_1;
    				$schedules[$key]['pickup_address_2'] = $pickup_address_2;
    				$schedules[$key]['contact_name'] = $addresses->concierge_name;
    				$schedules[$key]['contact_number'] = $addresses->concierge_number;
    				$schedules[$key]['pickup_date'] = date('D m/d/Y',strtotime($value->pickup_date));
    				$schedules[$key]['pickup_time'] = $pickup_time;
    				$schedules[$key]['dropoff_date'] = date('D m/d/Y',strtotime($value->dropoff_date));
    				$schedules[$key]['dropoff_time'] = $dropoff_time;
    				$schedules[$key]['special_instructions'] = $value->special_instructions;
    				$schedules[$key]['created_at'] = date('D m/d/Y g:i a',strtotime($value->created_at));
    				/**
    				* Status Index
    				* 1. Delivery Scheduled
    				* 2. En-route Pickup
    				* 3. Picked Up
    				* 4. Processing 
    				* 5. En-route Dropoff
    				* 6. Cancelled by user
    				* 7. Delayed - Processing not complete
    				* 8. Delayed - Customer not available for pickup
    				* 9. Delayed - Customer not available for dropoff
    				* 10. Delayed - Customer card on file error
    				* 11. Delayed - Delivery could not be completed
    				* 12. Dropped Off
    				**/
    				$schedules[$key]['status'] = $value->status;
    				// $schedules[$key]['status'] = 12;
    				switch($schedules[$key]['status']) {
    					case 1:
							$schedules[$key]['status_message'] = 'Delivery Scheduled';
    					break;

    					case 2:
    						$schedules[$key]['status_message'] = 'En-route Pickup';
    					break;

    					case 3:
    						$schedules[$key]['status_message'] = 'Picked Up';
    					break;

    					case 4:
    						$schedules[$key]['status_message'] = 'Processing';
    					break;

    					case 5:
    						$schedules[$key]['status_message'] = 'En-route Dropoff';
    					break;

    					case 6:
    						$schedules[$key]['status_message'] = 'Cancelled by customer';
    					break;

    					case 7:
    						$schedules[$key]['status_message'] = 'Delayed - Processing not complete';
    					break;

    					case 8:
    						$schedules[$key]['status_message'] = 'Delayed - Customer unavailable for pickup';
    					break;

    					case 9:
    						$schedules[$key]['status_message'] = 'Delayed - Customer unavailable for dropoff';
    					break;

    					case 10:
    						$schedules[$key]['status_message'] = 'Delayed - Card on file processing error';
    					break;

    					case 11:
    						$schedules[$key]['status_message'] = 'Delayed - Delivery could not be completed';
    					break;
     					case 12:
     						$schedules[$key]['status_message'] = 'Dropped Off. Thank You!';
    					break;


    				}

    			}
    		}
    	}


    	return $schedules;
    }
}
