<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as'=>'pages_index', 'uses' => 'PagesController@getIndex']);

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
    Route::auth();

    Route::get('/home', 'HomeController@index');
    Route::get('/admins',  ['as'=>'admins_index', 'uses' => 'AdminsController@getIndex']);
    Route::get('/admins/login',  ['as'=>'admins_login', 'uses' => 'AdminsController@getLogin']);
	Route::post('/admins/login',  ['as'=>'admins_login_post', 'uses' => 'AdminsController@postLogin']);
	Route::post('/admins/logout',  ['as'=>'admins_logout_post', 'uses' => 'AdminsController@postLogout']);
	Route::get('/admins/add',['as'=>'admins_add','uses'=>'AdminsController@getAdd']);
	Route::post('/admins/add',['as'=>'admins_post_add','uses'=>'AdminsController@postAdd']);
	Route::get('/admins/edit/{id}',['as'=>'admins_edit','uses'=>'AdminsController@getEdit']);
	Route::post('/admins/edit',['as'=>'admins_post_edit','uses'=>'AdminsController@postEdit']);
	Route::get('/admins/view/{id}',['as'=>'admins_view','uses'=>'AdminsController@getView']);
	Route::get('/admins/overview',['as'=>'admins_overview','uses'=>'AdminsController@getOverview']);

});

