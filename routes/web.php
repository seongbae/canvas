<?php



// Admin pages
Route::group(['namespace'=>'Seongbae\Canvas\Http\Controllers\Admin', 'prefix' => 'admin', 'middleware' => ['web','auth']], function () {
	
	// Admin home
	Route::get('/', 'AdminController@showHome');

	// Pages controller
	Route::resource('pages', 'PagesController', ['as'=>'admin']);
	
	// Account controller
	Route::get('account', 'AdminController@showAccount');
	Route::post('account', 'AdminController@saveccount');

	// User management
	Route::resource('users/roles', 'RolesController', ['as'=>'admin']);
	Route::resource('users', 'UsersController', ['as'=>'admin']);

	// Media management
	Route::resource('media', 'MediaController', ['as'=>'admin']);

	// Settings
	Route::get('settings', 'AdminController@showSettings')->name('admin.settings');
	Route::post('settings', 'AdminController@saveSettings');

	// Module management
	Route::get('modules/install/{module}', 'AdminController@installModule');
	Route::get('modules/uninstall/{module}', 'AdminController@uninstallModule');

	// Backend search
	Route::post('search', 'AdminController@search')->name('admin.search');

	// Log management	
	Route::get('logs/system', 'LogViewerController@index')->name('admin.logs.system');
	Route::get('logs/activity', 'AdminController@showActivityLogs')->name('admin.logs.activity');

});