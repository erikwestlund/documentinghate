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
Route::get('/add', 'IncidentAddController@create');
Route::post('/add', 'IncidentAddController@store');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/admin', 'AdminHomeController@show');

});

Route::group(['middleware' => ['permission:edit-users']], function () {

    Route::get('/admin/users', 'AdminUsersHomeController@show');
    Route::get('/admin/users/{user}', 'AdminUsersEditController@edit');
    Route::patch('/admin/users/{user}', 'AdminUsersEditController@update');
    Route::get('/admin/users/{user}/delete', 'AdminUsersEditController@delete');
    Route::delete('/admin/users/{user}', 'AdminUsersEditController@destroy');
    
});
