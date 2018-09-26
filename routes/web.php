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

Route::get('/', [
    'uses' => 'StudentController@index' , 
    'as' => 'students.index'
]);

Route::get('/students', [
	'uses' => 'StudentController@index' , 
    'as' => 'students.index'
]);

Route::get('/attendance', [
	'uses' => 'AttendanceController@index' , 
    'as' => 'attendance.index'
]);

Route::post('/attendance', [
	'uses' => 'AttendanceController@index' , 
    'as' => 'attendance.index'
]);

Route::get('/messages', [
	'uses' => 'AttendanceController@messages' , 
    'as' => 'messages.index'
]);

Route::get('/groups', [
	'uses' => 'GroupController@index' , 
    'as' => 'groups.index'
]);

Auth::routes();
