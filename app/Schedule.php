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
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class Schedule extends Model
{
    use SoftDeletes;

    static public function prepareSchedule($data) {
    	$schedules = [];
    	if (count($data) > 0) {
    		foreach ($data as $key => $value) {
    			if(isset($data[$key]['pickup_address'])) {
                    $company_id = $value->company_id;
                    $companies = Company::find($company_id);
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
                    if (isset($pickup_delivery_id)) {
                        $pickup_deliveries = Delivery::find($pickup_delivery_id);
                        $pickup_time = $pickup_deliveries->start_time.' - '.$pickup_deliveries->end_time;
                    } else {
                        $pickup_time = 'No time scheduled';
                    }

                    if (isset($dropoff_delivery_id)) {
                        $dropoff_deliveries = Delivery::find($dropoff_delivery_id);
                        $dropoff_time = $dropoff_deliveries->start_time.' - '.$dropoff_deliveries->end_time;
                    } else {
                        $dropoff_time = 'No time scheduled';
                    }
                    $customers = User::find($value->customer_id);

    				$schedules[$key]['id'] = $value->id;
                    $schedules[$key]['customer_id'] = $value->customer_id;
                    $schedules[$key]['email'] = $customers->email;
                
                    $schedules[$key]['first_name'] = ucFirst($customers->first_name);
                    $schedules[$key]['last_name'] = ucFirst($customers->last_name);
    				$schedules[$key]['company_id'] = $value->company_id;
                    $schedules[$key]['company_name'] = $companies->name;
    				$schedules[$key]['pickup_address'] = $value->pickup_address;
    				$schedules[$key]['pickup_delivery_id'] = $value->pickup_delivery_id;
    				$schedules[$key]['dropoff_delivery_id'] = $value->dropoff_delivery_id;
    				$schedules[$key]['address_name'] = $addresses->name;
                    $schedules[$key]['street'] = $street;
                    $schedules[$key]['city'] = $city;
                    $schedules[$key]['state'] = $state;
                    $schedules[$key]['zipcode'] = $zipcode;
    				$schedules[$key]['pickup_address_1'] = $pickup_address_1;
    				$schedules[$key]['pickup_address_2'] = $pickup_address_2;
    				$schedules[$key]['contact_name'] = $addresses->concierge_name;
    				$schedules[$key]['contact_number'] = $addresses->concierge_number;
                    if (strtotime($value->pickup_date) > 0) {
                        $schedules[$key]['pickup_date'] = date('D m/d/Y',strtotime($value->pickup_date));
                    } else {
                        $schedules[$key]['pickup_date'] = 'No Pickup Date Scheduled';
                    }
    				$schedules[$key]['pickup_time'] = $pickup_time;
                    if (strtotime($value->dropoff_date) > 0) {
                        $schedules[$key]['dropoff_date'] = date('D m/d/Y',strtotime($value->dropoff_date));
                    } else {
                        $schedules[$key]['dropoff_date'] = 'No Dropoff Date Scheduled';
                    }
    				
    				$schedules[$key]['dropoff_time'] = $dropoff_time;
    				$schedules[$key]['special_instructions'] = $value->special_instructions;
    				$schedules[$key]['created_at'] = date('D m/d/Y g:i a',strtotime($value->created_at));
                    $latlong = Schedule::getLatLong($street.' '.$pickup_address_2);
                    $schedules[$key]['latitude'] = $latlong['latitude'];
                    $schedules[$key]['longitude'] = $latlong['longitude'];

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
                    // $delay_list = [
                    //     '' => 'Select Delay Reason',
                    //     '7' => 'Delayed - Processing not complete',
                    //     '8' => 'Delayed - Customer unavailable for pickup',
                    //     '9' => 'Delayed - Customer unavailable for dropoff',
                    //     '10'=> 'Delayed - Card on file processing error'
                    // ];
    				// $schedules[$key]['status'] = 12;
    				switch($schedules[$key]['status']) {
    					case 1:
							$schedules[$key]['status_message'] = 'Delivery Scheduled';
                            $schedules[$key]['status_html'] = 'label-info';
                            $schedules[$key]['delay_list'] = [
                                '' => 'Select Delay Reason',
                                '8' => 'Delayed - Customer unavailable for pickup',
                            ];
    					break;

    					case 2:
    						$schedules[$key]['status_message'] = 'En-route Pickup';
                            $schedules[$key]['status_html'] = 'label-info';
                            $schedules[$key]['delay_list'] = [
                                '' => 'Select Delay Reason',
                                '8' => 'Delayed - Customer unavailable for pickup',
                            ];
                            $schedules[$key]['email_subject'] = 'En-route to Pickup';
                            $schedules[$key]['email_greetings'] = 'Greetings '.$schedules[$key]['first_name'].' '.$schedules[$key]['last_name'].', ';
                            $schedules[$key]['email_body'] = 'Your delivery request has been accepted and we are on the way!';
                            $schedules[$key]['email_body'] .= ' Please have you or your contact person(s) be available between the hours of '.$pickup_time.' today.';
                            $schedules[$key]['email_body'] .= ' If you or your contact cannot be available during these hours, please contact us at '.Job::formatPhoneString($companies->phone).' click on the "Reschedule" link below to cancel or make a change to your delivery date.';
    					break;

    					case 3:
    						$schedules[$key]['status_message'] = 'Picked Up';
                            $schedules[$key]['status_html'] = 'label-info';
                            $schedules[$key]['delay_list'] = [
                                '' => 'Select Delay Reason',
                                '7' => 'Delayed - Processing not complete',
                            ];
    					break;

    					case 4:
    						$schedules[$key]['status_message'] = 'Processing';
                            $schedules[$key]['status_html'] = 'label-info';
                            $invoices_selected = Invoice::where('schedule_id',$schedules[$key]['id']);
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->where('schedule_id',NULL)
                                                 ->union($invoices_selected)
                                                 ->get();
                            $prepared_invoices = Invoice::prepareInvoice($value->company_id, $invoices);
                            $invs = Invoice::where('schedule_id',$schedules[$key]['id'])->get();
                            $totals = Invoice::prepareTotals($invs);
                            $schedules[$key]['invoices'] = $prepared_invoices;
                            $schedules[$key]['invoice_totals'] = $totals;
                            $schedules[$key]['delay_list'] = [
                                '' => 'Select Delay Reason',
                                '7' => 'Delayed - Processing not complete',
                                '10'=> 'Delayed - Card on file processing error'
                            ];
    					break;

    					case 5:
    						$schedules[$key]['status_message'] = 'Invoice Paid';
                            $schedules[$key]['status_html'] = 'label-info';
                            $completed_invoices = Invoice::where('customer_id',$value->customer_id)
                                                           ->where('status',5)
                                                           ->where('schedule_id',$schedules[$key]['id']);
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->where('schedule_id',NULL)
                                                 ->union($completed_invoices)
                                                 ->get();
                            $prepared_invoices = Invoice::prepareInvoice($value->company_id, $invoices);

                            $invs = Invoice::where('schedule_id',$schedules[$key]['id'])->get();
                            $totals = Invoice::prepareTotals($invs);
                            $schedules[$key]['invoices'] = $prepared_invoices;
                            $schedules[$key]['invoice_totals'] = $totals;
                            $schedules[$key]['delay_list'] = [
                                '' => 'Select Delay Reason',
                                '9' => 'Delayed - Customer unavailable for dropoff'
                            ];
    					break;

    					case 6:
    						$schedules[$key]['status_message'] = 'Cancelled by customer';
                            $schedules[$key]['status_html'] = 'label-danger';
    					break;

    					case 7:
    						$schedules[$key]['status_message'] = 'Delayed - Processing not complete';
                            $schedules[$key]['status_html'] = 'label-warning';
                            $invoices_selected = Invoice::where('schedule_id',$schedules[$key]['id']);
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->where('schedule_id',NULL)
                                                 ->union($invoices_selected)
                                                 ->get();
                            $prepared_invoices = Invoice::prepareInvoice($value->company_id, $invoices);
                            $invs = Invoice::where('schedule_id',$schedules[$key]['id'])->get();
                            $totals = Invoice::prepareTotals($invs);
                            $schedules[$key]['invoices'] = $prepared_invoices;
                            $schedules[$key]['invoice_totals'] = $totals;
    					break;

    					case 8:
    						$schedules[$key]['status_message'] = 'Delayed - Customer unavailable for pickup';
                            $schedules[$key]['status_html'] = 'label-warning';
                            $schedules[$key]['email_subject'] = 'Delivery delayed due contact not available during pickup!';
                            $schedules[$key]['email_greetings'] = 'Greetings '.$schedules[$key]['first_name'].' '.$schedules[$key]['last_name'].', ';
                            $schedules[$key]['email_body'] = 'Your delivery has been delayed due your contact not being available during pickup.';
                            $schedules[$key]['email_body'] .= ' In order to finish this pickup we will need to reschedule to another available delivery date.';
                            $schedules[$key]['email_body'] .= ' Please click on the button below reschedule. Thank you for your understanding.';
                            $schedules[$key]['email_button'] = '<a style="color: #ffffff; text-align:center;text-decoration: none;" href="'.route('delivery_update',$schedules[$key]['id']).'">Reschedule Pickup</a>';
    					break;

    					case 9:
    						$schedules[$key]['status_message'] = 'Delayed - Customer unavailable for dropoff';
                            $schedules[$key]['status_html'] = 'label-warning';
                            $completed_invoices = Invoice::where('customer_id',$value->customer_id)
                                               ->where('status',5)
                                               ->where('schedule_id',$schedules[$key]['id']);
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->where('schedule_id',NULL)
                                                 ->union($completed_invoices)
                                                 ->get();
                            $prepared_invoices = Invoice::prepareInvoice($value->company_id, $invoices);

                            $invs = Invoice::where('schedule_id',$schedules[$key]['id'])->get();
                            $totals = Invoice::prepareTotals($invs);
                            $schedules[$key]['invoices'] = $prepared_invoices;
                            $schedules[$key]['invoice_totals'] = $totals;
                            $schedules[$key]['email_subject'] = 'Delivery delayed due contact not available during dropoff!';
                            $schedules[$key]['email_greetings'] = 'Greetings '.$schedules[$key]['first_name'].' '.$schedules[$key]['last_name'].', ';
                            $schedules[$key]['email_body'] = 'Your delivery has been delayed due your contact not being available during dropoff.';
                            $schedules[$key]['email_body'] .= ' In order to finish processing this delivery and have it delivered to you we will need to reschedule the dropoff to another available delivery date.';
                            $schedules[$key]['email_body'] .= ' Please click on the button below reschedule. Thank you for your understanding.';
                            $schedules[$key]['email_button'] = '<a style="color: #ffffff; text-align:center;text-decoration: none;" href="'.route('delivery_update',$schedules[$key]['id']).'">Reschedule Dropoff</a>';
    					break;

    					case 10:
    						$schedules[$key]['status_message'] = 'Delayed - Card on file processing error';
                            $schedules[$key]['status_html'] = 'label-warning';
                                        $invoices_selected = Invoice::where('schedule_id',$schedules[$key]['id']);
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->where('schedule_id',NULL)
                                                 ->union($invoices_selected)
                                                 ->get();
                            $prepared_invoices = Invoice::prepareInvoice($value->company_id, $invoices);
                            $invs = Invoice::where('schedule_id',$schedules[$key]['id'])->get();
                            $totals = Invoice::prepareTotals($invs);
                            $schedules[$key]['invoices'] = $prepared_invoices;
                            $schedules[$key]['invoice_totals'] = $totals;


                            // gather card information
                            $cards = Card::find($value->card_id);
                            $profile_id = $cards->profile_id;
                            $payment_id = $cards->payment_id;

                            $card_info = Card::getCardInfo($company_id, $profile_id, $payment_id);

                            $card_last_four = ($card_info['status']) ? $card_info['last_four'] : 'XXXX';

                            $schedules[$key]['email_subject'] = 'Delivery delayed due to credit card on file error!';
                            $schedules[$key]['email_greetings'] = 'Greetings '.$schedules[$key]['first_name'].' '.$schedules[$key]['last_name'].', ';
                            $schedules[$key]['email_body'] = 'Your delivery has been delayed due to a processing error on your card ending in "'.$card_last_four.'"';
                            $schedules[$key]['email_body'] .= ' In order to finish processing this delivery and have it delivered to you we will need to fix the issue with the card and process it successfully.';
                            $schedules[$key]['email_body'] .= ' Please click on the button below to make the appropriate adjustments.';
                            $schedules[$key]['email_button'] = '<a style="color: #ffffff; text-align:center;text-decoration: none;" href="'.route('cards_edit',$value->card_id).'">Update Card Info</a>';
                            $schedules[$key]['email_body_2'] = 'Once you have updated your card information. Click on the "Reschedule" button below to reschedule your drop off.';
                            $schedules[$key]['email_button_2'] = '<a style="color: #ffffff; text-align:center;text-decoration: none;" href="'.route('delivery_update',$schedules[$key]['id']).'">Reschedule</a>';
    					break;

    					case 11:
    						$schedules[$key]['status_message'] = 'En-route Dropoff - invoice paid';
                            $schedules[$key]['status_html'] = 'label-info';
                            $completed_invoices = Invoice::where('customer_id',$value->customer_id)
                                                           ->where('status',5)
                                                           ->where('schedule_id',$schedules[$key]['id']);
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->where('schedule_id',NULL)
                                                 ->union($completed_invoices)
                                                 ->get();
                            $prepared_invoices = Invoice::prepareInvoice($value->company_id, $invoices);

                            $invs = Invoice::where('schedule_id',$schedules[$key]['id'])->get();
                            $totals = Invoice::prepareTotals($invs);
                            $schedules[$key]['invoices'] = $prepared_invoices;
                            $schedules[$key]['invoice_totals'] = $totals;
                            $schedules[$key]['delay_list'] = [
                                '' => 'Select Delay Reason',
                                '9' => 'Delayed - Customer unavailable for dropoff'
                            ];
                            $schedules[$key]['email_subject'] = 'En-route to Dropoff!';
                            $schedules[$key]['email_greetings'] = 'Greetings '.$schedules[$key]['first_name'].' '.$schedules[$key]['last_name'].', ';
                            $schedules[$key]['email_body'] = 'Your delivery has been processed, paid and is ready to be dropped off at your address.';
                            $schedules[$key]['email_body'] .= ' Please have you or your contact person(s) be available between the hours of '.$dropoff_time.' today.';
                            $schedules[$key]['email_body'] .= ' If you or your contact cannot be available during these hours, please contact us at '.Job::formatPhoneString($companies->phone).' OR click on the "Reschedule" link below to cancel or make a change to your delivery date.';
                            $schedules[$key]['email_button'] = '<a style="color: #ffffff; text-align:center;text-decoration: none;" href="'.route('delivery_update',$schedules[$key]['id']).'">Reschedule</a>';
                        break;
     					case 12:
     						$schedules[$key]['status_message'] = 'Dropped Off. Thank You!';
                            $schedules[$key]['status_html'] = 'label-success';
                            $completed_invoices = Invoice::where('customer_id',$value->customer_id)
                                                           ->where('status',5)
                                                           ->where('schedule_id',$schedules[$key]['id']);
                            $invoices = Invoice::where('customer_id',$value->customer_id)
                                                 ->where('status','<',5)
                                                 ->where('schedule_id',NULL)
                                                 ->union($completed_invoices)
                                                 ->get();
                            $prepared_invoices = Invoice::prepareInvoice($value->company_id, $invoices);

                            $invs = Invoice::where('schedule_id',$schedules[$key]['id'])->get();
                            $totals = Invoice::prepareTotals($invs);
                            $schedules[$key]['invoices'] = $prepared_invoices;
                            $schedules[$key]['invoice_totals'] = $totals;
                            $schedules[$key]['email_subject'] = 'Delivery Successful!';
                            $schedules[$key]['email_greetings'] = 'Greetings '.$schedules[$key]['first_name'].' '.$schedules[$key]['last_name'].', ';
                            $schedules[$key]['email_body'] = 'Your delivery was successfully';
                            $schedules[$key]['email_body'] .= ' Congratulations! Your delivery was successfully completed.';
                            $schedules[$key]['email_body'] .= ' If you would like to schedule your next delivery you may click on the link below to reschedule. Thank you for your business!';
                            $schedules[$key]['email_button'] = '<a style="color: #ffffff; text-align:center;text-decoration: none;" href="'.route('delivery_start').'">New Delivery</a>';
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

        $company_id = null;
        if (count($schedules) > 0) {
            foreach ($schedules as $schedule) {
                Job::dump($schedule->id);
                $company_id = $schedule->company_id;
                $address_id = $schedule->pickup_address;
                $addresses = Address::find($address_id);

                if ($addresses) {
                    $address_name = $addresses->name;
                    $address_street = $addresses->street;
                    $address_suite = $addresses->suite;
                    $address_city = $addresses->city;
                    $address_state = $addresses->state;
                    $address_zipcode = $addresses->zipcode;
                    $address_string = $address_street.' '.$address_city.', '.$address_state.' '.$address_zipcode;
                    $latlong = Schedule::getLatLong($address_string);
                } else {
                    $address_name = NULL;
                    $address_street = NULL;
                    $address_suite = NULL;
                    $address_city = NULL;
                    $address_state = NULL;
                    $address_zipcode = NULL;
                    $address_string = NULL;
                    $latlong = [];
                }
                
                if ($schedule->pickup_delivery_id) {
                    $delivery_pickup = Delivery::find($schedule->pickup_delivery_id);
                    $pickup_start_time = Schedule::convertTimeToMilitary($delivery_pickup->start_time);
                    $pickup_end_time = Schedule::convertTimeToMilitary($delivery_pickup->end_time);
                } else {
                    $delivery_pickup = NULL;
                    $pickup_start_time = NULL;
                    $pickup_end_time = NULL;
                }

                if ($schule->dropoff_delivery_id) {
                    $delivery_dropoff = Delivery::find($schedule->dropoff_delivery_id);
                    $dropoff_start_time = Schedule::convertTimeToMilitary($delivery_dropoff->start_time);
                    $dropoff_end_time = Schedule::convertTimeToMilitary($delivery_dropoff->end_time);
                } else {
                    $delivery_dropoff = NULL;
                    $dropoff_start_time = NULL;
                    $dropoff_end_time = NULL;
                }



                $point = [
                            'name' => $address_street,
                            'lat'=>($latlong['status']) ? $latlong['latitude'] : false,
                            'lng'=>($latlong['status']) ? $latlong['longitude'] : false
                         ];
                // push to the visits array
                $trip['visits'][$address_id] = [
                    'location' => $point,
                    'start' =>($schedule->status == 5) ? $dropoff_start_time: $pickup_start_time,
                    'end' => ($schedule->status == 5) ? $dropoff_end_time : $pickup_end_time,
                    'duration' => 10,
                ];


            }

        }

        $user_id = Auth::user()->id;
        $trip['fleet'] = Schedule::getDrivers($company_id);        

        // options
        if($options){
            $trip['options'] = $options;
        }


        return $trip;
    }

    static public function prepareRouteSetup($schedules) {
        $setup = [];

        if (count($schedules) > 0) {
            foreach ($schedules as $schedule) {
                $company_id = $schedule->company_id;
                $customer_id = $schedule->customer_id;
                $users = User::find($customer_id);
                $first_name = $users->first_name;
                $last_name = $users->last_name;
                $address_id = $schedule->pickup_address;
                $addresses = Address::find($address_id);
                $address_name = $addresses->name;
                $address_street = $addresses->street;
                $address_suite = $addresses->suite;
                $address_city = $addresses->city;
                $address_state = $addresses->state;
                $address_zipcode = $addresses->zipcode;
                $tag = ucFirst($first_name).' '.ucFirst($last_name).' - '.$address_street;
                $address_string = $address_street.' '.$address_city.', '.$address_state.' '.$address_zipcode;
                $latlong = Schedule::getLatLong($address_string);
                array_push($setup, [$latlong['latitude'],$latlong['longitude'],$tag]);                
            }
        }

        return $setup;


    }

    static private function getDrivers($company_id = null) {
        $montlake = [
            'name'=>'Montlake',
            'lat'=>47.6400404, 
            'lng'=>-122.301674
        ];
        $roosevelt = [
            'name'=>'Roosevelt',
            'lat'=>47.6756443, 
            'lng'=>-122.3198168
        ];

        $end_point = ($company_id == 2) ? $montlake : $roosevelt;
        $start_point =($company_id == 2) ? $montlake : $roosevelt;

        $drivers = [
            'vehicle_1'=>[
                'start_location'=>$montlake,
                'end_location'=>$roosevelt,
                'shift_start'=>'7:00',
                'shift_end'=>'19:00'
            ]
        ];

        return $drivers;
    }

    static public function getLatLong($address) {
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
                'error'=>'Could not locate address. Please enter a valid address and try again.'
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

    static public function makePayment($company_id, $profile_id, $payment_id, $total) {

        $re = [];

        $companies = Company::find($company_id);
        $payment_api_login = $companies->payment_api_login;
        $payment_api_password = $companies->payment_gateway_id;
        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($payment_api_login);
        $merchantAuthentication->setTransactionKey($payment_api_password);
        $refId = 'ref' . time();

        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($profile_id);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($payment_id);
        $profileToCharge->setPaymentProfile($paymentProfile);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType( "authCaptureTransaction"); 
        $transactionRequestType->setAmount($total);
        $transactionRequestType->setProfile($profileToCharge);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId( $refId);
        $request->setTransactionRequest( $transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if ($response != null) {
            if($response->getMessages()->getResultCode() == 'Ok') {
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $re = [
                        'status'=>true,
                        'auth_code'=>$tresponse->getAuthCode(),
                        'trans_id'=>$tresponse->getTransId()
                    ];
                    return $re;
                    // echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                    // echo  "Charge Customer Profile APPROVED  :" . "\n";
                    // echo " Charge Customer Profile AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                    // echo " Charge Customer Profile TRANS ID  : " . $tresponse->getTransId() . "\n";
                    // echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n"; 
                    // echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
                } else {
                    // echo "Transaction Failed \n";
                    if($tresponse->getErrors() != null) {
                        // echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        // echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";  
                        $re = [
                            'status'=>false,
                            'error_code'=>$tresponse->getErrors()[0]->getErrorCode(),
                            'error_message'=>$tresponse->getErrors()[0]->getErrorText()
                        ];          
                    }
                }
            } else {
                $tresponse = $response->getTransactionResponse();

                if($tresponse != null && $tresponse->getErrors() != null) {
                    // echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    // echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";  
                    $re = [
                        'status'=>false,
                        'error_code'=>$tresponse->getErrors()[0]->getErrorCode(),
                        'error_message'=>$tresponse->getErrors()[0]->getErrorText()
                    ];                       
                } else {
                    // echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    // echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                    $re = [
                        'status'=>false,
                        'error_code'=>$response->getMessages()->getMessage()[0]->getCode(),
                        'error_message'=>$response->getMessages()->getMessage()[0]->getText()
                    ];
                }
            }
        } else {
            $re = [
                'status'=>false,
                'error_code'=>false,
                'error_message'=>"No response returned"
            ];
          
        }

        return $re;
    }
}
