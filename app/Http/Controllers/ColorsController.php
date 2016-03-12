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
use App\Color;
use App\Layout;

class ColorsController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }
    public function getIndex(){
    	$colors = Color::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();

    	return view('colors.index')
    	->with('layout',$this->layout)
    	->with('colors',$colors);
    }  

    public function postAdd(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:1',
            'color'=>'required',
        ]); 
        $color = new Color;
        $color->color = $request->color;
        $color->name = $request->name;
        $color->company_id = Auth::user()->company_id;
        $color->ordered = null;
        if($color->save()){
			Flash::success('Successfully added a new color!');
			return Redirect::route('colors_index');        	
        }

    }  

    public function postDelete(Request $request) {
        $color = Color::find($request->id);
        $color->status = 2;
        if($color->delete()){
			Flash::success('Successfully deleted color!');
			return Redirect::route('colors_index');
        }        	
    }

    public function postEdit(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:1',
            'color'=>'required',
        ]); 
        $color = Color::find($request->id);
        $color->color = $request->color;
        $color->name = $request->name;

        if($color->save()){
			Flash::success('Successfully edited color!');
			return Redirect::route('colors_index');        	
        }

    }  

    public function postOrder(Request $request) {
    	$colors = Input::get('color');
    	if(isset($colors)) {
    		foreach ($colors as $key => $value) {
    			$color = Color::find($key);
    			$color->ordered = $value['order'];
    			$color->save();
    		}
			Flash::success('Successfully ordered your colors!');
			return Redirect::route('colors_index'); 
    	} else {
 			Flash::warning('No colors to order!');
			return Redirect::route('colors_index');    		
    	}


    }
}
