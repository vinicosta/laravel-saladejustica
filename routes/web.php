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

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

// Route::group(['middleware' => 'auth'], function () {
// 	Route::get('table-list', function () {
// 		return view('pages.table_list');
// 	})->name('table');

// 	Route::get('typography', function () {
// 		return view('pages.typography');
// 	})->name('typography');

// 	Route::get('icons', function () {
// 		return view('pages.icons');
// 	})->name('icons');

// 	Route::get('map', function () {
// 		return view('pages.map');
// 	})->name('map');

// 	Route::get('notifications', function () {
// 		return view('pages.notifications');
// 	})->name('notifications');

// 	Route::get('rtl-support', function () {
// 		return view('pages.language');
// 	})->name('language');

// 	Route::get('upgrade', function () {
// 		return view('pages.upgrade');
// 	})->name('upgrade');
// });

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

    //Route::resource('issue', 'IssueController', ['except' => ['index', 'show']]);
    Route::post('issue/store', 'IssueController@store')->name('issue.store');
    Route::get('issue/{type}', 'IssueController@index');
    Route::get('issue/{type}/create', 'IssueController@create');
    Route::get('issue/{type}/show/{id}', 'IssueController@show');
    Route::get('issue/{type}/search', 'IssueController@search');
});



