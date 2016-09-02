<?php

namespace App\Http\Controllers;

use Input;
use Validator;
use Redirect;
use Hash;
use Route;
use Response;
use Auth;
use URL;
use Session;
use Laracasts\Flash\Flash;
use View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\User;
use App\Card;
use App\Address;
use App\Company;
use App\Customer;
use App\Custid;
use App\Delivery;
use App\Layout;
use App\Zipcode;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
define("AUTHORIZENET_LOG_FILE", "phplog");
class CardsController extends Controller
{
    public function __construct() {
    	$this->layout = 'layouts.frontend_basic';
    }

    public function getIndex(Request $request) {
    	$form_previous = ($request->session()->has('form_previous')) ? $request->session()->get('form_previous') : 'delivery_confirmation';
    	$cards = Card::where('user_id',Auth::user()->id)->get();

    	$cards_data = [];
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
    			// make exp status
    			$exp_status = 1; // good

    			$exp_full_time = strtotime($exp_year.'-'.$exp_month.'-01 00:00:00');
    			$today = strtotime(date('Y-m-d H:i:s'));
    			$difference = $exp_full_time - $today;
    			$days_remaining = floor($difference/60/60/24);
    			$days_comment = ($days_remaining > 0) ? $days_remaining.' day(s) remaining.' : 'Expired!';
    			if ($difference < 0) {
    				$exp_status = 3; // expired
    			} elseif(($exp_full_time < strtotime('+1 month'))) {
    				$exp_status = 2; // Within a month
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
				$merchantAuthentication->setName('97NAepQ6QS');
				$merchantAuthentication->setTransactionKey('28vWtpH555K7c8tD');
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
							case 'Master':
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
						// Job::dump($response->getPaymentProfile());
					}
				}    			
    		}
    	}


    	return view('cards.index')
    	->with('layout',$this->layout)
    	->with('form_previous',$form_previous)
    	->with('cards_data',$cards_data);
    }

    public function getAdd() {
    	$states = Job::states();
    	return view('cards.add')
        ->with('layout',$this->layout)
        ->with('states',$states);
    }

    public function postAdd(Request $request) {
    	$this->validate($request, [
            'first_name' => 'required',
            'last_name'=>'required',
            'street'=>'required',
            'city'=>'required',
            'zipcode'=>'required',
            'state'=>'required',
            'card'=>'required',
            'year'=>'required',
            'month'=>'required'
        ]);
	  	$cards = new Card();
	  	$cards->user_id = Auth::user()->id;
	  	$cards->company_id = 2;
	  	$cards->street = $request->street;
	  	$cards->suite = $request->suite;
	  	$cards->city = $request->city;
	  	$cards->zipcode = $request->zipcode;
	  	$cards->state = $request->state;
	  	$cards->exp_month = $request->month;
	  	$cards->exp_year = $request->year;


		// Common setup for API credentials
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName('97NAepQ6QS');
		$merchantAuthentication->setTransactionKey('28vWtpH555K7c8tD');
		$refId = 'ref' . time();
		// Create the payment data for a credit card
		$creditCard = new AnetAPI\CreditCardType();
		$creditCard->setCardNumber($request->card);
		$creditCard->setExpirationDate($request->year.'-'.$request->month);
		$paymentCreditCard = new AnetAPI\PaymentType();
		$paymentCreditCard->setCreditCard($creditCard);

		// Create the Bill To info
		$billto = new AnetAPI\CustomerAddressType();
		$billto->setFirstName($request->first_name);
		$billto->setLastName($request->last_name);
		$billto->setCompany('');
		$billto->setAddress($request->street.' '.$request->suite);
		$billto->setCity($request->city);
		$billto->setState($request->state);
		$billto->setZip($request->zipcode);
		$billto->setCountry("USA");

		$prev_profile_id = Auth::user()->profile_id;

		if (!isset($prev_profile_id)) {
			// Create a Customer Profile Request
			//  1. create a Payment Profile
			//  2. create a Customer Profile   
			//  3. Submit a CreateCustomerProfile Request
			//  4. Validate Profiiel ID returned

			$paymentprofile = new AnetAPI\CustomerPaymentProfileType();

			$paymentprofile->setCustomerType('individual');
			$paymentprofile->setBillTo($billto);
			$paymentprofile->setPayment($paymentCreditCard);
			$paymentprofiles[] = $paymentprofile;
			$customerprofile = new AnetAPI\CustomerProfileType();
			$customerprofile->setDescription("time description");
			// $customerprofile->setMerchantCustomerId("M_wondo@eyelevate.com");
			// $customerprofile->setEmail('wondo@eyelevate.com');
			$customerprofile->setPaymentProfiles($paymentprofiles);

			$request = new AnetAPI\CreateCustomerProfileRequest();
			$request->setMerchantAuthentication($merchantAuthentication);
			$request->setRefId( $refId);
			$request->setProfile($customerprofile);
			$controller = new AnetController\CreateCustomerProfileController($request);
			$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
			if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
				// Flash::success('Succesfully create customer profile : '.$response->getCustomerProfileId() );
			  	
			  	$paymentProfiles = $response->getCustomerPaymentProfileIdList();
			  	$payment_id = $paymentProfiles[0];

			  	$cards->profile_id = $response->getCustomerProfileId();
			  	$cards->payment_id = $payment_id;
			  	$cards->status = 1;
			  	$users = User::find(Auth::user()->id);
			  	$users->profile_id = $response->getCustomerProfileId();
			  	$users->save();
			  	if ($cards->save()) {
			  		Flash::success('Successfully added a new card!');
	        		return Redirect::route('cards_index');
			  	}
			  
			} else {
				$errorMessages = $response->getMessages()->getMessage();
				Flash::error($errorMessages);
				return Redirect::route('cards_index');

				Job::dump('error invalid reponse');
				Job::dump("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
			 
			}
		} else {


			// Create a new Customer Payment Profile
			$paymentprofile = new AnetAPI\CustomerPaymentProfileType();
			$paymentprofile->setCustomerType('individual');
			$paymentprofile->setBillTo($billto);
			$paymentprofile->setPayment($paymentCreditCard);

			$paymentprofiles[] = $paymentprofile;

			// Submit a CreateCustomerPaymentProfileRequest to create a new Customer Payment Profile
			$paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
			$paymentprofilerequest->setMerchantAuthentication($merchantAuthentication);
			//Use an existing profile id
			$paymentprofilerequest->setCustomerProfileId( $prev_profile_id );
			$paymentprofilerequest->setPaymentProfile( $paymentprofile );
			$paymentprofilerequest->setValidationMode("liveMode");
			$controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
			$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
			if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
			  	$cards->profile_id = $prev_profile_id;
			  	$cards->payment_id = $response->getCustomerPaymentProfileId();
			  	$cards->status = 1;
			  	if ($cards->save()) {
			  		Flash::success('Successfully added a new card!');
	        		return Redirect::route('cards_index');
			  	}
			} else {
				// echo "Create Customer Payment Profile: ERROR Invalid response\n";
				$errorMessages = $response->getMessages()->getMessage();
				// echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
			 	Flash::error("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
			 	return Redirect::route('cards_add');
			}			
		}
	  

    }

    public function getEdit($id = NULL) {

    }

    public function postEdit() {

    }

    public function getDelete($id = NULL) {

    }
}
