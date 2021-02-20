<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataTableAjaxCRUDController;

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

Route::get('login', function () {
    if (session()->has('user')) {
        return redirect('/');
    }
    return view('loginadmin');
});
Route::get('logout', function () {
    if (session()->has('user')) {
        session()->pull('user');
    }
    return view('loginadmin');
});
//Route::view('profile', 'profile');
Route::get('/', function () {
    if (session()->has('user')) {
        return view('profile');
    }
    return redirect('login');
});
Route::get('profile', function () {
    if (session()->has('user')) {
        return redirect('/');
    }
    return redirect('login');
});
Route::get('demo', function () {
    if (session()->has('user')) {
        return redirect('/');
    }
    return redirect('login');
});

Route::post('checklogin', 'App\Http\Controllers\DashboardController@checklogin');
Route::post('chart', 'App\Http\Controllers\DashboardController@checklogin');
Route::post('user', [UserAuth::class,'userLogin']);
Route::get('getlistcount', [DashboardController::class,'getlistcount']);
Route::get('getlistcountcarcare', [DashboardController::class,'getlistcountcarcare']);

Route::get('membercustomer', [DashboardController::class, 'index']);
Route::post('store-member', [DashboardController::class, 'store']);
Route::post('edit-member', [DashboardController::class, 'edit']);
Route::post('delete-member', [DashboardController::class, 'destroy'])->name('delete');
