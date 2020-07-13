<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', "AdminController@index");
Route::post('/admin-login', "AdminController@loginAuth");
Route::get('/logout', "AdminController@logout");

Route::get('/dashboard', "DashboardController@index");

Route::get('/add-category', "CategoryController@create");
Route::get('/add-category/{id}', "CategoryController@edit");
Route::post('/post-form-category', "CategoryController@store");
Route::post('/post-form-category/{id}', "CategoryController@update");
Route::get('/category-list', "CategoryController@index");
Route::get('/get-category/{id}', "CategoryController@getCategory");
Route::get('/delete-category/{id}', "CategoryController@deleteCategory");
Route::post('/category/change-status/{id}', "CategoryController@changeStatus");

Route::get('/add-product', "ProductController@create");
Route::get('/add-product/{id}', "ProductController@edit");
Route::post('/post-form-product', "ProductController@store");
Route::post('/post-form-product/{id}', "ProductController@update");
Route::get('/product-list', "ProductController@index");
Route::get('/get-product/{id}', "ProductController@getProduct");
Route::get('/delete-product/{id}', "ProductController@deleteProduct");
Route::post('/product/change-status/{id}', "ProductController@changeStatus");

Route::get('/add-slider', "SliderController@create");
Route::get('/add-slider/{id}', "SliderController@edit");
Route::post('/post-form-slider', "SliderController@store");
Route::post('/post-form-slider/{id}', "SliderController@update");
Route::get('/slider-list', "SliderController@index");
Route::get('/get-slider/{id}', "SliderController@getSlider");
Route::get('/delete-slider/{id}', "SliderController@deleteSlider");
Route::post('/slider/change-status/{id}', "SliderController@changeStatus");
