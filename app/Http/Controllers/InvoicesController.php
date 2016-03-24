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
        if(count($items) > 0) {
            $invoice = new Invoice();
            $invoice->company_id = Auth::user()->company_id;
            $invoice->customer_id = $request->customer_id;
            $invoice->due_date = $request->due_date;
            $invoice->pretax = $request->subtotal;
            $invoice->tax = $request->tax;
            $invoice->total = $request->total;
            $invoice->due_date = date('Y-m-d H:i:s',strtotime($request->due_date));
            $invoice->status = 1;
            if($invoice->save()){
                
                foreach ($items as $i) {
                    foreach ($i as $ikey => $ivalue) {
                        $item = new InvoiceItem();
                        $item->invoice_id = $invoice->id;
                        $item->item_id = $ikey;
                        $item->pretax = $i[$ikey]['price'];
                        $item->quantity = 1;
                        $item->color = $i[$ikey]['color'];
                        $item->memo = $i[$ikey]['memo'];
                        $item->status = 1;
                        $item->save();
                    }

                }
                // Do printer logic here

                
                // all finished
                Flash::success('Successfully added a new inventory!');
                return Redirect::route('customers_view',$invoice->customer_id);               
            }
        } else {
            Flash::warning('Could not save your invoice! Please select an invoice item');
            return Redirect::route('invoices_dropoff',$request->customer_id); 
        }   
    }

    public function getEdit($id = null){
        return view('invoices.edit')
        ->with('layout',$this->layout);
    }

    public function postEdit() {

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
}