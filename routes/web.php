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


Route::get('/', 'SearchController@moveIndex');

Route::get('get-ticket', 'Auth\WeixinController@getTicket');
Route::get('login', 'Auth\WeixinController@login');
Route::get('logout', 'Auth\WeixinController@logout');

Route::get('index', 'IndexController@index')->middleware('reject.spider');
Route::get('search', 'SearchController@index')->middleware('reject.spider');
Route::get('get-items', 'SearchController@getItems');
Route::get('get-qrcode', 'SearchController@getQrCode');
Route::get('get-relative-content', 'SearchController@getRelativeContent');

Route::get('detail', 'SearchController@detail')->middleware('reject.spider');
Route::get('subscribe', 'SubscribeController@index');
Route::get('report', 'ReportController@index');
Route::get('report/get-list', 'ReportController@getList');


Route::middleware(['weixin.verify'])->group(function (){
    Route::get('accept', 'Auth\WeixinController@authToken');
    Route::post('accept', 'Auth\WeixinController@acceptMessage');
});

