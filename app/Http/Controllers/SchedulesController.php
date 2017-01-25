<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Validator;
use Redirect;
use Hash;
use Exception;
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
use App\Droute;
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

        if ($request->session()->has('delivery_route')) {
            $request->session()->pull('delivery_route');
        }

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

    // Droutes
    public function getPrepareRoute(Request $request) {
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

        if (count($schedules) > 0) {
            $idx = 0;
            foreach ($schedules as $schedule) {
                $schedule_id = $schedule->id;
                $d_check = Droute::where('schedule_id',$schedule_id)->get();
                if (count($d_check) == 0) {
                    $idx++;
                    $d = new Droute();
                    $d->company_id = Auth::user()->company_id;
                    $d->schedule_id = $schedule->id;
                    $d->delivery_date = $today;
                    $d->ordered = $idx;
                    $d->status = 1;
                    $d->save();
                }
            }
        }
        $del_routes = Droute::where('delivery_date',$today)
            ->where('employee_id',NULL)
            ->orderBy('ordered','asc')
            ->get();

        $drs = Droute::where('delivery_date',$today)
            ->where('employee_id','!=',NULL)
            ->orderBy('ordered','asc')
            ->get();
        $droutes = Droute::prepareRoutes($drs);
        
        $schedule_ids = [];
        if (count($del_routes) > 0) {
            foreach ($del_routes as $dr) {
                array_push($schedule_ids, $dr->schedule_id);  
            }
            $schs = Schedule::whereIn('id',$schedule_ids)->get();
        } else {
            $schs = [];
        }
        
        $setup = Schedule::prepareRouteSetup($schs);
        $drivers = Schedule::prepareDrivers(User::where('role_id','<',5)->get());
        $check = Schedule::prepareSchedule($schs);
        return view('schedules.prepare_route')
            ->with('layout',$this->layout)
            ->with('setup',$setup)
            ->with('drivers',$drivers)
            ->with('droutes',$droutes)
            ->with('check',$check);

    }

    public function postSetupRoute(Request $request) {
        $schedule_id = $request->id;
        $employee_id = $request->employee_id;
        $delivery_date = $request->session()->get('delivery_date');

        $orders = Droute::where('delivery_date',$delivery_date)
            ->where('employee_id',$employee_id)
            ->where('ordered','>',0)
            ->orderBy('ordered','desc')
            ->limit(1)
            ->get();
        $ordered = 1;
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $ordered = $order->ordered + 1;
            }
        }
        $droutes = Droute::where('schedule_id',$schedule_id)->get();
        if (count($droutes) > 0) {
            foreach ($droutes as $droute) {
                $dr = Droute::find($droute->id);
                $dr->employee_id = $employee_id;
                $dr->ordered = $ordered;
                if ($dr->save()) {
                    Flash::success('Successfully setup route with driver. You may update order of route or download csv file below.');
                    return Redirect::back();
                }
            }
        } else {
            Flash::error('Could not find corresponding schedule, please contact administrator or reset schedule.');
            return Redirect::back();
        }        

    }

    public function postRevertSchedule(Request $request) {
        $schedule_id = $request->id;
        $droutes = Droute::where('schedule_id',$schedule_id)->get();
        if (count($droutes) > 0) {
            foreach ($droutes as $droute) {
                $dr = Droute::find($droute->id);
                $employee_id = $dr->employee_id;
                $dr->employee_id = NULL;
                $dr->ordered = NULL;
                $delivery_date = $dr->delivery_date;
                if ($dr->save()) {
                    // Reindex
                    $del_dates = Droute::where('delivery_date',$delivery_date)
                        ->where('employee_id',$employee_id)
                        ->orderBy('ordered','asc')
                        ->get();

                    if (count($del_dates) > 0) {
                        $idx = 0;
                        foreach ($del_dates as $dd) {
                            $idx++;
                            $drs = Droute::find($dd->id);
                            $drs->ordered = $idx;
                            $drs->save();
                            Job::dump('reindexing '.$idx);
                        }
                    }
                    Flash::success('Successfully reverted the delivery schedule.');
                    return Redirect::back();
                }
            }
        } else {
            Flash::warning('No such delivery schedule. Please try again.');
            return Redirect::back();
        }
    }

    public function postSort(Request $request) {
        if ($request->ajax()) {
            $data = $request->sort;
            if (count($data) > 0) {
                foreach ($data as $key => $value) {
                    $idx = $key + 1;
                    $schedule_id = $value;
                    $droutes = Droute::where('schedule_id',$schedule_id)->get();
                    if (count($droutes) > 0) {
                        foreach ($droutes as $droute) {
                            $dd_id = $droute->id;
                            $dds = Droute::find($dd_id);
                            $dds->ordered = $idx;
                            $dds->save();
                        }
                    }
                }
            }
        }
    }

    public function getRouteCSV(Request $request,$id = null) {
        if (isset($id)) {
            $delivery_date = $request->session()->get('delivery_date');
            $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
            $droutes = Droute::where('employee_id',$id)
                ->where('delivery_date',$delivery_date)
                ->orderBy('ordered','asc')
                ->get();
            // $csv->insertOne(\Schema::getColumnListing('droutes')); // optional change for map
            $csv->insertOne(['First Name','Last Name','Phone','Street','City','State','Zipcode']);
            if (count($droutes) > 0) {
                foreach ($droutes as $droute) {
                    $schedule_id = $droute->schedule_id;
                    $schedules = Schedule::find($schedule_id);
                    $dropoff_address = $schedules->dropoff_address;
                    $addresses = Address::find($dropoff_address);
                    $street = $addresses->street;
                    $city = $addresses->city;
                    $state = $addresses->state;
                    $zipcode = $addresses->zipcode;
                    $customer_id = $schedules->customer_id;
                    $users = User::find($customer_id);
                    $first_name = ucFirst($users->first_name);
                    $last_name = ucFirst($users->last_name);
                    $phone = Job::formatPhoneString($users->phone);
                    $data = [$first_name,$last_name,$phone,$street,$city,$state,$zipcode];
                    $csv->insertOne($data);
                }
                $title = 'route-'.strtotime(date('Y-m-d H:i:s')).'.csv';
                $csv->output($title);
            } else {
                Flash::warning('There are no stops for this driver. Please select routes then try again.');
                return Redirect::back();
            }
            
            
            
        } else {
            Flash::warning('No such employee_id. Please select a proper employee route and try again.');
            return Redirect::back();
        }
        
    }

    public function postPrepareRoute(Request $request) {

    }


    public function getDeliveryRoute(Request $request, $id = null) {
        $this->layout = 'layouts.dropoff';
        $employee_id = $id;
        if (isset($employee_id)) {
            $employees = User::find($employee_id);
            $today = ($request->session()->has('delivery_date')) ? $request->session()->get('delivery_date') : date('Y-m-d 00:00:00');
            $schedule_ids = [];
            $droutes = Droute::where('employee_id',$employee_id)
                ->where('delivery_date',$today)
                ->where('status',1)
                ->orderBy('ordered','asc')
                ->get();
            if (count($droutes) > 0) {
                foreach ($droutes as $droute) {
                    $schedule_id = $droute->schedule_id;
                    array_push($schedule_ids, $schedule_id);

                }
            }

            $schs = [];
            $active_list = [];
            if (count($schedule_ids)) {
                foreach ($schedule_ids as $key => $value) {
                    array_push($schs,Schedule::find($value));
                }
                $active_list = Schedule::prepareSchedule($schs);
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

            $delayed_list = (count($delayed) > 0) ? Schedule::prepareSchedule($delayed) : [];
            Job::dump($delayed_list);
            $setup = Schedule::prepareRouteSetup($schs);

            return view('schedules.delivery_route')
            ->with('layout',$this->layout)
            ->with('schedules',$active_list)
            ->with('setup',$setup)
            ->with('approved_list',$approved_list)
            ->with('delivery_date',date('D m/d/Y',strtotime($today)))
            ->with('delayed_list',$delayed_list);       
        } 
        Flash::warning('No such employee. Cannot create route');
        return Redirect::back();
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

            // update droutes
            $droutes = Droute::where('schedule_id',$request->id)->get();
            if (count($droutes) > 0) {
                foreach ($droutes as $droute) {
                    $dds = Droute::find($droute->id);
                    $dds->status = 2;
                    $dds->save();
                }
            }

    		Flash::success('Updated #'.$request->id.' to "Picked Up"');
    		return Redirect::back();
    	}
    }
    public function postApproveDroppedOff(Request $request){
        $next_status = 12;
        $schedules = Schedule::find($request->id);
        $schedules->status = $next_status;

        if ($schedules->save()) {
            // update droutes
            $droutes = Droute::where('schedule_id',$request->id)->get();
            if (count($droutes) > 0) {
                foreach ($droutes as $droute) {
                    $dds = Droute::find($droute->id);
                    $dds->status = 2;
                    $dds->save();
                }
            }
            Flash::success('Updated #'.$request->id.' to "Dropped Off / Completed"');
            return Redirect::back();
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
            // update droutes
            $droutes = Droute::where('schedule_id',$schedule_id)->get();
            if (count($droutes) > 0) {
                foreach ($droutes as $droute) {
                    $dds = Droute::find($droute->id);
                    $dds->status = 2;
                    $dds->save();
                }
            }

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
                $new_status = 2;
            break;
            case 3:
                $new_status = 3;
            break;
            case 4: 
                $new_status = 4;
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
            
            // update droutes
            $droutes = Droute::where('schedule_id',$request->id)->get();
            if (count($droutes) > 0) {
                foreach ($droutes as $droute) {
                    $dds = Droute::find($droute->id);
                    $dds->status = 1;
                    $dds->save();
                }
            }

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

    public function postAdminCancel(Request $request) {
        $schedule_id = $request->id;
        $schedules = Schedule::find($schedule_id);
        if ($schedules->delete()) {
            Flash::success('Successfully deleted schedule #'.$schedule_id.' from the server.');
            return Redirect::route('delivery_overview');
        }
    }




}
