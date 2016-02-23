<?php

namespace App;
use App\Job;
use App\User;
use App\Invoice;
use App\Customer;
use App\Custid;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
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
        $special_param = substr($data, 0, 1);

        switch ($special_param) {
            case '!': // by invoice id
                $results = Customer::searchByInvoiceId($search);
                break;
            case '@': //by customer mark
                $results = Customer::searchByCustomerMark($search);
                break;

            case '#': //by phone number
                $results = Customer::searchByPhoneNumber($search);
                break;

            case '%': //by last name
                $results = Customer::searchByLastName($search);
                break;
            default: //return regular search
                $results = Customer::searchByQuery($search);
                break;
        }


        return $results;
    }

    private static function searchByQuery($query) {
        $results = [];
        if(is_numeric($query)) { //Is a number so start checking for lengths
            if(strlen($query) > 6) { // Must be a phone number
                $results = Customer::searchByPhoneNumber($query);
            } elseif(strlen($query) == 6) { //must be our invoice
                $results = Customer::searchByInvoiceId($query);
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
        $first = User::where('contact_phone',$query)->get();
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

            $second = User::where('contact_phone', 'like', '%'.$query.'%')->get();
            
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

            } elseif($count($second > 1)){
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

        Job::dump($results);

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
