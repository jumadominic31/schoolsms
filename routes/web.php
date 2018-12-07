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

//signin
Route::get('/users/signin', [
    'uses' => 'UsersController@getSignin',
    'as' => 'users.signin'
]);

Route::post('/users/signin', [
    'uses' => 'UsersController@postSignin',
    'as' => 'users.signin'
]);

Route::group(['middleware' => 'auth'] , function () {

    Route::get('/users/logout', [
        'uses' => 'UsersController@getLogout',
        'as' => 'users.logout'
    ]);

    Route::get('/', [
        'uses' => 'DashboardController@index' , 
        'as' => 'dashboard.index'
    ]);

    Route::get('/students', [
    	'uses' => 'StudentController@index' , 
        'as' => 'students.index'
    ]);

    Route::post('/students', [
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

    Route::get('/school', [
    	'uses' => 'AdminController@schooldetails' , 
        'as' => 'school.index'
    ]);

    Route::get('/school/edit', [
        'uses' => 'AdminController@editschool' , 
        'as' => 'school.edit'
    ]);

    Route::match(array('PUT', 'PATCH'), '/school/update', [
        'uses' => 'AdminController@updateschool' , 
        'as' => 'school/update'
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

    //users
    Route::get('/users/profile', [
        'uses' => 'UsersController@getProfile',
        'as' => 'users.profile'
    ]);
    
    //users admin
    Route::resource('users', 'UsersController');
});
