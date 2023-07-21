<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(['namespace'=>'\Seongbae\Canvas\Http\Controllers\Admin', 'middleware' => ['web','auth'], 'prefix'=>'admin'], function () {

    Route::get('/', 'AdminController@index')->name('admin.home');

    // Account controller
    Route::get('account', 'AdminController@showAccount');
    Route::post('account', 'AdminController@saveccount');

    // User management
    Route::resource('users/roles', 'RolesController', ['as'=>'admin']);
    Route::resource('users', 'UsersController', ['as'=>'admin']);

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