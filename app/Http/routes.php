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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function() {

    // Simple heartbeat
    Route::get('heartbeat', "DefaultController@getHeartbeat");

    // Login / register, unprotected
    Route::group(['prefix' => 'user'], function() {
        Route::post('create', "UserController@createUser");
        Route::post('login', "UserController@loginUser");
    });

    // Everything else, protected
    Route::group(['middleware' => ['hackbu']], function() {

		Route::group(['prefix' => 'events'], function() {

			Route::get('list', "EventsController@listEvents");
			Route::post('create', "EventsController@createEvent");
			Route::post('register', "EventsController@registerForEvent");
			Route::patch('checkin', "EventsController@checkinUser");

		});

		

    });

});

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
});
