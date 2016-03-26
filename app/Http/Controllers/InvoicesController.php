<?php

namespace App\Http\Controllers;

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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\User;
use App\Admin;
use App\Layout;
use App\Inventory;
use App\InventoryItem;
use App\Color;
use App\Company;
use App\Memo;
use App\Tax;
use App\Invoice;
use App\InvoiceItem;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class InvoicesController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }

    public function getIndex(){

        return view('invoices.index')
        ->with('layout',$this->layout);
    }

    public function getAdd($id = null){
        $this->layout = 'layouts.dropoff';
        $customer = User::find($id);
        $inventories = Inventory::where('company_id',Auth::user()->company_id)
        ->where('status',1)
        ->orderBy('ordered','asc')
        ->get();
        $items = InventoryItem::prepareItems($inventories);
        $colors = Color::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();
        $memos = Memo::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();
        $company = Company::where('id',Auth::user()->company_id)->get();
        $store_hours = Company::getStoreHours($company);

        $turnaround_date = Company::getTurnaroundDate($company);
        $turnaround = Company::getTurnaround($company);
        $hours = Company::prepareStoreHours();
        $minutes = Company::prepareMinutes();
        $ampm = Company::prepareAmpm();
        $tax_rate = Tax::where('company_id',Auth::user()->company_id)->where('status',1)->first();

        return view('invoices.add')
        ->with('layout',$this->layout)
        ->with('customer',$customer)
        ->with('inventories',$inventories)
        ->with('items',$items)
        ->with('colors',$colors)
        ->with('memos',$memos)
        ->with('turnaround',$turnaround)
        ->with('turnaround_date',$turnaround_date)
        ->with('store_hours',$store_hours)
        ->with('hours',$hours)
        ->with('minutes',$minutes)
        ->with('ampm',$ampm)
        ->with('tax_rate',$tax_rate->rate);
    }

    public function postAdd(Request $request){
        $items = Input::get('item');
        $tax_rate = $tax_rate = Tax::where('company_id',Auth::user()->company_id)->where('status',1)->first()->rate;
        if(count($items) > 0) { //Check if any items were selected
            // what type of print out?
            $print_type = $request->store_copy;

            foreach ($items as $itms) { // iterate through the first index (inventory group)
                $invoice = new Invoice();
                $invoice->company_id = Auth::user()->company_id;
                $invoice->customer_id = $request->customer_id;
                $invoice->due_date = date('Y-m-d H:i:s',strtotime($request->due_date));
                $invoice->status = 1;   
                $qty = 0;
                $subtotal = 0;
                $tax = 0;
                $total = 0;   
                if($invoice->save()){ // save the invoice
                    
                    foreach ($itms as $i) {
                        foreach ($i as $ikey => $ivalue) {
                            $qty++;
                            $item = new InvoiceItem();
                            $item->invoice_id = $invoice->id;
                            $item->item_id = $ivalue['item_id'];
                            $item->pretax = $ivalue['price'];
                            $subtotal += $ivalue['price'];
                            $item->quantity = 1;
                            $item->color = $ivalue['color'];
                            $item->memo = $ivalue['memo'];
                            $item->status = 1;
                            $item->save();
                        }

                    }
                    // get totals here and update invoice
                    $edit = Invoice::find($invoice->id);
                    $edit->quantity = $qty;
                    $edit->pretax = $subtotal;
                    $edit->tax = number_format(round($subtotal * $tax_rate,2),2,'.','');
                    $edit->total = number_format(round($subtotal * (1+$tax_rate),2),2,'.','');
                    $edit->save();

                    // Do printer logic here


             
                }          
            }
            // all finished
            Flash::success('Successfully added a new inventory!');
            return Redirect::route('customers_view',$invoice->customer_id);  

        } else {
            Flash::warning('Could not save your invoice! Please select an invoice item');
            return Redirect::route('invoices_dropoff',$request->customer_id); 
        }   
    }

    public function getEdit($id = null){
        $this->layout = 'layouts.dropoff';
        $customer = User::find($id);
        $inventories = Inventory::where('company_id',Auth::user()->company_id)
        ->where('status',1)
        ->orderBy('ordered','asc')
        ->get();
        $items = InventoryItem::prepareItems($inventories);
        $colors = Color::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();
        $memos = Memo::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();
        $company = Company::where('id',Auth::user()->company_id)->get();
        $store_hours = Company::getStoreHours($company);

        $turnaround_date = Company::getTurnaroundDate($company);
        $turnaround = Company::getTurnaround($company);
        $hours = Company::prepareStoreHours();
        $minutes = Company::prepareMinutes();
        $ampm = Company::prepareAmpm();
        $tax_rate = Tax::where('company_id',Auth::user()->company_id)->where('status',1)->first();

        $invoice_items = InvoiceItem::prepareEdit(InvoiceItem::where('invoice_id',$id)->where('status',1)->get());
        $invoice_grouped = InvoiceItem::prepareGroup($invoice_items);
        return view('invoices.edit')
        ->with('layout',$this->layout)
        ->with('customer',$customer)
        ->with('inventories',$inventories)
        ->with('items',$items)
        ->with('colors',$colors)
        ->with('memos',$memos)
        ->with('turnaround',$turnaround)
        ->with('turnaround_date',$turnaround_date)
        ->with('store_hours',$store_hours)
        ->with('hours',$hours)
        ->with('minutes',$minutes)
        ->with('ampm',$ampm)
        ->with('tax_rate',$tax_rate->rate)
        ->with('invoice_id',$id)
        ->with('invoice_items',$invoice_items)
        ->with('invoice_grouped',$invoice_grouped);
    }

    public function postEdit(Request $request) {
        $items = Input::get('item');
        $tax_rate = $tax_rate = Tax::where('company_id',Auth::user()->company_id)->where('status',1)->first()->rate;
        if(count($items) > 0) { //Check if any items were selected


            // what type of print out?
            $print_type = $request->store_copy;

            foreach ($items as $itms) { // iterate through the first index (inventory group)
                $invoice = Invoice::find($request->invoice_id);
                $invoice->due_date = date('Y-m-d H:i:s',strtotime($request->due_date));
                $invoice->status = 1;   
                $qty = 0;
                $subtotal = 0;
                $tax = 0;
                $total = 0;   
                if($invoice->save()){ // save the invoice
                    // Get all the previously saved invoice items. remove any deleted ones by comparing existing items
                    $previous = InvoiceItem::where('invoice_id',$request->invoice_id)->where('status',1)->get();
                    if(isset($previous)) {
                        foreach ($previous as $pkey => $pvalue) {
                            $rmv = true;
                            //compare to new items
                            foreach ($items as $itms) {
                                foreach ($itms as $i) {

                                    foreach ($i as $ikey => $ivalue) {
                                        if(isset($ivalue['id'])){

                                            if($ivalue['id'] == $pvalue->id){ 
                                                $rmv = false;
                                                break;
    
                                            } 
                                        }
                                    }
                                }
                            }
                            if($rmv) {
                                $del = InvoiceItem::find($pvalue->id);
                                $del->delete();
                            }

                        }
                    }
                    // update and save the rest
                    foreach ($itms as $i) {
                        foreach ($i as $ikey => $ivalue) {
                            $qty++;
                            $item = (isset($ivalue['id'])) ? InvoiceItem::find($ivalue['id']) : new InvoiceItem();
                            $item->item_id = $ivalue['item_id'];
                            $item->invoice_id = $request->invoice_id;
                            $item->pretax = $ivalue['price'];
                            $subtotal += $ivalue['price'];
                            $item->quantity = 1;
                            $item->color = $ivalue['color'];
                            $item->memo = $ivalue['memo'];
                            $item->status = 1;
                            $item->save();
                        }

                    }
                    // get totals here and update invoice
                    $edit = Invoice::find($request->invoice_id);
                    $edit->quantity = $qty;
                    $edit->pretax = $subtotal;
                    $edit->tax = number_format(round($subtotal * $tax_rate,2),2,'.','');
                    $edit->total = number_format(round($subtotal * (1+$tax_rate),2),2,'.','');
                    $edit->save();

                    // Do printer logic here


             
                }          
            }
            // all finished
            Flash::success('Successfully added a new inventory!');
            return Redirect::route('customers_view',$invoice->customer_id);  

        } else {
            Flash::warning('Could not save your invoice! Please select an invoice item');
            return Redirect::route('invoices_dropoff',$request->customer_id); 
        }   
    }

    public function getView($id = null) {
        return view('invoices.view')
        ->with('layout',$this->layout);
    }
    public function getRack(){
        return view('invoices.rack')
        ->with('layout',$this->layout);
    }

    public function postRack() {

    }

    public function postFeed(Request $request) {
        if($request->ajax()){
            $test = $request->test;
            return response()->json([
                'title'=> $test,
                'start' => '2016-03-13T11:00:00',
                'constraint'=> 'availableForMeeting', // defined below
                'color'=> '#257e4a'
            ]);
        }
    }

    public function getTest(){

$connector = new FilePrintConnector("php://stdout");
$printer = new Printer($connector);
$printer -> text("Hello World!\n");
$printer -> cut();
$printer -> close();

        return view('invoices.test')
        ->with('layout',$this->layout);        
    }
}