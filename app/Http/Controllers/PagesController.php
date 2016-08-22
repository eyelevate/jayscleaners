<?php

namespace App\Http\Controllers;

use Input;
use Validator;
use Redirect;
use Hash;
use Request;
use Route;
use Response;
use Auth;
use URL;
use Session;
use Laracasts\Flash\Flash;
use View;


use App\Job;
use App\User;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function __construct() {
    	$this->layout = 'layouts.home';
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $auth = (Auth::check()) ? Auth::user() : False;
        return view('pages.index')
        ->with('layout',$this->layout)
        ->with('auth',$auth);
    }
}
