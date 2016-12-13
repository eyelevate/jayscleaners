<?php

namespace App\Http\Controllers;
use App\Http\Requests;
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
use Mail;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Account;
use App\InventoryItem;
use App\Card;
use App\Discount;
use App\Job;
use App\User;
use App\Company;
use App\Customer;
use App\Custid;
use App\Invoice;
use App\InvoiceItem;
use App\Inventory;
use App\Transaction;
use App\Layout;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class DiscountsController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.dropoff';

    }

    public function getIndex(){
        return view('discounts.index')
        ->with('layout',$this->layout);
    }

    public function getAdd() {
        $companies = Company::prepareSelect(Company::all());
        $inventories = Inventory::prepareInventorySelect(Inventory::where('company_id',Auth::user()->company_id)->get());
        $items = InventoryItem::prepareSelect(InventoryItem::where('company_id',Auth::user()->company_id)->get());
        return view('discounts.add')
        ->with('companies',$companies)
        ->with('inventories',$inventories)
        ->with('items',$items)
        ->with('layout',$this->layout);
    }

    public function postAdd(Request $request) {
        $company_id = $request->company_id;
        $name = $request->name;
        $type = $request->type;
        $rate = $request->rate;
        $discount = $request->price;
        $start_date = ($request->start_date != '') ? date('Y-m-d 00:00:00',strtotime($request->start_date)) : NULL;
        $end_date = ($request->end_date != '') ? date('Y-m-d 00:00:00',strtotime($request->end_date)) : NULL;
        $status = 1;
        $discounts = new Discount();
        $discounts->company_id = $company_id;
        $discounts->name = $name;
        $discounts->type = $type;
        $discounts->rate = $rate;
        $discounts->discount = $discount;
        $discounts->start_date = $start_date;
        $discounts->end_date = $end_date;
        $discounts->inventory_id = $request->inventory_id;
        $discounts->inventory_item_id = $request->inventory_item_id;
        $discounts->status = $status;
        Job::dump($discounts);
        if ($discounts->save()) {
            Flash::success('Successfully created a discount.');
            return Redirect::route('discounts_index');
        }
    }

    public function getEdit($id = null) {
        return view('discounts.edit')
        ->with('layout',$this->layout);
    }

    public function postEdit(Request $request) {

    }
}
