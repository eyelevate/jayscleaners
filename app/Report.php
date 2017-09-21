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
        $pretax = 0;
        $tax = 0;
        $discount = 0;
        $credit = 0;
        $total = 0;
        if (count($summary_totals) > 0) {
            foreach ($summary_totals as $summary) {
                $pretax = $summary->pretax;
                $tax = $summary->tax;
                $discount = $summary->discount;
                $credit = $summary->credit;
                $total = $summary->total;
            }
        }
        // $pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type','<',5)->sum('pretax');
        // $tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type','<',5)->sum('tax');
        // $discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type','<',5)->sum('discount');
        // $credit = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type','<',5)->sum('credit');
        // $total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type','<',5)->sum('total');
        $summary_totals = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',3)
            ->select(\DB::raw('sum(pretax) as pretax'),\DB::raw('sum(tax) as tax'),\DB::raw('sum(discount) as discount'),\DB::raw('sum(aftertax) as total'))
            ->get();
        dd($summary_totals);
        $cash_pretax = 0;
        $cash_tax = 0;
        $cash_discount = 0;
        $cash_total = 0;
        if (count($summary_totals) > 0) {
            foreach ($summary_totals as $summary) {
                dd($summary);
                $cash_pretax = $summary->pretax;
                $cash_tax = $summary->tax;
                $cash_discount = $summary->discount;
                $cash_total = $summary->total;
            }
        }

        // $cash_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',3)->sum('pretax');
        // $cash_tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',3)->sum('tax');
        // $cash_discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',3)->sum('discount');
        // $cash_total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',3)->sum('aftertax');
        $summary_totals = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',4)
            ->select(\DB::raw('sum(pretax) as pretax'),\DB::raw('sum(tax) as tax'),\DB::raw('sum(discount) as discount'),\DB::raw('sum(aftertax) as total'))
            ->get();
        $check_pretax = 0;
        $check_tax = 0;
        $check_discount = 0;
        $check_total = 0;
        if (count($summary_totals) > 0) {
            foreach ($summary_totals as $summary) {
                $check_pretax = $summary->pretax;
                $check_tax = $summary->tax;
                $check_discount = $summary->discount;
                $check_total = $summary->total;
            }
        }
        // $check_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',4)->sum('pretax');
        // $check_tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',4)->sum('tax');
        // $check_discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',4)->sum('discount');
        // $check_total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',4)->sum('aftertax');
        $summary_totals = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',1)
            ->select(\DB::raw('sum(pretax) as pretax'),\DB::raw('sum(tax) as tax'),\DB::raw('sum(discount) as discount'),\DB::raw('sum(aftertax) as total'))
            ->get();
        $cc_pretax = 0;
        $cc_tax = 0;
        $cc_discount = 0;
        $cc_total = 0;
        if (count($summary_totals) > 0) {
            foreach ($summary_totals as $summary) {
                $cc_pretax = $summary->pretax;
                $cc_tax = $summary->tax;
                $cc_discount = $summary->discount;
                $cc_total = $summary->total;
            }
        }
        // $cc_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',1)->sum('pretax');
        // $cc_tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',1)->sum('tax');
        // $cc_discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',1)->sum('discount');
        // $cc_total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',1)->sum('aftertax');
        $summary_totals = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',2)
            ->select(\DB::raw('sum(pretax) as pretax'),\DB::raw('sum(tax) as tax'),\DB::raw('sum(discount) as discount'),\DB::raw('sum(aftertax) as total'))
            ->get();
        $online_pretax = 0;
        $online_tax = 0;
        $online_discount = 0;
        $online_total = 0;
        if (count($summary_totals) > 0) {
            foreach ($summary_totals as $summary) {
                $online_pretax = $summary->pretax;
                $online_tax = $summary->tax;
                $online_discount = $summary->discount;
                $online_total = $summary->total;
            }
        }
        // $online_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',2)->sum('pretax');
        // $online_tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',2)->sum('tax');
        // $online_discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',2)->sum('discount');
        // $online_total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',2)->sum('aftertax');
        $summary_totals = Transaction::whereBetween('created_at',[$start_date,$end_date])
            ->where('company_id',$company_id)
            ->where('type',5)
            ->select(\DB::raw('sum(pretax) as pretax'),\DB::raw('sum(tax) as tax'),\DB::raw('sum(discount) as discount'),\DB::raw('sum(aftertax) as total'))
            ->get();
        $account_pretax = 0;
        $account_tax = 0;
        $account_discount = 0;
        $account_total = 0;
        if (count($summary_totals) > 0) {
            foreach ($summary_totals as $summary) {
                $account_pretax = $summary->pretax;
                $account_tax = $summary->tax;
                $account_discount = $summary->discount;
                $account_total = $summary->total;
            }
        }
        // $account_pretax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',5)->sum('pretax');
        // $account_tax = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',5)->sum('tax');
        // $account_discount = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',5)->sum('discount');
        // $account_total = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type',5)->sum('aftertax');


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
                    'tax' => money_format('%n',$cash_tax),
                    'discount' => money_format('%n',$cash_discount),
                    'total' => money_format('%n',$cash_total),
                ],
                'check' => [
                    'subtotal' => money_format('%n',$check_pretax),
                    'tax' => money_format('%n',$check_tax),
                    'discount' => money_format('%n',$check_discount),
                    'total' => money_format('%n',$check_total),
                ],
                'credit' => [
                    'subtotal' => money_format('%n',$cc_pretax),
                    'tax' => money_format('%n',$cc_tax),
                    'discount' => money_format('%n',$cc_discount),
                    'total' => money_format('%n',$cc_total),
                ],
                'online' => [
                    'subtotal' => money_format('%n',$online_pretax),
                    'tax' => money_format('%n',$online_tax),
                    'discount' => money_format('%n',$online_discount),
                    'total' => money_format('%n',$online_total),
                ],
                'account' => [
                    'subtotal' => money_format('%n',$account_pretax),
                    'tax' => money_format('%n',$account_tax),
                    'discount' => money_format('%n',$account_discount),
                    'total' => money_format('%n',$account_total),
                ]
            ],

        ];
        $query = Transaction::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->where('type','<',5)->pluck('id');
        $completed_invoice_ids = [];
        if (count($query) > 0) {
            $trans_id_list = [];
            foreach ($query as $qid) {
                $transaction_id = $qid;
                array_push($trans_id_list, $transaction_id);
            }
            $invs = Invoice::whereIn('transaction_id',$trans_id_list)->pluck('id');
            if (count($invs) > 0) {
                foreach ($invs as $invid) {
                    $invoice_id = $invid;
                    array_push($completed_invoice_ids, $invoice_id);
                }
            }

        }

        $dropoff_invoice_ids = [];
        $query = Invoice::whereBetween('created_at',[$start_date,$end_date])->where('company_id',$company_id)->pluck('id');
        
        if (count($query) > 0) {
            foreach ($query as $qid) {
                array_push($dropoff_invoice_ids,$qid);
            }
        }

        // iterate over inventory groups
        $inv_summary = [];
        $dropoff_summary = [];
        
        $inventories = Inventory::all();


        $inv_summary = Invoice::whereIn('id',$completed_invoice_ids)
            ->select(\DB::raw('SUM(quantity) as quantity'),\DB::raw('SUM(pretax) as pretax'),\DB::raw('SUM(tax) as tax'),\DB::raw('SUM(total) as total'))
            ->get();
        $ps_quantity = 0;
        $ps_subtotal = 0;
        $ps_tax = 0;
        $ps_total = 0;
        if (count($inv_summary) > 0) {
            foreach ($inv_summary as $summary) {
                $ps_quantity = $summary->quantity;
                $ps_subtotal = $summary->subtotal;
                $ps_tax = $summary->tax;
                $ps_total = $summary->total;
            }
        } 
        
        // $ps_quantity = Invoice::whereIn('id',$completed_invoice_ids)->sum('quantity');
        // $ps_subtotal = Invoice::whereIn('id',$completed_invoice_ids)->sum('pretax');
        // $ps_tax = Invoice::whereIn('id',$completed_invoice_ids)->sum('tax');
        // $ps_total = Invoice::whereIn('id',$completed_invoice_ids)->sum('total');
        $inv_summary = Invoice::whereIn('id',$dropoff_invoice_ids)
            ->select(\DB::raw('SUM(quantity) as quantity'),\DB::raw('SUM(pretax) as pretax'),\DB::raw('SUM(tax) as tax'),\DB::raw('SUM(total) as total'))
            ->get();
        $ds_quantity = 0;
        $ds_subtotal = 0;
        $ds_tax = 0;
        $ds_total = 0;
        if (count($inv_summary) > 0) {
            foreach ($inv_summary as $summary) {
                $ds_quantity = $summary->quantity;
                $ds_subtotal = $summary->subtotal;
                $ds_tax = $summary->tax;
                $ds_total = $summary->total;
            }
        } 
        // $ds_quantity = Invoice::whereIn('id',$dropoff_invoice_ids)->sum('quantity');
        // $ds_subtotal = Invoice::whereIn('id',$dropoff_invoice_ids)->sum('pretax');
        // $ds_tax = Invoice::whereIn('id',$dropoff_invoice_ids)->sum('tax');
        // $ds_total = Invoice::whereIn('id',$dropoff_invoice_ids)->sum('total');
        $pickup_summary_totals = [
            'quantity' => $ps_quantity, 
            'subtotal' => money_format('%n',$ps_subtotal), 
            'tax'=>money_format('%n',$ps_tax),
            'total'=>money_format('%n',$ps_total)
        ];
        $dropoff_summary_totals = [
            'quantity' => $ds_quantity, 
            'subtotal' => money_format('%n',$ds_subtotal), 
            'tax'=>money_format('%n',$ds_tax),
            'total'=>money_format('%n',$ds_total)
        ];

        // #make a list of inventory item id to inventory id
        $itemsToInventory = Report::itemsToInventory($company_id);
        $itemsToInvoice = [];
        if (count($inventories) > 0) {
            foreach ($inventories as $inventory) {
                $inventory_id = $inventory->id;
                $itemsToInvoice[$inventory_id] = [];
            }
        }
        // Job::dump($completed_invoice_ids);
        if (count($completed_invoice_ids) > 0) {
            foreach ($completed_invoice_ids as $iidkey => $iidvalue) {
                $check_inventory_id = InvoiceItem::where('invoice_id',$iidvalue)->limit(1)->get();
                if (count($check_inventory_id) > 0) {
                    foreach ($check_inventory_id as $cii) {
                        // Job::dump($iidkey.' - '.$cii->inventory_id.' - '.$cii->item_id);
                        $iiid = ($cii->inventory_id > 5) ? $cii->inventory_id - 5 : $cii->inventory_id;
                        array_push($itemsToInvoice[$iiid], $iidvalue);
                    }
                    
                }
            }
        }

        if (count($itemsToInvoice) > 0) {

            foreach ($itemsToInvoice as $inventory_id => $cmplist) {
                if ($inventory_id > 5) {
                    break;
                }
                $inventory = Inventory::find($inventory_id);
                $inv_summary = Invoice::whereIn('id',$cmplist)
                    ->select(\DB::raw('SUM(quantity) as quantity'),\DB::raw('SUM(pretax) as pretax'),\DB::raw('SUM(tax) as tax'),\DB::raw('SUM(total) as total'))
                    ->get();
                $qty = 0;
                $pretax = 0;
                $tax = 0;
                $total = 0;
                if (count($inv_summary) > 0) {
                    foreach ($inv_summary as $summary) {
                        $qty = $summary->quantity;
                        $pretax = $summary->subtotal;
                        $tax = $summary->tax;
                        $total = $summary->total;
                    }
                } 


                // $qty = Invoice::whereIn('id',$cmplist)->sum('quantity');
                // $pretax = Invoice::whereIn('id',$cmplist)->sum('pretax');
                // $tax = Invoice::whereIn('id',$cmplist)->sum('tax');
                // $total = Invoice::whereIn('id',$cmplist)->sum('total');

                $inv_summary[$inventory_id] = [
                    'name' => $inventory->name,
                    'totals' => [
                        'quantity' => $qty, 
                        'subtotal' =>money_format('%n', $pretax), 
                        'tax'=>money_format('%n', $tax),
                        'total'=>money_format('%n', $total)
                    ],
                    'summary' => ['quantity' => 0, 'subtotal' =>'$0.00', 'tax'=>'$0.00','total'=>'$0.00']
                ];
            }
        }

        $inventories = Inventory::where('company_id',$company_id)->get();
        if (count($inventories) > 0) {
            foreach ($inventories as $inventory) {
                $inv_summary = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)
                    ->where('inventory_id',$inventory->id)
                    ->select(\DB::raw('SUM(quantity) as quantity'),\DB::raw('SUM(pretax) as pretax'),\DB::raw('SUM(tax) as tax'),\DB::raw('SUM(total) as total'))
                    ->get();
                $drop_qty = 0;
                $drop_pretax = 0;
                $drop_tax = 0;
                $drop_total = 0;
                if (count($inv_summary) > 0) {
                    foreach ($inv_summary as $summary) {
                        $drop_qty = $summary->quantity;
                        $drop_pretax = $summary->subtotal;
                        $drop_tax = $summary->tax;
                        $drop_total = $summary->total;
                    }
                } 
                // $drop_qty = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)->where('inventory_id',$inventory->id)->sum('quantity');
                // $drop_pretax = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)->where('inventory_id',$inventory->id)->sum('pretax');
                // $drop_tax = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)->where('inventory_id',$inventory->id)->sum('tax');
                // $drop_total = InvoiceItem::whereIn('invoice_id',$dropoff_invoice_ids)->where('inventory_id',$inventory->id)->sum('total');
                $dropoff_summary[$inventory->id] = [
                    'name' => $inventory->name,
                    'totals' => [
                        'quantity' => $drop_qty, 
                        'subtotal' => money_format('%n',$drop_pretax), 
                        'tax'=> money_format('%n',$drop_tax),
                        'total'=> money_format('%n',$drop_total)
                    ],
                    'summary' => ['quantity' => 0, 'subtotal' =>'$0.00', 'tax'=>'$0.00','total'=>'$0.00']
                ];
            }
        }

        $report['pickup_summary'] = $inv_summary;
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

