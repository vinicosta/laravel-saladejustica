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

// Route::get('/', function () {
//     return view('welcome');
// });
Auth::routes();

// Route::get('/teste', 'HomeController@index');

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::get('user/search/return/{type}', 'UserController@search');
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    Route::resource('genre', 'GenreController', ['except' => ['show']]);
    Route::get('genre/search/return/{type}', 'GenreController@search');

    Route::resource('subgenre', 'SubgenreController', ['except' => ['show']]);
    Route::get('subgenre/search/return/{type}/{genre_id}', 'SubgenreController@search');
    Route::get('subgenre/search/return/{type}', 'SubgenreController@search');

    Route::resource('author', 'AuthorController', ['except' => ['show']]);
    Route::get('author/search/return/{type}', 'AuthorController@search');

    Route::resource('publisher', 'PublisherController', ['except' => ['show']]);
    Route::get('publisher/search/return/{type}', 'PublisherController@search');

    Route::get('periodicity/search/return/{type}', 'PeriodicityController@search');

    Route::resource('size', 'SizeController', ['except' => ['show']]);
    Route::get('size/search/return/{type}', 'SizeController@search');
    Route::get('size/search/return/{type}/type/{id}', 'SizeController@search');

    Route::resource('issue', 'IssueController', ['except' => ['show']]);
    Route::get('issue/{type}', 'IssueController@index');
    Route::get('issue/{type}/create', 'IssueController@create');
    Route::get('issue/{type}/rand', 'IssueController@rand');
    Route::get('issue/{type}/{id}', 'IssueController@show');
    Route::get('issue/{type}/{id}/edit', 'IssueController@edit');
    Route::get('issue/{type}/{id}/delete', 'IssueController@delete');
    Route::get('issue/{type}/search/return/{index}', 'IssueController@search');

    Route::resource('title', 'TitleController', ['except' => ['show']]);
    Route::get('title/{type}/{id}', 'TitleController@show');
    Route::get('title/{type}/{id}/delete', 'TitleController@delete');
    Route::get('title/{type}/{id}/edit', 'TitleController@edit');

    Route::get('title/{type}/{id}/next', 'IssueController@next');
    Route::get('title/{type}/create/{title_id}', 'IssueController@createFromTitle');

    Route::post('reading', 'ReadingController@store');
    Route::delete('reading', 'ReadingController@destroy');

    Route::post('collection', 'CollectionController@store');
    Route::delete('collection', 'CollectionController@destroy');

    Route::post('readed', 'ReadedController@store');
    Route::delete('readed', 'ReadedController@destroy');
});



