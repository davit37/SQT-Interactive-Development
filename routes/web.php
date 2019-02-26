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

Route::get('/', 'HomeController@index');



Route::get('/admin', 'AdminController@index')->middleware('auth')->name('admin');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('admin/users', 'UserController');

Route::resource('article', 'ArticleController');

Route::post('project-category/store', 'ProjectCategoryController@store');
Route::put('project-category/update/{id}', 'ProjectCategoryController@update');
Route::resource('project-category', 'ProjectCategoryController');


Route::get('project-slide/{id}', 'ProjectController@slide');

Route::resource('project', 'ProjectController');