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

Route::get('/', function ()
{
    return view('welcome');
})->middleware('guest')->name('login');

Route::get('/register', 'Auth\RegisterController@create')->name('registerFormDisplay');
Route::post('/register-save', 'Auth\RegisterController@store')->name('registerSave');
Route::post('/login-submit', 'Auth\LoginController@Login')->name('loginTry');
Route::post('/logout','Auth\LoginController@Logout')->name('logout');

Route::get('/events-panel','EventController@index');
Route::get('/events-panel-data','EventController@indexData');
Route::get('/events-create','EventController@create')->name('create.event');
Route::post('/events-save','EventController@store');
Route::delete('/events-remove/{id}','EventController@delete');

Route::post('/criteria-store','Criteria\CriteriaController@store')->name('criteria.adding');
Route::get('/criteria-index','Criteria\CriteriaController@index');
Route::delete('/criteria-delete/{id}','Criteria\CriteriaController@delete');

Route::get('/setup-create/{eventId}','SetupController@create');
Route::post('/setup-store','SetupController@store');
Route::get('/setup-index','SetupController@index');
Route::get('/setup-index-data','SetupController@indexData');
Route::delete('/setup-delete/{id}','SetupController@delete');
Route::put('/setup-enable/{id}','SetupController@enable');
Route::put('/setup-disable/{id}','SetupController@disable');

Route::get('/rating-create','RatingController@create');
Route::post('/rating-store','RatingController@storeRate');
Route::put('/rating-update/{setupId}','RatingController@updateRate');
Route::get('/rating-create-data','RatingController@createData');
Route::get('/rating-get-data/{setupId}','RatingController@Contestant');

Route::get('/results-show/{setupId}','ResultsController@show')->middleware('IfNotReady');
Route::get('/get-own-score-rank/{setupid}','RatingController@getOwnScoreRanking');

Route::get('/account-index','AccountController@index');
Route::delete('/account-delete/{id}','AccountController@delete');
Route::get('/account-index-data','AccountController@indexData');
Route::get('/account-fetch-data/{id}','AccountController@fetchuser');
Route::put('/account-update/{id}','AccountController@update');
Route::put('/account-update-as-admin/{id}','AccountController@setAdminRole');
Route::put('/account-update-as-judge/{id}','AccountController@setJudgeRole');
Route::put('/account-update-enable-toggle/{id}','AccountController@toggleEnable');

Route::post('/contestant-add/{setupid}','ContestantController@store');
Route::delete('/contestant-remove/{contestantid}','ContestantController@delete');

Route::get('/check-if-done/{setupid}','RatingController@checkifDone');
