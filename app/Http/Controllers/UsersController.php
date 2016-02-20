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
use App\Layout;

class UsersController extends Controller
{
    public function __construct() {

        //Set controller variables
    	$this->layout = 'layouts.admin';

    }  
    public function getIndex() {

        return view('users.index')
        ->with('layout',$this->layout);
    }

    public function postIndex(Request $request) {
        //Validate the request
        $this->validate($request, [
            'search_query' => 'required'
        ]);    

        // Search has validated start searching
        $results = User::prepareResults($request->search_query);
        
        return view('users.index')
        ->with('layout',$this->layout);

    }
}

?>