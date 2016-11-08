<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

use App\Schedule;
use App\Job;
use App\Address;
use App\User;
use App\Card;
use App\Company;
use App\Customer;
use App\Custid;
use App\Delivery;
use App\Invoice;
use App\Layout;
use App\Transaction;
use App\Zipcode;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Geocoder;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class SchedulesController extends Controller
{
	public function __construct() {
    	$this->layout = 'layouts.dropoff';
    }

    public function getView($id = null, Request $request) {
        $schedules = Schedule::where('customer_id',$id)->where('status','<',12)->where('status','!=',6)->get();
        $request->session()->put('form_previous',['schedule_view',$id]);
        $active_list = Schedule::prepareSchedule($schedules);

        return view('schedules.view')
        ->with('layout',$this->layout)
        ->with('customer_id',$id)
        ->with('schedules',$active_list);
    }

    public function getChecklist(Request $request) {
        $this->layout = 'layouts.dropoff';
        $company_id = Auth::user()->company_id;

        $today = ($request->session()->has('delivery_date')) ? $request->session()->get('delivery_date') : date('Y-m-d 00:00:00');
        $pickups = Schedule::where('pickup_date',$today)
                             ->where('status',1)
                             ->orderBy('id','desc');

        $schedules = Schedule::where('dropoff_date',$today)
        					   ->whereIn('status',[4,5])
        					   ->union($pickups)
        					   ->orderBy('id','desc')
        					   ->get();
        $active_list = Schedule::prepareSchedule($schedules);


        $approved_pickup = Schedule::where('pickup_date',$today)
	    					   ->where('status',2)
	    					   ->orderBy('id','desc');        
        $approved = Schedule::where('dropoff_date',$today)
	    					   ->where('status',11)
	    					   ->orderBy('id','desc')
	    					   ->union($approved_pickup)
	    					   ->get();
       	$approved_list = Schedule::prepareSchedule($approved);
        $pickup_delayed = Schedule::where('pickup_date',$today)
                               ->whereIn('status',[7,8,9,10])
                               ->orderBy('id','desc');
        $delayed = Schedule::where('dropoff_date',$today)
                               ->whereIn('status',[7,8,9,10])
                               ->orderBy('id','desc')
                               ->union($pickup_delayed)
                               ->get();
        $delayed_list = Schedule::prepareSchedule($delayed);

        $paid_invoices = ($request->session()->has('paid_invoices')) ? $request->session()->get('paid_invoices') : false;


        return view('schedules.checklist')
        ->with('layout',$this->layout)
        ->with('delivery_date',date('D m/d/Y',strtotime($today)))
        ->with('schedules',$active_list)
        ->with('approved_list',$approved_list)
        ->with('delayed_list',$delayed_list)
        ->with('paid_invoices',$paid_invoices);
    }

    public function postCheckList(Request $request) {
    	$this->validate($request, [
            'search' => 'required'
        ]);

        $request->session()->put('delivery_date',date('Y-m-d 00:00:00',strtotime($request->search)));

        return Redirect::route('schedules_checklist');
    }

    public function getDeliveryRoute(Request $request) {
        $this->layout = 'layouts.dropoff';
        $today = ($request->session()->has('delivery_date')) ? $request->session()->get('delivery_date') : date('Y-m-d 00:00:00');
        $pickups = Schedule::where('pickup_date',$today)
        					   ->whereIn('status',[2,5,11])
        					   ->orderBy('id','desc');        
        $schedules = Schedule::where('dropoff_date',$today)
        					   ->whereIn('status',[2,5,11])
        					   ->orderBy('id','desc')
        					   ->union($pickups)
        					   ->get();

        $active_list = Schedule::prepareSchedule($schedules);
        $options = $request->session()->has('route_options') ? $request->session()->get('route_options') : false;
        
        $trip = Schedule::prepareTrip($schedules, $options);
        $dr = ($request->session()->has('delivery_route')) ? $request->session()->get('delivery_route') : [];
        if (count($schedules) > 0) {
            
            $check = false;
            if ($request->session()->has('delivery_route')) {
                // check to see if todays session has been made
                $check = ($dr[strtotime($today)]) ? false : true;
            } else {
                $check = true;
            }

            if ($check) {
                $client = new Client();
                // $res = $client->request('POST', 'https://api.routific.com/v1/vrp', [
                //  'headers' => [
                //      'Content-Type' => 'application/json',
                //      'Authorization' => 'bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1N2Q4ODdjZTJjOGE5MGZhNmMyNDY2YTAiLCJpYXQiOjE0NzM4MDgzMzR9.G-wRJ7Prih7MXp15vUv6T_mqDSd-nvzPnR4OA9PzjbY'
                //  ],
                //     'json' => $trip 
                // ]);
                $res = $client->request('POST', 'https://api.routific.com/v1/vrp', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1N2UxZGI0MDc2ZGFmYjZhMGE5NmIwNGUiLCJpYXQiOjE0NzQ0MTk1MjB9.6MbKPl0y7a-mWwEtaRwqqmx2pA-6kXGZS8MJlv1gbFE'
                    ],
                    'json' => $trip 
                ]);



                $body = json_decode($res->getBody()->read(1024));
                
                $dr[strtotime($today)] = Schedule::prepareRouteForView($body,$active_list);
                $request->session()->put('delivery_route',$dr);
            } 



    	} else {
    		$body = false;
    		$dr = false;
    	}
    	$pickup_approved = Schedule::where('pickup_date',$today)
        					   ->whereIn('status',[3,12])
        					   ->orderBy('id','desc');
        $approved = Schedule::where('dropoff_date',$today)
        					   ->whereIn('status',[3,12])
        					   ->orderBy('id','desc')
        					   ->union($pickup_approved)
        					   ->get();
       	$approved_list = Schedule::prepareSchedule($approved);

       	$pickup_delayed = Schedule::where('pickup_date',$today)
        					   ->whereIn('status',[7,8,9,10])
        					   ->orderBy('id','desc');
        $delayed = Schedule::where('dropoff_date',$today)
        					   ->whereIn('status',[7,8,9,10])
        					   ->orderBy('id','desc')
        					   ->union($pickup_delayed)
        					   ->get();
       	$delayed_list = Schedule::prepareSchedule($delayed);

       	$traffic = [
       		'very slow' => 'Very Slow',
       		'slow' => 'Slow',
       		'normal' => 'Normal',
       		'fast' => 'Fast',
       		'faster' => 'Faster'
       	];

       	$shortest_distance = [
       		'false' => 'Shortest Time',
       		'true' => 'Shortest Distance'
       	];


       	$traffic_selected = ($options) ? $options['traffic'] : 'slow';
       	$shortest_distance_selected = ($options) ? $options['shortest_distance'] : 'false';
       	$route_options_header = ($options) ? ($options['shortest_distance'] == 'false') ? 'Route Optimized For Time' : 'Route Optimized For Distance' : 'Route Optimized For Time';


        return view('schedules.delivery_route')
        ->with('layout',$this->layout)
        ->with('schedules',$dr)
        ->with('approved_list',$approved_list)
        ->with('delivery_date',date('D m/d/Y',strtotime($today)))
        ->with('traffic',$traffic)
        ->with('shortest_distance',$shortest_distance)
        ->with('traffic_selected',$traffic_selected)
        ->with('shortest_distance_selected',$shortest_distance_selected)
        ->with('route_options_header',$route_options_header)
        ->with('travel_data',$body)
        ->with('delayed_list',$delayed_list);    	
    }

    public function postDeliveryRoute(Request $request) {
    	$this->validate($request, [
            'search' => 'required'
        ]);

        $request->session()->put('delivery_date',date('Y-m-d 00:00:00',strtotime($request->search)));

        return Redirect::route('schedules_delivery_route');
    }

    public function postApprovePickup(Request $request){
    	$next_status = 2;

    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;


    	if ($schedules->save()) {

            Flash::success('Updated #'.$request->id.' to "En-route to pickup"');
    		return Redirect::route('schedules_checklist');
    	}

    }
    public function postApproveDropoff(Request $request){
    	$next_status = 11;
    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;

    	if ($schedules->save()) {

    		Flash::success('Updated #'.$request->id.' to "En-route to dropoff"');
    		return Redirect::route('schedules_checklist');
    	}
    }
    public function postRevertPickup(Request $request){
    	$next_status = 1;

    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;

    	if ($schedules->save()) {
    		Flash::success('Reverted #'.$request->id.' to "Delivery Scheduled"');
    		return Redirect::route('schedules_checklist');
    	}

    }
    public function postRevertDropoff(Request $request){
    	$next_status = 5;
    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;

    	if ($schedules->save()) {
    		Flash::success('Reverted #'.$request->id.' to "Processing"');
    		return Redirect::route('schedules_checklist');
    	}
    }

    public function postEmailStatus(Request $request) {
        $schedules = Schedule::where('id',$request->id)->get();
        $adjust = Schedule::prepareSchedule($schedules);
        if (count($adjust) > 0) {
            foreach ($adjust as $schedule) {
                $send_to = $schedule['email'];
                $from = 'noreply@jayscleaners.com';
                $subject = $schedule['email_subject'];

                $optional = ($request->content) ? $request->content : false;
                // Email customer
                if (Mail::send('emails.status_update', [
                    'company_name' => $schedule['company_name'],
                    'status_message' => $schedule['status_message'],
                    'greeting' => $schedule['email_greetings'],
                    'body' => $schedule['email_body'],
                    'button' => $schedule['email_button'],
                    'optional' => $optional,
                    'body_2' => (isset($schedule['email_body_2'])) ? $schedule['email_body_2'] : false,
                    'button_2' => (isset($schedule['email_button_2'])) ? $schedule['email_button_2'] : false
                ], function($message) use ($send_to,$subject)
                {
                    $message->to($send_to);
                    $message->subject($subject);
                })) {
                    // redirect with flash
                    Flash::success('Sent status update email.');
                    
                } else {
                    Flash::error('Failed email. Please try again.');
                }

                
            }
        } else {
            Flash::error('No such delivery on file. Could not send status update email.');
        } 

        return Redirect::back();

    }
    public function postApprovePickedup(Request $request){
    	$next_status = 3;
    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;

    	if ($schedules->save()) {
            # remove the schedule id from the seession array
            $today = ($request->session()->has('delivery_date')) ? strtotime($request->session()->get('delivery_date')) : strtotime(date('Y-m-d 00:00:00'));
            $dr = ($request->session()->has('delivery_route')) ? $request->session()->get('delivery_route') : false;
            if ($dr[$today]) {
                // parse through the session and remove saved items
                if (count($dr[$today])) {
                    foreach ($dr[$today] as $key => $value) {
                        if ($value['id'] == $request->id){
                            unset($dr[$today][$key]);
                        }
                    }
                }
            }
            $request->session()->put('delivery_route',$dr);

    		Flash::success('Updated #'.$request->id.' to "Picked Up"');
    		return Redirect::route('schedules_delivery_route');
    	}
    }
    public function postApproveDroppedOff(Request $request){
        $next_status = 12;
        $schedules = Schedule::find($request->id);
        $schedules->status = $next_status;

        if ($schedules->save()) {
            # remove the schedule id from the seession array
            $today = ($request->session()->has('delivery_date')) ? strtotime($request->session()->get('delivery_date')) : strtotime(date('Y-m-d 00:00:00'));
            $dr = ($request->session()->has('delivery_route')) ? $request->session()->get('delivery_route') : false;
            if ($dr[$today]) {
                // parse through the session and remove saved items
                if (count($dr[$today])) {
                    foreach ($dr[$today] as $key => $value) {
                        if ($value['id'] == $request->id){
                            unset($dr[$today][$key]);
                        }
                    }
                }
            }
            $request->session()->put('delivery_route',$dr);
            Flash::success('Updated #'.$request->id.' to "Dropped Off / Completed"');
            return Redirect::route('schedules_delivery_route');
        }
    }
    public function postApproveDelivered(Request $request){
    	$next_status = 5;
    	$schedules = Schedule::find($request->id);
    	$schedules->status = $next_status;
    	// email customer


    	// finish status change
    	if ($schedules->save()) {
    		Flash::success('Updated #'.$request->id.' to "Delivered"');
    		return Redirect::route('schedules_delivery_route');
    	}
    }

    public function postRouteOptions(Request $request) {
    	$request->session()->put('route_options',[
			"traffic" => $request->traffic,
			"min_visits_per_vehicle"=> 1,
			"balance"=> 'true',
			"min_vehicles"=> 'true',
			"shortest_distance"=> $request->shortest_distance
    	]);

    	Flash::success('Updated route settings!');
    	return Redirect::route('schedules_delivery_route');
    }

    public function postDelayDelivery(Request $request) {
    	$this->validate($request, [
            'reason' => 'required'
        ]);    	
        $schedule_id = $request->id;
        $status = $request->reason;
        $schedules = Schedule::find($schedule_id);
        $schedules->status = $status;
        if ($schedules->save()) {
            # remove the schedule id from the seession array
            $today = ($request->session()->has('delivery_date')) ? strtotime($request->session()->get('delivery_date')) : strtotime(date('Y-m-d 00:00:00'));
            $dr = ($request->session()->has('delivery_route')) ? $request->session()->get('delivery_route') : false;
            if ($dr[$today]) {
                // parse through the session and remove saved items
                if (count($dr[$today])) {
                    foreach ($dr[$today] as $key => $value) {
                        if ($value['id'] == $request->id){
                            unset($dr[$today][$key]);
                        }
                    }
                }
            }
            $request->session()->put('delivery_route',$dr);

        	// send email

        	//redirect back
        	Flash::warning('Sent #'.$schedule_id.' to the delayed folder.');
        	return Redirect::back(); 
        }


    }

    public function postRevertDelay(Request $request) {
    	$schedule_id = $request->id;
    	$old_status = $request->status;
    	switch($old_status) {
            case 2:
                $new_status = 1;
            break;
            case 3:
                $new_status = 2;
            break;
            case 4: 
                $new_status = 3;
            break;
    		case 7: // Delayed - Processing not complete
    			$new_status = 4;
    		break;

    		case 8: // Delayed - Customer unavailable for pickup
    			$new_status = 2;
    		break;

    		case 9: //Delayed - Customer unavailable for dropoff
    			$new_status = 11;
    		break;

    		case 10: //Delayed - Card on file processing error
    			$new_status = 4;
    		break;
    	}

    	$schedules = Schedule::find($schedule_id);
    	$schedules->status = $new_status;
    	if ($schedules->save()) {
            # remove the schedule id from the seession array
            $today = ($request->session()->has('delivery_date')) ? strtotime($request->session()->get('delivery_date')) : strtotime(date('Y-m-d 00:00:00'));
            $dr = ($request->session()->has('delivery_route')) ? $request->session()->get('delivery_route') : false;
            if ($dr[$today]) {

                if (count($dr[$today])) {
                    unset($dr[$today]);
                }
            }
            $request->session()->put('delivery_route',$dr);
    		Flash::warning('Successfully reverted #'.$schedule_id.' back.');
    		return Redirect::back();
    	}
    }

    public function getProcessing(Request $request) {
        $this->layout = 'layouts.dropoff';
        $today = ($request->session()->has('delivery_date')) ? $request->session()->get('delivery_date') : date('Y-m-d 00:00:00');
        
        $schedules = Schedule::where('pickup_date',$today)
        					   ->where('status',3)
        					   ->orderBy('id','desc')
        					   ->get();

        $active_list = Schedule::prepareSchedule($schedules);

        $processing = Schedule::where('pickup_date',$today)
        					   ->where('status',4)
        					   ->orderBy('id','desc')
        					   ->get();
       	$processing_list = Schedule::prepareSchedule($processing);

        return view('schedules.processing')
        ->with('layout',$this->layout)
        ->with('schedules',$active_list)
        ->with('processing_list',$processing_list)
        ->with('processing_date',date('D m/d/Y',strtotime($today)));  
    }

    public function postProcessing(Request $request) {
    	$this->validate($request, [
            'search' => 'required'
        ]);

        $request->session()->put('processing_date',date('Y-m-d 00:00:00',strtotime($request->search)));

        return Redirect::route('schedules_processing');    	
    }

    public function postApproveProcessing(Request $request){
    	$schedule_id = $request->id;
    	$status = 4;
    	$schedules = Schedule::find($schedule_id);
    	$schedules->status = $status;
    	if($schedules->save()) {
    		Flash::success('Updated #'.$schedule_id.' to being "Processed"');
    		return Redirect::route('schedules_processing');
    	}
    }

    public function postSelectInvoiceRow(Request $request) {
        if ($request->ajax()) {
            $schedule_id = $request->schedule_id;
            $invoice_id = $request->invoice_id;


            $invoices = Invoice::find($invoice_id);
            $invoices->schedule_id = $schedule_id;
            if ($invoices->save()) {
                $status = true;
                $invs = Invoice::where('schedule_id',$schedule_id)->get();
                $totals = Invoice::prepareTotals($invs);

            } else {
                $status = false;
                $totals = [];
            }
            return response()->json(['status'=>$status,
                                     'totals'=>$totals]);
        }
    
    }

    public function postRemoveInvoiceRow(Request $request) {
        if ($request->ajax()) {
            $schedule_id = $request->schedule_id;
            $invoice_id = $request->invoice_id;


            $invoices = Invoice::find($invoice_id);
            $invoices->schedule_id = NULL;
            if ($invoices->save()) {
                $status = true;
                $invs = Invoice::where('schedule_id',$schedule_id)->get();
                $totals = Invoice::prepareTotals($invs);
                $paid_invoices = ($request->session()->has('paid_invoices')) ? $request->session()->get('paid_invoices') : [];

            } else {
                $totals = [];
                $status = false;
            }
            return response()->json(['status'=>$status,
                                     'totals'=>$totals]);
        }
    }

    public function postPayment(Request $r) {
        $schedule_id = $r->id;

        $schedules = Schedule::find($schedule_id);
        $company_id = $schedules->company_id;
        $customer_id = $schedules->customer_id;
        $current_paid = ($r->session()->has('current_paid')) ? $r->session()->get('curent_paid') : [];
        

        $valid_card_check = false; // Check for valid card start with false
        if (isset($schedules->card_id)) { // use the card selected by customer first
            $cards = Card::find($schedules->card_id);
            $profile_id = $cards->profile_id;
            $payment_id = $cards->payment_id;
            $valid_card_check = Card::checkValid($company_id, $profile_id, $payment_id);
        } else { // if no card was selected use an alternate card
            $cards = Card::where('user_id',$customer_id)->where('company_id',$company_id)->get();
            if (count($cards) > 0) {
                foreach ($cards as $card) {
                    $profile_id = $card->profile_id;
                    $payment_id = $card->payment_id;
                    $valid_card_check = Card::checkValid($company_id, $profile_id, $payment_id);
                    if ($valid_card_check) {
                        break;
                    }
                }
            }
            
        }

        // Card is not valid error
        if (!$valid_card_check) {
            Flash::error('Credit card on file did not validate. Please contact customer for new card and reschedule delivery.');
            return Redirect::back();
        }


        // get invoice totals for this schedule_id
        $invoices = Invoice::where('schedule_id',$schedule_id)->get();
        if (count($invoices) > 0) {
            $totals = Invoice::prepareTotals($invoices);

            $attempt_payment = Schedule::makePayment($company_id, $profile_id, $payment_id, $totals['total']);

            if ($attempt_payment['status']) {
                $schedules->status = 5;
                if ($schedules->save()) {
                    // save transaction information
                    $transactions = new Transaction();
                    $transactions->company_id = $company_id;
                    $transactions->customer_id = $customer_id;
                    $transactions->schedule_id = $schedule_id;
                    $transactions->pretax = $totals['subtotal'];
                    $transactions->tax = $totals['tax'];
                    $transactions->aftertax = $totals['total'];
                    $transactions->total = $totals['total'];
                    $transactions->type = 2;
                    $transactions->tendered = $totals['total'];
                    $transactions->transaction_id = $attempt_payment['trans_id'];
                    $transactions->status = 1;

                    // update invoices with transaction id
                    if ($transactions->save()) {
                        $transaction_id = $transactions->id;
                        foreach ($invoices as $invoice) {
                            $invoice_id = $invoice->id;
                            $s_invs = Invoice::find($invoice_id);
                            $s_invs->transaction_id = $transaction_id;
                            $s_invs->status = 5; // paid and done
                            $s_invs->save();
                        }

                        Flash::success('Successfully completed online payment for #'.$schedule_id.'.');
                        return Redirect::back();

                    }
                }
            } else {
                
                $schedules->status = 10;
                if ($schedules->save()) {
                    Flash::error('Error: '.$attempt_payment['error_message']);
                    return Redirect::back();
                }
            }

        } else {
            Flash::error('You have not selected any invoices cannot charge a $0 balance. Please select the appropriate invoice(s) and try again.');
            return Redirect::back();
        }

    }

    public function postRevertPayment(Request $request) {
        $schedule_id = $request->id;
        $schedules = Schedule::find($schedule_id);
        $company_id = $schedules->company_id;
        $customer_id = $schedules->customer_id;
        $transactions = Transaction::where('schedule_id',$schedule_id)->get();
        
        if (count($transactions) > 0) {
            foreach ($transactions as $transaction) {
                $trans = Transaction::find($transaction->id);
                $transaction_status = false;
                $transaction_id = $transaction->id;
                $total = $transaction->total;
                $payment_transaction_id = $transaction->transaction_id;

                // void the payment
                $void = Card::makeVoid($company_id, $payment_transaction_id);

                if ($void['status']) {
                    $transaction_status = true;
                    $trans->status = 2; // voided
                } else {
                    Flash::error('Void failure: '.$void['message']);
                    return Redirect::route('schedules_checklist');
                    $refund = Card::makeRefund($company_id, $payment_transaction_id);
                    if ($refund) {
                        $transaction_status = true;
                        $trans->status = 2; // refunded
                    } else {
                        Flash::error('Transaction could not be voided / refunded. Please manually refund.');
                        return Redirect::back();
                    }
                }
                // revert the status and remove the transaction
                // $transaction_status = true;
                if ($transaction_status) {
                    if ($trans->save()) {
                        // update invoices remove transaction_ids and change status back to 1
                        $invs = Invoice::where('transaction_id',$transaction_id)->get();
                        if (count($invs) > 0 ) {
                            foreach ($invs as $inv) {
                                $invoice_id = $inv->id;
                                $in = Invoice::find($invoice_id);
                                $in->status = 1;
                                $in->transaction_id = NULL;
                                $in->save();
                            }
                        }

                        // update schedule set staus to 4
                        $schedules->status = 4;
                        if ($schedules->save()) {
                            // successfully reverted back payment
                            Flash::success('Successfully voided / refunded '.money_format('$%i', $total).' to schedule #'.$schedule_id.'!');
                            return Redirect::back();
                        }


                    }
                }
            }
        }
    }




}
