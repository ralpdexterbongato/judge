<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('guest')->name('login');

Route::get('/register', 'Auth\RegisterController@create')->name('registerFormDisplay');
Route::post('/register-save', 'Auth\RegisterController@store')->name('registerSave');
Route::post('/login-submit', 'Auth\LoginController@Login')->name('loginTry');
Route::post('/logout','Auth\LoginController@Logout')->name('logout');

Route::get('/events-panel','EventController@index');
Route::get('/events-create','EventController@create')->name('create.event');
Route::post('/events-save','EventController@store');

Route::post('/criteria-store','Criteria\CriteriaController@store')->name('criteria.adding');
Route::get('/criteria-index','Criteria\CriteriaController@index');

Route::get('/setup-create/{eventId}','SetupController@create');
Route::post('/setup-store','SetupController@store');
Route::get('/setup-index','SetupController@index');
Route::get('/setup-index-data','SetupController@indexData');
Route::delete('/setup-delete/{id}','SetupController@delete');
Route::put('/setup-enable/{id}','SetupController@enable');
Route::put('/setup-disable/{id}','SetupController@disable');

Route::get('/rating-create','RatingController@create');
Route::post('/rating-store','RatingController@store');
Route::put('/rating-update/{setupId}','RatingController@update');
Route::get('/rating-create-data','RatingController@createData');
Route::get('/rating-get-data/{setupId}/{contestant}','RatingController@Contestant');

Route::get('/results-show/{setupId}','ResultsController@show');
