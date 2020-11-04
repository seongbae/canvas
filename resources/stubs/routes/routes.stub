<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get('/', [WelcomeController::class, 'index']);
Route::get('home', [HomeController::class, 'index']);

Route::group(['namespace' => '\Seongbae\Canvas\Http\Controllers', 'middleware' => ['web']], function () {


	Route::get('account', 'UserController@getUser');
	Route::put('account/{id}/profile', 'UserController@updateProfile');
	Route::post('account/{id}/password', 'UserController@updatePassword');

	Route::get('dynamicModal/{id}',[
	    'as'=>'dynamicModal',
	    'uses'=> 'Admin\MediaController@loadModal'
	]);		


});