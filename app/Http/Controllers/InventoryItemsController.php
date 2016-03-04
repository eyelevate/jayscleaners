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

class InventoryItemsController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }
    public function postAdd(Request $request){
        //Validate the request
        $this->validate($request, [
            'name' => 'required|min:1',
            'description' => 'min:1',
            'company_id'=>'required',
            'inventory_id'=>'required',
     		'price'=>'required'
        ]);   

        $item = new InventoryItem();
        $item->company_id = $request->company_id;
        $item->inventory_id = $request->inventory_id;
        $item->tags = $request->tags;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->image = $request->image;
        $item->ordered = null;
        $item->status = 1;
        if($item->save()){
			Flash::success('Successfully added a new inventory item!');
			return Redirect::route('inventories_index');
        }

    }
    public function postDelete(Request $request) {
        $item = InventoryItem::find($request->id);
        $item->status = 2;
        if($item->delete()){
			Flash::success('Successfully deleted an inventory item!');
			return Redirect::route('inventories_index');
        }    	
    }
    public function postEdit(Request $request){
        //Validate the request
        $this->validate($request, [
            'name' => 'required|min:1',
            'description' => 'min:1',
            'company_id'=>'required',
            'inventory_id'=>'required',
     		'price'=>'required'
        ]);   

        $item = InventoryItem::find($request->id);
        $item->company_id = $request->company_id;
        $item->inventory_id = $request->inventory_id;
        $item->tags = $request->tags;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->image = $request->image;
        $item->status = 1;
        if($item->save()){
			Flash::success('Successfully updated inventory item!');
			return Redirect::route('inventories_index');
        }
    }

    public function postOrder(Request $request) {

    	$items = $request->item;
    	if($items !== '') {
    		foreach ($items as $key => $value) {
		        $inventory = InventoryItem::find($value['id']);
		        $inventory->ordered = $value['ordered'];  
		        $inventory->save();	
    		}
    	}


		Flash::success('Successfully updated inventory items order!');
		return Redirect::route('inventories_index'); 	
    }
}
