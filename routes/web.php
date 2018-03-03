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
    return view('watch');
});



Route::post('/ticker/{options}', 'CoinigyController@tickerByExchangeMarketId');

Route::get('/twilio/sms', 'MessageController@sendSms');

Route::get('/test', 'TestController@test');

Route::get('/getpairs/{exchange_code}', 'CoinigyController@getPairs');

Route::get('/getexchanges', 'CoinigyController@getExchanges');

Route::get('/check/exchange/pairs/{exchange_code}', 'CoinigyController@getExchange');

Route::get('/coinpairs', 'CoinPairController@index');

Route::get('/search', "SearchController@search");