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
    			'start' => date('D n/d/Y',strtotime(date('Y-01-01 00:00:00'))),
    			'end' => date('D n/d/Y',strtotime(date('Y-12-31 23:59:59')))
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
                $today_transactions = Transaction::whereBetween('created_at',[$today_start,$today_end])->where('company_id',$company_id)->where('status',1)->sum('total');
                $this_week_transactions = Transaction::whereBetween('created_at',[$this_week_start,$this_week_end])->where('company_id',$company_id)->where('status',1)->sum('total');
                $this_month_transactions = Transaction::whereBetween('created_at',[$this_month_start,$this_month_end])->where('company_id',$company_id)->where('status',1)->sum('total');
                $this_year_transactions = Transaction::whereBetween('created_at',[$this_year_start,$this_year_end])->where('company_id',$company_id)->where('status',1)->sum('total');
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

    static public function prepareDropoffGlimpse() {
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
                $today_transactions = Invoice::whereBetween('created_at',[$today_start,$today_end])->where('company_id',$company_id)->sum('total');
                $this_week_transactions = Invoice::whereBetween('created_at',[$this_week_start,$this_week_end])->where('company_id',$company_id)->sum('total');
                $this_month_transactions = Invoice::whereBetween('created_at',[$this_month_start,$this_month_end])->where('company_id',$company_id)->sum('total');
                $this_year_transactions = Invoice::whereBetween('created_at',[$this_year_start,$this_year_end])->where('company_id',$company_id)->sum('total');
                $reports[$company_id] = [
                    'name'=>$company->name,
                    'today'=>'$'.number_format($today_transactions,2,'.',','),
                    'this_week'=>'$'.number_format($this_week_transactions,2,'.',','),
                    'this_month'=>'$'.number_format($this_month_transactions,2,'.',','),
                    'this_year'=>'$'.number_format($this_year_transactions,2,'.',',')
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

        $x = time()*1000;
        /** transaction types
        * 1. credit
        * 2. Credit - Online
        * 3. Cash
        * 4. Check
        * 5. account
        * 6. other
        **/

        $start_date = date('Y-m-d 00:00:00',$start);
        $end_date = date('Y-m-d 23:59:59',$end);

        $report = [];
        setlocale(LC_MONETARY, 'en_US.utf8');
        // pickup 
        $summary_totals = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type','<',5)
            ->select(\DB::raw('sum(pretax) as pretax'),\DB::raw('sum(tax) as tax'),\DB::raw('sum(discount) as discount'),\DB::raw('sum(credit) as credit'),\DB::raw('sum(total) as total'))
            ->get();

        if (count($summary_totals) > 0) {
            foreach ($summary_totals as $summary) {
                $pretax = ($summary->pretax != null) ? $summary->pretax : 0;
                $tax = ($summary->tax != null) ? $summary->tax : 0;
                $discount = ($summary->discount != null) ? $summary->discount : 0;
                $credit = ($summary->credit != null) ? $summary->credit : 0;
                $total = ($pretax + $tax) - ($credit + $discount);
            }
        }
        $cash_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',3)
            ->sum('pretax');
        $check_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',4)
            ->sum('pretax');
        $cc_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',1)
            ->sum('pretax');
        $online_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',2)
            ->sum('pretax');
        $account_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',5)
            ->sum('pretax');

        $report = [
            'totals' => [
                'subtotal' => money_format('%n',$pretax),
                'tax' => money_format('%n',$tax),
                'credit'=>money_format('(%n)',$credit),
                'discount' => money_format('(%n)',$discount),
                'total' => money_format('%n',$total),
            ],
            'total_splits' => [
                'cash' => [
                    'subtotal' => money_format('%n',$cash_pretax),

                ],
                'check' => [
                    'subtotal' => money_format('%n',$check_pretax),

                ],
                'credit' => [
                    'subtotal' => money_format('%n',$cc_pretax),

                ],
                'online' => [
                    'subtotal' => money_format('%n',$online_pretax),

                ],
                'account' => [
                    'subtotal' => money_format('%n',$account_pretax),
 
                ]
            ],

        ];

        $completed_transaction_ids = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type','<',5)->pluck('id')->toArray();

        $completed_invoice_ids = Invoice::whereIn('transaction_id',$completed_transaction_ids)->pluck('id')->toArray();


        $dropoff_invoice_ids = Invoice::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->pluck('id')->toArray();

        
        //iterate over inventory groups
        $inv_summary = [];
        $dropoff_summary = [];

        $pickup_summary_totals = [
            'quantity' => 0, 
            'subtotal' => 0, 
            'tax'=>0,
            'total'=>0
        ];
        $dropoff_summary_totals = [
            'quantity' => 0, 
            'subtotal' => 0, 
            'tax'=>0,
            'total'=>0
        ];

        $inventories = Inventory::where('company_id',$company_id)->get();
        if(count($inventories) > 0) {
            foreach ($inventories as $inventory) {
                $qty = 0;
                InvoiceItem::where('inventory_id',$inventory->id)->whereIn('invoice_id',$completed_invoice_ids)->chunk(100,function($chunks){
                    foreach ($chunks as $chunk) {
                        $qty += $chunk->quantity;
                    }
                });
                
                $y = time() * 1000;
                $z = $y - $x;
                dd("start={$x} stop={$y} diff={$z}");

                
                $ss = InvoiceItem::where('inventory_id',$inventory->id)->whereIn('invoice_id',$completed_invoice_ids)->select(\DB::raw('SUM(quantity) as quantity'),\DB::raw('SUM(pretax) as pretax'),\DB::raw('SUM(tax) as tax'),\DB::raw('SUM(total) as total'))->first();
                
                $pickup_summary[$inventory->id] = [
                    'name' => $inventory->name,
                    'totals' => [
                        'quantity' => $ss->quantity, 
                        'subtotal' =>money_format('%n', ($ss->pretax != null) ? $ss->pretax : 0), 
                    ],
                    'summary' => ['quantity' => 0, 'subtotal' =>'$0.00', 'tax'=>'$0.00','total'=>'$0.00']
                ];

                 // dropoff
                $inv_summary = InvoiceItem::where('inventory_id',$inventory->id)->whereIn('invoice_id',$dropoff_invoice_ids)
                    ->select(\DB::raw('SUM(quantity) as quantity'),\DB::raw('SUM(pretax) as pretax'),\DB::raw('SUM(tax) as tax'),\DB::raw('SUM(total) as total'))
                    ->first();
                $dropoff_summary[$inventory->id] = [
                    'name' => $inventory->name,
                    'totals' => [
                        'quantity' => $inv_summary->quantity, 
                        'subtotal' => money_format('%n', ($inv_summary->pretax != null) ? $inv_summary->pretax : 0), 
                    ],
                    'summary' => ['quantity' => 0, 'subtotal' =>'$0.00', 'tax'=>'$0.00','total'=>'$0.00']
                ];

                $pickup_summary_totals['quantity'] += $ss->quantity;
                $pickup_summary_totals['subtotal'] += $ss->pretax;
                $pickup_summary_totals['tax'] += $ss->tax;

                $dropoff_summary_totals['quantity'] += $inv_summary->quantity;
                $dropoff_summary_totals['subtotal'] += $inv_summary->pretax;
                $dropoff_summary_totals['tax'] += $inv_summary->tax;
  

            }
        }
        

        $pickup_summary_totals['total'] = '$'.number_format($pickup_summary_totals['subtotal'] + $pickup_summary_totals['tax'],2,'.',',');
        $pickup_summary_totals['subtotal'] = '$'.number_format($pickup_summary_totals['subtotal'],2,'.',',');
        $pickup_summary_totals['tax'] = '$'.number_format($pickup_summary_totals['tax'],2,'.',',');
        
        
        $dropoff_summary_totals['total'] = '$'.number_format($dropoff_summary_totals['subtotal']+$dropoff_summary_totals['tax'],2,'.',',');
        $dropoff_summary_totals['subtotal'] = '$'.number_format($dropoff_summary_totals['subtotal'],2,'.',',');
        $dropoff_summary_totals['tax'] = '$'.number_format($dropoff_summary_totals['tax'],2,'.',',');
        

        $report['pickup_summary'] = $pickup_summary;
        $report['pickup_summary_totals'] = $pickup_summary_totals;
        $report['dropoff_summary'] = $dropoff_summary;
        $report['dropoff_summary_totals'] = $dropoff_summary_totals;


        return $report;
    }

    public static function itemsToInventory($company_id) {
        $items = InventoryItem::where('company_id',$company_id)->get();
        $itemsToInventory = [];
        if (count($items)> 0) {
            foreach ($items as $item) {
                $itemsToInventory[$item->id] = $item->inventory_id;
            }
        }

        return $itemsToInventory;
    }

    public static function prepareInvoiceReport($start, $end, $company_id) {
        $data = [];

        return $data;
    }
}

