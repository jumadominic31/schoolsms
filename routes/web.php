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
    'uses' => 'DashboardController@index' , 
    'as' => 'dashboard.index'
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

Route::post('/attendance/postcustommsg', [
    'uses' => 'AttendanceController@postcustommsg' , 
    'as' => 'attendance.postcustommsg'
]);

Route::get('/attendance/sendmsg', [
    'uses' => 'AttendanceController@sendmsg' , 
    'as' => 'attendance.sendmsg'
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

Route::get('/admin/msgsetup', [
    'uses' => 'AdminController@msgsetup' , 
    'as' => 'admin.msgsetup'
]);

Route::match(array('PUT', 'PATCH'), '/admin/msgsetup/update', [
    'uses' => 'AdminController@updatemsg' , 
    'as' => 'admin.updatemsg'
]);

Route::get('/admin/smsengsetup', [
    'uses' => 'AdminController@smsengsetup' , 
    'as' => 'admin.smsengsetup'
]);

Route::match(array('PUT', 'PATCH'), '/admin/smsengsetup/update', [
    'uses' => 'AdminController@updatesmseng' , 
    'as' => 'admin.updatesmseng'
]);

Auth::routes();
