<?php

namespace App;
use App\Job;
use App\User;
use App\Invoice;
use App\Customer;
use App\Custid;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Customer extends Model
{
    use SoftDeletes;
    public static function prepareLast10($user, $last10) {

        $last10 = (count($last10) > 0) ? $last10 : [];

        if($user){
            //first check to see if the user is already inside of the array
            //if so then remove the user from the current standing and place him on top 
            if(count($last10) >0) {
                foreach ($last10 as $key => $value) {
                    foreach ($value as $skey => $svalue) {
                        if($skey == $user->user_id){

                            unset($last10[$key]);
                            break;
                        }
                    }
                }
            }
           

            //next if there are no users add in the user to the top of the array and remove the last user from the list 
            //maintaining a count of 10 always.
            $user_id = $user->user_id;
            array_unshift($last10, [$user_id=>$user]);
            unset($last10[10]);

        }


        return $last10;
    }

    /**
    * Look for special search characters first then if none then go with regular search
    * ! => invoice id
    * @ => customer id
    * @param string
    * @return array
    */

    public static function prepareResults($data) {
        $results = [];
        //Sanitize the data
        $search = preg_replace("/[^A-Za-z0-9 ]/", '', $data);
        // singlebyte strings
        $special_param = substr($data, 0, 2);

        switch ($special_param) {
            case 'i%': // by invoice id
                $results = Customer::searchByInvoiceId($search);
                break;
            case 'm%': //by customer mark
                $results = Customer::searchByCustomerMark($search);
                break;

            case 'p%': //by phone number
                $results = Customer::searchByPhoneNumber($search);
                break;

            case 'n%': //by last name
                $results = Customer::searchByLastName($search);
                break;
            default: //return regular search
                $results = Customer::searchByQuery($search);
                break;
        }


        return $results;
    }

    public static function prepareView($data){
        if(isset($data['company_id'])){
            switch($data['company_id']) {
                case 1: //Montlake
                    $data['company_name'] = 'Montlake';
                break;

                case 2: //Roosevelt
                    $data['company_name'] = 'Roosevelt';
                break;

                default: //TBD
                    $data['company_name'] = 'TBD';
                break;
            }
        }

        if(isset($data['shirt'])){
            switch ($data['shirt']) {
                case 1: //No Starch
                    $data['shirt'] = 'Hanger';
                    break;
                case 2: //Light
                    $data['shirt'] = 'Folded';
                    break;

                    $data['shirt'] = 'Hanger';
                    break;
            }
        }
        if(isset($data['starch'])){
            switch ($data['starch']) {
                case 1: //No Starch
                    $data['starch'] = 'None';
                    break;
                case 2: //Light
                    $data['starch'] = 'Light';
                    break;
                case 3: //Medium
                    $data['starch'] = 'Medium';
                    break;
                case 4: //Heavy
                    $data['starch'] = 'Heavy';
                    break;
                default:
                    $data['starch'] = 'None';
                    break;
            }
        }
        if(isset($data['account'])){
            $data['account'] = ($data['account'] == true) ? 'Yes' : 'No';
        }

        if(isset($data['delivery'])){
            $data['delivery'] = ($data['delivery'] == true) ? 'Yes' : 'No';
        }

        if(isset($data['id'])) {
            $data['marks'] = Custid::where('customer_id',$data['id'])->get();
        }

        if (isset($data['phone'])) {
            $data['phone_raw'] = $data['phone'];
            $data['phone'] = Job::formatPhoneString($data['phone']);

        }

        return $data;
    }

    public static function prepareAccountHistory($id, $status = null, $operand = null) {
        $history = [
            'total'=>0,
            'transactions'=>false
        ];

        $customers = User::find($id);
        $history['total'] = $customers->account_total;
        if (isset($status) and isset($operand)) {
            $transactions = Transaction::where('customer_id',$id)
                ->where('status',$operand,$status)
                ->orderBy('id','desc')
                ->get();
        } elseif(isset($status) && !isset($operand)) {
            $transactions = Transaction::where('customer_id',$id)
                ->where('status',$status)
                ->orderBy('id','desc')
                ->get();
        } else {
            $transactions = Transaction::where('customer_id',$id)
                ->orderBy('id','desc')
                ->get();
        }
        
        if (count($transactions) > 0) {
            foreach ($transactions as $key => $value) {
                $invoices = Invoice::where('transaction_id',$value->id)->where('status',1)->get();
                $transactions[$key]['invoices'] = $invoices;

                if (isset($transactions[$key]['status'])){
                    switch($transactions[$key]['status']) {
                        case 3: // Account active transaction
                            $status = 'Active';
                            $background_color = 'success';
                        break;

                        case 2:
                            $status = 'To Be Paid';
                            $background_color = 'info';
                        break;

                        default:
                            $status = 'Completed';
                            $background_color = 'active';
                        break;
                    }

                    $transactions[$key]['status_html'] = $status;
                    $transactions[$key]['background_color'] = $background_color;
                }
            }
        }
        $history['transactions'] = $transactions;

        return $history;
    }


    /**
    * PRIVATE METHODS
    * 
    **/


    public static function searchByQuery($query) {
        $results = [];
        if(is_numeric($query)) { //Is a number so start checking for lengths
            if(strlen($query) > 6) { // Must be a phone number
                $results = Customer::searchByPhoneNumber($query);
            } elseif(strlen($query) == 6) { //must be our invoice
                $results = Customer::searchByInvoiceId($query);
            } elseif(strlen($query) >= 4 && strlen($query) < 6) { //must be customer id
                $results = Customer::searchById($query);

            } else { //must be our customer id
                $results = Customer::searchByCustomerMark($query);
            } 
        } else { // Non number so check for last names
            $results = Customer::searchByLastName($query);
        }
        return $results;
    }
    private static function searchByInvoiceId($query){
        $results = [];
        $invoices = Invoice::where('id',$query)->get();
        if(count($invoices) == 1){ // Only one invoice returned
            $results = [
                'status'=>true,
                'redirect'=>'invoices_view',
                'param'=>$query,                        
                'data'=>$invoices,
                'flash'=>'Successfully found invoice #'.$query
            ];
        } elseif(count($invoices) > 1) { // found multiple instances of search data

            $results = [
                'status'=>true,
                'redirect'=>'invoices_index_post',
                'param'=>$query,                        
                'data'=>$invoices,
                'flash'=>'Successfully found '.count($invoices).' invoice(s) matching "'.$query.'"!'
            ];
        } else { // nothing found
            $results = [
                'status'=>false,
                'redirect'=>'invoices_index_post',
                'param'=>null,
                'flash'=>'No such invoice.'
            ];
        }   

        return $results;
    }

    private static function searchById($query){
        $results = [];
        $users = User::find($query);

        if(count($users) > 0){ // If only one customer is found
            $results = [
                'status'=>true,
                'redirect'=>'customers_view',
                'param'=>$query,
                'data'=>$users,
                'flash'=>'Found customer'
            ];
        } else { // else send the user to look for more customers
            $results = [
                'status'=>false,
                'redirect'=>'customers_index_post',
                'param'=>$query,
                'data'=>null,
                'flash'=>'No such customer.'
            ];
        }

        return $results;
    }

    private static function searchByCustomerMark($query){
        $results = [];
        $custids = Custid::where('mark',$query)->get();

        if(count($custids) == 1){ // If only one customer is found
            foreach ($custids as $cust) {
                $customer = User::find($cust->customer_id);
                break;
            }
            $results = [
                'status'=>true,
                'redirect'=>'customers_view',
                'param'=>$query,
                'data'=>$customer,
                'flash'=>'Found customer'
            ];
        } else { // else send the user to look for more customers
            $results = [
                'status'=>false,
                'redirect'=>'customers_index_post',
                'param'=>$query,
                'data'=>null,
                'flash'=>'No such customer.'
            ];
        }

        return $results;
    }

    private static function searchByPhoneNumber($query) {
        $results = [];
        $first = User::where('phone',$query)->get();
        if(count($first) >0) {
        	foreach ($first as $f) {
	            $results = [
	                'status'=>true,
	                'redirect'=>'customers_view',
	                'param' => $f->id,
	                'data' => $f
	            ];
        	}

        } else {

            $second = User::where('phone', 'like', '%'.$query.'%')->get();
            
            if(count($second) == 1) {
            
	        	foreach ($second as $s) {
		            $results = [
		                'status'=>true,
		                'redirect'=>'customers_view',
		                'param' => $s->id,
		                'data' => $s
		            ];
		            break;
	        	}

            } elseif(count($second) > 1){
            	foreach ($second as $s) {
	                $results = [
	                    'status'=>true,
	                    'redirect'=>'customers_index_post',
	                    'param'=>null,
	                    'data'=>$s
	                ];  
	                break;
	            }          	
            } else {
                $results = [
                    'status'=>false,
                    'redirect'=>'customers_index',
                    'param'=>null,
                    'data'=>$second
                ];            	
            }
        }


        return $results;
        
    }

    private static function searchByLastName($query) {
        $results = [];
        $first = User::where('last_name', 'like', '%'.$query.'%')->get();
        if(count($first)>0) {
            $results = [
                'status'=>true,
                'redirect'=>'customers_index_post',
                'param'=>null,
                'data'=>$first
            ];
        } else {
        	$results = [
        		'status'=>false,
        		'redirect'=>'',
        		'param'=>null,
        		'data'=>null
        	];
        }

        return $results;      
    }


}
