<?php

namespace App\Http\Controllers;

use Input;
use Validator;
use Redirect;
use Hash;
use Route;
use Response;
use Auth;
use Mail;
use URL;
use Session;
use Laracasts\Flash\Flash;
use View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\Address;
use App\User;
use App\Card;
use App\Company;
use App\Customer;
use App\Custid;
use App\Delivery;
use App\Layout;
use App\Schedule;
use App\Zipcode;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class DeliveriesController extends Controller
{
    public function __construct() {
    	$this->layout = 'layouts.frontend_basic';
    }

    public function getIndex(Request $request) {
        
        if ($request->session()->has('schedule')) {
            $request->session()->forget('schedule');
            
        }
        $schedules = Schedule::prepareSchedule(Schedule::where('customer_id',Auth::user()->id)
            ->where('status','<',12)
            ->where('status','!=',6)
            ->orderBy('id','desc')->get());

        return view('deliveries.index')
        ->with('layout',$this->layout)
        ->with('schedules',$schedules);
    }

    public function getPickupForm(Request $request) {

        $request->session()->put('form_previous','delivery_pickup');
        $check_address = $request->session()->has('check_address') ? $request->session()->pull('check_address') : false;
    	$auth = (Auth::check()) ? Auth::user() : false;
        $addresses = Address::addressSelect(Address::where('user_id',Auth::user()->id)->orderby('primary_address','desc')->get());
        
        $primary_address = Address::where('user_id',Auth::user()->id)->where('primary_address',true)->get();
        $primary_address_id = false;
        $primary_zipcode = false;
        $primary_address_zipcode = false;
        if (count($primary_address) > 0) {
            foreach ($primary_address as $pa) {
                $primary_address_id = $pa['id'];
                $primary_address_zipcode = $pa['zipcode'];
            }
        }

        $selected_date = false;
        $selected_delivery_id = false;
        $pickup_data = $request->session()->get('schedule');
        $check_session = (isset($pickup_data['pickup_address'])) ? true : false;
        if ($check_session) {
            
            $primary_address_id = (isset($pickup_data['pickup_address'])) ? $pickup_data['pickup_address'] : false;
            $primary_address = Address::find($primary_address_id);
            $primary_address_zipcode = (count($primary_address) > 0) ? $primary_address->zipcode : false;
            $selected_date = $pickup_data['pickup_date'];
            $selected_delivery_id = $pickup_data['pickup_delivery_id'];

        }

        $zipcodes = Zipcode::where('zipcode',$primary_address_zipcode)->get();
    
        $zip_list = [];
        if (count($zipcodes) > 0) {
            foreach ($zipcodes as $key => $zip) {
                $delivery_id = $zip['delivery_id'];
                $zip_list[$key] = $delivery_id;

            }
        }
        $time_options = Delivery::setTimeArray($selected_date,$zip_list);


        $zipcode_status = (count($zipcodes) > 0) ? true : false;
        $calendar_dates = [];
        if ($zipcodes) {
            foreach ($zipcodes as $zipcode) {
                $calendar_dates[$zipcode['delivery_id']] = $zipcode['zipcode'];
            }
        }

        $calendar_setup = Delivery::makeCalendar($calendar_dates);

        if (!$zipcode_status) {
            Flash::error('Your primary address is not set or zipcode is not valid. Please select a new address. ');
        }

        $breadcrumb_data = Delivery::setBreadCrumbs($pickup_data);

        $dropoff_method = [''=>'Select Dropoff Method',
                           '1'=>'Delivered to the address chosen below.',
                           '2'=>'I wish to pick up my order myself.'];
    	return view('deliveries.pickup')
        ->with('layout',$this->layout)
        ->with('auth',$auth)
        ->with('addresses',$addresses)
        ->with('primary_address_id',$primary_address_id ? $primary_address_id : null)
        ->with('dropoff_method',$dropoff_method ? $dropoff_method : [])
        ->with('zipcode_status',$zipcode_status ? $zipcode_status : null)
        ->with('calendar_disabled',$calendar_setup ? $calendar_setup : null)
        ->with('selected_date',$selected_date ? $selected_date : null)
        ->with('selected_delivery_id',$selected_delivery_id ? $selected_delivery_id : null)
        ->with('zip_list',$zip_list ? $zip_list : [])
        ->with('time_options',$time_options ? $time_options : [])
        ->with('breadcrumb_data',$breadcrumb_data ? $breadcrumb_data : []);
    } 

    public function getDropoffForm(Request $request) {
        $request->session()->put('form_previous','delivery_dropoff');
        $pickup_data = $request->session()->get('schedule');
        $check_session = (isset($pickup_data['pickup_address'])) ? true : false;
        if($check_session) {

            $auth = (Auth::check()) ? Auth::user() : false;
            $addresses = Address::addressSelect(Address::where('user_id',Auth::user()->id)->orderby('primary_address','desc')->get());
            $primary_address_id = (isset($pickup_data['pickup_address'])) ? $pickup_data['pickup_address'] :  false;
            $primary_address = Address::find($primary_address_id);

            $primary_address_zipcode = false;
            $primary_zipcode = false;
            if (count($primary_address) > 0) {
                $primary_address_id = $primary_address->id;
                $primary_address_zipcode = $primary_address->zipcode;            
            }

            $zipcodes = Zipcode::where('zipcode',$primary_address_zipcode)->get();
            $zipcode_status = (count($zipcodes) > 0) ? true : false;
            $calendar_dates = [];
            if ($zipcodes) {
                foreach ($zipcodes as $zipcode) {

                    $calendar_dates[$zipcode['delivery_id']] = $zipcode['zipcode'];
                }
            }

            $calendar_setup = Delivery::makeCalendar($calendar_dates);

            if ($zipcode_status == false) {
                Flash::error('Your primary address is not set or zipcode is not valid. Please select a new address. ');
            }

            $dropoff_method = [''=>'Select Dropoff Method',
                               '1'=>'Delivered to the address chosen below.',
                               '2'=>'I wish to pick up my order myself.'];

            $breadcrumb_data = Delivery::setBreadCrumbs($pickup_data);
            return view('deliveries.dropoff')
            ->with('layout',$this->layout)
            ->with('auth',$auth)
            ->with('addresses',$addresses)
            ->with('primary_address_id',$primary_address_id)
            ->with('dropoff_method',$dropoff_method)
            ->with('zipcode_status',$zipcode_status)
            ->with('calendar_disabled',$calendar_setup)
            ->with('date_start',date('D m/d/Y',strtotime($pickup_data['pickup_date'].' +1 day')))
            ->with('breadcrumb_data',$breadcrumb_data);
        } else {
            Flash::error('Pickup form data has been timed out. Please fill out and try again.');
            return Redirect::route('delivery_pickup');
        }

    } 


    public function postPickupForm(Request $request) {
    	$this->validate($request, [
            'pickup_address' => 'required',
            'pickup_date'=>'required',
            'pickup_time'=>'required'
        ]);
        $request->session()->put('schedule', [
            'customer_id' => Auth::user()->id,
            'pickup_delivery_id' => $request->pickup_time,
            'pickup_address' => $request->pickup_address,
            'pickup_date'=> date('Y-m-d H:i:s',strtotime($request->pickup_date))
        ]);

        Flash::success('Please fill out dropoff form.');
        return Redirect::route('delivery_dropoff');
    }

    public function postDropoffForm(Request $request) {
        $this->validate($request, [
            'dropoff_date'=>'required',
            'dropoff_time'=>'required'
        ]);
        $schedule = $request->session()->get('schedule');
        $request->session()->put('schedule', [
            'pickup_delivery_id' => $schedule['pickup_delivery_id'],
            'pickup_address' => $schedule['pickup_address'],
            'pickup_date'=> $schedule['pickup_date'],            
            'dropoff_delivery_id' => $request->dropoff_time,
            'dropoff_address' => $request->dropoff_address,
            'dropoff_date'=> date('Y-m-d H:i:s',strtotime($request->dropoff_date))
        ]);

        return Redirect::route('delivery_confirmation');
    }

    public function getConfirmation(Request $r) {
        $r->session()->put('form_previous','delivery_confirmation');
        $schedule_data = $r->session()->get('schedule');

        //  Kick out users who do not have form completed
        if ($r->session()->has('schedule')) {
            $check_pickup_address = (isset($schedule_data['pickup_address'])) ? $schedule_data['pickup_address'] : false;
            $check_pickup_date = (isset($schedule_data['pickup_date'])) ? $schedule_data['pickup_date'] : false;
            $check_pickup_delivery_id = (isset($schedule_data['pickup_delivery_id'])) ? $schedule_data['pickup_delivery_id'] : false;
            $check_dropoff_address = (isset($schedule_data['dropoff_address'])) ? $schedule_data['dropoff_address'] : false;
            $check_dropoff_date = (isset($schedule_data['dropoff_date'])) ? $schedule_data['dropoff_date'] : false;
            $check_dropoff_delivery_id = (isset($schedule_data['dropoff_delivery_id'])) ? $schedule_data['dropoff_delivery_id'] :  false;
            if (!$check_pickup_address || !$check_pickup_date || !$check_pickup_delivery_id || !$check_dropoff_address || !$check_dropoff_date || !$check_dropoff_delivery_id) {
                Flash::error('Could not confirm delivery. Missing data in delivery form. Please fill out form');
                return Redirect::route('delivery_pickup');
            }
        } else {
            Flash::error('Could not confirm delivery. Delivery form has not been initiated. Please try again.');
            return Redirect::route('delivery_pickup');
        }

        // Create variables for view
        $pickup_addresses = Address::find($check_pickup_address);
        $delivery_address_1 = ($pickup_addresses->suite) ? $pickup_addresses->street.' #'.$pickup_addresses->suite : $pickup_addresses->street;
        $delivery_address_2 = ucFirst($pickup_addresses->city).', '.strtoupper($pickup_addresses->state).' '.$pickup_addresses->zipcode;
        $pickup_date = date('D m/d/Y',strtotime($check_pickup_date));
        $dropoff_date = date('D m/d/Y', strtotime($check_dropoff_date));
        $pickup_times = Delivery::find($check_pickup_delivery_id);
        $pickup_time = $pickup_times->start_time.' - '.$pickup_times->end_time;
        $dropoff_times = Delivery::find($check_dropoff_delivery_id);
        $dropoff_time = $dropoff_times->start_time.' - '.$dropoff_times->end_time;

        $zipcodes = Zipcode::where('zipcode',$pickup_addresses->zipcode)->get();
        if (count($zipcodes) > 0 ){
            foreach ($zipcodes as $z) {
                $company_id = $z->company_id;
            }
        } else {
            $company_id = 1; // Default
        }
        $r->session()->put('schedule', [
            'pickup_delivery_id' => $check_pickup_delivery_id,
            'pickup_address' => $check_pickup_address,
            'pickup_date'=> $check_pickup_date,            
            'dropoff_delivery_id' => $check_dropoff_delivery_id,
            'dropoff_address' => $check_dropoff_address,
            'dropoff_date'=> $check_dropoff_date,
            'company_id' => $company_id
        ]);

        $breadcrumb_data = Delivery::setBreadCrumbs($schedule_data);
        $cards = Card::where('user_id',Auth::user()->id)->get();
        $companies = Company::find($company_id);
        $cards_data = [];
        $payment_ids = [];
        if (count($cards) > 0) {
            foreach ($cards as $key => $card) {
                $profile_id = $card->profile_id;
                $payment_id = $card->payment_id;
                $exp_month = $card->exp_month;
                $exp_year = $card->exp_year;
                $street = $card->street;
                $suite = $card->suite;
                $city = $card->city;
                $state = $card->state;
                $status = $card->status;

                $exp_full_time = strtotime($exp_year.'-'.$exp_month.'-01 00:00:00');
                $today = strtotime(date('Y-m-d H:i:s'));
                $difference = $exp_full_time - $today;
                $days_remaining = floor($difference/60/60/24);
                $days_comment = ($days_remaining > 0) ? $days_remaining.' day(s) remaining.' : 'Expired!';
                if ($difference < 0) {
                    $exp_status = 3; // expired
                } elseif(($exp_full_time < strtotime('+1 month'))) {
                    $exp_status = 2; // Within a month
                } else {
                    $exp_status = 1; // good
                }

                switch($exp_status) {
                    case 2:
                    $background_color = '#FCF8E3';
                    break;

                    case 3:
                    $background_color = '#F2DEDE';
                    break;

                    default:
                    $background_color = '';
                    break;
                }
                $cards_data[$key] = [
                    'id' => $card->id,
                    'profile_id' => $profile_id,
                    'payment_id' => $payment_id,
                    'exp_month' => $exp_month,
                    'exp_year' => $exp_year,
                    'exp_status' => $exp_status,
                    'status' => $status,
                    'days_remaining' => $days_comment,
                    'background_color'=>$background_color
                ];

                // Common setup for API credentials (merchant)
                $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
                $merchantAuthentication->setName($companies->payment_api_login);
                $merchantAuthentication->setTransactionKey($companies->payment_gateway_id);
                $refId = 'ref' . time();

                //request requires customerProfileId and customerPaymentProfileId
                $request = new AnetAPI\GetCustomerPaymentProfileRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setRefId( $refId);
                $request->setCustomerProfileId($profile_id);
                $request->setCustomerPaymentProfileId($payment_id);

                $controller = new AnetController\GetCustomerPaymentProfileController($request);
                $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
                if(($response != null)){
                    if ($response->getMessages()->getResultCode() == "Ok")
                    {
                        $card_number = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber();
                        $card_type = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardType();
                        $cards_data[$key]['card_number'] = $card_number;
                        $cards_data[$key]['card_type'] = $card_type;
                        $card_first_name = $response->getPaymentProfile()->getBillTo()->getFirstName();
                        $card_last_name = $response->getPaymentProfile()->getBillTo()->getLastName();
                        $cards_data[$key]['first_name'] = $card_first_name;
                        $cards_data[$key]['last_name'] = $card_last_name;
                        switch($card_type) {
                            case 'Visa':
                                $cards_data[$key]['card_image'] = '/imgs/icons/visa.jpg';
                            break;
                            case 'MasterCard':
                                $cards_data[$key]['card_image'] = '/imgs/icons/master.jpg';
                            break;
                            case 'Amex':
                                $cards_data[$key]['card_image'] = '/imgs/icons/amex.jpg';
                            break;

                            case 'Discover':
                                $cards_data[$key]['card_image'] = '/imgs/icons/discover.jpg';
                            break;

                            default:
                                $cards_data[$key]['card_image'] = '';
                            break;
                        }
                        if ($difference < 0) { // expired
                            $payment_ids[$payment_id] = $card_type.' '.$card_number.' ***'.$days_comment.'***';  
                        } elseif(($exp_full_time < strtotime('+1 month'))) { // Within a month
                            $payment_ids[$payment_id] = $card_type.' '.$card_number.' '.$days_comment;
                        } else {  // good
                            $payment_ids[$payment_id] = $card_type.' '.$card_number.' '.$days_comment;
                        }
                        // Job::dump($response->getPaymentProfile());
                    }
                }               
            }
        }

        return view('deliveries.confirmation')
        ->with('layout',$this->layout)
        ->with('schedule',$schedule_data)
        ->with('breadcrumb_data',$breadcrumb_data)
        ->with('cards',$cards)
        ->with('payment_ids',$payment_ids)
        ->with('delivery_address',[$delivery_address_1,$delivery_address_2])
        ->with('pickup_date',$pickup_date)
        ->with('pickup_time',$pickup_time)
        ->with('dropoff_date',$dropoff_date)
        ->with('dropoff_time',$dropoff_time);
    }

    public function postConfirmation(Request $r) {
        $this->validate($r, [
            'payment_id' => 'required'
        ]);       

        $validate_data = $r->session()->get('schedule');
        $company_id = $validate_data['company_id'];
        $companies = Company::find($company_id);

        $first_name = Auth::user()->first_name;
        $last_name = Auth::user()->last_name;
        $email = Auth::user()->email;
        $special_instructions = $r->special_instructions;
        if ($r->session()->has('schedule')) {
            $session_data = $r->session()->get('schedule');
            $pickup_address = $session_data['pickup_address'];
            $pickup_delivery_id = $session_data['pickup_delivery_id'];
            $pickup_date = $session_data['pickup_date'];
            $dropoff_address = $session_data['dropoff_address'];
            $dropoff_delivery_id = $session_data['dropoff_delivery_id'];
            $dropoff_date = $session_data['dropoff_date'];
            $company_id = $session_data['company_id'];
        } else {
            Flash::error('Session data was lost. Please fill out form again.');
            return Redirect::route('delivery_pickup');
        }

        $schedules = new Schedule();
        $schedules->special_instructions = $special_instructions;
        $schedules->company_id = $company_id;
        $schedules->customer_id = Auth::user()->id;
        $schedules->pickup_address = $pickup_address;
        $schedules->pickup_date = date('Y-m-d H:i:s',strtotime($pickup_date));
        $schedules->pickup_delivery_id = $pickup_delivery_id;
        $schedules->dropoff_address = $dropoff_address;
        $schedules->dropoff_delivery_id = $dropoff_delivery_id;
        $schedules->dropoff_date = date('Y-m-d H:i:s',strtotime($dropoff_date));
        $schedules->status = 1;
        $schedules->type = 1;

        // Check if expired and if it authorizes with auth.net
        $payment_id = $r->payment_id;

        $cards = Card::where('payment_id',$payment_id)->where('company_id',$company_id)->get();
        if(count($cards) > 0) {
            foreach ($cards as $card) {
                $profile_id = $card->profile_id;
                $exp_month = $card->exp_month;
                $exp_year = $card->exp_year;
            }
        } else {
            Flash::error('This card cannot be processed. Please select another card or use our <a href="/cards" class="btn btn-link">card management</a> page to update card on file.');
            return Redirect::route('delivery_confirmation');
        }
        $exp_full_time = strtotime($exp_year.'-'.$exp_month.'-01 00:00:00');
        $today = strtotime(date('Y-m-d H:i:s'));
        $difference = $exp_full_time - $today;
        $days_remaining = floor($difference/60/60/24);
        $days_comment = ($days_remaining > 0) ? $days_remaining.' day(s) remaining.' : 'Expired!';

        // db expiration date check. do this first before having auth net check
        if ($difference < 0) {
            $exp_status = 3; // expired
            Flash::error('This card cannot be processed. Credit card has expired.. Please go to the <a href="/cards" class="btn btn-link">card management</a> page to update card on file.');
            return Redirect::route('delivery_confirmation');
        } 
        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($companies->payment_api_login);
        $merchantAuthentication->setTransactionKey($companies->payment_gateway_id);

        // Use an existing payment profile ID for this Merchant name and Transaction key
        //validationmode tests , does not send an email receipt
        $validationmode = "testMode";

        $request = new AnetAPI\ValidateCustomerPaymentProfileRequest();

        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setCustomerProfileId($profile_id);
        $request->setCustomerPaymentProfileId($payment_id);
        $request->setValidationMode($validationmode);

        $controller = new AnetController\ValidateCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
            $validationMessages = $response->getMessages()->getMessage();

            // transaction has validated credit card on file is approved. save the rest and continue to thank you page
            if ($schedules->save()) {
                $send_to = Auth::user()->email;
                $from = 'noreply@jayscleaners.com';
                $username = Auth::user()->username;
                $addresses= Address::find($pickup_address);
                $pickup_delivery = Delivery::find($schedules->pickup_delivery_id);
                $dropoff_delivery = Delivery::find($schedules->dropoff_delivery_id);
                $thank_you_mail_data = Delivery::prepareThankYouMail($schedules,$addresses,$pickup_delivery,$dropoff_delivery, $companies);

                // Email customer
                if (Mail::send('emails.thank_you', [
                    'data' => $thank_you_mail_data
                ], function($message) use ($send_to,$username)
                {
                    $message->to($send_to);
                    $message->subject('Delivery Confirmation - '.$username.' - created on: '.date('D m/d/Y g:i a'));
                })) {
                    // redirect with flash
                    Flash::success('Successfully created a new delivery schedule! You will receive an email confirmation containing this delivery information. Please check your spam/junk folder(s) if you do not receive an email within a few minutes. Thank You!');
                    
                } else {
                    Flash::success('Successfully created a new delivery schedule! However, the email was halted due to an unxpected error from the email server. Please log in to review/change your delivery form. Thank You!');
                }
                
                // remove relative session data
                if ($r->session()->has('schedule')) {
                    $r->session()->forget('schedule');
                }
                return Redirect::route('delivery_thankyou');

            } else {
                Flash::error('Could not save youre delivery session. There was an error within the server. Please try again!');
                return Redirect::route('delivery_confirmation');
            }
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            $error_msg = 'ERROR: '.$errorMessages[0]->getText();
            Flash::error($error_msg);
            return Redirect::route('delivery_confirmation');

        }
    }

    public function getThankYou(Request $request) {
        $request->session()->put('form_previous','delivery_confirmation');
        $schedule_data = $request->session()->get('schedule');

        return view('deliveries.thank_you')
        ->with('layout',$this->layout);
    }

    public function getCancel(Request $request) {
        if ($request->session()->has('schedule')) {
            $request->session()->forget('schedule');
        }
        return Redirect::route('pages_index');

    }

    public function postCheckAddress(Request $request) {
        if($request->ajax()){

            $addr_id = $request->id;
            $addresses = Address::find($addr_id);
            $zip = (count($addresses) > 0) ? $addresses['zipcode'] : false;
            $zipcodes = Zipcode::where('zipcode',$zip)->get();
            $check = false;
            if (count($zipcodes) > 0) {
                foreach ($zipcodes as $zipcode) {
                    $check = true;
                }
            }
            $request->session()->put('schedule',[
                'pickup_address'=>$addr_id,
                'pickup_date' => null,
                'pickup_delivery_id' => null,
                'dropoff_address' => null,
                'dropoff_date' => null,
                'dropoff_delivery_id' => null
            ]);


            return Response::json(['status'=>$check]);

        }
    }

    public function postSetTime(Request $request) {
        if ($request->ajax()) {
            $date = $request->date;
            $address_id = $request->address_id;

            $addresses = Address::find($address_id);
            $zipcode = false;
            if ($addresses) {
                $zipcode = $addresses->zipcode;
            }

            $zipcodes = Zipcode::where('zipcode',$zipcode)->get();
            $zip_list = [];
            if (count($zipcodes) > 0) {
                foreach ($zipcodes as $key => $zip) {
                    $delivery_id = $zip['delivery_id'];
                    $zip_list[$key] = $delivery_id;

                }
            }

            $time_options = Delivery::setTimeOptions($date,$zip_list);
            if (count($time_options) > 0){
                return Response::json(['status'=>true,
                                       'options'=>$time_options]);
            } else {
                return Response::json(['status'=>false]);
            }
        }
    }

    public function postSetTimeUpdate(Request $request) {
        if ($request->ajax()) {
            $date = $request->date;
            $address_id = $request->address_id;

            $pickup_delivery_id = (isset($request->pickup_delivery_id)) ? $request->pickup_delivery_id : false;

            $addresses = Address::find($address_id);
            $zipcode = false;
            if ($addresses) {
                $zipcode = $addresses->zipcode;
            }

            $zipcodes = Zipcode::where('zipcode',$zipcode)->get();
            $zip_list = [];
            $next_date_list = [];
            if (count($zipcodes) > 0) {
                foreach ($zipcodes as $key => $zip) {
                    $company_id = $zip->company_id;
                    $delivery_id = $zip['delivery_id'];
                    $zip_list[$key] = $delivery_id;
                    $nd_get = Delivery::prepareNextAvailableDate($delivery_id,$date);
                    $next_date_list[strtotime($nd_get)] = $nd_get;
                }
            }
            ksort($next_date_list);
            $next_available_date = reset($next_date_list);

            if (isset($request->pickup_delivery_id)) {
                if ($request->session()->has('schedule')) {
                    $request->session()->put('schedule',[
                        'pickup_delivery_id' => $pickup_delivery_id,
                        'pickup_address' => $address_id,
                        'pickup_date' => $date,
                        'dropoff_date'=> $next_available_date,
                        'dropoff_delivery_id'=>null,
                        'dropoff_addres'=>$address_id,
                        'company_id'=>$company_id
                    ]);

                }
                
            }

            // dropoff time


            $time_options_pickup = Delivery::setTimeOptions($date,$zip_list);

            $time_options_dropoff = Delivery::setTimeOptions($next_available_date,$zip_list);
            if (count($time_options_pickup) > 0 && count($time_options_dropoff) > 0){
                return Response::json(['status'=>true,
                                       'options_pickup'=>$time_options_pickup,
                                       'options_dropoff'=>$time_options_dropoff,
                                       'date'=> $next_available_date]);
            } else {
                return Response::json(['status'=>false]);
            }
        }        
    }

    public function getStart(Request $request) {
        if ($request->session()->has('schedule')) {
            $request->session()->forget('schedule');
        }

        return Redirect::route('delivery_pickup');

    }

    public function getDelete($id = null) {
        $customer_id = Auth::user()->id;
        $schedules = Schedule::find($id);
        if ($schedules->customer_id == $customer_id) {
            if ($schedules->status < 3) {
                $schedules->status = 6;
                if($schedules->save() && $schedules->delete()) {
                    Flash::success('You have successfully cancelled your delivery request.');
                    return Redirect::route('delivery_index');
                }
            } else {
                Flash::error('Cannot cancel request once delivery has been picked up. Please contact us for further assistance.');
                return Redirect::route('delivery_index');
            }
        } else {
            Flash::error('You do not have permission to edit this delivery schedule. Please select one from your delivery page and try again.');
            return Redirect::route('delivery_index');            
        }
    }

    public function getUpdate($id = null, Request $request) {
        $schedules = Schedule::find($id);
        $customer_id = Auth::user()->id;
        if ($schedules->customer_id == $customer_id) {
            $request->session()->put('form_previous',['delivery_update',$id]);
            $check_address = $request->session()->has('check_address') ? $request->session()->pull('check_address') : false;
            $auth = (Auth::check()) ? Auth::user() : false;
            $addresses = Address::addressSelect(Address::where('user_id',Auth::user()->id)->orderby('primary_address','desc')->get());
            
            $primary_address = Address::where('user_id',Auth::user()->id)->where('primary_address',true)->get();
            $primary_address_id = false;
            $primary_zipcode = false;
            if (count($primary_address) > 0) {
                foreach ($primary_address as $pa) {
                    $primary_address_id = $pa['id'];
                    $primary_address_zipcode = $pa['zipcode'];
                }
            }

            $special_instructions = $schedules->special_instructions;
            $selected_date = false;
            $selected_dropoff_date = false;
            $selected_delivery_id = false;
            $selected_dropoff_delivery_id = false;
            if (!$request->session()->has('schedule')) {
                $request->session()->put('schedule',[
                    'pickup_delivery_id' => $schedules->pickup_delivery_id,
                    'pickup_address' => $schedules->pickup_address,
                    'pickup_date'=> $schedules->pickup_date,            
                    'dropoff_delivery_id' => $schedules->dropoff_delivery_id,
                    'dropoff_address' => $schedules->dropoff_address,
                    'dropoff_date'=> $schedules->dropoff_date,
                    'company_id' => $schedules->company_id                
                ]);
            }


            $pickup_data = $request->session()->get('schedule');

            $check_session = (isset($pickup_data['pickup_address'])) ? true : false;
            if ($check_session) {
                
                $primary_address_id = (isset($pickup_data['pickup_address'])) ? $pickup_data['pickup_address'] : false;
                $primary_address = Address::find($primary_address_id);
                $primary_address_zipcode = (count($primary_address) > 0) ? $primary_address->zipcode : false;
                $selected_date = $pickup_data['pickup_date'];
                $selected_dropoff_date = $pickup_data['dropoff_date'];
                $selected_delivery_id = $pickup_data['pickup_delivery_id'];
                $selected_dropoff_delivery_id = $pickup_data['dropoff_delivery_id'];

           }


            $zipcodes = Zipcode::where('zipcode',$primary_address_zipcode)->get();
            $zip_list = [];
            if (count($zipcodes) > 0) {
                foreach ($zipcodes as $key => $zip) {
                    $delivery_id = $zip['delivery_id'];
                    $zip_list[$key] = $delivery_id;

                }
            }
            $time_options = Delivery::setTimeArray($selected_date,$zip_list);

            $time_options_dropoff = Delivery::setTimeArray($schedules->dropoff_date, $zip_list);


            $zipcode_status = (count($zipcodes) > 0) ? true : false;
            $calendar_dates = [];
            if ($zipcodes) {
                foreach ($zipcodes as $zipcode) {
                    $calendar_dates[$zipcode['delivery_id']] = $zipcode['zipcode'];
                }
            }

            $calendar_setup = Delivery::makeCalendar($calendar_dates);

            if ($zipcode_status == false) {
                Flash::error('Your primary address is not set or zipcode is not valid. Please select a new address. ');
            }

            return view('deliveries.update')
            ->with('layout',$this->layout)
            ->with('auth',$auth)
            ->with('addresses',$addresses)
            ->with('primary_address_id',$primary_address_id)
            ->with('zipcode_status',$zipcode_status)
            ->with('calendar_disabled',$calendar_setup)
            ->with('selected_date',$selected_date)
            ->with('selected_delivery_id',$selected_delivery_id)
            ->with('dropoff_date',$selected_dropoff_date)
            ->with('zip_list',$zip_list)
            ->with('date_start',$schedules->pickup_date)
            ->with('time_options',$time_options)
            ->with('time_options_dropoff',$time_options_dropoff)
            ->with('dropoff_delivery_id',$selected_dropoff_delivery_id)
            ->with('special_instructions',$special_instructions)
            ->with('update_id',$id);

        } else {
            Flash::error('You do not have permission to edit this delivery schedule. Please select one from your delivery page and try again.');
            return Redirect::route('delivery_index');
        }


    }

    public function postUpdate(Request $request) {
        $this->validate($request, [
            'pickup_address' => 'required',
            'pickup_date'=>'required',
            'pickup_time'=>'required',
            'dropoff_date' => 'required',
            'dropoff_time' => 'required'
        ]);

        $schedules = Schedule::find($request->id);
        $schedules->pickup_address = $request->pickup_address;
        $schedules->pickup_date = date('Y-m-d H:i:s',strtotime($request->pickup_date));
        $schedules->pickup_delivery_id = $request->pickup_time;
        $schedules->dropoff_address = $request->pickup_address;
        $schedules->dropoff_date = date('Y-m-d H:i:s',strtotime($request->dropoff_date));
        $schedules->dropoff_delivery_id = $request->dropoff_time;
        $schedules->special_instructions = $request->special_instructions;

        if ($schedules->save()) {
            if ($request->session()->has('schedule')) {
                $request->session()->forget('schedule');
            }
            Flash::success('You have successfully updated your delivery.');
            return Redirect::route('delivery_index');
        }


    }


    public function getHistory() {

    }

    public function getEmailTest() {
        return view('emails.thank_you');        
    }

    public function getOverview() {
        $this->layout = 'layouts.dropoff';
        $today = date('Y-m-d 00:00:00');
        $schedules = Schedule::where('status','<',12)->where('status','!=',6)->orderBy('id','desc')->get();

        $active_list = Schedule::prepareSchedule($schedules);

        return view('deliveries.overview')
        ->with('layout',$this->layout)
        ->with('schedules',$active_list);
    }

    public function postOverview(Request $request) {
        $this->validate($request, [
            'search' => 'required'
        ]);       
        
        $ids = [];
        if (is_numeric($request->search)) {
            if (strlen($request->search) < 7) { #search by id
                $customers = User::find($request->search);
                array_push($ids,$customers->id);
            } else { # search by phone number
                $customers = User::where('phone','LIKE','%'.$request->search.'%')->get();
                if (count($customers) > 0) {
                    foreach ($customers as $customer) {
                        array_push($ids,$customer->id);
                    }
                }
            }
        } else { # search by last name
            $customers = User::where('last_name','LIKE','%'.$request->search.'%')->get();
            if (count($customers) > 0) {
                foreach ($customers as $customer) {
                    array_push($ids,$customer->id);
                }
            }

        }
        $this->layout = 'layouts.dropoff';
        $today = date('Y-m-d 00:00:00');
        $schedules = Schedule::whereIn('customer_id',$ids)->where('status','!=',6)->orderBy('id','desc')->get();

        $active_list = Schedule::prepareSchedule($schedules);

        return view('deliveries.overview')
        ->with('layout',$this->layout)
        ->with('schedules',$active_list);
    }


    public function getAdminEdit($id = null, Request $request) {
        $schedules = Schedule::find($id);
        $customer_id = $schedules->customer_id;
        $request->session()->put('form_previous',['delivery_admin_edit',$id]);
        $check_address = $request->session()->has('check_address') ? $request->session()->pull('check_address') : false;
        $auth = (Auth::check()) ? Auth::user() : false;
        $addresses = Address::addressSelect(Address::where('user_id',Auth::user()->id)->orderby('primary_address','desc')->get());
        
        $primary_address = Address::where('user_id',Auth::user()->id)->where('primary_address',true)->get();
        $primary_address_id = false;
        $primary_zipcode = false;
        if (count($primary_address) > 0) {
            foreach ($primary_address as $pa) {
                $primary_address_id = $pa['id'];
                $primary_address_zipcode = $pa['zipcode'];
            }
        }

        $special_instructions = $schedules->special_instructions;
        $selected_date = false;
        $selected_dropoff_date = false;
        $selected_delivery_id = false;
        $selected_dropoff_delivery_id = false;
        if (!$request->session()->has('schedule')) {
            $request->session()->put('schedule',[
                'pickup_delivery_id' => $schedules->pickup_delivery_id,
                'pickup_address' => $schedules->pickup_address,
                'pickup_date'=> $schedules->pickup_date,            
                'dropoff_delivery_id' => $schedules->dropoff_delivery_id,
                'dropoff_address' => $schedules->dropoff_address,
                'dropoff_date'=> $schedules->dropoff_date,
                'company_id' => $schedules->company_id                
            ]);
        }


        $pickup_data = $request->session()->get('schedule');

        $check_session = (isset($pickup_data['pickup_address'])) ? true : false;
        if ($check_session) {
            
            $primary_address_id = (isset($pickup_data['pickup_address'])) ? $pickup_data['pickup_address'] : false;
            $primary_address = Address::find($primary_address_id);
            $primary_address_zipcode = (count($primary_address) > 0) ? $primary_address->zipcode : false;
            $selected_date = $pickup_data['pickup_date'];
            $selected_dropoff_date = $pickup_data['dropoff_date'];
            $selected_delivery_id = $pickup_data['pickup_delivery_id'];
            $selected_dropoff_delivery_id = $pickup_data['dropoff_delivery_id'];

       }


        $zipcodes = Zipcode::where('zipcode',$primary_address_zipcode)->get();
        $zip_list = [];
        if (count($zipcodes) > 0) {
            foreach ($zipcodes as $key => $zip) {
                $delivery_id = $zip['delivery_id'];
                $zip_list[$key] = $delivery_id;

            }
        }
        $time_options = Delivery::setTimeArray($selected_date,$zip_list);

        $time_options_dropoff = Delivery::setTimeArray($schedules->dropoff_date, $zip_list);


        $zipcode_status = (count($zipcodes) > 0) ? true : false;
        $calendar_dates = [];
        if ($zipcodes) {
            foreach ($zipcodes as $zipcode) {
                $calendar_dates[$zipcode['delivery_id']] = $zipcode['zipcode'];
            }
        }

        $calendar_setup = Delivery::makeCalendar($calendar_dates);

        if ($zipcode_status == false) {
            Flash::error('Your primary address is not set or zipcode is not valid. Please select a new address. ');
        }
        $this->layout = 'layouts.dropoff';
        return view('deliveries.admin-edit')
        ->with('layout',$this->layout)
        ->with('addresses',$addresses)
        ->with('primary_address_id',$primary_address_id)
        ->with('zipcode_status',$zipcode_status)
        ->with('calendar_disabled',$calendar_setup)
        ->with('selected_date',$selected_date)
        ->with('selected_delivery_id',$selected_delivery_id)
        ->with('dropoff_date',$selected_dropoff_date)
        ->with('zip_list',$zip_list)
        ->with('date_start',$schedules->pickup_date)
        ->with('time_options',$time_options)
        ->with('time_options_dropoff',$time_options_dropoff)
        ->with('dropoff_delivery_id',$selected_dropoff_delivery_id)
        ->with('special_instructions',$special_instructions)
        ->with('customer_id',$customer_id)
        ->with('update_id',$id);   
    }
    public function postAdminEdit(Request $request) {
        $this->validate($request, [
            'pickup_address' => 'required',
            'pickup_date'=>'required',
            'pickup_time'=>'required',
            'dropoff_date' => 'required',
            'dropoff_time' => 'required'
        ]);

        $schedules = Schedule::find($request->id);
        $schedules->pickup_address = $request->pickup_address;
        $schedules->pickup_date = date('Y-m-d H:i:s',strtotime($request->pickup_date));
        $schedules->pickup_delivery_id = $request->pickup_time;
        $schedules->dropoff_address = $request->pickup_address;
        $schedules->dropoff_date = date('Y-m-d H:i:s',strtotime($request->dropoff_date));
        $schedules->dropoff_delivery_id = $request->dropoff_time;
        $schedules->special_instructions = $request->special_instructions;

        if ($schedules->save()) {
            if ($request->session()->has('schedule')) {
                $request->session()->forget('schedule');
            }
            Flash::success('You have successfully updated your delivery.');
            return Redirect::route('schedules_view',$request->session()->get('address_user_id'));
        }


    }
}
