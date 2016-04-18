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
    //public pages
    Route::auth();

    Route::get('/home', 'HomeController@index');
    Route::get('/admins/login',  ['as'=>'admins_login', 'uses' => 'AdminsController@getLogin']);
	Route::post('/admins/login',  ['as'=>'admins_login_post', 'uses' => 'AdminsController@postLogin']);
	Route::post('/admins/logout',  ['as'=>'admins_logout_post', 'uses' => 'AdminsController@postLogout']);

	//Authenticated Pages
	Route::group(['middleware' => ['auth']], function(){
		//Admins
		Route::get('/admins',  ['as'=>'admins_index', 'uses' => 'AdminsController@getIndex']);
		Route::get('/admins/add',['as'=>'admins_add','uses'=>'AdminsController@getAdd']);
		Route::post('/admins/add',['as'=>'admins_add_post','uses'=>'AdminsController@postAdd']);
		Route::get('/admins/edit/{id}',['as'=>'admins_edit','uses'=>'AdminsController@getEdit']);
		Route::post('/admins/edit',['uses'=>'AdminsController@postEdit']);
		Route::get('/admins/settings',['as'=>'admins_settings','uses'=>'AdminsController@getSettings']);
		Route::get('/admins/view/{id}',['as'=>'admins_view','uses'=>'AdminsController@getView']);
		Route::get('/admins/overview',['as'=>'admins_overview','uses'=>'AdminsController@getOverview']);
		Route::post('/admins/api/update',['as'=>'admins_api_update','uses'=>'AdminsController@postApiUpdate']);

		//Customers
		Route::get('/colors',  ['as'=>'colors_index', 'uses' => 'ColorsController@getIndex']);
		Route::get('/colors/add',['as'=>'colors_add','uses'=>'ColorsController@getAdd']);
		Route::post('/colors/add',['uses'=>'ColorsController@postAdd']);
		Route::post('/colors/edit',['uses'=>'ColorsController@postEdit']);
		Route::post('/colors/delete',  ['as'=>'colors_delete','uses' => 'ColorsController@postDelete']);
		Route::post('/colors/order',['uses'=>'ColorsController@postOrder']);

		//Customers
		Route::get('/companies',  ['as'=>'companies_index', 'uses' => 'CompaniesController@getIndex']);
		Route::get('/companies/add',['as'=>'companies_add','uses'=>'CompaniesController@getAdd']);
		Route::post('/companies/add',['uses'=>'CompaniesController@postAdd']);
		Route::get('/companies/edit/{id}',['as'=>'companies_edit','uses'=>'CompaniesController@getEdit']);
		Route::post('/companies/edit',['uses'=>'CompaniesController@postEdit']);
		Route::post('/companies/delete',  ['as'=>'companies_delete','uses' => 'CompaniesController@postDelete']);
		Route::get('/companies/operation',['as'=>'companies_operation','uses'=>'CompaniesController@getOperation']);
		Route::post('/companies/operation',['uses'=>'CompaniesController@postOperation']);

		//Customers
		Route::get('/customers',  ['as'=>'customers_index', 'uses' => 'CustomersController@getIndex']);
		Route::post('/customers',['as'=>'customers_index_post','uses'=>'CustomersController@postIndex']);
		Route::get('/customers/add',['as'=>'customers_add','uses'=>'CustomersController@getAdd']);
		Route::post('/customers/add',['uses'=>'CustomersController@postAdd']);
		Route::get('/customers/edit/{id}',['as'=>'customers_edit','uses'=>'CustomersController@getEdit']);
		Route::post('/customers/edit',['uses'=>'CustomersController@postEdit']);
		Route::get('/customers/delete/{id}',  ['as'=>'customers_delete','uses' => 'CustomersController@getDelete']);
		Route::get('/customers/history/{id}',  ['as'=>'customers_history','uses' => 'CustomersController@getHistory']);
		Route::get('/customers/view',['as'=>'customers_view','uses'=>'CustomersController@getView']);
		Route::get('/customers/view/{id}',['as'=>'customers_view','uses'=>'CustomersController@getView']);

		//Invoices
		Route::get('/invoices',  ['as'=>'invoices_index', 'uses' => 'InvoicesController@getIndex']);
		Route::get('/invoices/dropoff/{id}',['as'=>'invoices_dropoff','uses'=>'InvoicesController@getAdd']);
		Route::post('/invoices/dropoff',['uses'=>'InvoicesController@postAdd']);
		Route::get('/invoices/edit/{id}',['as'=>'invoices_edit','uses'=>'InvoicesController@getEdit']);
		Route::post('/invoices/edit',['uses'=>'InvoicesController@postEdit']);
		Route::post('/invoices/feed',['uses'=>'InvoicesController@postFeed']);
		Route::get('/invoices/view/{id}',['as'=>'invoices_view','uses'=>'InvoicesController@getView']);
		Route::get('/invoices/pickup/{id}',['as'=>'invoices_pickup','uses'=>'InvoicesController@getPickup']);
		Route::post('/invoices/pickup',['uses'=>'InvoicesController@postPickup']);
		Route::get('/invoices/rack',['as'=>'invoices_rack','uses'=>'InvoicesController@getRack']);
		Route::post('/invoices/rack',['uses'=>'InvoicesController@postRack']);
		Route::get('/invoices/test',['as'=>'invoices_test','uses'=>'InvoicesController@getTest']);

		//Inventory
		Route::get('/inventories',  ['as'=>'inventories_index', 'uses' => 'InventoriesController@getIndex']);
		Route::get('/inventories/add',['as'=>'inventories_add','uses'=>'InventoriesController@getAdd']);
		Route::post('/inventories/add',['as'=>'inventories_add_post','uses'=>'InventoriesController@postAdd']);
		Route::post('/inventories/delete',['as'=>'inventories_delete_post','uses'=>'InventoriesController@postDelete']);
		Route::get('/inventories/edit/{id}',['as'=>'inventories_edit','uses'=>'InventoriesController@getEdit']);
		Route::post('/inventories/edit',['uses'=>'InventoriesController@postEdit']);
		Route::post('/inventories/order',['uses'=>'InventoriesController@postOrder']);
		Route::get('/inventories/view/{id}',['as'=>'inventories_view','uses'=>'InventoriesController@getView']);

		//Inventory
		Route::post('/items/add',['as'=>'items_add_post','uses'=>'InventoryItemsController@postAdd']);
		Route::post('/items/delete',['as'=>'items_delete_post','uses'=>'InventoryItemsController@postDelete']);
		Route::post('/items/edit',['uses'=>'InventoryItemsController@postEdit']);
		Route::post('/items/order',['uses'=>'InventoryItemsController@postOrder']);

		//Memos
		Route::get('/memos',  ['as'=>'memos_index', 'uses' => 'MemosController@getIndex']);
		Route::post('/memos/add',['uses'=>'MemosController@postAdd']);
		Route::post('/memos/delete',['as'=>'memos_delete_post','uses'=>'MemosController@postDelete']);
		Route::post('/memos/edit',['uses'=>'MemosController@postEdit']);
		Route::post('/memos/order',['uses'=>'MemosController@postOrder']);

		//Taxes
		Route::get('/taxes',['as'=>'taxes_index','uses'=>'TaxesController@getIndex']);
		Route::post('/taxes/update',['uses'=>'TaxesController@postUpdate']);

		//Users
		Route::get('/users',  ['as'=>'users_index', 'uses' => 'UsersController@getIndex']);
		Route::post('/users',['uses' => 'UsersController@postIndex']);		

		//ACL Rules
	});

});

