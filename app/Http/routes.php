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
    Route::get('/', ['as'=>'pages_index', 'uses' => 'PagesController@getIndex']);
    Route::get('/login',['as'=>'pages_login','uses'=>'PagesController@getLogin']);
    Route::post('/login',  ['as'=>'pages_login_post', 'uses' => 'PagesController@postLogin']);
    Route::get('/logout',  ['as'=>'pages_logout', 'uses' => 'PagesController@getLogout']);
    Route::post('/logout',  ['as'=>'pages_logout_post', 'uses' => 'PagesController@postLogout']);
    Route::post('/zipcodes',  ['as'=>'pages_zipcodes', 'uses' => 'PagesController@postZipcodes']);
    Route::get('/register',['as'=>'pages_registration','uses'=>'PagesController@getRegistration']);
    Route::post('/register',  ['as'=>'pages_registration_post', 'uses' => 'PagesController@postRegistration']);    

    Route::get('/home', 'HomeController@index');

    // Admins
    Route::get('/admins/login',  ['as'=>'admins_login', 'uses' => 'AdminsController@getLogin']);
	Route::post('/admins/login',  ['as'=>'admins_login_post', 'uses' => 'AdminsController@postLogin']);
	Route::post('/admins/logout',  ['as'=>'admins_logout_post', 'uses' => 'AdminsController@postLogout']);
	Route::get('/admins/api/update/{id}/{api_token}/{server_at}/{up}/{upd}',['as'=>'admins_api_update','uses'=>'AdminsController@getApiUpdate']);
	Route::get('/admins/api/chunk/{id}/{api_token}/{table}/{start}/{end}',['as'=>'admins_api_chunk','uses'=>'AdminsController@getApiChunk']);
	Route::get('/admins/api/passmanage/{id}/{api_token}/{up}',['as'=>'admins_api_passmanage','uses'=>'AdminsController@getApiPassmanage']);
	Route::get('/admins/api/authenticate/{username}/{pw}',['as'=>'admins_api_authenticate','uses'=>'AdminsController@getAuthentication']);

	//Frontend Authentication
	Route::group(['middleware' => ['frontend']], function(){
		//Address
		Route::get('/address', ['as'=>'address_index','uses'=>'AddressesController@getIndex']);
		Route::get('/address/add', ['as'=>'address_add','uses'=>'AddressesController@getAdd']);
		Route::post('/address/add', ['as'=>'address_add_post','uses'=>'AddressesController@postAdd']);
		Route::get('/address/edit/{id}', ['as'=>'address_edit','uses'=>'AddressesController@getEdit']);
		Route::post('/address/edit', ['as'=>'address_edit_post','uses'=>'AddressesController@postEdit']);
		Route::get('/address/delete/{id}', ['as'=>'address_delete','uses'=>'AddressesController@getDelete']);
		Route::get('/address/primary/{id}', ['as'=>'address_primary','uses'=>'AddressesController@getPrimary']);
		
		//Cards
		Route::get('/cards', ['as'=>'cards_index','uses'=>'CardsController@getIndex']);
		Route::get('/cards/add',['as'=>'cards_add','uses'=>'CardsController@getAdd']);
    	Route::post('/cards/add', ['as'=>'cards_add_post','uses'=>'CardsController@postAdd']);
    	Route::get('cards/edit/{id}', ['as' => 'cards_edit', 'uses'=>'CardsController@getEdit', function ($id) {}]);
		Route::post('/cards/edit',['as'=>'cards_edit_post','uses'=>'CardsController@postEdit']);
    	Route::get('/cards/edit_again',['as'=>'cards_edit_again','uses'=>'CardsController@getEditAgain']);
    	Route::get('cards/delete/{id}', ['as' => 'cards_delete', 'uses'=>'CardsController@getDelete', function ($id) {}]);
    	Route::get('/cards/delete_again',['as'=>'cards_delete_again','uses'=>'CardsController@getDeleteAgain']);

		//Delivery 
		Route::get('/delivery',['as'=>'delivery_index','uses'=>'DeliveriesController@getIndex']);
		Route::get('/delivery/confirmation', ['as'=>'delivery_confirmation','uses'=>'DeliveriesController@getConfirmation']);
    	Route::post('/delivery/confirmation', ['as'=>'delivery_confirmation_post','uses'=>'DeliveriesController@postConfirmation']);
		Route::get('/delivery/pickup', ['as'=>'delivery_pickup','uses'=>'DeliveriesController@getPickupForm']);
    	Route::post('/delivery/pickup', ['as'=>'delivery_pickup_post','uses'=>'DeliveriesController@postPickupForm']);
		Route::get('/delivery/dropoff', ['as'=>'delivery_dropoff','uses'=>'DeliveriesController@getDropoffForm']);
    	Route::post('/delivery/dropoff', ['as'=>'delivery_dropoff_post','uses'=>'DeliveriesController@postDropoffForm']);
    	Route::post('/delivery/check_address', ['as'=>'delivery_check_address','uses'=>'DeliveriesController@postCheckAddress']);
    	Route::post('/delivery/set_time', ['as'=>'delivery_set_time','uses'=>'DeliveriesController@postSetTime']);
    	Route::get('/delivery/cancel',['as'=>'delivery_cancel','uses'=>'DeliveriesController@getCancel']);
    	Route::get('delivery/delete/{id}', ['as' => 'delivery_delete', 'uses'=>'DeliveriesController@getDelete', function ($id) {}]);
    	Route::get('/delivery/history',['as'=>'delivery_history','uses'=>'DeliveriesController@getHistory']);
    	Route::get('/delivery/start',['as'=>'delivery_start','uses'=>'DeliveriesController@getStart']);
    	Route::get('/delivery/thank-you',['as'=>'delivery_thankyou','uses'=>'DeliveriesController@getThankYou']);
    	Route::get('/delivery/email-test',['as'=>'delivery_emailtest','uses'=>'DeliveriesController@getEmailTest']);
    	Route::get('delivery/update/{id}', ['as' => 'delivery_update', 'uses'=>'DeliveriesController@getUpdate', function ($id) {}]);
    	Route::post('/delivery/update',['as'=>'delivery_update_post','uses'=>'DeliveriesController@postUpdate']);
    	Route::post('/delivery/set_time_update',['as'=>'delivery_set_time_update','uses'=>'DeliveriesController@postSetTimeUpdate']);

    	Route::post('/pages/one-touch',['as'=>'pages_onetouch','uses'=>'PagesController@postOneTouch']);
	});

	//Authenticated Pages
	Route::group(['middleware' => ['auth']], function(){

		//Admins
		Route::get('/admins',  ['as'=>'admins_index', 'uses' => 'AdminsController@getIndex']);
		Route::get('/admins/add',['as'=>'admins_add','uses'=>'AdminsController@getAdd']);
		Route::post('/admins/add',['as'=>'admins_add_post','uses'=>'AdminsController@postAdd']);
		Route::get('/admins/edit/{id}',['as'=>'admins_edit','uses'=>'AdminsController@getEdit']);
		Route::post('/admins/edit',['uses'=>'AdminsController@postEdit']);
		Route::get('/admins/settings',['as'=>'admins_settings','uses'=>'AdminsController@getSettings']);
		Route::get('/admins/view',['as'=>'admins_view','uses'=>'AdminsController@getView']);
		Route::get('/admins/overview',['as'=>'admins_overview','uses'=>'AdminsController@getOverview']);
		Route::get('/admins/sales-data',['as'=>'admins_sales_data','uses'=>'AdminsController@getSalesData']);

		// Address
		
		Route::get('/address/admin/{id}', ['as'=>'address_admin_index','uses'=>'AddressesController@getAdminIndex']);
		Route::get('/address/admin/add/{id}', ['as'=>'address_admin_add','uses'=>'AddressesController@getAdminAdd']);
		Route::post('/address/admin/add', ['as'=>'address_admin_add_post','uses'=>'AddressesController@postAdminAdd']);
		Route::get('/address/admin/edit/{id}', ['as'=>'address_admin_edit','uses'=>'AddressesController@getAdminEdit']);
		Route::post('/address/admin/edit', ['as'=>'address_admin_edit_post','uses'=>'AddressesController@postAdminEdit']);
		Route::get('/address/admin/delete/{id}', ['as'=>'address_admin_delete','uses'=>'AddressesController@getAdminDelete']);
		Route::get('/address/admin/primary/{id}', ['as'=>'address_admin_primary','uses'=>'AddressesController@getAdminPrimary']);
		//Customers
		Route::get('/colors',  ['as'=>'colors_index', 'uses' => 'ColorsController@getIndex']);
		Route::get('/colors/add',['as'=>'colors_add','uses'=>'ColorsController@getAdd']);
		Route::post('/colors/add',['uses'=>'ColorsController@postAdd']);
		Route::post('/colors/edit',['uses'=>'ColorsController@postEdit']);
		Route::post('/colors/delete',  ['as'=>'colors_delete','uses' => 'ColorsController@postDelete']);
		Route::post('/colors/order',['uses'=>'ColorsController@postOrder']);

		//Cards
		Route::get('/cards/admins/{id}',  ['as'=>'cards_admins_index', 'uses' => 'CardsController@getAdminsIndex']);
		Route::get('/cards/admins/add/{id}',['as'=>'cards_admins_add','uses'=>'CardsController@getAdminsAdd']);
		Route::post('/cards/admins/add',['as'=>'cards_admins_add_post','uses'=>'CardsController@postAdminsAdd']);
		Route::get('/cards/admins/edit/{id}',['as'=>'cards_admins_edit','uses'=>'CardsController@getAdminsEdit']);
		Route::post('/cards/admins-edit',['as'=>'cards_admins_edit_post','uses'=>'CardsController@postAdminsEdit']);		
		Route::get('/cards/admins-edit-again',['as'=>'cards_admins_edit_again','uses'=>'CardsController@getAdminsEditAgain']);		
		Route::get('/cards/admins-delete/{id}', ['as' => 'cards_admins_delete', 'uses'=>'CardsController@getAdminsDelete']);
    	Route::get('/cards/admins-delete-again',['as'=>'cards_admins_delete_again','uses'=>'CardsController@getAdminsDeleteAgain']);


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


		// Delivery 
		Route::get('/delivery/overview',['as'=>'delivery_overview','uses'=>'DeliveriesController@getOverview']);
		Route::post('/delivery/overview',['as'=>'delivery_overview_post','uses'=>'DeliveriesController@postOverview']);
		Route::get('/delivery/admin-edit/{id}',  ['as'=>'delivery_admin_edit','uses' => 'DeliveriesController@getAdminEdit']);
		Route::post('/delivery/admin-edit',['as'=>'delivery_admin_edit_post','uses'=>'DeliveriesController@postAdminEdit']);
		Route::get('/delivery/new/{id}',  ['as'=>'delivery_new','uses' => 'DeliveriesController@getNew']);
		Route::post('/delivery/new',['as'=>'delivery_new_post','uses'=>'DeliveriesController@postNew']);
		Route::post('/delivery/find-customer',['as'=>'delivery_find_customer','uses'=>'DeliveriesController@postFindCustomer']);
		Route::post('delivery/make-schedule',['as'=>'delivery_make_schedule','uses'=>'DeliveriesController@postMakeSchedule']);
		Route::post('delivery/make-pickup-time',['as'=>'delivery_make_pickup_time','uses'=>'DeliveriesController@postMakePickupTime']);
		Route::post('delivery/make-dropoff-time',['as'=>'delivery_make_dropoff_time','uses'=>'DeliveriesController@postMakeDropoffTime']);
		Route::post('delivery/redo-dropoff-schedule',['as'=>'delivery_redo_dropoff_schedule','uses'=>'DeliveriesController@postRedoDropoffSchedule']);
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

		// Reports
		Route::get('/reports',  ['as'=>'reports_index', 'uses' => 'ReportsController@getIndex']);
		Route::post('/reports', ['as'=>'reports_index_post', 'uses'=>'ReportsController@postIndex']);
		Route::get('/reports/make/{start}/{end}/{company_id}', ['as'=>'reports_make', 'uses'=>'ReportsController@getMake']);
		// Schedules
		Route::get('/schedules/checklist',['as'=>'schedules_checklist','uses'=>'SchedulesController@getChecklist']);
		Route::post('/schedules/checklist',['as'=>'schedules_checklist_post','uses'=>'SchedulesController@postChecklist']);
		Route::get('/schedules/delivery-route',['as'=>'schedules_delivery_route','uses'=>'SchedulesController@getDeliveryRoute']);
		Route::post('/schedules/delivery-route',['as'=>'schedules_delivery_route_post','uses'=>'SchedulesController@postDeliveryRoute']);
		Route::get('/schedules/processing', ['as'=>'schedules_processing','uses' => 'SchedulesController@getProcessing']);
		Route::post('/schedules/processing',['as'=>'schedules_processing_post','uses'=>'SchedulesController@postProcessing']);
		Route::get('/schedules/view/{id}', ['as'=>'schedules_view','uses' => 'SchedulesController@getView']);
		

		Route::post('/schedules/approve-pickup', ['as'=>'schedules_approve_pickup','uses'=>'SchedulesController@postApprovePickup']);
		Route::post('/schedules/approve-dropoff', ['as'=>'schedules_approve_dropoff','uses'=>'SchedulesController@postApproveDropoff']);
		Route::post('/schedules/delay-delivery', ['as'=>'schedules_delay_delivery','uses'=>'SchedulesController@postDelayDelivery']);
		Route::post('/schedules/revert-pickup', ['as'=>'schedules_revert_pickup','uses'=>'SchedulesController@postRevertPickup']);
		Route::post('/schedules/revert-delay', ['as'=>'schedules_revert_delay','uses'=>'SchedulesController@postRevertDelay']);
		Route::post('/schedules/revert-dropoff', ['as'=>'schedules_revert_dropoff','uses'=>'SchedulesController@postRevertDropoff']);
		Route::post('/schedules/email-status', ['as'=>'schedules_email_status','uses'=>'SchedulesController@postEmailStatus']);
		
		Route::post('/schedules/approve-pickedup', ['as'=>'schedules_approve_pickedup','uses'=>'SchedulesController@postApprovePickedup']);
		Route::post('/schedules/approve-droppedoff', ['as'=>'schedules_approve_droppedoff','uses'=>'SchedulesController@postApproveDroppedOff']);
		Route::post('/schedules/approve-delivered', ['as'=>'schedules_approve_delivered','uses'=>'SchedulesController@postApproveDelivered']);
		Route::post('/schedules/route-options', ['as'=>'schedules_route_options','uses'=>'SchedulesController@postRouteOptions']);
		Route::post('/schedules/approve-processing', ['as'=>'schedules_approve_processing','uses'=>'SchedulesController@postApproveProcessing']);
		Route::post('/schedules/payment', ['as'=>'schedules_payment','uses'=>'SchedulesController@postPayment']); 
		Route::post('/schedules/revert-payment', ['as'=>'schedules_revert_payment','uses'=>'SchedulesController@postRevertPayment']);
		Route::post('/schedules/select-invoice-row', ['as'=>'schedules_select_invoice_row','uses'=>'SchedulesController@postSelectInvoiceRow']); 
		Route::post('/schedules/remove-invoice-row', ['as'=>'schedules_remove_invoice_row','uses'=>'SchedulesController@postRemoveInvoiceRow']); 

		//Taxes
		Route::get('/taxes',['as'=>'taxes_index','uses'=>'TaxesController@getIndex']);
		Route::post('/taxes/update',['uses'=>'TaxesController@postUpdate']);

		//Users
		Route::get('/users',  ['as'=>'users_index', 'uses' => 'UsersController@getIndex']);
		Route::post('/users',['uses' => 'UsersController@postIndex']);	

		//Zipcodes
		Route::get('/zipcodes', ['as'=>'zipcodes_index', 'uses' => 'ZipcodesController@getIndex']);
		Route::get('/zipcodes/add', ['as'=>'zipcodes_add', 'uses' => 'ZipcodesController@getAdd']);
		Route::get('/zipcodes/edit/{id}', ['as'=>'zipcodes_edit', 'uses' => 'ZipcodesController@getEdit']);
		//ACL Rules
	});

});

