<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//ส่วนของลูกค้า
Route::post('login','App\Http\Controllers\ApiController@login');
Route::post('create','App\Http\Controllers\ApiController@create');
Route::post('showprofile','App\Http\Controllers\ApiController@showprofile');
Route::post('updatename','App\Http\Controllers\ApiController@updatename');
Route::get('showcar','App\Http\Controllers\ApiController@showcar');
Route::get('showcolor','App\Http\Controllers\ApiController@showcolor');
Route::post('carmember','App\Http\Controllers\ApiController@carmember');
Route::post('history','App\Http\Controllers\ApiController@history');
Route::post('listcarcare', 'App\Http\Controllers\ApiController@listcarcare');
Route::post('listcarmember','App\Http\Controllers\ApiController@listcarmember');
Route::post('attribute','App\Http\Controllers\ApiController@attribute');
Route::post('queue','App\Http\Controllers\ApiController@queue');
Route::post('checkstatus','App\Http\Controllers\ApiController@checkstatus');
Route::post('checkqueue','App\Http\Controllers\ApiController@checkqueue');
Route::post('updatequeue','App\Http\Controllers\ApiController@updatequeue');
Route::post('RatingReview','App\Http\Controllers\ApiController@RatingReview');
Route::post('sentstatus','App\Http\Controllers\ApiController@sentstatus');
Route::post('sentstatuscencel','App\Http\Controllers\ApiController@sentstatuscencel');

//ส่วนของคาร์แคร์
Route::post('logincarcare','App\Http\Controllers\CarcareController@login');
Route::post('createcarcare','App\Http\Controllers\CarcareController@create');
Route::post('addcarcare','App\Http\Controllers\CarcareController@addcarcare');
Route::post('addname','App\Http\Controllers\CarcareController@addname');
Route::post('menucarcare','App\Http\Controllers\CarcareController@menucarcare');
Route::post('updatecarcare','App\Http\Controllers\CarcareController@updatecarcare');
Route::get('member_carcare','App\Http\Controllers\CarcareController@member_carcare');
Route::post('addmember','App\Http\Controllers\CarcareController@addmember');
Route::post('listmembercarcare','App\Http\Controllers\CarcareController@listmembercarcare');
Route::post('gettoken','App\Http\Controllers\CarcareController@gettoken');
Route::post('listattribute','App\Http\Controllers\CarcareController@listattribute');
Route::post('addattribute','App\Http\Controllers\CarcareController@addattribute');
Route::post('index','App\Http\Controllers\CarcareController@index');
Route::post('listreportqueue','App\Http\Controllers\CarcareController@listreportqueue');
Route::post('menureport','App\Http\Controllers\CarcareController@menureport');
Route::post('menuattribute','App\Http\Controllers\CarcareController@menuattribute');
