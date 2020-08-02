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

    Route::get('/users', 'UserController@index')->name('users_listing');
    Route::get('/user/add', 'UserController@create')->name('user_create');
    Route::post('/user/add', 'UserController@store')->name('user_store');
    Route::get('/user/edit/{id}', 'UserController@edit')->name('user_edit');
    Route::post('/user/edit/{id}', 'UserController@update')->name('user_update');
    Route::post('/user/delete/{id}', 'UserController@destroy')->name('user_destroy');
    Route::get('/profile', 'UserController@profile')->name('profile');
    Route::post('/profile', 'UserController@update_profile')->name('profile_update');

    Route::get('/customers', 'CustomerController@index')->name('customers_listing');
    Route::get('/customer/add', 'CustomerController@create')->name('customer_create');
    Route::post('/customer/add', 'CustomerController@store')->name('customer_store');
    Route::get('/customer/edit/{id}', 'CustomerController@edit')->name('customer_edit');
    Route::post('/customer/edit/{id}', 'CustomerController@update')->name('customer_update');
    Route::post('/customer/delete/{id}', 'CustomerController@destroy')->name('customer_destroy');

    Route::get('/contacts', 'ContactController@index')->name('contacts_listing');
    Route::post('/contact/delete/{id}', 'ContactController@destroy')->name('contact_destroy');

    Route::get('/comments/{id}', 'ArticleController@get_all_comments')->name('comments_listing');
    Route::post('/comment/delete/{id}', 'ArticleController@delete_comment')->name('comment_destroy');
});
