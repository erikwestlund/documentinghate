<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@show');
Route::get('/about', 'AboutController@show');
Route::get('/acts/{id}-{slug}', 'IncidentPageController@show');
Route::get('/add', 'IncidentAddController@create');
Route::post('/add', 'IncidentAddController@store');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/admin', 'AdminHomeController@show');

});


Route::group(['middleware' => ['web', 'auth']], function () {

    Route::get('/admin/users', 'AdminUsersHomeController@show');
    Route::get('/admin/users/me', 'AdminUsersEditController@editMe');
    Route::get('/admin/users/{user}', 'AdminUsersEditController@edit');
    Route::patch('/admin/users/{user}', 'AdminUsersEditController@update');
    Route::get('/admin/users/{user}/delete', 'AdminUsersEditController@delete');
    Route::delete('/admin/users/{user}', 'AdminUsersEditController@destroy');
    
});

Route::group(['middleware' => ['auth', 'permission:moderate-incidents']], function () {

    Route::get('/admin/incidents', 'AdminIncidentsHomeController@show');
    Route::get('/admin/incidents/get', 'AdminIncidentsHomeController@getIncidents');
    Route::get('/admin/incidents/{incident}', 'AdminIncidentsModerateController@moderate');
    Route::patch('/admin/incidents/{incident}/approve', 'AdminIncidentsModerateController@approve');

});

Route::group(['middleware' => ['auth', 'permission:moderate-incidents']], function () {

    Route::patch('/admin/incidents/{incident}', 'AdminIncidentsModerateController@update');
    Route::get('/admin/incidents/{incident}/delete', 'AdminIncidentsModerateController@delete');
    Route::delete('/admin/incidents/{incident}', 'AdminIncidentsModerateController@destroy');
    
});