<?php

namespace App;
use App\Address;
use App\Delivery;
use App\Company;
use App\Invoice;
use App\User;
use Auth;
use App\Job;
use GuzzleHttp\Client;
use Geocoder;

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
    				$pickup_address_1 = ($suite) ? $street.' #'.$suite : $street;
    				$pickup_address_2 = ucFirst($city).', '.strtoupper($state).' '.$zipcode;
    				$pickup_delivery_id = $value->pickup_delivery_id;
    				$dropoff_delivery_id = $value->dropoff_delivery_id;
    				$pickup_deliveries = Delivery::find($pickup_delivery_id);
    				$pickup_time = $pickup_deliveries->start_time.' - '.$pickup_deliveries->end_time;
    				$dropoff_deliveries = Delivery::find($dropoff_delivery_id);
    				$dropoff_time = $dropoff_deliveries->start_time.' - '.$dropoff_deliveries->end_time;
                    $customers = User::find($value->customer_id);
    				$schedules[$key]['id'] = $value->id;
                    $schedules[$key]['customer_id'] = $value->customer_id;
                    $schedules[$key]['email'] = $customers->email;
                    $schedules[$key]['first_name'] = ucFirst($customers->first_name);
                    $schedules[$key]['last_name'] = ucFirst($customers->last_name);
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
                    $latlong = Schedule::getLatLong($pickup_address_1.' '.$pickup_address_2);

                    $schedules[$key]['gmap_address'] = (isset($latlong['latitude'])) ? 'http://maps.apple.com/?q='.$latlong['latitude'].','.$latlong['longitude'] : null;
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
                    $schedules[$key]['invoices'] = [];
    				// $schedules[$key]['status'] = 12;
    				switch($schedules[$key]['status']) {
    					case 1:
							$schedules[$key]['status_message'] = 'Delivery Scheduled';
                            $schedules[$key]['status_html'] = 'label-info';
    					break;

    					case 2:
    						$schedules[$key]['status_message'] = 'En-route Pickup';
                            $schedules[$key]['status_html'] = 'label-info';
    					break;

    					case 3:
    						$schedules[$key]['status_message'] = 'Picked Up';
                            $schedules[$key]['status_html'] = 'label-info';
    					break;

    					case 4:
    						$schedules[$key]['status_message'] = 'Processing';
                            $schedules[$key]['status_html'] = 'label-info';

                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->get();
                            $prepared_invoices = Invoice::prepareInvoice($value->company_id, $invoices);


                            $schedules[$key]['invoices'] = $prepared_invoices;
    					break;

    					case 5:
    						$schedules[$key]['status_message'] = 'Invoice Paid';
                            $schedules[$key]['status_html'] = 'label-info';
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->get();
                            $prepared_invoices = Invoice::prepareInvoice($value->company_id, $invoices);

                            Job::dump($prepared_invoices);
                            $schedules[$key]['invoices'] = $prepared_invoices;
    					break;

    					case 6:
    						$schedules[$key]['status_message'] = 'Cancelled by customer';
                            $schedules[$key]['status_html'] = 'label-danger';
    					break;

    					case 7:
    						$schedules[$key]['status_message'] = 'Delayed - Processing not complete';
                            $schedules[$key]['status_html'] = 'label-warning';
    					break;

    					case 8:
    						$schedules[$key]['status_message'] = 'Delayed - Customer unavailable for pickup';
                            $schedules[$key]['status_html'] = 'label-warning';
    					break;

    					case 9:
    						$schedules[$key]['status_message'] = 'Delayed - Customer unavailable for dropoff';
                            $schedules[$key]['status_html'] = 'label-warning';
    					break;

    					case 10:
    						$schedules[$key]['status_message'] = 'Delayed - Card on file processing error';
                            $schedules[$key]['status_html'] = 'label-warning';
    					break;

    					case 11:
    						$schedules[$key]['status_message'] = 'En-route Dropoff - invoice paid';
                            $schedules[$key]['status_html'] = 'label-info';
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->get();
                            $schedules[$key]['invoices'] = $invoices;
    					break;
     					case 12:
     						$schedules[$key]['status_message'] = 'Dropped Off. Thank You!';
                            $schedules[$key]['status_html'] = 'label-success';
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->get();
                            $schedules[$key]['invoices'] = $invoices;
    					break;


    				}

    			}
    		}
    	}

    	return $schedules;
    }

    static public function prepareTrip($schedules, $options) {
        $trip = [
            'visits'=>[],
            'fleet'=>[]
        ];
        if (count($schedules) > 0) {
            foreach ($schedules as $schedule) {
                $address_id = $schedule->pickup_address;
                $addresses = Address::find($address_id);
                $address_name = $addresses->name;
                $address_street = $addresses->street;
                $address_suite = $addresses->suite;
                $address_city = $addresses->city;
                $address_state = $addresses->state;
                $address_zipcode = $addresses->zipcode;
                $address_string = $address_street.' '.$address_city.', '.$address_state.' '.$address_zipcode;
                $latlong = Schedule::getLatLong($address_string);

                $delivery_pickup = Delivery::find($schedule->pickup_delivery_id);
                $pickup_start_time = Schedule::convertTimeToMilitary($delivery_pickup->start_time);
                $pickup_end_time = Schedule::convertTimeToMilitary($delivery_pickup->end_time);
                $delivery_dropoff = Delivery::find($schedule->dropoff_delivery_id);
                $dropoff_start_time = Schedule::convertTimeToMilitary($delivery_dropoff->start_time);
                $dropoff_end_time = Schedule::convertTimeToMilitary($delivery_dropoff->end_time);

                // push to the visits array
                $trip['visits'][$schedule->id] = [
                    'location' => [
                        'name' => $address_street,
                        'lat'=>($latlong['status']) ? $latlong['latitude'] : false,
                        'lng'=>($latlong['status']) ? $latlong['longitude'] : false
                    ],
                    'start' => ($schedule->status == 5) ? $dropoff_start_time : $pickup_start_time,
                    'end' => ($schedule->status == 5) ? $dropoff_end_time : $pickup_end_time,
                    'duration' => 20,
                ];


            }

        }

        $user_id = Auth::user()->id;
        $trip['fleet'] = Schedule::getDrivers();

        // options
        if($options){
            $trip['options'] = $options;
        }


        return $trip;
    }

    static private function getDrivers() {
        $montlake = [
            'status'=>true,
            'latitude'=>47.6400404, 
            'longitude'=>122.301674
        ];
        $roosevelt = [
            'status'=>true,
            'latitude'=>47.6756443, 
            'longitude'=>122.3198168
        ];

        $drivers = [
            'vehicle_1'=>[
                'start_location'=>[
                    'name'=>'Work',
                    'lat'=>47.6400404,
                    'lng'=>-122.301674
                ],
                'end_location'=>[
                    'name'=>'Work',
                    'lat'=>47.6400404,
                    'lng'=>-122.301674
                ],
                'shift_start'=>'8:00',
                'shift_end'=>'17:30'
            ]
        ];

        return $drivers;
    }

    static private function getLatLong($address) {
        $latlong = [];

        try {
            $geocode = Geocoder::geocode($address);
            // The GoogleMapsProvider will return a result
            $latlong = [
                'status'=>true,
                'latitude'=>$geocode->getLatitude(),
                'longitude'=>$geocode->getLongitude()
            ];
        } catch (\Exception $e) {
            // No exception will be thrown here
            $latlong = [
                'status'=>false,
                'error'=>$e
            ];
        }

        return $latlong;        
    }

    static private function convertTimeToMilitary($time) {
        $time = str_replace(['am','pm'], [' am',' pm'], $time);
        $time_converted = date('H:i',strtotime($time));
        return $time_converted;
    }

    static public function prepareRouteForView($route, $data) {
        $joined = [];
        $estimate_travel_time = $route->total_travel_time;
        $set_route = $route->solution;


        if(count($set_route) > 0) {
            foreach ($set_route as $driver_id => $route_list) {
                if (count($route_list) > 0 ) {
                    foreach ($route_list as $rl) {
                        $check_schedule_id = $rl->location_id;
                        foreach ($data as $key => $value) {
                            $schedule_id = $value['id'];
                            if ($schedule_id == $check_schedule_id) {

                                array_push($joined,$value);
                            }
                        }
                    }
                }

            }
        } 

        return $joined;
    }
}
