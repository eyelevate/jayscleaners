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
        ->with('ampm',$ampm);
    }

    public function postAdd(){

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