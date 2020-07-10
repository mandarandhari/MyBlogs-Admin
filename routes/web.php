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
    return redirect('/login');
});

Auth::routes(['register' => FALSE]);

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/articles', 'ArticleController@index')->name('articles_listing');
    Route::get('/article/add', 'ArticleController@create')->name('article_create');
    Route::post('/article/add', 'ArticleController@store')->name('article_store');
    Route::get('/article/edit/{id}', 'ArticleController@edit')->name('article_edit');
    Route::post('/article/edit/{id}', 'ArticleController@update')->name('article_update');
    Route::post('/article/delete/{id}', 'ArticleController@destroy')->name('article_destroy');
});
