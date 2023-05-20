<?php

use Illuminate\Http\Request;

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


Route::post('login', 'AuthController@login');
Route::post('admin-login', 'AuthController@loginUser');
Route::post('admin-register', 'AuthController@registerAdmin');
Route::post('register', 'AuthController@register');

// Route::get('user', 'AuthController@getAuthUser');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@getAuthUser');
});

Route::group(['middleware' => 'auth:kurir'], function () {
    Route::get('kurir', 'AuthController@getKurir');
    Route::post('submit-pengiriman', 'PengirimanController@submitPengiriman');
});

Route::group(['middleware' => 'admin'], function () {
    // Barang CRUD routes
    Route::get('barang', 'BarangController@index');
    Route::get('barang/{id}', 'BarangController@show');
    Route::post('barang', 'BarangController@store');
    Route::put('barang/{id}', 'BarangController@update');
    Route::delete('barang/{id}', 'BarangController@destroy');
});

Route::group(['middleware' => 'admin'], function () {
    // Lokasi CRUD routes
    Route::get('lokasi', 'LokasiController@index');
    Route::get('lokasi/{id}', 'LokasiController@show');
    Route::post('lokasi', 'LokasiController@store');
    Route::put('lokasi/{id}', 'LokasiController@update');
    Route::delete('lokasi/{id}', 'LokasiController@destroy');
});

Route::group(['middleware' => 'admin'], function () {
    // Lokasi CRUD routes
    Route::get('pengiriman', 'PengirimanController@index');
    Route::get('pengiriman/{id}', 'PengirimanController@show');
    Route::post('pengiriman', 'PengirimanController@store');
    Route::put('pengiriman/{id}', 'PengirimanController@update');
    Route::delete('pengiriman/{id}', 'PengirimanController@destroy');
    Route::put('approve-pengiriman/{no_pengiriman}', 'PengirimanController@approvePengiriman');
});
