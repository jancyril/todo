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


Route::get('tasks/get', 'TasksController@get');
Route::patch('tasks/status/{task}', 'TasksController@updateStatus');
Route::resource('tasks', 'TasksController')->only(['show', 'edit', 'store', 'update', 'destroy']);

Route::get('/', function () {
    return view('master');
});
