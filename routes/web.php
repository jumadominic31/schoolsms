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

Route::get('/attendance/sendcustommsg', [
	'uses' => 'AttendanceController@sendcustommsg' , 
    'as' => 'attendance.sendcustommsg'
]);

Route::get('/groups', [
	'uses' => 'GroupController@index' , 
    'as' => 'groups.index'
]);

Route::get('/dashboard', [
	'uses' => 'DashboardController@index' , 
    'as' => 'dashboard.index'
]);

Route::get('/reports/smsusage', [
	'uses' => 'AttendanceController@smsusage' , 
    'as' => 'reports.smsusage'
]);

Route::get('/reports/groupattendance', [
	'uses' => 'AttendanceController@groupattendance' , 
    'as' => 'reports.groupattendance'
]);

Route::get('/reports/studentattendance', [
	'uses' => 'AttendanceController@studentattendance' , 
    'as' => 'reports.studentattendance'
]);

Route::get('/admin/schooldetails', [
	'uses' => 'AdminController@schooldetails' , 
    'as' => 'admin.schooldetails'
]);

Route::get('/admin/users', [
	'uses' => 'AdminController@users' , 
    'as' => 'admin.users'
]);

Auth::routes();
