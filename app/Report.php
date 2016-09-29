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

class Report extends Model
{
    use SoftDeletes;

    static public function prepareDates() {
    	return [
    		'today' => [
    			'start' => date('D n/d/Y'),
    			'end'=>date('D n/d/Y')
    		],
    		'this_week' => [
    			'start'=>date("D n/d/Y", strtotime('monday this week', strtotime(date('Y-m-d H:i:s')))),
    			'end' => date("D n/d/Y", strtotime('saturday this week', strtotime(date('Y-m-d 23:59:59'))))
    		],
    		'this_month' => [
    			'start' => date('D n/d/Y',strtotime(date('Y-m-01 00:00:00'))),
    			'end' => date('D n/d/Y',strtotime(date('Y-m-t 00:00:00')))
    		],
    		'this_year' => [
    			'start' => date('D n/d/Y',strtotime('2016-01-01 00:00:00')),
    			'end' => date('D n/d/Y',strtotime('2016-12-31 23:59:59'))
    		],
    		'yesterday' => [
    			'start' => date('D n/d/Y',strtotime('-1 days')),
    			'end' => date('D n/d/Y',strtotime('-1 days'))
    		],
    		'last_week' => [
    			'start'=>date("D n/d/Y", strtotime('monday last week', strtotime(date('Y-m-d H:i:s')))),
    			'end' => date("D n/d/Y", strtotime('saturday last week', strtotime(date('Y-m-d 23:59:59'))))
    		],
    		'last_month' => [
    			'start' => date('D n/d/Y',strtotime('first day of previous month')),
    			'end' => date('D n/d/Y',strtotime('last day of previous month'))
    		],
    		'last_year' => [
    			'start' => date('D 1/1/Y',strtotime('-1 year')),
    			'end' => date('D 12/31/Y',strtotime('-1 year'))
    		]

    	];
    }

    static public function prepareGlimpse() {
        $reports = [];

        $companies = Company::all();

        $today_start = date('Y-m-d 00:00:00');
        $today_end = date('Y-m-d 23:59:59');
        $this_week_start = date("Y-m-d H:i:s", strtotime('monday this week', strtotime(date('Y-m-d 00:00:00'))));
        $this_week_end = date("Y-m-d H:i:s", strtotime('saturday this week', strtotime(date('Y-m-d 23:59:59'))));
        $this_month_start = date('Y-m-d H:i:s',strtotime(date('Y-m-01 00:00:00')));
        $this_month_end = date('Y-m-d H:i:s',strtotime(date('Y-m-t 23:59:59')));
        $this_year_start = date('Y-01-01 00:00:00');
        $this_year_end = date('Y-12-31 23:59:59');
        if (count($companies) > 0) {
            foreach ($companies as $company) {
                $company_id = $company->id;
                $today_transactions = Transaction::whereBetween('created_at',[$today_start,$today_end])->where('company_id',$company_id)->sum('total');
                $this_week_transactions = Transaction::whereBetween('created_at',[$this_week_start,$this_week_end])->where('company_id',$company_id)->sum('total');
                $this_month_transactions = Transaction::whereBetween('created_at',[$this_month_start,$this_month_end])->where('company_id',$company_id)->sum('total');
                $this_year_transactions = Transaction::whereBetween('created_at',[$this_year_start,$this_year_end])->where('company_id',$company_id)->sum('total');
                $reports[$company_id] = [
                    'name'=>$company->name,
                    'today'=>money_format('$%i',$today_transactions),
                    'this_week'=>money_format('$%i',$this_week_transactions),
                    'this_month'=>money_format('$%i',$this_month_transactions),
                    'this_year'=>money_format('$%i',$this_year_transactions)
                ];
            }
        }
        return $reports;
    }

    static public function prepareCompanies($data) {
    	$companies = [''=>'Select Company'];

    	if (count($data) > 0) {
    		foreach ($data as $company) {
    			$companies[$company->id] = $company->name;
    		}
    	}

    	return $companies;
    }

    static public function prepareQueryReport($start, $end, $company_id) {

        /** transaction types
        * 1. cash
        * 2. credit
        * 3. check
        * 4. online cc
        * 5. account
        * 6. other
        **/

        $start_date = date('Y-m-d H:i:s',strtotime($start));
        $end_date = date('Y-m-d H:i:s',strtotime($end));


        $report = [];

        // pickup 
        $pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->sum('pretax');
        $tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->sum('tax');
        $discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->sum('discount');
        $total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->sum('total');

        $cash_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',1)->sum('pretax');
        $cash_tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',1)->sum('tax');
        $cash_discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',1)->sum('discount');
        $cash_total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',1)->sum('total');
        $check_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',3)->sum('pretax');
        $check_tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',3)->sum('tax');
        $check_discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',3)->sum('discount');
        $check_total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',3)->sum('total');
        $cc_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',2)->sum('pretax');
        $cc_tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',2)->sum('tax');
        $cc_discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',2)->sum('discount');
        $cc_total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',2)->sum('total');
        $online_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',4)->sum('pretax');
        $online_tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',4)->sum('tax');
        $online_discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',4)->sum('discount');
        $online_total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',4)->sum('total');

        // iterate over inventory groups
        

        // dropoff

        return $report;
    }
}

