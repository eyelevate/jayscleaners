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
        return view('invoices.add')
        ->with('layout',$this->layout)
        ->with('customer',$customer)
        ->with('inventories',$inventories)
        ->with('items',$items)
        ->with('colors',$colors);
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
}