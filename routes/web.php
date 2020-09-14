<?php


Route::get('/', function () {
    return view('welcome');
});

Route::get('customers/{id}/restore', 'CustomersController@restore')->middleware('auth')->name('customers.restore');
Route::resource('customers', 'CustomersController')->except('show')->middleware('auth');
Route::get('orders/{id}/restore', 'OrdersController@restore')->middleware('auth')->name('orders.restore');
Route::resource('orders', 'OrdersController')->except('show')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
