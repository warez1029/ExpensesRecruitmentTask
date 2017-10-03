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
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('users', 'UsersController');
Route::get('/users', 'UsersController@index');
Route::get('/users/create', 'UsersController@create');
Route::get('/users/{id}', 'UsersController@show');
Route::get('/users/{id}/edit', 'UsersController@edit');

Route::resource('expenses', 'ExpensesController');
Route::get('/expenses', 'ExpensesController@index');
Route::get('/expenses/create', 'ExpensesController@create');
Route::get('/expenses/{id}', 'ExpensesController@show');
Route::get('/expenses/{id}/edit', 'ExpensesController@edit');

Route::resource('payments', 'PaymentsController');
Route::get('/payments', 'PaymentsController@index');
Route::get('/payments/{id}', 'PaymentsController@show');
Route::get('/payments/{id}/edit', 'PaymentsController@edit');