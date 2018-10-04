<?php




Route::resource('todo', 'TodoController')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
