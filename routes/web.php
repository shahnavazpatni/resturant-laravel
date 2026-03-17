<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

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
Route::group(['namespace'=>'Website'], function(){
    Route::get('/', 'HomeController@index')->name('home.index');
    Route::post('make/reverse', 'ReservationController@reverse')->name('make.reverse');
    Route::post('send/message', 'ContactController@sendMessage')->name('send.message');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix'=>'admin', 'middleware'=>'auth', 'namespace'=>'Admin'], function(){
    Route::get('/', 'DashboardController@index')->name('admin.index');
    Route::get('reservation/dashboard', 'DashboardController@reserve')->name('reservation.dashboard');

    // Slider Page All Routes Here
    Route::get('slider', 'SliderController@index')->name('slider.index');
    Route::get('slider/read', 'SliderController@getAll')->name('slider.read');
    Route::post('slider/store', 'SliderController@store')->name('slider.store');
    Route::get('slider/status/{id}', 'SliderController@status');
    Route::delete('slider/{id}/destroy', 'SliderController@destroy');
    Route::get('slider/{id}/edit', 'SliderController@edit');
    Route::put('slider/{id}/update', 'SliderController@update')->name('slider.update');

    // Category Page All Routes Here
    Route::get('category', 'CategoryController@index')->name('category.index');
    Route::delete('category/{id}/delete', 'CategoryController@destroy');
    Route::post('category/store', 'CategoryController@store')->name('category.store');
    Route::get('category/{id}/edit', 'CategoryController@edit');
    Route::put('category/{id}/update', 'CategoryController@update')->name('category.update');
    Route::get('item/category', 'CategoryController@getCategory');

    // Item Page All Routes Here
    Route::get('item', 'ItemController@index')->name('item.index');
    Route::delete('item/{id}/destroy', 'ItemController@destroy');
    Route::post('item/store', 'ItemController@store')->name('item.store');
    Route::get('item/{id}/edit', 'ItemController@edit');
    Route::put('item/{id}/update', 'ItemController@update');

    // Reservation Page All Routes Here
    Route::get('reservation', 'ReservationController@index')->name('reservation.index');
    Route::get('reservation/{id}/status', 'ReservationController@status');
    Route::delete('reservation/{id}/destroy', 'ReservationController@destroy');
    

    // Contact Page All Routes Here
    Route::get('contact', 'ContactController@index')->name('contact.index');
    Route::get('contact/{id}/show', 'ContactController@show');
    Route::delete('contact/{id}/destroy', 'ContactController@destroy');
});
