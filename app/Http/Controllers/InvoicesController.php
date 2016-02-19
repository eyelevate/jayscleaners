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

    public function getAdd(){
        return view('invoices.add')
        ->with('layout',$this->layout);
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