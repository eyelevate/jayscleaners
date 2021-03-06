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
				$paymentprofilerequest->setValidationMode("liveMode");
				// $paymentprofilerequest->setValidationMode("liveMode");
				$controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
				$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
				if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
					$root_payment_id = ($root_payment_id) ? $root_payment_id : $response->getCustomerPaymentProfileId();
				  	$cards->profile_id = $prev_profile_id;
				  	$cards->payment_id = $response->getCustomerPaymentProfileId();
				  	$cards->root_payment_id = $root_payment_id;
				  	$cards->status = 1;
				  	if ($cards->save()) {
				  		return ['status'=>true,
				  				'root_payment_id'=>$root_payment_id];
				  	}
				} else {
					// echo "Create Customer Payment Profile: ERROR Invalid response\n";
					$errorMessages = $response->getMessages()->getMessage();
					// echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
				 	
				 	return ['status'=>false,
				 			'message'=>"Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText()]; 
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
			$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
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
			  		
			  		return ['status'=>true,
			  				'root_payment_id'=>($root_payment_id) ? $root_payment_id : $payment_id];
			  	}
			  
			} else {
				$errorMessages = $response->getMessages()->getMessage();
				
				// Job::dump("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
				return Redirect::route('cards_add');
				return ['status'=>false,
						'message'=>"Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText()];
			}					
		}
    }

	static public function addAdminCard($form_data, $company_id, $root_payment_id, $customer_id) {
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
    	$customers = User::find($customer_id);
		$api_login_id = $companies->payment_api_login;
		$api_transaction_key = $companies->payment_gateway_id;
		$cards = new Card();
		$cards->user_id = $customer_id;
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
		$profiles = Profile::where('company_id',$company_id)->where('user_id',$customer_id)->get();
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
				// $paymentprofilerequest->setValidationMode("liveMode");
				$controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
				$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
				if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
					$root_payment_id = ($root_payment_id) ? $root_payment_id : $response->getCustomerPaymentProfileId();
				  	$cards->profile_id = $prev_profile_id;
				  	$cards->payment_id = $response->getCustomerPaymentProfileId();
				  	$cards->root_payment_id = $root_payment_id;
				  	$cards->status = 1;
				  	if ($cards->save()) {
				  		return ['status'=>true,
				  				'root_payment_id'=>$root_payment_id];
				  	}
				} else {
					// echo "Create Customer Payment Profile: ERROR Invalid response\n";
					$errorMessages = $response->getMessages()->getMessage();
					// echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
				 	
				 	return ['status'=>false,
				 			'message'=>"Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText()]; 
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
			$customerprofile->setMerchantCustomerId($customer_id);
			$customerprofile->setEmail($customers->email);
			$customerprofile->setDescription($customers->last_name.', '.$customers->first_name.' ~ '.strtotime(date('Y-m-d H:i:s')));
			$customerprofile->setPaymentProfiles($paymentprofiles);

			$request = new AnetAPI\CreateCustomerProfileRequest();
			$request->setMerchantAuthentication($merchantAuthentication);
			$request->setRefId( $refId);
			$request->setProfile($customerprofile);
			// Job::dump($request);
			$controller = new AnetController\CreateCustomerProfileController($request);
			$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
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
			  	$prof->user_id = $customer_id;
			  	$prof->profile_id = $response->getCustomerProfileId();
			  	$prof->status = 1;
			  	$prof->save();
			  	if ($cards->save()) {
			  		
			  		return ['status'=>true,
			  				'root_payment_id'=>($root_payment_id) ? $root_payment_id : $payment_id];
			  	}
			  
			} else {
				$errorMessages = $response->getMessages()->getMessage();
				
				// Job::dump("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n");
				return Redirect::route('cards_add');
				return ['status'=>false,
						'message'=>"Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText()];
			}					
		}
    }


    static public function getUserId($root_payment_id) {
    	$user_id = false;
    	$cards = Card::where('root_payment_id',$root_payment_id)->get();
    	if (count($cards) > 0) {
    		foreach ($cards as $card) {
    			$user_id = $card->user_id;
    		}
    	}

    	return $user_id;
    }

    static public function prepareForAdminView($data) {
    	$companies = Company::find(1); #todo fix this issue later for now hard code ydc company
    	$cards_data = [];
    	if (count($data) > 0) {
    		foreach ($data as $key => $card) {
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
				$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
				if(($response != null)){
					if ($response->getMessages()->getResultCode() == "Ok")
					{
						$card_number = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber();
						$card_type = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardType();
						$cards_data[$key]['card_number'] = $card_number;
						$cards_data[$key]['card_type'] = isset($card_type) ? $card_type : 'Visa';
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

    	return $cards_data;  	
    }

    static public function getCardInfo($company_id, $profile_id, $payment_id) {
    	$info = [];

    	$companies = Company::find($company_id);
        $payment_api_login = $companies->payment_api_login;
        $payment_api_password = $companies->payment_gateway_id;
		// Common setup for API credentials (merchant)
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName($payment_api_login);
		$merchantAuthentication->setTransactionKey($payment_api_password);
		$refId = 'ref' . time();

		//request requires customerProfileId and customerPaymentProfileId
		$request = new AnetAPI\GetCustomerPaymentProfileRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId( $refId);
		$request->setCustomerProfileId($profile_id);
		$request->setCustomerPaymentProfileId($payment_id);

		$controller = new AnetController\GetCustomerPaymentProfileController($request);
		$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
		if(($response != null)){
			if ($response->getMessages()->getResultCode() == "Ok") {
				$info = [
					'status' => true,
					'last_four' => $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber()
				];
			} else {
				$errorMessages = $response->getMessages()->getMessage();
				$info = [
					'status' => false,
					'message' => $errorMessages[0]->getText()
				];
				// echo "GetCustomerPaymentProfile ERROR :  Invalid response\n";
				// $errorMessages = $response->getMessages()->getMessage();
			 //    echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
			}
		} else {  
			echo "NULL Response Error";
			$info = [
				'status' => false,
				'message' => 'Response Error'
			];
		}

		return $info;
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
		$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

		if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
			return true;
		} else {	
			return false;
		}

    }

    static public function makeVoid($company_id, $transaction_id) {
    	$void_status = ['status'=>false,'message'=>''];
    	$companies = Company::find($company_id);
        $payment_api_login = $companies->payment_api_login;
        $payment_api_password = $companies->payment_gateway_id;
		// Common setup for API credentials
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName($payment_api_login);
		$merchantAuthentication->setTransactionKey($payment_api_password);
		$refId = 'ref' . time();

		// Create the payment data for a credit card
		// $creditCard = new AnetAPI\CreditCardType();
		// $creditCard->setCardNumber("4111111111111111" );
		// $creditCard->setExpirationDate("2038-12");
		// $paymentOne = new AnetAPI\PaymentType();
		// $paymentOne->setCreditCard($creditCard);
		//create a transaction
		$transactionRequestType = new AnetAPI\TransactionRequestType();
		$transactionRequestType->setTransactionType( "voidTransaction"); 
		// $transactionRequestType->setPayment($paymentOne);
		$transactionRequestType->setRefTransId($transaction_id);

		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId($refId);
		$request->setTransactionRequest( $transactionRequestType);
		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

		if ($response != null) {
			if($response->getMessages()->getResultCode() == "Ok") {
				$tresponse = $response->getTransactionResponse();

		  		if ($tresponse != null && $tresponse->getMessages() != null) {
					// echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
					// echo "Void transaction SUCCESS AUTH CODE: " . $tresponse->getAuthCode() . "\n";
					// echo "Void transaction SUCCESS TRANS ID  : " . $tresponse->getTransId() . "\n";
					// echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n"; 
					// echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
					$void_status['status'] = true;
				} else {
					$void_status['status'] = false;
					$void_status['message'] = $tresponse->getErrors()[0]->getErrorCode().' '.$tresponse->getErrors()[0]->getErrorText();
		  			// echo "Transaction Failed \n";
		  			// if($tresponse->getErrors() != null) {
					  //   // echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
					  //   // echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";    
					  //   // Job::dump('Error message: '.$tresponse->getErrors()[0]->getErrorText())        
		  			// }
				}
			} else {
				$void_status['status'] = false;
				
				// echo "Transaction Failed \n";
				$tresponse = $response->getTransactionResponse();
				if($tresponse != null && $tresponse->getErrors() != null) {
					// echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
					// echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n"; 
					$void_status['message'] =  $tresponse->getErrors()[0]->getErrorCode().' '.$tresponse->getErrors()[0]->getErrorText();                    
				} else {
					$void_status['message'] = $response->getMessages()->getMessage()[0]->getCode().' '.$response->getMessages()->getMessage()[0]->getText();
					// echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
					// echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
				}
			}      
		} else {
			// echo  "No response returned \n";
			$void_status['status'] = false;
			$void_status['message'] = "No response returned.";
		}

		return $void_status;
    }

    static public function makeRefund($company_id, $transaction_id) {

    }


}
