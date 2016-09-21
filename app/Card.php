<?php

namespace App;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class Card extends Model
{
    use SoftDeletes;

    static public function addCard($form_data, $company_id, $root_payment_id) {
        $cards_saved = 0;
        
        $first_name = $form_data['first_name'];
        $last_name = $form_data['last_name'];
        $street = $form_data['street'];
        $suite = $form_data['suite'];
        $city = $form_data['city'];
        $state = $form_data['state'];
        $zipcode = $form_data['zipcode'];
        $exp_month = $form_data['exp_month'];
        $exp_year = $form_data['exp_year'];
        $card_number = $form_data['card_number'];    	
    	$companies = Company::find($company_id);
		$api_login_id = $companies->payment_api_login;
		$api_transaction_key = $companies->payment_gateway_id;
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
				// $paymentprofilerequest->setValidationMode("liveMode");
				$controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
				$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
				if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
					$root_payment_id = ($root_payment_id) ? $root_payment_id : $response->getCustomerPaymentProfileId();
				  	$cards->profile_id = $prev_profile_id;
				  	$cards->payment_id = $response->getCustomerPaymentProfileId();
				  	$cards->root_payment_id = $root_payment_id;
				  	$cards->status = 1;
				  	if ($cards->save()) {
				  		return true;
				  	}
				} else {
					// echo "Create Customer Payment Profile: ERROR Invalid response\n";
					$errorMessages = $response->getMessages()->getMessage();
					// echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
				 	Flash::error("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
				 	return Redirect::route('cards_add');
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
			$customerprofile->setMerchantCustomerId(Auth::user()->id);
			$customerprofile->setEmail(Auth::user()->email);
			$customerprofile->setDescription(Auth::user()->last_name.', '.Auth::user()->first_name.' ~ '.strtotime(date('Y-m-d H:i:s')));
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
			  	$cards->root_payment_id = ($root_payment_id) ? $root_payment_id : $payment_id;
			  	$cards->status = 1;
			  	$prof = new Profile();
			  	$prof->company_id = $company_id;
			  	$prof->user_id = Auth::user()->id;
			  	$prof->profile_id = $response->getCustomerProfileId();
			  	$prof->status = 1;
			  	$prof->save();
			  	if ($cards->save()) {
			  		return ($root_payment_id) ? $root_payment_id : $payment_id;
			  	}
			  
			} else {
				$errorMessages = $response->getMessages()->getMessage();
				Flash::error("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
				// Job::dump("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
				return Redirect::route('cards_add');
			}					
		}
    }

    static public function checkValid($company_id, $profile_id, $payment_id) {
    	$companies = Company::find($company_id);
        $payment_api_login = $companies->payment_api_login;
        $payment_api_password = $companies->payment_gateway_id;
    	$validated = false;
		// Common setup for API credentials
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName($payment_api_login);
		$merchantAuthentication->setTransactionKey($payment_api_password);

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
			return true;
		} else {	
			return false;
		}

    }


}
