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

        $start_date = date('Y-m-d H:i:s',$start);
        $end_date = date('Y-m-d H:i:s',$end);

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


        $report = [
            'totals' => [
                'subtotal' => money_format('$%i',$pretax),
                'tax' => money_format('$%i',$tax),
                'discount' => money_format('$%i',$discount),
                'total' => money_format('$%i',$total),
            ],
            'total_splits' => [
                'cash' => [
                    'subtotal' => money_format('$%i',$cash_pretax),
                    'tax' => money_format('$%i',$cash_tax),
                    'discount' => money_format('$%i',$cash_discount),
                    'total' => money_format('$%i',$cash_total),
                ],
                'check' => [
                    'subtotal' => money_format('$%i',$check_pretax),
                    'tax' => money_format('$%i',$check_tax),
                    'discount' => money_format('$%i',$check_discount),
                    'total' => money_format('$%i',$check_total),
                ],
                'credit' => [
                    'subtotal' => money_format('$%i',$cc_pretax),
                    'tax' => money_format('$%i',$cc_tax),
                    'discount' => money_format('$%i',$cc_discount),
                    'total' => money_format('$%i',$cc_total),
                ],
                'online' => [
                    'subtotal' => money_format('$%i',$online_pretax),
                    'tax' => money_format('$%i',$online_tax),
                    'discount' => money_format('$%i',$online_discount),
                    'total' => money_format('$%i',$online_total),
                ]
            ],

        ];

        $query = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->get();
        $completed_invoice_ids = [];
        if (count($query) > 0) {
            foreach ($query as $q) {
                $transaction_id = $q->id;
                $invs = Invoice::where('transaction_id',$transaction_id)->where('status',5)->get();
                if (count($invs) > 0) {
                    foreach ($invs as $inv) {
                        $invoice_id = $inv->id;
                        array_push($completed_invoice_ids, $invoice_id);
                    }
                }
            }
        }
        $dropoff_invoice_ids = [];
        $query = Invoice::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->get();
        
        if (count($query) > 0) {
            foreach ($query as $q) {
                $invoice_id = $q->id;
                array_push($dropoff_invoice_ids,$invoice_id);
            }
        }

        // iterate over inventory groups
        $inv_summary = [];
        $dropoff_summary = [];
        
        $inventories = Inventory::where('company_id',$company_id)->get();
        
        $ps_quantity = InvoiceItem::whereIn('invoice_id',$completed_invoice_ids)->sum('quantity');
        $ps_subtotal = InvoiceItem::whereIn('invoice_id',$completed_invoice_ids)->sum('pretax');
        $ps_tax = InvoiceItem::whereIn('invoice_id',$completed_invoice_ids)->sum('tax');
        $ps_total = InvoiceItem::whereIn('invoice_id',$completed_invoice_ids)->sum('total');
        $ds_quantity = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)->sum('quantity');
        $ds_subtotal = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)->sum('pretax');
        $ds_tax = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)->sum('tax');
        $ds_total = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)->sum('total');
        $pickup_summary_totals = [
            'quantity' => $ps_quantity, 
            'subtotal' => money_format('$%i',$ps_subtotal), 
            'tax'=>money_format('$%i',$ps_tax),
            'total'=>money_format('$%i',$ps_total)
        ];
        $dropoff_summary_totals = [
            'quantity' => $ds_quantity, 
            'subtotal' => money_format('$%i',$ds_subtotal), 
            'tax'=>money_format('$%i',$ds_tax),
            'total'=>money_format('$%i',$ds_total)
        ];
        if (count($inventories) > 0) {

            foreach ($inventories as $inventory) {
                $pickup_quantity = 0;
                $pickup_subtotal = 0;
                $pickup_tax = 0;
                $pickup_total = 0;
                $dropoff_quantity = 0;
                $dropoff_subtotal = 0;
                $dropoff_tax = 0;
                $dropoff_total = 0;                
                $inventory_id = $inventory->id;
                $inventory_name = $inventory->name;

                $items = InvoiceItem::whereIn('invoice_id',$completed_invoice_ids)->get();
                $inv_summary[$inventory_id] = [
                    'name' => $inventory_name,
                    'totals' => ['quantity' => 0, 'subtotal' =>'$0.00', 'tax'=>'$0.00','total'=>'$0.00'],
                    'summary' => ['quantity' => 0, 'subtotal' =>'$0.00', 'tax'=>'$0.00','total'=>'$0.00']
                ];
                if (count($items) > 0) {
                    foreach ($items as $item) {
                        $item_id = $item->item_id;
                        $inventories = InventoryItem::find($item_id);
                        $inv_id = $inventories->inventory_id;
                        if ($inv_id == $inventory_id) {
                            $pickup_quantity += 1;
                            $pickup_subtotal += $item->pretax;
                            $pickup_tax += $item->tax;
                            $pickup_total += $item->total;
                        }

                    }
                    $inv_summary[$inventory_id]['totals'] = [
                        'quantity' => $pickup_quantity, 
                        'subtotal' =>money_format('$%i',$pickup_subtotal), 
                        'tax'=>money_format('$%i',$pickup_tax),
                        'total'=>money_format('$%i',$pickup_total)
                    ];
                }

                //dropoff info
                $dropoff_summary[$inventory_id] = [
                    'name' => $inventory_name,
                    'totals' => ['quantity' => 0, 'subtotal' => '$0.00', 'tax'=>'$0.00','total'=>'$0.00'],
                    'summary' => ['quantity' => 0, 'subtotal' =>'$0.00', 'tax'=>'$0.00','total'=>'$0.00']
                ];
                $dropoff_items = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)->get();
                if (count($dropoff_items) > 0) {
                    foreach ($dropoff_items as $di) {
                        $item_id = $di->item_id;
                        $inventories = InventoryItem::find($item_id);
                        $inv_id = $inventories->inventory_id;
                        if ($inv_id == $inventory_id) {
                            $dropoff_quantity += 1;
                            $dropoff_subtotal += $di->pretax;
                            $dropoff_tax += $di->tax;
                            $dropoff_total += $di->total;
                        }

                    }
                    $dropoff_summary[$inventory_id]['totals'] = [
                        'quantity' => $dropoff_quantity, 
                        'subtotal' => money_format('$%i',$dropoff_subtotal), 
                        'tax'=>money_format('$%i',$dropoff_tax),
                        'total'=>money_format('$%i',$dropoff_total)
                    ];

                }

            }
        }

        $report['pickup_summary'] = $inv_summary;
        $report['pickup_summary_totals'] = $pickup_summary_totals;
        $report['dropoff_summary'] = $dropoff_summary;
        $report['dropoff_summary_totals'] = $dropoff_summary_totals;


        return $report;
    }
}

