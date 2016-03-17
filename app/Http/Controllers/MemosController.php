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
use App\Memo;
use App\Layout;

class MemosController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }
    public function getIndex(){
    	$memos = Memo::where('company_id',Auth::user()->company_id)->orderBy('ordered','asc')->get();

    	return view('memos.index')
    	->with('layout',$this->layout)
    	->with('memos',$memos);
    }  
    public function postAdd(Request $request) {
        $this->validate($request, [
            'memo' => 'required|min:1'
        ]); 
        $memo = new Memo;
        $memo->memo = $request->memo;
        $memo->company_id = Auth::user()->company_id;
        $memo->ordered = null;
        if($memo->save()){
			Flash::success('Successfully added a new memo!');
			return Redirect::route('memos_index');        	
        }

    }  

    public function postDelete(Request $request) {
        $memo = Memo::find($request->id);
        $memo->status = 2;
        if($memo->delete()){
			Flash::success('Successfully deleted a memo!');
			return Redirect::route('memos_index');
        }        	
    }

    public function postEdit(Request $request) {
        $this->validate($request, [
            'memo' => 'required|min:1'
        ]); 
        $memo = Memo::find($request->id);
        $memo->memo = $request->memo;

        if($memo->save()){
			Flash::success('Successfully edited memo!');
			return Redirect::route('memos_index');        	
        }

    }  

    public function postOrder(Request $request) {
    	$memos = Input::get('memos');
    	if(isset($memos)) {
    		foreach ($memos as $key => $value) {
    			$memo = Color::find($key);
    			$memo->ordered = $value['order'];
    			$memo->save();
    		}
			Flash::success('Successfully ordered your memos!');
			
    	} else {
 			Flash::warning('No memos to order!');
   		
    	}
		return Redirect::route('memos_index'); 

    }
}
