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
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::group(['middleware' => ['web']], function () {
    //public pages
    Route::auth();
	// Admins
    Route::get('/admins/login',  ['as'=>'admins_login', 'uses' => 'AdminsController@getLogin']);
	Route::post('/admins/login',  ['as'=>'admins_login_post', 'uses' => 'AdminsController@postLogin']);
	Route::post('/admins/logout',  ['as'=>'admins_logout_post', 'uses' => 'AdminsController@postLogout']);
	Route::get('/admins/api/update/{id}/{api_token}/{server_at}/{up}/{upd}',['as'=>'admins_api_update','uses'=>'AdminsController@getApiUpdate']);
	Route::post('/admins/api/update', ['as'=>'admins_api_update_post','uses'=>'AdminsController@postApiUpdate']);
	Route::get('/admins/api/chunk/{id}/{api_token}/{table}/{start}/{end}',['as'=>'admins_api_chunk','uses'=>'AdminsController@getApiChunk']);
	Route::get('/admins/api/passmanage/{id}/{api_token}/{up}',['as'=>'admins_api_passmanage','uses'=>'AdminsController@getApiPassmanage']);
	Route::get('/admins/api/authenticate/{username}/{pw}',['as'=>'admins_api_authenticate','uses'=>'AdminsController@getAuthentication']);
	Route::get('/admins/api/auto/{table}',['as'=>'admins_api_auto','uses'=>'AdminsController@getApiAuto']);
	Route::post('/admins/api/auto', ['as'=>'admins_api_auto_post','uses'=>'AdminsController@postApiAuto']);

	#Api-Address
	Route::post('/admins/api/address-grab',['uses'=>'AdminsController@postApiAddressGrab']);
	Route::post('/admins/api/create-address',['uses'=>'AdminsController@postApiCreateAddress']);
    #Api-Card
    Route::post('/admins/api/card-grab',['uses'=>'AdminsController@postApiCardGrab']);
    Route::post('/admins/api/card-grab-root',['uses'=>'AdminsController@postApiCardGrabRoot']);
    Route::post('/admins/api/update-card',['uses'=>'AdminsController@postApiUpdateCard']);
    Route::post('/admins/api/create-card',['uses'=>'AdminsController@postApiCreateCard']);
    #Api-Colors
    Route::post('/admins/api/colors-query',['uses'=>'AdminsController@postApiColorsQuery']);
    #Api-Company
    Route::post('/admins/api/company-grab',['uses'=>'AdminsController@postApiCompanyGrab']);
    #Api-Credit
    Route::post('/admins/api/create-credit',['uses'=>'AdminsController@postApiCreateCredit']);
    Route::post('/admins/api/edit-credit',['uses'=>'AdminsController@postApiEditCredit']);
    Route::post('/admins/api/credit-query',['uses'=>'AdminsController@postApiCreditQuery']);
    #Api-Custid
    Route::post('/admins/api/check-mark',['uses'=>'AdminsController@postApiCheckMark']);
    Route::post('/admins/api/create-mark',['uses'=>'AdminsController@postApiCreateMark']);
    Route::post('/admins/api/delete-mark',['uses'=>'AdminsController@postApiDeleteMark']);
    Route::post('/admins/api/marks-query',['uses'=>'AdminsController@postApiMarksQuery']);
    #Api-delivery
    Route::post('/admins/api/delivery-grab',['uses'=>'AdminsController@postApiDeliveryGrab']);
    #Api-Discount
    Route::post('/admins/api/discount-query',['uses'=>'AdminsController@postApiDiscountQuery']);
    Route::post('/admins/api/discount-grab',['uses'=>'AdminsController@postApiDiscountGrab']);
    Route::post('/admins/api/discount-grab-by-company',['uses'=>'AdminsController@postApiDiscountGrabByCompany']);
    #Api-Inventory
    Route::post('/admins/api/inventory-grab',['uses'=>'AdminsController@postApiInventoryGrab']);
    Route::post('/admins/api/inventories-by-company',['uses'=>'AdminsController@postApiInventoriesByCompany']);
    #Api-InventoryItem
    Route::post('/admins/api/item-grab',['uses'=>'AdminsController@postApiItemGrab']);
    Route::post('/admins/api/delete-inventory-item',['uses'=>'AdminsController@postApiDeleteInventoryItem']);
	#Api-invites
	Route::post('/admins/api/create-invite',['uses'=>'AdminsController@postApiCreateInvite']);
	#Api-Invoice
    Route::post('/admins/api/invoice-data',['uses'=>'AdminsController@postApiInvoiceData']);
    Route::post('/admins/api/rack-single',['uses'=>'AdminsController@postRackSingle']);
    Route::post('/admins/api/delete-rack-single',['uses'=>'AdminsController@postDeleteRackSingle']);
    Route::post('/admins/api/sync-rackable-invoice',['uses'=>'AdminsController@postApiSyncRackableInvoice']);
    Route::post('/admins/api/sync-rackable-invoices',['uses'=>'AdminsController@postApiSyncRackableInvoices']);
    Route::post('/admins/api/create-invoice',['uses'=>'AdminsController@postApiCreateInvoice']);
    Route::post('/admins/api/edit-invoice',['uses'=>'AdminsController@postApiEditInvoice']);
    Route::post('/admins/api/invoice-grab',['uses'=>'AdminsController@postApiInvoiceGrab']);
    Route::post('/admins/api/invoice-grab-count',['uses'=>'AdminsController@postApiInvoiceGrabCount']);
    Route::post('/admins/api/invoice-search-history',['uses'=>'AdminsController@postApiInvoiceSearchHistory']);
    Route::post('/admins/api/invoice-grab-with-trashed',['uses'=>'AdminsController@postApiInvoiceGrabWithTrashed']);
    Route::post('/admins/api/invoice-query-transaction-id',['uses'=>'AdminsController@postApiInvoiceQueryTransactionId']);
    Route::post('/admins/api/invoice-grab-pickup',['uses'=>'AdminsController@postApiInvoiceGrabPickup']);
    Route::post('/admins/api/update-invoice-pickup',['uses'=>'AdminsController@postApiUpdateInvoicePickup']);
    Route::post('/admins/api/restore-invoice',['uses'=>'AdminsController@postApiRestoreInvoice']);
    Route::post('/admins/api/rack-invoice',['uses'=>'AdminsController@postApiRackInvoice']);
    Route::post('/admins/api/delete-invoice',['uses'=>'AdminsController@postApiDeleteInvoice']);
    Route::post('/admins/api/invoice-get-totals',['uses'=>'AdminsController@postApiInvoiceGetTotals']);
    Route::post('/admins/api/racks-by-company', ['uses'=>'AdminsController@getAllUsedRacks']);
    Route::post('/admins/api/rack-history', ['uses'=>'AdminsController@postApiRackHistoryByRack']);

    #Api-InvoiceItem
    Route::post('/admins/api/invoice-items-data',['uses'=>'AdminsController@postApiInvoiceItemsData']);
    Route::post('/admins/api/invoice-items-barcode',['uses'=>'AdminsController@postApiInvoiceItemsBarcode']);
    Route::post('/admins/api/invoice-items-rfid',['uses'=>'AdminsController@postApiInvoiceItemsRfid']);
    Route::post('/admins/api/set-barcode',['uses'=>'AdminsController@postApiSetBarcode']);
    Route::post('/admins/api/invoice-items-id-data',['uses'=>'AdminsController@postApiInvoiceItemsIdData']);
    Route::post('/admins/api/update-invoice-item-pretax',['uses'=>'AdminsController@postUpdateInvoiceItemPretax']);
    Route::post('/admins/api/create-tag',['uses'=>'AdminsController@postCreateTag']);
    Route::post('/admins/api/update-tag',['uses'=>'AdminsController@postUpdateTag']);
    Route::post('/admins/api/delete-tag',['uses'=>'AdminsController@postDeleteTag']);
    Route::post('/admins/api/invoice-item-grab',['uses'=>'AdminsController@postApiInvoiceItemGrab']);
    Route::post('/admins/api/create-invoice-item',['uses'=>'AdminsController@postApiCreateInvoiceItem']);
    Route::post('/admins/api/invoice-item-discount-find',['uses'=>'AdminsController@postApiInvoiceItemDiscountFind']);
    Route::post('/admins/api/invoice-item-discount-find-item-id',['uses'=>'AdminsController@postApiInvoiceItemDiscountFindItemId']);
    Route::post('/admins/api/edit-invoice-item',['uses'=>'AdminsController@postApiEditInvoiceItem']);
    Route::post('/admins/api/delete-invoice-items',['uses'=>'AdminsController@postApiDeleteInvoiceItems']);
    #Api-Memo
    Route::post('/admins/api/memos-query',['uses'=>'AdminsController@postApiMemosQuery']);
    #Api-Printer
    Route::get('/admins/api/print/{id}',['as'=>'admins_api_print','uses'=>'AdminsController@getApiPrint']);
    #Api-Profile
    Route::post('/admins/api/profiles-query',['uses'=>'AdminsController@postApiProfilesQuery']);
    Route::post('/admins/api/create-profile',['uses'=>'AdminsController@postApiCreateProfile']);
    #Api-Racks
    Route::post('/admins/api/remove-racks-from-list',['uses'=>'AdminsController@postApiRemoveRacksFromlist']);
    #Api-Schedule
    Route::post('/admins/api/create-schedule',['uses'=>'AdminsController@postApiCreateSchedule']);
    Route::post('/admins/api/schedule-query',['uses'=>'AdminsController@postApiScheduleQuery']);
    Route::post('/admins/api/schedule-grab',['uses'=>'AdminsController@postApiScheduleGrab']);
    #Api-Tax
    Route::post('/admins/api/taxes-query',['uses'=>'AdminsController@postApiTaxesQuery']);
    #Api-Transaction
    Route::post('/admins/api/transaction-grab',['uses'=>'AdminsController@postApiTransactionGrab']);
    Route::post('/admins/api/transaction-query',['uses'=>'AdminsController@postApiTransactionQuery']);
    Route::post('/admins/api/transaction-payment-query',['uses'=>'AdminsController@postApiTransactionPaymentQuery']);
    Route::post('/admins/api/create-transaction',['uses'=>'AdminsController@postApiCreateTransaction']);
    Route::post('/admins/api/pay-account',['uses'=>'AdminsController@postApiPayAccount']);
    Route::post('/admins/api/pay-account-customer',['uses'=>'AdminsController@postApiPayAccountCustomer']);
    Route::post('/admins/api/update-transaction',['uses'=>'AdminsController@postApiUpdateTransaction']);
    Route::post('/admins/api/last-transaction-grab',['uses'=>'AdminsController@postApiLastTransactionGrab']);
    Route::post('/admins/api/remove-invoice-by-transaction',['uses'=>'AdminsController@postApiRemoveInvoiceByTransaction']);
    #Api-User
    Route::post('/admins/api/scc',['uses'=>'AdminsController@postApiSearchCustomer']);
    Route::post('/admins/api/delete-customer',['uses'=>'AdminsController@postApiCustomerDelete']);
	Route::post('/admins/api/add-customer',['uses'=>'AdminsController@postApiAddCustomer']);
    Route::post('/admins/api/edit-customer',['uses'=>'AdminsController@postApiEditCustomer']);
    Route::get('/admins/api/sc/{query}',['uses'=>'AdminsController@getApiSearchCustomer']);
    Route::post('/admins/api/single-user-data',['uses'=>'AdminsController@postApiSingleUserData']);
    Route::post('/admins/api/sync-customer',['uses'=>'AdminsController@postApiSyncCustomer']);
    Route::post('/admins/api/check-account',['uses'=>'AdminsController@postApiCheckAccount']);
    Route::post('/admins/api/update-customer-account-total',['uses'=>'AdminsController@postApiUpdateCustomerAccountTotal']);
    Route::post('/admins/api/update-customer-credits',['uses'=>'AdminsController@postApiUpdateCustomerCredits']);
    Route::post('/admins/api/update-customer-pickup',['uses'=>'AdminsController@postApiUpdateCustomerPickup']);
    Route::post('/admins/api/customers-row-cap',['uses'=>'AdminsController@postApiCustomersRowCap']);
    Route::post('/admins/api/customers-search-results',['uses'=>'AdminsController@postApiCustomersSearchResults']);
    Route::post('/admins/api/customers-in',['uses'=>'AdminsController@postApiCustomersIn']);
    Route::post('/admins/api/cust-search-results',['uses'=>'AdminsController@postApiCustResults']); #for upgrade only
    Route::post('/admins/api/no-duplicates-create-customer',['uses'=>'UsersController@postApiNoDuplicateCreate']); #for upgrade only
    Route::post('/admins/api/user/edit',['uses'=>'UsersController@postApiEditCustomer']);
    // Route::get('/admins/api/single-user-data/{search}',['uses'=>'AdminsController@getSingleUserData']);

    // test only
    Route::get('/admins/api/test/wondo',['uses'=>'AdminsController@getApiTestWondo']);

    #Api-zipcode
    Route::post('/admins/api/zipcode-query',['uses'=>'AdminsController@postApiZipcodeQuery']);

    Route::group(['middleware' => ['forceSSL']], function(){
	   
	    Route::get('/', ['as'=>'pages_index', 'uses' => 'PagesController@getIndex']);
	    Route::get('/services',['as'=>'pages_services','uses'=>'PagesController@getServices']);
	    Route::get('/business-hours',['as'=>'pages_business_hours','uses'=>'PagesController@getBusinessHours']);
	    Route::get('/contact-us',['as'=>'pages_contact_us','uses'=>'PagesController@getContactUs']);
	    Route::get('/pricing',  ['as'=>'pages_pricing', 'uses' => 'PagesController@getPricing']);
	    Route::get('/terms-of-service',['as'=>'pages_terms','uses'=>'PagesController@getTerms']);
	    Route::get('/home', 'HomeController@index');

	    // Accounts
	    Route::get('/pay-my-bill',['as'=>'accounts_payMyBill','uses'=>'AccountsController@getPayMyBill']);
	    Route::get('/one-time-payment',['as'=>'accounts_oneTimePayment','uses'=>'AccountsController@getOneTimePayment']);
	    Route::get('/one-time-finish',['as'=>'accounts_oneTimeFinish','uses'=>'AccountsController@getOneTimeFinish']);
	    Route::post('/one-time-payment',['as'=>'accounts_oneTimePayment_post','uses'=>'AccountsController@postOneTimePayment']);
	    Route::post('/one-time-finish',['as'=>'accounts_oneTimeProcess_post','uses'=>'AccountsController@postOneTimeFinish']);


	    // Delivery Customer Page
	    Route::get('/login',['as'=>'pages_login','uses'=>'PagesController@getLogin']);
	    Route::post('/login',  ['as'=>'pages_login_post', 'uses' => 'PagesController@postLogin']);
	    Route::get('/logout',  ['as'=>'pages_logout', 'uses' => 'PagesController@getLogout']);
	    Route::post('/logout',  ['as'=>'pages_logout_post', 'uses' => 'PagesController@postLogout']);
	    Route::get('/reset-password/{token}',['as'=>'pages_reset_password','uses'=>'PagesController@getResetPassword']);
	    Route::post('/reset-password',['as'=>'pages_reset_password_post','uses'=>'PagesController@postResetPassword']);
	    Route::post('/zipcodes',  ['as'=>'pages_zipcodes', 'uses' => 'PagesController@postZipcodes']);
	    Route::get('/register',['as'=>'pages_registration','uses'=>'PagesController@getRegistration']);
	    Route::post('/register',  ['as'=>'pages_registration_post', 'uses' => 'PagesController@postRegistration']);    
	    Route::get('/zipcodes/request/{id}',['as'=>'zipcodes_request','uses'=>'ZipcodesController@getRequest']);
	    Route::post('/zipcodes/request',['as'=>'zipcodes_request_post','uses'=>'ZipcodesController@postRequest']);


		//Frontend Authentication
		Route::group(['middleware' => ['frontend']], function(){
			// Accounts
			Route::get('/member-payment',['as'=>'accounts_memberPayment','uses'=>'AccountsController@getMemberPayment']);
	    	Route::post('/member-payment',['as'=>'accounts_memberPayment_post','uses'=>'AccountsController@postMemberPayment']);
			Route::post('/member-file',['as'=>'accounts_memberFile_post','uses'=>'AccountsController@postMemberFile']);
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
	    	Route::get('/users/update', ['as' => 'users_update', 'uses'=>'UsersController@getUpdate']);
	    	Route::post('/users/update', ['as' => 'users_update_post', 'uses'=>'UsersController@postUpdate']);
	    	Route::get('/update-contact', ['as'=>'pages_update_contact','uses'=>'PagesController@getUpdateContact']);
	    	Route::post('/pages/one-touch',['as'=>'pages_onetouch','uses'=>'PagesController@postOneTouch']);
		});
	
		//Authenticated Pages
		Route::group(['middleware' => ['auth']], function(){
			// Accounts
			Route::get('/accounts',  ['as'=>'accounts_index', 'uses' => 'AccountsController@getIndex']);
			Route::post('/accounts',['as'=>'accounts_pay_post','uses'=>'AccountsController@postIndex']);
			Route::get('/accounts/pay/{id}',  ['as'=>'accounts_pay', 'uses' => 'AccountsController@getPay']);
			Route::post('/accounts/pay',['as'=>'accounts_pay_post','uses'=>'AccountsController@postPay']);
			Route::get('/accounts/history/{id}',  ['as'=>'accounts_history', 'uses' => 'AccountsController@getHistory']);
			Route::post('/accounts/update-total',['as'=>'accounts_update_total','uses'=>'AccountsController@postUpdateTotal']);
			Route::post('/accounts/revert',['as'=>'accounts_revert_post','uses'=>'AccountsController@postRevert']);
			Route::post('/accounts/bill',['as'=>'accounts_bill_post','uses'=>'AccountsController@postBill']);
			Route::get('/accounts/preview',['as'=>'accounts_preview','uses'=>'AccountsController@getPreview']);
			Route::get('/accounts/send',['as'=>'accounts_send','uses'=>'AccountsController@getSend']);
			Route::post('/accounts/send',['as'=>'accounts_send_post','uses'=>'AccountsController@postSend']);
			Route::post('/accounts/email-send',['as'=>'accounts_email_send_post','uses'=>'AccountsController@postEmailSend']);
			Route::post('/accounts/send-list',['as'=>'accounts_send_list','uses'=>'AccountsController@postSendList']);

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
			Route::get('/admins/dropoff-data',['as'=>'admins_dropoff_data','uses'=>'AdminsController@getDropoffData']);
			Route::get('/admins/reset-passwords',['as'=>'admins_reset_passwords','uses'=>'AdminsController@getResetPasswords']);
			Route::post('/admins/reset-passwords',['as'=>'admins_reset_passwords_post','uses'=>'AdminsController@postResetPasswords']);
			Route::get('/admins/duplicates',['as'=>'admins_duplicates','uses'=>'AdminsController@getDuplicates']);
			Route::post('/admins/duplicates',['as'=>'admins_duplicates_post','uses'=>'AdminsController@postDuplicates']);
			Route::get('/admins/rack-history',['as'=>'admins_rack_history','uses'=>'AdminsController@getRackHistory']);
			Route::post('/admins/rack-history',['as'=>'admins_rack_history_post','uses'=>'AdminsController@postRackHistory']);
			


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

			// Credits
			Route::post('/credits/add',['uses'=>'CreditsController@postAdd']);


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
			Route::get('/delivery/setup',['as'=>'delivery_setup','uses'=>'DeliveriesController@getSetup']);
			Route::get('/delivery/setup/add',['as'=>'delivery_setup_add','uses'=>'DeliveriesController@getSetupAdd']);
			Route::post('/delivery/setup/add',['as'=>'delivery_setup_add_post','uses'=>'DeliveriesController@postSetupAdd']);
			Route::get('/delivery/setup/edit/{id}',['as'=>'delivery_setup_edit','uses'=>'DeliveriesController@getSetupEdit']);
			Route::post('/delivery/setup/edit',['as'=>'delivery_setup_edit_post','uses'=>'DeliveriesController@postSetupEdit']);
			Route::get('/delivery/setup/delete/{id}',['as'=>'delivery_setup_delete','uses'=>'DeliveriesController@getSetupDelete']);


			// Discounts
			Route::get('/discounts',['as'=>'discounts_index','uses'=>'DiscountsController@getIndex']);
			Route::get('/discounts/add',['as'=>'discounts_add','uses'=>'DiscountsController@getAdd']);
			Route::get('/discounts/edit/{id}',['as'=>'discounts_edit','uses'=>'DiscountsController@getEdit']);
			Route::post('/discounts/add',['as'=>'discounts_add_post','uses'=>'DiscountsController@postAdd']);
			Route::post('/discounts/edit',['as'=>'discounts_edit_post','uses'=>'DiscountsController@postEdit']);
			Route::post('/discounts/delete',['as'=>'discounts_delete_post','uses'=>'DiscountsController@postDelete']);

			// Droutes
			Route::post('/droutes/revert',['as'=>'droutes_revert','uses'=>'SchedulesController@postRevertSchedule']);
			Route::get('/droutes/csv/{id}',['as'=>'droutes_csv','uses'=>'SchedulesController@getRouteCsv']);
			//Invoices
			Route::get('/invoices',['as'=>'invoices_index','uses'=>'InvoicesController@getIndex']);
			Route::get('/invoices/dropoff/{id}',['as'=>'invoices_dropoff','uses'=>'InvoicesController@getAdd']);
			Route::post('/invoices/dropoff',['uses'=>'InvoicesController@postAdd']);
			Route::get('/invoices/edit/{id}',['as'=>'invoices_edit','uses'=>'InvoicesController@getEdit']);
			Route::post('/invoices/edit',['uses'=>'InvoicesController@postEdit']);
			Route::post('/invoices/feed',['uses'=>'InvoicesController@postFeed']);
			Route::get('/invoices/view/{id}',['as'=>'invoices_view','uses'=>'InvoicesController@getView']);
			Route::get('/invoices/pickup/{id}',['as'=>'invoices_pickup','uses'=>'InvoicesController@getPickup']);
			Route::post('/invoices/pickup',['uses'=>'InvoicesController@postPickup']);
			Route::get('/invoices/rack/{id}',['as'=>'invoices_rack','uses'=>'InvoicesController@getRack']);
			Route::post('/invoices/rack',['uses'=>'InvoicesController@postRack']);
			Route::post('/invoices/rack-update',['as'=>'invoice_rack_update','uses'=>'InvoicesController@postRackUpdate']);
			Route::post('/invoices/rack-remove',['as'=>'invoice_rack_remove','uses'=>'InvoicesController@postRackRemove']);
			Route::get('/invoices/report/{id}',['as'=>'invoices_report','uses'=>'InvoicesController@getReport']);
			Route::get('/invoices/test',['as'=>'invoices_test','uses'=>'InvoicesController@getTest']);
			Route::get('/invoices/delete/{id}',['as'=>'invoices_delete','uses'=>'InvoicesController@getDelete']);
			Route::get('/invoices/pickup/{id}',['as'=>'invoices_pickup','uses'=>'InvoicesController@getPickup']);
			Route::post('/invoices/pickup',['uses'=>'InvoicesController@postPickup']);
			Route::post('/invoices/revert',['as'=>'invoices_revert','uses'=>'InvoicesController@postRevert']);
			Route::post('/invoices/select',['as'=>'invoices_select','uses'=>'InvoicesController@postSelect']);
			Route::get('/invoices/history/{id}',['as'=>'invoices_history','uses'=>'InvoicesController@getHistory']);
			Route::get('/invoices/manage',['as'=>'invoices_manage','uses'=>'InvoicesController@getManage']);
			Route::post('/invoices/manage',['as'=>'invoices_manage_post','uses'=>'InvoicesController@postManage']);
			Route::post('/invoices/manage-items',['as'=>'invoices_manage_items_post','uses'=>'InvoicesController@postManageItems']);
			Route::post('/invoices/manage-update',['as'=>'invoices_manage_update_post','uses'=>'InvoicesController@postManageUpdate']);
			Route::post('/invoices/manage-totals',['as'=>'invoices_manage_totals_post','uses'=>'InvoicesController@postManageTotals']);
			Route::post('/invoices/search',['as'=>'invoices_search','uses'=>'InvoicesController@postSearch']);
			//Inventory
			Route::get('/inventories',  ['as'=>'inventories_index', 'uses' => 'InventoriesController@getIndex']);
			Route::get('/inventories/add',['as'=>'inventories_add','uses'=>'InventoriesController@getAdd']);
			Route::post('/inventories/add',['as'=>'inventories_add_post','uses'=>'InventoriesController@postAdd']);
			Route::post('/inventories/delete',['as'=>'inventories_delete_post','uses'=>'InventoriesController@postDelete']);
			Route::get('/inventories/edit/{id}',['as'=>'inventories_edit','uses'=>'InventoriesController@getEdit']);
			Route::post('/inventories/edit',['uses'=>'InventoriesController@postEdit']);
			Route::post('/inventories/order',['uses'=>'InventoriesController@postOrder']);
			Route::post('/inventories/sort',['uses'=>'InventoriesController@postSort']);
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
			Route::get('/reports/view/{start}/{end}/{company_id}', ['as'=>'reports_view', 'uses'=>'ReportsController@getView']);
			

			// Schedules
			Route::get('/schedules/checklist',['as'=>'schedules_checklist','uses'=>'SchedulesController@getChecklist']);
			Route::post('/schedules/checklist',['as'=>'schedules_checklist_post','uses'=>'SchedulesController@postChecklist']);
			Route::get('/schedules/delivery-route/{id}',['as'=>'schedules_delivery_route','uses'=>'SchedulesController@getDeliveryRoute']);
			Route::post('/schedules/delivery-route',['as'=>'schedules_delivery_route_post','uses'=>'SchedulesController@postDeliveryRoute']);
			Route::get('/schedules/prepare-route',['as'=>'schedules_prepare_route','uses'=>'SchedulesController@getPrepareRoute']);
			Route::post('/schedules/prepare-route',['as'=>'schedules_prepare_route_post','uses'=>'SchedulesController@postPrepareRoute']);
			Route::get('/schedules/processing', ['as'=>'schedules_processing','uses' => 'SchedulesController@getProcessing']);
			Route::post('/schedules/processing',['as'=>'schedules_processing_post','uses'=>'SchedulesController@postProcessing']);
			Route::get('/schedules/view/{id}', ['as'=>'schedules_view','uses' => 'SchedulesController@getView']);
			Route::post('/schedules/admin-cancel',['as'=>'schedules_admin_cancel','uses'=>'SchedulesController@postAdminCancel']);
			Route::post('/schedules/sort',['as'=>'schedules_sort','uses'=>'SchedulesController@postSort']);
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
			Route::post('/schedules/setup-route',['as'=>'schedules_setup_route','uses'=>'SchedulesController@postSetupRoute']);


			//Taxes
			Route::get('/taxes',['as'=>'taxes_index','uses'=>'TaxesController@getIndex']);
			Route::post('/taxes/update',['uses'=>'TaxesController@postUpdate']);

			//Users
			Route::get('/users',  ['as'=>'users_index', 'uses' => 'UsersController@getIndex']);
			Route::post('/users',['uses' => 'UsersController@postIndex']);	

			//Zipcodes
			Route::get('/zipcodes', ['as'=>'zipcodes_index', 'uses' => 'ZipcodesController@getIndex']);
			Route::get('/zipcodes/add', ['as'=>'zipcodes_add', 'uses' => 'ZipcodesController@getAdd']);
			Route::post('/zipcodes/add', ['as'=>'zipcodes_add', 'uses' => 'ZipcodesController@postAdd']);
			Route::get('/zipcodes/edit/{id}', ['as'=>'zipcodes_edit', 'uses' => 'ZipcodesController@getEdit']);
			Route::post('/zipcodes/edit', ['as'=>'zipcodes_edit_post', 'uses' => 'ZipcodesController@postEdit']);
			Route::get('/zipcodes/delete/{id}', ['as'=>'zipcodes_delete', 'uses' => 'ZipcodesController@getDelete']);
			Route::post('/zipcodes/delete_zipcode',['as'=>'zipcodes_delete_post','uses'=>'ZipcodesController@postDelete']);


			Route::get('/zipcode-requests',['as'=>'zipcode_request_index','uses'=>'ZipcodeRequestsController@getIndex']);
			Route::get('/zipcode-requests/request-data',['as'=>'zipcode_request_request_data','uses'=>'ZipcodeRequestsController@getRequestData']);
			Route::post('/zipcode-requests/accept', ['as'=>'zipcode_request_accept', 'uses' => 'ZipcodeRequestsController@postAccept']);
			Route::post('/zipcode-requests/deny', ['as'=>'zipcode_request_deny', 'uses' => 'ZipcodeRequestsController@postDeny']);
			//ACL Rules
		});
	});

});

