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
use App\Profile;
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
    	$cards = Card::where('user_id',Auth::user()->id)->where('company_id',1)->get();
    	$companies = Company::find(1);
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

    public function postAdd(Request $r) {
    	$this->validate($r, [
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
        $first_name = $r->first_name;
        $last_name = $r->last_name;
        $street = $r->street;
        $suite = $r->suite;
        $city = $r->city;
        $state = $r->state;
        $zipcode = $r->zipcode;
        $exp_month = $r->month;
        $exp_year = $r->year;
        $card_number = $r->card;

        $cards_saved = 0;

        $root_payment_id = false;

    	// $company_id = 1;
    	$companies = Company::All();
    	if (count($companies) > 0) {
    		foreach ($companies as $company) {
    			$company_id = $company->id;
				$api_login_id = $company->payment_api_login;
				$api_transaction_key = $company->payment_gateway_id;
				$cards = new Card();
				$cards->user_id = Auth::user()->id;
				$cards->street = $street;
				$cards->suite = $suite;
				$cards->city = $city;
				$cards->zipcode = $zipcode;
				$cards->state = $state;
				$cards->exp_month = $exp_month;
				$cards->exp_year = $exp_year;
				$cards->company_id = $company_id;
				// Common setup for API credentials
				$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
				$merchantAuthentication->setName($api_login_id);
				$merchantAuthentication->setTransactionKey($api_transaction_key);
				$refId = 'ref' . time(). $company_id;
				// Create the payment data for a credit card
				$creditCard = new AnetAPI\CreditCardType();
				$creditCard->setCardNumber($card_number);
				$creditCard->setExpirationDate($exp_year.'-'.$exp_month);
				$paymentCreditCard = new AnetAPI\PaymentType();
				$paymentCreditCard->setCreditCard($creditCard);

				// Create the Bill To info
				$billto = new AnetAPI\CustomerAddressType();
				$billto->setFirstName($first_name);
				$billto->setLastName($last_name);
				$billto->setCompany('');
				$billto->setAddress($street.' '.$suite);
				$billto->setCity($city);
				$billto->setState($state);
				$billto->setZip($zipcode);
				$billto->setCountry("USA");

				$profiles = Profile::where('company_id',$company_id)->where('user_id',Auth::user()->id)->get();
				if (count($profiles)) {
					foreach ($profiles as $profile) {
						$prev_profile_id = $profile->profile_id;		

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
							$root_payment_id = ($root_payment_id) ? $root_payment_id : $response->getCustomerPaymentProfileId();
						  	$cards->profile_id = $prev_profile_id;
						  	$cards->payment_id = $response->getCustomerPaymentProfileId();
						  	$cards->root_payment_id = $root_payment_id;
						  	$cards->status = 1;
						  	if ($cards->save()) {
						  		$cards_saved += 1;
						  	}
						} else {
							// echo "Create Customer Payment Profile: ERROR Invalid response\n";
							$errorMessages = $response->getMessages()->getMessage();
							// echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
						 	Flash::error("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
						 	return Redirect::route('cards_add');
						 	// Job::dump("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
						}			
						

					} //end foreach
				} else {
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
					$customerprofile->setDescription("company_id #".$company_id." customer #".Auth::user()->id.' '.strtotime(date('Y-m-d H:i:s')));
					// $customerprofile->setMerchantCustomerId(Auth::user()->id);
					// $customerprofile->setEmail(Auth::user()->email);
					$customerprofile->setPaymentProfiles($paymentprofiles);

					$request = new AnetAPI\CreateCustomerProfileRequest();
					$request->setMerchantAuthentication($merchantAuthentication);
					$request->setRefId( $refId);
					$request->setProfile($customerprofile);
					// Job::dump($request);
					$controller = new AnetController\CreateCustomerProfileController($request);
					$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
					if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
						// Flash::success('Succesfully create customer profile : '.$response->getCustomerProfileId() );
					  	// Job::dump($response->getCustomerProfileId());
					  	$paymentProfiles = $response->getCustomerPaymentProfileIdList();
					  	$payment_id = $paymentProfiles[0];

					  	$cards->profile_id = $response->getCustomerProfileId();
					  	$cards->payment_id = $payment_id;
					  	$cards->root_payment_id = $payment_id;
					  	$cards->status = 1;
					  	$prof = new Profile();
					  	$prof->company_id = $company_id;
					  	$prof->user_id = Auth::user()->id;
					  	$prof->profile_id = $response->getCustomerProfileId();
					  	$prof->status = 1;
					  	$prof->save();
					  	if ($cards->save()) {
					  		$cards_saved += 1;
					  	}
					  
					} else {
						$errorMessages = $response->getMessages()->getMessage();
						Flash::error($errorMessages);
						// Job::dump("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
						return Redirect::route('cards_index');
					}					
				}
    		} #endforeach
    	}

    	if ($cards_saved == count($companies)) {
    		Flash::success('Successfully saved a new card!');
    		return Redirect::route('cards_index');
    	}
		
    }

    public function getEdit($id = NULL) {
    	$cards = Card::find($id);
    	$profile_id = $cards->profile_id;
    	$payment_id = $cards->payment_id;
    	$company_id = 1;
    	$companies = Company::find($company_id);
    	$api_login = $companies->payment_api_login;
    	$api_token = $companies->payment_gateway_id;

    	// Common setup for API credentials (merchant)
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName($api_login);
		$merchantAuthentication->setTransactionKey($api_token);
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
			if ($response->getMessages()->getResultCode() == "Ok") {
				$card_number = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber();
				$card_type = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardType();
				$card_first_name = $response->getPaymentProfile()->getBillTo()->getFirstName();
				$card_last_name = $response->getPaymentProfile()->getBillTo()->getLastName();

			} else {
				$card_number = null;
				$card_first_name = null;
				$card_last_name = null;
				$card_type = null;

			}
		}
		$states = Job::states();
    	return view('cards.edit')
        ->with('layout',$this->layout)
        ->with('cards',$cards)
        ->with('first_name',$card_first_name)
        ->with('last_name',$card_last_name)
        ->with('card_number',$card_number)
        ->with('states',$states);


    }

    public function postEdit(Request $r) {
    	$this->validate($r, [
            'first_name' => 'required',
            'last_name'=>'required',
            'street'=>'required',
            'city'=>'required',
            'zipcode'=>'required',
            'state'=>'required',
            'year'=>'required',
            'month'=>'required',
            'card' => 'required'
        ]);

        $card_id = $r->id;
        $first_name = $r->first_name;
        $last_name = $r->last_name;
        $street = $r->street;
        $city = $r->city;
        $suite = $r->suite;
        $zipcode = $r->zipcode;
        $state = $r->state;
        $exp_year = $r->year;
        $exp_month = $r->month;
        $card_number = $r->card;

        $cards = Card::find($card_id);
        $cards->street = $street;
        $cards->suite = $suite;
        $cards->city = $city;
        $cards->state = $state;
        $cards->zipcode = $zipcode;
        $cards->exp_month = $exp_month;
        $cards->exp_year = $exp_year;
        $companies = Company::find($cards->company_id);
        $root_payment_id = $cards->root_payment_id;
        $payment_id = $cards->payment_id;
        $profile_id = $cards->profile_id;


		// Common setup for API credentials
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName($companies->payment_api_login);
		$merchantAuthentication->setTransactionKey($companies->payment_gateway_id);
		$refId = 'ref' . time();

		//Set profile ids of profile to be updated
		$request = new AnetAPI\UpdateCustomerPaymentProfileRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setCustomerProfileId($cards->profile_id);
		$controller = new AnetController\GetCustomerProfileController($request);


		// We're updating the billing address but everything has to be passed in an update
		// For card information you can pass exactly what comes back from an GetCustomerPaymentProfile
		// if you don't need to update that info
		$creditCard = new AnetAPI\CreditCardType();
		$creditCard->setCardNumber( $card_number );
		$creditCard->setExpirationDate( $exp_year.'-'.$exp_month );
		$paymentCreditCard = new AnetAPI\PaymentType();
		$paymentCreditCard->setCreditCard($creditCard);

		// Create the Bill To info for new payment type
		$billto = new AnetAPI\CustomerAddressType();
		$billto->setFirstName($first_name);
		$billto->setLastName($last_name);
		$billto->setAddress($street.' '.$suite);
		$billto->setCity($city);
		$billto->setState($state);
		$billto->setZip($zipcode);
		// $billto->setPhoneNumber("000-000-0000");
		// $billto->setfaxNumber("999-999-9999");
		$billto->setCountry("USA");


		// Create the Customer Payment Profile object
		$paymentprofile = new AnetAPI\CustomerPaymentProfileExType();
		$paymentprofile->setCustomerPaymentProfileId($payment_id);
		$paymentprofile->setBillTo($billto);
		$paymentprofile->setPayment($paymentCreditCard);

		// Submit a UpdatePaymentProfileRequest
		$request->setPaymentProfile( $paymentprofile );

		$controller = new AnetController\UpdateCustomerPaymentProfileController($request);
		$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
		$errorMessages = $response->getMessages()->getMessage();
		$error_response = 'Error: could not save card, please try again!';
		if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {

			// Update only returns success or fail, if success
			// confirm the update by doing a GetCustomerPaymentProfile
			$getRequest = new AnetAPI\GetCustomerPaymentProfileRequest();
			$getRequest->setMerchantAuthentication($merchantAuthentication);
			$getRequest->setRefId( $refId);
			$getRequest->setCustomerProfileId($profile_id);
			$getRequest->setCustomerPaymentProfileId($payment_id);

			$controller = new AnetController\GetCustomerPaymentProfileController($getRequest);
			$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
			
			if(($response != null)) {
				if ($response->getMessages()->getResultCode() == "Ok") {
					if ($cards->save()) {
						$r->session()->put('edit_card_again',[
				    		"card_id" => $card_id,
					        "first_name" => $first_name,
					        "last_name" => $last_name,
					        "street" => $street,
					        "city" => $city,
					        "suite" => $suite,
					        "zipcode" => $zipcode,
					        "state" => $state,
					        "exp_year" => $exp_year,
					        "exp_month" => $exp_month,
					        "card_number" => $card_number,
					        "company_id" => 2, // fix later
					        "root_payment_id" => $root_payment_id
						]);

						return Redirect::route('cards_edit_again');
					}
					
				} else {
					$error_response = "Error: ".$errorMessages[0]->getText();
					Flash::error($error_response);
					return Redirect::route('cards_index');
				}
			} else {
				$error_response = "Error: Communication error with authorize.net, please try again";
				Flash::error($error_response);
				return Redirect::route('cards_index');
			}

		} else {

			$error_response = "Error: ".$errorMessages[0]->getText();
			Flash::error($error_response);
			return Redirect::route('cards_index');
		}

    }

    public function getEditAgain(Request $request) {
    	if ($request->session()->has('edit_card_again')) {
    		$session_data = $request->session()->pull('edit_card_again');
    		$card_id = $session_data['card_id'];
	        $first_name = $session_data['first_name'];
	        $last_name = $session_data['last_name'];
	        $street = $session_data['street'];
	        $city = $session_data['city'];
	        $suite = $session_data['suite'];
	        $zipcode = $session_data['zipcode'];
	        $state = $session_data['state'];
	        $exp_year = $session_data['exp_year'];
	        $exp_month = $session_data['exp_month'];
	        $card_number = $session_data['card_number'];
	        $company_id = $session_data['company_id'];
	        $root_payment_id = $session_data['root_payment_id'];

	        $companies = Company::find($company_id);

	        $cards = Card::where('root_payment_id',$root_payment_id)->where('company_id',$company_id)->get();
	        $error_response = 'Error: could not save card. please try again!';
	        if(count($cards) > 0) {
	        	foreach ($cards as $card) {
	        		$profile_id = $card->profile_id;
	        		$payment_id = $card->payment_id;
					$c_id = $card->id;
					$card_find = Card::find($c_id);
					$card_find->street = $street;
					$card_find->city = $city;
					$card_find->suite = $suite;
					$card_find->zipcode = $zipcode;
					$card_find->state = $state;
					$card_find->exp_year = $exp_year;
					$card_find->exp_month = $exp_month;

					// Common setup for API credentials
					$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
					$merchantAuthentication->setName($companies->payment_api_login);
					$merchantAuthentication->setTransactionKey($companies->payment_gateway_id);
					$refId = 'ref' . time();

					//Set profile ids of profile to be updated
					$request = new AnetAPI\UpdateCustomerPaymentProfileRequest();
					$request->setMerchantAuthentication($merchantAuthentication);
					$request->setCustomerProfileId($profile_id);
					$controller = new AnetController\GetCustomerProfileController($request);


					// We're updating the billing address but everything has to be passed in an update
					// For card information you can pass exactly what comes back from an GetCustomerPaymentProfile
					// if you don't need to update that info
					$creditCard = new AnetAPI\CreditCardType();
					$creditCard->setCardNumber( $card_number );
					$creditCard->setExpirationDate( $exp_year.'-'.$exp_month );
					$paymentCreditCard = new AnetAPI\PaymentType();
					$paymentCreditCard->setCreditCard($creditCard);

					// Create the Bill To info for new payment type
					$billto = new AnetAPI\CustomerAddressType();
					$billto->setFirstName($first_name);
					$billto->setLastName($last_name);
					$billto->setAddress($street.' '.$suite);
					$billto->setCity($city);
					$billto->setState($state);
					$billto->setZip($zipcode);
					// $billto->setPhoneNumber("000-000-0000");
					// $billto->setfaxNumber("999-999-9999");
					$billto->setCountry("USA");


					// Create the Customer Payment Profile object
					$paymentprofile = new AnetAPI\CustomerPaymentProfileExType();
					$paymentprofile->setCustomerPaymentProfileId($payment_id);
					$paymentprofile->setBillTo($billto);
					$paymentprofile->setPayment($paymentCreditCard);

					// Submit a UpdatePaymentProfileRequest
					$request->setPaymentProfile( $paymentprofile );

					$controller = new AnetController\UpdateCustomerPaymentProfileController($request);
					$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
					$errorMessages = $response->getMessages()->getMessage();
					if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {

						// Update only returns success or fail, if success
						// confirm the update by doing a GetCustomerPaymentProfile
						$getRequest = new AnetAPI\GetCustomerPaymentProfileRequest();
						$getRequest->setMerchantAuthentication($merchantAuthentication);
						$getRequest->setRefId( $refId);
						$getRequest->setCustomerProfileId($profile_id);
						$getRequest->setCustomerPaymentProfileId($payment_id);

						$controller = new AnetController\GetCustomerPaymentProfileController($getRequest);
						$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
						if(($response != null)) {
							if ($response->getMessages()->getResultCode() == "Ok") {
								if($card_find->save()) {
									Flash::success('Successfully updated credit card!');
									return Redirect::route('cards_index');
								}
								
							} else {
								$error_response = "Error: ".$errorMessages[0]->getText();
							}
						} else {
							$error_response = "Error: Communication error with authorize.net, please try again";
						}

					} else {

						$error_response = "Error: ".$errorMessages[0]->getText();
					}
	        	}
	        }
	        Flash::error($error_response);
	        return Redirect::route('cards_edit',$card_id);

    	} else {
    		Flash::error('Could not save card. Please try again!');
    		return Redirect::route('cards_index');
    	}
    }

    public function getDelete($id = NULL, Request $r) {
    	$cards = Card::find($id);
    	$root_payment_id = $cards->root_payment_id;
    	$company_id = $cards->company_id;
    	$companies = Company::find($company_id);
    	$profile_id = $cards->profile_id;
    	$payment_id = $cards->payment_id;

    	$r->session()->put('delete_card_again',[
    		'company_id' => 2,
    		'root_payment_id' => $root_payment_id
    	]);

		// Common setup for API credentials
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName($companies->payment_api_login);
		$merchantAuthentication->setTransactionKey($companies->payment_gateway_id);

		// Use an existing payment profile ID for this Merchant name and Transaction key

		$request = new AnetAPI\DeleteCustomerPaymentProfileRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setCustomerProfileId($profile_id);
		$request->setCustomerPaymentProfileId($payment_id);
		$controller = new AnetController\DeleteCustomerPaymentProfileController($request);
		$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
		if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
			if($cards->delete()) {
				// Flash::success('Successfully removed card!');
				return Redirect::route('cards_delete_again');
			}
			
		} else {
			$errorMessages = $response->getMessages()->getMessage();
			Flash::error('ERROR: '.$errorMessages[0]->getText());
			return Redirect::route('cards_index');
		}
    }

    public function getDeleteAgain(Request $r) {
    	if ($r->session()->has('delete_card_again')) {
    		$session_data = $r->session()->pull('delete_card_again');
    		$company_id = $session_data['company_id'];
    		$root_payment_id = $session_data['root_payment_id'];
    		$companies = Company::find($company_id);

    		$cards = Card::where('company_id',$company_id)->where('root_payment_id',$root_payment_id)->get();
    		if (count($cards) > 0) {
    			foreach ($cards as $card) {
    				$profile_id = $card->profile_id;
    				$payment_id = $card->payment_id;
    				$card_find = Card::find($card->id);
					// Common setup for API credentials
					$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
					$merchantAuthentication->setName($companies->payment_api_login);
					$merchantAuthentication->setTransactionKey($companies->payment_gateway_id);

					// Use an existing payment profile ID for this Merchant name and Transaction key

					$request = new AnetAPI\DeleteCustomerPaymentProfileRequest();
					$request->setMerchantAuthentication($merchantAuthentication);
					$request->setCustomerProfileId($profile_id);
					$request->setCustomerPaymentProfileId($payment_id);
					$controller = new AnetController\DeleteCustomerPaymentProfileController($request);
					$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
					if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
						if($card_find->delete()) {
							Flash::success('Successfully removed card!');
							return Redirect::route('cards_index');
						}
						
					} else {
						$errorMessages = $response->getMessages()->getMessage();
						Flash::error('ERROR: '.$errorMessages[0]->getText());
						return Redirect::route('cards_index');
					}
    			}
    		}
    	} else {
    		Flash::error('Could not delete card from authorize.net. Please try again!');
    		return Redirect::route('cards_index');    		
    	}
    }
}
