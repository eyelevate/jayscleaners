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
use App\Inventory;
use App\InventoryItem;
use App\Company;
use App\Layout;

class InventoriesController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }
    public function getIndex(){
    	$companies = Company::getCompany();
    	
    	$inventories = Inventory::where('company_id',Auth::user()->company_id)
    	->where('status',1)
    	->orderBy('ordered','asc')
    	->get();
    	$items = InventoryItem::prepareItems($inventories);

    	$group_select = Inventory::prepareInventorySelect($inventories); 
    	$icon_select = Inventory::prepareIconSelect();
    	$tags_select = Inventory::prepareTagSelect();
        $quantity_select = Inventory::prepareQuantitySelect();

    	return view('inventories.index')
    	->with('layout',$this->layout)
    	->with('companies',$companies)
    	->with('inventories',$inventories)
    	->with('items',$items)
    	->with('group_select',$group_select)
    	->with('icon_select',$icon_select)
    	->with('tags_select',$tags_select)
        ->with('quantity_select',$quantity_select);
    }
    public function getAdd(){


    	return view('inventories.add')
    	->with('layout',$this->layout);
    }

    public function postAdd(Request $request){
        //Validate the request
        $this->validate($request, [
            'name' => 'required|min:1',
            'description' => 'min:1',
            'company_id'=>'required'
        ]);   

        $inventory = new Inventory();
        $inventory->company_id = Auth::user()->company_id;
        $inventory->name = $request->name;
        $inventory->description = $request->description;
        $inventory->ordered = null;
        $inventory->status = 1;
        if($inventory->save()){
			Flash::success('Successfully added a new inventory!');
			return Redirect::route('inventories_index');
        }

    }

    public function postDelete(Request $request) {
        $inventory = Inventory::find($request->id);
        $inventory->status = 2;
        if($inventory->delete()){
			Flash::success('Successfully deleted an inventory group!');
			return Redirect::route('inventories_index');
        }    	
    }

    public function getEdit($id = null){
    	return view('inventories.edit')
    	->with('layout',$this->layout);
    }
    public function postEdit(Request $request){
        //Validate the request
        $this->validate($request, [
            'name' => 'required|min:1',
            'description' => 'min:1',
            'company_id'=>'required'
        ]);   

        $inventory = Inventory::find($request->id);
        $inventory->company_id = $request->company_id;
        $inventory->name = $request->name;
        $inventory->description = $request->description;
        $inventory->ordered = $request->order;
        $inventory->status = 1;
        if($inventory->save()){
			Flash::success('Successfully added a new inventory!');
			return Redirect::route('inventories_index');
        }
    }

    public function postOrder(Request $request) {

    	$inventories = $request->inventory;
    	if($inventories !== '') {
    		foreach ($inventories as $key => $value) {
		        $inventory = Inventory::find($value['id']);
		        $inventory->ordered = $value['ordered'];  
		        $inventory->save();	
    		}
    	}


		Flash::success('Successfully updated inventory group order!');
		return Redirect::route('inventories_index'); 	
    }
    public function getView(){
    	return view('inventories.view')
    	->with('layout',$this->layout);
    }
}
